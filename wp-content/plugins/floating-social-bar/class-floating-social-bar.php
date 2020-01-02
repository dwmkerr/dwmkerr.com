<?php
/**
 * Floating Social Bar plugin class.
 *
 * @package   Floating Social Bar
 * @author    Syed Balkhi
 * @author    Thomas Griffin
 * @license   GPL-2.0+
 * @copyright 2013 WPBeginner. All rights reserved.
 */

/**
 * Main plugin class.
 *
 * @package Floating Social Bar
 */
class floating_social_bar {

    /**
     * Plugin version, used for cache-busting of style and script file references.
     *
     * @since 1.0.0
     *
     * @var string
     */
    protected $version = '1.1.5';

    /**
     * The name of the plugin.
     *
     * @since 1.0.0
     *
     * @var string
     */
    protected $plugin_name = 'Floating Social Bar';

    /**
     * Unique plugin identifier.
     *
     * @since 1.0.0
     *
     * @var string
     */
    protected $plugin_slug = 'floating-social-bar';

    /**
     * Plugin textdomain.
     *
     * @since 1.0.0
     *
     * @var string
     */
    protected $domain = 'fsb';

    /**
     * Instance of this class.
     *
     * @since 1.0.0
     *
     * @var object
     */
    protected static $instance = null;

    /**
     * Slug of the plugin screen.
     *
     * @since 1.0.0
     *
     * @var string
     */
    protected $plugin_screen_hook_suffix = null;

    /**
     * The plugin options.
     *
     * @since 1.0.0
     *
     * @var null
     */
    protected $option = null;

    /**
     * Holds any plugin error messages.
     *
     * @since 1.0.0
     *
     * @var array
     */
    protected $errors = array();

    /**
     * Flag to determine if the script has been localized.
     *
     * @since 1.0.0
     *
     * @var bool
     */
    protected $is_localized = false;

    /**
     * Initialize the plugin class object.
     *
     * @since 1.0.0
     */
    private function __construct() {

        // Load plugin text domain.
        add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

        // Load the plugin.
        add_action( 'init', array( $this, 'init' ) );

        // Handle ajax requests.
        add_action( 'wp_ajax_fsb_save_order', array( $this, 'save_order' ) );
        add_action( 'wp_ajax_nopriv_fsb_save_order', array( $this, 'save_order' ) );
        add_action( 'wp_ajax_fsb_load_stats', array( $this, 'load_stats' ) );
        add_action( 'wp_ajax_nopriv_fsb_load_stats', array( $this, 'load_stats' ) );

    }

    /**
     * Return an instance of this class.
     *
     * @since 1.0.0
     *
     * @return object A single instance of this class.
     */
    public static function get_instance() {

        // If the single instance hasn't been set, set it now.
        if ( null == self::$instance )
            self::$instance = new self;

        return self::$instance;

    }

    /**
     * Fired when the plugin is activated.
     *
     * @since 1.0.0
     *
     * @param boolean $network_wide True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
     */
    public static function activate( $network_wide ) {

    	if ( is_multisite() ) :
	    	global $wpdb;
	      	$site_list = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->blogs ORDER BY blog_id" ) );
			foreach ( (array) $site_list as $site ) :
				switch_to_blog( $site->blog_id );

				// Ensure default options are set.
		        $option = get_option( 'fsb_global_option' );
		        if ( ! $option || empty( $option ) )
		            update_option( 'fsb_global_option', floating_social_bar::default_options() );

				restore_current_blog();
			endforeach;
		else :
	        // Ensure default options are set.
	        $option = get_option( 'fsb_global_option' );
	        if ( ! $option || empty( $option ) )
	            update_option( 'fsb_global_option', floating_social_bar::default_options() );
	    endif;

    }

    /**
     * Fired when the plugin is deactivated.
     *
     * @since 1.0.0
     *
     * @param boolean $network_wide True if WPMU superadmin uses "Network Deactivate" action, false if WPMU is disabled or plugin is deactivated on an individual blog.
     */
    public static function deactivate( $network_wide ) {



    }

    /**
     * Fired when the plugin is uninstalled.
     *
     * @since 1.0.0
     *
     * @param boolean $network_wide True if WPMU superadmin uses "Network Deactivate" action, false if WPMU is disabled or plugin is deactivated on an individual blog.
     */
    public static function uninstall( $network_wide ) {

		if ( is_multisite() ) :
			global $wpdb;
	      	$site_list = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->blogs ORDER BY blog_id" ) );
			foreach ( (array) $site_list as $site ) :
				switch_to_blog( $site->blog_id );
				delete_option( 'fsb_global_option' );
				restore_current_blog();
			endforeach;
		else :
	    	delete_option( 'fsb_global_option' );
	    endif;

    }

    /**
     * Load the plugin text domain for translation.
     *
     * @since 1.0.0
     */
    public function load_plugin_textdomain() {

        $domain = $this->domain;
        $locale = apply_filters( 'plugin_locale', get_locale(), $domain );

        load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
        load_plugin_textdomain( $domain, false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );

    }

    /**
     * Loads the plugin.
     *
     * @since 1.0.6
     */
    public function init() {

	    // Go ahead and set the option property.
        $this->option = get_option( 'fsb_global_option' );

        // Load the plugin settings link shortcut.
        add_filter( 'plugin_action_links_' . plugin_basename( plugin_dir_path( __FILE__ ) . 'floating-social-bar.php' ), array( $this, 'settings_link' ) );

        // Add the options page and menu item.
        add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

        // Add the shortcode for outputting the social bar.
        add_shortcode( 'fsb-social-bar', array( $this, 'shortcode' ) );

        // Filter the content to add in our floating social bar.
        add_action( 'pre_get_posts', array( $this, 'maybe_do_social_bar' ) );

    }

    /**
     * Add Settings page to plugin action links in the Plugins table.
     *
     * @since 1.0.0
     *
     * @param array $links Default plugin action links.
     * @return array $links Amended plugin action links.
     */
    public function settings_link( $links ) {

        $setting_link = sprintf( '<a href="%s">%s</a>', add_query_arg( array( 'page' => 'floating-social-bar' ), admin_url( 'options-general.php' ) ), __( 'Settings', 'fsb' ) );
        array_unshift( $links, $setting_link );

        return $links;

    }

    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @since 1.0.0
     */
    public function add_plugin_admin_menu() {

        //delete_option( 'fsb_global_option' );

        // Register the menu.
        $this->plugin_screen_hook_suffix = add_options_page(
            __( 'Floating Social Bar', $this->plugin_slug ),
            __( 'Floating Social Bar', $this->plugin_slug ),
            'manage_options',
            $this->plugin_slug,
            array( $this, 'display_plugin_admin_page' )
        );

        // Load any single metaboxes to allow individual post control over loading the social bar.
        foreach ( (array) $this->option['show_on'] as $post_type )
            add_meta_box( 'fsb-social-bar-' . $post_type, __( 'Floating Social Bar Options', 'fsb' ), array( $this, 'metabox_callback' ), $post_type, 'normal', 'high' );

        // Load the save feature for saving metabox values.
        add_action( 'save_post', array( $this, 'save_metabox' ) );

        // Optionally load check for saving plugin options.
        if ( isset( $_REQUEST['fsb-plugin-save'] ) && $_REQUEST['fsb-plugin-save'] )
            add_action( 'admin_init', array( $this, 'save' ) );

        // If successful, load admin assets only on that page.
        if ( $this->plugin_screen_hook_suffix )
            add_action( 'load-' . $this->plugin_screen_hook_suffix, array( $this, 'load_plugin_assets' ) );

    }

    /**
     * Callback for outputting the single post metabox.
     *
     * @since 1.0.0
     *
     * @param object $post The current post object.
     */
    public function metabox_callback( $post ) {

        // Add a nonce for extra security.
        wp_nonce_field( 'fsb_show_social', 'fsb_show_social' );

        ?>
        <table class="form-table">
            <tbody>
                <tr id="fsb-show-social" valign="middle">
                    <th scope="row"><label for="fsb-show-social-bar"><?php _e( 'Hide Social Bar?', 'fsb' ); ?></label></th>
                    <td>
                        <input id="fsb-show-social-bar" type="checkbox" name="fsb-show-social" value="<?php echo get_post_meta( $post->ID, 'fsb_show_social', true ); ?>" <?php checked( get_post_meta( $post->ID, 'fsb_show_social', true ), 1 ); ?> />
                        <span class="description"><?php printf( __( 'Checking this setting will hide the floating social bar on this particular %s.', 'fsb' ), $post->post_type ); ?></span>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php

    }

    /**
     * Saves metabox values.
     *
     * @since 1.0.0
     *
     * @param int $post_id The current post ID.
     */
    public function save_metabox( $post_id ) {

        // Bail out if we fail a security check.
        if ( ! isset( $_POST[sanitize_key( 'fsb_show_social' )] ) || ! wp_verify_nonce( $_POST[sanitize_key( 'fsb_show_social' )], 'fsb_show_social' ) )
            return;

        // Bail out if running an autosave, ajax or a cron.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
            return;
        if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
            return;
        if ( defined( 'DOING_CRON' ) && DOING_CRON )
            return;

        // Bail out if the user doesn't have the correct permissions to update the slider.
        if ( ! current_user_can( 'edit_post', $post_id ) )
            return;

        // Update the post meta field value.
        update_post_meta( $post_id, 'fsb_show_social', isset( $_POST['fsb-show-social'] ) ? 1 : 0 );

    }

    /**
     * Saves the plugin options if the user has submitted them.
     *
     * @since 1.0.0
     */
    public function save() {

        // Do a security check before saving options first.
        check_admin_referer( 'fsb_save_options', 'fsb_save_options' );

        // If no data was submitted, return early.
        if ( empty( $_REQUEST['_fsb_data'] ) )
            return;

        // Grab the plugin options and data that has been submitted.
        $option    = get_option( 'fsb_global_option' );
        $submitted = stripslashes_deep( $_REQUEST['_fsb_data'] );

        // Unset the submit value since we don't need it.
        if ( isset( $submitted['submit'] ) )
            unset( $submitted['submit'] );

        // Reset the show_on key.
        $option['show_on'] = array();
        $i = 0;

        foreach ( get_post_types( array( 'public' => true ) ) as $post_type )
            if ( isset( $submitted['show_on'] ) && in_array( $post_type, $submitted['show_on'] ) )
                $option['show_on'][] = esc_attr( $post_type );

        // Unset the show_on key from the submitted info.
        if ( isset( $submitted['show_on'] ) )
            unset( $submitted['show_on'] );

        // Sanitize the option values.
        $submitted['label'] 	= isset( $submitted['label'] ) ? sanitize_text_field( $submitted['label'] ) : '';
        $submitted['twitter'] 	= isset( $submitted['twitter'] ) ? sanitize_text_field( $submitted['twitter'] ) : '';
        $submitted['transient'] = isset( $submitted['transient'] ) ? intval( $submitted['transient'] ) : 1800;
        $submitted['pinback']	= isset( $submitted['pinback'] ) ? esc_url( $submitted['pinback'] ) : '';
		$submitted['static'] 	= isset( $submitted['static'] ) ? 1 : 0;
		$submitted['position']	= isset( $submitted['position'] ) ? esc_attr( $submitted['position'] ) : 'above';
		$submitted['socialite'] = isset( $submitted['socialite'] ) ? 1 : 0;

        // Finally, update the option.
        update_option( 'fsb_global_option', array_merge( $option, $submitted ) );

    }

    /**
     * Loads assets only on this plugin's administration dashboard.
     *
     * @since 1.0.0
     */
    public function load_plugin_assets() {

        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

    }

    /**
     * Register and enqueue admin-specific stylesheets.
     *
     * @since 1.0.0
     */
    public function enqueue_admin_styles() {

        wp_enqueue_style( $this->plugin_slug . '-bootstrap', plugins_url( 'lib/bootstrap/css/bootstrap.min.css', __FILE__ ), array(), $this->version );
        wp_enqueue_style( $this->plugin_slug . '-google-fonts', '//fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600|Lato:300,400,700', array( $this->plugin_slug . '-bootstrap' ), $this->version );
        wp_enqueue_style( $this->plugin_slug . '-admin-styles', plugins_url( 'css/admin.css', __FILE__ ), array( $this->plugin_slug . '-bootstrap', $this->plugin_slug . '-google-fonts' ), $this->version );

    }

    /**
     * Register and enqueue admin-specific JS.
     *
     * @since 1.0.0
     */
    public function enqueue_admin_scripts() {

        // Enqueue jQuery UI components.
        wp_enqueue_script( 'jquery-ui-sortable'  );
        wp_enqueue_script( 'jquery-ui-draggable' );
        wp_enqueue_script( 'jquery-ui-droppable' );

        // Enqueue scripts.
        wp_enqueue_script( $this->plugin_slug . '-bootstrap', plugins_url( 'lib/bootstrap/js/bootstrap.min.js', __FILE__ ), array( 'jquery' ), $this->version );
        wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'js/admin.js', __FILE__ ), array( 'jquery' ), $this->version );

        // Localize scripts.
        wp_localize_script(
            $this->plugin_slug . '-admin-script',
            'fsb',
            array(
                'ajax' => admin_url( 'admin-ajax.php' ),
                'save' => __( 'Saving your settings...', 'fsb' )
            )
        );

    }

    /**
     * Render the settings page for this plugin.
     *
     * @since 1.0.0
     */
    public function display_plugin_admin_page() {

        // Load the plugin options.
        $this->option = get_option( 'fsb_global_option' ) ? get_option( 'fsb_global_option' ) : $this->default_options();

        ?>
        <!-- Facebook Like Code -->
        <div id="fb-root"></div>
        <script>(function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s); js.id = id;
          js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
          fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>

        <!-- Facebook Like Code -->
        <div id="tgm-plugin-settings" class="container">
            <header class="row">
                <div class="col-lg-6">
                    <h2><span class="glyphicon glyphicon-tasks"></span> <?php echo esc_html( get_admin_page_title() ); ?></h2>
                </div>
                <div class="col-lg-6">
                    <ul class="nav nav-pills pull-right">
                        <li class="twitter-follow"><a href="https://twitter.com/wpbeginner" class="twitter-follow-button" data-show-count="true">Follow @wpbeginner</a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>
                        <li class="facebook-like"><div class="fb-like" data-href="http://facebook.com/wpbeginner" data-send="false" data-layout="button_count" data-width="147" data-show-faces="false"></div></li>
                    </ul>
                </div>
            </header>

            <?php if ( ! empty( $_REQUEST['fsb-plugin-save'] ) && $_REQUEST['fsb-plugin-save'] ) : ?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="alert alert-warning">
                        <button type="button" class="close" data-dismiss="alert">x</button>
                        <p class="no-margin"><strong><?php _e( 'Your settings have been saved successfully!', 'fsb' ); ?></strong></p>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if ( ! empty( $this->errors ) ) : ?>
            <div class="row">
                <div class="col-lg-12">
                    <?php foreach ( $this->errors as $error ) : ?>
                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert">x</button>
                            <p class="no-margin"><strong><?php echo $error; ?></strong></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-lg-8">
                    <div class="tgm-plugin-settings">
                        <p class="tgm-plugin-settings-intro"><?php _e( 'You can customize your plugin settings below. To save your settings, make sure you click the green "Save Settings" button at the bottom of the settings table.', 'fsb' ); ?></p>
                        <form id="tgm-plugin-settings-form" action="<?php echo add_query_arg( array( 'page' => $this->plugin_slug, 'fsb-plugin-save' => true ), admin_url( 'options-general.php' ) ); ?>" method="post">
                            <?php wp_nonce_field( 'fsb_save_options', 'fsb_save_options' ); ?>

                            <div class="tgm-sharing-area col-lg-12">
                                <div class="available-sharing sharing-helper row">
                                    <div class="col col-lg-4">
                                        <h5><strong><?php _e( 'Available Social Services', 'fsb' ); ?></strong></h5>
                                        <p><?php _e( 'Drag and drop services you would like to enable in your floating social bar into the box below.', 'fsb' ); ?></p>
                                    </div>
                                    <div class="col col-lg-8">
                                        <ul class="list-unstyled col col-lg-12 no-padding">
                                            <?php foreach ( $this->option['services'] as $service => $data ) : ?>
                                                <?php if ( true === $data['on'] ) continue; ?>
                                                <li class="service-<?php echo $service; ?>" data-service="<?php echo $service; ?>">
                                                    <span class="service-icon"></span><span class="service-title"><?php _e( $service, 'fsb' ); ?></span>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                                <div class="enabled-sharing sharing-helper row">
                                    <div class="col col-lg-4">
                                        <h5><strong><?php _e( 'Enabled Social Services', 'fsb' ); ?></strong></h5>
                                        <p><?php _e( 'Services listed here will be enabled in the floating social bar on your site.', 'fsb' ); ?></p>
                                    </div>
                                    <div class="col col-lg-8">
                                        <h5 class="text-muted sharing-text"><?php _e( 'Drag services here to enable them.', 'fsb' ); ?></h5>
                                        <ul class="list-unstyled col col-lg-12 no-padding">
                                            <?php foreach ( $this->option['services'] as $service => $data ) : ?>
                                                <?php if ( false === $data['on'] ) continue; ?>
                                                <li class="service-<?php echo $service; ?>" data-service="<?php echo $service; ?>">
                                                    <span class="service-icon"></span><span class="service-title"><?php _e( $service, 'fsb' ); ?></span>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-striped table-bordered clearfix clear">
                                <thead>
                                    <tr>
                                        <td><?php _e( 'Setting', 'fsb' ); ?></td>
                                        <td><?php _e( 'Value', 'fsb' ); ?></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th><label for="fsb-label"><?php _e( 'Social Bar Label', 'fsb' ); ?></th>
                                        <td>
                                            <input id="fsb-label" type="text" name="_fsb_data[label]" value="<?php echo $this->option['label']; ?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th><label for="fsb-posts"><?php _e( 'Show Bar On Single', 'fsb' ); ?></th>
                                        <td>
                                            <?php $post_types = get_post_types( array( 'public' => true ) ); foreach ( (array) $post_types as $show ) : $pt_object = get_post_type_object( $show ); $label = $pt_object->labels->name; ?>
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" id="fsb-<?php esc_html_e( strtolower( $label ) ); ?>" name="_fsb_data[show_on][]" value="<?php echo $show; ?>" <?php checked( in_array( $show, $this->option['show_on'] ) ); ?> /> <?php esc_html_e( $label ); ?>
                                                </label>
                                             <?php endforeach; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th><label for="fsb-twitter"><?php _e( 'Twitter Username', 'fsb' ); ?></th>
                                        <td>
                                            <input id="fsb-twitter" type="text" name="_fsb_data[twitter]" value="<?php echo $this->option['twitter']; ?>" />
                                            <small style="margin-bottom:0;" class="help-block text-muted"><?php _e( 'Your twitter username when visitors retweet your posts (no @ symbol).', 'fsb' ); ?></small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th><label for="fsb-transient"><?php _e( 'Interval to Update Stats', 'fsb' ); ?></th>
                                        <td>
                                            <input id="fsb-transient" type="text" name="_fsb_data[transient]" value="<?php echo $this->option['transient']; ?>" />
                                            <small style="margin-bottom:0;" class="help-block text-muted"><?php _e( 'Defaults to every 30 minutes (1800 seconds). Value calculated in seconds.', 'fsb' ); ?></small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th><label for="fsb-pinback"><?php _e( 'Pinterest Image Fallback', 'fsb' ); ?></th>
                                        <td>
                                        	<?php $pinback = isset( $this->option['pinback'] ) ? $this->option['pinback'] : ''; ?>
                                            <input id="fsb-pinback" type="text" name="_fsb_data[pinback]" value="<?php echo $pinback; ?>" />
                                            <small style="margin-bottom:0;" class="help-block text-muted"><?php _e( 'Used if no featured image or images within the content are found.', 'fsb' ); ?></small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th><label for="fsb-static"><?php _e( 'Make Social Bar Static', 'fsb' ); ?></th>
                                        <td>
                                        	<?php $static = isset( $this->option['static'] ) ? $this->option['static'] : 0; ?>
                                            <input id="fsb-static" type="checkbox" name="_fsb_data[static]" value="<?php echo $static; ?>" <?php checked( $static ); ?> />
                                            <small style="display:inline;margin:0 0 0 3px;" class="help-block text-muted"><?php _e( 'If checked, the social bar will not float.', 'fsb' ); ?></small>
                                        </td>
                                    </tr>
                                    <tr id="fsb-position-box" style="display:none;">
                                        <th><label for="fsb-position"><?php _e( 'Position of Static Bar', 'fsb' ); ?></th>
                                        <td>
                                            <select id="fsb-position" name="_fsb_data[position]">
                                            	<option value="above" <?php selected( 'above', ( isset( $this->option['position'] ) ? $this->option['position'] : 'above' ) ); ?>><?php _e( 'Above Content', 'fsb' ); ?></option>
                                            	<option value="below" <?php selected( 'below', ( isset( $this->option['position'] ) ? $this->option['position'] : 'above' ) ); ?>><?php _e( 'Below Content', 'fsb' ); ?></option>
                                            	<option value="both" <?php selected( 'both', ( isset( $this->option['position'] ) ? $this->option['position'] : 'above' ) ); ?>><?php _e( 'Above and Below Content', 'fsb' ); ?></option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th><label for="fsb-socialite"><?php _e( 'Disable Socialite', 'fsb' ); ?></th>
                                        <td>
                                        	<?php $socialite = isset( $this->option['socialite'] ) ? $this->option['socialite'] : 0; ?>
                                            <input id="fsb-socialite" type="checkbox" name="_fsb_data[socialite]" value="<?php echo $socialite; ?>" <?php checked( $socialite ); ?> />
                                            <small style="display:inline;margin:0 0 0 3px;" class="help-block text-muted"><?php _e( 'If checked, Socialite will not load when hovering over the social bar.', 'fsb' ); ?></small>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <p><strong><input type="submit" class="btn btn-success" name="_fsb_data[submit]" value="<?php esc_attr_e( 'Save Settings', 'fsb' ); ?>" /></strong></p>
                        </form>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="panel panel-primary">
                        <div class="panel-heading text-center"><?php _e( 'Get Exclusive Email Updates!', 'fsb' ); ?></div>
                        <div id="mc_embed_signup">
                            <form action="http://wpbeginner.us1.list-manage.com/subscribe/post?u=549b83cc29ff23c36e5796c38&amp;id=bb5851a68f" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
                                 <p><label style="margin-bottom:0;" for="mce-EMAIL"><?php _e( 'Join our email newsletter to get exclusive add-ons and other cool WordPress related resources.', 'fsb' ); ?></label></p>
                                 <p><input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="<?php esc_attr_e( 'Enter your email address here...', 'fsb' ); ?>" required></p>
                                 <div class="clear"><input type="submit" value="<?php esc_attr_e( 'Subscribe', 'fsb' ); ?>" name="subscribe" id="mc-embedded-subscribe" class="btn btn-primary btn-block"></div>
                            </form>
                        </div>
                    </div>
                    <div class="panel panel-success">
                        <div class="panel-heading text-center"><?php _e( 'Get Soliloquy - Best WordPress Slider!', 'fsb' ); ?></div>
                        <p><a title="<?php esc_attr_e( 'Get Soliloquy Today!', 'fsb' ); ?>" href="http://wpbeginner.com/refer/soliloquy/?utm_source=fsb&utm_medium=link&utm_campaign=Floating+Social+Bar" target="_blank"><img src="<?php echo plugins_url( 'images/soliloquy.png', __FILE__ ); ?>" alt="<?php esc_attr_e( 'Soliloquy - the Best Responsive WordPress Slider Plugin', 'fsb' ); ?>" /></a></p>
                        <p><?php printf( __( '<strong><a href="%s" title="%s" target="_blank">%s</a> %s</strong> %s', 'fsb' ), 'http://wpbeginner.com/refer/soliloquy/?utm_source=fsb&utm_medium=link&utm_campaign=Floating+Social+Bar', 'Soliloquy is the best responsive WordPress slider plugin!', 'Soliloquy', 'is the best responsive WordPress slider plugin on the market.', 'You can build responsive sliders in 60 seconds or less with its easy to use interface. Take advantage of our coupon and save 25% on Soliloquy today!' ); ?></p>
                        <a class="btn btn-success btn-block" title="<?php esc_attr_e( 'Get Soliloquy Today!', 'fsb' ); ?>" href="http://wpbeginner.com/refer/soliloquy/?utm_source=fsb&utm_medium=link&utm_campaign=Floating+Social+Bar" target="_blank"><strong><?php _e( 'Click Here to Get Soliloquy!', 'fsb' ); ?></strong></a>
                        <div class="panel-footer">
                            <p class="no-margin text-center"><?php printf( __( 'Use the code <strong>%s</strong> at the checkout!', 'fsb' ), 'WPBEGINNER' ); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php

    }

    /**
     * Updates the stats of the social services.
     *
     * @since 1.0.0
     *
     * @param array $services The services to update.
     * @param int $post_id The current post ID to update.
     * @return array $stats Array of updated stats from services.
     */
    public function do_stats_update( $services, $post_id ) {

    	// Set our stats variable.
    	$stats = array();

        // If our does not exist, update the stat counters and set the transient.
        if ( false === get_transient( 'fsb_transient_' . $post_id ) ) {
            // Set variables and update active service counters.
            $post_url = get_permalink( $post_id );

            // Loop through each service and do updating if necessary.
            foreach ( (array) $services as $service ) {
                // Update an individual service.
                switch ( $service ) {
                    case 'facebook' :
                        $facebook_url      = 'https://api.facebook.com/method/fql.query?format=json&query=SELECT%20total_count%20FROM%20link_stat%20WHERE%20url=%22' . $post_url . '%22';
                        $facebook_stat     = json_decode( wp_remote_fopen( $facebook_url ) );
                        $stats['facebook'] = empty( $facebook_stat[0]->total_count ) ? '0' : (int) $facebook_stat[0]->total_count;
                        update_post_meta( $post_id, 'fsb_social_facebook', $stats['facebook'] );
                    break;

                    case 'twitter' :
                        $twitter_url      = 'http://urls.api.twitter.com/1/urls/count.json?url=' . $post_url;
                        $twitter_stat     = json_decode( wp_remote_fopen( $twitter_url ) );
                        $stats['twitter'] = empty( $twitter_stat->count ) ? '0' : (int) $twitter_stat->count;
                        update_post_meta( $post_id, 'fsb_social_twitter', $stats['twitter'] );
                    break;

                    case 'google' :
                        $curl = curl_init();
                        curl_setopt( $curl, CURLOPT_URL, "https://clients6.google.com/rpc" );
                        curl_setopt( $curl, CURLOPT_POST, 1 );
                        curl_setopt( $curl, CURLOPT_POSTFIELDS, '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"' . $post_url . '","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]' );
                        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
                        curl_setopt( $curl, CURLOPT_HTTPHEADER, array( 'Content-type: application/json' ) );
                        $curl_results = curl_exec( $curl );
                        curl_close( $curl );

                        $json            = json_decode( $curl_results, true );
                        $stats['google'] = intval( $json[0]['result']['metadata']['globalCounts']['count'] );
                        update_post_meta( $post_id, 'fsb_social_google', $stats['google'] );
                    break;

                    case 'linkedin' :
                        $linkedin_url      = 'http://www.linkedin.com/countserv/count/share?url=' . $post_url .'&format=json';
                        $linkedin_stat     = json_decode( wp_remote_fopen( $linkedin_url ) );
                        $stats['linkedin'] = empty( $linkedin_stat->count ) ? '0' : (int) $linkedin_stat->count;
                        update_post_meta( $post_id, 'fsb_social_linkedin', $stats['linkedin'] );
                    break;

                    case 'pinterest' :
                        $pinterest_url  = 'http://api.pinterest.com/v1/urls/count.json?callback=receiveCount&url=' . $post_url;
                        $pinterest_stat = json_decode( preg_replace( '/^receiveCount\((.*)\)$/', "\\1", wp_remote_fopen( $pinterest_url ) ) );
                        $stats['pinterest'] = empty( $pinterest_stat->count ) ? '0' : (int) $pinterest_stat->count;
                        update_post_meta( $post_id, 'fsb_social_pinterest', $stats['pinterest'] );
                    break;
                }
            }

            // Update our transient and set it to expire based on what the user has determined.
            set_transient( 'fsb_transient_' . $post_id, $stats, $this->option['transient'] );
        }

        // Return the updated stats counter.
        return $stats;

    }

    /**
     * Generates the shortcode for the social bar.
     *
     * @since 1.0.0
     *
     * @param array $atts Array of shortcode attributes.
     * @return string $fsb Concatenated output of the social bar
     */
    public function shortcode( $atts ) {

        // Prepare variables.
        global $post;
        $output   = '';
        $has_atts = false;

        // Cast any "true" or "false" attributes to a boolean value.
        foreach ( (array) $atts as $key => $val ) {
            if ( 'true' == $val )
                $atts[$key] = true;
            if ( 'false' == $val )
                $atts[$key] = false;
        }

        // If we have any attributes, set the flag to true.
        if ( ! empty( $atts ) )
            $has_atts = true;

        // If we have attributes, output in the order that they are placed in the attributes.
        if ( $has_atts ) {
            // Loop through the attributes and output the proper code.
            $services 	 = '';
            $has_service = false;
            $available = $this->get_services();
            foreach ( (array) $atts as $service => $bool ) {
                // Pass over any items set to false or if they are not in the services list.
                if ( ! $bool || ! in_array( $service, $available ) ) continue;

                // Set flag to true so that we know to output something on the screen.
                $has_service = true;

                // Retrieve the output.
                $count  = get_post_meta( $post->ID, 'fsb_social_' . $service, true );
                $services .= $this->get_service_output( $service, $count );
            }

            // Only proceed if we actually have services to output.
            if ( $has_service )
            	$output .= $this->do_social_bar_output( $services, $atts, true );
        } else {
            // Otherwise, we will just use the user-defined settings.
            $services 	 = '';
            $has_service = false;
            foreach ( $this->option['services'] as $service => $data ) {
                if ( ! $data['on'] ) continue;

                // Set flag to true so that we know to output something on the screen.
                $has_service = true;

                // Retrieve the output.
                $count  = get_post_meta( $post->ID, 'fsb_social_' . $service, true );
                $services .= $this->get_service_output( $service, $count );
            }

            // Only proceed if we actually have services to output.
            if ( $has_service )
            	$output .= $this->do_social_bar_output( $services );
        }

        // Return the output.
        return $output;

    }

    public function do_social_bar_output( $services, $atts = array(), $manual = false ) {

    	global $post;

	    // Enqueue the JS file for the social bar.
        wp_enqueue_script( $this->plugin_slug . '-fsb', plugins_url( 'js/fsb.js', __FILE__ ), array( 'jquery' ), $this->version, true );

        // Localize the JS script if it hasn't already been done.
        if ( ! $this->is_localized ) {
	        wp_localize_script( $this->plugin_slug . '-fsb', 'fsb',
	        	array(
	        		'ajax' => admin_url( 'admin-ajax.php' )
				)
			);
			$this->is_localized = true;
		}

        // Build the outer social bar container.
        if ( $manual )
        	$static_class = isset( $atts['static'] ) && $atts['static'] ? ' fsb-no-float' : '';
        else
        	$static_class = isset( $this->option['static'] ) && $this->option['static'] ? ' fsb-no-float' : '';

        // Check if we should output socialite or not.
        if ( $manual )
        	$socialite = isset( $atts['socialite'] ) && ! $atts['socialite'] ? ' data-socialite="false"' : ' data-socialite="true"';
        else
        	$socialite = isset( $this->option['socialite'] ) && $this->option['socialite'] ? ' data-socialite="false"' : ' data-socialite="true"';

        $output  = '';
        $output .= '<div id="fsb-social-bar" class="fsb-social-bar' . $static_class . '" data-post-id="' . $post->ID . '"' . $socialite . '>';

        // Prepend the styles inline to the social bar for increased speed.
        $output .= '<style type="text/css">';
        $css     = '
            #fsb-social-bar { width: 100%; border-bottom: 1px solid #dbdbdb; border-top: 1px solid #dbdbdb; padding: 10px 0; margin: 0px 0 20px 0; float: left; background: #fff; position: relative; clear: both; }
            #fsb-social-bar a { border: 0px !important }
            #fsb-social-bar.fsb-fixed { position: fixed; top: -2px; z-index: 99999; }
            #fsb-social-bar .fsb-title { display: block; float: left; margin: 3px 20px 0 0; font-size: 16px; font-family: Arial, Helvetica, sans-serif; text-decoration: none; color: #333; }
            #fsb-social-bar .fsb-share-facebook { width: 120px; float: left; padding: 3px 0 2px; height: 25px; }
            #fsb-social-bar .fsb-share-facebook.fsb-hide-count { width: 44px; overflow: hidden; margin-right: 30px; }
            #fsb-social-bar .fsb-share-twitter { float: left; width: 135px; padding: 3px 0 2px; height: 25px; }
            #fsb-social-bar .fsb-share-twitter.fsb-hide-count { width: 61px; overflow: hidden; margin-right: 30px; }
            #fsb-social-bar .fsb-share-google { float: left; width: 105px; padding: 3px 0 2px; height: 25px; }
            #fsb-social-bar .fsb-share-google.fsb-hide-count { width: 33px; overflow: hidden; margin-right: 30px; }
            #fsb-social-bar .fsb-share-linkedin { float: left; width: 135px; padding: 3px 0 2px; height: 25px; }
            #fsb-social-bar .fsb-share-linkedin.fsb-hide-count { width: 61px; overflow: hidden; margin-right: 30px; }
            #fsb-social-bar .fsb-share-pinterest { float: left; width: 115px; padding: 3px 0 2px; height: 25px;}
            #fsb-social-bar .fsb-share-pinterest.fsb-hide-count { width: 43px; overflow: hidden; margin-right: 30px; }
            #fsb-social-bar .socialite { display: block; position: relative; background: url(' . plugins_url( 'images/fsb-sprite.png', __FILE__ ) . ') no-repeat scroll 0 0; }
            #fsb-social-bar .socialite-loaded { background: none !important; }
            #fsb-social-bar .fsb-service-title { display: none; }
            #fsb-social-bar a { color: #333; text-decoration: none; font-size: 12px; font-family: Arial, Helvetica, sans-serif; }
            #fsb-social-bar .fsb-twitter { width: 105px; height: 25px; background-position: -13px -10px; line-height: 25px; vertical-align: middle; }
            #fsb-social-bar .fsb-twitter .fsb-count { width: 30px; text-align: center; display: inline-block; margin: 0px 0 0 69px; color: #333; }
            #fsb-social-bar .fsb-google { width: 75px; height: 25px; background-position: -136px -10px; line-height: 25px; vertical-align: middle; }
            #fsb-social-bar .fsb-google .fsb-count { width: 30px; text-align: center; display: inline-block; margin: 0px 0 0 41px; color: #333; }
            #fsb-social-bar .fsb-google .socialite-button { margin: 0 !important; }
            #fsb-social-bar .fsb-share-google .socialite-loaded .socialite-button{padding: 2px 0 0}
            #fsb-social-bar .fsb-facebook { width: 89px; height: 25px; background-position: -231px -10px; line-height: 25px; vertical-align: middle; }
            #fsb-social-bar .fsb-facebook .fsb-count { width: 30px; text-align: center; display: inline-block; margin: 0px 0 0 52px; color: #333; }
            #fsb-social-bar .fsb-facebook .socialite-button { margin: 0 !important;}
            #fsb-social-bar .fsb-share-facebook .socialite-loaded .socialite-button {padding: 2px 0 0}
            #fsb-social-bar .fsb-linkedin { width: 105px; height: 25px; background-position: -347px -10px; line-height: 25px; vertical-align: middle; }
            #fsb-social-bar .fsb-linkedin .fsb-count { width: 30px; text-align: center; display: inline-block; margin: 0px 0 0 70px; color: #333; }
            #fsb-social-bar .fsb-linkedin .socialite-button { margin: 0 !important; }
            #fsb-social-bar .fsb-pinterest { width: 88px; height: 25px; background-position: -484px -10px; line-height: 25px; vertical-align: middle; }
            #fsb-social-bar .fsb-pinterest .fsb-count { width: 30px; text-align: center; display: inline-block; margin: 0px 0 0 50px; color: #333; }
            #fsb-social-bar .fsb-pinterest .socialite-button { margin: 0 !important; }
            .fsb-clear { clear: both; }
            .fsb-clear:after { clear:both; content:'.'; display:block; height:0; line-height:0; overflow:auto; visibility:hidden; zoom:1; }
            @media (max-width: 768px) { #fsb-social-bar.fsb-fixed { position: relative !important; top: auto !important; } }
        ';
        $output .= str_replace( array( "\n", "\t", "\r" ), '', $css ) . '</style>';

        // If we have a title, output it now.
        if ( ! empty( $this->option['label'] ) )
            $output .= '<span class="fsb-title">' . esc_attr( $this->option['label'] ) . '</span>';

        // Add in the services.
        $output .= $services;

        // Close up the outer social bar container.
        $output .= '</div>';

        // Append a float clear.
        $output .= '<div class="fsb-clear"></div>';

        // Return the output.
        return $output;

    }

    /**
     * Maybe adds the social bar if the query is the main query.
     *
     * @since 1.0.0
     *
     * @param object $query The current query to check if main query.
     */
    public function maybe_do_social_bar( $query ) {

	    // If we are in the admin or not on the main query, do nothing.
	    if ( is_admin() || ! $query->is_main_query() )
	    	return;

	    // Filter the content and excerpt with our social bar.
	    add_filter( 'the_content', array( $this, 'fsb' ), apply_filters( 'fsb_social_bar_priority', 10 ) );
	    add_filter( 'the_excerpt', array( $this, 'fsb' ), apply_filters( 'fsb_social_bar_priority', 10 ) );

    }

    /**
     * Filters the content to output our floating social bar.
     *
     * @since 1.0.0
     *
     * @param string $content The post content to be filtered.
     * @return string $content Possibly amended content with our social bar.
     */
    public function fsb( $content ) {

    	global $post, $wp_current_filter;

    	// If we are not on a single post, the global $post is not set or the post status is not published, return early.
        if ( ! is_singular( $this->option['show_on'] ) || empty( $post ) || 'publish' !== get_post_status( $post->ID ) )
            return $content;

        // Also return early if the post type is not in our settings array or if the meta value is checked to off.
        $hide = get_post_meta( $post->ID, 'fsb_show_social', true );
        if ( ! in_array( $post->post_type, $this->option['show_on'] ) || $hide )
            return $content;

        // Don't do anything for excerpts.
        if ( in_array( 'get_the_excerpt', (array) $wp_current_filter ) )
        	return $content;

        // If we have reached this point, let's output the social bar and prepend it to the content.
        $social_bar = do_shortcode( '[fsb-social-bar]' );

        // Determine how we add the content by user options.
        if ( ! isset( $this->option['static'] ) || isset( $this->option['static'] ) && ! $this->option['static'] ) {
        	return $social_bar . $content;
        } else {
	        if ( ! isset( $this->option['position'] ) || isset( $this->option['position'] ) && 'above' == $this->option['position'] )
	        	return $social_bar . $content;
	        else if ( isset( $this->option['position'] ) && 'below' == $this->option['position'] )
	        	return $content . $social_bar;
	        else
	        	return $social_bar . $content . $social_bar;
        }

    }

    /**
     * Remove our social bar from any excerpt output.
     *
     * @since 1.0.0
     *
     * @param string $content The content to be filtered.
     * @return string $content The content without our social bar.
     */
    public function excerpt_helper( $content ) {

	    remove_filter( 'the_content', array( $this, 'fsb' ), 10 );
	    return $content;

    }

    /**
     * Saves the order of enabled services.
     *
     * @since 1.0.0
     */
    public function save_order() {

        // Prepare variables.
        $items  = stripslashes_deep( $_REQUEST['items'] );
        $option = get_option( 'fsb_global_option' );
        $update = array();

        // Loop through options, and if the service is not in the array of items, set it to off (the order doesn't matter).
        foreach ( $option['services'] as $service => $data )
            if ( ! in_array( $service, $items ) )
                $update['services'][$service]['on'] = false;

        // Now loop through the selected items and set them to on and save the order.
        foreach ( $items as $i => $item ) {
            $update['services'][$item]['on']    = true;
            $update['services'][$item]['order'] = $i;
        }

        // Update our option.
        update_option( 'fsb_global_option', array_merge( $option, $update ) );

        // Send back a response and die.
        echo json_encode( $update );
        die;

    }

    /**
     * Loads the stats into place asynchronously so as to not affect page
     * load times even remotely. :-)
     *
     * @since 1.0.0
     */
    public function load_stats() {

		// Prepare variables.
		$post_id  = stripslashes( absint( $_POST['postid'] ) );
		$services = stripslashes_deep( $_POST['services'] );

		// Do the stats update.
		$stats = $this->do_stats_update( $services, $post_id );

		// Return the stats counter back to the script and die.
		echo json_encode( $stats );
		die;

    }

    /**
     * Loads the default plugin options.
     *
     * @since 1.0.0
     *
     * @return array Array of default plugin options.
     */
    public static function default_options() {

        return array(
            'services' => array(
                'facebook'  => array(
                    'on'    => false,
                    'order' => 0
                ),
                'twitter'   => array(
                    'on'    => false,
                    'order' => 1
                ),
                'google'    => array(
                    'on'    => false,
                    'order' => 2
                ),
                'linkedin'  => array(
                    'on'    => false,
                    'order' => 3
                ),
                'pinterest' => array(
                    'on'    => false,
                    'order' => 4
                )
            ),
            'label'     => '',
            'show_on'   => array( 0 => 'post' ),
            'twitter'   => '',
            'transient' => 1800,
            'pinback'	=> '',
            'static'	=> 0,
            'position' 	=> 'above',
            'socialite' => 0
        );

    }

    /**
     * Builds the service sharing output for each enabled item.
     *
     * @since 1.0.0
     *
     * @global object $post The current post object.
     * @param string $service The sharing service to build code for.
     * @param int $count The number of shares for the service.
     * @return string $output HTML output of the sharing button.
     */
    public function get_service_output( $service = '', $count = 0 ) {

        global $post;

		$hide_count = 0 == $count ? ' fsb-hide-count' : '';
        $output 	= '<div class="fsb-share-' . $service . $hide_count . '">';
            switch ( $service ) {
                case 'facebook' :
                    $output .= '<a href="http://www.facebook.com/sharer.php?u=' . get_permalink( $post->ID ) . '" class="socialite facebook fsb-facebook" data-fsb-service="facebook" data-href="' . get_permalink( $post->ID ) . '" data-send="false" data-layout="button_count" data-width="60" data-show-faces="false" rel="nofollow" target="_blank"><span class="fsb-service-title">Facebook</span><span class="fsb-count">' . ( $count ? $count : 0 ) . '</span></a>';
                break;

                case 'twitter' :
                    $output .= '<a href="https://twitter.com/share?original_referer=' . urlencode( trailingslashit( get_home_url() ) ) . '&source=tweetbutton&text=' . urlencode( strip_tags( get_the_title( $post->ID ) ) ) . '&url=' .  urlencode( get_permalink( $post->ID ) ) . '&via=' . urlencode( $this->option['twitter'] ) . '" class="socialite twitter fsb-twitter" data-fsb-service="twitter" rel="nofollow" target="_blank" title="' . ( $count ? $count . __( ' retweets so far', 'fsb' ) : __( 'Be the first one to tweet this article!' ) ) . '"><span class="fsb-service-title">Twitter</span><span class="fsb-count">' . ( $count ? $count : 0 ) . '</span></a>';
                break;

                case 'google' :
                    $output .= '<a href="https://plus.google.com/share?url=' . get_permalink( $post->ID ) . '" class="socialite googleplus fsb-google" data-fsb-service="google" data-size="medium" data-href="' . get_permalink( $post->ID ) . '" rel="nofollow" target="_blank"><span class="fsb-service-title">Google+</span><span class="fsb-count">' . ( $count ? $count : 0 ) . '</span></a>';
                break;

                case 'linkedin' :
                    $output .= '<a href="https://www.linkedin.com/cws/share?url=' . get_permalink( $post->ID ) . '" class="socialite linkedin fsb-linkedin" data-fsb-service="linkedin" data-size="medium" data-href="' . get_permalink( $post->ID ) . '" rel="nofollow" target="_blank"><span class="fsb-service-title">LinkedIn</span><span class="fsb-count">' . ( $count ? $count : 0 ) . '</span></a>';
                break;

                case 'pinterest' :
                	// Attempt to get either featured image, first image from post or a default fallback.
                	$image = '';

                	if ( has_post_thumbnail( $post->ID ) ) {
	                	$data  = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
	                	$image = $data[0];
                	} else {
	                	// Try to find an image in the content.
	                	preg_match_all( '|<img.*?src=[\'"](.*?)[\'"].*?>|i', get_post_field( 'post_content', $post->ID ), $matches );

	                	if ( isset( $matches ) && ! empty( $matches[1][0] ) ) {
	                		$image = esc_url( $matches[1][0] );
	                	} else {
		                	if ( isset( $this->option['pinback'] ) && ! empty( $this->option['pinback'] ) )
		                		$image = esc_url( $this->option['pinback'] );
		                	else
		                		$image = ''; // We have exhausted every option.
	                	}
                	}

                    $output .= '<a href="http://pinterest.com/pin/create/button/?url=' . get_permalink( $post->ID ) . '&description=' . urlencode( strip_tags( get_the_title( $post->ID ) ) ) . '&media=' . $image . '" class="socialite pinit fsb-pinterest" data-fsb-service="pinterest" target="_blank" rel="nofollow"><span class="fsb-service-title">Pinterest</span><span class="fsb-count">' . ( $count ? $count : 0 ) . '</span></a>';
                break;
            }
        $output .= '</div>';

        // Return the output.
        return $output;

    }

    /**
     * Returns an array of available services to be used in the social bar.
     *
     * @since 1.0.6
     *
     * @return array Array of services to be used in the bar.
     */
    public function get_services() {

	    return array( 'facebook', 'twitter', 'google', 'linkedin', 'pinterest' );

    }

    /**
     * Sets the error message into the $errors property.
     *
     * @since 1.0.0
     */
    public function set_error( $id, $error ) {

        $this->errors[$id] = $error;

    }

}