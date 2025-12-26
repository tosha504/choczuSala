<?php

/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.6.0
 */

defined('ABSPATH') || exit;

get_header('shop');
/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action('woocommerce_before_main_content');

?>
<div class="container">
    <?php
    /**
     * Hook: woocommerce_shop_loop_header.
     *
     * @since 8.6.0
     *
     * @hooked woocommerce_product_taxonomy_archive_header - 10
     */
    do_action('woocommerce_shop_loop_header');

    if (woocommerce_product_loop()) {

        /**
         * Hook: woocommerce_before_shop_loop.
         *
         * @hooked woocommerce_output_all_notices - 10
         * @hooked woocommerce_result_count - 20
         * @hooked woocommerce_catalog_ordering - 30
         */
        do_action('woocommerce_before_shop_loop');

    ?></div>
<div class="container">
    <div class="wrap-products">
        <aside class="custom-sidebar-shop" data-shop-filter>
            <button class="button" type="button" data-shop-filter-open>
                <?= esc_html__('Filter', 'start'); ?>
            </button>

            <div class="custom-sidebar-shop__panel" data-shop-filter-panel>
                <button type="button" class="custom-sidebar-shop__close" data-shop-filter-close>
                    <?php esc_html_e('Close', 'start'); ?>
                </button>

                <?php dynamic_sidebar('left-sidebar'); ?>
            </div>

            <div class="custom-sidebar-shop__backdrop" data-shop-filter-backdrop hidden></div>
        </aside>
        <script>
            (() => {
                const root = document.querySelector('[data-shop-filter]');
                if (!root) return;

                const openBtn = root.querySelector('[data-shop-filter-open]');
                const closeBtn = root.querySelector('[data-shop-filter-close]');
                const panel = root.querySelector('[data-shop-filter-panel]');
                const backdrop = root.querySelector('[data-shop-filter-backdrop]');

                const MQ = window.matchMedia('(max-width: 767px)');

                const setBackdropHidden = (hidden) => {
                    if (!backdrop) return;
                    backdrop.hidden = hidden;
                };

                const open = () => {
                    if (!MQ.matches) return; // tylko mobile
                    root.classList.add('is-open');
                    document.body.classList.add('fixed-page');
                    setBackdropHidden(false);

                    // focus na panel (UX)
                    const focusable = panel?.querySelector('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
                    focusable?.focus?.();
                };

                const close = () => {
                    root.classList.remove('is-open');
                    document.body.classList.remove('fixed-page');
                    setBackdropHidden(true);
                    openBtn?.focus?.();
                };

                openBtn?.addEventListener('click', (e) => {
                    e.preventDefault();
                    open();
                });

                closeBtn?.addEventListener('click', (e) => {
                    e.preventDefault();
                    close();
                });

                backdrop?.addEventListener('click', close);

                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape') close();
                });

                // Gdy przechodzisz na >=768px – resetuj stan, żeby nie zostało overflow hidden
                const syncWithViewport = () => {
                    if (!MQ.matches) close();
                    else setBackdropHidden(!root.classList.contains('is-open'));
                };

                // inicjalizacja
                syncWithViewport();

                // matchMedia change (nowoczesne)
                MQ.addEventListener?.('change', syncWithViewport);
                // fallback
                window.addEventListener('resize', syncWithViewport);
            })();
        </script>
    <?php
        woocommerce_product_loop_start();

        if (wc_get_loop_prop('total')) {
            while (have_posts()) {
                the_post();

                /**
                 * Hook: woocommerce_shop_loop.
                 */
                // do_action( 'woocommerce_shop_loop' );

                wc_get_template_part('content', 'product');
            }
        }

        woocommerce_product_loop_end();
        echo '</div>';

        /**
         * Hook: woocommerce_after_shop_loop.
         *
         * @hooked woocommerce_pagination - 10
         */
        do_action('woocommerce_after_shop_loop');
    } else {
        /**
         * Hook: woocommerce_no_products_found.
         *
         * @hooked wc_no_products_found - 10
         */
        do_action('woocommerce_no_products_found');
    }

    /**
     * Hook: woocommerce_after_main_content.
     *
     * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
     */

    ?>
    </div>
    <?php
    do_action('woocommerce_after_main_content');

    /**
     * Hook: woocommerce_sidebar.
     *
     * @hooked woocommerce_get_sidebar - 10
     */
    do_action('woocommerce_sidebar');

    get_footer('shop');
