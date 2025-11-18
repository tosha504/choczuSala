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

?>
<!-- Elfsight Google Reviews | Untitled Google Reviews -->
<div class="container">
  <script src="https://elfsightcdn.com/platform.js" async></script>
  <div class="elfsight-app-ab667762-539e-4d5b-b95f-d3c7e96dcd23" data-elfsight-app-lazy></div>
</div>
<footer id="colophon" class="footer">
  <div class="container cols">
    <div>
      <h3 style="margin-top:0">Хочу Сала</h3>
      <p class="muted">Sklep z produktami wschodnimi w Gdańsku. Najlepsze słoniny, ryby, słodycze i napoje.</p>
    </div>
    <nav aria-label="Dodatkowe">
      <h4>Informacje</h4>
      <ul style="list-style:none;padding:0;margin:0;display:grid;gap:6px">
        <li><a href="#">O nas</a></li>
        <li><a href="#">Dostawa</a></li>
        <li><a href="#">Płatności</a></li>
        <li><a href="#">Regulamin</a></li>
        <li><a href="#">Polityka prywatności</a></li>
      </ul>
    </nav>
    <nav aria-label="Kategorie">
      <h4>Kategorie</h4>
      <ul style="list-style:none;padding:0;margin:0;display:grid;gap:6px">
        <li><a href="#kategorie">Słonina</a></li>
        <li><a href="#kategorie">Wędliny</a></li>
        <li><a href="#kategorie">Ryby</a></li>
        <li><a href="#kategorie">Słodycze</a></li>
      </ul>
    </nav>
    <div>
      <h4>Kontakt</h4>
      <p class="muted">ul. Przykładowa 1, 80-000 Gdańsk<br />tel. 500 000 000<br />email: sklep@chce-salo.pl</p>
    </div>
  </div>


  <div style="border-top:1px solid rgba(255,255,255,.15)">
    <div class="container"
      style="display:flex;justify-content:space-between;align-items:center;padding:12px 0;font-size:.9rem">
      <span>© <span id="y"></span> Хочу Сала</span>
      <span>Demo by <a href="https://arturiko-web.eu/" target="_blank" rel="noopener noreferrer">arturiko-web</a></span>
    </div>
  </div>
</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>

</html>