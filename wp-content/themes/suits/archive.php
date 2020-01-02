<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Suits
 * @since Suits 1.0
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

		<?php if ( have_posts() ) : ?>
			<header class="archive-header">
				<h1 class="archive-title"><?php
					if ( is_category() ) :
						printf( __( 'Category Archives: %s', 'suits' ), single_cat_title( '', false ) );

					elseif ( is_tag() ) :
						printf( __( 'Tag Archives: %s', 'suits' ), single_tag_title( '', false ) );

					elseif ( is_author() ) :
						/* Queue the first post, that way we know
						 * what author we're dealing with (if that is the case).
						 *
						 * We reset this later so we can run the loop
						 * properly with a call to rewind_posts().
						 */
						the_post();
						printf( __( 'Author Archives: %s', 'suits' ), '<span class="vcard">' . get_the_author() . '</span>' );
						rewind_posts();

					elseif ( is_day() ) :
						printf( __( 'Daily Archives: %s', 'suits' ), get_the_date() );

					elseif ( is_month() ) :
						printf( __( 'Monthly Archives: %s', 'suits' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'suits' ) ) );

					elseif ( is_year() ) :
						printf( __( 'Yearly Archives: %s', 'suits' ), get_the_date( _x( 'Y', 'yearly archives date format', 'suits' ) ) );

					else :
						_e( 'Archives', 'suits' );

					endif;
				?></h1>
				<?php
					// Show an optional term description.
					$term_description = term_description();
					if ( ! empty( $term_description ) ) :
						printf( '<div class="archive-meta">%s</div>', $term_description );
					endif;

					// If a user has filled out their description, show a bio on their entries.
					if ( is_author() && get_the_author_meta( 'description' ) ) : ?>
						<div class="archive-meta">
							<p><?php the_author_meta( 'description' ); ?></p>
						</div><!-- .archive-meta -->
					<?php endif; ?>
			</header><!-- .archive-header -->

			<?php /* The loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'content', get_post_format() ); ?>
			<?php endwhile; ?>

			<?php suits_paging_nav(); ?>

		<?php else : ?>
			<?php get_template_part( 'content', 'none' ); ?>
		<?php endif; ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>