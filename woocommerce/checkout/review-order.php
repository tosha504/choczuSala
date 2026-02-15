<?php

/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
 */

defined('ABSPATH') || exit;

?>
<table class="shop_table woocommerce-checkout-review-order-table">
    <tbody>
        <?php
        do_action('woocommerce_review_order_before_cart_contents');

        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
            $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);

            if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key)) { ?>
                <tr class="<?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">

                    <td class="product-thumbnail">
                        <?php
                        $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);
                        if (!$_product->get_permalink()) {
                            echo $thumbnail; // PHPCS: XSS ok.
                        } else {
                            printf('<a href="%s">%s</a>', esc_url($_product->get_permalink()), $thumbnail); // PHPCS: XSS ok.
                        }
                        ?>
                    </td>

                    <td class="product-name-quantity">
                        <div class="product-name">
                            <?php echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key))  ?>
                            <?php echo wc_get_formatted_cart_item_data($cart_item); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            ?>
                        </div>

                        <div class="product-quantity" data-title="<?php esc_attr_e('Quantity', 'woocommerce'); ?>">
                            <?php
                            if ($_product->is_sold_individually()) {
                                $min_quantity = 1;
                                $max_quantity = 1;
                            } else {
                                $min_quantity = 0;
                                $max_quantity = $_product->get_max_purchase_quantity();
                            }

                            $product_quantity = woocommerce_quantity_input(
                                array(
                                    'input_name'   => $cart_item_key,
                                    'input_value'  => $cart_item['quantity'],
                                    'max_value'    => $max_quantity,
                                    'min_value'    => $min_quantity,
                                    'product_name' => $_product->get_name(),
                                ),
                                $_product,
                                false
                            );

                            echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item); // PHPCS: XSS ok.
                            ?>
                        </div>
                    </td>

                    <td class="wrap">
                        <div class="product-remove">
<?php
	echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		'woocommerce_cart_item_remove_link',
		sprintf(
			'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
			esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
			esc_attr__( 'Remove this item', 'woocommerce' ),
			esc_attr( $_product->get_id() ),
			esc_attr( $_product->get_sku() )
		),
		$cart_item_key
	);
	?>
                        </div>

                        <div class="product-total">
                            <?php echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            ?>
                        </div>
                    </td>

                </tr>
        <?php
            }
        }
        do_action('woocommerce_review_order_after_cart_contents');
        ?>
    </tbody>
    <tfoot>

        <tr class="cart-subtotal">
            <th><?php esc_html_e('Subtotal', 'woocommerce'); ?></th>
            <td><?php wc_cart_totals_subtotal_html(); ?></td>
        </tr>

        <?php foreach (WC()->cart->get_coupons() as $code => $coupon) :  ?>
            <tr class="cart-discount coupon-<?php echo esc_attr(sanitize_title($code)); ?>">
                <th><?php wc_cart_totals_coupon_label($coupon); ?></th>
                <td><?php wc_cart_totals_coupon_html($coupon); ?></td>
            </tr>
        <?php endforeach; ?>


        <?php foreach (WC()->cart->get_fees() as $fee) : ?>
            <tr class="fee">
                <th><?php echo esc_html($fee->name); ?></th>
                <td><?php wc_cart_totals_fee_html($fee); ?></td>
            </tr>
        <?php endforeach; ?>

        <?php if (wc_tax_enabled() && !WC()->cart->display_prices_including_tax()) : ?>
            <?php if ('itemized' === get_option('woocommerce_tax_total_display')) : ?>
                <?php foreach (WC()->cart->get_tax_totals() as $code => $tax) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
                ?>
                    <tr class="tax-rate tax-rate-<?php echo esc_attr(sanitize_title($code)); ?>">
                        <th><?php echo esc_html($tax->label); ?></th>
                        <td><?php echo wp_kses_post($tax->formatted_amount); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr class="tax-total">
                    <th><?php echo esc_html(WC()->countries->tax_or_vat()); ?></th>
                    <td><?php wc_cart_totals_taxes_total_html(); ?></td>
                </tr>
            <?php endif; ?>
        <?php endif; ?>

        <?php do_action('woocommerce_review_order_before_order_total'); ?>

        <?php if (WC()->cart->needs_shipping()) : ?>
            <tr class="order-shipping">
                <th><?php esc_html_e('Shipping', 'woocommerce'); ?></th>
                <td data-title="<?php esc_attr_e('Shipping', 'woocommerce'); ?>">
                    <?php echo wp_kses_post(aw_checkout_selected_shipping_label()); ?>
                </td>
            </tr>
        <?php endif; ?>
        <tr class="order-total">
            <th><?php esc_html_e('Total', 'woocommerce'); ?></th>
            <td><?php wc_cart_totals_order_total_html(); ?></td>
        </tr>

        <?php do_action('woocommerce_review_order_after_order_total'); ?>

    </tfoot>
</table>