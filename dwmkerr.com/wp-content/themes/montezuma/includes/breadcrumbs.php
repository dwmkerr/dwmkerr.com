<?php function bfa_breadcrumbs($id = '', $home = '&nbsp;', $sep = '' ) {

	global $post, $wp_query, $author;
	
	$before = '<span class="bc-current">'; 
	$after = '</span>'; 
	

	# if ( is_home() OR is_front_page() )  { $sep = ""; }

	echo '<ol><li class="bc-home"><a href="' . home_url() . '">' . $home . '</a></li>';

	if ( is_category() ) {
		$cat_obj = $wp_query->get_queried_object();
		$thisCat = $cat_obj->term_id;
		$thisCat = get_category($thisCat);
		$parentCat = get_category($thisCat->parent);
		if ($thisCat->parent != 0) 
			echo '<li>' . get_category_parents($parentCat, TRUE, "</li><li>") ;
		else echo '<li>';
		echo $before . single_cat_title("", false) . $after . '</li>';
		
	} elseif ( is_day() ) {
		echo '<li><a href="' . get_year_link(get_the_time("Y")) . '">' . get_the_time("Y") . '</a></li>';
		echo '<li><a href="' . get_month_link(get_the_time("Y"),get_the_time("m")) . '">' . get_the_time("F") . '</a></li>';
		echo '<li>' . $before . get_the_time("d") . $after . '</li>';
		
	} elseif ( is_month() ) {
		echo '<li><a href="' . get_year_link(get_the_time("Y")) . '">' . get_the_time("Y") . '</a></li>';
		echo '<li>' . $before . get_the_time("F") . $after . '</li>';
		
	} elseif ( is_year() ) {
		echo '<li>' . $before . get_the_time("Y") . $after . '</li>';
		
	} elseif ( is_single() AND !is_attachment() ) {
		if ( get_post_type() != 'post' ) {
			$post_type = get_post_type_object(get_post_type());
			$slug = $post_type->rewrite;
			echo '<li><a href="' . home_url() . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a></li>';
			echo '<li>' . $before . the_title('','',false) . $after . '</li>';
		} else {
			$cat = get_the_category(); $cat = $cat[0];
			echo '<li>' . get_category_parents($cat, TRUE, "</li><li>") ;
			echo $before . the_title('','',false) . $after . '</li>';
		}
		
	} elseif ( !is_single() AND !is_page() AND get_post_type() != 'post' ) {
		$post_type = get_post_type_object(get_post_type());
		if( is_object( $post_type ) ) {
			echo '<li>' . $before . $post_type->labels->singular_name . $after . '</li>';
		}
		
	} elseif ( is_attachment() ) {
		$parent = get_post($post->post_parent);
		$cat = get_the_category($parent->ID); $cat = $cat[0];
		echo "<li>" . get_category_parents($cat, TRUE, "</li><li>");
		echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a></li>';
		echo '<li>' . $before . the_title('','',false) . $after . '</li>';
		
	} elseif ( is_page() AND !$post->post_parent ) {
		echo '<li>' . $before . the_title('','',false) . $after . '</li>';
		
	} elseif ( is_page() AND $post->post_parent ) {
		$parent_id  = $post->post_parent;
		$breadcrumbs = array();
		while ($parent_id) {
			$page = get_page($parent_id);
			$breadcrumbs[] = '<li><a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a></li>';
			$parent_id  = $page->post_parent;
		}
		$breadcrumbs = array_reverse($breadcrumbs);
		foreach ($breadcrumbs as $singleCrumb) { 
			echo $singleCrumb;
		}
		echo '<li>' . $before . the_title('','',false) . $after . '</li>';
		
	} elseif ( is_search() ) {
		echo '<li>'. $before . __( 'Search results for: ', 'montezuma' ) . get_search_query() . $after . '</li>';
		
	} elseif ( is_tag() ) {
		echo '<li>' . $before . __( 'Posts tagged: ', 'montezuma' ) . single_tag_title("", false) . $after. '</li>';
		
	} elseif ( is_author() ) {
		$userdata = get_userdata($author);
		echo '<li>' . $before . __( 'Articles posted by: ', 'montezuma' ) . $userdata->display_name . $after . '</li>';
		
	} elseif ( is_404() ) {
		echo '<li>' . $before . __( 'Error 404', 'montezuma' ) . $after . '</li>';
/*		
	} else {
		echo '<li>' . $before . 'Blog ' . $after . '</li>';
	}
*/	
	} elseif ( is_home() ) {
		echo '<li>' . $before . __( 'Blog', 'montezuma' ) . $after . '</li>';
	}
	

	if ( get_query_var('paged') ) {
		echo '<li class="bc-pagenumber">';	
		if ( is_category() OR is_day() OR is_month() OR is_year() OR is_search() OR is_tag() OR is_author() ) 
			echo ' (';
		echo 'Page ' . get_query_var('paged');
		if ( is_category() OR is_day() OR is_month() OR is_year() OR is_search() OR is_tag() OR is_author() ) 
			echo ')';
		echo '</li>';
	}
	

	
	echo '</ol>';
	
	
} 
