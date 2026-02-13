<?php

/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 10.1.0
 */

defined('ABSPATH') || exit;
do_action('woocommerce_before_cart'); ?>

<div class="container">
    <form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
        <?php do_action('woocommerce_before_cart_table'); ?>

        <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
            <thead>
                <tr>
                    <th class="product-remove">&nbsp;</th>
                    <th class="product-thumbnail">&nbsp;</th>
                    <th class="product-name"><?php esc_html_e('Product', 'woocommerce'); ?></th>
                    <th class="product-price"><?php esc_html_e('Price', 'woocommerce'); ?></th>
                    <th class="product-quantity"><?php esc_html_e('Quantity', 'woocommerce'); ?></th>
                    <th class="product-subtotal"><?php esc_html_e('Subtotal', 'woocommerce'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php do_action('woocommerce_before_cart_contents'); ?>

                <?php
                foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                    $_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                    $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

                    if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
                        $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
                ?>
                        <tr class="woocommerce-cart-form__cart-item <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">

                            <td class="product-remove">
                                <?php
                                echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                    'woocommerce_cart_item_remove_link',
                                    sprintf(
                                        '<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
                                        esc_url(wc_get_cart_remove_url($cart_item_key)),
                                        esc_html__('Remove this item', 'woocommerce'),
                                        esc_attr($product_id),
                                        esc_attr($_product->get_sku())
                                    ),
                                    $cart_item_key
                                );
                                ?>
                            </td>

                            <td class="product-thumbnail">
                                <?php
                                $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);

                                if (! $product_permalink) {
                                    echo $thumbnail; // PHPCS: XSS ok.
                                } else {
                                    printf('<a href="%s">%s</a>', esc_url($product_permalink), $thumbnail); // PHPCS: XSS ok.
                                }
                                ?>
                            </td>

                            <td class="product-name" data-title="<?php esc_attr_e('Product', 'woocommerce'); ?>">
                                <?php
                                if (! $product_permalink) {
                                    echo wp_kses_post(apply_filters('woocommerce_cart_item_name', wp_trim_words($_product->get_name(), 3, '…'), $cart_item, $cart_item_key) . '&nbsp;');
                                } else {
                                    echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', esc_url($product_permalink), wp_trim_words($_product->get_name(), 3, '…')), $cart_item, $cart_item_key));
                                }

                                do_action('woocommerce_after_cart_item_name', $cart_item, $cart_item_key);

                                // Meta data.
                                echo wc_get_formatted_cart_item_data($cart_item); // PHPCS: XSS ok.

                                // Backorder notification.
                                if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity'])) {
                                    echo wp_kses_post(apply_filters('woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__('Available on backorder', 'woocommerce') . '</p>', $product_id));
                                }
                                ?>
                            </td>

                            <td class="product-price" data-title="<?php esc_attr_e('Price', 'woocommerce'); ?>">
                                <?php
                                echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key); // PHPCS: XSS ok.
                                ?>
                            </td>

                            <td class="product-quantity" data-title="<?php esc_attr_e('Quantity', 'woocommerce'); ?>">
                                <?php
                                if ($_product->is_sold_individually()) {
                                    $product_quantity = sprintf('1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key);
                                } else {
                                    $product_quantity = woocommerce_quantity_input(
                                        array(
                                            'input_name'   => "cart[{$cart_item_key}][qty]",
                                            'input_value'  => $cart_item['quantity'],
                                            'max_value'    => $_product->get_max_purchase_quantity(),
                                            'min_value'    => '0',
                                            'product_name' => $_product->get_name(),
                                        ),
                                        $_product,
                                        false
                                    );
                                }

                                echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item); // PHPCS: XSS ok.
                                ?>
                            </td>

                            <td class="product-subtotal" data-title="<?php esc_attr_e('Subtotal', 'woocommerce'); ?>">
                                <?php
                                echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); // PHPCS: XSS ok.
                                ?>
                            </td>
                        </tr>
                        <tr>

                        </tr>
                <?php
                    }
                }
                ?>

                <?php do_action('woocommerce_cart_contents'); ?>

                <td colspan="6" class="actions">
                    <?php if (wc_coupons_enabled()) : ?>
                        <div class="aw-coupon" data-aw-coupon>
                            <button
                                type="button"
                                class="button"
                                data-aw-coupon-toggle
                                aria-expanded="false"
                                data-label-show="<?php echo esc_attr(__('Mam kupon', 'start')); ?>"
                                data-label-hide="<?php echo esc_attr(__('Ukryj kupon', 'start')); ?>">
                                <?php echo esc_html(__('Mam kupon', 'start')); ?>
                            </button>

                            <div class="aw-coupon__panel" data-aw-coupon-panel hidden>
                                <label for="coupon_code"><?php esc_html_e('Coupon:', 'woocommerce'); ?></label>
                                <div class="coupon">
                                    <input type="text" name="coupon_code" class="input-text" id="coupon_code" value=""
                                        placeholder="<?php esc_attr_e('Coupon code', 'woocommerce'); ?>" />
                                    <button type="submit" class="btn__primary button" name="apply_coupon"
                                        value="<?php esc_attr_e('Apply coupon', 'woocommerce'); ?>">
                                        <?php esc_attr_e('Apply coupon', 'woocommerce'); ?>
                                    </button>

                                    <?php do_action('woocommerce_cart_coupon'); ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <script>
                        (() => {
                            const SELECTOR_ROOT = '[data-aw-coupon]';
                            const SELECTOR_TOGGLE = '[data-aw-coupon-toggle]';
                            const SELECTOR_PANEL = '[data-aw-coupon-panel]';

                            const isCouponApplied = () =>
                                document.querySelector('.cart-discount, .woocommerce-remove-coupon') !== null;

                            const setOpenState = (root, open) => {
                                const toggle = root.querySelector(SELECTOR_TOGGLE);
                                const panel = root.querySelector(SELECTOR_PANEL);
                                const input = root.querySelector('#coupon_code');
                                if (!toggle || !panel) return;

                                panel.hidden = !open;
                                toggle.setAttribute('aria-expanded', open ? 'true' : 'false');

                                const label = open ? toggle.getAttribute('data-label-hide') : toggle.getAttribute('data-label-show');
                                if (label) toggle.textContent = label;

                                if (open && input) input.focus();
                            };

                            const initCouponUI = () => {
                                const root = document.querySelector(SELECTOR_ROOT);
                                if (!root) return;

                                // Stan po odświeżeniu fragmentów: jeśli kupon aktywny albo coś wpisane -> ma zostać otwarte
                                const input = root.querySelector('#coupon_code');
                                const hasTyped = input && input.value && input.value.trim().length > 0;

                                setOpenState(root, isCouponApplied() || hasTyped);
                            };

                            // Delegacja kliknięć (działa nawet po podmianie fragmentu)
                            document.addEventListener('click', (e) => {
                                const toggle = e.target.closest(SELECTOR_TOGGLE);
                                if (!toggle) return;

                                const root = toggle.closest(SELECTOR_ROOT);
                                if (!root) return;

                                const panel = root.querySelector(SELECTOR_PANEL);
                                if (!panel) return;

                                setOpenState(root, panel.hidden); // toggle
                            });

                            // WooCommerce po apply_coupon robi AJAX i podmienia HTML koszyka -> trzeba przywrócić stan UI
                            document.body.addEventListener('updated_wc_div', initCouponUI);
                            document.body.addEventListener('wc_fragments_refreshed', initCouponUI);

                            // Start
                            initCouponUI();
                        })();
                    </script>

                    <button type="submit" class="button" name="update_cart" value="<?php esc_attr_e('Update cart', 'woocommerce'); ?>"><?php esc_html_e('Update cart', 'woocommerce'); ?></button>

                    <?php do_action('woocommerce_cart_actions'); ?>

                    <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
                </td>

                <?php do_action('woocommerce_after_cart_contents'); ?>
            </tbody>
        </table>
        <?php do_action('woocommerce_after_cart_table'); ?>
    </form>

    <?php do_action('woocommerce_before_cart_collaterals');
    if (count(WC()->cart->get_cross_sells()) > 0) {  ?>

        <div class="cart-collaterals">
            <?php
            /**
             * Cart collaterals hook.
             *
             * @hooked woocommerce_cross_sell_display
             * @hooked woocommerce_cart_totals - 10
             */
            do_action('woocommerce_cart_collaterals');
            ?>
        </div>
    <?php } ?>
    <?php do_action('woocommerce_after_cart'); ?>
</div>