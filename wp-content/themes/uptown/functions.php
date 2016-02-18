<?php
/**
 * upTown functions and definitions
 *
 * @package upTown
 */

/**
 * Set the content width based on the theme's design and stylesheet
 *
 * @since 1.0
 */
if ( ! isset( $content_width ) )
	$content_width = 640; /* pixels */


/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * @since 1.0
 */

add_action( 'after_setup_theme', 'uptown_setup' );

if ( ! function_exists( 'uptown_setup' ) ) {

	function uptown_setup() {
	
		/**
		 * Make theme available for translation
		 * Translations can be filed in the /languages/ directory
		 * If you're building a theme based on upTown, use a find and replace
		 * to change 'uptown' to the name of your theme in all the template files
		 */
		load_theme_textdomain( 'uptown', get_template_directory() . '/languages' );
	
		/**
		 * Add default posts and comments RSS feed links to head
		 */
		add_theme_support( 'automatic-feed-links' );
		
		add_theme_support( 'title-tag' );
	
		/**
		 * Enable support for Post Thumbnails on posts and pages
		 *
		 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
		 */
		add_theme_support( 'post-thumbnails' );
	    
	    // Set post-thumbnail size
	    set_post_thumbnail_size( 300, 167, true );
	    
	    // Set custom image size 'big'
	    add_image_size( 'big', 640, 360, true );
	    
	    // Set custom image size 'full'
	    add_image_size( 'full', 980, 450, true );    
	
		/**
		 * This theme uses wp_nav_menu() in one location.
		 */
		register_nav_menus( array(
			'primary' => __( 'Primary Menu', 'uptown' ),
		) );
	
		/**
		 * Setup the WordPress core custom background feature.
		 */
		add_theme_support( 'custom-background', apply_filters( 'uptown_custom_background_args', array(
			'default-color' => 'e2e2e2',
			'default-image' => '',
		) ) );
		
		/**
		 * Add post editor style.
		 */
		add_editor_style( 'style-editor.css' );
	}

} // endif uptown_setup


/**
 * Register widgetized area and update sidebar with default widgets
 *
 * @since 1.0
 */

add_action( 'widgets_init', 'uptown_widgets_init' );
 
function uptown_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'uptown' ),
		'id'            => 'sidebar',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<div class="title"><h3 class="widget-title">',
		'after_title'   => '</h3></div>',
	) );
}


/**
 * Enqueue scripts and styles
 *
 * @since 1.0
 */
 
add_action( 'wp_enqueue_scripts', 'uptown_scripts' );

function uptown_scripts() {

	wp_enqueue_style( 'uptown-style', get_stylesheet_uri() );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	if ( is_singular() && wp_attachment_is_image() )
		wp_enqueue_script( 'uptown-keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
	
	wp_register_style( 'oswald-lora', 'http://fonts.googleapis.com/css?family=Oswald|Lora:400,700', false, '1.0', 'all' );
	wp_enqueue_style( 'oswald-lora');

}


/**
 * Implement helpful helper functions
 *
 * @since 1.0
 */
require get_template_directory() . '/inc/helpers.php';


/**
 * Implement the Custom Header feature
 *
 * @since 1.0
 */
require get_template_directory() . '/inc/custom-header.php';


/**
 * Custom template tags for this theme
 *
 * @since 1.0
 */
require get_template_directory() . '/inc/template-tags.php';


/**
 * Custom functions that act independently of the theme templates
 *
 * @since 1.0
 */
require get_template_directory() . '/inc/extras.php';


/**
 * Customizer additions
 *
 * @since 1.0
 */
require get_template_directory() . '/inc/customizer.php';
