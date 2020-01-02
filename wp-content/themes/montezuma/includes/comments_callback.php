<?php 

function bfa_comments_callback( $comment, $args, $depth ) {
	$GLOBALS["comment"] = $comment; global $montezuma;
	
	switch ( $comment->comment_type ) :
	
		case 'pingback' :
		case 'trackback' :
			echo bfa_parse_php( $montezuma['subtemplate-comments-pingback'] ); 
			break;
		
		default :
			echo bfa_parse_php( $montezuma['subtemplate-comments-comment'] ); 
			break;
		
	endswitch;
}

