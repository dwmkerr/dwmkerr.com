<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the class=site-inner div and all content after
 *
 * @package Omega
 */
?>
		<?php omega_do_atomic( 'after_main' ); // omega_after_main ?>

	</div><!-- .site-inner -->

	<?php omega_do_atomic( 'before_footer' ); // omega_before_footer ?>
	<?php omega_do_atomic( 'footer' ); // omega_footer ?>	
	<?php omega_do_atomic( 'after_footer' ); // omega_after_footer ?>

</div><!-- .site-container -->

<?php omega_do_atomic( 'after' ); // omega_after ?>

<?php wp_footer(); ?>

</body>
</html>