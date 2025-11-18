<?php
if (!defined('ABSPATH')) exit;

/** DEBUG SWITCH */
define('AW_WEBP_DEBUG', true);
define('AW_WEBP_QUALITY', 78);

/** Logger */
function aw_log($msg, $ctx = [])
{
    if (!AW_WEBP_DEBUG) return;
    $prefix = '[aw-webp] ';
    if (!empty($ctx)) $msg .= ' ' . json_encode($ctx, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    error_log($prefix . $msg);
}

/** Helpers */
function aw_webp_supported_by_editor(): bool
{
    $ok = function_exists('wp_image_editor_supports') && wp_image_editor_supports(['mime_type' => 'image/webp']);
    aw_log('webp_supported_by_editor', ['result' => $ok]);
    return $ok;
}

function aw_abs_from_upload_url($url)
{
    $upload = wp_get_upload_dir();
    if (strpos($url, $upload['baseurl']) !== 0) return null;
    $rel = substr($url, strlen($upload['baseurl']));
    return $upload['basedir'] . $rel;
}

/** 0) MIME */
add_filter('upload_mimes', function ($m) {
    $m['webp'] = 'image/webp';
    aw_log('filter:upload_mimes');
    return $m;
});

/** 0.1) Loguj start obsługi uploadu */
add_filter('wp_handle_upload', function ($info) {
    aw_log('wp_handle_upload', $info);
    return $info;
});

/** 1) Zapis .webp przez WP_Image_Editor + twardy fallback na Imagick */
function aw_save_as_webp($src_abs, $dest_abs, $quality = AW_WEBP_QUALITY)
{
    aw_log('save:start', ['src' => $src_abs, 'dest' => $dest_abs]);

    if (!file_exists($src_abs)) {
        aw_log('save:src_not_exists', ['src' => $src_abs]);
        return false;
    }
    if (file_exists($dest_abs)) {
        aw_log('save:already_exists', ['dest' => $dest_abs]);
        return true;
    }

    // 1A) Core editor
    if (aw_webp_supported_by_editor()) {
        $editor = wp_get_image_editor($src_abs);
        if (is_wp_error($editor)) {
            aw_log('save:editor_error', ['error' => $editor->get_error_message()]);
        } else {
            if (method_exists($editor, 'set_quality')) $editor->set_quality((int)$quality);
            $saved = $editor->save($dest_abs, 'image/webp');
            if (!is_wp_error($saved) && !empty($saved['path']) && file_exists($saved['path'])) {
                aw_log('save:editor_ok', ['path' => $saved['path']]);
                return true;
            }
            aw_log('save:editor_failed', ['saved' => is_wp_error($saved) ? $saved->get_error_message() : $saved]);
        }
    } else {
        aw_log('save:editor_no_webp_support');
    }

    // 1B) Fallback: czysty Imagick (bywa, że wp_image_editor_supports() zwraca false na niektórych buildach)
    if (class_exists('Imagick')) {
        try {
            $im = new Imagick($src_abs);
            $im->setImageFormat('webp');
            $im->setImageCompressionQuality((int)$quality);
            if (method_exists($im, 'setOption')) {
                $im->setOption('webp:method', '6');
                $im->setOption('webp:lossless', 'false');
            }
            $ok = $im->writeImage($dest_abs);
            $im->clear();
            $im->destroy();
            aw_log('save:imagick_result', ['ok' => $ok, 'exists' => file_exists($dest_abs)]);
            return $ok && file_exists($dest_abs);
        } catch (\Throwable $e) {
            aw_log('save:imagick_exception', ['msg' => $e->getMessage()]);
        }
    } else {
        aw_log('save:no_imagick_class');
    }

    // 1C) Ostateczny fallback: GD
    if (function_exists('imagewebp')) {
        $ext = strtolower(pathinfo($src_abs, PATHINFO_EXTENSION));
        $img = false;
        if (in_array($ext, ['jpg', 'jpeg'], true)) $img = @imagecreatefromjpeg($src_abs);
        if ($ext === 'png') {
            $img = @imagecreatefrompng($src_abs);
            if ($img) {
                imagepalettetotruecolor($img);
                imagealphablending($img, false);
                imagesavealpha($img, true);
            }
        }
        if (!$img) {
            aw_log('save:gd_open_failed', ['ext' => $ext]);
            return false;
        }
        $ok = imagewebp($img, $dest_abs, (int)$quality);
        imagedestroy($img);
        aw_log('save:gd_result', ['ok' => $ok, 'exists' => file_exists($dest_abs)]);
        return $ok && file_exists($dest_abs);
    }
    aw_log('save:no_gd');
    return false;
}

/** 2) Generacja .webp po uploadzie (oryginał + każdy rozmiar) */
add_filter('wp_generate_attachment_metadata', function ($meta, $attachment_id) {
    try {
        $file = get_attached_file($attachment_id);
        aw_log('metadata:start', ['id' => $attachment_id, 'file' => $file]);
        if (!$file) return $meta;

        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png'], true)) {
            aw_log('metadata:skip_ext', ['ext' => $ext]);
            return $meta;
        }

        $dir  = dirname($file);
        $name = pathinfo($file, PATHINFO_FILENAME);

        // Oryginał
        $webp_orig = $dir . '/' . $name . '.webp';
        $ok_orig   = aw_save_as_webp($file, $webp_orig, AW_WEBP_QUALITY);

        // Rozmiary
        $ok_sizes = [];
        if (!empty($meta['sizes']) && is_array($meta['sizes'])) {
            foreach ($meta['sizes'] as $size_key => $size) {
                if (empty($size['file'])) continue;
                $size_abs = $dir . '/' . $size['file'];
                if (strtolower(pathinfo($size_abs, PATHINFO_EXTENSION)) === 'webp') {
                    $ok_sizes[$size_key] = 'already_webp';
                    continue;
                }
                $noext     = pathinfo($size_abs, PATHINFO_FILENAME);
                $webp_size = dirname($size_abs) . '/' . $noext . '.webp';
                $ok_sizes[$size_key] = aw_save_as_webp($size_abs, $webp_size, AW_WEBP_QUALITY);
            }
        }
        $meta['aw_webp_generated'] = ['orig' => $ok_orig, 'sizes' => $ok_sizes];
        aw_log('metadata:done', $meta['aw_webp_generated']);
    } catch (\Throwable $e) {
        aw_log('metadata:exception', ['msg' => $e->getMessage()]);
        // nie przerywamy uploadu
    }
    return $meta;
}, 10, 2);

add_filter('wp_get_attachment_image_url', function ($url, $attachment_id, $size, $icon) {
    if (!$url) return $url;
    $ext = strtolower(pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION));
    if (!in_array($ext, ['jpg', 'jpeg', 'png'], true)) return $url;

    $upload = wp_get_upload_dir();
    if (strpos($url, $upload['baseurl']) !== 0) return $url;

    $rel = substr($url, strlen($upload['baseurl']));
    $abs = $upload['basedir'] . $rel;
    $abs_webp = preg_replace('/\.(jpe?g|png)$/i', '.webp', $abs);
    if (file_exists($abs_webp)) {
        return preg_replace('/\.(jpe?g|png)$/i', '.webp', $url);
    }
    return $url;
}, 10, 4);

// --- RELAXED: zamień URL uploads na ścieżkę plikową, ignorując scheme/host ---
function aw_abs_from_upload_url_relaxed($url)
{
    $upload = wp_get_upload_dir();

    // Weź tylko część ścieżkową obu adresów
    $url_path    = wp_parse_url($url, PHP_URL_PATH);
    $baseurl_path = wp_parse_url($upload['baseurl'], PHP_URL_PATH);

    if (!$url_path || !$baseurl_path) return null;

    // Upewnij się o trailing slash dla porównania
    $baseurl_path = trailingslashit($baseurl_path);

    // Jeśli URL pliku zaczyna się od ścieżki uploadów, odetnij ją i sklej z basedir
    if (strpos($url_path, $baseurl_path) === 0) {
        $rel = substr($url_path, strlen($baseurl_path)); // np. 2025/08/plik.jpg
        return trailingslashit($upload['basedir']) . $rel;
    }

    // Fallback: spróbuj prostego podstawienia całej bazy
    $abs = str_replace(trailingslashit($upload['baseurl']), trailingslashit($upload['basedir']), $url);
    // Normalizuj backslashe na Windowsie
    if ($abs && file_exists(wp_normalize_path($abs))) {
        return wp_normalize_path($abs);
    }
    return null;
}

// —> użyj tego helpera w obu filtrach podmiany URL

add_filter('wp_get_attachment_image_src', function ($image, $attachment_id) {
    if (!is_array($image) || empty($image[0])) return $image;
    $url = $image[0];
    $ext = strtolower(pathinfo(wp_parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION));
    if (!in_array($ext, ['jpg', 'jpeg', 'png'], true)) return $image;

    $abs = aw_abs_from_upload_url_relaxed($url);
    if (!$abs) return $image;

    $abs_webp = preg_replace('/\.(jpe?g|png)$/i', '.webp', $abs);
    if (file_exists($abs_webp)) {
        $image[0] = preg_replace('/\.(jpe?g|png)$/i', '.webp', $url);
    }
    return $image;
}, 10, 2);

add_filter('wp_get_attachment_image_url', function ($url) {
    if (!$url) return $url;
    $ext = strtolower(pathinfo(wp_parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION));
    if (!in_array($ext, ['jpg', 'jpeg', 'png'], true)) return $url;

    $abs = aw_abs_from_upload_url_relaxed($url);
    if (!$abs) return $url;

    $abs_webp = preg_replace('/\.(jpe?g|png)$/i', '.webp', $abs);
    if (file_exists($abs_webp)) {
        return preg_replace('/\.(jpe?g|png)$/i', '.webp', $url);
    }
    return $url;
}, 10, 1);

add_filter('wp_calculate_image_srcset', function ($sources) {
    if (!is_array($sources)) return $sources;
    foreach ($sources as &$s) {
        if (empty($s['url'])) continue;
        $url = $s['url'];
        $ext = strtolower(pathinfo(wp_parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png'], true)) continue;

        $abs = aw_abs_from_upload_url_relaxed($url);
        if (!$abs) continue;

        $abs_webp = preg_replace('/\.(jpe?g|png)$/i', '.webp', $abs);
        if (file_exists($abs_webp)) {
            $s['url']  = preg_replace('/\.(jpe?g|png)$/i', '.webp', $url);
            $s['type'] = 'image/webp';
        }
    }
    return $sources;
}, 10, 1);
function aw_srcset_from_meta(int $attachment_id, bool $prefer_webp = true): string
{
    $meta = wp_get_attachment_metadata($attachment_id);
    if (!$meta || empty($meta['file'])) return '';

    $uploads  = wp_get_upload_dir();
    $baseurl  = trailingslashit($uploads['baseurl']) . trailingslashit(dirname($meta['file']));
    $original = $uploads['baseurl'] . '/' . $meta['file'];

    $parts = [];

    // kandydaci z metadanych
    if (!empty($meta['sizes']) && is_array($meta['sizes'])) {
        foreach ($meta['sizes'] as $info) {
            if (empty($info['file']) || empty($info['width'])) continue;
            $url = $baseurl . $info['file'];
            if ($prefer_webp) {
                $maybe_webp = preg_replace('/\.(jpe?g|png|gif)$/i', '.webp', $url);
                if ($maybe_webp && @fopen($maybe_webp, 'r')) $url = $maybe_webp;
            }
            $parts[$info['width']] = esc_url($url) . ' ' . (int)$info['width'] . 'w';
        }
    }

    // dodaj oryginał jako największy
    if (!empty($meta['width'])) {
        $url = $original;
        if ($prefer_webp) {
            $maybe_webp = preg_replace('/\.(jpe?g|png|gif|webp)$/i', '.webp', $original);
            if ($maybe_webp && @fopen($maybe_webp, 'r')) $url = $maybe_webp;
        }
        $parts[(int)$meta['width']] = esc_url($url) . ' ' . (int)$meta['width'] . 'w';
    }

    ksort($parts, SORT_NUMERIC);
    return implode(', ', array_values($parts));
}
