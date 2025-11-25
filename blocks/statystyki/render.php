<?php

/**
 * Column JW Block template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during backend preview render.
 * @param   int $post_id The post ID the block is rendering content against.
 *          This is either the post ID currently being displayed inside a query loop,
 *          or the post ID of the post hosting this block.
 */

// Load values and assign defaults.

$anchor = '';
if (!empty($block['anchor'])) {
  $anchor = 'id="' . esc_attr($block['anchor']) . '" ';
}
$tag = get_field('tag');
$text_title = get_field('text_title');
$color_text = get_field('color_text');
$class = get_field('class');
$items = get_field('items');
$icons = [
  [
    'value' => '500+',
    'label' => 'Товарів у асортименті',
    'icon'  => <<<SVG
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" aria-labelledby="i-products" role="img">
              <title id="i-products">Produkty w ofercie</title>
              <g stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="8" height="8" rx="1.5" />
                <rect x="13" y="3" width="8" height="8" rx="1.5" />
                <rect x="3" y="13" width="8" height="8" rx="1.5" />
                <path d="M13 13h8v8h-8z" />
              </g>
            </svg>
        SVG
  ],
  [
    'value' => '24h',
    'label' => 'Середній час доставки',
    'icon'  => <<<SVG
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" aria-labelledby="i-24h" role="img">
              <title id="i-24h">Dostawa 24h</title>
              <g stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="7.5" />
                <path d="M12 7.5v4.5l3 1.5" />
                <path d="M18.5 5.5l1.8-1.8M20.3 3.7v3.6h-3.6" />
              </g>
            </svg>
        SVG
  ],
  [
    'value' => '4.7/5',
    'label' => 'Середня оцінка клієнтів',
    'icon'  => <<<SVG
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" aria-labelledby="i-rating" role="img">
              <title id="i-rating">Średnia ocena klientów</title>
              <g stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 3.5l2.3 4.68 5.17.75-3.74 3.64.88 5.13L12 15.9 7.39 17.7l.88-5.13L4.5 8.93l5.17-.75L12 3.5z" />
              </g>
            </svg>
        SVG
  ],
  [
    'value' => '1',
    'label' => 'Такий магазин у Гданську',
    'icon'  => <<<SVG
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" aria-labelledby="i-store" role="img">
              <title id="i-store">Sklep stacjonarny</title>
              <g stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 10h16l-1-5H5l-1 5z" />
                <path d="M6 10v9h12v-9" />
                <path d="M10 19v-4h4v4" />
              </g>
            </svg>
        SVG
  ],
];

?>
<section class="aw-statystyki" <?php echo $anchor; ?>>
  <div class="container">
    <?php echo show_title($tag, $text_title, $color_text);
    if (!empty($items) && is_array($items)) : ?>
      <div class="grid4">
        <?php foreach ($items as $key => $item) :

          // walidacja danych z ACF
          if (empty($item['text_item']) || empty($item['number_item'])) {
            continue;
          }

          $icon_svg = isset($icons[$key]['icon']) ? $icons[$key]['icon'] : '';
          $suffix   = isset($item['add_item']) ? $item['add_item'] : '';
        ?>
          <article class="surface usp" style="text-align:center">
            <?php if ($icon_svg) : ?>
              <?php echo $icon_svg; ?>
            <?php endif; ?>

            <p role="heading">
              <span class="counter" data-target="<?php echo esc_attr($item['number_item']); ?>">
                0
              </span><?php echo esc_html($suffix); ?>
            </p>
            <p class="muted">
              <?php echo esc_html($item['text_item']); ?>
            </p>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>