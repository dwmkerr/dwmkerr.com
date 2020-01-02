<div id="post-<?php the_ID(); ?>" <?php post_class( 'cf' ); ?>>

	<h2>
		<span class="post-format"></span>
		<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a>
		<?php bfa_comments_popup_link( '0', '1', '%' ); ?>
	</h2>

	<?php bfa_thumb( 620, 180, true, '<div class="thumb-shadow"><div class="post-thumb">', '</div></div>' ); ?>
	
	<div class="post-bodycopy cf">
	
		<div class="post-date">		
			<p class="post-month"><?php the_time( 'M' ); ?></p>
			<p class="post-day"><?php the_time( 'j' ); ?></p>
			<p class="post-year"><?php the_time( 'Y' ); ?></p>				
		</div>

		<?php bfa_excerpt( 55, ' ...' ); ?>
		
	</div>

	<div class="post-footer">
		<a class="post-readmore" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
		<?php _e( 'read more &rarr;', 'montezuma' ); ?></a>
		<p class="post-categories"><?php the_category( ' &middot; ' ); ?></p>

		<?php the_tags( '<p class="post-tags">', ' &middot; ', '</p>' ); ?>
	</div>
	
</div>

