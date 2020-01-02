<?php
/**

 */
class ThemeOptions {

	protected $wp_scripts = array();
	protected $custom_scripts = array();
	protected $wp_styles = array();
	protected $custom_styles = array();
	protected $codemirrors = array();
	protected $menu = array();
	public $cur_plup_dir;

	public function __construct( $title, $ID, $path = '', $dir = '' ) {
			
			$this->title = $title;
			$this->ID = $ID;
			$this->path = ( $path == '' ? dirname(__FILE__) . '/options' : $path ); 
			$this->dir = ( $dir == '' ? 'admin' : $dir ); 
			$this->config = $this->get_option_config();
			$this->types = $this->get_option_types();
			$this->config_flat = $this->get_option_config_flat();
			$this->default_settings = $this->get_default_settings();
			$this->help_exists = file_exists( $this->path . '/help.php' );

			add_action( 'admin_init', array( &$this, 'register_admin' ) );
			add_action( 'admin_menu', array( &$this, 'add_admin' ) );	

			if ( !get_option( $this->ID ) ) {
				// If no settings exist, set default settings:
				update_option( $this->ID, $this->default_settings );
				
			} else {
				// Saved settings exist. array_merge ( saved settings, default settings )
				update_option( $this->ID,  array_merge( (array) $this->default_settings, (array) get_option( $this->ID ) ) );
			}
			
			$this->saved_settings = get_option( $this->ID );
			
			// handle file upload
			add_action( 'wp_ajax_bfa_plup_ajax', array( &$this, 'handle_upload' ) );	
			
			// add_action ( 'wp_ajax_' + [name of "action" in jQuery.ajax, see functions/bfa_css_admin_head.php], [name of function])
			add_action( 'wp_ajax_delete_file', array( &$this, 'delete_file' ) );
			
			// unused, use again later
			add_action( 'wp_ajax_save_php_files_ajax', array( &$this, 'save_php_files_ajax' ) );
			
			// action and function name same for simplicity: 'bfa_add_item_ajax'
			add_action( 'wp_ajax_bfa_add_item', array( &$this, 'add_item_ajax' ) );
			add_action( 'wp_ajax_bfa_delete_item', array( &$this, 'delete_item_ajax' ) );
			add_action( 'wp_ajax_bfa_import_settings', array( &$this, 'import_settings_ajax' ) );
			add_action( 'wp_ajax_bfa_reset_settings', array( &$this, 'reset_settings_ajax' ) );
			add_action( 'wp_ajax_bfa_reset_single', array( &$this, 'reset_single_ajax' ) );
	}

		

	public function handle_upload() {
	
		check_ajax_referer('bfa_plupload');
		$file = $_FILES['file-data'];
		
		// Change upload_subdir to submitted upload_subdir of this uploader:
		$this->cur_plup_dir = $_POST['upload_subdir'];
		add_filter('upload_dir', array( &$this,'img_upload_dir') ); // Temporarily change WP upload directory
		$updir_now = wp_upload_dir();
		$status = wp_handle_upload($file, array('test_form'=>true, 'action' => 'bfa_plup_ajax')); // Upload...
		remove_filter('upload_dir', array( &$this,'img_upload_dir') ); // Change upload directory back to default
		$size = getimagesize( $status['file'] );

		$reply = array(
				'path' => $status['file'],
				'url' => $status['url'],
				'width' => $size[0],
				'height' => $size[1]
			);
		
		if( $_POST['thumbcheck'] == 1 && isset( $_POST['thumbwidth'] ) && isset( $_POST['thumbheight'] ) ) {
			$cropbool = FALSE;
			
			if( $_POST['thumbcrop'] == 1 ) 
				$cropbool = TRUE;

			$thumb = image_make_intermediate_size( $status['file'], $_POST['thumbwidth'], $_POST['thumbheight'], $cropbool ); 
			
			$reply['thumb'] = $updir_now['url'] . '/' . $thumb['file'];
			$reply['tpath'] = $updir_now['path'] . '/' . $thumb['file'];
			$reply['twidth'] = $thumb['width'];
			$reply['theight'] = $thumb['height'];	
		}
		echo json_encode( $reply );
		exit;
	}
		

	public function img_upload_dir( $upload ) {
		$upload['path'] = $upload['basedir'] . '/' . $this->ID . '/' . $this->cur_plup_dir;
		$upload['url'] = $upload['baseurl'] . '/' . $this->ID . '/'. $this->cur_plup_dir;
		return $upload;
	}

	
	public function base_upload_dir( $upload ) {
		$upload['path'] = $upload['basedir'] . '/' . $this->ID;
		$upload['url'] = $upload['baseurl'] . '/' . $this->ID;	
		return $upload;
	}


	public function delete_file() {
		check_ajax_referer( "bfa_plupload" );
		if( isset( $_POST['path'] ) )
			unlink( $_POST['path'] );
		if( isset( $_POST['tpath'] ) )
			unlink( $_POST['tpath'] );
			
		die();
	}


	public function compress( $string ) {
		// remove comments 
		$string = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $string );
		/*
		$string = str_replace( array( "\r\n", "\n\r", "\r", "\n", "\t" ), '', $string ); // remove tabs and newlines
		*/
      return $string;
	}	
	

	public function get_css() {

		$css = implode( '', file( get_template_directory() . 
								'/admin/default-templates/css/grids/' . 
								$this->saved_settings['choose_css_grid'] . '.css' ) );

		foreach ( $this->config['css_files']['fields'] as $field ) 
			$css .= $this->saved_settings[$field['id']];
			
		/* First get the default array of wp_upload_dir() for 3rd option wp-content/uploads
		 * for replacement of image URLs in CSS file
		 */
		$updir = wp_upload_dir(); // Array - $updir['baseurl'] = http://www.mydomain.com/wp-content/uploads

		/* In the default CSS only $tpldir% is used so it shouldn't matter if wp-content/uploads exists */
		$css = str_replace(
			array( '%tpldir%', '%tplupldir%' , '%upldir%' ),
			array( get_template_directory_uri(), $updir['baseurl'] . '/' . $this->ID, $updir['baseurl'] ),
			$css
		);
		return $css;
	}	

	
	
	public function get_used_colors() {
		$css = $this->get_css();
		
		preg_match_all (
			'/(#[A-Fa-f0-9]{3,6})/',
			$css,
			$matches
		);
		
		$final_colors = array();
		
		foreach( $matches[0] as $color ) {
			if( strlen( $color ) == 4 ) {
				$final_color = preg_replace( 
					'/#([\dA-Fa-f])([\dA-Fa-f])([\dA-Fa-f])/',
					'#$1$1$2$2$3$3',
					$color
				);
			} else {
				$final_color = $color;
			}
			$final_colors[] = $final_color;
		}
		$return = array_unique( $final_colors );
		rsort( $return );
		return $return;
	}
	
	
	public function save_css_file() {

		$css = $this->get_css();
				
		/* Temporarily change default WP upload dir, to be able to
		 * use wp_upload_bits (instead of WP_filesystem) for the upload and not have it upload to /2012/02/ etc
		 * as it usually would 
		*/
		add_filter( 'upload_dir', array( &$this, 'base_upload_dir' ) );
		$updir_now = wp_upload_dir(); // http://www.mydomain.com/wp-content/uploads/themename

		// delete existing style.css (wp_upload_bits appends number, does not overwrite existing files)
		$css_path = $updir_now['path'] . '/style.css';
		if( file_exists( $css_path ) ) 
			unlink( $css_path );

		$css = $this->compress( $css ); // Compress
		wp_upload_bits( 'style.css', null, $css ); // Save
		remove_filter( 'upload_dir', array( &$this, 'base_upload_dir' ) ); // Change upload_dir back to WP default
	}		

	

	public function save_javascript_file() {

		$js = bfa_get_javascript();
		
		/* Temporarily change default WP upload dir, to be able to
		 * use wp_upload_bits (instead of WP_filesystem) for the upload and not have it upload to /2012/02/ etc
		 * as it usually would 
		*/
		add_filter( 'upload_dir', array( &$this, 'base_upload_dir' ) );
		$updir_now = wp_upload_dir();

		// delete existing style.css (wp_upload_bits appends number, does not overwrite existing files)
		$js_path = $updir_now['path'] . '/javascript.js';
		if( file_exists( $js_path ) ) 
			unlink( $js_path );

		// Compress
		#$js = $this->compress( $js );
			
		// Save
		wp_upload_bits( 'javascript.js', null, $js );
		
		// Change upload_dir back to WP default
		remove_filter( 'upload_dir', array( &$this, 'base_upload_dir' ) );
	}		
	

	public function import_settings_ajax() {
		
		check_ajax_referer('bfa_import_settings');

		// settings was encoded with encodeURIComponent 
		//$import_options = rawurldecode( $_POST['settings'] );
		$import_options = stripslashes( $_POST['settings'] );
		
		// Very basic check for option name 'favicon_url' to avoid glaring mistakes
		if ( json_decode( $import_options ) != NULL AND strpos( $import_options, 'favicon_url' ) !== FALSE ) {
			update_option( $this->ID, json_decode( $import_options, TRUE ) );
			$answer = "<span style='color:green'>" . __( 'Successfully imported new settings! Reloading now...', 'montezuma' ) . "</span>";		
		
		// Probably not a valid settings file:
		} else {
			$answer = "<span style='color:red'>" . sprintf( __( 'Sorry, but this does not appear to be a valid %1$s Settings File.', 'montezuma' ), $this->title ) . "</span>";
		}	
		echo $answer;
		die();	
	}

	
	public function reset_settings_ajax() {
		
		check_ajax_referer('bfa_reset_settings');

		/* comparing old and new setting because WP will return false for update_option if both 
		are the same even though the update action was successful */
		
		$data = $this->default_settings;
		
		if( !is_array( get_option( $this->ID ) ) ) 
			$options = array();
		else 
			$options = get_option( $this->ID );

		if( !empty( $data ) ) {
			$diff = array_diff( $options, $data );
			$diff2 = array_diff( $data, $options );
			$diff = array_merge( $diff, $diff2 );
		} else {
			$diff = array();
		}
			
		if( !empty( $diff ) ) {	
			if( update_option( $this->ID, $data ) ) 
				$answer = "<span style='color:green'>" . __( 'Settings were reset! Reloading now...', 'montezuma' ) . "</span>";
			else 
				$answer = "<span style='color:red'>" . __( 'Sorry, could not reset settings.', 'montezuma' ) . "</span>";
		} else {
			$answer = "<span style='color:green'>" . __( 'Settings were reset, however new and old settings were the same! Reloading now...', 'montezuma' ) . "</span>";	
		}
		echo $answer;
		die();
	}
	
	
	public function reset_single_ajax() {
		
		check_ajax_referer('bfa_reset_single');

		/* comparing old and new setting because WP will return false for update_option if both 
		are the same even though the update action was successful */
		$data = $this->default_settings;
		
		$id_to_be_reset = $_POST['id_to_be_reset'];
		
		$default_value_of_id = $data[$id_to_be_reset];
		
		if( !is_array( get_option( $this->ID ) ) ) 
			$options = array();
		else 
			$options = get_option( $this->ID );
		
		$new_options = $options;
		$new_options[$id_to_be_reset] = $default_value_of_id;

		if( !empty( $new_options ) ) {
			$diff = array_diff( $options, $new_options );
			$diff2 = array_diff( $new_options, $options );
			$diff = array_merge( $diff, $diff2 );
		} else {
			$diff = array();
		}
			
		if( !empty( $diff ) ) {	
			if( update_option( $this->ID, $new_options ) ) 
				$answer = "<span style='color:green'>" . __( 'Setting was reset! Reloading now...', 'montezuma' ) . "</span>";
			else 
				$answer = "<span style='color:red'>" . __( 'Sorry, could not reset setting.', 'montezuma' ) . "</span>";
		} else {
			$answer = "<span style='color:green'>" . __( 'Setting was reset, but it was the default value anyway! Reloading now...', 'montezuma' ) . "</span>";	
		}
		echo $answer;
		die();
	}
	
	
	public function add_item_ajax() {
		
		$settings = $this->saved_settings;
		check_ajax_referer('bfa_add_item');
		$item_name = $_POST['item_name'];		
		$item_type = $_POST['item_type'];
		
		// Since 1.1.2: hardcode subtemplate- and maintemplate-
		if( 	!isset( $settings[ $item_type . '-' . $item_name ] ) 
				&& !isset( $settings[ 'maintemplate-' . $item_name ] ) 
				&& !isset($settings[ 'subtemplate-' . $item_name ] ) 
				&& $item_name != 'comments' 
			) {
		// if( ! isset( $settings[ $item_type . '-' . $item_name ] ) ) {
			$new_item_content = '';
			if( isset( $_POST['copy_of'] ) && $_POST['copy_of'] != 'startblank' && $_POST['copy_of'] != '' && isset( $settings[ $item_type . '-' . $_POST['copy_of'] ] ) )
				$new_item_content = $settings[ $item_type . '-' . $_POST['copy_of'] ];
			
			$settings[ $item_type . '-' . $item_name ] = $new_item_content;
			update_option( $this->ID, $settings );
			$answer = "<span style='color:green'>" . sprintf( __( 'Successfully added new %1$s item %2$s...', 'montezuma' ), $item_type, $item_name ) . "</span>";
		} else {
			$answer = "<span style='color:red'>" . sprintf( __( 'Item named "%1$s" already exists!', 'montezuma' ), $item_name ) . "</span>";
		}
		echo $answer;
		die();		
	}

	
	public function delete_item_ajax() {
		
		$settings = $this->saved_settings;
		check_ajax_referer('bfa_delete_item');
		$item_to_be_deleted = $_POST['item_to_be_deleted'];
		$cannot_delete = FALSE;
		$item_exists = FALSE;
		
		// Make sure some default templates aren't deleted
		if( in_array( $item_to_be_deleted, 
			array( 
				'maintemplate-index', 
				'maintemplate-single', 
				'maintemplate-page' ,
				'subtemplate-header', 
				'subtemplate-footer', 
				'subtemplate-postformat', 
				'subtemplate-comments', 
				'subtemplate-comments-closed', 
				'subtemplate-comments-comment', 
				'subtemplate-comments-form', 
				'subtemplate-comments-list',  
				'subtemplate-comments-password',  
				'subtemplate-comments-pingback',
				// add more...	
			) ) ) {
			$cannot_delete = TRUE;
		}

		if( isset( $settings[ $item_to_be_deleted ] ) )
			$item_exists = TRUE;
		
		if( $cannot_delete === FALSE && $item_exists === TRUE ) {
			unset( $settings[ $item_to_be_deleted ] );
			update_option( $this->ID, $settings );
			$answer = "<span style='color:green'>" . sprintf( __( 'Successfully deleted <code>%1$s.php</code>! Reloading now...', 'montezuma' ), $item_to_be_deleted ) . "</span>";
		} else {
			$answer = "<span style='color:red'>" . sprintf( __( 'Could not delete <code>%1$s.php</code> because:', 'montezuma' ), $item_to_be_deleted );
			if( $item_exists === FALSE ) 
				$answer .= ' ' . __( 'It does not exist.', 'montezuma' );
			if( $cannot_delete === TRUE ) 
				$answer .= ' ' . __( 'It is an item that cannot be deleted.', 'montezuma' );
			$answer .= ' ' . __( 'Reloading now...', 'montezuma' ) . '</span>';
		}
		echo $answer;
		die();		
	}


	public function save_php_files_ajax() {
		
		check_ajax_referer('bfa_php_files');
		$file_group = $_POST['file_group'];
		$answer = $this->save_php_files( $file_group );
		
		echo $answer;
		die();	
	}


	public function save_php_files( $file_group ) {
		
		$result = '';
		
		foreach ( $this->config[$file_group]['fields'] as $field ) {
			$file_content = $this->saved_settings[$field['id']];
			$file_name = $field['id'];
			$result .= $this->save_php_file( $file_name, $file_content, $file_group ) . '<br>';
		}
		return $result;
	}
		


	public function save_php_file( $file_name, $file_content, $file_group ) {

		$updir = wp_upload_dir(); // Array - $updir['baseurl'] = http://www.mydomain.com/wp-content/uploads

		// Temporarily allow .php uploads
		#add_filter( 'upload_mimes', array( &$this, 'add_php_to_upload_mimes'), 1, 1 );
		add_filter( 'upload_mimes', 'add_php_to_upload_mimes', 1, 1 );
		
		// Temporarily change default WP upload dir
		add_filter( 'upload_dir', array( &$this, 'base_upload_dir' ) );
		$updir_now = wp_upload_dir(); 

		// delete existing file (wp_upload_bits appends number, does not overwrite existing files)
		$file_path = trailingslashit( $updir_now['path'] ) . $file_name . '.php';
		if( file_exists( $file_path ) ) 
			unlink( $file_path );

		// Save: $result['file'], $result['url'], $result['error']
		$result = wp_upload_bits( $file_name . '.php', null, $file_content );
		
		if( isset( $result['file'] ) ) {
		
			// Change upload_dir back to WP default
			remove_filter( 'upload_dir', array( &$this, 'base_upload_dir' ) );
			// Remove .php again from allowed mime types 
			remove_filter( 'upload_mimes', 'add_php_to_upload_mimes' );
		
			// Get some unique file info
			$filetime = filemtime( $result['file'] );
			$filesize = filesize( $result['file'] );
			$md5 = md5_file( $result['file'] );
			
			if( get_option( $this->ID . 'filecheck' ) )
				$options = get_option( $this->ID . 'filecheck' );
			else
				$options = array();
			
			// Store file info in db
			if( ! isset( $options['files'] ) ) 
				$options['files'] = array();
			if( ! isset( $options['files'][$file_group] ) ) 
				$options['files'][$file_group] = array();
			if( ! isset( $options['files'][$file_group][$file_name] ) ) 
				$options['files'][$file_group][$file_name] = array();
			
			$options['files'][$file_group][$file_name]['time'] = $filetime;
			$options['files'][$file_group][$file_name]['size'] = $filesize;
			$options['files'][$file_group][$file_name]['md5'] = $md5;

			update_option( $this->ID . 'filecheck', $options );
			return $file_name . ' ' . __( 'saved...', 'montezuma' ); 
		
		} else {
			return $result['error'];
		}
	}	



	/** 
	 * Register this setting with WP:
	 * For the option group we're simply adding '-group' to the option name
	 */	
	public function register_admin() {
	
		// register_setting( $option_group, $option_name, $sanitize_callback ); 
		register_setting( $this->ID . '-group', $this->ID, array( &$this, 'validate' ) );
		$this->register_sections_n_fields();
	}

	
	
	/** 
	 * Register the theme page, required scripts and styles, plus some contextual help, with WP: 
	 */
	public function add_admin() {

		 // add_theme_page( $page_title, $menu_title, $capability, $menu_slug, $function);
		 // returns the page slug of this admin page, i.e. /wp-admin/themes.php?page=[$page_slug]
		$this->page = add_theme_page( $this->title, $this->title, 'edit_theme_options', $this->ID, array( &$this, 'print_admin' ) ); 

		// Use the $page_slug to add scripts and styles only on this admin page 

		// add_action( 'admin_enqueue_scripts', array( &$this, 'enqueue_scripts_n_styles' ) );
		add_action( 'admin_enqueue_scripts', array( &$this, 'styles' ) );
		add_action( 'admin_enqueue_scripts', array( &$this, 'scripts' ) );

		add_action( 'admin_head-' . $this->page, array( &$this, 'headscript' ) );

		// Since WP 3.3: add_help_tab - http://wpdevel.wordpress.com/tag/3-3-dev-notes/
		// if ( $page_slug )
		if( $this->help_exists )
			add_action( 'load-' . $this->page,  array( &$this, 'add_help_tabs' ) );	
	}


	public function headscript() {
	?>
<style type="text/css">
.CodeMirror pre {
  font-family: <?php echo $this->saved_settings['code_font_family']; ?>;
  font-size: <?php echo str_replace( 'px', '', $this->saved_settings['code_font_size']); ?>px;
  line-height: <?php echo str_replace( 'px', '', $this->saved_settings['code_line_height']); ?>px;
}</style>
<script type="text/javascript">
var optionID = '<?php echo $this->ID ?>',
uploaders = new Array(),
usedAttr = new Array(),
<?php // includes_url is a WP function, js/plupload... the relative path to WP's plupload files ?>
plup_flash_swf_url = '<?php echo includes_url( 'js/plupload/plupload.flash.swf' ); ?>',
plup_silverlight_xap_url = '<?php echo includes_url( 'js/plupload/plupload.silverlight.xap' ); ?>',
plup_nonce = '<?php echo wp_create_nonce( 'bfa_plupload' ); ?>',
php_file_nonce = '<?php echo wp_create_nonce( 'bfa_php_files' ); ?>',
bfa_add_item_nonce = '<?php echo wp_create_nonce( 'bfa_add_item' ); ?>',
bfa_delete_item_nonce = '<?php echo wp_create_nonce( 'bfa_delete_item' ); ?>',
bfa_import_settings_nonce = '<?php echo wp_create_nonce( 'bfa_import_settings' ); ?>',
bfa_reset_settings_nonce = '<?php echo wp_create_nonce( 'bfa_reset_settings' ); ?>',
bfa_reset_single_nonce = '<?php echo wp_create_nonce( 'bfa_reset_single' ); ?>',
bfa_tpl_dir_uri = '<?php echo get_template_directory_uri(); ?>',
currentCodemirror,
bfa_used_colors = <?php echo json_encode( $this->get_used_colors() ); ?>;
</script>
	<?php
	}

	
	public function scripts( $hook_suffix ) {

		// If we are on our own admin page
		if( $hook_suffix == $this->page ) {
		
			$enqu_list = array( 'jquery', 'jquery-ui-sortable', 'jquery-ui-draggable' );
			$wp_scripts = array_unique( $this->wp_scripts );		
			$enqu_list = array_merge( $enqu_list, $wp_scripts );
			$custom_scripts = array_unique( $this->custom_scripts );
			$custom_scripts[] = 'jquery.ui.colorPicker';

			foreach( $custom_scripts as $script ) {
				wp_register_script( $this->title . '-' . str_replace( '/', '-', $script ), get_template_directory_uri() 
					. '/' . $this->dir . '/' . $script . '.js', $enqu_list );
			}
			$custom_enqu_list = array();
			foreach( $custom_scripts as $script ) {
				$custom_enqu_list[] = $this->title . '-' . str_replace( '/', '-', $script );
			}
			
			$enqu_list = array_merge( $enqu_list, $custom_enqu_list );
					
			// Load jquery-ui-core through dependencies, direct wp_enqueue_script('jquery-ui-core') may be broken
			// http://wordpress.org/support/topic/wp_enqueue_script-with-jquery-ui-and-tabs
			wp_enqueue_script( $this->ID . '-admin-js', get_template_directory_uri() . '/' . $this->dir . '/admin.js', $enqu_list );		
		}	
	}



	public function styles( $hook_suffix ) {
	
		// If we are on our own admin page
		if( $hook_suffix == $this->page ) {	
		
			$wp_styles = array_unique( $this->wp_styles );
			
			foreach( $wp_styles as $style ) {
				wp_enqueue_style( $style );
			}
			
			$this->custom_styles[] = 'admin'; // Append admin.css
			$custom_styles = array_unique( $this->custom_styles );
			
			foreach( $custom_styles as $style ) {
				wp_enqueue_style( $this->title . '-' . str_replace( '/', '-', $style ), get_template_directory_uri() 
					. '/' . $this->dir . '/' . $style . '.css' );
			}
		}
	}	



	public function split_list_into_columns( $list, $columns ) {
	
		$string = "<table cellpadding='0' cellspacing='0'><tr>";
		
		// Split into columns
		$chunk_size = ceil( count( $list ) / $columns );
		$list_chunks = array_chunk( $list, $chunk_size );
		foreach( $list_chunks as $items ) {
			$string .= "<td style='vertical-align:top;padding-right:20px'>";	
			foreach( $items as $item ) 
				$string .= $item;
			$string .= "</td>";
		}
		$string .= "</tr></table>";
		return $string;		
	}
	
	

	// Add the required scripts and styles for this option to our list (i.e. media-upload script for WP uploader)
	public function add_scripts_n_styles( $option_type ) {
	
		switch ( $option_type ):
		
		case 'upload-image':
			array_push( $this->wp_scripts, 'media-upload', 'thickbox' );
			$this->custom_scripts[] = 'uploader';
			$this->wp_styles[] = 'thickbox';
			break;
			
		case 'wp-colorpicker':
			$this->wp_styles[] = 'farbtastic';
			#$this->wp_scripts[] = 'farbtastic';
			/**
				Using custom farbtastic version 'colorpicker.js',
				see changes in colorpicker.js line 192-198:
			*/
			$this->custom_scripts[] = 'colorpicker';
			break;
			
		case 'plupload':
			$this->wp_scripts[] = 'plupload-all';
			$this->custom_scripts[] = 'imagelist';
			break;
		
		
		case 'codemirror':
			array_push( $this->custom_scripts, 'codemirror/codemirror', 'codemirror/xml', 'codemirror/javascript', 'codemirror/css', 'codemirror/clike', 'codemirror/php' );
			$this->custom_styles[] = 'codemirror/codemirror';
			break;
			
		/*
		add more for other types that need scripts/styles		
		*/
		endswitch;
	}
	


	public function add_help_tabs() {

		$help = include $this->path . '/help.php';
		
		// Since WP 3.3: add_help_tab - http://wpdevel.wordpress.com/tag/3-3-dev-notes/
		$screen = get_current_screen();
		
		// If sidebar content defined in help.php, add it to WP and remove it from help tabs array
		if( isset( $help['sidebar'] ) ) {
			$screen->set_help_sidebar ( $help['sidebar'] );
			unset( $help['sidebar'] );
		}
		
		$i = 1;
		foreach( $help as $tab ) {
			$screen->add_help_tab( array(
				'id'      => $this->ID . '-help-tab-' . $i, // This should be unique for the screen.
				'title'   => $tab['title'],
				'content' => $tab['content'],
				// Use 'callback' instead of 'content' for a function callback that renders the tab content.
			) );		
			$i++;
		}
	}


	/**
	 * Extract default settings from the option_config array
	 */
	public function get_default_settings() {
	
		foreach ( $this->config as $section ) {
			foreach( $section['fields'] as $field ) { 
				if ( isset( $field['std'] ) ) {
					$default_settings[$field['id']] = $field['std'];
				} else {
					$default_settings[$field['id']] = '';
				}
			}
		}
		return $default_settings;
	}


	/** 
	 * Parse option section files and build one array with the complete option configuration 
	 * and add 'label_for' for appropriate fields
	 */
	public function get_option_config() {
	
		$sections = $this->parse_section_directory();
		
		// Parse each section file and add its settings to the array of default settings
		foreach( $sections as $section_ID => $section_info ) {
		
			$section_fields = include $this->path . '/' . $section_info['file'];
			
			// Section title is defined inside section file:
			if( isset( $section_fields['title'] ) ) {
				// Use this one and remove it from the fields array:
				$section_title = $section_fields['title']; 
				unset( $section_fields['title'] );
			// No section title defined:
			} else {
				// Use the existing section title, derived from the sections file name, in parse_section_directory() 
				$section_title = $section_info['title']; 
			}
			// Add the section title to the array of sections
			$sections[$section_ID]['title'] = $section_title;
			
			// Section description is defined inside section file:
			if( isset( $section_fields['description'] ) ) {
				// Add to sections array and remove it from the fields array:
				$sections[$section_ID]['description'] = $section_fields['description'];
				unset( $section_fields['description'] );
			}		
			
			// Add label_for to each field where applicable
			foreach( $section_fields as $field_ID => $field ) {
				$section_fields[ $field_ID ] = $this->add_label_for( $field );
			}
			
			// Section info such as 'title' and 'description' are removed, the fields array contains only fields 
			$sections[$section_ID]['fields'] = $section_fields;
		}
		return $sections;	
	}


	/**
	 * 'Automatically' adds the 'label_for' property to a field, based on
	 * type of field and whether title exists. No need to specify this manually 
	 * inside the option files. Does not add the 'label_for' where it doesn't make
	 * sense, i.e. for checkbox lists and radio lists, which get a directly 
	 * attached label on each checkbox or radio button.
	 */
	public function add_label_for( $field ) {
	
		/**
		 * IMPROVE THIS 
		 * so that the applicable types don't need to be kept track of here
		 */
		$label_for_types = array(
			'checkbox', 'select', 'text', 'textarea', 'upload-image', 'wp-colorpicker'
		);
		
		// Add the 'label_for' property (= same as option id)
		if( isset( $field['title'] ) && in_array( $field['type'], $label_for_types ) )
			$field['label_for'] = $field['id'];
			
		return $field;
	}


	// NEEDED?
	public function get_option_types() {
	
		foreach( $this->config as $section_ID => $section_info ) {
			foreach( $section_info['fields'] as $field ) {
				if( isset( $field['id'] ) && isset( $field['type'] ) ) 
					$types[ $field['id'] ] = $field['type'];			
			}
		}
		return $types;
	}


	/** NEEDED?
	 * Retrieves each option's additional info. Flat, without sections
	 */
	public function get_option_config_flat() {
	
		$option_config_flat = array();
		foreach( $this->config as $section_ID => $section_info ) {
			foreach( $section_info['fields'] as $field ) {
				if( isset( $field['id'] ) ) {
					$id = $field['id'];
					unset( $field['id'] );
					$option_config_flat[ $id ] = $field;
				}
			}
		}
		return $option_config_flat;
	}


	/** 
	  * Scan option file directory for section files, derive each section's title 
	  * from the file name, prettify the title and save it along with the full file name
	  */	  
	public function parse_section_directory() {
	
		$sections = array();
		
		foreach( scandir(  $this->path ) as $file_name ) {
			if( !is_dir( $file_name ) && $file_name != 'help.php' )  {
				// 100_section_name_html.php -> section_name_html
				$section_ID = str_replace( '.php', '', substr( $file_name, strpos( $file_name, '_') + 1 ) );	
				// section_name_html -> Section name HTML		
				// $section_title = $this->pretty_title( ucfirst( str_replace( '_', ' ', $section_ID ) ) );					
				$section_title = $this->pretty_title( ucwords( str_replace( '_', ' ', $section_ID ) ) );			
				
				$sections[ $section_ID ] = array( 'file' => $file_name, 'title' => $section_title );
			}	
		}
		return $sections;
	}


	/**
	 * Helper function for parse_section_directory
	 * Prettify section titles
	 */
	public function pretty_title( $string ) {
	
		$string_pretty = str_ireplace(
			array( 'php', 'xhtml', 'html5', 'html', 'css', 'js', 'jquery', 'seo' ),
			array( 'PHP', 'XHTML', 'HTML5', 'HTML', 'CSS', 'JS', 'jQuery', 'SEO' ),
			$string );	
			
		return $string_pretty;
	}


	/** 
	 * Register all settings sections and their fields with WP 
	 */
	public function register_sections_n_fields() {
	
		foreach ( $this->config as $section_ID => $section_info ) {
		
			// Register this settings section with WP: 
			// add_settings_section( $id, $title, $callback, $page ); 
			add_settings_section( $section_ID, $section_info['title'], array( &$this, 'section_callback' ), $this->ID );
			
			foreach( $section_info['fields'] as $field ) { 
			
				// Register fields for this section
				$title = isset( $field['title'] ) ? $field['title'] : '';
				// add_settings_field( $id, $title, $callback, $page, $section, $args );
				add_settings_field( $field['id'], $title, array( &$this, 'field_callback' ), $this->ID, $section_ID, $field );
						
				// Register required scripts and styles
				$this->add_scripts_n_styles( $field['type'] );		
				
				// Special for codemirror textareas. Each one will be initialized in admin head javascript. 
				if( $field['type'] == 'codemirror' ) {
					$this->codemirrors[] = $field['id'];
				}
			}
		}
	}

		
	/**
	 * wp-amdin/includes/template.php: call_user_func($section['callback'], $section);
	 * That means we have section['id'] and section['title'] 
	 */
	public function section_callback( $section ) {
	
		// Will be re-arranged with with jQuery. Using the opportunity to print the section ID here
		echo '<div class="section-descr" id=' . $section['id'] . '>';
		if( isset( $this->config[$section['id']]['description'] ) ) {
			echo  $this->config[$section['id']]['description'];
		}
		echo '</div>';
	} 


	public function field_callback( $args ) {

		$options = $this->saved_settings;
		$defaults = $this->default_settings;
		
		extract( $args, EXTR_SKIP );
		
		$name = "{$this->ID}[{$id}]";  //  myoptions['option1']
		$setting = isset( $options[$id] ) ? $options[$id] : '';	// whatever (saved value for setting)

		// Dummy element, will be used to "group" settings with jQuery
		echo isset( $group ) ? '<i class="group-this"></i>' : '';

		echo isset( $before ) ? $before : '';
		
		switch( $type ):

		case 'info':
			echo $setting;
			break;
			
	
		case 'checkbox':
			/**
			 * Save unchecked checkboxes as '0', with a hidden field: If the checkbox is checked,  
			 * its '1' value overrides the hidden input's '0' as both have the same 'name' attr.: 
			 * ...&name=0&name=1&... 
			 * If the checbox is unchecked, only the hidden '0' gets submitted: 
			 * ...&name=0&...
			 * Purpose: Verify whether a checkbox was indeed unchecked, or not known yet 
			 * (due to being new), whenever new options are added in new theme versions. 
			 */
			echo "<input type='hidden' name='$name' value='0' />";
			echo "<input type='checkbox' id='$id' name='$name' value='1' " . checked( '1', $setting, false ) . " />";
			
			break;


		case 'checkbox-list':
			// different handling based on whether checkbox options were configured as name/value pairs
			// or just values. The is_assoc function wouldn't be able to distinguish a numerical array
			// where a key was removed, from an associative array, however we don't remove keys anywhere
			// so it's good enough. The same function is used below for radio and select

			$list = array();
			
			if( $this->is_assoc( $values ) ) {
				foreach( $values as $value => $label ) {
					$list[] = "<label><input name='{$name}[]' type='checkbox' value='" . esc_attr( $value ) . "' " . 
					( is_array( $setting ) && in_array( $value, $setting ) ? "checked='checked'" : "" ) . 
					" /> $label</label>\n";
				}
			} else {
				foreach( $values as $value ) {
					$list[] = "<label><input name='{$name}[]' type='checkbox' value='" . esc_attr( $value ) . "' " . 
					( is_array( $setting ) && in_array( $value, $setting ) ? "checked='checked'" : "" ) . 
					" /> $value</label>\n";	
				}
			}
			
			if( isset( $columns ) ) 
				echo $this->split_list_into_columns( $list, $columns );
			else 
				echo implode( '', $list );
			break;

			
		case 'radio':
			$list = array();
			
			if( $this->is_assoc( $values ) ) {
				foreach( $values as $value => $label ) 
					$list[] = "<label><input name='$name' type='radio' value='" . esc_attr( $value ) . "' " . 
					checked( $value, $setting, false ) . "> $label</label>\n";
			} else {
				foreach( $values as $value ) 
					$list[] = "<label><input name='$name' type='radio' value='" . esc_attr( $value ) . "' " . 
					checked( $value, $setting, false ) . "> $value</label>\n";	
			}
			
			if( isset( $columns ) ) 
				echo $this->split_list_into_columns( $list, $columns );
			else 
				echo implode( '', $list );
			break;
			

		case 'posts':
			$list = array();
			
			$posts = &get_posts( array( 'numberposts' => -1, 'orderby' => 'date' ) );
			if( $posts ) {	
			
				foreach( $posts as $post ) {
					// Posts without title
					if( $post->post_title == '' ) $post_title = "Post without Title - ID #" . $post->ID;
					else $post_title = $post->post_title;

					$list[] = "<label><input name='{$name}[]' type='checkbox' value='" . $post->ID . "' " . 
					( is_array( $setting ) && in_array( $post->ID, $setting ) ? "checked='checked'" : "" ) . 
					" /> " . $post_title . "</label>\n";
				}			
			
				if( isset( $columns ) ) 
					echo $this->split_list_into_columns( $list, $columns );
				else 
					echo implode( '', $list );		
				
			} else {
				echo '<p>' . __( 'No Posts Available', 'montezuma' ) . '</p>';
			}
			break;

			
		case 'pages':
			$list = array();
			
			$pages = &get_pages();
			if( $pages ) {
			
				foreach( $pages as $page ) {
					// Pages without title
					if( $page->post_title == '' ) $page_title = "Page without Title - ID #" . $page->ID;
					else $page_title = $page->post_title;

					$list[] = "<label><input name='{$name}[]' type='checkbox' value='" . $page->ID . "' " . 
					( is_array( $setting ) && in_array( $page->ID, $setting ) ? "checked='checked'" : "" ) . 
					" /> " . $page_title . "</label>\n";
				}			

				if( isset( $columns ) ) 
					echo $this->split_list_into_columns( $list, $columns );
				else 
					echo implode( '', $list );	
				
			} else {
				echo '<p>' . __( 'No Pages Available', 'montezuma' ) . '</p>';
			}
			break;

			
		case 'categories':
			$list = array();
			
			$categories = &get_categories( array( 'hide_empty' => false ) );
			if( $categories ) {
			
				foreach( $categories as $category ) 
					$list[] = "<label><input name='{$name}[]' type='checkbox' value='" . $category->term_id . "' " . 
					( is_array( $setting ) && in_array( $category->term_id, $setting ) ? "checked='checked'" : "" ) . 
					" /> " . $category->name . "</label>\n";		
			
				if( isset( $columns ) ) 
					echo $this->split_list_into_columns( $list, $columns );
				else 
					echo implode( '', $list );	
							
			} else {
				echo '<p>' . __( 'No Categories Available', 'montezuma' ) . '</p>';
			}
			break;

			
		case 'tags':
			$list = array();
			
			$tags = &get_tags( array( 'hide_empty' => false ) );
			if( $tags ) {
			
				foreach( $tags as $tag ) 
					$list[] = "<label><input name='{$name}[]' type='checkbox' value='" . $tag->term_id . "' " . 
					( is_array( $setting ) && in_array( $tag->term_id, $setting ) ? "checked='checked'" : "" ) . 
					" /> " . $tag->name . "</label>\n";

				if( isset( $columns ) ) 
					echo $this->split_list_into_columns( $list, $columns );
				else 
					echo implode( '', $list );	
				
			} else {
				echo '<p>' . __( 'No Tags Available', 'montezuma' ) . '</p>';
			}
			break;
			

		case 'select':
			echo "<select id='$id' name='$name' />";
			echo '<option value="">-- ' . __( 'Select One', 'montezuma' ) . ' --</option>';
			if( $this->is_assoc( $values ) ) {
				foreach( $values as $value => $label ) 
					echo "<option value='" . esc_attr( $value ) . "' " . selected( $value, $setting, false ) . ">$label</option>";		
			} else {
				foreach( $values as $value ) 
					echo "<option value='" . esc_attr( $value ) . "' " . selected( $value, $setting, false ) . ">$value</option>";
			}
			echo "</select>";
			break;

			
		case 'post':
			echo "<select id='$id' name='$name' />";
			echo '<option value="">-- ' . __( 'Select One Post', 'montezuma' ) . ' --</option>';
			$posts = &get_posts( array( 'numberposts' => -1, 'orderby' => 'date' ) );
			if( $posts ) {
				foreach( $posts as $post ) {
					// Posts without title
					if( $post->post_title == '' ) $post_title = sprintf( __( 'Post without Title - ID #%1$s', 'montezuma' ), $post->ID );
					else $post_title = $post->post_title;
						
					echo "<option value='" . $post->ID . "' " . selected( $post->ID, $setting, false ) . ">" . $post_title . "</option>";			
				}
			} else {
				echo '<option value="0">... ' . __( 'No Posts Available', 'montezuma' ) . ' ...</option>';
			}
			echo "</select>";
			break;

			
		case 'category':
			echo "<select id='$id' name='$name' />";
			echo '<option value="">-- ' . __( 'Select One Category', 'montezuma' ) . ' --</option>';
			$categories = &get_categories( array( 'hide_empty' => false ) );
			if( $categories ) {
				foreach( $categories as $category ) 				
					echo "<option value='" . $category->term_id . "' " . selected( $category->term_id, $setting, false ) . ">" . $category->name . "</option>";			
			} else {
				echo '<option value="0">... ' . __( 'No Categories Available', 'montezuma' ) . ' ...</option>';
			}
			echo "</select>";	
			break;

			
		case 'tag':
			echo "<select id='$id' name='$name' />";
			echo '<option value="">-- ' . __( 'Select One Tag', 'montezuma' ) . ' --</option>';
			$tags = &get_tags( array( 'hide_empty' => false ) );
			if( $tags ) {
				foreach( $tags as $tag ) 			
					echo "<option value='" . $tag->term_id . "' " . selected( $tag->term_id, $setting, false ) . ">" . $tag->name . "</option>";			
			} else {
				echo '<option value="0">... ' . __( 'No Tags Available', 'montezuma' ) . ' ...</option>';
			}
			echo "</select>";	
			break;
	
	
		case 'page':
			echo "<select id='$id' name='$name' />";
			echo '<option value="">-- ' . __( 'Select One Page', 'montezuma' ) . ' --</option>';
			$pages = &get_pages();
			if( $pages ) {
				foreach( $pages as $page ) {
					// Posts without title
					if( $page->post_title == '' ) 
						$page_title = sprintf( __( 'Page without Title - ID #%1$s', 'montezuma' ), $page->ID );
					else 
						$page_title = $page->post_title;
						
					echo "<option value='" . $page->ID . "' " . selected( $page->ID, $setting, false ) . ">" . $page_title . "</option>";			
				}
			} else {
				echo '<option value="0">... ' . __( 'No Pages Available', 'montezuma' ) . ' ...</option>';
			}
			echo "</select>";
			break;
	
	
		case 'text':
			if( ! isset( $style ) ) $style = '';
			// Using esc_textarea to allow html tags like &middot;
			echo "<input class='regular-text' type='text' id='$id' name='$name' value='" . esc_textarea( $setting ) . "' style='$style' />";
			break;
		
		
		case 'textarea':
			// esc_textarea turns & into &amp; 
			echo "<textarea spellcheck='false' class='code' id='$id' name='$name'>" . esc_textarea( $setting ) . "</textarea>";
			break;

				
		case 'codemirror':
		// Replace - with _ in $id if any, to make it suitable for unique (JS) CodeMirror variable name
		$code_mirror_id = str_replace( '-', '_', $id ) . '_CodeMiror';
		// esc_textarea turns & into &amp; 
		$mode = isset( $codemirrormode ) ? " rel='$codemirrormode'" : "";
		// Using $id-codemirror or else footer.php gets id "footer" which steps on WP's admin area #footer
		echo "<textarea spellcheck='false' class='codemirrorarea code' id='$id-codemirror' name='$name'$mode>" . esc_textarea( $setting ) . "</textarea>";
		break;
		
			
		case 'upload-image':
			echo "<input type='text' class='regular-text code' style='color:blue;width:500px' id='$id' name='$name' value='" . esc_url( $setting ) . "' />"; 
			// submit_button( $text, $type, $name, $wrap, $other_attributes )
			// Adding unique $id to button ID for the sole reason of avoiding multiple identical ID's on the same page
			submit_button( __( 'Upload', 'montezuma' ), 'button-secondary upload_wp_image', 'upload_image-' . $id, false );
			submit_button( __( 'Delete', 'montezuma' ), 'button-secondary delete delete_wp_image', 'delete_image-' . $id, false );
			if( !isset( $style ) OR $style == '' ) $style = 'width:100%;height:70px;';
			echo "<div class='image-here' style='border:dotted 1px #ccc;margin-top:5px;". 
			( $setting != ''  ? "background:url(" . esc_url( $setting ) . ") no-repeat left center;" : "" ) . "$style'></div>";
			break;

			
		case 'wp-colorpicker':
			echo "<input type='text' class='code colorfield' id='$id' name='$name' value='" . esc_attr( $setting ) . "' />
			<input type='button' class='pickcolor button-secondary' value='" . __( 'Select Color', 'montezuma' ) . "'>" . 
			( isset( $std ) ? "&nbsp;&nbsp;<span class='resetcolor'>" . __( 'Default:', 'montezuma' ) . " <a href='#'>$std</a> 
			&nbsp;<span style='background:$std;padding-left:15px'></span></span>" : "" ) . 
			"&nbsp;&nbsp;<a href='#' class='clearcolor'>" . __( 'Clear', 'montezuma' ) . "</a>
			<div style='z-index: 100; background:#eee; border:1px solid #ccc; position:absolute; display:none;'></div>";
			break;

			
		case 'plupload': 
			$resize = isset( $setting['resize'] ) ? $setting['resize'] : NULL;
			$img_width = isset( $setting['img-width'] ) ? $setting['img-width'] : NULL;
			$img_height = isset( $setting['img-height'] ) ? $setting['img-height'] : NULL;
			$img_quality = isset( $setting['img-quality'] ) ? $setting['img-quality'] : NULL;
			$create_thumb = isset( $setting['create-thumb'] ) ? $setting['create-thumb'] : NULL;
			$thumb_width = isset( $setting['thumb-width'] ) ? $setting['thumb-width'] : NULL;
			$thumb_height = isset( $setting['thumb-height'] ) ? $setting['thumb-height'] : NULL;
			$thumb_crop = isset( $setting['thumb-crop'] ) ? $setting['thumb-crop'] : NULL;
			?>
			
			<div class="plupcontainer" id="<?php echo $id ?>" rel="<?php echo $name ?>">
			
			<strong><?php _e( '1. Upload Images', 'montezuma' ); ?></strong> 
			
			<br><?php _e( 'Upload settings:', 'montezuma' ); ?>
			<span class="clicktip">?</span>
			<div class="hidden">
				<p><?php _e( 'Resizing &amp; thumbnail creation happens <strong>while</strong> images are being uploaded.  
				To resize or create thumbnails for existing images, delete those images (Step 2), 
				and upload them again.', 'montezuma' ); ?></p>
			</div>
			
			<br>
			<div>
				<input type="hidden" name="<?php echo $name ?>[resize]" value="0" />
				<label><input class="addtl-info resize-check" type="checkbox" name="<?php echo $name ?>[resize]" value="1" <?php checked( '1', $resize ) ?>/> <?php _e( 'Resize Images', 'montezuma' ); ?></label>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<span style="display:none;margin-left:50px;">
					 <?php _e( 'Width:', 'montezuma' ); ?> <input class="resize-width" type="text" name="<?php echo $name ?>[img-width]" value="<?php echo $img_width ?>" style="width:50px" />px
					 &nbsp;&nbsp;&nbsp;&nbsp;<?php _e( 'Height:', 'montezuma' ); ?> <input class="resize-height" type="text" name="<?php echo $name ?>[img-height]" value="<?php echo $img_height ?>" style="width:50px" />px 
					 &nbsp;&nbsp;&nbsp;&nbsp;<?php _e( 'Quality:', 'montezuma' ); ?> <input class="resize-quality" type="text" name="<?php echo $name ?>[img-quality]" value="<?php echo $img_quality ?>" style="width:30px" />% 
					 <span class="clicktip">?</span>
					 <div class="hidden"><?php _e( 'Possible values: Number between 1 and 99. 
						 Reasonable values: 50-95. Recommended: 65-85. 
						 <br><br>Higher number means better image quality but also bigger file size. 
						 <br><br>It is a good idea to use this option if have not already 
						 shrinked the file size of your images, i.e. with an image editing program, on your desktop computer.
						 <br><br>Tip: Try a value and after the upload view the size value displayed below each image, and click 
						 the image to inspect its quality. If the quality is too bad or the file size too big, simply delete 
						 the image and repeat the upload with a different quality value.', 'montezuma' ); ?> 
					</div> 
				 </span>
			</div>
			
			<div>
				<input type="hidden" name="<?php echo $name ?>[create-thumb]" value="0" />
				<label><input class="addtl-info thumb-check" type="checkbox" name="<?php echo $name ?>[create-thumb]" value="1" <?php checked( '1', $create_thumb ) ?>/> <?php _e( 'Create Thumbnails', 'montezuma' ); ?></label>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<span style="display:none;margin-left:50px;">
					 <?php _e( 'Width:', 'montezuma' ); ?> <input class="thumb-width" type="text" name="<?php echo $name ?>[thumb-width]" value="<?php echo $thumb_width ?>" style="width:35px" />px
					 &nbsp;&nbsp;&nbsp;&nbsp;<?php _e( 'Height:', 'montezuma' ); ?> <input class="thumb-height" type="text" name="<?php echo $name ?>[thumb-height]" value="<?php echo $thumb_height ?>" style="width:35px" />px 
					 &nbsp;&nbsp;&nbsp;&nbsp;<label><input class="thumb-crop" type="checkbox" name="<?php echo $name ?>[thumb-crop]" value="1" <?php checked( '1', $thumb_crop ) ?>/> <?php _e( 'Crop', 'montezuma' ); ?></label>
					  <span class="clicktip">?</span>
					  <div class="hidden">
						<?php _e( 'Checking this will force the thumbnail to have the specified width &amp; 
						height, by cutting image parts appropriately.', 'montezuma' ); ?>
					</div>
				 </span>
			</div>

			<input id="<?php echo $id ?>-button" type="button" value="Upload Images" style="margin-top:10px" class="plupButton button" />
			<span class="clicktip">?</span>
			<div class="hidden">
				<?php _e( '<p>After you clicked the "Upload Images" button you can select &amp; upload multiple images at once.</p>
				<p>Uploaded images will appear at the bottom of existing images. This may take 1-3 seconds per image.</p>', 'montezuma' ); ?>
			</div>
			
			<br><br><strong><?php _e( '2. Re-order &amp; delete images', 'montezuma' ); ?></strong> 
			<span class="clicktip">?</span>
			<div class="hidden">
				<span style="float:right;padding:5px">
				<img src="<?php echo get_template_directory_uri(); ?>/<?php echo $this->dir; ?>/images/moveThis.png"/><br>
				<img src="<?php echo get_template_directory_uri(); ?>/<?php echo $this->dir; ?>/images/close.png"/></span>
				<?php _e( 'Pointing your mouse at an image shows its "Move" and "Delete" handles in the top left and right corners. 
				Click the "Delete" handle in the top right corner to delete the image from this list. 
				This will also delete the image from the server.', 'montezuma' ); ?>
				</li>
				</ul>

			</div>
			
			<ul class="pluplist">
				<?php if( isset($setting['list']) AND is_array($setting['list']) ) { ?>
					<?php $i=0; foreach($setting['list'] as $image) { ?>
					<li class="imagelist-item">
						<a class="imagelist-image" title="<?php _e( 'Click to view full size image', 'montezuma' ); ?>" 
						href="<?php echo $image['src'] ?>" style="background-image:url('<?php echo $image['src'] ?>')"></a>
						<div title="<?php _e( 'Click, hold & move to change this image position in list', 'montezuma' ); ?>" class="movehandle"></div>
						<div title="<?php _e( 'Click to remove this image from list', 'montezuma' ); ?>" class="closehandle"></div>
						<div class="imagelist-inner" style="padding:5px">
							<input class="thisPath" name="<?php echo $name ?>[list][<?php echo $i ?>][path]" type="hidden" value="<?php echo $image['path'] ?>" />
							<input name="<?php echo $name ?>[list][<?php echo $i ?>][src]" type="hidden" value="<?php echo $image['src'] ?>" />
							<input name="<?php echo $name ?>[list][<?php echo $i ?>][size]" type="hidden" value="<?php echo $image['size'] ?>" /> 
							<input name="<?php echo $name ?>[list][<?php echo $i ?>][width]" type="hidden" value="<?php echo $image['width'] ?>" /> 
							<input name="<?php echo $name ?>[list][<?php echo $i ?>][height]" type="hidden" value="<?php echo $image['height'] ?>" />
							<strong><?php _e( 'image src', 'montezuma' ); ?></strong> <?php echo $image['src'] ?>
							<br><strong><?php _e( 'size', 'montezuma' ); ?></strong> <?php echo $image['size'] ?> (kb) 
							&nbsp;&nbsp;&nbsp;&nbsp;<strong><?php _e( 'width', 'montezuma' ); ?></strong> <?php echo $image['width'] ?> (px)
							&nbsp;&nbsp;&nbsp;&nbsp;<strong><?php _e( 'height', 'montezuma' ); ?></strong> <?php echo $image['height'] ?> (px)
							<?php if ( isset( $image['thumb'] ) ) { ?>
							<br>
							<input class="thisThumbPath" name="<?php echo $name ?>[list][<?php echo $i ?>][tpath]" type="hidden" value="<?php echo $image['tpath'] ?>" />
							<input name="<?php echo $name ?>[list][<?php echo $i ?>][thumb]" type="hidden" value="<?php echo $image['thumb'] ?>" />
							<input name="<?php echo $name ?>[list][<?php echo $i ?>][twidth]" type="hidden" value="<?php echo $image['twidth'] ?>" /> 
							<input name="<?php echo $name ?>[list][<?php echo $i ?>][theight]" type="hidden" value="<?php echo $image['theight'] ?>" />
							<strong><?php _e( 'thumb', 'montezuma' ); ?></strong> <?php echo $image['thumb'] ?>
							<br><strong><?php _e( 'twidth', 'montezuma' ); ?></strong> <?php echo $image['twidth'] ?> (px) 
							&nbsp;&nbsp;&nbsp;&nbsp;<strong><?php _e( 'theight', 'montezuma' ); ?></strong> <?php echo $image['theight'] ?> (px)						
							<?php } ?>
							<?php if( isset($setting['used-attr']) ) { 
								foreach( $setting['used-attr'] as $attr ) { 
								if( !in_array( $attr, array('src', 'size', 'width', 'height') ) ) { ?>
							<span class="attr-<?php echo $attr ?>"><br><label><?php echo $attr ?> 
							<input name="<?php echo $name ?>[list][<?php echo $i ?>][<?php echo $attr ?>]" class="regular-text code" style="color:blue;width:700px;margin-bottom:5px" type="text" value="<?php echo $image[$attr] ?>" /></label></span>
							<?php } } } ?>
						</div>
					</li>		  
					<?php $i++; } ?>
				<?php } ?>
			</ul>
			
			<br><strong><?php _e( '3. Add additional data to images (optional)', 'montezuma' ); ?></strong>
			<span class="clicktip">?</span>
			<div class="hidden">
				<?php _e( "Right after the upload each image gets 4 basic attributes applied to it 
				automatically: <strong>src</strong>, (file) <strong>size</strong>, 
				<strong>width</strong> and <strong>height</strong>.<br><br>
				However, you may need more data than those 4 attributes.<br><br>
				Example: You want to link each image, so you need to add an URL to each image. 
				In that case you'd probably add an <strong>href</strong> attribute. 
				(Technically, you could give it any name, like 'whatever' instead of 'href'). <br><br>
				Here in this section, you'll just add the data name, such as 'href'. The data value 
				(such as 'http://mysite.com/link.html') you will add directly below each image.<br><br>
				The text input field for the data value will appear below each image, 
				immediately after you clicked 'Add &raquo;'.", 'montezuma' ); ?>
			</div>
			<br><?php _e( 'Data name:', 'montezuma' ); ?> 
			<span class="add-attr"><input type="text" style="width:130px" value=""> <a href="#"><?php _e( 'Add', 'montezuma' ); ?> &raquo;</a></span>
			&nbsp;&nbsp;&nbsp;<em>(<?php _e( 'Examples: href, alt, title, rel, target, whatever, anything...', 'montezuma' ); ?>)</em><br> 
			<?php _e( 'Added data (Click to remove):', 'montezuma' ); ?><br>
			<div class="used-attr">
			<?php if( isset($setting['used-attr']) ) { 
				foreach( $setting['used-attr'] as $attr ) { ?>
				<label><input type="checkbox" name="<?php echo $name ?>[used-attr][]" checked="checked" value="<?php echo $attr ?>" /> 
				<?php echo $attr ?></label>&nbsp;&nbsp;			
			<?php } } ?>
			</div>
			</div>
			<?php
			
			break;

			
		endswitch;
		
		echo isset( $after ) ? $after : '';	
		
		if( $type != 'info' ) 
			echo '<button class="reset-single" id="reset-single-' . $id . '" title="' . sprintf( __( 'Reset option %1$s to default', 'montezuma' ), $id ) . '"><i></i>'. $id . '</button>';
	} 


	/**
	 * Helper function for field_callback:
	 * Check whether array is associative = whether select, radio or multi-checkboxes  
	 * were provided with separate values/labels or just values, in the option configuration files
	 */
	function is_assoc($arr) {
	
		return array_keys($arr) !== range(0, count($arr) - 1);
	}


	/**
	 * Print the admin page
	 */
	public function print_admin() {

		// This at the end, because both "settings-updated" and "reset-now" may be set
		if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] == true ) {
			$update_message = '<div class="updated fade"><p>' . sprintf( __( '%1$s updated', 'montezuma' ), $this->title ) . '</p></div>'; 
		}
			
		?><div class="wrap">
			
			<div id="ataajaxloading" style="display:none;width:160px;z-index:10000000;position:fixed;top:50%;left:50%;margin-left:-100px;margin-top:-60px;border-radius:10px;box-shadow:0 0 20px -10px #000;background:#F0FFDC;padding:30px;font-size:30px;text-align:center;">
			<img src="<?php echo get_template_directory_uri(); ?>/admin/images/ajax-loader.gif" />
			<br><br><?php _e( 'Saving...', 'montezuma' ); ?>
			</div>
			
			<?php $this->save_css_file(); ?>
			<?php $this->save_javascript_file(); ?>
		
			<?php screen_icon(); ?><h2><?php echo $this->title; ?></h2>
			<?php echo isset( $update_message ) ? $update_message : ''; ?>
			
			<div id="ata-wrap">
				<div id="ataadmincontent">
					<form id="<?php echo $this->ID ?>" class="themeoptionsform" method="post" action="options.php">
						<?php wp_nonce_field( $this->ID . '-theme-options'); ?>
						<div id="topsubmit"> 
							<input class="button-primary" type="submit" value="Save Changes" />
						</div>
						<?php settings_fields( $this->ID . '-group' ); ?>
						<?php // do_settings_sections( $this->ID ); ?>
						<?php // Using custom 'do_settings_sections':
						$this->do_settings_sections( $this->ID ); ?>
						
						<?php // not using WP's submit_button() because it prints name and ID attributes, both of which
						// breaks jQuery's submit() and trigger('submit'): http://bugs.jquery.com/ticket/4652
						// submit_button(); ?>
						<!-- don't use 'name' or 'id' attribute for the input element or else jQuery submit() won't work 
						Needed for a second submit botton in the top right corner of the theme admin : -->
						<button id="save-all" title="Save ALL (not just on curent tab) settings" type="submit"><i></i><?php _e( 'SAVE Changes', 'montezuma' ); ?></button>
					</form>

					<?php // no "Reset" mechanism in WP Settings API, hardcoding own: ?>
					<button id="reset-all" title="Reset ALL <?php echo $this->ID; ?> settings to default values"><i></i><?php _e( 'Reset ALL', 'montezuma' ); ?></button>
				</div>
				
				<div id="ataadminmenu">
					<ul>
					<?php // $this->menu available AFTER $this->do_settings_sections ran
					foreach( $this->menu as $sectionid => $sectioncontent ) {
						$section_title = $this->pretty_title( ucwords( str_replace( '_', ' ', $sectionid ) ) );
						echo "<li id='topmenu-$sectionid'><a href='#'>$section_title</a>
								<ul>";
						foreach( $sectioncontent as $id => $title ) {
							echo "<li id='menu-$id'><a href='#'>$title</a></li>";
						}
						
						echo "</ul></li>";
					} ?>
					</ul>
				</div>
				
				
			</div>
	
			

	
	<?php	
	}



	/** 
	 * custom version of WP's do_settings_sections in wp-admin/includes/template.php
	 *
	*/
	public function do_settings_sections( $page ) {
	
		global $wp_settings_sections, $wp_settings_fields;

		if ( !isset($wp_settings_sections) || !isset($wp_settings_sections[$page]) )
			return;

		foreach ( (array) $wp_settings_sections[$page] as $section ) {

			if ( !isset( $wp_settings_fields ) || !isset( $wp_settings_fields[$page] ) || !isset( $wp_settings_fields[$page][$section['id']] ) )
				continue;
			
			$this->menu[$section['id']] = array();
			$this->do_settings_fields($page, $section['id']);		
		}
		echo "</div>"; // close last option DIV
	}
	
	

	/** 
	 * custom version of WP's do_settings_fields in wp-admin/includes/template.php
	 *
	 */	
	public function do_settings_fields( $page, $section ) {
	
		global $wp_settings_fields;

		if ( !isset( $wp_settings_fields ) || !isset( $wp_settings_fields[$page] ) || !isset( $wp_settings_fields[$page][$section] ) )
			return;

		foreach ( (array) $wp_settings_fields[$page][$section] as $field ) {
		
			// build "option package" id from title of first option in package
			// = option where 'title' is set.
			$title = trim( $field['title'] );
			if( $title != '' ) {
			
				if( ! isset( $this->config_flat[$field['id']]['isfirstoption'] )  ) 
					echo "\n</div>\n";
								
				$title_id = strtolower( preg_replace( '/[^a-zA-Z0-9]*/', '', $title ) );
				
				echo "<div id='option-pack-$title_id' class='ata-option-pack-container cf'>\n
						<h2>$title</h2>";

				$this->menu[$section][$title_id] = $title;
			}
			
			echo '<div id="option-' . $field['id'] . '" class="ata-option">';
			call_user_func($field['callback'], $field['args']);			
			echo '</div>';
		}
	}



	// Sanitize and validate input. Accepts an array, return a sanitized array.
	public function validate( $input ) {
	
		// 1. General validation based on type
		foreach( $input as $ID => $value ):
	
			// Dynamically added options not included in $this-types because it gets loaded from 
			// default option files. TODO get types from saved options instead to include 
			// dynamically added options.
			// Dynamic options possible with: maintemplate-..., subtemplate-... (add: imagelists)
			if( strpos( $ID, 'maintemplate-' ) === 0 OR strpos( $ID, 'subtemplate-' ) === 0 )
				$type = 'codemirror';
			else 
				$type = $this->types[$ID]; 
			
			switch( $type ):

			case 'textarea':
				if ( !current_user_can('unfiltered_html') )
					$input[$ID] = stripslashes( wp_filter_post_kses( addslashes( $input[$ID] ) ) ); // wp_filter_post_kses() expects slashed
				break;
			
			// (Single) checkboxes must be 0 or 1
			case 'checkbox':
				$input[$ID] = ( $input[$ID] == 1 ? 1 : 0 );
			break;
			
			// Colors must be #xxx or #xxxxxx
			/*
			case 'wp-colorpicker':
				if( !preg_match( '/^#?(([a-fA-F0-9]){3}){1,2}$/i', $input[$ID] ) )
					$input[$ID] = '';
			break;
			*/
			
			// Radio option must contain a value that was in the array of available values as set in option config files
			/*
			case 'radio':
				$available_options = $this->get_available_options( $ID );
				if( !in_array( $input[$ID], $available_options ) )
					$input[$ID] = '';
			break;
			*/
			
			// Checkbox list array must contain available values as set in option config files
			// Check this
			/*case 'checkbox-list':
				$available_options = $this->get_available_options( $ID );
				if( !in_array( $input[$ID], $available_options ) )
					$input[$ID] = '';
			break;	
			*/
			
			endswitch;
		
		endforeach;
		
		// 2. Individual validations:
		
		// Our first value is either 0 or 1
		// $input['option1'] = ( $input['option1'] == 1 ? 1 : 0 );
		
		// Say our second option must be safe text with no HTML tags
		// $input['sometext'] =  wp_filter_nohtml_kses( $input['sometext'] );
		
		// add_settings_error( $setting, $code, $message, $type )
		
		return $input;
	}


	/**
	 * Get available values of radio or checkbox lists as (numerical) array
	 */
	public function get_available_options( $ID ) {

		$values = $this->config_flat[$ID]['values'];
		$available_options = array();
		if( $this->is_assoc( $values) ) {
			foreach( $values as $value => $label ) {
				$available_options[] = $value;
			}
		} else {
				$available_options = $values;
		}
		return $available_options;
	}


	
}
