<?php //if ( ! defined( 'ABSPATH' ) ) exit;
//
///**
// * Class NF_Fields_CountryList
// */
//class NF_Fields_ListCountry extends NF_Abstracts_List
//{
//    protected $_name = 'listcountry';
//
//    protected $_type = 'listcountry';
//
//    protected $_nicename = 'Country';
//
//    protected $_section = 'userinfo';
//
//    protected $_templates = array( 'listcountry', 'listselect' );
//
//    public function __construct()
//    {
//        parent::__construct();
//
//        $this->_nicename = __( 'Country', 'ninja-forms' );
//
//        $this->_settings[ 'options' ][ 'value' ] = $this->get_options();
//    }
//
//    private function get_options()
//    {
//        $order = 0;
//        $options = array();
//        foreach( Ninja_Forms()->config( 'CountryList' ) as $label => $value ){
//            $options[] = array(
//                'label'  => $label,
//                'value' => $value,
//                'calc' => '',
//                'selected' => 0,
//                'order' => $order
//            );
//
//            $order++;
//        }
//
//        return $options;
//    }
//}
