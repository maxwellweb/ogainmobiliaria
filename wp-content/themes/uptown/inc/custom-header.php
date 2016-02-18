<?php
/**
 * Sample implementation of the Custom Header feature
 * http://codex.wordpress.org/Custom_Headers
 *
 * @package upTown
 */

/**
 * Setup the WordPress core custom header feature.
 *
 * @uses uptown_header_style()
 * @uses uptown_admin_header_style()
 * @uses uptown_admin_header_image()
 *
 * @package upTown
 * @since 1.0
 */
 
add_action( 'after_setup_theme', 'uptown_custom_header_setup' );

function uptown_custom_header_setup() {

	add_theme_support( 'custom-header', apply_filters( 'uptown_custom_header_args', array(
		'default-image'          => get_stylesheet_directory_uri() . '/img/bg-subheader.png',
		'default-text-color'     => '#404040',
		'width'                  => 1280,
		'height'                 => 800,
		'flex-width'             => true,
		'flex-height'            => true,
		'wp-head-callback'       => 'uptown_header_style',
		'admin-head-callback'    => 'uptown_admin_header_style',
		'admin-preview-callback' => 'uptown_admin_header_image',
	) ) );

}


/**
 * Styles the header image and text displayed on the blog
 *
 * @uses get_header_image()
 * @uses header_image()
 * @uses get_header_textcolor()
 *
 * @see uptown_custom_header_setup()
 * @since 1.0
 */

if ( ! function_exists( 'uptown_header_style' ) ) {

	function uptown_header_style() {
	?>
		<style type="text/css">
	    <?php if ( get_header_image() ) : ?>
	        .site-subheader {
	            background: url(<?php header_image(); ?>);
	        }
		<?php endif; // End header image check. ?>
		</style>
	
	    <?php $header_text_color = get_header_textcolor();
	
		// If no custom options for text are set, let's bail
		// get_header_textcolor() options: HEADER_TEXTCOLOR is default, hide text (returns 'blank') or any hex value
		if ( HEADER_TEXTCOLOR == $header_text_color )
			return;
	
		// If we get this far, we have custom styles. Let's do this.
		?>
		<style type="text/css">
	    <?php if ( get_header_image() ) : ?>
	        .site-subheader {
	            background-image: url(<?php header_image(); ?>);
	        }
		<?php endif; // End header image check. ?>
		<?php
			// Has the text been hidden?
			if ( 'blank' == $header_text_color ) :
		?>
			.site-description {
				position: absolute;
				clip: rect(1px, 1px, 1px, 1px);
			}
		<?php
			// If the user has set a custom color for the text use that
			else :
		?>
			.site-description {
				color: #<?php echo $header_text_color; ?>;
			}
		<?php endif; ?>
		</style>
		<?php
	}

} // endif uptown_header_style


/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * @see uptown_custom_header_setup()
 * @since 1.0
 */

if ( ! function_exists( 'uptown_admin_header_style' ) ) {
 
	function uptown_admin_header_style() {
	?>
		<style type="text/css">
			.appearance_page_custom-header #headimg {
				border: none;
			}
		</style>
	<?php
	}

} // endif uptown_admin_header_style


/**
 * Custom header image markup displayed on the Appearance > Header admin panel.
 *
 * @uses get_header_image()
 * @uses header_image()
 *
 * @see uptown_custom_header_setup()
 * @since 1.0
 */

if ( ! function_exists( 'uptown_admin_header_image' ) ) {

	function uptown_admin_header_image() {
	?>
		<div id="headimg">
			<?php if ( get_header_image() ) : ?>
			<img src="<?php header_image(); ?>" alt="">
			<?php endif; ?>
		</div>
	<?php
	}

} // endif uptown_admin_header_image
