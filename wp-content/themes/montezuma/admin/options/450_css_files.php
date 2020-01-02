<?php 

$montezuma = get_option( 'Montezuma' );
/**********************************************

// actual CSS files should have type codemirror.
// Do not add type "codemirror" to non files 
in 400_css_files.php
See admin.php
***********************************************/

$cssfiles = array(
	'title'			=> __( 'CSS', 'montezuma' ),
	'description' 	=> __( 'For referencing background or other images use the following placeholders', 'montezuma' ) . ':
	<ul>
	<li><code>%tpldir%</code> = ' . __( 'Template Directory', 'montezuma' ) . ' = http://www.yourdomain.com/wp-content/themes/Montezuma</li>
	<li><code>%tplupldir%</code> = ' . __( 'Template\'s own folder inside WP Uploads', 'montezuma' ) . ' = http://www.yourdomain.com/wp-content/uploads/Montezuma</li>
	<li><code>%upldir%</code> = ' . __( 'Default WordPress Uploads directory', 'montezuma' ) . ' = http://www.yourdomain.com/wp-content/uploads</li>
	<li><span class="closemirror">' . __( 'Close Mirror', 'montezuma' ) . '</span></li>
	'
);


foreach ( glob( get_template_directory() . "/admin/default-templates/css/*.css") as $filepath) {

	$filename = basename( $filepath );
	$filename = substr( $filename, strpos( $filename, '-') + 1 );
	
	$file_ID = str_replace(
		array( '.css', '-' ),
		array( '', '_' ),
		$filename );
	
	$thisfile = array(
		'id' => 'css_' . $file_ID,
		'type' => 'codemirror',
		'title' => $file_ID .  '<span style="color:#666">.css</span>',
		// 'std' => file_get_contents( $filepath )
		// Alternative to file_get_contents:
		'std' => implode( "", file( $filepath ) ),
		'before' => __( 'For referencing background or other images use the following placeholders', 'montezuma' ) . ':
	<ul>
	<li><code>%tpldir%</code> = ' . __( 'Template Directory', 'montezuma' ) . ' = http://www.yourdomain.com/wp-content/themes/Montezuma</li>
	<li><code>%tplupldir%</code> = ' . __( 'Template\'s own folder inside WP Uploads', 'montezuma' ) . ' = http://www.yourdomain.com/wp-content/uploads/Montezuma</li>
	<li><code>%upldir%</code> = ' . __( 'Default WordPress Uploads directory', 'montezuma' ) . ' = http://www.yourdomain.com/wp-content/uploads</li>
	</ul>
',
		'codemirrormode' => 'css'
	);
	$cssfiles[] = $thisfile;
}


return $cssfiles;