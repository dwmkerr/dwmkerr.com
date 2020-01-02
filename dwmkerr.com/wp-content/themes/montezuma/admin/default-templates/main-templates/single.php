<?php get_header(); ?>

<div id="main" class="row">

	<div id="content" class="col8">

		<nav class="singlenav cf">
			<div class="older"><?php previous_post_link(); ?></div>
			<div class="newer"><?php next_post_link(); ?></div>
		</nav>
		
		<div id="post-<?php the_ID(); ?>" <?php post_class( 'cf' ); ?>>

			<h1>
				<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a>
				<?php bfa_comments_number(); ?>
			</h1>

			<div class="post-footer">
				<?php the_time( 'j M Y' ); ?> &nbsp;&nbsp;| <?php the_category( ' &middot; ' ); ?>
				<?php the_tags( __( '<p class="post-tags">Tags: ', 'montezuma' ), ' &middot; ', '</p>' ); ?>
			</div>

			<div class="post-bodycopy cf">
				<?php the_content(); ?>
				<?php wp_link_pages( array( 
					'before' => __( '<p class="post-pagination">Pages:', 'montezuma' ), 
					'after' => '</p>'
				) ); ?>
			</div>

			<?php edit_post_link( __( "Edit", 'montezuma' ) ); ?>
			


		</div>

		<?php comments_template( '', true ); ?>

		<nav class="singlenav cf">
			<div class="older"><?php previous_post_link(); ?></div>
			<div class="newer"><?php next_post_link(); ?></div>
		</nav>
		
	</div>
	
	<div id="widgetarea-one" class="col4">
		<?php dynamic_sidebar( 'Widget Area ONE' ); ?>
	</div>
	
</div>

<?php get_footer(); ?>