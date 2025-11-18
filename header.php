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
	<!-- Elfsight Google Reviews | Untitled Google Reviews -->
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
				<?php
				if (is_active_sidebar('lang')) : ?>
					<aside class="footer-widget-area">
						<?php dynamic_sidebar('lang'); ?>
					</aside>
				<?php endif; ?>


				<div>Świeże dostawy • Odbiór osobisty w Gdańsku</div>
				<div>Pn–Sb 10:00–20:00</div>
			</div>
		</div>
		<header id="masthead" class="header">
			<div class="container">
				<?php
				$logo = get_field('logo', 'options');
				if ($logo) { ?>
					<div class="header__logo">
						<a class="header__logo_link" href="<?php echo esc_url(home_url('/')) ?>" title="Go to homepage"
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
					?>
				</nav><!-- #site-navigation -->
				<div class="actions">
					<div class="search" role="search"><a href="#"> <img src="<?php echo get_template_directory_uri() . '/assets/image/icons/woo/search.svg'; ?>" alt="search"></a>
					</div>
					<div class="actions__cart">
						<?php
						$account_page_id = get_option('woocommerce_cart_page_id');
						$translated_id = function_exists('pll_get_post') ? pll_get_post($account_page_id) : $account_page_id;
						$account_url = get_permalink($translated_id);
						?>
						<a class="header__logo_link"
							href="<?php echo esc_url($account_url); ?>"
							title="<?php esc_attr_e('Moje konto', 'start'); ?>"
							rel="noopener noreferrer"
							target="_self">
							<img src="<?php echo get_template_directory_uri() . '/assets/image/icons/woo/cart.svg'; ?>" alt="Go to cart page">
						</a>
						<?php $account_page_id = get_option('woocommerce_myaccount_page_id');
						$translated_id = function_exists('pll_get_post') ? pll_get_post($account_page_id) : $account_page_id;
						$account_url = get_permalink($translated_id); ?>
						<a class="header__logo_link"
							href="<?php echo esc_url($account_url); ?>"
							title="<?php esc_attr_e('Moje konto', 'start'); ?>"
							rel="noopener noreferrer"
							target="_self">
							<img src="<?php echo get_template_directory_uri() . '/assets/image/icons/woo/account.svg'; ?>" alt="account">
						</a>
					</div>

					<button class="burger"
						aria-label="Open the menu"><span></span><span></span><span></span></button><!-- burger -->
				</div>
			</div>
		</header><!-- #masthead -->