<?php

/**
 * Polylang: filtruj kategorie WooCommerce pod język aktualnego produktu.
 */
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
