<?php

/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.4.0
 */

if (!defined('ABSPATH')) {
    exit;
}

do_action('woocommerce_before_checkout_form', $checkout);

// If checkout registration is disabled and not logged in, the user cannot checkout.
if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
    echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'start')));
    return;
}
$order_button_text = __('I buy and pay', 'start');
?>
<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">
    <?php if ($checkout->get_checkout_fields()) : ?>
        <div class="left">
            <?php do_action('woocommerce_checkout_before_customer_details'); ?>
            <?php do_action('woocommerce_checkout_billing'); ?>
            <?php do_action('woocommerce_checkout_shipping'); ?>

            <h3 class="stepCheckout-title"><?php echo __('2. Shipping', 'start'); ?></h3>
            <?php
            if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) {    ?>
                <div class="ajax-shipp-method">
                    <?php do_action('woocommerce_review_order_before_shipping'); ?>
                    <?php wc_cart_totals_shipping_html(); ?>
                    <?php do_action('woocommerce_review_order_after_shipping'); ?>
                </div>
            <?php } else {
                echo '<p class="shipping-virtual">' . esc_html__('Digital product will be sent to the provided e-mail address after the order is completed.', 'start') . '</p>';
            } ?>
            <h3 class="stepCheckout-title"><?php echo __('3. Payments', 'start'); ?></h3>
            <?php do_action('custom_payment_position'); ?>
        </div>
        <?php do_action('woocommerce_checkout_after_customer_details'); ?>

    <?php endif; ?>
    <div class="right">
        <?php do_action('woocommerce_checkout_before_order_review_heading'); ?>
        <?php do_action('woocommerce_checkout_before_order_review'); ?>
        <div>
            <h3 id="order_review_heading"><?php esc_html_e('4.Your order', 'start'); ?></h3>
            <br>
            <?php do_action('woocommerce_checkout_order_review'); ?>
            <div class="form-row place-order">
                <noscript>
                    <?php
                    /* translators: $1 and $2 opening and closing emphasis tags respectively */
                    printf(esc_html__('Since your browser does not support JavaScript, or it is disabled, please ensure you click the %1$sUpdate Totals%2$s button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'start'), '<em>', '</em>');
                    ?>
                    <br /><button type="submit" class="button alt" name="woocommerce_checkout_update_totals" value="<?php esc_attr_e('Update totals', 'start'); ?>"><?php esc_html_e('Update totals', 'start'); ?></button>
                </noscript>
                <?php
                if (wc_coupons_enabled()) { ?>
                    <div class="checkoutCopon">
                        <div class="checkoutCopon-toggleObject">
                            <p class="resoult-coupon w-100"></p>
                            <div class="checkout_coupon d-flex align-items-center" method="post">
                                <div class="couponField">
                                    <input type="text" name="coupon_code" class="input-text" placeholder="<?php esc_attr_e('Coupon code', 'start'); ?>" id="checkout_coupon_code" value="" />
                                </div>
                                <div class="couponBtn">
                                    <a href="#apply" id="checkout_apply_coupon" class="button btn-round text-uppercase"><?php esc_attr_e('Apply', 'start'); ?> </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <?php wc_get_template('checkout/terms.php'); ?>
                <?php do_action('woocommerce_review_order_before_submit'); ?>
                <?php echo apply_filters('woocommerce_order_button_html', '<button type="submit" class="button alt' . esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : '') . '" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr($order_button_text) . '" data-value="' . esc_attr($order_button_text) . '">' . esc_html($order_button_text) . '</button>'); // @codingStandardsIgnoreLine
                ?>
                <?php do_action('woocommerce_review_order_after_submit'); ?>
                <?php wp_nonce_field('woocommerce-process_checkout', 'woocommerce-process-checkout-nonce'); ?>
            </div>
        </div>

        <?php do_action('woocommerce_checkout_after_order_review'); ?>
    </div>
</form>

<?php do_action('woocommerce_after_checkout_form', $checkout); ?>