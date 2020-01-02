<?php
/**
 * Casper Theme Customizer
 *
 * @package Casper
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function casper_customize_register( $wp_customize ) {
	/**
	 * Adds textarea support to the theme customizer
	 */
	class Casper_textarea_control extends WP_Customize_Control {
	    public $type = 'textarea';
	 
	    public function render_content() {
	        ?>
	            <label>
	                <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
	                <textarea rows="5" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
	            </label>
	        <?php
	    }
	}
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'background_color' )->transport = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'refresh';
	// Logo Controls
	$wp_customize->add_section( 'casper_logo_section' , array(
	    'title'       => __( 'Logo', 'casper' ),
	    'priority'    => 30,
	    'description' => 'Upload a logo to display above the site title on each page',
	) );
	$wp_customize->add_setting( 'casper_logo'  , array(
	    'transport'   => 'refresh'
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'casper_logo', array(
	    'label'    => __( 'Logo', 'casper' ),
	    'section'  => 'casper_logo_section',
	    'settings' => 'casper_logo',
	) ) );
	// Custom Controls
	$wp_customize->add_section(
	    'casper_custom',
	    array(
	        'title'     => 'Casper Options',
	        'priority'  => 200
	    )
	);
	// Theme header bg color
	$wp_customize->add_setting( 'casper_header_color' , array(
	    'default'     => '#303538',
	    'transport'   => 'postMessage'
	) );
	$wp_customize->add_control(
	    new WP_Customize_Color_Control(
	        $wp_customize,
	        'header_color',
	        array(
	            'label'      => __( 'Header Color', 'casper' ),
	            'section'    => 'colors',
	            'settings'   => 'casper_header_color'
	        )
	    )
	);
	// Home head text color
	$wp_customize->add_setting( 'casper_header_textcolor' , array(
	    'default'     => '#50585D',
	    'transport'   => 'postMessage'
	) );
	$wp_customize->add_control(
	    new WP_Customize_Color_Control(
	        $wp_customize,
	        'casper_header_textcolor',
	        array(
	            'label'      => __( 'Page Header Text Color', 'casper' ),
	            'section'    => 'colors',
	            'settings'   => 'casper_header_textcolor',
	        )
	    )
	);
	// Theme link color
	$wp_customize->add_setting( 'casper_link_color' , array(
	    'default'     => '#4a4a4a',
	    'transport'   => 'postMessage'
	) );
	$wp_customize->add_control(
	    new WP_Customize_Color_Control(
	        $wp_customize,
	        'link_color',
	        array(
	            'label'      => __( 'Link Color', 'casper' ),
	            'section'    => 'colors',
	            'settings'   => 'casper_link_color'
	        )
	    )
	);
	// Theme hover color
	$wp_customize->add_setting( 'casper_hover_color' , array(
	    'default'     => '#57A3E8',
	    'transport'   => 'refresh'
	) );
	$wp_customize->add_control(
	    new WP_Customize_Color_Control(
	        $wp_customize,
	        'hover_color',
	        array(
	            'label'      => __( 'Hover Color', 'casper' ),
	            'section'    => 'colors',
	            'settings'   => 'casper_hover_color'
	        )
	    )
	);
	// Home Menu color
	$wp_customize->add_setting( 'casper_home_menu_color' , array(
	    'default'     => '#ffffff',
	    'transport'   => 'refresh'
	) );
	$wp_customize->add_control(
	    new WP_Customize_Color_Control(
	        $wp_customize,
	        'home_menu_color',
	        array(
	            'label'      => __( 'Home Menu Color', 'casper' ),
	            'section'    => 'colors',
	            'settings'   => 'casper_home_menu_color'
	        )
	    )
	);
	// Menu color
	$wp_customize->add_setting( 'casper_menu_color' , array(
	    'default'     => '#50585D',
	    'transport'   => 'refresh'
	) );
	$wp_customize->add_control(
	    new WP_Customize_Color_Control(
	        $wp_customize,
	        'menu_color',
	        array(
	            'label'      => __( 'Menu Color', 'casper' ),
	            'section'    => 'colors',
	            'settings'   => 'casper_menu_color'
	        )
	    )
	);

	// Display header bg on all pages (vs home only)
	$wp_customize->add_setting(
	    'casper_display_header',
	    array(
	        'default'    =>  false,
	        'transport'  =>  'refresh'
	    )
	);
	$wp_customize->add_control(
	    'casper_display_header',
	    array(
	        'section'   => 'casper_custom',
	        'label'     => 'Only display header background on home page',
	        'type'      => 'checkbox'
	    )
	);
	// Display header on all pages (vs home only)
	$wp_customize->add_setting(
	    'casper_display_header_all',
	    array(
	        'default'    =>  false,
	        'transport'  =>  'refresh'
	    )
	);
	$wp_customize->add_control(
	    'casper_display_header_all',
	    array(
	        'section'   => 'casper_custom',
	        'label'     => 'Only display header on home page',
	        'type'      => 'checkbox'
	    )
	);
	// Circle logo
	$wp_customize->add_setting(
	    'casper_logo_circle',
	    array(
	        'default'    =>  false,
	        'transport'  =>  'postMessage'
	    )
	);
	$wp_customize->add_control(
	    'casper_logo_circle',
	    array(
	        'section'   => 'casper_custom',
	        'label'     => 'Make logo circular',
	        'type'      => 'checkbox'
	    )
	);
	// Frame logo
	$wp_customize->add_setting(
	    'casper_logo_frame',
	    array(
	        'default'    =>  false,
	        'transport'  =>  'postMessage'
	    )
	);
	$wp_customize->add_control(
	    'casper_logo_frame',
	    array(
	        'section'   => 'casper_custom',
	        'label'     => 'Frame logo image',
	        'type'      => 'checkbox'
	    )
	);
	// Custom meta
	$wp_customize->add_setting( 'casper_custom_meta' );
 
	$wp_customize->add_control(
	    new Casper_textarea_control(
	        $wp_customize,
	        'casper_custom_meta',
	        array(
	            'label' => 'Custom meta information (tags)',
	            'section' => 'casper_custom',
	            'settings' => 'casper_custom_meta'
	        )
	    )
	);
	$wp_customize->add_setting('casper_custom_footer', array('transport' => 'refresh'));
	$wp_customize->add_control('casper_custom_footer', array('section' => 'casper_custom', 'label' => 'Custom footer', 'type' => 'text'));

	/* ==========================================================================
    Social Icons
    ========================================================================== */

	$wp_customize->add_section(
	    'casper_social',
	    array(
	        'title'     => 'Social URLs',
	        'priority'  => 199
	    )
	);
	$wp_customize->add_setting('casper_social_dribbble', array('transport' => 'refresh'));
	$wp_customize->add_control('casper_social_dribbble', array('section' => 'casper_social', 'label' => 'Dribbble', 'type' => 'text'));
	$wp_customize->add_setting('casper_social_facebook', array('transport' => 'refresh'));
	$wp_customize->add_control('casper_social_facebook', array('section' => 'casper_social', 'label' => 'Facebook', 'type' => 'text'));
	$wp_customize->add_setting('casper_social_github', array('transport' => 'refresh'));
	$wp_customize->add_control('casper_social_github', array('section' => 'casper_social', 'label' => 'GitHub', 'type' => 'text'));
	$wp_customize->add_setting('casper_social_google', array('transport' => 'refresh'));
	$wp_customize->add_control('casper_social_google', array('section' => 'casper_social', 'label' => 'Google+', 'type' => 'text'));
	$wp_customize->add_setting('casper_social_linkedin', array('transport' => 'refresh'));
	$wp_customize->add_control('casper_social_linkedin', array('section' => 'casper_social', 'label' => 'LinkedIn', 'type' => 'text'));
	$wp_customize->add_setting('casper_social_mail', array('transport' => 'refresh'));
	$wp_customize->add_control('casper_social_mail', array('section' => 'casper_social', 'label' => 'Email', 'type' => 'text'));
	$wp_customize->add_setting('casper_social_tumblr', array('transport' => 'refresh'));
	$wp_customize->add_control('casper_social_tumblr', array('section' => 'casper_social', 'label' => 'Tumblr', 'type' => 'text'));
	$wp_customize->add_setting('casper_social_twitter', array('transport' => 'refresh'));
	$wp_customize->add_control('casper_social_twitter', array('section' => 'casper_social', 'label' => 'Twitter', 'type' => 'text'));
	$wp_customize->add_setting('casper_social_website', array('transport' => 'refresh'));
	$wp_customize->add_control('casper_social_website', array('section' => 'casper_social', 'label' => 'Website', 'type' => 'text'));
	$wp_customize->add_setting('casper_social_youtube', array('transport' => 'refresh'));
	$wp_customize->add_control('casper_social_youtube', array('section' => 'casper_social', 'label' => 'Youtube', 'type' => 'text'));
}
add_action( 'customize_register', 'casper_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function casper_customize_preview_js() {
	wp_enqueue_script( 'casper_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'jquery', 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'casper_customize_preview_js' );
