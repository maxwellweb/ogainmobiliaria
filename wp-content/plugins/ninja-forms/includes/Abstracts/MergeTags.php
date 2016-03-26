<?php if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class NF_Abstracts_MergeTags
 */
abstract class NF_Abstracts_MergeTags
{
    protected $id = '';

    protected $title = '';

    protected $merge_tags = array();

    protected $_default_group = TRUE;

    public function __construct()
    {
        add_filter( 'kbj_test', array( $this, 'replace' ) );

        add_filter( 'ninja_forms_render_default_value', array( $this, 'replace' ) );

        add_filter( 'ninja_forms_calc_setting',  array( $this, 'replace' ) );

        add_filter( 'ninja_forms_run_action_settings',  array( $this, 'replace' ) );
        add_filter( 'ninja_forms_run_action_settings_preview',  array( $this, 'replace' ) );

        add_filter( 'ninja_forms_run_action_settings',  array( $this, 'replace' ) );
        add_filter( 'ninja_forms_run_action_settings_preview',  array( $this, 'replace' ) );
    }

    public function replace( $subject )
    {
        foreach( $this->merge_tags as $merge_tag ){

            if( is_array( $subject ) ){

                foreach( $subject as $i => $s ){
                    $subject[ $i ] = $this->replace( $s );
                }
            } elseif( FALSE !== strpos( $subject, $merge_tag[ 'tag' ] ) ){

                $replace = ( is_callable( array( $this, $merge_tag[ 'callback' ] ) ) ) ? $this->{$merge_tag[ 'callback' ]}() : '';

                $subject = str_replace( $merge_tag[ 'tag' ], $replace, $subject );
            }
        }

        return $subject;
    }

    public function get_id()
    {
        return $this->id;
    }

    public function get_title()
    {
        return $this->title;
    }

    public function get_merge_tags()
    {
        return $this->merge_tags;
    }

    public function is_default_group()
    {
        return $this->_default_group;
    }


} // END CLASS NF_Abstracts_MergeTags
