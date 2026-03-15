<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package start
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<div id="page" class="wrapper">
		<a class="skip" href="#main"
			style="position:absolute;left:-9999px;top:auto;width:1px;height:1px;overflow:hidden">Pomiń do treści</a>

		<!-- Top bar -->
		<div class="topbar">
			<div class="container">
				<?php $pre_header_text = aw_get_option('pre_header_text',);
				$work_time = aw_get_option('work_time'); ?>
				<div><?php if (! empty($pre_header_text)) echo $pre_header_text; ?></div>
				<div><?php if (! empty($work_time)) echo $work_time; ?></div>
			</div>
		</div>
		<header id="masthead" class="header">
			<div class="container">
				<?php
				$logo = get_field('logo', 'options');
				if ($logo) { ?>
					<div class="header__logo">
						<a href="<?php echo esc_url(home_url('/')) ?>" title="Go to homepage"
							rel="noopener noreferrer"
							target="_self">
							<?php
							echo wp_get_attachment_image($logo, 'full');
							?>
						</a>
					</div> <!-- header-logo -->
				<?php } ?>
				<nav id="site-navigation" class="main-navigation">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'menu-header',
							'container' => false,
							'menu_id' => 'primary-menu1',
							'menu_class' => 'header__nav',
						),
					);


					aw_language_switcher();
					?>
				</nav><!-- #site-navigation -->
				<div class="actions">
					<div class="actions__woo">
						<button
							type="button"
							class="search-toggle js-search-open"
							aria-label="<?php esc_attr_e('Otwórz wyszukiwarkę produktów', 'start'); ?>"
							aria-controls="site-search-modal"
							aria-expanded="false">
							<?php echo aw_svg('search'); ?>
						</button>
						<a
							href="<?php echo wc_get_account_endpoint_url('dashboard'); ?>"
							title="<?php esc_attr_e('Moje konto', 'start'); ?>"
							rel="noopener noreferrer"
							target="_self">
							<?php echo aw_svg('account'); ?>
						</a>
						<a
							href="<?php echo esc_url(wc_get_cart_url()); ?>" class="cart-header"
							title="<?php esc_attr_e('Koszyk', 'start'); ?>"
							rel="noopener noreferrer"
							target="_self">
							<?php echo aw_svg('cart'); ?>
							<span class="count">0</span>
						</a>
					</div>

					<button class="burger"
						aria-label="Open the menu"><span></span><span></span><span></span></button><!-- burger -->
				</div>
			</div>
		</header><!-- #masthead -->
		<?php
		$current_lang = function_exists('pll_current_language')
			? pll_current_language('slug')
			: '';
		?>

		<div
			id="site-search-modal"
			class="search-modal"
			hidden
			aria-hidden="true">
			<div class="search-modal__backdrop js-search-close" aria-hidden="true"></div>

			<div
				class="search-modal__dialog"
				role="dialog"
				aria-modal="true"
				aria-labelledby="site-search-title">
				<div class="search-modal__header">
					<h2 id="site-search-title" class="search-modal__title">
						<?php esc_html_e('Szukaj produktów', 'start'); ?>
					</h2>

					<button
						type="button"
						class="search-modal__close js-search-close"
						aria-label="<?php esc_attr_e('Zamknij wyszukiwarkę', 'start'); ?>">
						<span aria-hidden="true">×</span>
					</button>
				</div>

				<div class="search-modal__body">
					<p style="margin-bottom:0.5rem;"><?php esc_html_e('Wpisz min. 3 litery produktu', 'start'); ?></p>
					<form
						role="search"
						method="get"
						class="search-modal__form js-search-form"
						action="#"
						novalidate
						autocomplete="off">
						<label class="screen-reader-text" for="aw-header-search">
							<?php esc_html_e('Szukaj produktów', 'start'); ?>
						</label>

						<div class="search-modal__field-wrap">
							<input
								id="aw-header-search"
								type="search"
								class="search-modal__input"
								placeholder="<?php esc_attr_e('Wpisz nazwę produktu...', 'start'); ?>"
								value=""
								name="s"
								autocomplete="off"
								enterkeyhint="search"
								data-rlvlive="true"
								data-rlvconfig="default"
								data-rlvparentel="#aw-search-live-results" />

							<input type="hidden" name="post_type" value="product">

							<?php if (!empty($current_lang)) : ?>
								<input type="hidden" name="lang" value="<?php echo esc_attr($current_lang); ?>">
							<?php endif; ?>

							<button type="button" class="search-modal__submit js-search-refresh">
								<?php echo aw_svg('search'); ?>
								<span><?php esc_html_e('Szukaj', 'start'); ?></span>
							</button>
						</div>

						<div
							id="aw-search-live-results"
							class="search-modal__results"
							aria-live="polite"
							aria-label="<?php esc_attr_e('Wyniki wyszukiwania', 'start'); ?>"></div>
					</form>
				</div>
			</div>
		</div>