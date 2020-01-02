<?php 

function bfa_cat_menu($args){

	$menu = '';
	
	$args['echo'] = false;
	$args['title_li'] = '';


	if( $args['container'] ) {
		$menu = '<'. $args['container'];
						
			if( $args['container_id'] )
				$menu .= ' id="' . $args['container_id'] . '"';

			if( $args['container_class'] )
				$menu .= ' class="' . $args['container_class'] . '"';
				
		$menu .= ">\n";
	}

	$menu .= '<ul id="' . $args['menu_id'] . '" class="' . $args['menu_class'] . '">';

	// add 'home' menu item
	/*
	$menu .= '<li class="home ' . ( ( is_front_page() && !is_paged() ) ? 'current-menu-item' : null ) . 
		'"><a href="'. home_url( '/' ) . '" title="' . __( "Home Page" ) . '">' . 
		$args['link_before'] . __( "Home" ) . $args['link_after'] . '</a></li>';
	*/
	
	/* wp_list_pages (and wp_page_menu) and wp_list_categories use "children" as
	 * class name for children UL's whereas wp_nav_menu uses "sub-menu". 
	 *  
	 */
	$menu .= str_replace( "<ul class='children'>", '<ul class="sub-menu">', wp_list_categories( $args ) );

	$menu .= '</ul>';

	if( $args['container'] ) 
		$menu .= '</' . $args['container'] . ">\n";

	echo $menu;
}

function bfa_page_menu($args){

	$menu = '';
	
	$args['echo'] = false;
	$args['title_li'] = '';

	// If the front page is a page, add it to the exclude list
	if( get_option( 'show_on_front' ) == 'page' ) 
		$args['exclude'] = get_option( 'page_on_front' );

	if( $args['container'] ) {
		$menu = '<'. $args['container'];
						
			if( $args['container_id'] )
				$menu .= ' id="' . $args['container_id'] . '"';

			if( $args['container_class'] )
				$menu .= ' class="' . $args['container_class'] . '"';
				
		$menu .= ">\n";
	}

	$menu .= '<ul id="' . $args['menu_id'] . '" class="' . $args['menu_class'] . '">';

	// add 'home' menu item
	/*
	$menu .= '<li class="home ' . ( ( is_front_page() && !is_paged() ) ? 'current-menu-item' : null ) . 
		'"><a href="'. home_url( '/' ) . '" title="' . __( "Home Page" ) . '">' . 
		$args['link_before'] . __( "Home" ) . $args['link_after'] . '</a></li>';
	*/
	
	$menu .= str_replace( "<ul class='children'>", '<ul class="sub-menu">', wp_list_pages( $args ) );

	$menu .= '</ul>';

	if( $args['container'] ) 
		$menu .= '</' . $args['container'] . ">\n";

	echo $menu;
}

// Category
// category list output has no ancestor class, add with jQuery?
// cat-item cat-item-48 current-cat-parent
// cat-item cat-item-53 current-cat
// ul class='children'


// Page
// page_item page-item-174 current_page_ancestor
// page_item page-item-501 current_page_ancestor
// page_item page-item-21 current_page_ancestor current_page_parent
// page_item page-item-2 current_page_item
// ul class='children'



// current_page_item, current_page_parent, current_page_ancestor


// current-menu-item, current-menu-parent, current-menu-ancestor
// children, sub-menu




function bfa_simplify_wp_list_categories($output) {

	$output = preg_replace_callback(
		'/class="cat-item cat-item-(\d+)( current-cat)?(-parent)?"/',
		create_function(
			'$matches',
			'if( isset($matches[2]) && isset($matches[3]) ) $extra = " parent";
			elseif( isset($matches[2]) ) $extra = " active";
			else $extra = "";
			$cat = &get_category( $matches[1] ); return "class=\"cat-" . $cat->slug . $extra . "\"";'
		),
		$output
	);

// the following line commented out by Patch 113-02		
//	$output = preg_replace('/ title="(.+)"/', '', $output);
	return $output;
	
}
add_filter('wp_list_categories', 'bfa_simplify_wp_list_categories');
add_filter('the_category', 'bfa_simplify_wp_list_categories');



function bfa_simplify_wp_nav_menu( $classes, $item ) {
	
	$item_type = 'item';
	$new_classes = array();

	foreach( $classes as $class ) {
	
		if( $class == 'menu-item-object-category' ) 
			$item_type = 'cat';
		elseif( $class == 'menu-item-object-page' ) 
			$item_type = 'page';
			
		elseif( $class == 'current-menu-item' ) 
			$new_classes[] = 'active';
		elseif( $class == 'current-menu-parent' ) 
			$new_classes[] = 'parent';
		elseif( $class == 'current-menu-ancestor' ) 
			$new_classes[] = 'ancestor';

	}
	
	// static homepage returns '' with basename( get_permalink( $item->object_id ) ) from below
	if( trailingslashit( get_permalink( $item->object_id ) ) == trailingslashit( home_url() ) 
			&& get_option( 'show_on_front' ) == 'page' ) { 
			
		$homepage_id = get_option( 'page_on_front' );
		$thispage = get_post( $homepage_id ); 
		$slug = $thispage->post_name;
		$new_classes[] = $item_type . '-' . $slug;
		
	} else {
	
		// category
		if( $item_type == 'cat' ) 
			$slug = esc_attr( basename( get_category_link( $item->object_id ) ) );
		// page or custom link
		else 
			$slug = esc_attr( basename( get_permalink( $item->object_id ) ) );
		
		$new_classes[] = $item_type . '-' . $slug;
		
	}
	
	// adjust active menu classes to match the ones added by wp_nav_menu()
	foreach( $classes as $class ) {

	}
	return $new_classes;
	
}
add_filter( 'nav_menu_css_class', 'bfa_simplify_wp_nav_menu', 100, 2 );


function bfa_strip_wp_nav_menu_ids( $menu ) {
    $menu = preg_replace( '/\<li id="(.*?)"/','<li', $menu );
    return $menu;
}
add_filter ( 'wp_nav_menu', 'bfa_strip_wp_nav_menu_ids' );




function bfa_simplify_wp_list_pages( $classes, $page ) {

	$new_classes = array( 'page-' . $page->post_name );
	
	// adjust active menu classes to match the ones added by wp_nav_menu()
	foreach( $classes as $class ) {
		if( $class == 'current_page_item' ) 
			$new_classes[] = 'active';
		elseif( $class == 'current_page_parent' ) 
			$new_classes[] = 'parent';
		elseif( $class == 'current_page_ancestor' ) 
			$new_classes[] = 'ancestor';
	}
	
	return $new_classes;
}
add_filter( 'page_css_class', 'bfa_simplify_wp_list_pages', 100, 2 );



