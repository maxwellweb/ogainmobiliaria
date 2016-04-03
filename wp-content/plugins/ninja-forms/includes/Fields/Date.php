<?php if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class NF_Fields_Date
 */
class NF_Fields_Date extends NF_Fields_Textbox
{
    protected $_name = 'date';

    protected $_nicename = 'Date';

    protected $_section = 'common';

    protected $_type = 'date';

    protected $_templates = 'date';

    protected $_test_value = '12/12/2022';

    protected $_settings = array( 'date_default', 'date_format' );

    protected $_settings_exclude = array( 'default', 'placeholder', 'input_limit_set', 'disable_input' );

    public function __construct()
    {
        parent::__construct();

        $this->_nicename = __( 'Date', 'ninja-forms' );
    }

    public function process( $field, $data )
    {

        foreach( $data[ 'fields' ] as $key => $field ){

            if( 'date' != $field[ 'type' ] ) continue;
            if( ! isset( $field[ 'date_format' ] ) || ! $field[ 'date_format' ] ) continue;

            $format = $this->get_format( $field[ 'date_format' ] );
            $data[ 'fields' ][ $key ][ 'value' ] = date( $format, strtotime( $field[ 'value' ] ) );
        }

        return $data;
    }

    private function get_format( $format )
    {
        $lookup = array(
            'DD/MM/YYYY' => 'm/d/Y',
            'DD-MM-YYYY' => 'd-m-Y',
            'MM/DD/YYYY' => 'm/d/Y',
            'MM-DD-YYYY' => 'm-d-Y',
            'YYYY-MM-DD' => 'Y-m-d',
            'YYYY/MM/DD' => 'Y/m/d',
            'dddd, MMMM D YYYY' => 'l, F d Y'
        );

        return ( isset( $lookup[ $format ] ) ) ? $lookup[ $format ] : $format;
    }

}
