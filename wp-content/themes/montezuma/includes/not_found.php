<?php function bfa_not_found() {
?>
	<h2 class="center"><?php _e( 'Not Found', 'montezuma' ); ?></h2>
	<p class="center"><?php _e( 'Sorry, but you are looking for something that is not here.', 'montezuma' ); ?></p>
	<?php get_search_form(); 
}
