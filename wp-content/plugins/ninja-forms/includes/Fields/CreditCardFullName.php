<?php if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class NF_Fields_CreditCardFullName
 */
class NF_Fields_CreditCardFullName extends NF_Abstracts_Input
{
    protected $_name = 'creditcardfullname';
    protected $_type = 'creditcardfullname';

    protected $_section = '';

    protected $_templates = 'textbox';

    protected $_test_value = 'Tester T. Test';

    public function __construct()
    {
        parent::__construct();

        $this->_nicename = __( 'Credit Card Full Name', 'ninja-forms' );

        add_filter( 'nf_sub_hidden_field_types', array( $this, 'hide_field_type' ) );
    }

    function hide_field_type( $field_types )
    {
        $field_types[] = $this->_name;

        return $field_types;
    }
}
