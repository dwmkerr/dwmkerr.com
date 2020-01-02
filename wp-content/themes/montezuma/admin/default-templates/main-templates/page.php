<?php get_header(); ?>

<div id="main" class="row">
	
	<div id="content" class="col8">
		
		<div id="post-<?php the_ID(); ?>" <?php post_class('cf'); ?>>

			<h1>
				<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a>
				<?php bfa_comments_number(); ?>
			</h1>
			
			<div class="post-bodycopy cf">
				<?php the_content(); ?>
				<?php wp_link_pages( array( 
					'before' => __( '<p class="post-pagination">Pages:', 'montezuma' ), 
					'after' => '</p>'
				) ); ?>
			</div>

			<?php edit_post_link( __( 'Edit', 'montezuma' ) ); ?>

		</div>
		
		<?php comments_template( '', true ); ?>
		
	</div>
	
	<div id="widgetarea-one" class="col4">
		<?php dynamic_sidebar( 'Widget Area ONE' ); ?>
	</div>
		
</div>

<?php get_footer(); ?>