<?php

/**
 * Custom functions
 *
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

function my_custom_attachment_image(int $attachment_id, array $args = []): string
{
  if ($attachment_id <= 0 || !get_post($attachment_id)) {
    return '';
  }

  $defaults = [
    'size'     => 'large',
    'class'    => null,
    'priority' => false,
    'sizes'    => '100vw',
    'attrs'    => [],
    'preload'  => false,
    'icon'     => null, // auto
  ];
  $args = array_merge($defaults, $args);

  // ALT z metadanych lub tytuł załącznika
  $alt = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
  if (!is_string($alt) || trim($alt) === '') {
    $alt = get_the_title($attachment_id) ?: '';
  }

  // Atrybuty <img>
  $attrs = array_merge([
    'alt'      => $alt,
    'sizes'    => $args['sizes'],
    'decoding' => 'async',
  ], $args['attrs']);

  if ($args['class']) {
    $attrs['class'] = $args['class'];
  }

  if ($args['priority']) {
    // LCP / priorytet
    $attrs['loading'] = 'eager';
    $attrs['fetchpriority'] = 'high';
  } else {
    $attrs['loading'] = 'lazy';
  }

  // Ikona typu MIME, jeśli to nie obraz
  $icon = $args['icon'];
  if ($icon === null) {
    $icon = !wp_attachment_is_image($attachment_id);
  }

  // Opcjonalny preload (tylko dla obrazów)
  if ($args['preload'] && !$icon) {
    my_enqueue_image_preload_once($attachment_id, $args['size'], $attrs['sizes']);
  }

  // Render
  $html = wp_get_attachment_image($attachment_id, $args['size'], $icon, $attrs);
  return $html ?: '';
}

/**
 * Dodaje <link rel="preload" as="image"> dla danego rozmiaru obrazu.
 * Zapobiega duplikatom i wypisuje linki raz w <head>.
 */
function my_enqueue_image_preload_once(int $attachment_id, $size = 'large', string $sizes = '100vw'): void
{
  static $queued = [];
  $img = wp_get_attachment_image_src($attachment_id, $size);
  if (!$img || empty($img[0])) return;

  $url = $img[0];
  if (isset($queued[$url])) return;

  $srcset = wp_get_attachment_image_srcset($attachment_id, $size);
  $queued[$url] = ['url' => $url, 'srcset' => $srcset, 'sizes' => $sizes];

  // Wypisz tylko raz
  add_action('wp_head', function () use (&$queued) {
    foreach ($queued as $item) {
      printf(
        '<link rel="preload" as="image" href="%s"%s%s fetchpriority="high">' . "\n",
        esc_url($item['url']),
        $item['srcset'] ? ' imagesrcset="' . esc_attr($item['srcset']) . '"' : '',
        $item['sizes']  ? ' imagesizes="' . esc_attr($item['sizes']) . '"'  : ''
      );
    }
    $queued = [];
  }, 1);
}
function show_title($tag, $text_title, $color_text = null, $class_title = null)
{
  $text_color = $color_text !== null && !empty($color_text) ? "style='color: $color_text;'" : "";
  $cls = $class_title !== null ? "class='{$class_title}'" : "";
  if (!empty($text_title)) {
    echo <<<TITLE
    <div class="title-block-ps">
    <$tag $cls $text_color>$text_title</$tag>
    </div>
    TITLE;
  }
}
