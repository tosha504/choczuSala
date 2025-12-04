<?php
if (!defined('ABSPATH')) exit;
/**
 * Polylang: filtruj kategorie WooCommerce pod język aktualnego produktu.
 */
require_once get_theme_file_path('inc/woo/woopolyaw.php');
add_action('after_setup_theme', function () {
    remove_theme_support('wc-product-gallery-zoom');
    remove_theme_support('wc-product-gallery-lightbox');
    remove_theme_support('wc-product-gallery-slider');
}, 20);
add_filter('woocommerce_enqueue_styles', '__return_empty_array');
