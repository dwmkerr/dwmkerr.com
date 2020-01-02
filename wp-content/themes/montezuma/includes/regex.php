<?php function bfa_regex( $content ) {

	// blank lines and tabs
	$content = preg_replace(
		array( '/\r\n\r\n/i', '/\t/i' ), 
		'', 
		$content
	);

	// </a>...</li> line breaks
	$content = preg_replace(
		'/\<\/a\>\n\<\/li\>/i', 
		'</a></li>', 
		$content
	);
	
	// Relative URLs
	$url = preg_quote( home_url(), '/' );
	$content = preg_replace(
		array( '/ href="' . $url . '/', '/ href=\'' . $url . '/' ),
		array( ' href="', ' href=\'' ),
		$content
	);
	
	// Page menu LI classes
	$content = preg_replace(
		'/\<li class="page_item (.*)"\>\<a href=/i', 
		'<li><a href=', 
		$content
	);

	// Category menu LI classes
	$content = preg_replace(
		'/\<li class="cat-item (.*)"\>\<a href=/i', 
		'<li><a href=', 
		$content
	);

	// Category menu A titles
	$content = preg_replace(
		'/ title="View all posts filed under (.*)"/i', 
		'', 
		$content
	);
	
	// Category links with count, line breaks 
	$content = preg_replace(
		'/\<\/a\> \((.*)\)\n\<\/li\>/i', 
		"</a> (\${1})</li>", 
		$content
	);	

	// Space between articles 
	$content = preg_replace(
		'/\<\/article\>\<article\>/i', 
		"</article>\n\n<article>", 
		$content
	);		
	
	
	return $content;
}
