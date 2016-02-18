<?php
/**
 * upTown Theme Customizer
 *
 * @package upTown
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param object $wp_customize WP_Customize_Manager Theme Customizer object
 * @uses $wp_customize->get_setting()
 *
 * @since 1.0
 */
 
add_action( 'customize_register', 'uptown_customize_register' );

function uptown_customize_register( $wp_customize ) {

	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

}


/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 *
 * @uses wp_enqueue_script()
 * @uses get_template_directory_uri()
 *
 * @since 1.0
 */
 
add_action( 'customize_preview_init', 'uptown_customize_preview_js' );

function uptown_customize_preview_js() {
	wp_enqueue_script( 'uptown_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20131017', true );
}


/**
 * Register custom customizer logo options
 *
 * @param object $wp_customize WP_Customize_Manager Theme Customizer object
 * @uses $wp_customize->add_setting()
 *
 * @since 1.0.1
 */

add_action( 'customize_register', 'uptown_customize_register_logo' );

function uptown_customize_register_logo( $wp_customize ) {
	
	// Add setting header right
	
	$wp_customize->add_setting(
		'header-right',
		array(
			'default' 			=> __( 'Call us today - 1 (800) 234 567', 'uptown' ),
			'type' 				=> 'option',
			'sanitize_callback' => 'sanitize_text_field'
		)
	);
	
	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'customize_header-right',
			array(
			    'label'    => __( 'Header Right', 'uptown' ),
			    'section'  => 'title_tagline',
			    'settings' => 'header-right'
			)
		)
	);

}


/**
 * Register custom subheader options
 *
 * @param object $wp_customize WP_Customize_Manager Theme Customizer object
 * @uses $wp_customize->add_section()
 *
 * @since 1.0.1
 */

add_action( 'customize_register', 'uptown_customize_register_subheader' );

function uptown_customize_register_subheader( $wp_customize ) {

	// Add section 'Subheader'
	
	$wp_customize->add_section(
		'uptown_subheader' , array(
	    	'title'      => __( 'Subheader', 'uptown' ),
	    	'priority'   => 30
		)
	);
	
	// Add subheader content
	
	$default  = '<div class="hero-title">Important Headline</div>' . "\n\n";
	
	$default .= '<div class="hero-text">' . "\n";
	$default .= "\t" . '<p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Donec sed odio dui. Donec sed odio dui. Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis in, egestas eget quam. <a href="#">Duis mollis</a>, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Donec sed odio dui. Donec sed odio dui. Cras mattis consectetur purus sit amet fermentum.</p>' . "\n";
	$default .= '</div>' . "\n\n";
	
	$default .= '<div class="hero-button">' . "\n";
	$default .= "\t" . '<a href="#" class="button">Call To Action</a>' . "\n";
	$default .= '</div>';
	
	$wp_customize->add_setting(
		'subheader',
		array(
			'default' 			=> $default,
			'type' 				=> 'option',
			'sanitize_callback' => 'wp_kses_post'
		)
	);
	
	$wp_customize->add_control(
		new upTown_Customize_Textarea_Control(
			$wp_customize,
			'customize_subheader',
			array(
			    'label'    => __( 'Content', 'uptown' ),
			    'section'  => 'uptown_subheader',
			    'settings' => 'subheader'
			)
		)
	);

}


/**
 * Register custom customizer color options
 *
 * @param object $wp_customize WP_Customize_Manager Theme Customizer object
 * @uses $wp_customize->add_setting()
 *
 * @since 1.0.1
 */

add_action( 'customize_register', 'uptown_customize_register_color' );

function uptown_customize_register_color( $wp_customize ) {
	
	// Add setting link color
	
	$wp_customize->add_setting(
		'accent_color',
		array(
			'default' 			=> '#43a97b',
			'type' 				=> 'theme_mod',
			'sanitize_callback' => 'sanitize_hex_color'
		)
	);
	
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'customize_link_color',
			array(
			    'label'    => __( 'Accent Color', 'uptown' ),
			    'section'  => 'colors',
			    'settings' => 'accent_color',
			)
		)
	);

}


/**
 * Add theme mods CSS from
 * theme options to header
 *
 * @uses uptown_generate_css()
 *
 * @since 1.0
 */
 
add_action( 'wp_head', 'uptown_do_theme_mods_css' );

function uptown_do_theme_mods_css() {

	$mods 		  = '';
	$accent_color = '';
	
	// Set accent color
	
	$accent_color .= uptown_generate_css( 'a, a:visited, a:hover, a:focus, a:active, .site-branding em, .entry-title h1 a:hover, .entry-title-meta span', 'color', 'accent_color' );
	$accent_color .= uptown_generate_css( '.main-navigation ul ul a:hover', 'color', 'accent_color', false, ' !important' );
	$accent_color .= uptown_generate_css( '.label, button, .button, html input[type="button"], input[type="reset"], input[type="submit"], button:hover, html input[type="button"]:hover, input[type="reset"]:hover, input[type="submit"]:hover, button:focus, html input[type="button"]:focus, input[type="reset"]:focus, input[type="submit"]:focus, button:active, html input[type="button"]:active, input[type="reset"]:active, input[type="submit"]:active, #wp-calendar caption', 'background-color', 'accent_color' );	
	$accent_color .= uptown_generate_css( '.blog .entry-image .overlay, .search .entry-image .overlay, .archive .entry-image .overlay', 'background-color', 'accent_color', false, false, false, '.75' );
	
	if( ! empty( $accent_color ) )
		$mods .= $accent_color;
	
	if( ! empty( $mods ) ) {	
	
		$css  = '<style type="text/css" media="screen">';
		$css .= $mods;
		$css .= '</style>' . "\n";
		
		echo $css;
		
	}

}


/**
 * Add upTown_Customize_Textarea_Control
 *
 * @uses esc_html()
 * @uses esc_textarea()
 *
 * @since 1.0.1
 */
 
add_action( 'customize_register', 'uptown_customize_register_class_textarea', 9 );

function uptown_customize_register_class_textarea() {

	class upTown_Customize_Textarea_Control extends WP_Customize_Control {
	
	    public $type = 'textarea';
	 
	    public function render_content() {
	        ?>
	        <label>
	        <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
	        <textarea rows="22" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
	        </label>
	        <?php
	    }
	}

}
