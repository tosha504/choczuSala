<?php

/**
 * Product Catalog Menu Component.
 *
 * @package start
 */

defined('ABSPATH') || exit;

if (! function_exists('aw_product_catalog_get_grouped_terms')) {
    function aw_product_catalog_get_grouped_terms(array $args = []): array
    {
        if (! taxonomy_exists('product_cat')) {
            return [];
        }

        $defaults = [
            'hide_empty'             => false,
            'exclude_uncategorized'  => true,
        ];

        $args = wp_parse_args($args, $defaults);

        $query_args = [
            'taxonomy'   => 'product_cat',
            'hide_empty' => (bool) $args['hide_empty'],
            'orderby'    => 'menu_order',
            'order'      => 'ASC',
        ];

        if (function_exists('pll_current_language')) {
            $lang = pll_current_language('slug');

            if (! empty($lang)) {
                $query_args['lang'] = $lang;
            }
        }

        if (! empty($args['exclude_uncategorized'])) {
            $default_cat_id = (int) get_option('default_product_cat');

            if ($default_cat_id > 0) {
                $query_args['exclude'] = [$default_cat_id];
            }
        }

        $cache_key = 'aw_product_catalog_terms_' . md5(wp_json_encode($query_args));
        $cached    = wp_cache_get($cache_key, 'aw_theme');

        if (is_array($cached)) {
            return $cached;
        }

        $terms = get_terms($query_args);

        if (is_wp_error($terms) || empty($terms)) {
            wp_cache_set($cache_key, [], 'aw_theme', MINUTE_IN_SECONDS * 10);
            return [];
        }

        $grouped = [];

        foreach ($terms as $term) {
            if (! $term instanceof WP_Term) {
                continue;
            }

            $parent_id = (int) $term->parent;

            if (! isset($grouped[$parent_id])) {
                $grouped[$parent_id] = [];
            }

            $grouped[$parent_id][] = $term;
        }

        wp_cache_set($cache_key, $grouped, 'aw_theme', MINUTE_IN_SECONDS * 10);

        return $grouped;
    }
}

if (! function_exists('aw_product_catalog_category_image')) {
    function aw_product_catalog_category_image(WP_Term $term, string $class = ''): string
    {
        $thumbnail_id = (int) get_term_meta($term->term_id, 'thumbnail_id', true);

        if ($thumbnail_id > 0) {
            return wp_get_attachment_image(
                $thumbnail_id,
                'thumbnail',
                false,
                [
                    'class'    => trim($class),
                    'loading'  => 'lazy',
                    'decoding' => 'async',
                    'alt'      => '',
                ]
            );
        }

        return '<span class="' . esc_attr(trim($class . ' aw-category-image-placeholder')) . '" aria-hidden="true"></span>';
    }
}

if (! function_exists('aw_product_catalog_render_desktop_children')) {
    function aw_product_catalog_render_desktop_children(int $parent_id, array $grouped_terms, int $depth = 1): void
    {
        $children = $grouped_terms[$parent_id] ?? [];

        if (empty($children)) {
            return;
        }
?>

        <ul class="aw-product-menu__submenu-list aw-product-menu__submenu-list--depth-<?php echo esc_attr((string) $depth); ?>">
            <?php foreach ($children as $child) : ?>
                <?php
                $child_link = get_term_link($child);

                if (is_wp_error($child_link)) {
                    continue;
                }

                $has_children = ! empty($grouped_terms[(int) $child->term_id]);
                ?>

                <li class="aw-product-menu__submenu-item aw-product-menu__submenu-item--depth-<?php echo esc_attr((string) $depth); ?> <?php echo $has_children ? 'aw-product-menu__submenu-item--has-children' : ''; ?>">
                    <a class="aw-product-menu__submenu-link" href="<?php echo esc_url($child_link); ?>">
                        <?php echo esc_html($child->name); ?>
                    </a>

                    <?php
                    if ($has_children) {
                        aw_product_catalog_render_desktop_children((int) $child->term_id, $grouped_terms, $depth + 1);
                    }
                    ?>
                </li>
            <?php endforeach; ?>
        </ul>

    <?php
    }
}

if (! function_exists('aw_render_product_catalog_menu')) {
    function aw_render_product_catalog_menu(array $args = []): void
    {
        if (! class_exists('WooCommerce')) {
            return;
        }

        $defaults = [
            'title'                  => __('Nasze produkty', 'start'),
            'id'                     => 'aw-product-catalog-menu',
            'hide_empty'             => false,
            'exclude_uncategorized'  => true,
        ];

        $args = wp_parse_args($args, $defaults);

        $grouped_terms = aw_product_catalog_get_grouped_terms([
            'hide_empty'            => (bool) $args['hide_empty'],
            'exclude_uncategorized' => (bool) $args['exclude_uncategorized'],
        ]);

        $top_terms = $grouped_terms[0] ?? [];

        if (empty($top_terms)) {
            return;
        }

        $menu_id  = sanitize_html_class((string) $args['id']);
        $panel_id = $menu_id . '-panel';
    ?>

        <nav class="aw-product-menu js-aw-product-menu" id="<?php echo esc_attr($menu_id); ?>" aria-label="<?php esc_attr_e('Kategorie produktów', 'start'); ?>">
            <button
                class="aw-product-menu__toggle js-aw-product-menu-toggle"
                type="button"
                aria-expanded="false"
                aria-controls="<?php echo esc_attr($panel_id); ?>">
                <span class="aw-product-menu__burger" aria-hidden="true">
                    <span></span>
                    <span></span>
                    <span></span>
                </span>

                <span class="aw-product-menu__toggle-text">
                    <?php echo esc_html((string) $args['title']); ?>
                </span>
            </button>

            <div class="aw-product-menu__panel js-aw-product-menu-panel" id="<?php echo esc_attr($panel_id); ?>" hidden>
                <ul class="aw-product-menu__list">
                    <?php foreach ($top_terms as $term) : ?>
                        <?php
                        $term_link = get_term_link($term);

                        if (is_wp_error($term_link)) {
                            continue;
                        }

                        $has_children = ! empty($grouped_terms[(int) $term->term_id]);
                        $submenu_id   = 'aw-product-submenu-' . (int) $term->term_id;
                        ?>

                        <li class="aw-product-menu__item <?php echo $has_children ? 'aw-product-menu__item--has-children' : ''; ?>">
                            <div class="aw-product-menu__item-row">
                                <a class="aw-product-menu__link" href="<?php echo esc_url($term_link); ?>">
                                    <?php echo aw_product_catalog_category_image($term, 'aw-product-menu__image'); ?>
                                    <span class="aw-product-menu__label"><?php echo esc_html($term->name); ?></span>
                                </a>

                                <?php if ($has_children) : ?>
                                    <button
                                        class="aw-product-menu__submenu-toggle js-aw-product-submenu-toggle"
                                        type="button"
                                        aria-expanded="false"
                                        aria-controls="<?php echo esc_attr($submenu_id); ?>"
                                        aria-label="<?php echo esc_attr(sprintf(__('Pokaż podkategorie: %s', 'start'), $term->name)); ?>">
                                        <span aria-hidden="true">›</span>
                                    </button>
                                <?php endif; ?>
                            </div>

                            <?php if ($has_children) : ?>
                                <div class="aw-product-menu__submenu" id="<?php echo esc_attr($submenu_id); ?>">
                                    <?php aw_product_catalog_render_desktop_children((int) $term->term_id, $grouped_terms); ?>
                                </div>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </nav>

    <?php
    }
}

if (! function_exists('aw_product_catalog_render_mobile_items')) {
    function aw_product_catalog_render_mobile_items(int $parent_id, array $grouped_terms, int $depth = 0): void
    {
        $terms = $grouped_terms[$parent_id] ?? [];

        if (empty($terms)) {
            return;
        }
    ?>

        <ul class="aw-mobile-categories__list aw-mobile-categories__list--depth-<?php echo esc_attr((string) $depth); ?>">
            <?php foreach ($terms as $term) : ?>
                <?php
                $term_link = get_term_link($term);

                if (is_wp_error($term_link)) {
                    continue;
                }

                $has_children = ! empty($grouped_terms[(int) $term->term_id]);
                $children_id  = 'aw-mobile-category-children-' . (int) $term->term_id;
                ?>

                <li class="aw-mobile-categories__item aw-mobile-categories__item--depth-<?php echo esc_attr((string) $depth); ?> <?php echo $has_children ? 'aw-mobile-categories__item--has-children' : ''; ?>">
                    <div class="aw-mobile-categories__row">
                        <a class="aw-mobile-categories__link" href="<?php echo esc_url($term_link); ?>">
                            <?php if (0 === $depth) : ?>
                                <?php echo aw_product_catalog_category_image($term, 'aw-mobile-categories__image'); ?>
                            <?php endif; ?>

                            <span class="aw-mobile-categories__label">
                                <?php echo esc_html($term->name); ?>
                            </span>
                        </a>

                        <?php if ($has_children) : ?>
                            <button
                                class="aw-mobile-categories__toggle js-aw-mobile-category-toggle"
                                type="button"
                                aria-expanded="false"
                                aria-controls="<?php echo esc_attr($children_id); ?>"
                                aria-label="<?php echo esc_attr(sprintf(__('Rozwiń kategorię: %s', 'start'), $term->name)); ?>">
                                <span aria-hidden="true">⌄</span>
                            </button>
                        <?php endif; ?>
                    </div>

                    <?php if ($has_children) : ?>
                        <div class="aw-mobile-categories__children" id="<?php echo esc_attr($children_id); ?>" hidden>
                            <?php aw_product_catalog_render_mobile_items((int) $term->term_id, $grouped_terms, $depth + 1); ?>
                        </div>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>

    <?php
    }
}

if (! function_exists('aw_render_product_catalog_mobile_tree')) {
    function aw_render_product_catalog_mobile_tree(array $args = []): void
    {
        if (! class_exists('WooCommerce')) {
            return;
        }

        $defaults = [
            'hide_empty'            => false,
            'exclude_uncategorized' => true,
        ];

        $args = wp_parse_args($args, $defaults);

        $grouped_terms = aw_product_catalog_get_grouped_terms([
            'hide_empty'            => (bool) $args['hide_empty'],
            'exclude_uncategorized' => (bool) $args['exclude_uncategorized'],
        ]);

        if (empty($grouped_terms[0])) {
            return;
        }
    ?>

        <div class="aw-mobile-categories js-aw-mobile-categories">
            <?php aw_product_catalog_render_mobile_items(0, $grouped_terms); ?>
        </div>

<?php
    }
}
