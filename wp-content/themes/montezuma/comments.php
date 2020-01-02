<?php 
global $montezuma;

if ( post_password_required() ) : 
	echo bfa_parse_php( $montezuma['subtemplate-comments-password'] ); 
	return; 
endif; 

if ( have_comments() OR comments_open() ) : 
?>
<div id="comments">
<?php 

	if ( have_comments() ) 
		echo bfa_parse_php( $montezuma['subtemplate-comments-list'] ); 

	if ( ! comments_open() ) {
		echo bfa_parse_php( $montezuma['subtemplate-comments-closed'] ); 
		
	} else { 
		echo bfa_parse_php( $montezuma['subtemplate-comments-form'] ); 
	}
	
?>
</div><!-- #comments -->
<?php 
endif; 

