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
$buttons = get_field('buttons');
$description = !empty(get_field('description')) ? "<div class='description'>" . get_field('description') . "</div>" : "";
?>
<!-- aw-hero -->
<section class="aw-hero" <?php echo $anchor; ?>>
  <div class="container">
    <div class="aw-hero__left">
      <?php echo show_title($tag, $text_title, $color_text) . $description; ?>
      <div class="aw-badges">
        <span class="aw-badge aw-badge--fresh"> Świeżo krojone</span>
        <span class="aw-badge aw-badge--weight"> Na wagę</span>
        <span class="aw-badge aw-badge--local"> Lokalny sklep</span>
        <span class="aw-badge aw-badge--fast"> Dostawa nawet w 14h</span>
      </div>
      <style>
        .aw-badges {
          display: flex;
          flex-wrap: wrap;
          gap: 10px;
        }

        /* baza */
        .aw-badge {
          display: inline-flex;
          align-items: center;
          gap: 6px;
          padding: 8px 12px;
          border-radius: 999px;
          font-family: var(--inter);
          font-size: 13px;
          font-weight: 600;
          line-height: 1;
          white-space: nowrap;
          border: 1px solid transparent;
        }

        /* 🥩 Świeżo krojone – quality / premium */
        .aw-badge--fresh {
          background: rgba(165, 185, 62, .14);
          /* secondary */
          color: var(--color-dark);
          border-color: rgba(165, 185, 62, .35);
        }

        /* ⚖️ Na wagę – neutral / UX info */
        .aw-badge--weight {
          background: var(--color-surface);
          color: var(--color-text-muted);
          border-color: var(--color-border);
        }

        /* 📍 Lokalny sklep – zaufanie / brand */
        .aw-badge--local {
          background: rgba(185, 152, 78, .14);
          /* accent gold */
          color: var(--color-dark);
          border-color: rgba(185, 152, 78, .35);
        }

        /* 🚚 Dostawa 14h – urgency / CTA-support */
        .aw-badge--fast {
          background: rgba(122, 15, 21, .12);
          /* primary */
          color: var(--color-primary);
          border-color: rgba(122, 15, 21, .35);
        }
      </style>
      <?php if (!empty($buttons) && count($buttons) > 0) { ?>
        <div class="buttons">
          <?php foreach ($buttons as $key => $button)  create_button_block($button['button'], $button['class_btn']); ?>
        </div>
      <?php } ?>

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