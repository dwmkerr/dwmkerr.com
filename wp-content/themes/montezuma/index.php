<?php get_template_part( 'head' ); ?>
</head>
<body <?php body_class(); ?>>
			
	<?php 
	global $montezuma, $wp;
	// - use custom template if set ( for post or page )
	// - or else, according to WP Template Hierarchy
	$tpl = bfa_get_virtual_template();
	echo bfa_parse_php( $montezuma['maintemplate-' . $tpl] );
	?>
	
	<?php wp_footer(); ?>

</body>
</html>
