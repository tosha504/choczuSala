<?php // Exit if accessed directly.
defined('ABSPATH') || exit;
remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);

remove_action('woocommerce_shop_loop_header', 'woocommerce_product_taxonomy_archive_header', 10);
add_action('woocommerce_shop_loop_header', function () {
?>
    <div class="banner-shop">
        <!-- <?= my_custom_attachment_image(2792, [
                    'szie' => 'large',
                    'priority' => true,
                    'sizes'    => '(max-width: 768px) 100vw, 50vw',
                ]); ?> -->
        <h1><?= woocommerce_page_title(); ?></h1>
    </div>
<?php
}, 10);
