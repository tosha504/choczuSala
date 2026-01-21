<?php

/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package start
 */

$logo_logo = get_field('logo_logo', 'options');
$title_menu = aw_get_option('title_menu');
$under_logo = aw_get_option('under_logo');
$categories_aw_title = aw_get_option('categories_aw_title');
$kontakt_title = aw_get_option('kontakt_title');
$payment = get_field('payment', 'options');  ?>


<footer id="colophon" class="footer">
  <div class="container cols">
    <div class="footer__logo">
      <?php echo !empty($logo_logo) ? my_custom_attachment_image($logo_logo) : "";
      echo !empty($under_logo) ? '<p class="muted">' . $under_logo . '</p>' : ""; ?>

    </div>
    <nav aria-label="Dodatkowe">
      <?php echo !empty($title_menu) ? '<h4>' . $title_menu . '</h4>' : "";
      wp_nav_menu(
        array(
          'theme_location' => 'menu-foot-info',
          'container' => false,
          'menu_id' => 'footerInfo',
          'menu_class' => '',
        ),
      ); ?>

    </nav>
    <nav aria-label="Kategorie">
      <?php echo !empty($title_menu) ? '<h4>' . $title_menu . '</h4>' : "";
      wp_nav_menu(
        array(
          'theme_location' => 'menu-foot-cat',
          'container' => false,
          'menu_id' => 'footerCatInfo',
          'menu_class' => '',
        ),
      ); ?>
    </nav>
    <div>
      <?php echo !empty($kontakt_title) ? '<h4>' . $kontakt_title . '</h4>' : "";
      ?>

      <p class="muted"> NIP: 9571181815, <br />REGON: 540331049<br />Tytusa Chałubińskiego 39, <br />80-807 Gdańsk<br /><a href="tel:+48792741241">+48 792 741 241</a><br /> <a href="mailto:sklep@chce-salo.pl"></a>sklep@chce-salo.pl</p>
    </div>
  </div>


  <div style="border-top:1px solid rgba(255,255,255,.15)" class="footer-bottom">
    <div class="container"
      style="display:flex;justify-content:space-between;align-items:center;padding:12px 1rem;font-size:.9rem">
      <span>© <span id="y"></span> Хочу Сала</span>
      <span><?php _e('Wykonanie: ', 'start'); ?> <a href="https://arturiko-web.eu/" target="_blank" rel="noopener noreferrer">arturiko-web</a></span>
      <?php echo my_custom_attachment_image($payment); ?>
    </div>
  </div>
</footer><!-- #colophon -->
</div><!-- #page -->
<!-- <?php //echo do_shortcode('[aw_google_reviews limit="9" mode="badge"]'); 
      ?> -->

<?php wp_footer(); ?>

</body>

</html>