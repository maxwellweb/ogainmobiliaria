<?php if ( ! defined( 'ABSPATH' ) ) exit;

return apply_filters( 'ninja_forms_merge_tags_post', array(

    /*
    |--------------------------------------------------------------------------
    | Post ID
    |--------------------------------------------------------------------------
    */

    'id' => array(
        'id' => 'id',
        'tag' => '{post:id}',
        'label' => __( 'Post ID', 'ninja_forms' ),
        'callback' => 'post_id'
    ),

    /*
    |--------------------------------------------------------------------------
    | Post Title
    |--------------------------------------------------------------------------
    */

    'title' => array(
        'id' => 'title',
        'tag' => '{post:title}',
        'label' => __( 'Post Title', 'ninja_forms' ),
        'callback' => 'post_title'
    ),

    /*
    |--------------------------------------------------------------------------
    | Post URL
    |--------------------------------------------------------------------------
    */

    'url' => array(
        'id' => 'url',
        'tag' => '{post:url}',
        'label' => __( 'Post URL', 'ninja_forms' ),
        'callback' => 'post_url'
    ),

));