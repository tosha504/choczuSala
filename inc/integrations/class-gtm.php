<?php

declare(strict_types=1);

/**
 * Google Tag Manager integration.
 *
 * Author: arturiko-web
 * Author URI: https://arturiko-web.eu
 */

if (!defined('ABSPATH')) {
    exit;
}

final class AW_Google_Tag_Manager
{

    private const GTM_ID = 'GTM-5BP7DNR5';


    public function init(): void
    {
        add_action('wp_head', [$this, 'render_head_code'], 1);
        add_action('wp_body_open', [$this, 'render_body_code'], 1);
    }

    /**
     * Render GTM script in <head>.
     */
    public function render_head_code(): void
    {
       
        if ($this->should_skip()) {
            return;
        }
    
?>

        <!-- Google Tag Manager -->
        <script>
            (function(w, d, s, l, i) {
                w[l] = w[l] || [];
                w[l].push({
                    'gtm.start': new Date().getTime(),
                    event: 'gtm.js'
                });
                var f = d.getElementsByTagName(s)[0],
                    j = d.createElement(s),
                    dl = l != 'dataLayer' ? '&l=' + l : '';
                j.async = true;
                j.src =
                    'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
                f.parentNode.insertBefore(j, f);
            })(window, document, 'script', 'dataLayer', '<?php echo esc_js(self::GTM_ID); ?>');
        </script>
        <!-- End Google Tag Manager -->
    <?php
    }

    /**
     * Render GTM noscript immediately after opening <body>.
     */
    public function render_body_code(): void
    {
        if ($this->should_skip()) {
            return;
        }
    ?>
        <!-- Google Tag Manager (noscript) -->
        <noscript>
            <iframe
                src="https://www.googletagmanager.com/ns.html?id=<?php echo esc_attr(self::GTM_ID); ?>"
                height="0"
                width="0"
                style="display:none;visibility:hidden"></iframe>
        </noscript>
        <!-- End Google Tag Manager (noscript) -->
<?php
    }

    /**
     * Skip GTM in selected contexts.
     */
    private function should_skip(): bool
    {
        // Nie ładuj w panelu admina.
        if (is_admin()) {
            return true;
        }

        // Opcjonalnie: nie ładuj dla zalogowanych administratorów.
        // Przydatne, gdy nie chcesz zanieczyszczać danych.
        if (current_user_can('manage_options')) {
            return true;
        }

        // Opcjonalnie: wyłącz na local/staging.
        // if (wp_get_environment_type() !== 'production') {
        // 	return true;
        // }

        return false;
    }
}
