<?php
/**
 * Handles the display and functionality of the theme settings page. This feature is merely a wrapper for the 
 * WordPress Settings API.  It creates the appropriate screen and HTML output for the settings page. This file 
 * is only loaded if the theme supports the 'omega-theme-settings' feature.
 *
 * Note that the use of this feature is discouraged.  Theme authors are recommended to use the WordPress 
 * Theme Customizer API instead.  However, there are scenarios where non-stylistic theme options are needed. 
 * That's the purpose of this feature.
 *
 * To register default settings, devs should use the `omega_default_theme_settings` filter hook.  To 
 * validate/sanitize data from custom settings, devs should use the `sanitize_option_omega_theme_settings` 
 * filter hook.  Use `appearance_page-theme-settings` as the `$page` ID when registering settings sections
 * and settings fields.
 */

/**
 * Creates a settings field id attribute for use on the theme settings page.  This is a helper function for use
 * with the WordPress settings API.
 *
 * @since  0.9.0
 * @param  string  $setting
 * @return string
 */
function omega_settings_field_id( $setting ) {
	return omega_get_prefix() . '_theme_settings-' . sanitize_html_class( $setting );
}

/**
 * Creates a settings field name attribute for use on the theme settings page.  This is a helper function for 
 * use with the WordPress settings API.
 *
 * @since  0.9.0
 * @param  string  $setting
 * @return string
 */
function omega_settings_field_name( $setting ) {
	return omega_get_prefix() . "_theme_settings[{$setting}]";
}

/**
 * Creates a theme settings page for the theme.
 *
 * @since  0.9.0
 * @access public
 */
final class Omega_Theme_Settings{

	/**
	 * Holds the instance of this class.
	 *
	 * @since  0.9.0
	 * @access private
	 * @var    object
	 */
	private static $instance;

	/**
	 * Holds the object returned from wp_get_theme().
	 *
	 * @since  0.9.0
	 * @access public
	 * @var    object
	 */
	public $theme;

	/**
	 * Theme prefix.  Defaults to the value of get_template().
	 *
	 * @since  0.9.0
	 * @access public
	 * @var    string
	 */
	public $prefix = '';

	/**
	 * Holds an array the theme settings.
	 *
	 * @since  0.9.0
	 * @access public
	 * @var    array
	 */
	public $settings = array();

	/**
	 * Settings page name.
	 *
	 * @since  0.9.0
	 * @access public
	 * @var    string
	 */
	public $settings_page = 'appearance_page_theme-settings';

	/**
	 * Sets up the theme admin.
	 *
	 * @since  0.9.0
	 * @access public
	 * @return void
	 */
	public function __construct() {

		/* Add theme settings to the admin menu. */
		add_action( 'admin_menu', array( $this, 'admin_menu' ), 5 );

		/* Get the theme object. */
		$this->theme = wp_get_theme( get_template() );

		/* Get the theme prefix. */
		$this->prefix = omega_get_prefix();
	}

	/**
	 * Adds the settings page to the admin menu.
	 *
	 * @since  0.9.0
	 * @access public
	 * @return void
	 */
	public function admin_menu() {

		/* Add the theme settings page. */
		$this->settings_page = add_theme_page( 
			/* Translators: %s is the theme name. */
			sprintf( esc_html__( 'Theme Settings', 'omega' ), $this->theme->display( 'Name', false, true ) ), 
			esc_html__( 'Theme Settings', 'omega' ), 
			'edit_theme_options', 
			'theme-settings', 
			array( $this, 'settings_page' )
		);

		/* If the theme settings page was created for the current user. */
		if ( !empty( $this->settings_page ) ) {

			/* Register the theme settings. */
			add_action( 'admin_init', array( $this, 'register_settings' ) );

			/* Add help tabs. */
			add_action( "load-{$this->settings_page}", array( $this, 'add_help_tabs' ), 5 );
		}
	}

	/**
	 * Registers the plugin settings and sets up the settings sections and fields.
	 *
	 * @since  0.9.0
	 * @access public
	 * @return void
	 */
	public function register_settings() {

		/* Register theme settings. */
		register_setting(
			"{$this->prefix}_theme_settings",    // Options group.
			"{$this->prefix}_theme_settings",    // Database option.
			array( $this, 'validate_settings' ) // Validation callback function.
		);

		/* Get the plugin settings from the database. */
		$this->settings = get_option(
			"{$this->prefix}_theme_settings", 
			omega_get_default_theme_settings()
		);
	}

	/**
	 * Validates the plugin settings once the form has been submitted.
	 *
	 * @since  0.9.0
	 * @access public
	 * @return void
	 */
	public function validate_settings( $settings ) {
		return $settings;
	}

	/**
	 * Displays the HTML and uses the required functions for creating the plugin settings page.
	 *
	 * @since  0.9.0
	 * @access public
	 * @return void
	 */
	public function settings_page() { ?>

		<div class="wrap">
			<?php screen_icon(); ?>

			<h2><?php
				/* Translators: %s is the theme name. */
				printf( __( 'Theme Settings', 'omega' ), $this->theme->display( 'Name', false, true ) ); 
			?>
			<a href="<?php echo admin_url( 'customize.php' ); ?>" class="add-new-h2"><?php esc_html_e( 'Customize', 'omega' ); ?></a>
			<?php do_action( 'omega_child_theme' ); // hence add ?>
			</h2>

			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php settings_fields( "{$this->prefix}_theme_settings" ); ?>
				<?php do_settings_sections( $this->settings_page ); ?>
				<?php do_action('omega_admin_setting'); ?>
				<?php submit_button( '', 'primary' ); ?>
			</form>

		</div><!-- wrap -->
	<?php }

	/**
	 * Adds help tabs to the settings screen.
	 *
	 * @since  0.9.0
	 * @access public
	 * @return void
	 */
	public function add_help_tabs() {

		get_current_screen()->add_help_tab(
			array(
				'id'      => 'default',
				'title'   => $this->theme->display( 'Name', false, true ),
				'content' => wpautop( $this->theme->display( 'Description', true, true ) ),
			)
		);
	}

	/**
	 * Returns the instance.
	 *
	 * @since  0.9.0
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		if ( !self::$instance )
			self::$instance = new self;

		return self::$instance;
	}
}

Omega_Theme_Settings::get_instance();
