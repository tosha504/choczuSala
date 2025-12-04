<?php
// Exit if accessed directly.
defined('ABSPATH') || exit;

if (class_exists('ACF')) {
  add_action('init', function () {
    $paths = glob(get_theme_file_path('blocks/*/block.json'));
    if (!$paths) return;

    foreach ($paths as $json) {
      $dir = dirname($json);
      register_block_type($dir); // ← WP sam czyta block.json z katalogu
    }
  }, 9);
  add_filter('block_categories_all', 'register_my_block_category', 10, 2);
  add_filter('block_categories_all', 'register_my_block_category', 10, 2);

  function register_my_block_category($categories, $post)
  {
    return array_merge([
      [
        'slug'  => 'aw-theme',
        'title' => 'AW Theme',
        'icon'  => 'wordpress',
      ],
    ], $categories);
  }
}
