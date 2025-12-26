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

					function aw_language_switcher()
					{
						if (!function_exists('pll_the_languages')) return;

						$langs = pll_the_languages([
							'raw'           => 1,
							'hide_if_empty' => 0,
						]);

						if (empty($langs)) return;

						echo '<div class="aw-lang-switcher" data-aw-lang>';

						// DESKTOP (select)
						echo '<div class="aw-lang aw-lang--desktop">';
						echo '<select class="aw-lang__select" aria-label="Language switcher">';
						foreach ($langs as $lang) {
							printf(
								'<option value="%s" %s>%s</option>',
								esc_url($lang['url']),
								$lang['current_lang'] ? 'selected' : '',
								esc_html(strtoupper($lang['slug']))
							);
						}
						echo '</select>';
						echo '</div>';

						// MOBILE (buttons)
						echo '<div class="aw-lang aw-lang--mobile">';
						foreach ($langs as $lang) {
							printf(
								'<a href="%s" class="aw-lang__btn %s" aria-current="%s">%s</a>',
								esc_url($lang['url']),
								$lang['current_lang'] ? 'is-active' : '',
								$lang['current_lang'] ? 'true' : 'false',
								esc_html(strtoupper($lang['slug']))
							);
						}
						echo '</div>';

						echo '</div>';
					}
					aw_language_switcher();
					?>
				</nav><!-- #site-navigation -->
				<div class="actions">
					<div class="actions__woo">
						<!-- <a href="#" class="search" role="search">
							<?php echo aw_svg('search'); ?>
						</a> -->

						<?php
						$account_page_id = get_option('woocommerce_myaccount_page_id');
						$translated_id = function_exists('pll_get_post') ? pll_get_post($account_page_id) : $account_page_id;
						$account_url = get_permalink($translated_id); ?>
						<a
							href="<?php echo esc_url($account_url); ?>"
							title="<?php esc_attr_e('Moje konto', 'start'); ?>"
							rel="noopener noreferrer"
							target="_self">
							<?php echo aw_svg('account'); ?>
						</a>
						<?php
						$account_page_id = get_option('woocommerce_cart_page_id');
						$translated_id = function_exists('pll_get_post') ? pll_get_post($account_page_id) : $account_page_id;
						$account_url = get_permalink($translated_id);
						?>
						<a
							href="<?php echo esc_url($account_url); ?>" class="cart-header"
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