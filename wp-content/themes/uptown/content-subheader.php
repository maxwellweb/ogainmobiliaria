<?php
/**
 * @package upTown
 */
 
// Get subheader content
$subheader = wp_kses_post( get_option( 'subheader' ) );

if( ! empty( $subheader ) ) {

	/**
	 * Uncomment the following line
	 * to only show the subheader
	 * on the front page (at bottom too!).
	 */
	// if( is_front_page() ) { ?>
	
	<div id="subheader-wrap" class="site-subheader-wrap wrap">
	
		<div id="subheader" class="site-subheader">
			
	    	<div class="hero">
	    	
	    		<?php echo do_shortcode( $subheader ); ?>
	    	
	    	</div>
	    
	    </div><!-- #subheader -->
	    
	</div><!-- #subheader-wrap --><?php
	
	/**
	 * Uncomment the following line
	 * to only show the subheader
	 * on the front page.
	 */
	 // } // endif is_front_page 
 
} // endif $subheader ?>
