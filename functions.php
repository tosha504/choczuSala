<?php

/**
 * start functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package start
 */

if (!defined('_S_VERSION')) {
	// Replace the version number of the theme on each release.
	define('_S_VERSION', '1.0.0');
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function start_setup()
{
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on start, use a find and replace
		* to change 'start' to the name of your theme in all the template files.
		*/
	load_theme_textdomain('start', get_template_directory() . '/languages');

	// Add default posts and comments RSS feed links to head.
	add_theme_support('automatic-feed-links');

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support('title-tag');

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support('post-thumbnails');

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-header' => esc_html__('Header menu', 'start'),
			'menu-foot-info' => esc_html__('Info footer menu', 'start'),
			'menu-foot-cat' => esc_html__('Category footer menu', 'start'),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'start_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support('customize-selective-refresh-widgets');

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height' => 250,
			'width' => 250,
			'flex-width' => true,
			'flex-height' => true,
		)
	);
}
add_action('after_setup_theme', 'start_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function start_content_width()
{
	$GLOBALS['content_width'] = apply_filters('start_content_width', 640);
}
add_action('after_setup_theme', 'start_content_width', 0);

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function start_widgets_init()
{
	register_sidebar(
		array(
			'name' => esc_html__('Sidebar', 'start'),
			'id' => 'sidebar-1',
			'description' => esc_html__('Add widgets here.', 'start'),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget' => '</section>',
			'before_title' => '<h2 class="widget-title">',
			'after_title' => '</h2>',
		)
	);
	register_sidebar(
		array(
			'name' => esc_html__('Sidebar-lang', 'start'),
			'id' => 'lang',
			'description' => esc_html__('Add widgets here.', 'start'),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget' => '</section>',
			'before_title' => '',
			'after_title' => '',
		)
	);
	register_sidebar([
		'name' => 'The left sidebar of the shop',
		'id' => 'left-sidebar',
		'description' => 'These widgets will be shown in the right column of the site',
		'before_title' => '<h2>',
		'after_title' => '</h2>'
	]);
}
add_action('widgets_init', 'start_widgets_init');

/**
 * Disable Gutenberg
 */
// add_filter('use_block_editor_for_post', '__return_false');

// Theme includes directory.
$realestate_inc_dir = 'inc';

// Array of files to include.
$realestate_includes = array(
	'/functions-template.php',  // 	Theme custom functions
	'/enqueue.php',				//	Enqueue scripts and styles.
	'/custom-header.php',		//	Implement the Custom Header feature.
	'/customizer.php',			//	Customizer additions.
	'/template-tags.php',		// 	Custom template tags for this theme.
	'/template-functions.php',	//	Functions which enhance the theme by hooking into WordPress.
	'/install-plugin-formthis-theme.php',
	'/webp.php',
	'/popuplang.php',

);

// Load WooCommerce functions if WooCommerce is activated.
if (class_exists('WooCommerce')) {
	$realestate_includes[] = '/as-woocommerce.php';
}
if (class_exists('ACF')) {
	$realestate_includes = array_merge($realestate_includes, [
		'/blocks.php',
		'/acf/options-pages.php',
		'/acf/acf-json.php',
	]);
}

// Include files.
foreach ($realestate_includes as $file) {
	require_once get_theme_file_path($realestate_inc_dir . $file);
}

require_once dirname(__FILE__) . '/inc/class-tgm-plugin-activation.php';


add_action('after_switch_theme', function () {
	$plugins = [
		'contact-form-7',
		'query-monitor',
		'seo-by-rank-math',
	];

	include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
	include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
	include_once ABSPATH . 'wp-admin/includes/file.php';
	include_once ABSPATH . 'wp-admin/includes/misc.php';
	include_once ABSPATH . 'wp-admin/includes/plugin.php';

	$upgrader = new Plugin_Upgrader();

	foreach ($plugins as $slug) {
		if (!is_dir(WP_PLUGIN_DIR . "/$slug")) {
			$api = plugins_api('plugin_information', ['slug' => $slug, 'fields' => ['sections' => false]]);
			$upgrader->install($api->download_link);
		}

		$plugin_file = get_plugins("/$slug");
		$plugin_main_file = key($plugin_file);

		if (!is_plugin_active("$slug/$plugin_main_file")) {
			activate_plugin("$slug/$plugin_main_file");
		}
	}
});

function cc_mime_types($mimes)
{
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');


add_filter('intermediate_image_sizes_advanced', function ($sizes) {
	return [
		// Miniatury WP (dla widgetów, bloga itp.)
		'thumbnail'       => ['width' => 300, 'height' => 300, 'crop' => true],
		'medium'          => ['width' => 600, 'height' => 600, 'crop' => false],
		'medium_large'    => ['width' => 768, 'height' => 768, 'crop' => false],
		'large'           => ['width' => 1200, 'height' => 1200, 'crop' => false],

		// WooCommerce
		'woocommerce_thumbnail'         => ['width' => 600, 'height' => 600, 'crop' => true],
		'woocommerce_single'            => ['width' => 1200, 'height' => 1200, 'crop' => false],
		'woocommerce_gallery_thumbnail' => ['width' => 150, 'height' => 150, 'crop' => true],
	];
}, 10, 1);

/** 3) Podmiana URL-i JPG/PNG → WebP */
add_filter('wp_get_attachment_image_src', function ($image) {
	if (!is_array($image) || empty($image[0])) return $image;
	$url = $image[0];
	$ext = strtolower(pathinfo(wp_parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION));
	if (!in_array($ext, ['jpg', 'jpeg', 'png'], true)) return $image;

	$abs = aw_abs_from_upload_url_relaxed($url);
	if ($abs && file_exists(preg_replace('/\.(jpe?g|png)$/i', '.webp', $abs))) {
		$image[0] = preg_replace('/\.(jpe?g|png)$/i', '.webp', $url);
	}
	return $image;
}, 10, 1);

// Bezpieczna podmiana <img> w "the_content" na .webp (tylko dla uploadów z mediów)
add_filter('the_content', function ($html) {
	if (empty($html) || stripos($html, '<img') === false) return $html;

	// Szybka funkcja: jeśli istnieje .webp dla danego URL z uploadów – zwróć go
	$to_webp = function ($url) {
		if (!$url) return $url;
		// tylko nasze uploady
		$abs = aw_abs_from_upload_url_relaxed($url);
		if (!$abs) return $url;
		$abs_webp = preg_replace('/\.(jpe?g|png)$/i', '.webp', $abs);
		if ($abs_webp && file_exists($abs_webp)) {
			return preg_replace('/\.(jpe?g|png)$/i', '.webp', $url);
		}
		return $url;
	};

	// Użyj DOMDocument – mniej kruche niż regex na HTML
	$internal_errors = libxml_use_internal_errors(true);
	$doc = new DOMDocument();
	// wrapper, żeby DOM nie dodawał <html><body>
	$doc->loadHTML('<?xml encoding="utf-8" ?><div id="__wrap__">' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
	$wrap = $doc->getElementById('__wrap__');
	if (!$wrap) {
		libxml_use_internal_errors($internal_errors);
		return $html;
	}

	/** @var DOMNodeList $imgs */
	$imgs = $wrap->getElementsByTagName('img');
	// Iterujemy „od końca” (DOMNodeList jest live)
	for ($i = $imgs->length - 1; $i >= 0; $i--) {
		/** @var DOMElement $img */
		$img = $imgs->item($i);
		if (!$img->hasAttribute('src')) continue;

		// src -> webp (jeśli jest)
		$src = html_entity_decode($img->getAttribute('src'));
		$new = $to_webp($src);
		if ($new !== $src) {
			$img->setAttribute('src', $new);
			// często mamy też data-src / data-lazy-src
			foreach (['data-src', 'data-lazy-src'] as $attr) {
				if ($img->hasAttribute($attr)) {
					$val = html_entity_decode($img->getAttribute($attr));
					$img->setAttribute($attr, $to_webp($val));
				}
			}
		}

		// srcset -> podmień każdy URL z osobna
		if ($img->hasAttribute('srcset')) {
			$srcset = html_entity_decode($img->getAttribute('srcset'));
			// srcset to lista "url width-descriptor"
			$parts = array_map('trim', explode(',', $srcset));
			$out   = [];
			foreach ($parts as $p) {
				if ($p === '') continue;
				// rozbij "URL [x|w]"
				if (preg_match('~^(\S+)(\s+\d+[wx])?$~', $p, $m)) {
					$u = $m[1];
					$d = isset($m[2]) ? $m[2] : '';
					$out[] = $to_webp($u) . $d;
				} else {
					$out[] = $p; // jak nie pasuje, zostaw oryginał
				}
			}
			if ($out) $img->setAttribute('srcset', implode(', ', $out));
		}
	}

	$out = '';
	foreach ($wrap->childNodes as $n) {
		$out .= $doc->saveHTML($n);
	}
	libxml_use_internal_errors($internal_errors);
	return $out;
}, 11);
add_filter('woocommerce_enable_order_notes_field', '__return_false');


/**
 * Tłumaczenie stron WooCommerce (shop, cart, checkout, my-account, terms)
 * dla Polylang FREE.
 */
function aw_pll_translate_wc_page_id($page_id)
{
	if (function_exists('pll_get_post') && $page_id) {
		$translated = pll_get_post($page_id);
		if ($translated) {
			return $translated;
		}
	}

	return $page_id;
}
add_filter('option_woocommerce_shop_page_id', 'aw_pll_translate_wc_page_id');
add_filter('option_woocommerce_cart_page_id', 'aw_pll_translate_wc_page_id');
add_filter('option_woocommerce_checkout_page_id', 'aw_pll_translate_wc_page_id');
add_filter('option_woocommerce_myaccount_page_id', 'aw_pll_translate_wc_page_id');
add_filter('option_woocommerce_terms_page_id', 'aw_pll_translate_wc_page_id');


// add_action('woocommerce_before_shop_loop', function () {
// 	if (!is_shop() && !is_product_taxonomy()) return;

// 	// Pobierz kategorie najwyższego poziomu (zmień parent jeśli chcesz konkretne)
// 	$terms = get_terms([
// 		'taxonomy'   => 'product_cat',
// 		'hide_empty' => true,
// 		'parent'     => 0,
// 		'orderby'    => 'menu_order',
// 		'order'      => 'ASC',
// 		'number'     => 24, // limit – zmień wg potrzeb
// 	]);

// 	if (is_wp_error($terms) || empty($terms)) return;

// 	echo '<section class="aw-cat-carousel" aria-label="Kategorie produktów">';
// 	echo '  <div class="aw-cat-carousel__head">';
// 	echo '    <h2 class="aw-cat-carousel__title">Kategorie</h2>';
// 	echo '    <div class="aw-cat-carousel__nav">';
// 	echo '      <button class="swiper-button-prev" aria-label="Poprzednie"></button>';
// 	echo '      <button class="swiper-button-next" aria-label="Następne"></button>';
// 	echo '    </div>';
// 	echo '  </div>';

// 	echo '  <div class="swiper">';
// 	echo '    <div class="swiper-wrapper">';

// 	foreach ($terms as $term) {
// 		$thumb_id = get_term_meta($term->term_id, 'thumbnail_id', true);
// 		$img_html = '';

// 		if ($thumb_id) {
// 			// Lepsze obrazki: srcset/sizes + lazy
// 			$img_html = wp_get_attachment_image(
// 				$thumb_id,
// 				'medium',
// 				false,
// 				[
// 					'class'         => 'aw-cat__img',
// 					'loading'       => 'lazy',
// 					'decoding'      => 'async',
// 					'fetchpriority' => 'low',
// 					'alt'           => esc_attr($term->name),
// 				]
// 			);
// 		} else {
// 			// Placeholder Woo
// 			$src = wc_placeholder_img_src('woocommerce_thumbnail');
// 			$img_html = '<img class="aw-cat__img" src="' . esc_url($src) . '" alt="' . esc_attr($term->name) . '" loading="lazy" decoding="async">';
// 		}

// 		$link = get_term_link($term);
// 		if (is_wp_error($link)) continue;

// 		echo '      <div class="swiper-slide">';
// 		echo '        <a class="aw-cat" href="' . esc_url($link) . '">';
// 		echo '          <span class="aw-cat__circle">' . $img_html . '</span>';
// 		echo '          <span class="aw-cat__name">' . esc_html($term->name) . '</span>';
// 		echo '          <span class="aw-cat__count">' . intval($term->count) . ' ' . _n('produkt', 'produkty', $term->count, 'thenewlook') . '</span>';
// 		echo '        </a>';
// 		echo '      </div>';
// 	}

// 	echo '    </div>'; // .swiper-wrapper
// 	echo '  </div>';   // .swiper

// 	echo '</section>';
// }, 5);
add_action('acf/init', 'aw_register_multilang_options_pages');

function aw_register_multilang_options_pages(): void
{
	static $done = false;
	if ($done) return;
	$done = true;

	if (!function_exists('acf_add_options_page')) return;

	$langs = function_exists('pll_languages_list')
		? pll_languages_list(['fields' => 'slug'])
		: ['pl'];

	foreach ($langs as $lang) {
		acf_add_options_page([
			'page_title' => "Ustawienia ({$lang})",
			'menu_title' => "Ustawienia {$lang}",
			'menu_slug'  => "theme-options-{$lang}",
			'post_id'    => "theme_options_{$lang}",
			'redirect'   => false,
			'capability' => 'manage_options',
		]);
	}
}
/**
 * Zwraca aktualny slug języka (Polylang) lub 'pl' jako domyślny.
 */
function aw_get_current_lang(): string
{

	// 1. Jeśli Polylang nie istnieje → ustaw domyślny język
	if (!function_exists('pll_current_language')) {
		return 'pl';
	}

	/**
	 * 2. Próba pobrania języka w normalnym kontekście (front)
	 */
	$lang = pll_current_language('slug');
	if ($lang) {
		return $lang;
	}

	/**
	 * 3. Admin — jeśli edytujemy jakiegoś posta
	 */
	if (is_admin() && isset($_GET['post'])) {
		$post_id = intval($_GET['post']);
		$post_lang = pll_get_post_language($post_id, 'slug');
		if ($post_lang) {
			return $post_lang;
		}
	}

	/**
	 * 4. AJAX – Polylang często nie ustawia języka
	 */
	if (defined('DOING_AJAX') && DOING_AJAX) {
		// Spróbuj pobrać z parametru ?lang=xx
		if (!empty($_REQUEST['lang'])) {
			return sanitize_text_field($_REQUEST['lang']);
		}
		// fallback do domyślnego języka PL
		return pll_default_language('slug');
	}

	/**
	 * 5. Ostateczny fallback — domyślny język Polylang
	 */
	$default = pll_default_language('slug');
	return $default ?: 'pl';
}
function aw_get_options_post_id(?string $lang = null): string
{
	$lang = $lang ?: aw_get_current_lang();
	return "theme_options_{$lang}";
}

/**
 * Skrót do get_field() z odpowiednim post_id dla języka.
 */
function aw_get_option(string $field_name, ?string $lang = null)
{
	$post_id = aw_get_options_post_id($lang);
	return get_field($field_name, $post_id);
}
/**
 * Plugin Name: AW Weight Products
 * Description: Sprzedaż na wagę z ceną za kg i dowolną ilością gramów + live podgląd ceny.
 * Author: ArtiWeb
 */

function mb_ai1wm_exclude_node_modules($exclude_filters)
{
	$exclude_filters[] = 'hochusala/node_modules';
	return $exclude_filters;
}

add_filter('ai1wm_exclude_themes_from_export', 'mb_ai1wm_exclude_node_modules');

add_action('init', function () {
	if (isset($_GET['aw_test_sku'])) {


		$sku = sanitize_text_field($_GET['aw_test_sku']);

		$id = wc_get_product_id_by_sku($sku);

		echo "<h2>Test SKU</h2>";
		echo "Szukane SKU: <strong>{$sku}</strong><br>";
		echo "Znaleziony product_id: <strong>{$id}</strong><br>";

		if ($id) {
			$post = get_post($id);
			echo "<pre>";
			echo "post_type: {$post->post_type}\n";
			echo "post_status: {$post->post_status}\n";
			echo "post_title: {$post->post_title}\n";
			echo "</pre>";
		}

		exit;
	}
});


add_action('after_setup_theme', function () {
	// 720x720 dla retina (2x), przy docelowej szerokości ~360px
	add_image_size('aw_card_1x1', 720, 720, true); // hard crop
});
/**
 * Nie transliteruj wewnętrznych kluczy ACF (group_ / field_) i nie ruszaj ACF w adminie.
 */

function aw_is_acf_internal_slug(string $s): bool
{
	return (bool) preg_match('/^(group|field)_[a-z0-9]+$/i', $s);
}

function aw_should_skip_slug_transliteration(string $title, string $raw_title = '', string $context = 'display'): bool
{
	// 1) Nie ruszaj kluczy ACF
	if (aw_is_acf_internal_slug($title) || ($raw_title && aw_is_acf_internal_slug($raw_title))) {
		return true;
	}

	// 2) W adminie: jeśli zapis dotyczy ACF Field Group / Field – nie ruszaj
	if (is_admin()) {
		$post_type = $_POST['post_type'] ?? '';
		if (in_array($post_type, ['acf-field-group', 'acf-field'], true)) {
			return true;
		}

		// ACF często wysyła swoje payloady bez klasycznego post_type,
		// ale ma charakterystyczne pola w POST.
		if (isset($_POST['acf_field_group']) || isset($_POST['acf_fields'])) {
			return true;
		}
	}

	return false;
}
/**
 * 1) Transliteration funkcja bazowa (UA/RU/PL -> ASCII)
 */
function aw_slug_transliterate(string $str): string
{
	$str = trim($str);
	if ($str === '') return 'item';

	// PL diakrytyki (dla pewności)
	$map = [
		'ą' => 'a',
		'ć' => 'c',
		'ę' => 'e',
		'ł' => 'l',
		'ń' => 'n',
		'ó' => 'o',
		'ś' => 's',
		'ż' => 'z',
		'ź' => 'z',
		'Ą' => 'a',
		'Ć' => 'c',
		'Ę' => 'e',
		'Ł' => 'l',
		'Ń' => 'n',
		'Ó' => 'o',
		'Ś' => 's',
		'Ż' => 'z',
		'Ź' => 'z',
	];

	// UA/RU cyrlica -> łacina (praktyczny mapping SEO)
	$map += [
		'А' => 'a',
		'Б' => 'b',
		'В' => 'v',
		'Г' => 'h',
		'Ґ' => 'g',
		'Д' => 'd',
		'Е' => 'e',
		'Ё' => 'yo',
		'Є' => 'ye',
		'Ж' => 'zh',
		'З' => 'z',
		'И' => 'y',
		'І' => 'i',
		'Ї' => 'yi',
		'Й' => 'y',
		'К' => 'k',
		'Л' => 'l',
		'М' => 'm',
		'Н' => 'n',
		'О' => 'o',
		'П' => 'p',
		'Р' => 'r',
		'С' => 's',
		'Т' => 't',
		'У' => 'u',
		'Ф' => 'f',
		'Х' => 'kh',
		'Ц' => 'ts',
		'Ч' => 'ch',
		'Ш' => 'sh',
		'Щ' => 'shch',
		'Ъ' => '',
		'Ы' => 'y',
		'Ь' => '',
		'Э' => 'e',
		'Ю' => 'yu',
		'Я' => 'ya',
		'а' => 'a',
		'б' => 'b',
		'в' => 'v',
		'г' => 'h',
		'ґ' => 'g',
		'д' => 'd',
		'е' => 'e',
		'ё' => 'yo',
		'є' => 'ye',
		'ж' => 'zh',
		'з' => 'z',
		'и' => 'y',
		'і' => 'i',
		'ї' => 'yi',
		'й' => 'y',
		'к' => 'k',
		'л' => 'l',
		'м' => 'm',
		'н' => 'n',
		'о' => 'o',
		'п' => 'p',
		'р' => 'r',
		'с' => 's',
		'т' => 't',
		'у' => 'u',
		'ф' => 'f',
		'х' => 'kh',
		'ц' => 'ts',
		'ч' => 'ch',
		'ш' => 'sh',
		'щ' => 'shch',
		'ъ' => '',
		'ы' => 'y',
		'ь' => '',
		'э' => 'e',
		'ю' => 'yu',
		'я' => 'ya',
	];

	$str = strtr($str, $map);

	// cleanup: wszystko poza a-z0-9 -> "-"
	$str = preg_replace('~[^a-zA-Z0-9]+~u', '-', $str);
	$str = strtolower($str);
	$str = trim($str, '-');

	return $str !== '' ? $str : 'item';
}

add_filter('sanitize_title', function ($title, $raw_title = '', $context = 'display') {
	if (!is_string($title) || $title === '') return $title;

	if (aw_should_skip_slug_transliteration($title, (string)$raw_title, (string)$context)) {
		return $title;
	}

	return aw_slug_transliterate($title);
}, 9, 3);

add_filter('pre_wp_unique_post_slug', function ($override, $slug, $post_ID, $post_status, $post_type, $post_parent) {
	if (!is_string($slug) || $slug === '') return $override;

	// Nie ruszaj ACF
	if (in_array($post_type, ['acf-field-group', 'acf-field'], true) || aw_is_acf_internal_slug($slug)) {
		return $override;
	}

	return aw_slug_transliterate($slug);
}, 9, 6);
/**
 * 3) Termy (kategorie/tagi/atrybuty)
 */
add_filter('pre_term_slug', function ($slug) {
	if (!is_string($slug) || $slug === '') return $slug;
	return aw_slug_transliterate($slug);
}, 9);



/**
 * (Opcjonalnie) transliteruj nazwy plików uploadów (używaj świadomie!)
 */
// add_filter('sanitize_file_name', function ($filename) {
//     return is_string($filename) && $filename !== '' ? aw_slug_transliterate($filename) : $filename;
// }, 9);


/**
 * 5) BULK FIXER – narzędzie w Tools -> Fix slugs (arturiko-web)
 */
add_action('admin_menu', function () {
	add_management_page(
		'Fix slugs (arturiko-web)',
		'Fix slugs (arturiko-web)',
		'manage_options',
		'aw-fix-slugs',
		'aw_fix_slugs_admin_page'
	);
});

function aw_fix_slugs_admin_page(): void
{
	if (!current_user_can('manage_options')) return;

	$url   = admin_url('admin-post.php');
	$nonce = wp_create_nonce('aw_fix_slugs');

	echo '<div class="wrap"><h1>Fix slugs (arturiko-web)</h1>';
	echo '<p>Naprawia slugi dla postów/CPT oraz termów (kategorie/tagi/atrybuty). Zrób backup przed uruchomieniem.</p>';

	if (!empty($_GET['done'])) {
		echo '<div class="notice notice-success"><p>Gotowe. Zrobiono aktualizację slugów + flush rewrite rules.</p></div>';
	}

	echo '<form method="post" action="' . esc_url($url) . '">';
	echo '<input type="hidden" name="action" value="aw_fix_slugs">';
	echo '<input type="hidden" name="_wpnonce" value="' . esc_attr($nonce) . '">';

	echo '<p><label><input type="checkbox" name="fix_posts" value="1" checked> Fix post slugs (all public post types)</label></p>';
	echo '<p><label><input type="checkbox" name="fix_terms" value="1" checked> Fix term slugs (all public taxonomies)</label></p>';

	echo '<p><button class="button button-primary">Run bulk fix</button></p>';
	echo '</form></div>';
}

add_action('admin_post_aw_fix_slugs', function () {
	if (!current_user_can('manage_options')) wp_die('No permissions');
	check_admin_referer('aw_fix_slugs');

	$fix_posts = !empty($_POST['fix_posts']);
	$fix_terms = !empty($_POST['fix_terms']);

	if ($fix_posts) {
		aw_bulk_fix_post_slugs();
	}
	if ($fix_terms) {
		aw_bulk_fix_term_slugs();
	}

	// WAŻNE: po zmianach w slugach warto odświeżyć reguły
	flush_rewrite_rules(false);

	wp_safe_redirect(admin_url('tools.php?page=aw-fix-slugs&done=1'));
	exit;
});

function aw_bulk_fix_post_slugs(): void
{
	$post_types = get_post_types(['public' => true], 'names');

	$q = new WP_Query([
		'post_type'      => array_values($post_types),
		'post_status'    => 'any',
		'posts_per_page' => -1,
		'fields'         => 'ids',
		'no_found_rows'  => true,
		'orderby'        => 'ID',
		'order'          => 'ASC',
	]);

	foreach ($q->posts as $post_id) {
		$post_id = (int) $post_id;

		$current = (string) get_post_field('post_name', $post_id);
		$title   = (string) get_post_field('post_title', $post_id);

		$base = $current !== '' ? $current : $title;
		$new  = aw_slug_transliterate($base);

		if ($new === $current || $new === '') continue;

		// WP dopnie unikalność
		wp_update_post([
			'ID'        => $post_id,
			'post_name' => $new,
		]);
	}

	wp_reset_postdata();
}

function aw_bulk_fix_term_slugs(): void
{
	$taxes = get_taxonomies(['public' => true], 'names');

	foreach ($taxes as $tax) {
		$terms = get_terms([
			'taxonomy'   => $tax,
			'hide_empty' => false,
			'number'     => 0,
		]);

		if (is_wp_error($terms)) continue;

		foreach ($terms as $term) {
			$current = (string) $term->slug;
			$base    = $current !== '' ? $current : (string) $term->name;

			$new = aw_slug_transliterate($base);
			if ($new === $current || $new === '') continue;

			wp_update_term((int) $term->term_id, $tax, [
				'slug' => $new,
			]);
		}
	}
}
add_filter('use_widgets_block_editor', '__return_false', 100);
add_filter('gutenberg_use_widgets_block_editor', '__return_false', 100);

/**
 * (Opcjonalnie) Wyłącz też edytor blokowy dla Customizera (czasem pomaga).
 */
add_filter('use_block_editor_for_widgets', '__return_false', 100);
