<?php
defined('ABSPATH') || exit;
remove_action('woocommerce_cart_collaterals', 'woocommerce_cart_totals', 10);
add_action('woocommerce_before_cart_collaterals', 'woocommerce_cart_totals');

add_action('wp_footer', 'cart_update_qty_script');
function cart_update_qty_script()
{
    if (is_checkout()) :
?>
        <script>
            let timeout;
            jQuery('.checkout.woocommerce-checkout').on('change', 'input.qty', function() {
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
