<?php

/**
 * Search results template
 *
 * @package start
 */

get_header();
$current_lang = function_exists('pll_current_language')
	? pll_current_language('slug')
	: '';

$search_action_url = home_url('/');

if (function_exists('pll_home_url') && !empty($current_lang)) {
	$search_action_url = pll_home_url($current_lang);
}
$search_query = get_search_query();
$current_lang = function_exists('pll_current_language') ? pll_current_language('slug') : '';
?>

<main id="main" class="site-main search-page">
	<div class="container">
		<header class="search-page__header">
			<h1 class="search-page__title">
				<?php
				printf(
					esc_html__('Wyniki wyszukiwania dla: %s', 'start'),
					'<span>' . esc_html($search_query) . '</span>'
				);
				?>
			</h1>

			<form
				role="search"
				method="get"
				class="search-page__form"
				action="<?php echo esc_url($search_action_url); ?>">

				<label class="screen-reader-text" for="search-page-input">
					<?php esc_html_e('Szukaj produktów', 'start'); ?>
				</label>

				<div class="search-page__field-wrap">
					<input
						id="search-page-input"
						type="search"
						name="s"
						class="search-page__input"
						value="<?php echo esc_attr($search_query); ?>"
						placeholder="<?php esc_attr_e('Wpisz nazwę produktu...', 'start'); ?>" />

					<input type="hidden" name="post_type" value="product">

					<?php if (!empty($current_lang)) : ?>
						<input type="hidden" name="lang" value="<?php echo esc_attr($current_lang); ?>">
					<?php endif; ?>

					<button type="submit" class="search-page__submit">
						<?php esc_html_e('Szukaj', 'start'); ?>
					</button>
				</div>
			</form>
		</header>

		<?php if (have_posts()) : ?>
			<div class="search-page__meta">
				<?php
				global $wp_query;
				printf(
					esc_html(_n('Znaleziono %d produkt', 'Znaleziono %d produktów', (int) $wp_query->found_posts, 'start')),
					(int) $wp_query->found_posts
				);
				?>
			</div>

			<div class="search-page__grid products columns-4">
				<?php
				while (have_posts()) :
					the_post();

					global $product;

					if (!$product instanceof WC_Product) {
						$product = wc_get_product(get_the_ID());
					}

					wc_get_template_part('content', 'product');
				endwhile;
				?>
			</div>

			<?php the_posts_pagination(); ?>

		<?php else : ?>
			<div class="search-page__empty">
				<h2><?php esc_html_e('Nie znaleziono produktów.', 'start'); ?></h2>
				<p><?php esc_html_e('Spróbuj użyć innej nazwy, krótszego hasła albo wyszukaj po kategorii.', 'start'); ?></p>
			</div>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
