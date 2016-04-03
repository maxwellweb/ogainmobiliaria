<?php

function nf_tmp_frontend( $atts = array() ) {
	ob_start();
	Ninja_Forms()->display( $atts['id'] );
	return ob_get_clean();
}

add_shortcode( 'nf_tmp_frontend', 'nf_tmp_frontend' );
