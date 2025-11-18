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
?>
<section class="aw-statystyki" <?php echo $anchor; ?>>
  <div class="container">
    <h2>Dlaczego nas wybieraja nasi klienci</h2>
    <div class="grid4">
      <article class="surface usp" style="text-align:center">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" aria-labelledby="i-products" role="img">
          <title id="i-products">Produkty w ofercie</title>
          <g stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="3" width="8" height="8" rx="1.5" />
            <rect x="13" y="3" width="8" height="8" rx="1.5" />
            <rect x="3" y="13" width="8" height="8" rx="1.5" />
            <path d="M13 13h8v8h-8z" />
          </g>
        </svg>
        <p role="heading"><span class="counter" data-target="500">0</span>+</p role="heading">
        <p class="muted">Produktów w ofercie</p>
      </article>
      <article class="surface usp" style="text-align:center">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" aria-labelledby="i-24h" role="img">
          <title id="i-24h">Dostawa 24h</title>
          <g stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="7.5" />
            <path d="M12 7.5v4.5l3 1.5" />
            <path d="M18.5 5.5l1.8-1.8M20.3 3.7v3.6h-3.6" />
          </g>
        </svg>
        <p role="heading"><span class="counter" data-target="24">0</span>h</p role="heading">
        <p class="muted">Średni czas dostawy</p>
      </article>
      <article class="surface usp" style="text-align:center">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" aria-labelledby="i-rating" role="img">
          <title id="i-rating">Średnia ocena klientów</title>
          <g stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 3.5l2.3 4.68 5.17.75-3.74 3.64.88 5.13L12 15.9 7.39 17.7l.88-5.13L4.5 8.93l5.17-.75L12 3.5z" />
          </g>
        </svg>
        <p role="heading"><span class="counter" data-target="4.7">0</span>/5</p role="heading">
        <p class="muted">Średnia ocena klientów</p>
      </article>
      <article class="surface usp" style="text-align:center">

        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" aria-labelledby="i-store" role="img">
          <title id="i-store">Sklep stacjonarny</title>
          <g stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 10h16l-1-5H5l-1 5z" />
            <path d="M6 10v9h12v-9" />
            <path d="M10 19v-4h4v4" />
          </g>
        </svg>
        <p role="heading"><span class="counter" data-target="1">0</span></p role="heading">
        <p class="muted">Taki sklep w Gdańsku</p>
      </article>
    </div>
  </div>
</section>