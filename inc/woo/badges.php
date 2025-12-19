
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
