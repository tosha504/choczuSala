<?php

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
add_filter('woocommerce_get_product_terms', function ($terms, $product_id, $taxonomy, $args) {

	if ($taxonomy !== 'product_cat') {
		return $terms;
	}
	var_dump($taxonomy);

	// Język aktualnej wersji produktu
	$lang = function_exists('pll_get_post_language')
		? pll_get_post_language($product_id)
		: false;

	if (!$lang) {
		return $terms;
	}

	$filtered = [];

	foreach ($terms as $term) {
		if (!is_a($term, 'WP_Term')) continue;

		// Spróbuj znaleźć tłumaczenie kategorii
		$translated_id = function_exists('pll_get_term')
			? pll_get_term($term->term_id, $lang)
			: 0;

		if ($translated_id) {
			$filtered[] = get_term($translated_id);
		}
	}

	return $filtered;
}, 20, 4);
