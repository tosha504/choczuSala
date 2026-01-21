<?php
if (!defined('ABSPATH')) exit;
/**
 * Polylang: filtruj kategorie WooCommerce pod język aktualnego produktu.
 */
require_once get_theme_file_path('inc/woo/woopolyaw.php');
require_once get_theme_file_path('inc/woo/badges.php');
require_once get_theme_file_path('inc/woo/archive.php');
require_once get_theme_file_path('inc/woo/cart.php');

add_action('after_setup_theme', function () {
    add_theme_support('woocommerce');
    remove_theme_support('wc-product-gallery-zoom');
    remove_theme_support('wc-product-gallery-lightbox');
    remove_theme_support('wc-product-gallery-slider');
}, 20);
add_filter('woocommerce_enqueue_styles', '__return_empty_array');


remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 10);
remove_action('woocommerce_output_content_wrapper_end', 'woocommerce_breadcrumb', 10);


// remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
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
    echo '<div class="container">';
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

    global $product;
    if (!$product || $product->is_sold_individually()) return; // nic nie dodawaj
    echo '<button class="cart-qty plus">+</button>';
});
add_filter('woocommerce_cart_item_quantity', function ($product_quantity, $cart_item_key, $cart_item) {

    // Pokazuj na koszyku i checkout
    if (! (is_cart() || is_checkout())) {
        return $product_quantity;
    }

    $product = $cart_item['data'];

    if (!$product || $product->is_sold_individually()) {
        return $product_quantity;
    }
    if ($product->get_meta('_aw_weight_enabled', true) === '1') {
        return $product_quantity;
    }
    $product_quantity =
        '<button class="cart-qty minus">-</button>'
        . $product_quantity .
        '<button class="cart-qty plus">+</button>';

    return $product_quantity;
}, 10, 3);

add_filter('woocommerce_get_stock_html', function ($html, $product) {
    if (is_product()) {
        return '';
    }
    return $html;
}, 10, 2);
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
add_action('woocommerce_single_product_summary', function () {
    global $product;

    if (!$product instanceof WC_Product) {
        return;
    }

    $description = $product->get_description();

    if (empty($description)) {
        return;
    }

    echo '<section class="aw-product-description">';
    echo apply_filters('the_content', $description);
    echo '</section>';
}, 40);
add_filter('woocommerce_add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment');

function woocommerce_header_add_to_cart_fragment($fragments)
{
    global $woocommerce;

    ob_start();
    $account_page_id = get_option('woocommerce_cart_page_id');
    $translated_id = function_exists('pll_get_post') ? pll_get_post($account_page_id) : $account_page_id;
    $account_url = get_permalink($translated_id); ?>

    <a href="<?php echo esc_url($account_url); ?>" class="cart-header"
        title="<?php esc_attr_e('Koszyk', 'start'); ?>"
        rel="noopener noreferrer"
        target="_self">
        <?php echo aw_svg('cart'); ?>
        <span class="count">
            <?php echo sprintf($woocommerce->cart->cart_contents_count); ?>
        </span>
    </a>

<?php
    $fragments['a.cart-header'] = ob_get_clean();
    ob_start();



    return $fragments;
}
add_action('wp_ajax_ajaxapplucoupon', 'aw_ajax_apply_coupon');
add_action('wp_ajax_nopriv_ajaxapplucoupon', 'aw_ajax_apply_coupon');

function aw_ajax_apply_coupon(): void
{
    if (! function_exists('WC') || ! WC()->cart) {
        wp_send_json(['result' => 'error', 'message' => 'Koszyk nie jest dostępny.']);
    }

    // (Opcjonalnie, ale bardzo polecam) nonce
    $nonce = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : '';
    if ($nonce && ! wp_verify_nonce($nonce, 'aw_checkout_coupon')) {
        wp_send_json(['result' => 'error', 'message' => 'Nieprawidłowy token bezpieczeństwa.']);
    }

    $code_raw = isset($_POST['coupon_code']) ? wp_unslash($_POST['coupon_code']) : '';
    $code     = wc_format_coupon_code(sanitize_text_field($code_raw));

    if ($code === '') {
        wp_send_json(['result' => 'error', 'message' => 'Pole z kodem kuponu nie może być puste.']);
    }

    $coupon = new WC_Coupon($code);

    // Jedyny poprawny check istnienia kuponu
    if (! $coupon->get_id()) {
        wp_send_json(['result' => 'error', 'message' => 'Nieprawidłowy kod kuponu.']);
    }

    if (WC()->cart->has_discount($code)) {
        wp_send_json(['result' => 'error', 'message' => 'Ten kupon jest już zastosowany.']);
    }

    $applied = WC()->cart->apply_coupon($code);

    if (! $applied) {
        // WooCommerce często wrzuca konkretny powód w notices
        $message = wp_strip_all_tags(wc_print_notices(true));
        wc_clear_notices();

        wp_send_json([
            'result'  => 'error',
            'message' => $message ?: 'Nie udało się zastosować kuponu.',
        ]);
    }

    WC()->cart->calculate_totals();

    wp_send_json(['result' => 'success', 'message' => 'Kupon zastosowany.']);
}


add_action('wp_ajax_aw_update_checkout_qty', 'aw_update_checkout_qty');
add_action('wp_ajax_nopriv_aw_update_checkout_qty', 'aw_update_checkout_qty');
function aw_update_checkout_qty(): void
{
    if (! WC()->cart) {
        wp_send_json_error();
    }

    $key = sanitize_text_field($_POST['cart_item_key'] ?? '');
    $qty = isset($_POST['qty']) ? (int) $_POST['qty'] : null;

    if ($key === '' || $qty === null) {
        wp_send_json_error();
    }

    WC()->cart->set_quantity($key, $qty, true);
    WC()->cart->calculate_totals();

    wp_send_json_success();
}
add_filter('wp_inline_script_attributes', function (array $attrs) {
    if (function_exists('aw_get_csp_nonce')) {
        $nonce = aw_get_csp_nonce();
        if ($nonce) {
            $attrs['nonce'] = $nonce;
        }
    }
    return $attrs;
});
