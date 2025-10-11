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
<section class="aw-statystyki" <?php echo $anchor; ?> style="padding:8px 0 24px">
  <div class="container">
    <h2>Dlaczego my?</h2>
    <div class="grid4">
      <article class="surface usp" style="text-align:center">
        <img class="img" src="https://tse2.mm.bing.net/th/id/OIP.gf4q2b8y6t3aQZzbV9u5SgHaE8?pid=Api"
          alt="Ikona świeżości"
          style="width:72px;margin:0 auto 8px;aspect-ratio:1/1;object-fit:cover;border-radius:12px" />
        <h3><span class="counter" data-target="1200">0</span>+</h3>
        <p class="muted">Produktów w ofercie</p>
      </article>
      <article class="surface usp" style="text-align:center">
        <img class="img" src="https://tse1.mm.bing.net/th/id/OIP.n0cZ-8XrO9m2aV8r1mZ5PwHaE7?pid=Api"
          alt="Ikona dostawy"
          style="width:72px;margin:0 auto 8px;aspect-ratio:1/1;object-fit:cover;border-radius:12px" />
        <h3><span class="counter" data-target="24">0</span>h</h3>
        <p class="muted">Średni czas dostawy</p>
      </article>
      <article class="surface usp" style="text-align:center">
        <img class="img" src="https://tse4.mm.bing.net/th/id/OIP._I3B3oHqfE7G8a7Yb7aZpgHaE8?pid=Api"
          alt="Ikona opinii"
          style="width:72px;margin:0 auto 8px;aspect-ratio:1/1;object-fit:cover;border-radius:12px" />
        <h3><span class="counter" data-target="4.8">0</span>/5</h3>
        <p class="muted">Średnia ocena klientów</p>
      </article>
      <article class="surface usp" style="text-align:center">
        <img class="img" src="https://tse2.mm.bing.net/th/id/OIP.Qb1Wwqv6m2b5v7R8g3k4lAHaE8?pid=Api"
          alt="Ikona lokalizacji"
          style="width:72px;margin:0 auto 8px;aspect-ratio:1/1;object-fit:cover;border-radius:12px" />
        <h3><span class="counter" data-target="1">0</span></h3>
        <p class="muted">Sklep w Gdańsku</p>
      </article>
    </div>
  </div>
</section>