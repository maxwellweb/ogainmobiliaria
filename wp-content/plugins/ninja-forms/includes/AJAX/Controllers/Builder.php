<?php if ( ! defined( 'ABSPATH' ) ) exit;

class NF_AJAX_Controllers_Builder extends NF_Abstracts_Controller
{
    public function __construct()
    {
        add_action( 'wp_ajax_nf_builder',   array( $this, 'builder' )   );
    }

    public function builder()
    {
        check_ajax_referer( 'ninja_forms_ajax_nonce', 'builder' );

        if( ! isset( $_POST[ 'form' ] ) ){
            $this->_errors[] = 'Form Not Found';
            $this->_respond();
        }

        $form_id = $_POST[ 'form' ];

        $this->_form_data( $form_id );
        $this->_field_type_data();
        $this->_action_type_data();
        $this->_form_settings();
        $this->_merge_tags();

        $this->_respond();
    }

    private function _form_data( $form_id )
    {
        $form = Ninja_Forms()->form( $form_id )->get();
        $fields = Ninja_Forms()->form( $form_id )->get_fields();
        $actions = Ninja_Forms()->form( $form_id )->get_actions();

        $fields_settings = array();

        foreach( $fields as $field ){
            $settings = $field->get_settings();
            $settings[ 'id' ] = $field->get_id();

            foreach( $settings as $key => $setting ){
                if( is_numeric( $setting ) ) $settings[ $key ] = floatval( $setting );
            }

            $fields_settings[] = $settings;
        }

        $actions_settings = array();

        foreach( $actions as $action ){

            $settings = $action->get_settings();
            $settings[ 'id' ] = $action->get_id();

            $actions_settings[] = $settings;
        }

        $form_data = array();
        $form_data['id'] = $form_id;
        $form_data['settings'] = $form->get_settings();
        $form_data['fields'] = $fields_settings;
        $form_data['actions'] = $actions_settings;

        $this->_data[ 'preloadedFormData' ] = wp_json_encode( $form_data );
    }

    private function _field_type_data()
    {
        $field_type_sections = array_values( Ninja_Forms()->config( 'FieldTypeSections' ) );
        $field_type_settings = array();

        $master_settings = array();

        $setting_defaults = array();

        foreach( Ninja_Forms()->fields as $field ){

            $name = $field->get_name();
            $settings = $field->get_settings();
            $groups = Ninja_Forms::config( 'SettingsGroups' );

            $unique_settings = $this->_unique_settings( $settings );

            $master_settings = array_merge( $master_settings, $unique_settings );

            $settings_groups = $this->_group_settings( $settings, $groups );

            $settings_defaults = $this->_setting_defaults( $unique_settings );

            $field_type_settings[ $name ] = array(
                'id' =>  $name,
                'nicename' => $field->get_nicename(),
                'alias' => $field->get_aliases(),
                'parentType' => $field->get_parent_type(),
                'section' => $field->get_section(),
                'type' => $field->get_type(),
                'settingGroups' => $settings_groups,
                'settingDefaults' => $settings_defaults
            );
        }

        $this->_data[ 'fieldTypeData' ]     = wp_json_encode( $field_type_settings );
        $this->_data[ 'fieldSettings' ]     = wp_json_encode( array_values( $master_settings ) );
        $this->_data[ 'fieldTypeSections' ] = wp_json_encode( $field_type_sections );
    }

    private function _action_type_data()
    {
        $action_type_settings = array();

        $master_settings_list = array();

        foreach( Ninja_Forms()->actions as $action ){

            $name = $action->get_name();
            $settings = $action->get_settings();
            $groups = Ninja_Forms::config( 'SettingsGroups' );

            $settings_groups = $this->_group_settings( $settings, $groups );

            $master_settings_list = array_merge( $master_settings_list, $settings );

            $action_type_settings[ $name ] = array(
                'id' => $name,
                'section' => $action->get_section(),
                'nicename' => $action->get_nicename(),
                'image' => $action->get_image(),
                'settingGroups' => $settings_groups,
                'settingDefaults' => $this->_setting_defaults( $master_settings_list )
            );
        }

        $external_actions = $this->_fetch_action_feed();

        foreach( $external_actions as $action){

            if( ! isset( $action[ 'name' ] ) || ! $action[ 'name' ] ) continue;

            $name = $action[ 'name' ];
            $nicename = ( isset( $action[ 'nicename' ] ) ) ? $action[ 'nicename' ] : '';
            $image = ( isset( $action[ 'image' ] ) ) ? $action[ 'image' ] : '';
            $link = ( isset( $action[ 'link' ] ) ) ? $action[ 'link' ] : '';

            $action_type_settings[ $name ] = array(
                'id' => $name,
                'section' => 'available',
                'nicename' => $nicename,
                'image' => $image,
                'link' => $link,
                'settingGroups' => array(),
                'settingDefaults' => array()
            );
        }

        ?>
        <script>
            var actionTypeData = <?php echo wp_json_encode( $action_type_settings ); ?>;
            var actionSettings = <?php echo wp_json_encode( array_values( $master_settings_list ) ); ?>;
            // console.log( actionTypeData );
        </script>
        <?php
    }

    protected function _form_settings()
    {
        $form_settings_types = Ninja_Forms::config( 'FormSettingsTypes' );

        $form_settings[ 'display' ] = Ninja_Forms::config( 'FormDisplaySettings' );
        $form_settings[ 'restrictions' ] = Ninja_Forms::config( 'FormRestrictionSettings' );
        $form_settings = apply_filters( 'ninja_forms_localize_forms_settings', $form_settings );

        $groups = Ninja_Forms::config( 'SettingsGroups' );

        $master_settings = array();

        foreach( $form_settings_types as $id => $type ) {

            $unique_settings = $this->_unique_settings( $form_settings[ $id ] );
            $master_settings = array_merge( $master_settings, $unique_settings );

            $form_settings_types[ $id ]['settingGroups'] = $this->_group_settings($form_settings[ $id ], $groups);
            $form_settings_types[ $id ]['settingDefaults'] = $this->_setting_defaults($unique_settings);
        }
        ?>
        <script>
            var formSettingTypeData = <?php echo wp_json_encode( $form_settings_types )?>;
            var formSettings = <?php echo wp_json_encode( array_values( $master_settings ) )?>;
        </script>
        <?php
    }

    protected function _merge_tags()
    {
        $merge_tags = array(
            'fields' => array(
                'id'    => 'fields',
                'label' => __( 'Fields', 'ninja-forms' )
            )
        );

        foreach( Ninja_Forms()->merge_tags as $key => $group ){

            $merge_tags[ $key ] = array(
                'id'    => $group->get_id(),
                'label' => $group->get_title(),
                'tags'  => array_values( $group->get_merge_tags() )
            );
        }

        $this->_data[ 'merge_tags' ] = wp_json_encode( array_values( $merge_tags ) );
    }

    protected function _group_settings( $settings, $groups )
    {
        foreach( $settings as $setting ){

            $group = ( isset( $setting[ 'group' ] ) ) ? $setting[ 'group' ] : '';

            if( isset( $setting[ 'type'] ) && 'fieldset' == $setting[ 'type' ] ){
                $setting[ 'settings' ] = array_values( $setting[ 'settings' ] );
            }

            $groups[ $group ][ 'settings'][] = $setting;
        }

        foreach( $groups as $id => $group ) {
            if ( empty( $group[ 'settings' ] ) ) {
                unset( $groups[ $id ] );
            }
        }

        unset( $groups[ "" ] );

        return $groups;
    }

    protected function _unique_settings( $settings )
    {
        $unique_settings = array();

        foreach( $settings as $setting ){

            if( 'fieldset' == $setting[ 'type' ] ){

                $unique_settings = array_merge( $unique_settings, $this->_unique_settings( $setting[ 'settings' ] ) );
            } else {

                $name = $setting[ 'name' ];
                $unique_settings[ $name ] = $setting;
            }

        }

        return $unique_settings;
    }

    protected function _setting_defaults( $settings )
    {
        $setting_defaults = array();

        foreach( $settings as $setting ){

            $name = ( isset( $setting[ 'name' ] ) ) ? $setting[ 'name' ] : '';
            $default = ( isset( $setting[ 'value' ] ) ) ? $setting[ 'value' ] : '';
            $setting_defaults[ $name ] = $default;
        }

        return $setting_defaults;
    }

    protected function _fetch_action_feed()
    {
        $actions = get_transient( 'ninja-forms-builder-actions-feed' );

        $bust = ( isset( $_GET[ 'nf-bust-actions-feed' ] ) );

        if( $bust || ! $actions ) {
            $actions = wp_remote_get('https://ninjaforms.com/?action_feed=true');
            $actions = wp_remote_retrieve_body($actions);
            $actions = json_decode($actions, true);

            set_transient( 'ninja-forms-builder-actions-feed', $actions, WEEK_IN_SECONDS );
        }

        return $actions;
    }
}
