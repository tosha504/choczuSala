<?php

/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package start
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-content">
		<?php the_content(); ?>
		<!-- Statystyki / Licznik -->

		<section id="opinie" class="section reviews">
			<div class="container">
				<h2>Opinie klientów</h2>
				<div class="reviews__summary">
					<div class="stars" aria-label="Ocena 4.9 na 5">
						★★★★★
					</div>
					<p><strong>4.9/5</strong> na podstawie <span id="reviews-count">128</span> opinii Google</p>
					<a class="btn btn--sm" href="https://maps.google.com/?q=Хочу+Сала+Gdańsk" target="_blank" rel="noopener">
						Zobacz wszystkie
					</a>
				</div>

				<div class="reviews__list" id="reviews-list">
					<!-- Tu wstrzykniemy 6 najnowszych -->
				</div>
			</div>
		</section>
		<!-- Elfsight Google Reviews | Untitled Google Reviews -->
		<script src="https://elfsightcdn.com/platform.js" async></script>
		<div class="elfsight-app-ab667762-539e-4d5b-b95f-d3c7e96dcd23" data-elfsight-app-lazy></div>

		<?php $rating = (float) get_field('aw_reviews_rating', 'option');
		$count  = (int)   get_field('aw_reviews_count',  'option');
		$url    = (string)get_field('aw_reviews_url',    'option');

		$items  = get_field('aw_reviews_items', 'option') ?: []; // repeater: name,text

		echo '<section class="aw-reviews section"><div class="container">';
		echo '<h2>Opinie klientów</h2>';
		echo '<div class="aw-reviews__summary">';
		echo '  <span class="aw-reviews__stars">★★★★★</span> ';
		echo '  <strong>' . esc_html(number_format_i18n($rating, 1)) . '/5</strong>';
		echo '  <span>na podstawie</span> <strong>' . esc_html($count) . '</strong> <span>opinii</span>';
		if ($url) {
			echo '  <a class="btn btn--sm" href="' . esc_url($url) . '" target="_blank" rel="noopener">Zobacz w Google</a>';
		}
		echo '</div>';

		if (!empty($items)) {
			echo '<div class="aw-reviews__list" role="list">';
			foreach ($items as $it) {
				$name = $it['name'] ?? 'Klient';
				$text = $it['text'] ?? '';
				echo '<article class="aw-reviews__card" role="listitem">';
				echo '  <div class="aw-reviews__name">' . esc_html($name) . '</div>';
				echo '  <p>' . esc_html($text) . '</p>';
				echo '</article>';
			}
			echo '</div>';
		}

		// Uwaga: NIE dodajemy tutaj JSON-LD z agregatem, bo wartości pochodzą z zewnętrznego źródła.
		echo '</div></section>';
		?>
	</div><!-- .entry-content -->
</article><!-- #post-<?php the_ID(); ?> -->