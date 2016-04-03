<?php if ( ! defined( 'ABSPATH' ) ) exit;

class NF_AJAX_Controllers_Submission extends NF_Abstracts_Controller
{
    protected $_form_data = array();

    protected $_form_id = '';

    public function __construct()
    {
        if( isset( $_POST['formData'] ) ) {
            $this->_form_data = json_decode( $_POST['formData'], TRUE  );

            // php5.2 fallback
            if( ! $this->_form_data ) $this->_form_data = json_decode( stripslashes( $_POST['formData'] ), TRUE  );
        }


        add_action( 'wp_ajax_nf_ajax_submit',   array( $this, 'submit' )  );
        add_action( 'wp_ajax_nopriv_nf_ajax_submit',   array( $this, 'submit' )  );

        add_action( 'wp_ajax_nf_ajax_resume',   array( $this, 'resume' )  );
        add_action( 'wp_ajax_nopriv_nf_ajax_resume',   array( $this, 'resume' )  );
    }

    public function submit()
    {
        check_ajax_referer( 'ninja_forms_ajax_nonce', 'security' );

        if( ! $this->_form_data ) {

            if( function_exists( 'json_last_error' ) // Function not supported in php5.2
                && function_exists( 'json_last_error_msg' )// Function not supported in php5.2
                && json_last_error() ){
                $this->_errors[] = json_last_error_msg();
            } else {
                $this->_errors[] = __( 'An unexpected error occurred.', 'ninja-forms' );
            }

            $this->_respond();
        }

        $this->_form_id = $this->_data[ 'form_id' ] = $this->_form_data['id'];

        $this->_data['settings'] = $this->_form_data['settings'];

        $this->_data['fields'] = $this->_form_data['fields'];

        $this->validate_fields();

        $this->process_fields();

        $this->process();
    }

    public function resume()
    {
        $this->_data = Ninja_Forms()->session()->get( 'nf_processing_data' );

        $this->_form_id = $this->_data[ 'form_id' ];

        unset( $this->_data[ 'halt' ] );

        $this->process();
    }

    protected function process()
    {
        $field_merge_tags = Ninja_Forms()->merge_tags[ 'fields' ];
        $this->populate_field_merge_tags( $this->_data['fields'], $field_merge_tags );

        if( isset( $this->_data[ 'settings' ][ 'calculations' ] ) ) {
            $calcs_merge_tags = Ninja_Forms()->merge_tags[ 'calcs' ];
            $this->populate_calcs_merge_tags( $this->_data[ 'settings' ][ 'calculations' ], $calcs_merge_tags );
        }

        if( isset( $this->_form_data[ 'settings' ][ 'is_preview' ] ) && $this->_form_data[ 'settings' ][ 'is_preview' ] ) {
            $this->run_actions_preview();
        } else {
            $this->run_actions();
        }

        $this->_respond();
    }

    protected function populate_field_merge_tags( $fields, $field_merge_tags )
    {
        foreach( $fields as $field ){

            $field_merge_tags->add_field( $field );
        }
    }

    protected function populate_calcs_merge_tags( $calcs, $calcs_merge_tags )
    {
        foreach( $calcs as $calc ){

            $calcs_merge_tags->set_merge_tags( $calc[ 'name' ], apply_filters( 'ninja_forms_calc_setting', $calc[ 'eq' ] ) );
        }
    }

    protected function validate_fields()
    {
        foreach( $this->_data['fields'] as $field ){

            $errors = $this->validate_field( $field, $this->_data );

            if( ! empty( $errors ) ){
                $this->_errors[ $field['id'] ] = $errors;
            }
        }
    }

    protected function validate_field( $field, $data )
    {
        $field_model = Ninja_Forms()->form()->field( $field['id'] )->get();

        $field = array_merge( $field, $field_model->get_settings() );

        $field_class = Ninja_Forms()->fields[ $field['type'] ];

        return $errors = $field_class->validate( $field, $data );
    }

    protected function process_fields()
    {
        foreach( $this->_data['fields'] as $field ){

            $data = $this->process_field( $field, $this->_data );

            if( ! empty( $data ) ){
                $this->_data = $data;
            }
        }
    }

    protected function process_field( $field, $data )
    {
        $field_model = Ninja_Forms()->form()->field( $field['id'] )->get();

        $field = array_merge( $field, $field_model->get_settings() );

        $field_class = Ninja_Forms()->fields[ $field['type'] ];

        return $field_class->process( $field, $data );
    }

    protected function run_actions()
    {
        $actions = Ninja_Forms()->form( $this->_form_id )->get_actions();

        foreach( $actions as $action ){

            $action_settings = apply_filters( 'ninja_forms_run_action_settings', $action->get_settings(), $this->_form_id, $action->get_id(), $this->_data['settings'] );

            if( isset( $this->_data[ 'processed_actions' ][ $action->get_id() ] ) ) continue;

            if( ! $action_settings['active'] ) continue;

            $type = $action_settings['type'];

            if( ! apply_filters( 'ninja_forms_run_action_type_' . $type, TRUE ) ) continue;

            $action_settings[ 'id' ] = $action->get_id();

            if( ! isset( Ninja_Forms()->actions[ $type ] ) ) continue;

            $data = Ninja_Forms()->actions[ $type ]->process( $action_settings, $this->_form_id, $this->_data );

            $this->_data = ( $data ) ? $data : $this->_data;

            $this->_data[ 'processed_actions' ][ $action->get_id() ] = $this->_data;

            $this->maybe_halt();
        }
    }

    protected function run_actions_preview()
    {
        $form = get_user_option( 'nf_form_preview_' . $this->_form_id );

        if( ! isset( $form[ 'actions' ] ) || empty( $form[ 'actions' ] ) ) return;

        foreach( $form[ 'actions' ] as $action ){

            $action_settings = apply_filters( 'ninja_forms_run_action_settings_preview', $action[ 'settings' ], $this->_form_id, '', $this->_data['settings'] );

            if( ! $action_settings['active'] ) continue;

            $type = $action_settings['type'];

            $data = Ninja_Forms()->actions[ $type ]->process( $action_settings, $this->_form_id, $this->_data );

            $this->_data = ( $data ) ? $data : $this->_data;

            $this->maybe_halt();
        }
    }

    protected function maybe_halt()
    {
        if( isset( $this->_data[ 'halt' ] ) && $this->_data[ 'halt' ] ){

            Ninja_Forms()->session()->set( 'nf_processing_data', $this->_data );

            $this->_respond();
        }
    }
}