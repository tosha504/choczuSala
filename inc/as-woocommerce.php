<?php
if (!defined('ABSPATH')) exit;
/**
 * Polylang: filtruj kategorie WooCommerce pod język aktualnego produktu.
 */
require_once get_theme_file_path('inc/woo/woopolyaw.php');
require_once get_theme_file_path('inc/woo/badges.php');
require_once get_theme_file_path('inc/woo/archive.php');

add_action('after_setup_theme', function () {
    add_theme_support('woocommerce');
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

remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);
add_action('woocommerce_single_product_summary', 'woocommerce_breadcrumb', 1);
//quantity field
add_filter('woocommerce_quantity_input', function ($html, $product) {
    if (is_product() && $product instanceof WC_Product && $product->is_sold_individually()) {
        return ''; // nic nie renderuj
    }
    return $html;
}, 10, 2);

// Warianty: usuń qty-HTML dla wariacji sprzedawanych pojedynczo
add_filter('woocommerce_available_variation', function ($args, $product, $variation) {
    if (is_product() && $variation instanceof WC_Product && $variation->is_sold_individually()) {
        $args['quantity_html']        = ''; // usuwa wrap qty przy wariacji
        $args['min_qty']              = 1;
        $args['max_qty']              = 1;
        $args['is_sold_individually'] = true;
    }
    return $args;
}, 10, 3);
add_action('woocommerce_before_quantity_input_field', function () {
    if (! is_product()) return;
    global $product;
    if (!$product || $product->is_sold_individually()) return; // nic nie dodawaj
    echo '<button class="cart-qty minus">-</button>';
});

add_action('woocommerce_after_quantity_input_field', function () {
    if (! is_product()) return;
    if (!is_product()) return;

    global $product;
    if (!$product || $product->is_sold_individually()) return; // nic nie dodawaj
    echo '<button class="cart-qty plus">+</button>';
});
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
add_action('wp_footer', 'cart_update_qty_script');
function cart_update_qty_script()
{
    if (is_checkout()) :
?>
        <script>
            let timeout;
            jQuery('.checkout.woocommerce-checkout').on('change', 'input.qty', function() {
                alert('sdfsdf')
                if (timeout !== undefined) {
                    clearTimeout(timeout);
                }
                timeout = setTimeout(function() {
                    jQuery('.cart-qty.plus, .minus').attr('disabled', true) // trigger cart update
                }, 100); // 1 second delay, half a second (500) seems comfortable too
                // jQuery(document.body).trigger('wc_fragment_refresh');
                setTimeout(function() {
                    jQuery(document.body).trigger('wc_fragment_refresh'); // Refresh the cart fragments
                }, 1000);
            });
        </script>
<?php
    endif;
}
