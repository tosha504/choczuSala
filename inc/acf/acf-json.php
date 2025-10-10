<?php
defined('ABSPATH') || exit;
function acf_json_save_point()
{
  return get_template_directory() . '/acf-json';
}
add_filter('acf/settings/save_json', 'acf_json_save_point');

function acf_json_load_point($paths)
{
  unset($paths[0]);
  $paths[] = get_template_directory() . '/acf-json';
  return $paths;
}
add_filter('acf/settings/load_json', 'acf_json_load_point');

// jeśli chcesz warunkowo zmieniać zapis tylko dla konkretnych grup
function acf_json_change_field_group($group)
{
  $groups = [
    'group_64dcb34c9db9a',
    'group_64dcb34c9db9a__trashed',
    'group_64dc8b9fc1e74',
    'group_64dc8b9fc1e74__trashed',
    'group_64e30cbb90836',
    'group_64e30cbb90836__trashed',
  ];
  if (in_array($group['key'], $groups)) {
    add_filter('acf/settings/save_json', 'acf_json_save_point');
  }
  return $group;
}
add_action('acf/update_field_group', 'acf_json_change_field_group');
add_action('acf/trash_field_group', 'acf_json_change_field_group');
add_action('acf/untrash_field_group', 'acf_json_change_field_group');
