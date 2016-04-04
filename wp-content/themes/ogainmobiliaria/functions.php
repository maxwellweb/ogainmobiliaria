<?php
/**
 * ogainmobiliaria functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package ogainmobiliaria
 */

if ( ! function_exists( 'ogainmobiliaria_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function ogainmobiliaria_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on ogainmobiliaria, use a find and replace
	 * to change 'ogainmobiliaria' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'ogainmobiliaria', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary', 'ogainmobiliaria' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See https://developer.wordpress.org/themes/functionality/post-formats/
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'ogainmobiliaria_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif;
add_action( 'after_setup_theme', 'ogainmobiliaria_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function ogainmobiliaria_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'ogainmobiliaria_content_width', 640 );
}
add_action( 'after_setup_theme', 'ogainmobiliaria_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function ogainmobiliaria_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'ogainmobiliaria' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'ogainmobiliaria_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function ogainmobiliaria_scripts() {
	wp_enqueue_style( 'ogainmobiliaria-style', get_template_directory_uri() . '/assets/css/main.min.css');


	wp_enqueue_script( 'ogainmobiliaria-skip-link-focus-fix', get_template_directory_uri() . '/assets/js/skip-link-focus-fix.js', array(), '20151215', true );
	wp_enqueue_script('ogainmobiliaria-listing-map', 'https://maps.googleapis.com/maps/api/js');

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'ogainmobiliaria_scripts' );

class Mi_menu_walker extends Walker_Nav_Menu
{
    public function start_el( &$output, $item, $depth, $args )
    {
        /*
         * Abrimos el elemento, por el momento si clases
         */ 

        $output .= '<li>';

        /*
         * Atributos del enlace
         */ 
        // Evitamos titulos redundantes
        ! empty ( $item->attr_title )
            and $item->attr_title !== $item->title
            and $attributes .= ' title="' . esc_attr( $item->attr_title ) .'"';

        ! empty ( $item->url )
            and $attributes .= ' href="' . esc_attr( $item->url ) .'"';

        //Mostramos el atributo rel
        ! empty( $item->xfn )  
            and $attributes .= ' rel="'    . esc_attr( $item->xfn        ) .'"';

        $attributes  = trim( $attributes );
        $title       = apply_filters( 'the_title', $item->title, $item->ID );

        $anchor = "{$args->before}<a {$attributes}>{$args->link_before}{$title}</a>"
                    . $args->link_after . $args->after;

        //Agregamos un filtro par si un plugin necesita acceso
        $output .= apply_filters(
            'walker_nav_menu_start_el'
            ,   $anchor
            ,   $item
            ,   $depth
            ,   $args
        );
    }

    function end_el( &$output )
    {
        $output .= '</li>';
    }
}

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';
