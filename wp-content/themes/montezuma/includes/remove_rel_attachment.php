<?php 

/* For existing posts */
add_filter( 'the_content', 'bfa_remove_rel_attachment', 12 );
/* For new posts */
add_filter( 'wp_get_attachment_link' , 'bfa_remove_rel_attachment' );

function bfa_remove_rel_attachment( $content ) {  

	// Remove W3C invalid 'rel="attachment..."'
	if( strpos( $content, 'rel="attachment' ) !== FALSE ) 
		$content = preg_replace( '/ rel="attachment(.*?)"/', '', $content );
		
	return $content;
}



