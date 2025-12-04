<?php

/**
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during backend preview render.
 * @param   int $post_id The post ID the block is rendering content against.
 *          This is either the post ID currently being displayed inside a query loop,
 *          or the post ID of the post hosting this block.
 */

/**
 * FB Videos Grid Block template.
 */

if (!defined('ABSPATH')) exit;

$anchor = '';
if (!empty($block['anchor'])) {
  $anchor = 'id="' . esc_attr($block['anchor']) . '" ';
}
$tag = get_field('tag');
$text_title = get_field('text_title');
$color_text = get_field('color_text');
$class = get_field('class');
$description = !empty(get_field('description')) ? "<div class='description'>" . get_field('description') . "</div>" : "";
$className = !empty($block['className']) ? $block['className'] : "";

$className = 'aw-fb-videos';
if (!empty($block['className'])) {
  $className .= ' ' . $block['className'];
}

$videos = get_field('face_aw');
$link = get_field('link_face');


if (empty($videos)) {
  if (!empty($is_preview)) {
    echo '<div style="padding:16px;border:1px dashed #ddd;background:#fafafa;">';
    echo '⚠️ Dodaj przynajmniej jedno wideo Facebook w repeaterze <strong>videos</strong>.';
    echo '</div>';
  }
  return;
}
?>

<section class="<?php echo esc_attr($className); ?>" <?php echo $anchor; ?>>
  <div class="container">
    <div class="aw-fb-videos__top">
      <?php echo show_title($tag, $text_title, $color_text);
      if ($link) :
        $link_url = $link['url'];
        $link_title = $link['title'];
        $link_target = $link['target'] ? $link['target'] : '_self';
      ?>
        <a class="link-arrow" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>"><?php echo esc_html($link_title) . aw_svg('/arrow');; ?></a>
      <?php endif; ?>
    </div>
    <div class="aw-fb-videos__grid">
      <?php foreach ($videos as $row): ?>
        <?php
        $embed = $row['face_aw_item'] ?? '';

        if (empty(trim($embed))) {
          continue;
        }
        ?>
        <div class="aw-fb-videos__item">
          <div class="aw-fb-videos__embed">
            <?php
            // Pozwalamy na iframe / fb-video, ale czyścimy niebezpieczne rzeczy.
            echo wp_kses(
              $embed,
              [
                'iframe' => [
                  'src'             => true,
                  'width'           => true,
                  'height'          => true,
                  'style'           => true,
                  'frameborder'     => true,
                  'allowfullscreen' => true,
                  'allow'           => true,
                  'scrolling'       => true,
                  'loading'         => 'lazy',
                ],
                'div' => [
                  'id'           => true,
                  'class'        => true,
                  'data-href'    => true,
                  'data-width'   => true,
                  'data-show-text' => true,
                  'data-allowfullscreen' => true,
                ],
                'blockquote' => [
                  'cite'  => true,
                  'class' => true,
                ],
                'a' => [
                  'href'  => true,
                  'target' => true,
                  'rel'   => true,
                ],
                'p' => [],
                'span' => ['class' => true],
              ]
            );
            ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>