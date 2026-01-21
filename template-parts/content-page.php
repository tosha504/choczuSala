<?php

/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package start
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-content">
		<?php if (function_exists('is_checkout') && is_checkout() || function_exists('is_cart') && is_cart()) : ?>
			<div class="container">
				<?php the_content(); ?>
			</div>
		<?php else : ?>
			<?php the_content(); ?>
		<?php endif; ?>
	</div><!-- .entry-content -->
</article><!-- #post-<?php the_ID(); ?> -->