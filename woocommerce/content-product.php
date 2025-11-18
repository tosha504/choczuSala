<!-- <?php

        /**
         * Template: Product Card (Loop)
         * Place in: your-theme/woocommerce/content-product.php
         */

        defined('ABSPATH') || exit;

        global $product, $post;

        // Ensure visibility.
        if (empty($product) || ! $product->is_visible()) {
            return;
        }


        // Short description (excerpt)
        $short_desc = '';
        if (! empty($post->post_excerpt)) {
            $short_desc = wp_trim_words(wp_strip_all_tags($post->post_excerpt), 20, '…');
        }
        ?>
<li <?php wc_product_class('product-card', $product); ?>>

    <div class="product-card__media">
        <?php if ($product->is_on_sale() && $product->get_regular_price()) {
            $regular = (float) $product->get_regular_price();
            $sale    = (float) $product->get_sale_price();
            $off     = $regular > 0 ? round((1 - ($sale / $regular)) * 100) : 0;
            echo '<span class="product-card__badge">-' . esc_html($off) . '%</span>';
        } ?>

        <a class="product-card__thumb" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr(get_the_title()); ?>">
            <?php
            // Thumbnail with fallback.
            if (has_post_thumbnail()) {
                echo wp_get_attachment_image(
                    get_post_thumbnail_id(),
                    'woocommerce_thumbnail',
                    false,
                    ['class' => 'product-card__img', 'loading' => 'lazy']
                );
            } else {
                echo wc_placeholder_img('woocommerce_thumbnail', ['class' => 'product-card__img product-card__img--placeholder']);
            }
            ?>
        </a>
    </div>

    <div class="product-card__body">
        <h3 class="product-card__title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>

        <?php if ($short_desc) : ?>
            <p class="product-card__desc"><?php echo esc_html($short_desc); ?></p>
        <?php endif; ?>

        <div class="product-card__meta">
            <div class="product-card__rating">
                <?php
                if (function_exists('wc_get_rating_html')) {
                    echo wc_get_rating_html($product->get_average_rating()); // stars + screen-reader text
                }
                ?>
            </div>

            <div class="product-card__price">
                <?php echo $product->get_price_html(); ?>
            </div>
        </div>

        <div class="product-card__cta">
            <?php woocommerce_template_loop_add_to_cart(); ?>
        </div>
    </div>

</li> -->

<?php
/**
 * Template: Product Card (Loop)
 * Updated: with category and shortened description
 */

defined('ABSPATH') || exit;

global $product, $post;

if (empty($product) || ! $product->is_visible()) {
    return;
}

// Pobierz pierwszą kategorię produktu
$product_cats = wp_get_post_terms($product->get_id(), 'product_cat');
$cat_name = '';
$cat_link = '';
if (! empty($product_cats) && ! is_wp_error($product_cats)) {
    $cat_name = $product_cats[0]->name;
    $cat_link = get_term_link($product_cats[0]->term_id, 'product_cat');
}

// Skrócony opis do 20 słów max
$short_desc = '';
if (! empty($post->post_excerpt)) {
    $short_desc = wp_trim_words(wp_strip_all_tags($post->post_excerpt), 20, '…');
}
?>
<li <?php wc_product_class('product-card', $product); ?>>

    <div class="product-card__media">
        <?php if ($product->is_on_sale()) : ?>
            <span class="product-card__badge"><?php esc_html_e('Promocja', 'woocommerce'); ?></span>
        <?php endif; ?>

        <a class="product-card__thumb" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr(get_the_title()); ?>">
            <?php
            if (has_post_thumbnail()) {
                echo wp_get_attachment_image(
                    get_post_thumbnail_id(),
                    'full',
                    false,
                    ['class' => 'product-card__img product-card__img--full', 'loading' => 'lazy']
                );
            } else {
                echo wc_placeholder_img('woocommerce_thumbnail', ['class' => 'product-card__img product-card__img--placeholder']);
            }
            ?>
        </a>
    </div>

    <div class="product-card__body">

        <?php if ($cat_name && $cat_link) : ?>
            <a href="<?php echo esc_url($cat_link); ?>" class="product-card__cat">
                <?php echo esc_html($cat_name); ?>
            </a>
        <?php endif; ?>

        <h3 class="product-card__title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>

        <?php if ($short_desc) : ?>
            <p class="product-card__desc"><?php echo esc_html($short_desc); ?></p>
        <?php endif; ?>

        <div class="product-card__meta">
            <div class="product-card__rating">
                <?php
                if (function_exists('wc_get_rating_html')) {
                    echo wc_get_rating_html($product->get_average_rating());
                }
                ?>
            </div>

            <div class="product-card__price">
                <?php echo $product->get_price_html(); ?>
            </div>
        </div>

        <div class="product-card__cta">
            <?php woocommerce_template_loop_add_to_cart(); ?>
        </div>
    </div>

</li>