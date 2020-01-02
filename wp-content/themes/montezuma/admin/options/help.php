<?php 

$whitelist = bfa_get_whitelist();

$whitelist_string = '';

// First item:
$full_whitelist_array = array(
'sidebar'	=>	'
<button style="cursor:pointer;margin-top:20px;" id="slideUpHelpTag">' . __( 'Close This', 'montezuma' ) . '</button>' . 
__( '<h3>Limited Set of PHP code</h3>
<p>
This is a list of 60+ PHP functions that you can use in Montezuma\'s main- and sub templates. 
Simply copy a function here (with <code>Ctrl+c</code> on Windows or <code>Cmd+c</code> on Mac) 
and paste it (with <code>Ctrl+v</code> on Windows or <code>Cmd+v</code> on Mac) into a template at 
Montezuma > Main Templates or Montezuma > Sub Templates. 
Then save the template and reload a front end page of your site to see the result.
</p>
<p>
Note: This is a <strong>limited</strong> set of PHP functions. You <strong>cannot</strong> use 
arbitrary PHP code. You can only use the functions that are listed here. You can also not use 
PHP conditions or loops etc... Just what you see listed here.
</p>
<p>If you need full PHP access you would have to create actual "physical" template files 
in a text editor on your desktop computer. Those physical template files you\'d either put 
into the Montezuma theme directory, or you create a child theme and put the files there.
</p>
<p>
In physical template files you can use the functions listed here "as is", that means you can just copy 
a main or sub template\'s content from a textarea here in the Montezuma admin and paste it into 
the physical template on your desktop computer.
</p>', 'montezuma' ),
);


foreach( $whitelist as $func => $info ) {
	$infotext = str_replace( array( '<code>', '</code>' ), array ( 'xxxcode', 'yyycode' ), $info['info'] );
	$infotext = htmlspecialchars( $infotext );
	$infotext = str_replace( array ( 'xxxcode', 'yyycode' ), array( '<code>', '</code>' ), $infotext );
	$this_string = '<h2>' . $func . '</h2>';
	foreach( $info['examples'] as $phpcode => $codeinfo ) {
		$codeinfotext = str_replace( array( '<code>', '</code>' ), array ( 'xxxcode', 'yyycode' ), $codeinfo );
		$codeinfotext = htmlspecialchars( $codeinfotext );
		$codeinfotext = str_replace( array ( 'xxxcode', 'yyycode' ), array( '<code>', '</code>' ), $codeinfotext );
		
		$phpcode_raw = $phpcode;

		$phpcode = htmlspecialchars( $phpcode );
		
		$phpcode = str_replace( array( '\\\'', '\\"' ), array( '%escsingle%', '%escdouble%' ), $phpcode );
		
		// temporarily replace commas (with optional space[s] left/right) outside of quotes 
		// http://stackoverflow.com/questions/632475/regex-to-pick-commas-outside-of-quotes
		$phpcode = preg_replace( '/(\s*,\s*)(?=(?:[^\']|\'[^\']*\')*$)|(\s*,\s*)(?=(?:[^"]|"[^"]*")*$)/', '%%co%%', $phpcode );
		
		$phpcodearray = explode( '%%co%%', $phpcode );
		
		$phpcodearray2 = array();
		foreach( $phpcodearray as $phpcodearray_part ) {
			$phpcodearray2[] = preg_replace( 
				// Todo: THis doesn't grab double quotes
				'/([\'|"])(.*?)([\'|"])/', 
				'<span style="color:red">$1</span><span style="color:green">$2</span><span style="color:red">$3</span>', 
				$phpcodearray_part 
			);
		}
		$phpcode = implode( '%%co%%', $phpcodearray2 );
		
		$phpcode = str_replace( array( '%escsingle%', '%escdouble%', '%%co%%' ), array( '\\\'', '\\"', ', ' ), $phpcode );		



		$phpcode = str_replace( 
			array( '&lt;?php', '?&gt;', '(', ')' ), 
			array( '<span style="color:black">&lt;?php</span>', 
					'<span style="color:black">?&gt;</span>',
					'<span style="color:purple">(</span>',
					'<span style="color:purple">)</span>',
				), 
			$phpcode 
		);
		
		
		$this_string .= 
			'<div class="codeContainer">
			<code class="codehighlight">' . $phpcode . '</code>
			<textarea style="display:none" class="copyMe">' . htmlspecialchars( $phpcode_raw ) . '</textarea><br style="clear:both">'
			. $codeinfotext . '</div>';
	}
	$this_string .= '<p>' . $infotext . '</p>';
	
	$whitelist_array[] = array(
		'title' => $func,
		'content' => $this_string,
	);
	
	$whitelist_string .= $this_string;
	
}

$full_whitelist_array[] = 
array(
	'title' => __( 'PHP Code - Full List', 'montezuma' ),
	'content' => $whitelist_string,
);

foreach( $whitelist_array as $list_item )
	$full_whitelist_array[] = $list_item;



return $full_whitelist_array;
