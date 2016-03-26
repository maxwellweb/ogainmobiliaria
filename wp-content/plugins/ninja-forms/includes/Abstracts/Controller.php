<?php if ( ! defined( 'ABSPATH' ) ) exit;

abstract class NF_Abstracts_Controller
{
    /**
     * Errors passed back to the client in the Reponse.
     *
     * @var array
     */
    protected $_errors = array();

    /**
     * Data (Misc.) passed back to the client in the Response.
     *
     * @var array
     */
    protected $_data = array();

    /*
     * PUBLIC METHODS
     */

    /**
     * NF_Abstracts_Controller constructor.
     */
    public function __construct()
    {
        //This section intentionally left blank.
    }


    /*
     * PROTECTED METHODS
     */

    /**
     * Respond
     *
     * A wrapper for the WordPress AJAX response pattern.
     */
    protected function _respond( $data = array() )
    {
        if( empty( $data ) ){
            $data = $this->_data;
        }

        $response = array( 'errors' => $this->_errors, 'data' => $data );

        echo wp_json_encode( $response );

        wp_die(); // this is required to terminate immediately and return a proper response
    }
}