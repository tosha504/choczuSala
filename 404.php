<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package start
 */

get_header();
?>

<main id="primary" class="site-main">
		<section class="error">
			<div class="container">
				<div class="error_content">
					<h1 class="error_content-title"><?php esc_html_e( 'Wygląda na to, że w tej lokalizacji nic nie znaleziono. Może spróbuj jednego z poniższych linków lub wyszukiwania?', 'teatr' ); ?></h1>
					<div class="e404">
						<a href="<?php echo esc_url( home_url( '/' ) ) ?>">
							404
						</a>
					</div>
				</div><!-- .page-content -->
			</div>
		</section><!-- .error-404 -->	
	</main><!-- #main -->

<?php
get_footer();
