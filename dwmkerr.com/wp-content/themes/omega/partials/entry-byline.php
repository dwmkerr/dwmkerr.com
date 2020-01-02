<div class="entry-meta">
	<?php 
	if (is_multi_author()) {
		echo omega_apply_atomic_shortcode( 'entry_author', __( 'Posted by [post_author_posts_link] ', 'omega' ) ); 
	} else {
		echo omega_apply_atomic_shortcode( 'entry_author', __( 'Posted ', 'omega' ) ); 
	}?>
	<?php
	if (  omega_get_setting( 'trackbacks_posts' ) || omega_get_setting( 'comments_posts' ) ) {
		echo omega_apply_atomic_shortcode( 'entry_byline', __( 'on [post_date] [post_comments] [post_edit before=" | "]', 'omega' ) ); 
	} else {
		echo omega_apply_atomic_shortcode( 'entry_byline', __( 'on [post_date]  [post_edit before=" | "]', 'omega' ) ); 				
	}	
	
	?>
</div><!-- .entry-meta -->