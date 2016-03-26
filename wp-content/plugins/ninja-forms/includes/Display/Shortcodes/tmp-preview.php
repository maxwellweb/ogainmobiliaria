<?php

function nf_tmp_preview( $atts = array() ) {
    ob_start();
    Ninja_Forms()->display( $atts['id'], TRUE );
    return ob_get_clean();
}

add_shortcode( 'nf_tmp_preview', 'nf_tmp_preview' );
