<article <?php omega_attr( 'post' ); ?>>

	<div class="entry-wrap">

		<?php omega_do_atomic( 'before_entry' ); // omega_before_entry ?>

		<div class="entry-content">

			<?php omega_do_atomic( 'entry' ); // omega_entry ?>

		</div><!-- .entry-content -->

		<?php omega_do_atomic( 'after_entry' ); // omega_after_entry ?>

	</div><!-- .entry-wrap -->

</article><!-- #post-## -->