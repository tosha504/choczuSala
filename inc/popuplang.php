<?php
// Exit if accessed directly.
defined('ABSPATH') || exit;
/**
 * Popup wyboru języka dla Polylang.
 */

add_action('wp_footer', 'aw_lang_selector_popup');
function aw_lang_selector_popup()
{
    // Zbuduj tablicę języków dla JS + HTML
    $langs_js = array();

    if (function_exists('pll_the_languages')) {
        $pll_raw = pll_the_languages(array('raw' => 1));
        if (is_array($pll_raw)) {
            foreach ($pll_raw as $l) {
                $slug = isset($l['slug']) ? $l['slug'] : '';
                if (!$slug) continue;

                $langs_js[$slug] = array(
                    'name' => isset($l['name']) ? $l['name'] : $slug,
                    'url'  => isset($l['url']) ? $l['url'] : home_url('/'),
                    'flag' => isset($l['flag']) ? $l['flag'] : '',
                );
            }
        }
    } else {
        // fallback gdy Polylang nieaktywny
        $langs_js['default'] = array(
            'name' => get_bloginfo('language'),
            'url'  => home_url('/'),
            'flag' => '',
        );
    }

    $json_langs   = wp_json_encode($langs_js);
    $default_lang = function_exists('pll_default_language') ? pll_default_language('slug') : '';

    // === HTML OVERLAYA ===
?>
    <div id="aw-lang-overlay" class="aw-lang-overlay" aria-hidden="true">
        <div class="aw-lang-overlay__backdrop"></div>
        <div class="aw-lang-overlay__dialog" role="dialog" aria-modal="true">
            <h2 class="aw-lang-overlay__title">
                <?php esc_html_e('Wybierz jezyk', 'aw-theme'); ?>
            </h2>
            <div class="aw-lang-overlay__buttons">
                <?php foreach ($langs_js as $code => $data) : ?>
                    <button
                        type="button"
                        class="aw-lang-btn"
                        data-lang="<?php echo esc_attr($code); ?>"
                        data-url="<?php echo esc_url($data['url']); ?>">
                        <?php if (!empty($data['flag'])) : ?>
                            <span class="aw-lang-btn__flag">
                                <img src="<?php
                                            // Polylang czasem zwraca gotowy <img>, czasem URL – zakładamy gotowy HTML
                                            echo $data['flag']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                            ?>" alt="">
                            </span>
                        <?php endif; ?>
                        <span class="aw-lang-btn__label">
                            <?php echo esc_html($data['name']); ?>
                        </span>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script>
        (function() {
            document.addEventListener('DOMContentLoaded', function() {
                var COOKIE_NAME = 'aw_lang_choice';
                var langs = <?php echo $json_langs ?: '{}'; ?>;
                var overlay = document.getElementById('aw-lang-overlay');

                // console.log('aw-lang-overlay element:', overlay, langs);

                if (!overlay) return;

                function getCookie(name) {
                    var m = document.cookie.match('(^|;)\\s*' + name + '\\s*=\\s*([^;]+)');
                    return m ? decodeURIComponent(m.pop()) : '';
                }

                function setCookie(name, value, days) {
                    try {
                        var d = new Date();
                        d.setTime(d.getTime() + (days || 365) * 24 * 60 * 60 * 1000);
                        var cookie = name + '=' + encodeURIComponent(value) +
                            ';expires=' + d.toUTCString() +
                            ';path=/;SameSite=Lax';
                        if (location.protocol === 'https:') cookie += ';secure';
                        document.cookie = cookie;
                    } catch (e) {}
                }

                // Jeśli język już wybrany → nie pokazujemy popupu
                var chosen = getCookie(COOKIE_NAME);
                // console.log('aw_lang_choice cookie:', chosen);

                if (chosen) {
                    overlay.setAttribute('aria-hidden', 'true');
                    return;
                }

                // --- Pokaż popup (brak wybranego języka) ---
                overlay.setAttribute('aria-hidden', 'false');
                document.documentElement.classList.add('aw-lang-lock');
                if (document.body) document.body.classList.add('aw-lang-lock');

                // Klik w przycisk języka w overlayu
                overlay.addEventListener('click', function(e) {
                    var btn = e.target.closest('.aw-lang-btn');
                    if (!btn) return;

                    var code = btn.getAttribute('data-lang');
                    var url = btn.getAttribute('data-url') || window.location.href;

                    if (code) {
                        setCookie(COOKIE_NAME, code, 365);
                    }

                    // normalne przeładowanie TYLKO po kliknięciu
                    window.location.href = url;
                });

                // Klik w standardowy switcher Polylanga → aktualizujemy nasze cookie
                document.addEventListener('click', function(e) {
                    var link = e.target.closest('.lang-item a, .menu-item-language a');
                    if (!link) return;

                    var path = link.pathname || '';
                    var code = null;

                    Object.keys(langs).forEach(function(c) {
                        if (path === '/' + c + '/' || path.indexOf('/' + c + '/') === 0) {
                            code = c;
                        }
                    });

                    if (code) {
                        setCookie(COOKIE_NAME, code, 365);
                    }
                });
            });
        })();
    </script>

<?php
}
