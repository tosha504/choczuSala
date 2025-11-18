<?php

/**
 * Theme enqueue scripts and styles.
 *
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;
if (!function_exists('start_scripts')) {
	function start_scripts()
	{
		$theme_uri = get_template_directory_uri();
		// Custom JS
		wp_enqueue_script('start_functions', $theme_uri . '/src/index.js', ['jquery'], time(), true);

		wp_localize_script('start_functions', 'localizedObject', [
			'ajaxurl' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('ajax_nonce'),
		]);
		wp_enqueue_style('tailwind', get_template_directory_uri() . '/dist/style.css', [], '1.0', 'all');

		// Custom css
		wp_enqueue_style('start_style', $theme_uri . '/src/index.css', [], time());

		if (is_singular() && comments_open() && get_option('thread_comments')) {
			wp_enqueue_script('comment-reply');
		}

		if (is_singular() && comments_open() && get_option('thread_comments')) {
			wp_enqueue_script('comment-reply');
		}

		$blocks_dir = get_template_directory() . '/blocks';
		$blocks_uri = $theme_uri . '/blocks';
		$post_id = get_queried_object_id();

		if (!is_dir($blocks_dir)) {
			return;
		}

		$block_folders = scandir($blocks_dir);

		foreach ($block_folders as $folder) {
			// Pomijamy "." i ".." oraz pliki
			if ($folder === '.' || $folder === '..') {
				continue;
			}
			$index = '/index.js';
			$block_path = $blocks_dir . '/' . $folder;
			$block_script = $block_path . $index;

			// Jeśli istnieje plik JS o nazwie takiej jak folder (np. hero-aw/hero-aw.js)
			if (is_dir($block_path) && file_exists($block_script)) {
				$block_name = 'aw-theme/' . $folder;
				// Jeśli blok jest użyty na tej stronie
				if (has_block($block_name, $post_id)) {
					wp_enqueue_script(
						$folder . '-block',                   // uchwyt
						$blocks_uri . '/' . $folder . $index, // URL
						['jquery'],                          // zależności
						'1.0.0',                              // wersja
						true                                  // ładowanie w stopce
					);
				}
			}
		}
	}
}
add_action('wp_enqueue_scripts', 'start_scripts',);


function mytheme_preload_google_fonts()
{
?>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Merriweather:ital,opsz,wght@0,18..144,300..900;1,18..144,300..900&display=swap" rel="stylesheet">
<?php
}
add_action('wp_head', 'mytheme_preload_google_fonts', 1);
