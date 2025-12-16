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
remove_action('woocommerce_cart_collaterals', 'woocommerce_cart_totals', 10);
add_action('woocommerce_before_cart_collaterals', 'woocommerce_cart_totals');

remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);

// functions.php (child theme) lub własna wtyczka MU
add_action('init', function () {
    $hooks = [
        'woocommerce_before_single_product',
        'woocommerce_before_shop_loop',
        'woocommerce_before_cart',
        'woocommerce_before_checkout_form',
        'woocommerce_account_content',
    ];

    foreach ($hooks as $hook) {
        // Usuń defaultowe renderowanie
        remove_action($hook, 'woocommerce_output_all_notices', 10);
        // Dodaj własne – z kontenerem
        add_action($hook, 'aw_output_notices_in_container', 10);
    }
});

function aw_output_notices_in_container()
{
    echo '<div class="container">';          // ← Twoja klasa kontenera
    woocommerce_output_all_notices();        // oryginalne wypisanie notice’ów
    echo '</div>';
}
remove_action('woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20);
remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10);
add_action('custom_payment_position', 'woocommerce_checkout_payment', 20);
add_action('woocommerce_before_checkout_form', function () {
    echo '<div class="container">1243';
}, 1);

add_action('woocommerce_after_checkout_form', function () {
    echo '</div>';
}, 1);
add_filter('woocommerce_checkout_fields', function ($fields) {

    // Usuwamy pole "Adres 2" z danych rozliczeniowych
    unset($fields['billing']['billing_address_2']);

    return $fields;
});
function aw_get_product_image_fallback_id(int $product_id): int
{
    // 1️⃣ Obrazek produktu
    $thumbnail_id = get_post_thumbnail_id($product_id);
    if ($thumbnail_id) {
        return $thumbnail_id;
    }

    // 2️⃣ Kategorie produktu
    $terms = get_the_terms($product_id, 'product_cat');
    if (!empty($terms) && !is_wp_error($terms)) {

        // sortuj: najpierw kategorie z parentem (child)
        usort($terms, function ($a, $b) {
            return (int) $b->parent <=> (int) $a->parent;
        });

        foreach ($terms as $term) {
            $term_thumb_id = get_term_meta($term->term_id, 'thumbnail_id', true);
            if ($term_thumb_id) {
                return (int) $term_thumb_id;
            }
        }
    }

    // 3️⃣ Placeholder globalny (ID z media library)
    return (int) get_option('woocommerce_placeholder_image', 0);
}
