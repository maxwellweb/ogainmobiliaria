<?php if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class NF_Fields_Checkbox
 */
class NF_Fields_Checkbox extends NF_Abstracts_Input
{
    protected $_name = 'checkbox';

    protected $_nicename = 'Checkbox';

    protected $_section = 'common';

    protected $_type = 'checkbox';

    protected $_templates = 'checkbox';

    protected $_test_value = 0;

    protected $_settings =  array( 'checkbox_default_value', 'checked_calc_value', 'unchecked_calc_value' );

    protected $_settings_exclude = array( 'default', 'placeholder', 'input_limit_set' );

    public function __construct()
    {
        parent::__construct();

        $this->_nicename = __( 'Checkbox', 'ninja-forms' );

        $this->_settings[ 'label_pos' ][ 'value' ] = 'right';

        add_filter( 'ninja_forms_custom_columns', array( $this, 'custom_columns' ), 10, 2 );
    }

    public function admin_form_element( $id, $value )
    {
        $checked = ( $value ) ? "checked" : "";

        return "<input type='checkbox' name='fields[$id]' id='' $checked>";
    }

    public function custom_columns( $value, $field )
    {
        if( 'checkbox' == $field->get_setting( 'type' ) ){
            $value = ( $value ) ? __( 'checked', 'ninja-forms' ) : __( 'unchecked', 'ninja-forms' );
        }
        return $value;
    }
}
