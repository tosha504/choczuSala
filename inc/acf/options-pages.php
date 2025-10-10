<?php defined('ABSPATH') || exit;
add_action('acf/init', 'my_acf_options_pages');

function my_acf_options_pages()
{
  if (function_exists('acf_add_options_page')) {
    acf_add_options_page([
      'page_title' => 'General settings',
      'menu_title' => 'General settings',
      'menu_slug'  => 'theme-general-settings',
      'capability' => 'edit_posts',
      'redirect'   => false,
    ]);

    acf_add_options_page([
      'page_title' => 'Header settings',
      'menu_title' => 'Header settings',
      'menu_slug'  => 'theme-header-settings',
      'capability' => 'edit_posts',
      'redirect'   => false,
    ]);

    acf_add_options_page([
      'page_title' => 'Footer settings',
      'menu_title' => 'Footer settings',
      'menu_slug'  => 'theme-footer-settings',
      'capability' => 'edit_posts',
      'redirect'   => false,
    ]);
  }
}
