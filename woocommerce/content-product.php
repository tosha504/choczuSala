<?php

/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.4.0
 */

defined('ABSPATH') || exit;

global $product;
if (empty($product) || !$product->is_visible()) return;

$classes = ['wc-card__inner'];

if ($product instanceof WC_Product) {
    if ($product->is_featured() || $product->is_on_sale()) {
        $classes[] = 'is-highlighted';
    }
}
$permalink = apply_filters('woocommerce_loop_product_link', get_the_permalink(), $product); ?>

<li <?php wc_product_class('wc-card', $product); ?>>
    <article class="<?php echo esc_attr(implode(' ', $classes)); ?> ">

        <a href="<?php echo esc_url($permalink); ?>" class="wc-card__thumb" aria-label="<?php the_title_attribute(); ?>">
            <?php aw_wc_render_custom_badges_overlay($product->get_id()); ?>
            <?php
            $image_id   = aw_get_product_image_fallback_id($product->get_ID());
            // Miniatura z zachowaniem proporcji (obj-cover)
            if (has_post_thumbnail()) {
                // the_post_thumbnail('woocommerce_single', ['class' => 'wc-card__img', 'alt' => get_the_title()]);
                echo wp_get_attachment_image(
                    $image_id,
                    'aw_card_1x1',
                    false,
                    [
                        'class' => 'wc-card__img',
                        'sizes' => '(max-width: 600px) 50vw, (max-width: 1200px) 25vw, 360px',
                    ]
                );
            } else {


                // echo wp_get_attachment_image(
                //     $image_id,
                //     'woocommerce_single',
                //     false,
                //     [
                //         'class' => 'wc-card__img',
                //         'loading' => 'lazy',
                //         'decoding' => 'async',
                //     ]
                // );
                echo wp_get_attachment_image(
                    $image_id,
                    'aw_card_1x1',
                    false,
                    [
                        'class' => 'wc-card__img',
                        'sizes' => '(max-width: 600px) 50vw, (max-width: 1200px) 25vw, 360px',
                    ]
                );
            }
            ?>
        </a>

        <div class="wc-card__body">
            <h3 class="wc-card__title">
                <a href="<?php echo esc_url($permalink); ?>"><?php the_title(); ?></a>
            </h3>

            <?php
            // krótki opis (excerpt) – bez shortcode’ów
            $short = apply_filters('woocommerce_short_description', $post->post_excerpt);
            $short_5_words = wp_trim_words($short, 4, '…');
            if (!empty($short)) : ?>
                <div class="wc-card__excerpt"><?php echo wp_kses_post(wpautop($short_5_words)); ?></div>
            <?php endif; ?>

            <div class="wc-card__price">
                <?php woocommerce_template_loop_price(); ?>
            </div>

            <div class="wc-card__cta">
                <?php woocommerce_template_loop_add_to_cart(); ?>
            </div>
        </div>

    </article>
</li>