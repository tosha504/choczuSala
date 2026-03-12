<?php

/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package start
 */

get_header();
?>

<main id="primary" class="site-main">
	<?php
	$shop_url = function_exists('wc_get_page_permalink')
		? wc_get_page_permalink('shop')
		: home_url('/sklep/');

	$home_url = home_url('/');
	$contact_url = home_url('/kontakt/');
	?>

	<section class="error-404-hero" aria-labelledby="error-404-title">
		<div class="error-404-hero__media" aria-hidden="true">
			<div class="error-404-hero__overlay"></div>
		</div>

		<div class="container">
			<div class="error-404-hero__inner">
				<div class="error-404-hero__content">
					<span class="error-404-hero__eyebrow">
						<?= __('Sklep z produktami ze wschodu', 'start'); ?>
					</span>

					<h1 id="error-404-title" class="error-404-hero__title">
						<?= __('Tutaj nic nie znaleźliśmy', 'start'); ?>
					</h1>

					<p class="error-404-hero__text">
						<?= __('Strona, której szukasz, mogła zostać przeniesiona, usunięta albo adres został wpisany nieprawidłowo.
						Ale spokojnie — możesz wrócić do sklepu i znaleźć swoje ulubione ukraińskie produkty.', 'start'); ?>
					</p>

					<div class="error-404-hero__actions">
						<a class="error-404-hero__button error-404-hero__button--primary" href="<?php echo esc_url($shop_url); ?>">
							<?= __('Przejdź do sklepu', 'start'); ?>
						</a>

						<a class="error-404-hero__button error-404-hero__button--secondary" href="<?php echo esc_url($home_url); ?>">
							<?= __('Wróć na stronę główną', 'start'); ?>
						</a>
					</div>

					<div class="error-404-hero__search">
						<form role="search" method="get" class="error-404-search-form" action="<?php echo esc_url(home_url('/')); ?>">
							<label class="screen-reader-text" for="error-404-search">
								<?= __('Szukaj produktów', 'start'); ?>
							</label>

							<input
								id="error-404-search"
								class="error-404-search-form__input"
								type="search"
								name="s"
								placeholder="Wpisz nazwę produktu, np. pielmieni, kwas chlebowy..."
								value="<?php echo esc_attr(get_search_query()); ?>" />

							<input type="hidden" name="post_type" value="product" />

							<button class="error-404-search-form__button" type="submit">
								<?= __('Szukaj', 'start'); ?>
							</button>
						</form>
					</div>

					<ul class="error-404-hero__quick-links" aria-label="Szybkie przejścia">
						<li><a href="<?php echo esc_url($shop_url); ?>"><?= __('Wszystkie produkty', 'start'); ?></a></li>
						<!-- <li><a href="<?php echo esc_url(home_url('/promocje/')); ?>">Promocje</a></li>
						<li><a href="<?php echo esc_url(home_url('/nowosci/')); ?>">Nowości</a></li>
						<li><a href="<?php echo esc_url($contact_url); ?>">Kontakt</a></li> -->
					</ul>
				</div>
			</div>
		</div>
	</section>
</main><!-- #main -->

<?php
get_footer();
