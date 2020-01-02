<?php get_header(); ?>

<div id="main" class="row">
	
	<div id="content" class="col12">
		<?php the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class('cf image-attachment'); ?>>

			<h1><?php the_title(); ?></h1>
			
         <p>
				<?php the_time( 'j M Y' ); ?> | 
				<a href="<?php bfa_parent_permalink(); ?>"><?php bfa_parent_title(); ?></a> | 
				<?php bfa_image_size(); ?>
			</p>
					 
			<div class="post-bodycopy cf">
				
				<div class="wp-caption">
					<a href="<?php bfa_attachment_url(); ?>"><?php bfa_attachment_image( 'full' ); ?></a>
					<?php bfa_attachment_caption(); ?>
				</div>

				<nav class="singlenav cf">
					<div class="older"><?php previous_image_link( false ); ?></div>
					<div class="newer"><?php next_image_link( false ); ?></div>
				</nav>				
			
				<div class="entry-description">
					<?php the_content(); ?>
					<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'montezuma' ), 'after' => '</div>' ) ); ?>
				</div>

			</div>

			<?php edit_post_link( __( "Edit", 'montezuma' ) ); ?>
				
			<div class="post-footer">
				<p><?php bfa_image_meta(); ?></p>

			</div>

		</div>
		
		<?php comments_template(); ?>

	</div>
					
</div>

<?php get_footer(); ?>
