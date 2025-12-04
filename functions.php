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

);

// Load WooCommerce functions if WooCommerce is activated.
if (class_exists('WooCommerce')) {
	$realestate_includes[] = '/woocommerce.php';
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
add_action('init', 'aw_register_multilang_options_pages');

function aw_register_multilang_options_pages()
{
	if (!function_exists('acf_add_options_page')) return;

	// Dynamicznie pobiera wszystkie aktywne języki
	$langs = function_exists('pll_languages_list')
		? pll_languages_list(['fields' => 'slug'])
		: ['pl']; // fallback

	foreach ($langs as $lang) {
		acf_add_options_page([
			'page_title' => "Ustawienia ({$lang})",
			'menu_title' => "Ustawienia {$lang}",
			'menu_slug'  => "theme-options-{$lang}",
			'post_id'    => "theme_options_{$lang}", // KLUCZ
			'redirect'   => false,
			'capability' => 'manage_options'
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

?><?php
	/**
	 * Plugin Name: AW Weight Products
	 * Description: Sprzedaż na wagę z ceną za kg i dowolną ilością gramów + live podgląd ceny.
	 * Author: ArtiWeb
	 */

	if (!defined('ABSPATH')) {
		exit;
	}

	class AW_Weight_Products
	{
		const META_ENABLED      = '_aw_weight_enabled';
		const META_PRICE_PER_KG = '_aw_price_per_kg';
		const META_MIN_GRAMS    = '_aw_min_grams';
		const META_MAX_GRAMS    = '_aw_max_grams';

		public function __construct()
		{
			// Admin: metabox
			add_action('add_meta_boxes', [$this, 'add_metabox']);
			add_action('save_post_product', [$this, 'save_metabox'], 10, 2);

			// Front: pole na gramy + cena za kg
			add_action('woocommerce_before_add_to_cart_button', [$this, 'render_grams_field']);

			// Koszyk / zamówienie: zapis i wyświetlanie gramów
			add_filter('woocommerce_add_cart_item_data', [$this, 'add_cart_item_data'], 10, 3);
			add_filter('woocommerce_get_item_data', [$this, 'display_item_data'], 10, 2);
			add_action('woocommerce_checkout_create_order_line_item', [$this, 'add_order_item_meta'], 10, 4);

			// Przeliczenie cen (kg -> g)
			add_action('woocommerce_before_calculate_totals', [$this, 'recalculate_cart_prices'], 20, 1);

			// Wyświetlanie "/ kg" przy cenie produktu na wagę
			add_filter('woocommerce_get_price_html', [$this, 'price_html_per_kg'], 10, 2);

			// JS do live podglądu
			add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
		}

		/*
     * =========================
     *  ADMIN: METABOX
     * =========================
     */

		public function add_metabox()
		{
			add_meta_box(
				'aw_weight_product',
				__('Sprzedaż na wagę', 'aw-theme'),
				[$this, 'render_metabox'],
				'product',
				'side',
				'default'
			);
		}

		public function render_metabox($post)
		{
			wp_nonce_field('aw_weight_metabox_nonce', 'aw_weight_metabox_nonce_field');

			$enabled      = get_post_meta($post->ID, self::META_ENABLED, true);
			$price_per_kg = get_post_meta($post->ID, self::META_PRICE_PER_KG, true);
			$min_grams    = get_post_meta($post->ID, self::META_MIN_GRAMS, true);
			$max_grams    = get_post_meta($post->ID, self::META_MAX_GRAMS, true);

	?>
<p>
	<label>
		<input type="checkbox" name="aw_weight_enabled" value="1" <?php checked($enabled, '1'); ?> />
		<?php esc_html_e('Sprzedawaj ten produkt na wagę (cena w zł/kg)', 'aw-theme'); ?>
	</label>
</p>

<p>
	<label for="aw_price_per_kg"><strong><?php esc_html_e('Cena za 1 kg', 'aw-theme'); ?></strong></label><br>
	<input
		type="text"
		id="aw_price_per_kg"
		name="aw_price_per_kg"
		value="<?php echo esc_attr($price_per_kg); ?>"
		placeholder="<?php esc_attr_e('np. 49.90', 'aw-theme'); ?>"
		style="width:100%;" />
	<small><?php esc_html_e('Jeśli puste, użyta będzie regularna cena produktu.', 'aw-theme'); ?></small>
</p>

<p>
	<label for="aw_min_grams"><strong><?php esc_html_e('Minimalna ilość (g)', 'aw-theme'); ?></strong></label><br>
	<input
		type="number"
		id="aw_min_grams"
		name="aw_min_grams"
		value="<?php echo esc_attr($min_grams); ?>"
		min="0"
		step="1"
		style="width:100%;" />
</p>

<p>
	<label for="aw_max_grams"><strong><?php esc_html_e('Maksymalna ilość (g)', 'aw-theme'); ?></strong></label><br>
	<input
		type="number"
		id="aw_max_grams"
		name="aw_max_grams"
		value="<?php echo esc_attr($max_grams); ?>"
		min="0"
		step="1"
		style="width:100%;" />
	<small><?php esc_html_e('Zostaw puste, jeśli brak limitu.', 'aw-theme'); ?></small>
</p>
<?php
		}

		public function save_metabox($post_id, $post)
		{
			if (
				!isset($_POST['aw_weight_metabox_nonce_field']) ||
				!wp_verify_nonce($_POST['aw_weight_metabox_nonce_field'], 'aw_weight_metabox_nonce')
			) {
				return;
			}

			if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
				return;
			}

			if ($post->post_type !== 'product') {
				return;
			}

			$enabled = isset($_POST['aw_weight_enabled']) ? '1' : '0';
			update_post_meta($post_id, self::META_ENABLED, $enabled);

			$price_per_kg = isset($_POST['aw_price_per_kg']) ? sanitize_text_field($_POST['aw_price_per_kg']) : '';
			$price_per_kg = str_replace(',', '.', $price_per_kg);
			if ($price_per_kg !== '' && !is_numeric($price_per_kg)) {
				$price_per_kg = '';
			}
			if ($price_per_kg !== '') {
				update_post_meta($post_id, self::META_PRICE_PER_KG, $price_per_kg);
			} else {
				delete_post_meta($post_id, self::META_PRICE_PER_KG);
			}

			$min_grams = isset($_POST['aw_min_grams']) ? intval($_POST['aw_min_grams']) : 0;
			$max_grams = isset($_POST['aw_max_grams']) ? intval($_POST['aw_max_grams']) : 0;

			if ($min_grams > 0) {
				update_post_meta($post_id, self::META_MIN_GRAMS, $min_grams);
			} else {
				delete_post_meta($post_id, self::META_MIN_GRAMS);
			}

			if ($max_grams > 0) {
				update_post_meta($post_id, self::META_MAX_GRAMS, $max_grams);
			} else {
				delete_post_meta($post_id, self::META_MAX_GRAMS);
			}
		}

		/*
     * =========================
     *  HELPERY
     * =========================
     */

		public static function is_weight_product($product_id)
		{
			return get_post_meta($product_id, self::META_ENABLED, true) === '1';
		}

		public static function get_price_per_kg($product)
		{
			if (!$product instanceof WC_Product) {
				return 0;
			}

			$product_id   = $product->get_id();
			$meta_price   = get_post_meta($product_id, self::META_PRICE_PER_KG, true);
			$regular      = $product->get_regular_price();

			if ($meta_price !== '' && is_numeric($meta_price)) {
				return (float) $meta_price;
			}

			return (float) $regular;
		}

		public static function get_min_grams($product_id)
		{
			$min = (int) get_post_meta($product_id, self::META_MIN_GRAMS, true);
			if ($min <= 0) {
				$min = 1;
			}
			return $min;
		}

		public static function get_max_grams($product_id)
		{
			$max = (int) get_post_meta($product_id, self::META_MAX_GRAMS, true);
			return $max > 0 ? $max : 0;
		}

		/*
     * =========================
     *  FRONTEND: POLE NA GRAMY
     * =========================
     */

		public function render_grams_field()
		{
			global $product;

			if (!$product instanceof WC_Product) {
				return;
			}

			// Dla prostoty obsługujemy tylko produkty proste
			if ($product->get_type() !== 'simple') {
				return;
			}

			$product_id = $product->get_id();

			if (!self::is_weight_product($product_id)) {
				return;
			}

			$price_per_kg = self::get_price_per_kg($product);
			if ($price_per_kg <= 0) {
				return;
			}

			$min_grams = self::get_min_grams($product_id);
			$max_grams = self::get_max_grams($product_id);

			$default_grams = $min_grams > 0 ? $min_grams : 100;

			$currency_symbol    = get_woocommerce_currency_symbol();
			$decimals           = wc_get_price_decimals();
			$decimal_separator  = wc_get_price_decimal_separator();
			$thousand_separator = wc_get_price_thousand_separator();

?>
	<div
		class="aw-weight-product"
		data-price-per-kg="<?php echo esc_attr($price_per_kg); ?>"
		data-currency-symbol="<?php echo esc_attr($currency_symbol); ?>"
		data-decimals="<?php echo esc_attr($decimals); ?>"
		data-decimal-separator="<?php echo esc_attr($decimal_separator); ?>"
		data-thousand-separator="<?php echo esc_attr($thousand_separator); ?>">
		<p class="aw-weight-product__price-per-kg">
			<?php echo wc_price($price_per_kg); ?> / kg
		</p>

		<div class="aw-weight-product__grams-field">
			<label for="aw_grams">
				<?php esc_html_e('Ilość (w gramach):', 'aw-theme'); ?>
			</label>
			<input
				type="number"
				id="aw_grams"
				name="aw_grams"
				value="<?php echo esc_attr($default_grams); ?>"
				min="<?php echo esc_attr($min_grams); ?>"
				<?php if ($max_grams > 0) : ?>
				max="<?php echo esc_attr($max_grams); ?>"
				<?php endif; ?>
				step="1"
				inputmode="numeric" />
		</div>

		<p class="aw-weight-product__preview">
			<strong><?php esc_html_e('Zapłacisz:', 'aw-theme'); ?></strong>
			<span class="aw-weight-product__preview-value"></span>
		</p>
	</div>
<?php
		}

		/*
     * =========================
     *  KOSZYK: ZAMIAN GRAMÓW
     * =========================
     */

		public function add_cart_item_data($cart_item_data, $product_id, $variation_id)
		{
			if (!self::is_weight_product($product_id)) {
				return $cart_item_data;
			}

			if (!isset($_POST['aw_grams'])) {
				wc_add_notice(__('Podaj ilość w gramach.', 'aw-theme'), 'error');
				return $cart_item_data;
			}

			$grams = (int) $_POST['aw_grams'];

			$min_grams = self::get_min_grams($product_id);
			$max_grams = self::get_max_grams($product_id);

			if ($grams < $min_grams) {
				wc_add_notice(
					sprintf(__('Minimalna ilość to %d g.', 'aw-theme'), $min_grams),
					'error'
				);
				return $cart_item_data;
			}

			if ($max_grams > 0 && $grams > $max_grams) {
				wc_add_notice(
					sprintf(__('Maksymalna ilość to %d g.', 'aw-theme'), $max_grams),
					'error'
				);
				return $cart_item_data;
			}

			$cart_item_data['aw_grams']      = $grams;
			$cart_item_data['aw_unique_key'] = md5(microtime() . rand());

			return $cart_item_data;
		}

		public function display_item_data($item_data, $cart_item)
		{
			if (isset($cart_item['aw_grams'])) {
				$item_data[] = [
					'name'  => __('Ilość', 'aw-theme'),
					'value' => intval($cart_item['aw_grams']) . ' g',
				];
			}

			return $item_data;
		}

		public function add_order_item_meta($item, $cart_item_key, $values, $order)
		{
			if (isset($values['aw_grams'])) {
				$item->add_meta_data(__('Ilość (g)', 'aw-theme'), intval($values['aw_grams']), true);
			}
		}

		public function recalculate_cart_prices($cart)
		{
			if (is_admin() && !defined('DOING_AJAX')) {
				return;
			}

			if (empty($cart) || !$cart instanceof WC_Cart) {
				return;
			}

			foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
				if (!isset($cart_item['aw_grams'])) {
					continue;
				}

				$product = $cart_item['data'];
				if (!$product instanceof WC_Product) {
					continue;
				}

				$price_per_kg = self::get_price_per_kg($product);
				if ($price_per_kg <= 0) {
					continue;
				}

				$grams = (float) $cart_item['aw_grams'];
				if ($grams <= 0) {
					continue;
				}

				$kg_amount = $grams / 1000;

				// Cena jednostkowa pozycji (za tę ilość gramów)
				$line_unit_price = $price_per_kg * $kg_amount;
				$line_unit_price = wc_format_decimal($line_unit_price);

				$product->set_price($line_unit_price);
			}
		}

		/*
     * =========================
     *  CENA / KG W LISTINGACH
     * =========================
     */

		public function price_html_per_kg($price_html, $product)
		{
			if (!$product instanceof WC_Product) {
				return $price_html;
			}

			$product_id = $product->get_id();

			if (!self::is_weight_product($product_id)) {
				return $price_html;
			}

			return $price_html . ' <span class="aw-unit-label">/ kg</span>';
		}

		/*
     * =========================
     *  JS: LIVE PODGLĄD CENY
     * =========================
     */

		public function enqueue_scripts()
		{
			if (!is_product()) {
				return;
			}

			wp_register_script(
				'aw-weight-product',
				'', // pusty src, użyjemy inline
				[],
				'1.0.0',
				true
			);
			wp_enqueue_script('aw-weight-product');

			$config = [
				'decimals'           => wc_get_price_decimals(),
				'decimal_separator'  => wc_get_price_decimal_separator(),
				'thousand_separator' => wc_get_price_thousand_separator(),
				'currency_symbol'    => get_woocommerce_currency_symbol(),
			];

			wp_localize_script('aw-weight-product', 'awWeightProductConfig', $config);

			$inline_js = <<<JS
(function() {
    function formatPrice(amount) {
        var cfg = window.awWeightProductConfig || {};
        var decimals = typeof cfg.decimals !== 'undefined' ? parseInt(cfg.decimals, 10) : 2;
        var decSep = cfg.decimal_separator || '.';
        var thouSep = cfg.thousand_separator || '';
        var symbol = cfg.currency_symbol || '';

        if (isNaN(amount)) {
            amount = 0;
        }

        var negative = amount < 0;
        amount = Math.abs(amount);

        var factor = Math.pow(10, decimals);
        amount = Math.round(amount * factor) / factor;

        var parts = amount.toFixed(decimals).split('.');
        var integerPart = parts[0];
        var decimalPart = parts.length > 1 ? parts[1] : '';

        if (thouSep) {
            integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, thouSep);
        }

        var result = integerPart;
        if (decimals > 0) {
            result += decSep + decimalPart;
        }

        if (negative) {
            result = '-' + result;
        }

        return symbol + ' ' + result;
    }

    function initWeightPreview() {
        var wrapper = document.querySelector('.aw-weight-product');
        if (!wrapper) return;

        var input = wrapper.querySelector('#aw_grams');
        var preview = wrapper.querySelector('.aw-weight-product__preview-value');

        if (!input || !preview) return;

        var pricePerKg = parseFloat(wrapper.getAttribute('data-price-per-kg') || '0');

        function updatePreview() {
            var grams = parseFloat(input.value || '0');
            if (isNaN(grams) || grams <= 0 || pricePerKg <= 0) {
                preview.textContent = '';
                return;
            }

            var kgAmount = grams / 1000;
            var total = pricePerKg * kgAmount;

            preview.textContent = formatPrice(total);
        }

        input.addEventListener('input', updatePreview);
        input.addEventListener('change', updatePreview);

        // pierwszy przelicz po załadowaniu
        updatePreview();
    }

    document.addEventListener('DOMContentLoaded', initWeightPreview);
})();
JS;

			wp_add_inline_script('aw-weight-product', $inline_js);
		}
	}

	new AW_Weight_Products();
