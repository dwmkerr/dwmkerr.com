<?php 
/* This is not namespaced because it is a fallback for a default PHP function */
if( !function_exists( 'str_getcsv' ) ):
	function str_getcsv( $text ) {

		if( strpos( $text, '=>' ) !== FALSE ) {
			$text = preg_replace( '/\s*,\s*/', '%%co%%', $text );
		} else {
			// temporarily replace commas (with optional space[s] left/right) outside of quotes 
			// http://stackoverflow.com/questions/632475/regex-to-pick-commas-outside-of-quotes
			$text = preg_replace( '/(\s*,\s*)(?=(?:[^\']|\'[^\']*\')*$)/', '%%co%%', $text );
		}

		$result = explode( '%%co%%', $text );

		$final = array();
		foreach( $result as $item ) {
			$final[] = str_replace( array( '%%sq%%', '%%dq%%' ), array( '\\\'', '\\"' ), $item );
		}

		return $final;
	}
	
endif;