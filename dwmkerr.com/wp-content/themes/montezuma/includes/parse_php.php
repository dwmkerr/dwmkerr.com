<?php

function bfa_parse_php_callback( $matches ) {

	$function_name = $matches[1];
	$parameter_string = $matches[2]; 

	$whitelist = bfa_get_whitelist();
	
	/*
	 * Check for "echo " in 'function_name' part and remove it 
	 * echo function_name( ... )
	 */	
	$echo = FALSE;
	if( strpos( $function_name, 'echo ' ) === 0 ) {
		$echo = TRUE;
		// Remove 'echo ' (first 5 letters) from the beginning of the string 
		$function_name = str_replace( substr( $function_name, 0, 5 ), '', $function_name );
	}
	
	
	// Allow only whitelisted functions:
	if( ! in_array( $function_name, array_keys( $whitelist ) ) ) 
		return;
	

	// $need_loop = array( 'bfa_comments_popup_link', 'comments_popup_link', 'the_content' );
// the following line changed by Patch 113-06		
//	$need_loop = array( 'the_content' );
	$need_loop = array( 'the_author',
						'the_author_meta',
						'the_author_post_links',
						'the_content', 
						'the_date', 
						'the_excerpt' 
						 );
	// functions that needs the loop
// the following line changed by Patch 113-02		
//	if (have_posts()) 
	if( !in_the_loop() && in_array( $function_name, $need_loop ) ) {
	/*
		global $query_string;
		$posts = query_posts($query_string); 
	*/
		if ((is_single() OR is_page()) AND have_posts()) 
			the_post(); 	
	}


	// No paramater -> parameter type doesn't matter 
	if( $parameter_string == '' ) {
		
		ob_start(); 
			if( $echo == TRUE ) 				
				echo $function_name();
			else 
				$function_name();
				
			$result = ob_get_contents(); 
		ob_end_clean();				
	
		return $result;
	}	


	/*
	 * Array style parameters: 
	 * function_name(array('this'=>'that','this'=>3,'this'=>true));
	 */
	elseif( $whitelist[$function_name]['type'] == 'array' ) {
	
		$param_array = array();
	
		$parameter_string = str_replace( "\n", " ", $parameter_string );
		$parameter_string = str_replace( "  ", " ", $parameter_string ); // remove double spaces
		
		$parameter_array = str_getcsv( $parameter_string, ',', '\'', '\\' );
		
		foreach( $parameter_array as $parameter ) {
			list( $key, $value ) = explode( '=>', $parameter );
			$param_array[ trim( $key, '\' ' ) ] = trim( $value, '\' ' );
		}

		ob_start(); 
			if( $echo === TRUE ) 
				echo $function_name( $param_array );
			else 
				$function_name( $param_array );
			$result = ob_get_contents(); 
		ob_end_clean();	

		return $result;
	}

	
	/*
	 * URL-query style parameters: 
	 * function_name( 'this=that&this=that&this=that' );
	 */
	elseif( $whitelist[$function_name]['type'] == 'queryarray' ) {
		
		ob_start(); 
			if( $echo === TRUE ) 
				echo $function_name( $parameter_string );
			else 
				$function_name( $parameter_string );
			$result = ob_get_contents(); 
		ob_end_clean();				
	
		return $result;
	}

	
	/* 
	 * PHP function-style parameters: 
	 * function_name( 'param', 'param', '', TRUE, 1, 'param' );
	 */
	elseif( $whitelist[$function_name]['type'] == 'function' ) {

		$parameter_array = str_getcsv( $parameter_string, ',', '\'', '\\' );
			
		$args = array();
		foreach( $parameter_array as $arg ) {
			$thisarg = $arg;
			$args[] = trim( $thisarg, '\'' );
		}
		
		ob_start(); 
			if( $echo === TRUE ) {
				echo call_user_func_array( $function_name, $args );
			} else { 
				call_user_func_array( $function_name, $args );
			}	
			$result = ob_get_contents(); 
		ob_end_clean();	
			
		return $result;
	}		

	
	/*
	 * Single PHP style parameter, or none at all:
	 * function_name();
	 * function_name('param');
	 */
	elseif( $whitelist[$function_name]['type'] == 'single' || $whitelist[$function_name]['type'] == 'function') {	
		ob_start(); 
			if( $echo === TRUE ) 
				echo call_user_func( $function_name, trim( $parameter_string, '\'' ) );
			else 
				call_user_func( $function_name, trim( $parameter_string, '\'' ) );
			$result = ob_get_contents(); 
		ob_end_clean();	
	
	return $result;
	}
	
}
	
	
function bfa_parse_php_string( $matches ) {

	$php_string = $matches[1];
	
	$php_string = str_replace( array( "\r", "\n", "\t" ), "", $php_string );
	
	// Replace translation texts that are paramaters first
	// __('afsfsfs "nnhjj" peter\'s ', 'montezuma')
	// __("afsfsfs \"nnhjj\" peter's ", 'montezuma')
	$php_string = preg_replace_callback(
		'/__\(\s*[\'|"](.*?)[\'|"]\s*,\s*\'montezuma\'\s*\)/',
		create_function(
			'$matches',
			//'return __( stripslashes( $matches[1] ), "montezuma");'
			'return translate( stripslashes( $matches[1] ), "montezuma" );'
		),
		$php_string
	);
	
	// $matches[1] is the (.*) from above. We have a php code string without the 
	// opening and closing PHP tags, and no spaces left/right
	// match 'echo function_name( parameters )' or 'function_name( parameters )'
	//	\s* = 0 or more spaces
	//  (echo [a-z_]+[a-z\d_]+|[a-z_]+[a-z\d_]*) = min 1 character, 'echo func_name' or 'func_name'
	//            'func_name' can start with a-z or _, second character optional, can be a-z, _ or \d = number
	//  \s* = 0 or more spaces
	//  \( = opening bracket ( - literally
	//  \s* = 0 or more spaces
	//  (?:array\s*\()? = ?: = don't capture. ()? = optional
	//              content: an optional 'array' followed by 0 or more spaces and an opening bracket (
	
	
	$result = preg_replace_callback(
		'/\s*(echo [a-zA-Z_]+[a-zA-Z\d_]+|[a-zA-Z_]+[a-zA-Z\d_]*)\s*\(\s*(?:array\s*\()?\s*(.*?)\s*(?:\))?\s*\)\s*/',		
		'bfa_parse_php_callback',
		$php_string
	);
	
	return $result;
}
		
		
		
function bfa_parse_php( $text ) {
	$whitelist = bfa_get_whitelist();
	
	$text = preg_replace_callback(
		'/\<\?php \s*(.*?)\s*(?:;)?\s*\?\>/s', // s = multiline \s* = 0 or more spaces
		'bfa_parse_php_string', 
		$text
	);
	return $text;
}



// parse potentially eval'able code for illegal function calls
function bd_parse($str) {
	
	// allowed functions:
	$allowedCalls = explode(
		',',
		'explode,implode,date,time,round,trunc,rand,ceil,floor,srand,'.
		'strtolower,strtoupper,substr,stristr,strpos,print,print_r'
	);
	
	// check if there are any illegal calls
	$parseErrors = array();
	$tokens = token_get_all($str); 
	$vcall = '';
	
	foreach($tokens as $token) {
		if(is_array($token)) {
			$id = $token[0];
			switch ($id) {
				case(T_VARIABLE): { $vcall .= 'v'; break; }
				case(T_CONSTANT_ENCAPSED_STRING): { $vcall .= 'e'; break; }
				
				case(T_STRING): { $vcall .= 's'; }
				
				case(T_REQUIRE_ONCE): case(T_REQUIRE): case(T_NEW): case(T_RETURN):
				case(T_BREAK): case(T_CATCH): case(T_CLONE): case(T_EXIT):
				case(T_PRINT): case(T_GLOBAL): case(T_ECHO): case(T_INCLUDE_ONCE):
				case(T_INCLUDE): case(T_EVAL): case(T_FUNCTION): case(T_GOTO):
				case(T_USE): case(T_DIR): {
					if (array_search($token[1], $allowedCalls) === false)
						$parseErrors[] = 'illegal call: '.$token[1];
				}
			}
		}
		else $vcall .= $token;
	}
	
	// check for dynamic functions
	if(stristr($vcall, 'v(')!='') $parseErrors[] = array('illegal dynamic function call');
	
	return $parseErrors;
}

/*
Check for safe code by running: if(count(bd_parse($user_code))==0)
*/
