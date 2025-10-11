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
$image = get_field('image');

$description = !empty(get_field('description')) ? "<div class='description'>" . get_field('description') . "</div>" : "";
?>
<!-- aw-hero -->
<section class="aw-hero" <?php echo $anchor; ?>>
  <div class="container">
    <div class="aw-hero__left">
      <?php echo show_title($tag, $text_title, $color_text) . $description; ?>

      <div style="display:flex;gap:10px;margin-top:16px">
        <a class="btn" href="#kategorie">Przeglądaj kategorie</a>
        <a class="btn secondary" href="#promocje">Zobacz promocje</a>
      </div>
    </div>
    <div class="media" aria-hidden="true">
      <?php if (!empty($image)) {
        echo my_custom_attachment_image($image, [
          'priority' => true,
          'preload'  => true,
          'sizes'    => '(max-width:768px) 90vw, 800px',
        ]);
      } ?>
    </div>
  </div>

</section>