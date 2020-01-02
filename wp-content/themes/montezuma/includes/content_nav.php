<?php 

if ( ! function_exists( 'bfa_content_nav' ) ) :

function bfa_content_nav( $id = '' ) {

	global $wp_query; if ( $wp_query->max_num_pages > 1 ) : // Display only if more than 1 page: 
	
	if( $id != '' ) 
		$id = ' id="' . $id . '"'; // a CSS ID can be added 
	?>

	
	<nav class="multinav"<?php echo $id; ?>>
		<?php $big = 999999999; // need an unlikely integer
		echo paginate_links( array(
			'base' => str_replace( $big, '%#%', get_pagenum_link( $big ) ),
			'format' => '?paged=%#%',
			'current' => max( 1, get_query_var('paged') ),
			'total' => $wp_query->max_num_pages,
			'mid_size' => 5
		) ); ?>
	</nav>

	<?php endif;

}

endif;
