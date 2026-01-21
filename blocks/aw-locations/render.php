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
$className = !empty($block['className']) ? $block['className'] : "";
$link = get_field('link_face');
$links =  get_field('links');
$iframes =  get_field('iframes'); ?>
<section class="aw-locations <?php echo esc_attr($className); ?>" <?php echo $anchor; ?>>
  <div class="container">
    <?php echo show_title($tag, $text_title, $color_text);
    ?>
    <div class="aw-locations__wrap">
      <div class="aw-locations__wrap--left">
        <?php if (!empty($links) && count($links) > 0) { ?>
          <?php foreach ($links as $key => $item) { ?>
            <button class="button is-active" data-target="<?php echo $item['id']; ?>"><?= __('Lokalizacja ', 'start') . $key + 1; ?></button>
          <?php } ?>
        <?php } ?>
      </div>
      <div class="aw-locations__wrap--right">
        <?php if (!empty($iframes) && count($iframes) > 0) { ?>
          <?php foreach ($iframes as $key => $item) { ?>
            <div class="location-map <?php echo $key === 0 ? 'is-active' : ''; ?>" id="<?php echo $links[$key]['id']; ?>"><?php echo $item['iframe']; ?></div>
          <?php } ?>
      </div>
    <?php } ?>
    </div>

  </div>
</section>
<style>
  .location-map {
    display: none;
  }

  .location-map.is-active {
    display: block;
  }

  .locations__wrap--left button.is-active {
    font-weight: 600;
  }
</style>
<script>
  (() => {
    const wrapper = document.querySelector('.aw-locations__wrap');
    if (!wrapper) return;

    const buttons = wrapper.querySelectorAll('[data-target]');
    const maps = wrapper.querySelectorAll('.location-map');

    const activateLocation = (targetId) => {
      // maps
      maps.forEach(map => {
        map.classList.toggle('is-active', map.id === targetId);
      });

      // buttons
      buttons.forEach(btn => {
        btn.classList.toggle(
          'is-active',
          btn.dataset.target === targetId
        );
      });
    };

    wrapper.addEventListener('click', (e) => {
      const button = e.target.closest('[data-target]');
      if (!button) return;

      activateLocation(button.dataset.target);
    });
  })();
</script>