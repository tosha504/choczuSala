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
$items = get_field('items'); ?>
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
          $icon_svg = isset($item['logo']) && !empty($item['logo']) ? $item['logo'] : '';
          $suffix   = isset($item['add_item']) ? $item['add_item'] : '';
        ?>
          <article class="surface usp" style="text-align:center">
            <div class="surface__svg"><?php echo aw_svg($icon_svg); ?></div>

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