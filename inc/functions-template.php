<?php

/**
 * Custom functions
 *
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

function my_custom_attachment_image(int $attachment_id, array $args = []): string
{
  if ($attachment_id <= 0 || !get_post($attachment_id)) {
    return '';
  }

  $defaults = [
    'size'     => 'large',
    'class'    => null,
    'priority' => false,
    'sizes'    => '100vw',
    'attrs'    => [],
    'preload'  => false,
    'icon'     => null, // auto
  ];
  $args = array_merge($defaults, $args);

  // ALT z metadanych lub tytuł załącznika
  $alt = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
  if (!is_string($alt) || trim($alt) === '') {
    $alt = get_the_title($attachment_id) ?: '';
  }

  // Atrybuty <img>
  $attrs = array_merge([
    'alt'      => $alt,
    'sizes'    => $args['sizes'],
    'decoding' => 'async',
  ], $args['attrs']);

  if ($args['class']) {
    $attrs['class'] = $args['class'];
  }

  if ($args['priority']) {
    // LCP / priorytet
    $attrs['loading'] = 'eager';
    $attrs['fetchpriority'] = 'high';
  } else {
    $attrs['loading'] = 'lazy';
  }

  // Ikona typu MIME, jeśli to nie obraz
  $icon = $args['icon'];
  if ($icon === null) {
    $icon = !wp_attachment_is_image($attachment_id);
  }

  // Opcjonalny preload (tylko dla obrazów)
  if ($args['preload'] && !$icon) {
    my_enqueue_image_preload_once($attachment_id, $args['size'], $attrs['sizes']);
  }

  // Render
  $html = wp_get_attachment_image($attachment_id, $args['size'], $icon, $attrs);
  return $html ?: '';
}

/**
 * Dodaje <link rel="preload" as="image"> dla danego rozmiaru obrazu.
 * Zapobiega duplikatom i wypisuje linki raz w <head>.
 */
function my_enqueue_image_preload_once(int $attachment_id, $size = 'large', string $sizes = '100vw'): void
{
  static $queued = [];
  $img = wp_get_attachment_image_src($attachment_id, $size);
  if (!$img || empty($img[0])) return;

  $url = $img[0];
  if (isset($queued[$url])) return;

  $srcset = wp_get_attachment_image_srcset($attachment_id, $size);
  $queued[$url] = ['url' => $url, 'srcset' => $srcset, 'sizes' => $sizes];

  // Wypisz tylko raz
  add_action('wp_head', function () use (&$queued) {
    foreach ($queued as $item) {
      printf(
        '<link rel="preload" as="image" href="%s"%s%s fetchpriority="high">' . "\n",
        esc_url($item['url']),
        $item['srcset'] ? ' imagesrcset="' . esc_attr($item['srcset']) . '"' : '',
        $item['sizes']  ? ' imagesizes="' . esc_attr($item['sizes']) . '"'  : ''
      );
    }
    $queued = [];
  }, 1);
}
function show_title($tag, $text_title, $color_text = null, $class_title = null)
{
  $text_color = $color_text !== null && !empty($color_text) ? "style='color: $color_text;'" : "";
  $cls = $class_title !== null ? "class='{$class_title}'" : "";
  if (!empty($text_title)) {
    echo <<<TITLE
    <div class="title-block-ps">
    <$tag $cls $text_color>$text_title</$tag>
    </div>
    TITLE;
  }
}

function create_button_block($link, $class_btn = null)
{
  if (!empty($link)) {
    $class = $class_btn !== null ? "btn-" . $class_btn : "";
    $link_url = $link['url'];
    $link_title = $link['title'];
    $link_target = $link['target'] ? $link['target'] : '_self'; ?>
    <a class="btn <?php echo $class ?>" href="<?php echo esc_url($link_url); ?>"
      target="<?php echo esc_attr($link_target); ?>">
      <?php echo esc_html($link_title); ?>
    </a>
<?php
  }
}
function aw_svg($source): string
{
  static $cache = [];

  // Obsługa tylko attachment ID (u Ciebie ten case)
  if (!is_numeric($source)) {
    return '';
  }

  $id = (int) $source;
  if ($id <= 0) {
    return '';
  }

  if (isset($cache[$id])) {
    return $cache[$id];
  }

  $mime = get_post_mime_type($id);
  if ($mime !== 'image/svg+xml') {
    return $cache[$id] = '';
  }

  // 1) Najlepiej: plik z dysku
  $file_path = get_attached_file($id); // pełna ścieżka w filesystem
  if ($file_path && is_string($file_path) && file_exists($file_path) && is_readable($file_path)) {
    $svg = file_get_contents($file_path);
  } else {
    // 2) Fallback: pobierz po HTTP, ale bez warningów i z timeoutem
    $url = wp_get_attachment_url($id);
    if (!$url) {
      return $cache[$id] = '';
    }

    $res = wp_remote_get($url, [
      'timeout'   => 5,
      'sslverify' => false, // na local czasem pomaga; na produkcji możesz usunąć
    ]);

    if (is_wp_error($res)) {
      return $cache[$id] = '';
    }

    $code = (int) wp_remote_retrieve_response_code($res);
    if ($code < 200 || $code >= 300) {
      return $cache[$id] = '';
    }

    $svg = (string) wp_remote_retrieve_body($res);
  }

  if (empty($svg) || !is_string($svg)) {
    return $cache[$id] = '';
  }

  // Minimalna sanitizacja
  $svg = preg_replace('/<script\b[^>]*>.*?<\/script>/is', '', $svg);
  $svg = preg_replace('/\son\w+=(["\']).*?\1/is', '', $svg);

  // Opcjonalnie: usuń XML/DOCTYPE (czasem psuje HTML)
  $svg = preg_replace('/<\?xml.*?\?>/is', '', $svg);
  $svg = preg_replace('/<!DOCTYPE.*?>/is', '', $svg);

  return $cache[$id] = $svg;
}
