<?php

/**
 * AW – unified product badges (SALE + Nowość + Na wagę)
 * arturiko-web.eu
 */

defined('ABSPATH') || exit;

/**
 * Render badge overlay dla konkretnego produktu
 */
function aw_wc_render_custom_badges_overlay($product): void
{
    if (is_numeric($product)) {
        $product = wc_get_product((int) $product);
    }

    if (!$product instanceof WC_Product) return;

    $badges = aw_wc_get_badges_for_product($product);
    if (empty($badges)) return;

    echo '<div class="aw-badges" aria-label="Product badges">';

    foreach ($badges as $badge) {
        printf(
            '<span class="aw-badge aw-badge--%s">%s</span>',
            esc_attr($badge['key']),
            esc_html($badge['label'])
        );
    }

    echo '</div>';
}

/**
 * Logika badge (ZERO globali)
 */
function aw_wc_get_badges_for_product(WC_Product $product): array
{
    $badges = [];
    $id = $product->get_id();

    /* -------------------------
     * SALE (-XX%)
     * ------------------------- */
    if ($product->is_on_sale()) {
        $percent = aw_wc_get_discount_percent($product);

        if ($percent) {
            $badges[] = [
                'key'   => 'sale',
                'label' => '-' . (int) $percent . '%',
            ];
        }
    }

    /* -------------------------
     * Na wagę (meta z metaboxa)
     * ------------------------- */
    if ($product->get_meta('_aw_weight_enabled', true) === '1') {
        $badges[] = [
            'key'   => 'na-wage',
            'label' => __('Na wagę', 'start'),
        ];
    }

    /* -------------------------
     * Nowość (ACF true/false)
     * ------------------------- */
    if (function_exists('get_field') && get_field('aw_is_new', $id)) {
        $badges[] = [
            'key'   => 'nowosc',
            'label' => __('Nowość', 'start'),
        ];
    }
    /* -------------------------
     * Bestseller (AUTO)
     * ------------------------- */
    if (aw_wc_is_bestseller_product($product)) {
        $badges[] = [
            'key'   => 'bestseller',
            'label' => __('Bestseller', 'start'),
        ];
    }


    return $badges;
}
function aw_wc_get_discount_percent(WC_Product $product): ?int
{
    // Variable product → bierzemy MAX %
    if ($product->is_type('variable')) {
        $max = 0;

        foreach ($product->get_children() as $child_id) {
            $variation = wc_get_product($child_id);
            if (!$variation || !$variation->is_on_sale()) continue;

            $regular = (float) $variation->get_regular_price();
            $sale    = (float) $variation->get_sale_price();

            if ($regular > 0 && $sale > 0 && $sale < $regular) {
                $pct = round((($regular - $sale) / $regular) * 100);
                $max = max($max, $pct);
            }
        }

        return $max ?: null;
    }

    // Simple product
    $regular = (float) $product->get_regular_price();
    $sale    = (float) $product->get_sale_price();

    if ($regular > 0 && $sale > 0 && $sale < $regular) {
        return (int) round((($regular - $sale) / $regular) * 100);
    }

    return null;
}
/**
 * Czy produkt jest bestsellerem?
 * Production-ready: oparte o total_sales, z cache dla wydajności.
 *
 * Logika:
 * - dla variable sumuje total_sales wariantów
 * - porównuje do progu (domyślnie 50) — możesz zmienić filtrem
 * - cache na 12h (transient)
 */
function aw_wc_is_bestseller_product(WC_Product $product): bool
{
    $threshold = (int) apply_filters('aw_wc_bestseller_threshold', 50, $product);
    if ($threshold <= 0) return false;

    $sales = aw_wc_get_product_total_sales_cached($product);

    return $sales >= $threshold;
}

/**
 * Zwraca total sprzedaży produktu (simple) lub sumę sprzedaży wariantów (variable),
 * z cache w transientach (12h).
 */
function aw_wc_get_product_total_sales_cached(WC_Product $product): int
{
    $id = $product->get_id();
    $key = 'aw_wc_sales_' . $id;

    $cached = get_transient($key);

    if ($cached !== false) {
        return (int) $cached;
    }

    $sales = 0;

    if ($product->is_type('variable')) {
        foreach ($product->get_children() as $child_id) {
            $variation = wc_get_product($child_id);
            if (!$variation) continue;

            $sales += (int) $variation->get_total_sales();
        }
    } else {
        $sales = (int) $product->get_total_sales();
    }

    // Cache 12h — wystarczy do badge’a (i chroni przed kosztami na loopie)
    set_transient($key, $sales, 12 * HOUR_IN_SECONDS);

    return $sales;
}
add_action('woocommerce_checkout_order_processed', function ($order_id) {
    $order = wc_get_order($order_id);
    if (!$order) return;

    foreach ($order->get_items('line_item') as $item) {
        /** @var WC_Order_Item_Product $item */
        $pid = (int) $item->get_id();
        $vid = (int) $item->get_variation_id();
        if ($pid) delete_transient('aw_wc_sales_' . $pid);
        if ($vid) delete_transient('aw_wc_sales_' . $vid);
    }
}, 10);

add_action('save_post_product', function ($post_id) {
    delete_transient('aw_wc_sales_' . (int) $post_id);
}, 10);

add_action('save_post_product_variation', function ($post_id) {
    delete_transient('aw_wc_sales_' . (int) $post_id);
}, 10);
add_filter('aw_wc_bestseller_threshold', function ($threshold, $product) {
    return 1; // np. od 30 szt. = Bestseller
}, 10, 2);
