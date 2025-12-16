<?php

/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined('ABSPATH') || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action('woocommerce_before_single_product');

if (post_password_required()) {
    echo get_the_password_form(); // WPCS: XSS ok.
    return;
}
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class('', $product); ?>>

    <div class="container">
        <div class="product-top-data">
            <?php
            /**
             * Hook: woocommerce_before_single_product_summary.
             *
             * @hooked woocommerce_show_product_sale_flash - 10
             * @hooked woocommerce_show_product_images - 20
             */
            do_action('woocommerce_before_single_product_summary'); ?>

            <div class="summary entry-summary">

                <?php
                /**
                 * Hook: woocommerce_single_product_summary.
                 *
                 * @hooked woocommerce_template_single_title - 5
                 * @hooked woocommerce_template_single_rating - 10
                 * @hooked woocommerce_template_single_price - 10
                 * @hooked woocommerce_template_single_excerpt - 20
                 * @hooked woocommerce_template_single_add_to_cart - 30
                 * @hooked woocommerce_template_single_meta - 40
                 * @hooked woocommerce_template_single_sharing - 50
                 * @hooked WC_Structured_Data::generate_product_data() - 60
                 */
                do_action('woocommerce_single_product_summary');
                ?>
            </div>
        </div>
        <?php $id = get_the_ID();
        $tabs = get_field('tabs', $id);
        $tabs = get_field('tabs', $id);
        $tabs = get_field('tabs', $id); ?>
        <section class="aw-product-section">
            <?php if (!empty($tabs) && count($tabs) > 0) { ?>
                <div class="aw-tabs">
                    <?php foreach ($tabs as $key => $item) {
                        if (!empty($item["link"]["title"])) { ?>
                            <button class="aw-tab <?= $key === 0 ? "active" : ""; ?>" data-url="<?= !empty($item["link"]["url"]) ? $item["link"]["url"] : ""; ?>"><?= $item["link"]["title"]; ?></button>
                    <?php }
                    } ?>
                </div>
            <?php  }
            $tag = get_field('tag', $id);
            $text_title = get_field('text_title', $id);
            $color_text = get_field('color_text', $id);
            $class = get_field('class', $id);
            $description = !empty(get_field('description', $id)) ? '<div class="aw-description">' . get_field('description', $id) . '</div>' : "";            ?>

            <div class="aw-content">
                <?php echo show_title($tag, $text_title, $color_text, $class);
                echo  $description; ?>
            </div>
        </section>
        <?php //single_product_elements_templates(); 
        ?>

        <?php
        /**
         * Hook: woocommerce_after_single_product_summary.
         *
         * @hooked woocommerce_output_product_data_tabs - 10
         * @hooked woocommerce_upsell_display - 15
         * @hooked woocommerce_output_related_products - 20
         */
        do_action('woocommerce_after_single_product_summary');
        ?>
    </div>
    <?php
    /**
     * AW: Blok „Dane techniczne” z danych produktu WooCommerce
     */

    if (!function_exists('aw_render_tech_specs')) {
        function aw_render_tech_specs($product = null)
        {
            if (!function_exists('wc_get_product')) {
                return; // WooCommerce nieaktywne
            }

            if (!$product) {
                $product = wc_get_product(get_the_ID());
            }
            if (!$product) {
                return;
            }

            $rows = [];

            // 1) WAGA
            if ($product->has_weight()) {
                $rows[] = [
                    'label' => __('Waga', 'aw'),
                    'value' => wc_format_weight($product->get_weight()),
                ];
            }

            // 2) WYMIARY (L × W × H)
            $length = $product->get_length();
            $width  = $product->get_width();
            $height = $product->get_height();

            if ($length || $width || $height) {
                // Ładny format z jednostką
                $dim = wc_format_dimensions([
                    'length' => $length,
                    'width'  => $width,
                    'height' => $height,
                ]);
                if (!empty($dim) && $dim !== __('N/A', 'woocommerce')) {
                    $rows[] = [
                        'label' => __('Wymiary', 'aw'),
                        'value' => $dim,
                    ];
                }
            }

            // 3) MARKA: spróbuj kilka popularnych miejsc
            $brand_value = '';
            // a) Atrybut pa_marka / pa_brand
            // foreach (['pa_marka', 'pa-brand', 'pa_brand'] as $brand_attr_slug) {
            //     $val = trim(wc_get_formatted_product_attribute($product, $brand_attr_slug));
            //     if (!$val) {
            //         // ręcznie z attrybutu (dla custom/simple attr)
            //         $val = trim($product->get_attribute($brand_attr_slug));
            //     }
            //     if ($val) {
            //         $brand_value = $val;
            //         break;
            //     }
            // }
            // b) Taksonomia product_brand (np. WooCommerce Brands/Perfect Brands)
            // if (!$brand_value && taxonomy_exists('product_brand')) {
            //     $terms = wc_get_product_terms($product->get_id(), 'product_brand', ['fields' => 'names']);
            //     if (!is_wp_error($terms) && !empty($terms)) {
            //         $brand_value = implode(', ', $terms);
            //     }
            // }
            // if ($brand_value) {
            //     $rows[] = [
            //         'label' => __('Marka', 'aw'),
            //         'value' => $brand_value,
            //     ];
            // }

            // 4) POZOSTAŁE WIDOCZNE ATRYBUTY (automatycznie)
            $attributes = $product->get_attributes();
            if (!empty($attributes)) {
                foreach ($attributes as $attribute) {
                    if (!method_exists($attribute, 'get_visible') || !$attribute->get_visible()) {
                        continue;
                    }

                    $name  = wc_attribute_label($attribute->get_name());
                    $value = '';

                    if ($attribute->is_taxonomy()) {
                        $terms = wc_get_product_terms($product->get_id(), $attribute->get_name(), ['fields' => 'names']);
                        if (!is_wp_error($terms) && !empty($terms)) {
                            $value = implode(', ', $terms);
                        }
                    } else {
                        // atrybuty niestandardowe (tekstowe)
                        $options = $attribute->get_options();
                        if (!empty($options)) {
                            $value = implode(', ', $options);
                        }
                    }

                    // Pomijamy, jeśli to „Marka” aby nie dublować
                    $lower = mb_strtolower($name);
                    if (in_array($lower, ['marka', 'brand'])) {
                        continue;
                    }

                    if ($value) {
                        $rows[] = [
                            'label' => $name,
                            'value' => $value,
                        ];
                    }
                }
            }

            // Nic do pokazania
            if (empty($rows)) {
                return;
            }

            // RENDER
    ?>
            <section class="aw-tech" id="dane-techniczne">
                <h2 class="aw-tech__title"><?php echo esc_html__('Dane techniczne', 'aw'); ?></h2>
                <ul class="aw-tech__list" aria-label="<?php echo esc_attr__('Dane techniczne produktu', 'aw'); ?>">
                    <?php foreach ($rows as $row): ?>
                        <li class="aw-tech__row">
                            <span><?php echo esc_html($row['label']); ?></span>
                            <span><?php echo wp_kses_post($row['value']); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </section>
            <style>
                .aw-tech {
                    margin-bottom: 2.5rem;
                }

                .aw-tech__title {
                    text-align: center;
                    font-size: 1.75rem;
                    font-weight: 700;
                    margin-bottom: 2rem;
                }

                .aw-tech__list {
                    /* podgląd */
                    max-width: 50rem;
                    padding: 1.5rem;
                    list-style: none;
                    margin: 0 auto;
                }

                .aw-tech__row {
                    display: flex;
                    justify-content: space-between;
                    padding: 0.75rem 0;
                    font-size: 1rem;
                    border-bottom: 1px solid #e8e8e8;
                }

                .aw-tech__row:last-child {
                    border-bottom: none;
                }

                @media (max-width: 600px) {
                    .aw-tech__row {
                        flex-direction: column;
                        gap: 0.25rem;
                    }

                    .aw-tech__row span:last-child {
                        font-weight: 600;
                    }
                }
            </style>
    <?php
        }
    }

    /** Shortcode: [aw_tech_specs] */
    add_shortcode('aw_tech_specs', function () {
        ob_start();
        aw_render_tech_specs();
        return ob_get_clean();
    });
    aw_render_tech_specs(wc_get_product());
    /** Hook: pokaż po podsumowaniu produktu */
    add_action('woocommerce_after_single_product_summary', function () {
        // Możesz zmienić priorytet lub warunek (np. tylko dla określonych kategorii)
        aw_render_tech_specs();
    }, 15);

    ?>


</div>

<?php do_action('woocommerce_after_single_product'); ?>