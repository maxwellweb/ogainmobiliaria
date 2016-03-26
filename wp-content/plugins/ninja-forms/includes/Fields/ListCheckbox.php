<?php if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class NF_Fields_CheckboxList
 */
class NF_Fields_ListCheckbox extends NF_Abstracts_List
{
    protected $_name = 'listcheckbox';

    protected $_type = 'listcheckbox';

    protected $_nicename = 'Checkbox List';

    protected $_section = '';

    protected $_templates = 'listcheckbox';

    protected $_old_classname = 'list-checkbox';

    public function __construct()
    {
        parent::__construct();

        $this->_nicename = __( 'Checkbox List', 'ninja-forms' );
    }

    public function admin_form_element( $id, $value )
    {
        $field = Ninja_Forms()->form()->get_field( $id );

        $list = '';
        foreach( $field->get_setting( 'options' ) as $option ){
            $checked = ( in_array( $option[ 'value' ], $value ) ) ? "checked" : '';
            $list .= "<li><label><input type='checkbox' value='{$option[ 'value' ]}' name='fields[$id][]' $checked> {$option[ 'label' ]}</label></li>";
        }

        return "<ul>$list</ul>";
    }
}
