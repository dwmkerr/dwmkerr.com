<?php 

add_action( 'widgets_init', 'bfa_widgets_init' );

/* <span> added inside <h3> as a "hook" for additional styling options for 
the widget headers.  */
function bfa_widgets_init() {
	$widget_areas = bfa_get_widget_areas();
	if( $widget_areas !== FALSE )  {
		foreach( $widget_areas as $name ) {
			register_sidebar( array(
				"name" => $name, 
				"id" => strtolower( str_replace( " ", "", $name ) ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s cf">', 
				'after_widget' => '</aside>', 
				'before_title' => '<h3><span>', 
				'after_title' => '</span></h3>'
			));
		}
	}
}



function bfa_get_widget_areas_OLD() {  // used files, 

	// Exclude files in top level theme directory that are not page templates
	// No harm if we miss one here, just avoiding unnecessary parsing
	$not_page_template = array( 'head', 'header', 'footer', 'searchform', 'comments' );
	
	$widget_areas = array();
	
	foreach( scandir(  get_template_directory() . "/" ) as $file_name ) {
		if( !is_dir( $file_name ) &&  strpos( $file_name, '.php' ) !== FALSE && !in_array( $file_name, $not_page_template ) )  {
		
			$tpl = implode( '', file( get_template_directory() . "/" . $file_name ) );
			
			$these_areas = bfa_get_widget_areas_in_string( $tpl );
			
			if( $these_areas !== FALSE )
				$widget_areas = array_merge( $widget_areas, $these_areas );
		}
	}
	
	if( !empty( $widget_areas ) )
		return array_unique( $widget_areas );
		
	return FALSE;
}



function bfa_get_widget_areas() {

	global $montezuma;
	
	$widget_areas = array();
	
	foreach( $montezuma as $key => $value ) {
		if( strpos( $key, 'maintemplate-' ) === 0 OR strpos( $key, 'subtemplate-' ) === 0 )  {
				
			$these_areas = bfa_get_widget_areas_in_string( $montezuma[$key] );
			
			if( $these_areas !== FALSE )
				$widget_areas = array_merge( $widget_areas, $these_areas );
		}
	}
	
	if( !empty( $widget_areas ) )
		return array_unique( $widget_areas );
		
	return FALSE;
}



function bfa_get_widget_areas_in_string( $tpl ) {

	$result = array();
	
	
	preg_match_all( '/dynamic_sidebar\s*\(\s*(.*?)\s*\)/', $tpl, $matches, PREG_PATTERN_ORDER );
	foreach( $matches[1] as $match ) {
		$result[] = trim( $match, '\'"' ); // Trims single & double quotes from match ("...")
	}
	
	// This one not used currently
	// find widget areas caused by: bfa_loop_single( array( 'widgetarea_after_post7' => 'My new widget area', ..... ) )
	preg_match_all( '/\'\s*widgetarea_after_post(?:[1-9]|10)\s*\'\s*=>\s*\'\s*(.*?)\s*\'/', $tpl, $matches, PREG_PATTERN_ORDER );
	foreach( $matches[1] as $match ) {
		$result[] = trim( $match, '\'"' ); // Trims single & double quotes from match ("...")
	}
	
	if( !empty( $result ) ) 
		return array_unique( $result );
		
	return FALSE;
}


