<?php
defined('ABSPATH') || exit;

if (empty($results) || !is_array($results)) {
    return;
}

$count = count($results);
?>
<div class="aw-live-search">
    <div class="aw-live-search__meta">
        <?php echo esc_html($count); ?> wynik(ów)
    </div>

    <div class="aw-live-search__list">
        <?php foreach ($results as $post) : ?>
            <?php
            if (!$post instanceof WP_Post) {
                continue;
            }

            $product = wc_get_product($post->ID);

            if (!$product instanceof WC_Product) {
                continue;
            }

            $permalink = get_permalink($post->ID);
            $title     = get_the_title($post->ID);
            $thumb     = get_the_post_thumbnail($post->ID, 'thumbnail', [
                'class'   => 'aw-live-search__thumb-image',
                'loading' => 'lazy',
                'alt'     => $title,
            ]);
            ?>
            <article class="aw-live-search__item">
                <a class="aw-live-search__link" href="<?php echo esc_url($permalink); ?>">
                    <div class="aw-live-search__thumb">
                        <?php if ($thumb) : ?>
                            <?php echo $thumb; ?>
                        <?php else : ?>
                            <div class="aw-live-search__thumb-placeholder" aria-hidden="true"></div>
                        <?php endif; ?>
                    </div>

                    <div class="aw-live-search__content">
                        <h3 class="aw-live-search__title">
                            <?php echo esc_html($title); ?>
                        </h3>

                        <?php if ($product->get_price_html()) : ?>
                            <div class="aw-live-search__price">
                                <?php echo wp_kses_post($product->get_price_html()); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </a>
            </article>
        <?php endforeach; ?>
    </div>
</div>