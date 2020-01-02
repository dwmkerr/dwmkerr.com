<?php 
// http://wordpress.stackexchange.com/questions/11578/custom-page-links-for-paginated-posts-wp-link-pages-nextpage-quicktag
// http://wordpress.stackexchange.com/questions/14406/how-to-style-current-page-number-wp-link-pages/14460#14460
/*
 * Usage:
 * <!--nextpage--><!--pagetitle:Title for link to next page/post part-->
 * For first part the post/page title is used if <!--pagetitle:...--> is used at least one time in post/page
 * For each <!--nextpage--> without <!--pagetitle:...--> the number will be displayed as link text, 
 * as a fallback for situations where some <!--nextpage--> have <!--pagetitle:...--> appended and 
 * some have not (probably accidently). Usually this would be used with 
 * <--nextpage--><!--pagetitle:...--> for each page break.
 */

function bfa_link_pages( $args = '' ) {

	$defaults = array(
		'before' => '<p class="post-pagination">' . __('Pages:', 'montezuma'), 
		'after' => '</p>',
		'link_before' => '', 
		'link_after' => '',
		'echo' => 1,
		'pagelink'    => '%',
		'list_type' => 'flat' // flat, ol, ul
	);

	$r = wp_parse_args( $args, $defaults );
	extract( $r, EXTR_SKIP );

	global $page, $numpages, $multipage, $more, $pagenow, $pages;

	$output = '';
	
	if( $multipage ) {
	
		$output .= $before;
		
		if( ! in_array( $list_type, array( 'flat', 'ol', 'ul' )  ) )
			$list_type = 'flat';
			
		if( $list_type != 'flat' ) {
			$output .= "<$list_type>";
			$li_open = '<li>';
			$li_close = '</li>';			
		} else {
			$li_open = '';
			$li_close = '';				
		}
		
		$using_pagetitle = false;
		for( $i = 1; $i < ( $numpages + 1 ); $i = $i + 1 ) {
		
			$part_content = $pages[$i-1];
			$has_part_title = strpos( $part_content, '<!--pagetitle:' );

			if( 0 === $has_part_title ) {
				$using_pagetitle = true;
				$end = strpos( $part_content, '-->' );
				$title = trim( str_replace( '<!--pagetitle:', '', substr( $part_content, 0, $end ) ) );
				$title = isset( $title ) && ( strlen( $title ) > 0 ) ? $title : 'First';			
			} else {
				//$title = str_replace( '%', $i, $pagelink );
				// $title = 'First';
				if( $i == 1 )
					$title = "first-placeholder";
				else 
					$title = str_replace( '%', $i, $pagelink );
			}

			$output .= ' ';
			
			// Is link
			if( $i != $page || ( ! $more && 1 == $page ) ) {
				//$output .= _wp_link_page( $i ) . "{$link_before}{$title}{$link_after}</a>";
				$output .= $li_open . _wp_link_page( $i ) . "{$link_before}{$title}{$link_after}</a>$li_close";
			// Is current page
			} else {   
				//$output .= "<$highlight>{$link_before}{$title}{$link_after}</$highlight>";
				$output .= "$li_open<span>{$link_before}{$title}{$link_after}</span>$li_close";
			}			
		}

		if( $using_pagetitle === true ) 
			$first_link_text = get_the_title();
		else 
			$first_link_text = '1';
			
		$output = str_replace( 'first-placeholder', $first_link_text, $output );
		
		if( $list_type != 'flat' ) 
			$output .= "</$list_type>";
			
		$output .= $after;
	}
	
	if( $echo )
		echo $output;
	return $output;
}


