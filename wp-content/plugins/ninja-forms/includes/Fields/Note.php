<?php if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class NF_Fields_Note
 */
class NF_Fields_Note extends NF_Fields_Hidden
{
    protected $_name = 'note';

    protected $_type = 'note';

    protected $_nicename = 'Note';

    protected $_section = '';

    protected $_templates = '';

    protected $_settings_only = array(
        'label', 'default'
    );

    public function __construct()
    {
        parent::__construct();

        $this->_settings[ 'label' ][ 'width' ] = 'full';
        $this->_settings[ 'default' ][ 'group' ] = 'primary';
        $this->_settings[ 'default' ][ 'type' ] = 'textarea';

        $this->_nicename = __( 'Note', 'ninja-forms' );

        add_filter( 'nf_sub_hidden_field_types', array( $this, 'hide_field_type' ) );
    }

    function hide_field_type( $field_types )
    {
        $field_types[] = $this->_name;
        return $field_types;
    }
}
