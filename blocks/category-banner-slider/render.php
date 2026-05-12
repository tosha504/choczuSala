<?php

/**
 * Category Banner Slider Block.
 *
 * @package start
 */

defined('ABSPATH') || exit;

$anchor = '';

if (! empty($block['anchor'])) {
    $anchor = 'id="' . esc_attr($block['anchor']) . '" ';
}

$class_name = 'aw-category-slider';

if (! empty($block['className'])) {
    $class_name .= ' ' . sanitize_html_class($block['className']);
}

if (! empty($block['align'])) {
    $class_name .= ' align' . sanitize_html_class($block['align']);
}

$banners = get_field('banners');

if (! is_array($banners)) {
    $banners = [];
}

$autoplay_enabled = get_field('autoplay_enabled');
$autoplay_enabled = null === $autoplay_enabled ? true : (bool) $autoplay_enabled;

$autoplay_delay = absint(get_field('autoplay_delay') ?: 4500);
$autoplay_delay = max(2500, min($autoplay_delay, 12000));

$show_arrows = get_field('show_arrows');
$show_arrows = null === $show_arrows ? true : (bool) $show_arrows;

$show_pagination = get_field('show_pagination');
$show_pagination = null === $show_pagination ? true : (bool) $show_pagination;

$height = sanitize_key(get_field('height') ?: 'default');

if (! in_array($height, ['compact', 'default', 'tall'], true)) {
    $height = 'default';
}

$slides = [];

foreach ($banners as $banner) {
    $image_desktop = absint($banner['image_desktop'] ?? 0);
    $image_mobile  = absint($banner['image_mobile'] ?? 0);

    if ($image_desktop <= 0) {
        continue;
    }

    $link_type = sanitize_key((string) ($banner['link_type'] ?? 'category'));

    if (! in_array($link_type, ['category', 'product', 'custom_url'], true)) {
        $link_type = 'category';
    }

    $link       = '';
    $link_label = '';
    $link_title = '';
    $aria_label = '';
    $target     = ! empty($banner['open_in_new_tab']) ? '_blank' : '_self';
    $rel        = '_blank' === $target ? 'noopener noreferrer' : '';

    if ('category' === $link_type) {
        $category = $banner['category'] ?? 0;

        if ($category instanceof WP_Term) {
            $category_id = (int) $category->term_id;
        } else {
            $category_id = absint($category);
        }

        if ($category_id <= 0) {
            continue;
        }

        $term = get_term($category_id, 'product_cat');

        if (! $term instanceof WP_Term || is_wp_error($term)) {
            continue;
        }

        $term_link = get_term_link($term);

        if (is_wp_error($term_link)) {
            continue;
        }

        $link       = $term_link;
        $link_label = $term->name;
        $link_title = $term->name;
        $aria_label = sprintf(__('Przejdź do kategorii: %s', 'start'), $term->name);
    }

    if ('product' === $link_type) {
        $product_id = absint($banner['product'] ?? 0);

        if ($product_id <= 0 || 'product' !== get_post_type($product_id)) {
            continue;
        }

        $product_link = get_permalink($product_id);

        if (! $product_link) {
            continue;
        }

        $product_title = get_the_title($product_id);

        $link       = $product_link;
        $link_label = $product_title;
        $link_title = $product_title;
        $aria_label = sprintf(__('Przejdź do produktu: %s', 'start'), $product_title);
    }

    if ('custom_url' === $link_type) {
        $custom_url = trim((string) ($banner['custom_url'] ?? ''));

        if ('' === $custom_url) {
            continue;
        }

        $link       = esc_url_raw($custom_url);
        $link_label = __('Zobacz więcej', 'start');
        $link_title = __('Zobacz więcej', 'start');
        $aria_label = __('Przejdź do wybranej strony', 'start');
    }

    if ('' === $link) {
        continue;
    }

    $title = trim((string) ($banner['title'] ?? ''));

    if ('' === $title) {
        $title = $link_title;
    }

    $button_label = trim((string) ($banner['button_label'] ?? ''));

    if ('' === $button_label) {
        if ('category' === $link_type) {
            $button_label = __('Zobacz kategorię', 'start');
        } elseif ('product' === $link_type) {
            $button_label = __('Zobacz produkt', 'start');
        } else {
            $button_label = __('Zobacz więcej', 'start');
        }
    }

    $slides[] = [
        'image_desktop' => $image_desktop,
        'image_mobile'  => $image_mobile,
        'link'          => $link,
        'link_type'     => $link_type,
        'link_label'    => $link_label,
        'aria_label'    => $aria_label,
        'target'        => $target,
        'rel'           => $rel,
        'eyebrow'       => trim((string) ($banner['eyebrow'] ?? '')),
        'title'         => $title,
        'description'   => trim((string) ($banner['description'] ?? '')),
        'button_label'  => $button_label,
    ];
}

if (empty($slides)) {
    if (! empty($is_preview)) {
        echo '<div class="aw-category-slider-empty">' . esc_html__('Dodaj przynajmniej jeden poprawny banner.', 'start') . '</div>';
    }

    return;
}

$block_id = ! empty($block['id'])
    ? sanitize_html_class($block['id'])
    : 'aw-category-slider-' . wp_rand(1000, 9999);

$has_multiple_slides = count($slides) > 1;
$use_autoplay        = $autoplay_enabled && $has_multiple_slides;
?>

<section
    <?php echo $anchor; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
    ?>
    class="<?php echo esc_attr($class_name . ' aw-category-slider--' . $height); ?>"
    data-block-id="<?php echo esc_attr($block_id); ?>">
    <div class="container">
        <div
            class="swiper aw-category-slider__swiper js-aw-category-slider"
            data-autoplay="<?php echo esc_attr($use_autoplay ? '1' : '0'); ?>"
            data-delay="<?php echo esc_attr((string) $autoplay_delay); ?>">
            <div class="swiper-wrapper aw-category-slider__wrapper">
                <?php foreach ($slides as $index => $slide) : ?>
                    <a
                        class="swiper-slide aw-category-slider__slide"
                        href="<?php echo esc_url($slide['link']); ?>"
                        aria-label="<?php echo esc_attr($slide['aria_label']); ?>"
                        <?php if ('_blank' === $slide['target']) : ?>
                        target="_blank"
                        rel="<?php echo esc_attr($slide['rel']); ?>"
                        <?php endif; ?>>
                        <span class="aw-category-slider__media" aria-hidden="true">
                            <picture>
                                <?php if ($slide['image_mobile'] > 0) : ?>
                                    <?php
                                    $mobile_srcset = wp_get_attachment_image_srcset($slide['image_mobile'], 'large');
                                    $mobile_src    = wp_get_attachment_image_url($slide['image_mobile'], 'large');
                                    ?>

                                    <?php if ($mobile_srcset || $mobile_src) : ?>
                                        <source
                                            media="(max-width: 767px)"
                                            srcset="<?php echo esc_attr($mobile_srcset ?: $mobile_src); ?>"
                                            sizes="100vw">
                                    <?php endif; ?>
                                <?php endif; ?>

                                <?php
                                if (function_exists('my_custom_attachment_image')) {
                                    echo my_custom_attachment_image(
                                        $slide['image_desktop'],
                                        [
                                            'size'     => 'full',
                                            'class'    => 'aw-category-slider__image',
                                            'priority' => 0 === $index,
                                            'preload'  => 0 === $index && empty($is_preview),
                                            'sizes'    => '(max-width: 767px) 100vw, min(100vw, 1140px)',
                                        ]
                                    );
                                } else {
                                    echo wp_get_attachment_image(
                                        $slide['image_desktop'],
                                        'full',
                                        false,
                                        [
                                            'class'         => 'aw-category-slider__image',
                                            'loading'       => 0 === $index ? 'eager' : 'lazy',
                                            'fetchpriority' => 0 === $index ? 'high' : 'auto',
                                            'decoding'      => 'async',
                                            'sizes'         => '(max-width: 767px) 100vw, min(100vw, 1140px)',
                                        ]
                                    );
                                }
                                ?>
                            </picture>
                        </span>

                        <span class="aw-category-slider__overlay"></span>

                        <span class="aw-category-slider__content">
                            <?php if (! empty($slide['eyebrow'])) : ?>
                                <span class="aw-category-slider__eyebrow">
                                    <?php echo esc_html($slide['eyebrow']); ?>
                                </span>
                            <?php endif; ?>

                            <span class="aw-category-slider__title">
                                <?php echo esc_html($slide['title']); ?>
                            </span>

                            <?php if (! empty($slide['description'])) : ?>
                                <span class="aw-category-slider__description">
                                    <?php echo wp_kses_post(wpautop($slide['description'])); ?>
                                </span>
                            <?php endif; ?>

                            <span class="aw-category-slider__button">
                                <?php echo esc_html($slide['button_label']); ?>
                            </span>
                        </span>
                    </a>
                <?php endforeach; ?>
            </div>

            <?php if ($has_multiple_slides && $show_pagination) : ?>
                <div class="swiper-pagination aw-category-slider__pagination"></div>
            <?php endif; ?>
        </div>

        <?php if ($has_multiple_slides && $show_arrows) : ?>
            <div class="aw-category-slider__arrows" aria-hidden="false">
                <button class="aw-category-slider__arrow aw-category-slider__arrow--prev" type="button" aria-label="<?php esc_attr_e('Poprzedni banner', 'start'); ?>">
                    ‹
                </button>

                <button class="aw-category-slider__arrow aw-category-slider__arrow--next" type="button" aria-label="<?php esc_attr_e('Następny banner', 'start'); ?>">
                    ›
                </button>
            </div>
        <?php endif; ?>
    </div>
</section>