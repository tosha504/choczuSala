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
		<div class="header-launch-notice">
			<p class="header-launch-notice__text">
				<?php _e('Jesteśmy świeżo po starcie. Jeśli zauważysz problem techniczny, zgłoś go tutaj:', 'aw-theme'); ?>
				<a
					class="header-launch-notice__link"
					href="<?php echo esc_url('https://t.me/aH_human'); ?>"
					target="_blank"
					rel="noopener noreferrer nofollow">
					<?php _e('Telegram', 'aw-theme'); ?>
				</a>
			</p>
		</div>
		<!-- Top bar -->
		<?php
		$pre_header_text = aw_get_option('pre_header_text');
		$work_time       = aw_get_option('work_time');
		$social_links    = aw_get_option('topbar_social_links');

		$has_topbar_content = ! empty($pre_header_text) || ! empty($work_time) || ! empty($social_links);
		?>

		<?php if ($has_topbar_content) : ?>
			<div class="topbar">
				<div class="container topbar__inner">

					<?php if (! empty($pre_header_text) || ! empty($work_time)) : ?>
						<div class="topbar__notice">
							<?php if (! empty($pre_header_text)) : ?>
								<span class="topbar__notice-item">
									<?php echo esc_html($pre_header_text); ?>
								</span>
							<?php endif; ?>

							<?php if (! empty($work_time)) : ?>
								<span class="topbar__notice-item">
									<?php echo esc_html($work_time); ?>
								</span>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<?php if (! empty($social_links) && is_array($social_links)) : ?>
						<nav class="topbar__socials" aria-label="<?php echo esc_attr__('Social media', 'arturiko-web'); ?>">
							<?php foreach ($social_links as $social_link) : ?>
								<?php
								$platform = ! empty($social_link['platform']) ? sanitize_key($social_link['platform']) : 'custom';
								$url      = ! empty($social_link['url']) ? esc_url($social_link['url']) : '';
								$label    = ! empty($social_link['label'])
									? sanitize_text_field($social_link['label'])
									: aw_get_social_label($platform);

								$open_new_tab = ! empty($social_link['open_new_tab']);

								if (empty($url)) {
									continue;
								}
								?>

								<a
									class="topbar__social-link topbar__social-link--<?php echo esc_attr($platform); ?>"
									href="<?php echo esc_url($url); ?>"
									aria-label="<?php echo esc_attr($label); ?>"
									<?php if ($open_new_tab) : ?>
									target="_blank"
									rel="noopener noreferrer"
									<?php endif; ?>>
									<?php echo aw_get_social_icon_svg($platform); ?>
									<span class="screen-reader-text"><?php echo esc_html($label); ?></span>
								</a>
							<?php endforeach; ?>
						</nav>
					<?php endif; ?>

				</div>
			</div>
		<?php endif; ?>
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
				<?php if (function_exists('aw_render_product_catalog_menu')) : ?>
					<div class="header__catalog">
						<?php
						aw_render_product_catalog_menu([
							'title'                 => __('Nasze produkty', 'start'),
							'hide_empty'            => false,
							'exclude_uncategorized' => true,
						]);
						?>
					</div>
				<?php endif; ?>

				<nav id="site-navigation" class="main-navigation">
					<div class="aw-mobile-nav-tabs" role="tablist" aria-label="<?php esc_attr_e('Nawigacja mobilna', 'start'); ?>">
						<button
							type="button"
							class="aw-mobile-nav-tabs__button is-active js-aw-mobile-nav-tab"
							data-aw-mobile-tab="categories"
							aria-selected="true">
							<?php esc_html_e('Kategorie', 'start'); ?>
						</button>

						<button
							type="button"
							class="aw-mobile-nav-tabs__button js-aw-mobile-nav-tab"
							data-aw-mobile-tab="menu"
							aria-selected="false">
							<?php esc_html_e('Menu', 'start'); ?>
						</button>
					</div>

					<div class="aw-mobile-nav-panel aw-mobile-nav-panel--categories is-active js-aw-mobile-nav-panel" data-aw-mobile-panel="categories">
						<?php
						if (function_exists('aw_render_product_catalog_mobile_tree')) {
							aw_render_product_catalog_mobile_tree([
								'hide_empty'            => false,
								'exclude_uncategorized' => true,
							]);
						}
						?>
					</div>

					<div class="aw-mobile-nav-panel aw-mobile-nav-panel--menu js-aw-mobile-nav-panel" data-aw-mobile-panel="menu">
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'menu-header',
								'container'      => false,
								'menu_id'        => 'primary-menu1',
								'menu_class'     => 'header__nav',
							),
						);

						// aw_language_switcher();
						?>
					</div>
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

		$search_action_url = home_url('/');

		if (function_exists('pll_home_url') && !empty($current_lang)) {
			$search_action_url = pll_home_url($current_lang);
		}
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
					<p style="margin-bottom:0.5rem;">
						<?php esc_html_e('Wpisz min. 2–3 litery produktu', 'start'); ?>
					</p>
					<form
						role="search"
						method="get"
						class="search-modal__form js-search-form"
						action="<?php echo esc_url(aw_get_search_action_url()); ?>"
						data-search-action="<?php echo esc_url(aw_get_search_action_url()); ?>"
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
							<input type="hidden" name="lang" value="<?php echo esc_attr(aw_get_current_lang()); ?>">

							<button type="submit" class="search-modal__submit js-search-refresh">
								<?php echo aw_svg('search'); ?>
								<span><?php esc_html_e('Szukaj', 'start'); ?></span>
							</button>
						</div>

						<div
							id="aw-search-live-results"
							class="search-modal__results"
							aria-live="polite"
							aria-label="<?php esc_attr_e('Wyniki wyszukiwania', 'start'); ?>">
						</div>
					</form>
				</div>
			</div>
		</div>
		<style>
			.header {
				z-index: 50;
				overflow: visible;
			}

			.header.is-product-menu-open,
			body.aw-product-menu-is-open .header,
			#masthead.is-product-menu-open,
			body.aw-product-menu-is-open #masthead {
				position: relative !important;
				top: auto !important;
			}

			.header>.container {
				overflow: visible;
			}

			.header__catalog {
				display: none;
			}

			@media (min-width: 1200px) {
				.header__catalog {
					display: block;
				}
			}

			.aw-product-menu {
				position: relative;
				z-index: 80;
			}

			.aw-product-menu__toggle {
				display: inline-flex;
				align-items: center;
				gap: 12px;
				min-height: 54px;
				padding: 14px 22px;
				border: 0;
				border-radius: 14px;
				background: #1f2933;
				color: #fff;
				font: inherit;
				font-weight: 800;
				line-height: 1;
				cursor: pointer;
			}

			.aw-product-menu__burger {
				display: inline-flex;
				flex-direction: column;
				gap: 5px;
				width: 22px;
			}

			.aw-product-menu__burger span {
				display: block;
				width: 22px;
				height: 3px;
				border-radius: 999px;
				background: currentColor;
			}

			.aw-product-menu__panel {
				position: absolute;
				top: calc(100% + 12px);
				left: 0;
				width: 380px;
				overflow: visible;
				padding: 10px;
				border: 1px solid rgba(15, 23, 42, 0.1);
				border-radius: 18px;
				background: #fff;
				box-shadow: 0 22px 70px rgba(15, 23, 42, 0.18);
				z-index: 9999;
			}

			.aw-product-menu__list,
			.aw-product-menu__submenu-list {
				list-style: none;
				margin: 0;
				padding: 0;
			}

			.aw-product-menu__item {
				position: relative;
			}

			.aw-product-menu__item-row {
				display: flex;
				align-items: center;
				min-height: 58px;
				border-radius: 14px;
			}

			.aw-product-menu__item-row:hover,
			.aw-product-menu__item:focus-within>.aw-product-menu__item-row,
			.aw-product-menu__item.is-open>.aw-product-menu__item-row {
				background: #f5f6f8;
			}

			.aw-product-menu__link {
				display: flex;
				align-items: center;
				gap: 14px;
				flex: 1;
				min-width: 0;
				padding: 10px 12px;
				color: #1f2933;
				text-decoration: none;
			}

			.aw-product-menu__image,
			.aw-category-image-placeholder {
				width: 42px;
				height: 42px;
				flex: 0 0 42px;
				object-fit: contain;
				border-radius: 12px;
				background: #f1f3f5;
			}

			.aw-category-image-placeholder {
				display: inline-block;
			}

			.aw-product-menu__label {
				display: block;
				overflow: hidden;
				font-size: 15px;
				font-weight: 700;
				line-height: 1.25;
				text-overflow: ellipsis;
				white-space: nowrap;
			}

			.aw-product-menu__submenu-toggle {
				display: inline-flex;
				align-items: center;
				justify-content: center;
				width: 42px;
				height: 42px;
				margin-right: 6px;
				border: 0;
				border-radius: 10px;
				background: transparent;
				color: #6b7280;
				font-size: 24px;
				line-height: 1;
				cursor: pointer;
			}

			.aw-product-menu__submenu-toggle:hover,
			.aw-product-menu__submenu-toggle:focus-visible {
				background: #e5e7eb;
				color: #111827;
			}

			.aw-product-menu__submenu {
				display: none;
				position: absolute;
				top: 0;
				left: calc(100% + 12px);
				width: 360px;
				max-height: 72vh;
				overflow-y: auto;
				padding: 14px;
				border: 1px solid rgba(15, 23, 42, 0.1);
				border-radius: 18px;
				background: #fff;
				box-shadow: 0 22px 70px rgba(15, 23, 42, 0.16);
				z-index: 10000;
			}

			.aw-product-menu__item--has-children:hover>.aw-product-menu__submenu,
			.aw-product-menu__item--has-children:focus-within>.aw-product-menu__submenu,
			.aw-product-menu__item--has-children.is-open>.aw-product-menu__submenu {
				display: block;
			}

			.aw-product-menu__submenu-item {
				margin: 0;
			}

			.aw-product-menu__submenu-link {
				display: flex;
				width: 100%;
				padding: 9px 10px;
				border-radius: 10px;
				color: #1f2933;
				font-size: 14px;
				font-weight: 600;
				line-height: 1.3;
				text-decoration: none;
			}

			.aw-product-menu__submenu-link:hover,
			.aw-product-menu__submenu-link:focus-visible {
				background: #f3f4f6;
				color: #111827;
			}

			.aw-product-menu__submenu-list--depth-2,
			.aw-product-menu__submenu-list--depth-3,
			.aw-product-menu__submenu-list--depth-4 {
				margin: 2px 0 8px 14px;
				padding-left: 12px;
				border-left: 1px solid #e5e7eb;
			}

			.aw-product-menu__submenu-list--depth-2 .aw-product-menu__submenu-link,
			.aw-product-menu__submenu-list--depth-3 .aw-product-menu__submenu-link,
			.aw-product-menu__submenu-list--depth-4 .aw-product-menu__submenu-link {
				font-size: 13px;
				font-weight: 500;
				color: #4b5563;
			}

			/* Mobile tabs/offcanvas */
			.aw-mobile-nav-tabs,
			.aw-mobile-nav-panel--categories {
				display: none;
			}

			@media (max-width: 1199px) {
				#site-navigation.main-navigation {
					width: min(92vw, 430px);
					padding: 28px 28px 38px;
				}

				.aw-mobile-nav-tabs {
					display: grid;
					grid-template-columns: 1fr 1fr;
					gap: 0;
					width: 100%;
					margin: 28px 0 22px;
					border-bottom: 1px solid #9ca3af;
				}

				.aw-mobile-nav-tabs__button {
					appearance: none;
					border: 0;
					border-bottom: 3px solid transparent;
					background: transparent;
					padding: 0 10px 18px;
					color: #8b8b8b;
					font: inherit;
					font-size: 20px;
					font-weight: 600;
					text-transform: uppercase;
					cursor: pointer;
				}

				.aw-mobile-nav-tabs__button.is-active {
					border-bottom-color: #222;
					color: #222;
				}

				.aw-mobile-nav-panel {
					display: none;
					width: 100%;
				}

				.aw-mobile-nav-panel.is-active {
					display: block;
				}

				.aw-mobile-nav-panel--menu .header__nav {
					display: flex;
				}

				.aw-mobile-categories,
				.aw-mobile-categories__list {
					width: 100%;
				}

				.aw-mobile-categories__list {
					list-style: none;
					margin: 0;
					padding: 0;
				}

				.aw-mobile-categories__item {
					margin: 0;
				}

				.aw-mobile-categories__row {
					display: flex;
					align-items: center;
					gap: 10px;
					min-height: 64px;
				}

				.aw-mobile-categories__link {
					display: flex;
					align-items: center;
					gap: 16px;
					flex: 1;
					min-width: 0;
					color: #222;
					text-decoration: none;
				}

				.aw-mobile-categories__image {
					width: 46px;
					height: 46px;
					flex: 0 0 46px;
					object-fit: contain;
					border-radius: 999px;
					background: #f3f4f6;
				}

				.aw-mobile-categories__label {
					display: block;
					font-size: 20px;
					font-weight: 500;
					line-height: 1.25;
				}

				.aw-mobile-categories__toggle {
					appearance: none;
					display: inline-flex;
					align-items: center;
					justify-content: center;
					width: 42px;
					height: 42px;
					border: 0;
					background: transparent;
					color: #222;
					font-size: 24px;
					line-height: 1;
					cursor: pointer;
				}

				.aw-mobile-categories__item.is-open>.aw-mobile-categories__row .aw-mobile-categories__toggle span {
					transform: rotate(180deg);
				}

				.aw-mobile-categories__toggle span {
					display: block;
					transition: transform 0.2s ease;
				}

				.aw-mobile-categories__children {
					padding: 2px 0 10px 62px;
				}

				.aw-mobile-categories__list--depth-1,
				.aw-mobile-categories__list--depth-2,
				.aw-mobile-categories__list--depth-3 {
					border-left: 1px solid #e5e7eb;
					padding-left: 16px;
				}

				.aw-mobile-categories__item--depth-1 .aw-mobile-categories__row,
				.aw-mobile-categories__item--depth-2 .aw-mobile-categories__row,
				.aw-mobile-categories__item--depth-3 .aw-mobile-categories__row {
					min-height: 46px;
				}

				.aw-mobile-categories__item--depth-1 .aw-mobile-categories__label {
					font-size: 16px;
					font-weight: 600;
				}

				.aw-mobile-categories__item--depth-2 .aw-mobile-categories__label,
				.aw-mobile-categories__item--depth-3 .aw-mobile-categories__label {
					font-size: 15px;
					font-weight: 500;
					color: #4b5563;
				}

				.aw-mobile-categories__item--depth-1 .aw-mobile-categories__link,
				.aw-mobile-categories__item--depth-2 .aw-mobile-categories__link,
				.aw-mobile-categories__item--depth-3 .aw-mobile-categories__link {
					gap: 0;
				}
			}

			@media (min-width: 1200px) {
				.aw-mobile-nav-panel {
					display: block;
				}

				.aw-mobile-nav-panel--categories,
				.aw-mobile-nav-tabs {
					display: none !important;
				}

				.aw-mobile-nav-panel--menu {
					display: flex;
					align-items: center;
				}
			}
		</style>