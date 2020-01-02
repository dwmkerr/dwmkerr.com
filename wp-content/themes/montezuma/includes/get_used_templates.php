<?php function bfa_get_used_templates( $string ) {

	$montezuma = get_option( 'montezuma' );

	if( $montezuma !== FALSE ) {
		$used_templates = array();
		$used_templates_dropdown = array();
		$used_subtemplates = array();
		$used_subtemplates_dropdown = array();
		$all_templates = array();
		
		$valid_default_templates = array(
			'standard' => array( 
				'404', 
				'application', 
				'archive',
				'attachment', 
				'audio', 
				'author', 
				'category', 
				'date', 
				'front-page', 
				'home', 
				'image', 
				'index', 
				'message', 
				'model', 
				'multipart', 
				'page', 
				'paged',
				'search', 
				'single', 
				'tag', 
				'taxonomy', 
				'text', 
				'video', 
				'vnd', 
				'x',
				'x-ckps',
			),
			'with-parameter' => array(
				'archive-XXX', 
				'author-XXX', 
				'category-XXX', 
				'page-XXX',  
				'single-XXX', 
				'tag-XXX', 
				'taxonomy-XXX', 
			)

		);

		$unused_default_templates = $valid_default_templates['standard'];


		foreach( $montezuma as $key => $value ) {
			
			// Option is a main template
			if( strpos( $key, 'maintemplate-' ) === 0 ) {

				// substr: strip off 'maintemplate-'
				$tpl_name = str_replace( substr( $key, 0, 13 ), '', $key );
			
				// Add to used templates
				$used_templates[] = $tpl_name;
				$used_templates_dropdown[] = '<option value="' . $tpl_name . '">' . $tpl_name . '</option>';
				
				// Remove from unused templates
				$unused_default_templates = array_merge( array_diff( $unused_default_templates, array( $tpl_name ) ) );
				
				$all_templates[] = $tpl_name;
			
			// Option is a sub template
			} elseif ( strpos( $key, 'subtemplate-' ) === 0 ) {

				// substr: strip off 'subtemplate-'
				$tpl_name = str_replace( substr( $key, 0, 12 ), '', $key );
				
				// Add to used templates
				$used_subtemplates[] = $tpl_name;
				$used_subtemplates_dropdown[] = '<option value="' . $tpl_name . '">' . $tpl_name . '</option>';
			
				$all_templates[] = $tpl_name;	
			}
		}
		
		sort( $all_templates );
		

		switch( $string ) :

			case 'used_templates': 
				$return = '<li>' . implode( '</li><li>', $used_templates ) . '</li>'; break;
			case 'used_templates_dropdown': 
				$return = implode( '', $used_templates_dropdown ); break;
			case 'used_subtemplates': 
				$return = '<li>' . implode( '</li><li>', $used_subtemplates ) . '</li>'; break;
			case 'used_subtemplates_dropdown': 
				$return = implode( '', $used_subtemplates_dropdown ); break;
			case 'unused_default_templates': 
				$return = '<li>' . implode( '</li><li>', $unused_default_templates ) . '</li>'; break;
			case 'unused_default_templates_parameter': 
				$return = '<li>' . implode( '</li><li>', $valid_default_templates['with-parameter'] ) . '</li>'; break;
			case 'all_templates': 
				$return = '<li>' . implode( '</li><li>', $all_templates ) . '</li>'; break;
		
		endswitch;
		
		return $return;
	} 

}
