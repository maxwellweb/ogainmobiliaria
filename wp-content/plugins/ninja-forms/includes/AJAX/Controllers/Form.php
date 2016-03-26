<?php if ( ! defined( 'ABSPATH' ) ) exit;

class NF_AJAX_Controllers_Form extends NF_Abstracts_Controller
{
    public function __construct()
    {
        add_action( 'wp_ajax_nf_save_form',   array( $this, 'save' )   );
        add_action( 'wp_ajax_nf_delete_form', array( $this, 'delete' ) );
    }

    public function save()
    {
        check_ajax_referer( 'ninja_forms_ajax_nonce', 'security' );

        if( ! isset( $_POST[ 'form' ] ) ){
            $this->_errors[] = 'Form Not Found';
            $this->_respond();
        }

        $form_data = json_decode( stripslashes( $_POST['form'] ), ARRAY_A );

        if( is_string( $form_data[ 'id' ] ) ) {
            $tmp_id = $form_data[ 'id' ];
            $form = Ninja_Forms()->form()->get();
            $form->save();
            $form_data[ 'id' ] = $form->get_id();
            $this->_data[ 'new_ids' ][ 'forms' ][ $tmp_id ] = $form_data[ 'id' ];
        } else {
            $form = Ninja_Forms()->form($form_data['id'])->get();
        }

        $form->update_settings( $form_data[ 'settings' ] )->save();

        if( isset( $form_data[ 'fields' ] ) ) {
            foreach ($form_data['fields'] as $field_data) {

                $id = $field_data['id'];

                $field = Ninja_Forms()->form( $form_data[ 'id' ] )->get_field($id);

                $field->update_settings($field_data['settings'])->save();

                if ($field->get_tmp_id()) {

                    $tmp_id = $field->get_tmp_id();
                    $this->_data['new_ids']['fields'][$tmp_id] = $field->get_id();
                }

                $this->_data[ 'fields' ][ $id ] = $field->get_settings();
            }
        }

        if( isset( $form_data[ 'deleted_fields' ] ) ){

            foreach( $form_data[ 'deleted_fields' ] as  $deleted_field_id ){

                $field = Ninja_Forms()->form()->get_field( $deleted_field_id );
                $field->delete();
            }
        }

        if( isset( $form_data[ 'actions' ] ) ) {

            /*
             * Loop Actions and fire Save() hooks.
             */
            foreach ($form_data['actions'] as $action_data) {

                $id = $action_data['id'];

                $action = Ninja_Forms()->form( $form_data[ 'id' ] )->get_action( $id );

                $action->update_settings($action_data['settings'])->save();

                $action_type = $action->get_setting( 'type' );

                if( isset( Ninja_Forms()->actions[ $action_type ] ) ) {
                    $action_class = Ninja_Forms()->actions[ $action_type ];

                    $action_settings = $action_class->save( $action_data['settings'] );
                    if( $action_settings ){
                        $action_data['settings'] = $action_settings;
                        $action->update_settings( $action_settings )->save();
                    }
                }

                if ($action->get_tmp_id()) {

                    $tmp_id = $action->get_tmp_id();
                    $this->_data['new_ids']['actions'][$tmp_id] = $action->get_id();
                }

                $this->_data[ 'actions' ][ $id ] = $action->get_settings();
            }
        }

        /*
         * Loop Actions and fire Publish() hooks.
         */
        foreach ($form_data['actions'] as $action_data) {

            $action = Ninja_Forms()->form( $form_data[ 'id' ] )->get_action( $action_data['id'] );

            $action_type = $action->get_setting( 'type' );

            if( isset( Ninja_Forms()->actions[ $action_type ] ) ) {
                $action_class = Ninja_Forms()->actions[ $action_type ];

                if( $action->get_setting( 'active' ) && method_exists( $action_class, 'publish' ) ) {
                    $data = $action_class->publish( $this->_data );
                    if ($data) {
                        $this->_data = $data;
                    }
                }
            }
        }

        if( isset( $form_data[ 'deleted_actions' ] ) ){

            foreach( $form_data[ 'deleted_actions' ] as  $deleted_action_id ){

                $action = Ninja_Forms()->form()->get_action( $deleted_action_id );
                $action->delete();
            }
        }

        delete_user_option( get_current_user_id(), 'nf_form_preview_' . $form_data['id'] );

        $this->_respond();
    }

    public function delete()
    {
        check_ajax_referer( 'ninja_forms_ajax_nonce', 'security' );

        $this->_respond();
    }
}
