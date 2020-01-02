<?php
/**
 * The template for displaying the footer.
 *
 * Contains footer content and the closing of the
 * #main and #page div elements.
 *
 * @package Suits
 * @since Suits 1.0
 */
?>

		</div><!-- #main -->
		<footer id="colophon" class="site-footer" role="contentinfo">
			<?php get_sidebar( 'footer' ); ?>

			<div class="site-info-container">
				<div class="site-info">
					<?php do_action( 'suits_credits' ); ?>
					<?php printf( __( 'Proudly powered by %s', 'suits' ), '<a href="http://wordpress.org/" title="Semantic Personal Publishing Platform">WordPress</a>' ); ?>
					<span class="sep"> &middot; </span>
					<?php printf( __( 'Theme: %1$s by %2$s', 'suits' ), 'Suits', '<a href="http://www.themeweaver.net/" title="Theme Developer" rel="designer">Theme Weaver</a>' ); ?>
				</div><!-- .site-info -->
			</div><!-- .site-info-container -->
		</footer><!-- #colophon -->
	</div><!-- #page -->

	<?php wp_footer(); ?>
</body>
</html>