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

	/**
	 * Load Jetpack compatibility file.
	 */
	if (defined('JETPACK__VERSION')) {
		require get_template_directory() . '/inc/jetpack.php';
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
	// functions.php (motyw potomny) lub własna wtyczka

	// Rejestrujemy filtry wcześnie (po załadowaniu motywu), ale tylko gdy Polylang jest aktywny
	// add_action('after_setup_theme', function () {
	// 	if (!function_exists('pll_is_translated_post_type')) {
	// 		return; // Polylang nieaktywny
	// 	}
	// 	add_filter('pll_get_post_types', function ($types, $is_settings) {
	// 		// Włącz tłumaczenia dla produktów WooCommerce
	// 		$types['product'] = 'product';
	// 		return $types;
	// 	}, 10, 2);

	// 	add_filter('pll_get_taxonomies', function ($taxonomies, $is_settings) {
	// 		// Włącz tłumaczenia dla kategorii i tagów produktów
	// 		$taxonomies['product_cat'] = 'product_cat';
	// 		$taxonomies['product_tag'] = 'product_tag';
	// 		return $taxonomies;
	// 	}, 10, 2);
	// }, 5);

	/**
	 * Plugin Name: AW – Polylang + Woo Sync (safe)
	 * Description: Kopiowanie i synchronizacja meta/tax WooCommerce między tłumaczeniami (Polylang Free).
	 */


	/** =======================
	 *  BEZPIECZNIK POLYLANG
	 *  ======================= */
	/**
	 * Uruchamiaj kod dopiero, gdy Polylang jest załadowany.
	 */
	add_action('init', function () {

		if (!function_exists('pll_get_post_language')) {
			add_action('admin_notices', function () {
				echo '<div class="notice notice-error"><p>❌ Polylang nie został jeszcze załadowany. Włącz lub sprawdź kolejność ładowania wtyczek.</p></div>';
			});
			return;
		}

		// ----------------------------------------------------
		// 👉 Tutaj wklej cały Twój kod synchronizacji (np. aw_pll_active, save_post_product itd.)
		// ----------------------------------------------------
	},);

	function aw_pll_active(): bool
	{
		return function_exists('pll_get_post_language') && function_exists('pll_copy_post_metas');
	}

	/** =========================================
	 *  0) Rejestracja typów/taksonomii do tłumaczeń
	 *  ========================================= */
	add_action('after_setup_theme', function () {

		if (!function_exists('pll_is_translated_post_type')) return;

		add_filter('pll_get_post_types', function ($types, $is_settings) {
			$types['product'] = 'product';
			return $types;
		}, 10, 2);

		add_filter('pll_get_taxonomies', function ($taxonomies, $is_settings) {
			$taxonomies['product_cat'] = 'product_cat';
			$taxonomies['product_tag'] = 'product_tag';
			return $taxonomies;
		}, 10, 2);
	}, 5);

	/** ==================================
	 *  1) Lista meta do kopiowania/sync
	 *  ================================== */
	function aw_wc_meta_whitelist(): array
	{
		$list = [
			// ceny / podatki / stock
			'_regular_price',
			'_sale_price',
			'_price',
			'_tax_status',
			'_tax_class',
			'_manage_stock',
			'_stock',
			'_stock_status',
			'_backorders',
			'_sold_individually',

			// wymiary / typy / pliki
			'_weight',
			'_length',
			'_width',
			'_height',
			'_virtual',
			'_downloadable',
			'_download_limit',
			'_download_expiry',
			'_downloadable_files',
			'_file_paths',

			// atrybuty / powiązania
			'_product_attributes',
			'_variation_description',
			'_upsell_ids',
			'_crosssell_ids',

			// media
			'_thumbnail_id',
			'_product_image_gallery',

			// dopisz tu własne pola/ACF, jeśli mają być wspólne między językami:
			// 'acf_pole_x','acf_pole_y',
		];
		/** Pozwól nadpisać z zewnątrz */
		return apply_filters('aw_wc_meta_whitelist', $list);
	}

	/** ==========================================================
	 *  2) KOPIOWANIE przy tworzeniu tłumaczenia (oficjalne filtry)
	 *  ========================================================== */
	add_filter('pll_copy_post_metas', function ($metas, $sync, $from, $to, $lang) {
		if (!function_exists('pll_is_translated_post_type')) return $metas;
		return array_values(array_unique(array_merge($metas, aw_wc_meta_whitelist())));
	}, 10, 5);

	add_filter('pll_copy_taxonomies', function ($taxonomies, $sync, $from, $to, $lang) {
		if (!function_exists('pll_is_translated_post_type')) return $taxonomies;
		foreach (['product_cat', 'product_tag'] as $tax) {
			if (!in_array($tax, $taxonomies, true)) $taxonomies[] = $tax;
		}
		return $taxonomies;
	}, 10, 5);

	/** Miniatura i galeria – próba „przetłumaczenia” ID mediów na docelowy język */
	add_filter('pll_translate_post_meta', function ($value, $key, $lang, $from, $to) {
		if (!function_exists('pll_is_translated_post_type')) return $value;

		// 1: miniatura
		if ($key === '_thumbnail_id' && $value) {
			$tr = function_exists('pll_get_post') ? (int) pll_get_post((int)$value, $lang) : 0;
			return $tr ?: (int)$value;
		}

		// 2: galeria (CSV ID-ów)
		if ($key === '_product_image_gallery' && is_string($value) && strlen($value)) {
			$ids = array_filter(array_map('intval', explode(',', $value)));
			if (!$ids) return $value;

			$mapped = [];
			foreach ($ids as $id) {
				$tr = function_exists('pll_get_post') ? (int) pll_get_post((int)$id, $lang) : 0;
				$mapped[] = $tr ?: (int)$id;
			}
			return implode(',', array_unique($mapped));
		}

		return $value;
	}, 10, 5);
	// var_dump('sdfsf', aw_pll_active(), 'ltest');

	/** =============================================
	 *  3) SYNC po zapisie produktu (save_post_product)
	 *     – bez pętli i „fikołków”
	 *  ============================================= */
	add_action('save_post_product', function ($post_id, $post, $update) {
		if (!function_exists('pll_is_translated_post_type')) return;
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
		if (wp_is_post_autosave($post_id) || wp_is_post_revision($post_id)) return;

		static $running = false;
		if ($running) return; // anty-loop
		$running = true;

		$post_id = is_object($post_id) ? (int)$post_id->ID : (int)$post_id;

		$translations = pll_get_post_translations($post_id); // ['pl'=>123,'en'=>456,...]
		if (!$translations || !is_array($translations)) {
			$running = false;
			return;
		}

		foreach ($translations as $lang => $target_id) {
			$target_id = (int)$target_id;
			if ($target_id === $post_id) continue;

			aw_sync_product_data($post_id, $target_id, $lang);
		}

		$running = false;
	}, 20, 3);

	/** ==========================================================
	 *  4) CORE: kopiowanie meta + mapowanie taksonomii na tłumaczenia
	 *  ========================================================== */
	function aw_sync_product_data($source_id, $target_id, string $target_lang)
	{
		if (!function_exists('pll_is_translated_post_type')) return;
		$source_id = is_object($source_id) ? (int)$source_id->ID : (int)$source_id;
		$target_id = is_object($target_id) ? (int)$target_id->ID : (int)$target_id;


		// --- Główne pola postu ---
		$source_post = get_post($source_id);
		if ($source_post && $source_post instanceof WC_Product) {
			wp_update_post([
				'ID'           => $target_id,
				'post_title'   => $source_post->post_title,
				'post_content' => $source_post->post_content,
				'post_excerpt' => $source_post->post_excerpt,
			]);
		}

		// --- META (biała lista) ---
		foreach (aw_wc_meta_whitelist() as $key) {
			if (!metadata_exists('post', $source_id, $key)) {
				delete_post_meta($target_id, $key);
				continue;
			}
			$val = get_post_meta($source_id, $key, true);

			// translate media IDs when applicable
			if ($key === '_thumbnail_id' && $val) {
				$tr = function_exists('pll_get_post') ? (int) pll_get_post((int)$val, $target_lang) : 0;
				$val = $tr ?: (int)$val;
			} elseif ($key === '_product_image_gallery' && is_string($val) && strlen($val)) {
				$ids = array_filter(array_map('intval', explode(',', $val)));
				$mapped = [];
				foreach ($ids as $id) {
					$tr = function_exists('pll_get_post') ? (int) pll_get_post((int)$id, $target_lang) : 0;
					$mapped[] = $tr ?: (int)$id;
				}
				$val = implode(',', array_unique($mapped));
			}

			update_post_meta($target_id, $key, maybe_unserialize($val));
		}

		// --- Miniatura (dla pewności) ---
		$thumb = get_post_thumbnail_id($source_id);
		if ($thumb) {
			$tr = function_exists('pll_get_post') ? (int) pll_get_post((int)$thumb, $target_lang) : 0;
			set_post_thumbnail($target_id, $tr ?: (int)$thumb);
		} else {
			delete_post_thumbnail($target_id);
		}

		// --- Taksonomie: product_cat, product_tag -> tłumaczenia w $target_lang ---
		foreach (['product_cat', 'product_tag'] as $tax) {
			$terms = wp_get_object_terms($source_id, $tax, ['fields' => 'ids']);
			if (is_wp_error($terms) || empty($terms)) {
				wp_set_object_terms($target_id, [], $tax, false);
				continue;
			}

			$target_terms = [];
			foreach ($terms as $term_id) {
				$term_id = (int)$term_id;
				$tr = function_exists('pll_get_term') ? (int) pll_get_term($term_id, $target_lang) : 0;
				$target_terms[] = $tr ?: $term_id; // fallback do oryginału, jeśli brak tłumaczenia
			}

			wp_set_object_terms($target_id, array_values(array_unique(array_map('intval', $target_terms))), $tax, false);
		}
	}

	/** ==========================================================
	 *  (Opcjonalnie) WARIANTY i atrybuty globalne `pa_*`
	 *  ----------------------------------------------------------
	 *  Jeżeli używasz wariantów – daj znać, dorzucę blok sync
	 *  dla post_type `product_variation` i mapowania `pa_*`.
	 *  ========================================================== */
	add_filter('woocommerce_enqueue_styles', '__return_empty_array');
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
	 * Popup wyboru języka dla Polylang.
	 */

	add_action('wp_footer', 'aw_lang_selector_popup');
	function aw_lang_selector_popup()
	{
		// Zbuduj tablicę języków dla JS + HTML
		$langs_js = array();

		if (function_exists('pll_the_languages')) {
			$pll_raw = pll_the_languages(array('raw' => 1));
			if (is_array($pll_raw)) {
				foreach ($pll_raw as $l) {
					$slug = isset($l['slug']) ? $l['slug'] : '';
					if (!$slug) continue;

					$langs_js[$slug] = array(
						'name' => isset($l['name']) ? $l['name'] : $slug,
						'url'  => isset($l['url']) ? $l['url'] : home_url('/'),
						'flag' => isset($l['flag']) ? $l['flag'] : '',
					);
				}
			}
		} else {
			// fallback gdy Polylang nieaktywny
			$langs_js['default'] = array(
				'name' => get_bloginfo('language'),
				'url'  => home_url('/'),
				'flag' => '',
			);
		}

		$json_langs   = wp_json_encode($langs_js);
		$default_lang = function_exists('pll_default_language') ? pll_default_language('slug') : '';

		// === HTML OVERLAYA ===
	?>
		<div id="aw-lang-overlay" class="aw-lang-overlay" aria-hidden="true">
			<div class="aw-lang-overlay__backdrop"></div>
			<div class="aw-lang-overlay__dialog" role="dialog" aria-modal="true">
				<h2 class="aw-lang-overlay__title">
					<?php esc_html_e('Wybierz jezyk', 'aw-theme'); ?>
				</h2>
				<div class="aw-lang-overlay__buttons">
					<?php foreach ($langs_js as $code => $data) : ?>
						<button
							type="button"
							class="aw-lang-btn"
							data-lang="<?php echo esc_attr($code); ?>"
							data-url="<?php echo esc_url($data['url']); ?>">
							<?php if (!empty($data['flag'])) : ?>
								<span class="aw-lang-btn__flag">
									<img src="<?php
												// Polylang czasem zwraca gotowy <img>, czasem URL – zakładamy gotowy HTML
												echo $data['flag']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
												?>" alt="">
								</span>
							<?php endif; ?>
							<span class="aw-lang-btn__label">
								<?php echo esc_html($data['name']); ?>
							</span>
						</button>
					<?php endforeach; ?>
				</div>
			</div>
		</div>

		<script>
			(function() {
				document.addEventListener('DOMContentLoaded', function() {
					var COOKIE_NAME = 'aw_lang_choice';

					var langs = <?php echo $json_langs ?: '{}'; ?>;
					var overlay = document.getElementById('aw-lang-overlay');

					console.log('aw-lang-overlay element:', overlay, langs);

					if (!overlay) return;

					function getCookie(name) {
						var m = document.cookie.match('(^|;)\\s*' + name + '\\s*=\\s*([^;]+)');
						return m ? decodeURIComponent(m.pop()) : '';
					}

					function setCookie(name, value, days) {
						try {
							var d = new Date();
							d.setTime(d.getTime() + (days || 365) * 24 * 60 * 60 * 1000);
							var cookie = name + '=' + encodeURIComponent(value) +
								';expires=' + d.toUTCString() +
								';path=/;SameSite=Lax';
							if (location.protocol === 'https:') cookie += ';secure';
							document.cookie = cookie;
						} catch (e) {}
					}

					// Jeśli język już wybrany → nie pokazujemy popupu
					var chosen = getCookie(COOKIE_NAME);
					console.log('aw_lang_choice cookie:', chosen);

					if (chosen) {
						overlay.setAttribute('aria-hidden', 'true');
						return;
					}

					// --- Pokaż popup (brak wybranego języka) ---
					overlay.setAttribute('aria-hidden', 'false');
					document.documentElement.classList.add('aw-lang-lock');
					if (document.body) document.body.classList.add('aw-lang-lock');

					// Klik w przycisk języka w overlayu
					overlay.addEventListener('click', function(e) {
						var btn = e.target.closest('.aw-lang-btn');
						if (!btn) return;

						var code = btn.getAttribute('data-lang');
						var url = btn.getAttribute('data-url') || window.location.href;

						if (code) {
							setCookie(COOKIE_NAME, code, 365);
						}

						// normalne przeładowanie TYLKO po kliknięciu
						window.location.href = url;
					});

					// Klik w standardowy switcher Polylanga → aktualizujemy nasze cookie
					document.addEventListener('click', function(e) {
						var link = e.target.closest('.lang-item a, .menu-item-language a');
						if (!link) return;

						var path = link.pathname || '';
						var code = null;

						Object.keys(langs).forEach(function(c) {
							if (path === '/' + c + '/' || path.indexOf('/' + c + '/') === 0) {
								code = c;
							}
						});

						if (code) {
							setCookie(COOKIE_NAME, code, 365);
						}
					});
				});
			})();
		</script>

	<?php
	}


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
