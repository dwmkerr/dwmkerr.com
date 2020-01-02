<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package Omega
 */

if ( ! function_exists( 'omega_content_nav' ) ) :
/**
 * Display navigation to next/previous pages when applicable
 */
function omega_content_nav( $nav_id ) {
	global $wp_query, $post;

	// Don't print empty markup on single pages if there's nowhere to navigate.
	if ( is_single() ) {
		$previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
		$next = get_adjacent_post( false, '', false );

		if ( ! $next && ! $previous )
			return;
	}

	// Don't print empty markup in archives if there's only one page.
	if ( $wp_query->max_num_pages < 2 && ( is_home() || is_archive() || is_search() ) )
		return;

	$nav_class = ( is_single() ) ? 'post-navigation' : 'paging-navigation';

	?>
	<nav role="navigation" id="<?php echo esc_attr( $nav_id ); ?>" class="navigation  <?php echo $nav_class; ?>">

	<?php if ( is_single() && !omega_get_setting( 'single_nav' ) ) : // navigation links for single posts ?>

		<?php previous_post_link( '<div class="nav-previous alignleft">%link</div>', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'omega' ) . '</span> %title' ); ?>
		<?php next_post_link( '<div class="nav-next alignright">%link</div>', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'omega' ) . '</span>' ); ?>

	<?php elseif ( $wp_query->max_num_pages > 1 && ( is_home() || is_archive() || is_search() ) ) : // navigation links for home, archive, and search pages ?>

		<?php
		if (current_theme_supports( 'loop-pagination' ) && ( 'numeric' == omega_get_setting( 'posts_nav' ) ) ) {
			loop_pagination();	
		} else {
			if ( get_next_posts_link() ) : ?>
			<div class="nav-previous alignleft"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Previous Page', 'omega' )); ?></div>
			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>
			<div class="nav-next alignright"><?php previous_posts_link( __( 'Next Page <span class="meta-nav">&rarr;</span>', 'omega' )); ?></div>
			<?php endif;
		}
		?>

	<?php endif; ?>

	</nav><!-- #<?php echo esc_html( $nav_id ); ?> -->
	<?php
}
endif; // omega_content_nav


/**
 * Returns true if a blog has more than 1 category
 */
function omega_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'all_the_cool_cats' ) ) ) {
		// Create an array of all the categories that are attached to posts
		$all_the_cool_cats = get_categories( array(
			'hide_empty' => 1,
		) );

		// Count the number of categories that are attached to the posts
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'all_the_cool_cats', $all_the_cool_cats );
	}

	if ( '1' != $all_the_cool_cats ) {
		// This blog has more than 1 category so omega_categorized_blog should return true
		return true;
	} else {
		// This blog has only 1 category so omega_categorized_blog should return false
		return false;
	}
}

/**
 * Flush out the transients used in omega_categorized_blog
 */
function omega_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'all_the_cool_cats' );
}
add_action( 'edit_category', 'omega_category_transient_flusher' );
add_action( 'save_post',     'omega_category_transient_flusher' );