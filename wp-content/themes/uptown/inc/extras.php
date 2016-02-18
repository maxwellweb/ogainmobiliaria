<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package upTown
 */

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @param array $args Array of arguments
 * @return array $args Array of arguments
 *
 * @since 1.0
 */
 
add_filter( 'wp_page_menu_args', 'uptown_page_menu_args' );

function uptown_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}


/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 *
 * @param string $title WordPress title
 * @param string $sep WordPress title separator
 * @uses is_feed()
 * @uses strip_tags()
 * @uses html_entity_decode()
 * @uses get_bloginfo()
 * @uses is_home()
 * @uses is_front_page()
 * @uses sprintf()
 * @uses max()
 * @return string $title WordPress title
 *
 * @since 1.0
 */
 
add_filter( 'wp_title', 'uptown_wp_title', 10, 2 );

function uptown_wp_title( $title, $sep ) {

	global $page, $paged;

	if ( is_feed() )
		return $title;

	// Add the blog name
	$title .= strip_tags( html_entity_decode( get_bloginfo( 'name' ) ) );

	// Add the blog description for the home/front page.
	
	$site_description = get_bloginfo( 'description', 'display' );
	
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title .= " $sep $site_description";

	// Add a page number if necessary:
	
	if ( $paged >= 2 || $page >= 2 )
		$title .= " $sep " . sprintf( __( 'Page %s', 'uptown' ), max( $paged, $page ) );

	return $title;
}


/**
 * Remove <em> tags from admin title
 *
 * @param string $title WordPress admin title
 * @uses strip_tags()
 * @uses html_entity_decode()
 * @return string $title WordPress admin title
 *
 * @since 1.0
 */
 
add_filter( 'admin_title', 'uptown_wp_title_admin' );

function uptown_wp_title_admin( $title ) {
	return strip_tags( html_entity_decode( $title ) );
}


/**
 * Remove <em> tags from bloginfo name
 *
 * @param string $output Bloginfo name
 * @param string $show bloginfo() paramenter
 * @uses html_entity_decode()
 * @return string $output Bloginfo name
 *
 * @since 1.0
 */

add_filter( 'bloginfo', 'uptown_bloginfo', 10, 2 );

function uptown_bloginfo( $output, $show ) {

    if ( 'name' == $show )
    	$output = html_entity_decode( $output );

    return $output;
}


/**
 * Remove <em> tags from admin bar title
 *
 * @uses $wp_admin_bar->get_node()
 * @uses strip_tags()
 * @uses html_entity_decode()
 * @uses $wp_admin_bar->add_node()
 *
 * @since 1.0
 */
 
add_action( 'wp_before_admin_bar_render', 'uptown_admin_bar_name', 1 );

function uptown_admin_bar_name() {
	global $wp_admin_bar;

	$node = $wp_admin_bar->get_node( 'site-name' );
	
	$node->title = strip_tags( html_entity_decode( $node->title ) );
	
	$wp_admin_bar->add_node( $node );

}
