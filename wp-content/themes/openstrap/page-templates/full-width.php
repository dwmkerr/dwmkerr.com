<?php
/**
 * Template Name: Full Width Template
 *
 * Page template for
 *
 * @package Openstrap
 * @since Openstrap 0.1
 */

get_header(); ?>



	<!-- Main Content -->	
	<div class="col-md-12" role="main">
	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>			
				<?php get_template_part( 'content', 'page' ); ?>
				<?php comments_template( '', true ); ?>				
		<?php endwhile; ?>

	<?php else : ?>

		<h2><?php _e('No posts.', 'openstrap' ); ?></h2>
		<p class="lead"><?php _e('Sorry about this, I couldn\'t seem to find what you were looking for.', 'openstrap' ); ?></p>
		
	<?php endif; ?>		
	
	<?php openstrap_custom_pagination(); ?>
	</div>	
	<!-- End Main Content -->	


<?php get_footer(); ?>

