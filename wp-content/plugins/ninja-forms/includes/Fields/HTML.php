<?php if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class NF_Fields_HTML
 */
class NF_Fields_HTML extends NF_Abstracts_Input
{
    protected $_name = 'html';

    protected $_section = 'layout';

    protected $_aliases = array( 'html' );

    protected $_type = 'html';

    protected $_templates = 'html';

    protected $_settings_only = array( 'label', 'default', 'classes' );

    protected $_use_merge_tags_include = array( 'calculations' );

    public function __construct()
    {
        parent::__construct();

        $this->_settings[ 'label' ][ 'width' ] = 'full';
        $this->_settings[ 'default' ][ 'group' ] = 'primary';
        $this->_settings[ 'default' ][ 'type' ]  = 'rte';
        $this->_settings[ 'default' ][ 'use_merge_tags' ]  = array(
            'include' => array(
                'calcs'
            ),
        );

        $this->_nicename = __( 'HTML', 'ninja-forms' );
    }

}
