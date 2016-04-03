<?php if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class NF_Fields_MultiselectList
 */
class NF_Fields_ListMultiselect extends NF_Abstracts_List
{
    protected $_name = 'listmultiselect';

    protected $_type = 'listmultiselect';

    protected $_nicename = 'Mulit-Select';

    protected $_section = '';

    protected $_templates = 'listselect';

    protected $_old_classname = 'list-multiselect';

    public function __construct()
    {
        parent::__construct();

        $this->_nicename = __( 'Multi-Select', 'ninja-forms' );
    }

    public function admin_form_element( $id, $value )
    {
        $field = Ninja_Forms()->form()->get_field( $id );

        $options = '';
        foreach( $field->get_setting( 'options' ) as $option ){
            $selected = ( in_array( $option[ 'value' ], $value ) ) ? "selected" : '';
            $options .= "<option value='{$option[ 'value' ]}' $selected>{$option[ 'label' ]}</option>";
        }

        return "<select class='widefat' name='fields[$id]' id='' multiple>$options</select>";
    }
}
