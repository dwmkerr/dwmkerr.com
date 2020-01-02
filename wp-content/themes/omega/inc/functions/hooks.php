<?php

if ( omega_get_setting( 'more_link_scroll' )) {
	add_filter( 'the_content_more_link', 'omega_remove_more_link_scroll' );
}

function omega_remove_more_link_scroll( $link ) {
	$link = preg_replace( '|#more-[0-9]+|', '', $link );
	return $link;
}