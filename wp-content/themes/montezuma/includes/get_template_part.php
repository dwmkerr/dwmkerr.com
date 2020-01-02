<?php 

// Get virtual template from DB if no physical template exists 
function bfa_get_template_part( $slug, $name ) {

	// Use physical file in child theme if it exists, e.g. "/page.php"
	if( file_exists( trailingslashit( get_stylesheet_directory() ) . "{$slug}.php" ) OR 
		file_exists( trailingslashit( get_stylesheet_directory() ) . "{$slug}-{$name}.php" ) ) {

		get_template_part( $slug, $name );
	
	// else use virtual template:
	} else {
	
		global $montezuma;

		if( isset( $montezuma['subtemplate-' . $slug . '-' . $name] ) ) 
			echo bfa_parse_php( $montezuma['subtemplate-' . $slug . '-' . $name] );
		elseif( isset( $montezuma['subtemplate-' . $slug] ) ) 
			echo bfa_parse_php( $montezuma['subtemplate-' . $slug] );
		
// the following two lines added as part of PATCH 113-07
		elseif( isset( $montezuma['subtemplate-' . $name] ) ) 	
			echo bfa_parse_php( $montezuma['subtemplate-' . $name] );
		
	}
	
	return;

}
