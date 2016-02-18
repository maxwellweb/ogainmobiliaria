<?php
/**
 * Custom helper functions for this theme.
 *
 * Here we define some helper functions
 * to make life easier. 
 *
 * @package upTown
 */


/**
 * Add some useful body classes
 *
 * @param array $classes Array of body classes
 * @uses is_page_template()
 * @return array $classes Array of body classes
 *
 * @since 1.0
 */
 
add_filter( 'body_class', 'uptown_body_class' );

function uptown_body_class( $classes ) {

	if( is_page_template( 'tpl-latest-posts.php' ) )
		$classes[] = 'archive';
		
	return $classes;

}


/**
 * Custom excerpt and more link
 *
 * @uses get_post_type()
 * @uses get_the_ID()
 * @uses __()
 * @uses get_permalink()
 * @uses apply_filters()
 * @return string $excerpt_more Read more link
 *
 * @since 1.0
 */
 
add_filter( 'excerpt_more', 'uptown_excerpt_more' );

function uptown_excerpt_more() {

	$more_text = get_post_type( get_the_ID() ) == 'post' ? __( 'Read more', 'uptown' ) : __( 'More info', 'uptown' );

	$excerpt_more = ' <a class="moretag" href="'. get_permalink( get_the_ID() ) . '">' . apply_filters( 'uptown_more_text', $more_text ) . '</a>';

	return apply_filters( 'uptown_excerpt_more', $excerpt_more );
	
}

/**
 * Echo custom excerpt
 *
 * @param integer $post_id Post ID
 * @param bool $excerpt Show WordPress excerpt or not
 * @param integer $length Length of the excerpt
 * @uses get_the_ID()
 * @uses get_post()
 * @uses get_the_content()
 * @uses preg_match()
 * @uses uptown_excerpt_more()
 * @uses apply_filters()
 * @uses the_excerpt()
 * @uses the_content()
 *
 * @since 1.0
 */

// Make function pluggable/overwritable
if ( ! function_exists( 'uptown_the_excerpt' ) ) {

	function uptown_the_excerpt( $post_id = '', $excerpt = false, $length = '' ) {	
		global $post, $more;

		$more = false;
	
		if( empty( $post_id ) )
		    $post_id = get_the_ID();
		
		// Get post object	
		$post = get_post( $post_id );
		
		/**
		 * If length parameter provided,
		 * create custom excerpt.
		 */
			
		if( ! empty( $length ) ) {
		
			// Clean up excerpt
			$output = trim( strip_tags( strip_shortcodes( $post->post_content ) ) );
			
			// Respect post excerpt
			if( ! empty( $post->post_excerpt ) )
				$output = trim( strip_tags( strip_shortcodes( $post->post_excerpt ) ) );
			
			// Stop if no content
			if( empty( $output ) )
				return;
			
			// Get post word count	
			$count = count( explode( ' ', $output ) );
			
			// Respect more tag
			
			if( strpos( $post->post_content, '<!--more-->' ) ) {
				$output = get_the_content( '', true );
			} else {		
				// Get excerpt depening on $length		
				preg_match( '/^\s*+(?:\S++\s*+){1,' . $length . '}/', $output, $matches );	  
				$output = $matches[0];
			}
			
			// If content longer than excerpt, display more	
			if( $length <= $count )
				$output .= uptown_excerpt_more();
			
			// Respect the_excerpt filter	
			$output = apply_filters( 'the_excerpt', $output );
			
			// Finally display custom excerpt
			echo $output;
			
			return;
		
		}
		
		/**
		 * Check if only the_excerpt or
		 * the_content with more.
		 */
			
		if( $excerpt == true || ! empty( $post->post_excerpt ) ) {
			the_excerpt();
		} else {	
			the_content( uptown_excerpt_more() );	
		}
	
	}

}


/**
 * Helper function to display
 * theme_mods CSS
 *
 * @param string $selector CSS selector
 * @param string $style CSS style
 * @param string $mod_name Key of the modification
 * @param string $prefix Prefix for CSS style
 * @param string $postfix Postfix for CSS style
 * @param bool $echo Return or echo
 * @param bool|string Opacity for rgba() colors
 * @uses uptown_hex2rgb()
 * @uses sprintf()
 *
 * @since 1.0.1
 */
 
function uptown_generate_css( $selector, $style, $mod_name, $prefix = '', $postfix = '', $echo = false, $opacity = false ) {

	$output = '';
	$mod = get_theme_mod( $mod_name );
	
	if ( ! empty( $mod ) ) {		
	
		if( $opacity !== false ) {
			$rgb = uptown_hex2rgb( $mod );
			$mod = 'rgba(' . $rgb . ',' . $opacity . ')';
		}
	
	   $output = "\n\t" . sprintf( '%s { %s:%s; }', $selector, $style, $prefix . $mod . $postfix ) . "\n";
	   
	   if ( $echo )
	      echo $output;
	}
	
	return $output;

}


/**
 * Helper function to convert
 * hex color in RGBA
 *
 * @param string $hex Hex color code
 * @uses str_replace()
 * @uses strlen()
 * @uses hexdec()
 * @uses substr()
 * @return string RGB color code
 *
 * @since 1.0.1
 */

function uptown_hex2rgb( $hex ) {

	$hex = str_replace( '#', '', $hex );
	
	if( strlen( $hex ) == 3 ) {
	
	   $r = hexdec( substr( $hex,0,1 ) . substr( $hex,0,1 ) );
	   $g = hexdec( substr( $hex,1,1 ) . substr( $hex,1,1 ) );
	   $b = hexdec( substr( $hex,2,1 ) . substr( $hex,2,1 ) );
	   
	} else {
	
	   $r = hexdec( substr( $hex,0,2 ) );
	   $g = hexdec( substr( $hex,2,2 ) );
	   $b = hexdec( substr( $hex,4,2 ) );

	}
	
	$rgb = array( $r, $g, $b );
	
	return implode( ',', $rgb );
}
