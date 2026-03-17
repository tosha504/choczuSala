<?php

if (!defined('_S_VERSION')) {
    define('_S_VERSION', '1.0.0');
}

/**
 * Relevanssi Live Ajax Search ma działać na WP_Query
 * dla kompatybilności z WooCommerce i Polylang.
 */
add_filter('relevanssi_live_search_mode', function () {
    return 'wp_query';
});

/**
 * Wyłączamy domyślne style pluginu.
 */
add_filter('relevanssi_live_search_base_styles', '__return_false');

/**
 * Live search tylko dla produktów i tylko dla aktualnego języka.
 */
add_filter('relevanssi_live_search_query_args', function (array $args): array {
    $args['post_type']   = 'product';
    $args['post_status'] = 'publish';

    if (function_exists('aw_get_current_lang')) {
        $current_lang = aw_get_current_lang();

        if (!empty($current_lang)) {
            $args['lang'] = $current_lang;
        }
    } elseif (function_exists('pll_current_language')) {
        $current_lang = pll_current_language('slug');

        if (!empty($current_lang)) {
            $args['lang'] = $current_lang;
        }
    }

    return $args;
});

/**
 * Relevanssi ma indeksować dodatkowe pola wspierające search.
 */
add_filter('relevanssi_custom_fields', function (array $fields): array {
    $fields[] = '_sku';
    $fields[] = 'search_alias';

    return array_values(array_unique($fields));
});
