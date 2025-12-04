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

$permalink = apply_filters('woocommerce_loop_product_link', get_the_permalink(), $product);
?>

<li <?php wc_product_class('wc-card', $product); ?>>
    <article class="wc-card__inner">

        <a href="<?php echo esc_url($permalink); ?>" class="wc-card__thumb" aria-label="<?php the_title_attribute(); ?>">
            <?php
            // Miniatura z zachowaniem proporcji (obj-cover)
            if (has_post_thumbnail()) {
                the_post_thumbnail('large', ['class' => 'wc-card__img', 'alt' => get_the_title()]);
            } else {
                echo wc_placeholder_img('full', ['class' => 'wc-card__img']);
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
            if (!empty($short)) : ?>
                <div class="wc-card__excerpt"><?php echo wp_kses_post(wpautop($short)); ?></div>
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