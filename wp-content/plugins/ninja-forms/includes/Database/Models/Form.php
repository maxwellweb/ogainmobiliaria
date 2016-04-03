<?php if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class NF_Database_Models_Form
 */
final class NF_Database_Models_Form extends NF_Abstracts_Model
{
    protected $_type = 'form';

    protected $_table_name = 'nf3_forms';

    protected $_meta_table_name = 'nf3_form_meta';

    protected $_columns = array(
        'title',
        'key',
        'created_at'
    );

    protected $_fields;

    protected static $imported_form_id;

    public function __construct( $db, $id = '' )
    {
        add_action( 'ninja_forms_before_import_form', array( $this, 'import_form_backwards_compatibility' ) );
        parent::__construct( $db, $id );
    }

    public function delete()
    {
        parent::delete();

        $fields = Ninja_Forms()->form( $this->_id )->get_fields();

        foreach( $fields as $field ){
            $field->delete();
        }

        $actions = Ninja_Forms()->form( $this->_id )->get_actions();

        foreach( $actions as $action ){
            $action->delete();
        }
    }

    public static function get_next_sub_seq( $form_id )
    {
        $form = Ninja_Forms()->form( $form_id )->get();

        $last_seq_num = $form->get_setting( '_seq_num', 1 );

        $form->update_setting( '_seq_num', $last_seq_num + 1 )->save();

        return $last_seq_num;
    }

    public static function import( array $import, $id = '', $is_conversion )
    {
        $import = apply_filters( 'ninja_forms_before_import_form', $import );

        /*
        * Create Form
        */
        $form = Ninja_Forms()->form( $id )->get();
        $form->update_settings( $import[ 'settings' ] );
        $form->save();
        $form_id = $form->get_id();

        foreach( $import[ 'fields' ] as $settings ){

            if( $is_conversion ) {

                $field_id = $settings[ 'id' ];

                $field = Ninja_Forms()->form($form_id)->field( $field_id )->get();
            } else {
                $field = Ninja_Forms()->form($form_id)->field()->get();
            }

            $settings[ 'parent_id' ] = $form_id;

            $field->update_settings( $settings )->save();
        }

        foreach( $import[ 'actions' ] as $settings ){

            $action = Ninja_Forms()->form($form_id)->action()->get();

            $action->update_settings( $settings )->save();
        }

        self::$imported_form_id = $form_id;
        add_action( 'admin_notices', array( 'NF_Database_Models_Form', 'import_admin_notice' ) );
    }

    public static function import_admin_notice()
    {
        Ninja_Forms()->template( 'admin-notice-form-import.html.php', array( 'form_id'=> self::$imported_form_id ) );
    }

    public static function duplicate( $form_id )
    {
        $form = Ninja_Forms()->form( $form_id )->get();

        $settings = $form->get_settings();

        $new_form = Ninja_Forms()->form()->get();
        $new_form->update_settings( $settings );

        $form_title = $form->get_setting( 'title' );

        $new_form_title = $form_title . " - " . __( 'copy', 'ninja-forms' );

        $new_form->update_setting( 'title', $new_form_title );

        $new_form->update_setting( 'lock', 0 );

        $new_form->save();

        $new_form_id = $new_form->get_id();

        $fields = Ninja_Forms()->form( $form_id )->get_fields();

        foreach( $fields as $field ){

            $field_settings = $field->get_settings();

            $field_settings[ 'parent_id' ] = $new_form_id;

            $new_field = Ninja_Forms()->form( $new_form_id )->field()->get();
            $new_field->update_settings( $field_settings )->save();
        }

        $actions = Ninja_Forms()->form( $form_id )->get_actions();

        foreach( $actions as $action ){

            $action_settings = $action->get_settings();

            $new_action = Ninja_Forms()->form( $new_form_id )->action()->get();
            $new_action->update_settings( $action_settings )->save();
        }

        return $new_form_id;
    }

    public static function export( $form_id, $return = FALSE )
    {
        //TODO: Set Date Format from Plugin Settings
        $date_format = 'm/d/Y';

        $form = Ninja_Forms()->form( $form_id )->get();

        $export = array(
            'settings' => $form->get_settings(),
            'fields' => array(),
            'actions' => array()
        );

        $fields = Ninja_Forms()->form( $form_id )->get_fields();

        foreach( $fields as $field ){
            $export['fields'][] = $field->get_settings();
        }

        $actions = Ninja_Forms()->form( $form_id )->get_actions();

        foreach( $actions as $action ){
            $export[ 'actions' ][] = $action->get_settings();
        }

        if( $return ){
            return $export;
        } else {

            $today = date( $date_format, current_time( 'timestamp' ) );
            $filename = apply_filters( 'ninja_forms_form_export_filename', 'nf_form_' . $today );
            $filename = $filename . ".nff";

            header( 'Content-type: application/nff');
            header( 'Content-Disposition: attachment; filename="'.$filename .'"' );
            header( 'Pragma: no-cache');
            header( 'Expires: 0' );
            echo apply_filters( 'ninja_forms_form_export_bom',"\xEF\xBB\xBF" ) ; // Byte Order Mark
            echo base64_encode( maybe_serialize( $export ) );

            die();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Backwards Compatibility
    |--------------------------------------------------------------------------
    */

    public function import_form_backwards_compatibility( $import )
    {
        // Rename `data` to `settings`
        if( isset( $import[ 'data' ] ) ){
            $import[ 'settings' ] = $import[ 'data' ];
            unset( $import[ 'data' ] );
        }

        // Rename `notifications` to `actions`
        if( isset( $import[ 'notifications' ] ) ){
            $import[ 'actions' ] = $import[ 'notifications' ];
            unset( $import[ 'notifications' ] );
        }

        // Rename `form_title` to `title`
        if( isset( $import[ 'settings' ][ 'form_title' ] ) ){
            $import[ 'settings' ][ 'title' ] = $import[ 'settings' ][ 'form_title' ];
            unset( $import[ 'settings' ][ 'form_title' ] );
        }

        // Make sure
        if( ! isset( $import[ 'fields' ] ) ){
            $import[ 'fields' ] = array();
        }

        // `Field` to `Fields`
        if( isset( $import[ 'field' ] ) ){
            $import[ 'fields' ] = $import[ 'field' ];
            unset( $import[ 'field' ] );
        }

        $import = apply_filters( 'ninja_forms_upgrade_settings', $import );

        // Combine Field and Field Data
        foreach( $import[ 'fields' ] as $key => $field ){
            // TODO: Split Credit Card field into multiple fields.
            $field = $this->import_field_backwards_compatibility( $field );
            $import[ 'fields' ][ $key ] = $field;
        }

        $has_save_action = FALSE;
        foreach( $import[ 'actions' ] as $key => $action ){
            $action = $this->import_action_backwards_compatibility( $action );
            $import[ 'actions' ][ $key ] = $action;

            if( 'save' == $action[ 'type' ] ) $has_save_action = TRUE;
        }

        if( ! $has_save_action ) {
            $import[ 'actions' ][] = array(
                'type' => 'save',
                'label' => 'Save Form',
                'active' => TRUE
            );
        }

        $import = $this->import_merge_tags_backwards_compatibility( $import );

        return $import;
    }

    public function import_merge_tags_backwards_compatibility( $import )
    {
        $field_lookup = array();

        foreach( $import[ 'fields' ] as $key => $field ){
            $field_id  = $field[ 'id' ];
            $field_key = $field[ 'type' ] . '_' . $field_id;
            $field_lookup[ $field_id ] = $import[ 'fields' ][ $key ][ 'key' ] = $field_key;
        }

        foreach( $import[ 'actions' ] as $key => $action_settings ){
            foreach( $action_settings as $setting => $value ){
                foreach( $field_lookup as $field_id => $field_key ){

                    // Convert Tokenizer
                    $token = 'field_' . $field_id;
                    if( FALSE !== strpos( $value, $token ) ) {
                        $value = str_replace( $token, '{field:' . $field_key . '}', $value );
                    }

                    // Convert Shortcodes
                    $shortcode = "[ninja_forms_field id=$field_id]";
                    if( FALSE !== strpos( $value, $shortcode ) ){
                        $value = str_replace( $shortcode, '{field:' . $field_key . '}', $value );
                    }
                }

                if( FALSE !== strpos( $value, '[ninja_forms_all_fields]' ) ) {
                    $value = str_replace( '[ninja_forms_all_fields]', '{field:all_fields}', $value );
                }
                $action_settings[ $setting ] = $value;
                $import[ 'actions' ][ $key ] = $action_settings;
            }
        }

        return $import;
    }

    public function import_action_backwards_compatibility( $action )
    {
        // Remove `_` from type
        if( isset( $action[ 'type' ] ) ) {
            $action['type'] = str_replace('_', '', $action['type']);
        }

        // Convert `name` to `label`
        if( isset( $action[ 'name' ] ) ) {
            $action['label'] = $action['name'];
            unset($action['name']);
        }

        return apply_filters( 'ninja_forms_upgrade_action_' . $action[ 'type' ], $action );
    }

    public function import_field_backwards_compatibility( $field )
    {
        // Flatten field settings array
        if( isset( $field[ 'data' ] ) && is_array( $field[ 'data' ] ) ){
            $field = array_merge( $field, $field[ 'data' ] );
        }
        unset( $field[ 'data' ] );

        // Drop form_id in favor of parent_id, which is set by the form.
        if( isset( $field[ 'form_id' ] ) ){
            unset( $field[ 'form_id' ] );
        }

        // Remove `_` prefix from type setting
        $field[ 'type' ] = ltrim( $field[ 'type' ], '_' );

        // Type: `text` -> `textbox`
        if( 'text' == $field[ 'type' ] ){
            $field[ 'type' ] = 'textbox';
        }

        if( 'submit' == $field[ 'type' ] ){
            $field[ 'processing_label' ] = 'Processing';
        }

        if( 'calc' == $field[ 'type' ] ){
            $field[ 'type' ] = 'note';

            if( isset( $field[ 'calc_method' ] ) ) {

                switch( $field[ 'calc_method' ] ){
                    case 'eq':
                        $method = __( 'Equation (Advanced)', 'ninja-forms' );
                        break;
                    case 'fields':
                        $method = __( 'Operations and Fields (Advanced)', 'ninja-forms' );
                        break;
                    case 'auto':
                        $method = __( 'Auto-Total Fields', 'ninja-forms' );
                        break;
                    default:
                        $method = '';
                }
                $field['default'] = $method . "\r\n";

                if ('eq' == $field['calc_method'] && isset( $field['calc_eq'] ) ) {
                    $field['default'] .= $field['calc_eq'];
                }

                if ('fields' == $field['calc_method'] && isset( $field['calc'] ) ) {
                    // TODO: Support 'operations and fields (advanced)' calculations.
                }

                if ('auto' == $field['calc_method'] && isset( $field['calc'] ) ) {
                    // TODO: Support 'auto-totaling' calculations.
                }
            }

            unset( $field[ 'calc' ] );
            unset( $field[ 'calc_eq' ] );
            unset( $field[ 'calc_method' ] );
        }

        if( isset( $field[ 'email' ] ) ){

            if( 'textbox' == $field[ 'type' ] && $field[ 'email' ] ) {
                $field['type'] = 'email';
            }
            unset( $field[ 'email' ] );
        }

        if( isset( $field[ 'class' ] ) ){
            $field[ 'element_class' ] = $field[ 'class' ];
            unset( $field[ 'class' ] );
        }

        if( isset( $field[ 'req' ] ) ){
            $field[ 'required' ] = $field[ 'req' ];
            unset( $field[ 'req' ] );
        }

        if( isset( $field[ 'default_value_type' ] ) ){

            /* User Data */
            if( '_user_id' == $field[ 'default_value_type' ] )           $field[ 'default' ] = '{user:id}';
            if( '_user_email' == $field[ 'default_value_type' ] )        $field[ 'default' ] = '{user:email}';
            if( '_user_lastname' == $field[ 'default_value_type' ] )     $field[ 'default' ] = '{user:last_name}';
            if( '_user_firstname' == $field[ 'default_value_type' ] )    $field[ 'default' ] = '{user:first_name}';
            if( '_user_display_name' == $field[ 'default_value_type' ] ) $field[ 'default' ] = '{user:display_name}';

            /* Post Data */
            if( 'post_id' == $field[ 'default_value_type' ] )    $field[ 'default' ] = '{post:id}';
            if( 'post_url' == $field[ 'default_value_type' ] )   $field[ 'default' ] = '{post:url}';
            if( 'post_title' == $field[ 'default_value_type' ] ) $field[ 'default' ] = '{post:title}';

            /* System Data */
            if( 'today' == $field[ 'default_value_type' ] ) $field[ 'default' ] = '{system:date}';

            /* Miscellaneous */
            if( '_custom' == $field[ 'default_value_type' ] && isset( $field[ 'default_value' ] ) ){
                $field[ 'default' ] = $field[ 'default_value' ];
            }
            if( 'querystring' == $field[ 'default_value_type' ] && isset( $field[ 'default_value' ] ) ){
                $field[ 'default' ] = '{' . $field[ 'default_value' ] . '}';
            }

            unset( $field[ 'default_value' ] );
            unset( $field[ 'default_value_type' ] );
        }

        if( 'list' == $field[ 'type' ] ) {

            if ( isset( $field[ 'list_type' ] ) ) {

                if ('dropdown' == $field['list_type']) {
                    $field['type'] = 'listselect';
                }
                if ('radio' == $field['list_type']) {
                    $field['type'] = 'listradio';
                }
                if ('checkbox' == $field['list_type']) {
                    $field['type'] = 'listcheckbox';
                }
                if ('multi' == $field['list_type']) {
                    $field['type'] = 'listmultiselect';
                }
            }

            if( isset( $field[ 'list' ][ 'options' ] ) ) {
                $field[ 'options' ] = $field[ 'list' ][ 'options' ];
                unset( $field[ 'list' ][ 'options' ] );
            }
        }

        // Convert `textbox` to other field types
        foreach( array( 'fist_name', 'last_name', 'user_zip', 'user_city', 'user_phone', 'user_email', 'user_address_1', 'user_address_2', 'datepicker' ) as $item ) {
            if ( isset( $field[ $item ] ) && $field[ $item ] ) {
                $field[ 'type' ] = str_replace( array( '_', 'user', '1', '2', 'picker' ), '', $item );

                unset( $field[ $item ] );
            }
        }

        if( 'timed_submit' == $field[ 'type' ] ) {
            $field[ 'type' ] = 'submit';
        }

        if( 'checkbox' == $field[ 'type' ] ){

            if( isset( $field[ 'calc_value' ] ) ){

                if( isset( $field[ 'calc_value' ][ 'checked' ] ) ){
                    $field[ 'checked_calc_value' ] = $field[ 'calc_value' ][ 'checked' ];
                    unset( $field[ 'calc_value' ][ 'checked' ] );
                }
                if( isset( $field[ 'calc_value' ][ 'unchecked' ] ) ){
                    $field[ 'unchecked_calc_value' ] = $field[ 'calc_value' ][ 'unchecked' ];
                    unset( $field[ 'calc_value' ][ 'unchecked' ] );
                }
            }
        }

        if( 'rating' == $field[ 'type' ] ){
            $field[ 'type' ] = 'starrating';

            if( isset( $field[ 'rating_stars' ] ) ){
                $field[ 'default' ] = $field[ 'rating_stars' ];
                unset( $field[ 'rating_stars' ] );
            }
        }

        return $field;
    }

} // End NF_Database_Models_Form
