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
$description = !empty(get_field('description')) ? "<div class='description'>" . get_field('description') . "</div>" : "";
// Pobranie wybranych kategorii z pola ACF (taxonomy field)
$terms_field = get_field('aw-categories'); // nazwa pola ACF
if (empty($terms_field)) {
  // Fallback w edytorze – info dla klienta
  if ($is_preview) {
    echo '<div style="padding: 16px; border: 1px dashed #ddd; background:#fafafa;">';
    echo '⚠️ Wybierz kategorie produktów w ustawieniach bloku (pole: <strong>product_categories</strong>).';
    echo '</div>';
  }
  return;
}

// Normalizacja – ACF może zwrócić ID lub obiekty terminów
$term_ids = [];

foreach ((array) $terms_field as $term) {
  if (is_object($term) && isset($term->term_id)) {
    $term_ids[] = (int) $term->term_id;
  } else {
    $term_ids[] = (int) $term;
  }
}

$term_ids = array_filter(array_unique($term_ids));

if (empty($term_ids)) {
  return;
}

// Pobierz TYLKO te kategorie, w tej samej kolejności co w ACF
$terms = get_terms([
  'taxonomy'   => 'product_cat',
  'include'    => $term_ids,
  'hide_empty' => false, // ustaw wg potrzeb
  'orderby'    => 'include', // zachowaj kolejność z $term_ids
]);

if (is_wp_error($terms) || empty($terms)) {
  return;
}
$className = !empty($block['className']) ? $block['className'] : "";
?>
<section class="aw-cat-product <?php echo esc_attr($className); ?>" <?php echo $anchor; ?>>
  <div class="container">
    <?php echo show_title($tag, $text_title, $color_text) . $description; ?>

    <div class="aw-cat-product__list">
      <?php if (!empty($terms) && count($terms) > 0) {
        foreach ($terms as $term): ?>
          <?php
          $thumb_id  = get_term_meta($term->term_id, 'thumbnail_id', true);
          $thumb_url = $thumb_id ? wp_get_attachment_image_url($thumb_id, 'full') : wc_placeholder_img_src('thumbnail');
          ?>
          <a class="aw-cat-product__item" href="<?php echo esc_url(get_term_link($term)); ?>">
            <span class="aw-cat-product__img">
              <img
                src="<?php echo esc_url($thumb_url); ?>"
                alt="<?php echo esc_attr($term->name); ?>">

              <span class="aw-cat-product__label-text">
                <?php echo esc_html($term->name) . aw_svg('/arrow'); ?>
              </span>
          </a>
        <?php endforeach; ?>
      <?php } ?>
    </div>
  </div>
</section>