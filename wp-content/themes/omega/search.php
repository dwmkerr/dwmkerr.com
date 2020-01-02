<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package Omega
 */

get_header(); ?>

	<main class="<?php echo omega_apply_atomic( 'main_class', 'content' );?>" role="main" itemprop="mainContentOfPage" itemscope="itemscope" itemtype="http://schema.org/SearchResultsPage">

		<?php omega_do_atomic( 'before_content' ); // omega_before_content ?>

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="archive-title"><?php printf( __( 'Search Results for: %s', 'omega' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
			</header><!-- .page-header -->

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'partials/content', 'search' ); ?>

			<?php endwhile; ?>

			<?php omega_content_nav( 'nav-below' ); ?>

		<?php else : ?>

			<?php get_template_part( 'no-results', 'search' ); ?>

		<?php endif; ?>

		<?php omega_do_atomic( 'after_content' ); // omega_after_content ?>

	</main><!-- .content -->
	
<?php get_footer(); ?>