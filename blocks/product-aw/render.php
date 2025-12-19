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
$className = !empty($block['className']) ? $block['className'] : "";
$link = get_field('link_face');
$products = get_field('product_aw');
?>
<section class="aw-products <?php echo esc_attr($className); ?>" <?php echo $anchor; ?>>
  <div class="container">
    <div class="aw-products__top">
      <?php echo show_title($tag, $text_title, $color_text);
      if ($link) :
        $link_url = $link['url'];
        $link_title = $link['title'];
        $link_target = $link['target'] ? $link['target'] : '_self';
      ?>
        <a class="link-arrow" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>"><?php echo esc_html($link_title) . aw_svg('/arrow');; ?></a>
      <?php endif; ?>
    </div>


    <?php if (!empty($products) && count($products) > 0) : ?>
      <div class="aw-products-block" data-aw-products data-aw-threshold="3">

        <div class="aw-products-block__nav" aria-hidden="true">
          <button class="aw-products-block__btn aw-products-block__btn--prev" type="button" aria-label="Poprzednie produkty">‹</button>
          <button class="aw-products-block__btn aw-products-block__btn--next" type="button" aria-label="Następne produkty">›</button>
        </div>

        <ul class="aw-product-aw__products products">
          <?php
          foreach ($products as $item) {
            $product_id = is_object($item) ? (int) $item->ID : (int) $item;
            if (!$product_id) continue;

            $post_object = get_post($product_id);
            if (!$post_object) continue;

            global $post, $product;
            $post    = $post_object;
            $product = wc_get_product($product_id);
            if (!$product) continue;

            setup_postdata($post);

            // NIE dodajemy swiper-slide w PHP
            wc_get_template_part('content', 'product');
          }
          wp_reset_postdata();
          ?>
        </ul>

        <div class="swiper-pagination"></div>

      </div>
    <?php endif; ?>

  </div>
</section>