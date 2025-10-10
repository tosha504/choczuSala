<?php
// Exit if accessed directly.
defined('ABSPATH') || exit;

if (class_exists('ACF')) {
  function register_acf_blocks()
  {
    $dir = get_template_directory() . '/blocks/';
    $blocks = scandir($dir);
    $fiels = array_diff($blocks, array('.', '..'));
    if (!empty($fiels)) {
      foreach ($fiels as $key => $block) {
        register_block_type(dirname(__DIR__) . "/blocks/{$block}/block.json");
      }
    }
  }
  add_action('init', 'register_acf_blocks');
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
