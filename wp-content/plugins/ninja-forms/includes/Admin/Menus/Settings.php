<?php if ( ! defined( 'ABSPATH' ) ) exit;

final class NF_Admin_Menus_Settings extends NF_Abstracts_Submenu
{
    public $parent_slug = 'ninja-forms';

    public $page_title = 'Settings';

    public $priority = 11;

    protected $_prefix = 'ninja_forms';

    public function __construct()
    {
        parent::__construct();

        if( isset( $_POST[ 'update_ninja_forms_settings' ] ) ) {
            $this->update_settings();
        }
    }

    public function display()
    {
        $tabs = apply_filters( 'ninja_forms_settings_tabs', array(
                'settings' => __( 'Settings', 'ninja-forms' ),
                'licenses' => __( 'Licenses', 'ninja-forms' )
            )
        );

        $tab_keys = array_keys( $tabs );
        $active_tab = ( isset( $_GET[ 'tab' ] ) ) ? $_GET[ 'tab' ] : reset( $tab_keys );

        wp_enqueue_style( 'nf-admin-settings', Ninja_Forms::$url . 'assets/css/admin-settings.css' );

        $groups = Ninja_Forms()->config( 'PluginSettingsGroups' );

        $grouped_settings = $this->get_settings();

        $save_button_text = __( 'Save Settings', 'ninja-forms' );

        $setting_defaults = Ninja_Forms()->get_settings();

        $errors = array();

        foreach( $grouped_settings as $group => $settings ){

            foreach( $settings as $id => $setting ){

                $value = ( isset( $setting_defaults[ $id ] ) ) ? $setting_defaults[$id] : '';

                $grouped_settings[$group][$id]['id'] = $this->prefix( $grouped_settings[$group][$id]['id'] );
                $grouped_settings[$group][$id]['value'] = $value;

                $grouped_settings[$group][$id] = apply_filters( 'ninja_forms_check_setting_' . $id, $grouped_settings[$group][$id] );

                if( ! isset( $grouped_settings[$group][$id][ 'errors' ] ) || ! $grouped_settings[$group][$id][ 'errors' ] ) continue;

                if( ! is_array( $grouped_settings[$group][$id][ 'errors' ] ) ) $grouped_settings[$group][$id][ 'errors' ] = array( $grouped_settings[$group][$id][ 'errors' ] );

                foreach( $grouped_settings[$group][$id][ 'errors' ] as $old_key => $error ){
                    $new_key = $grouped_settings[$group][$id][ 'id' ] . "[" . $old_key . "]";
                    $errors[ $new_key ] = $error;
                    $grouped_settings[$group][$id][ 'errors'][ $new_key ] = $error;
                    unset( $grouped_settings[$group][$id][ 'errors' ][ $old_key ] );
                }
            }
        }

        $grouped_settings[ 'general' ][ 'version' ][ 'value' ] = Ninja_Forms::VERSION;

        $saved_fields = Ninja_Forms()->form()->get_fields( array( 'saved' => 1 ) );

        foreach( $saved_fields as $saved_field ){

            $saved_field_id = $saved_field->get_id();

            $grouped_settings[ 'saved_fields'][] = array(
                'id' => '',
                'type' => 'html',
                'html' => '<a class="js-delete-saved-field button button-secondary" data-id="' . $saved_field_id . '">' . __( 'Delete' ) . '</a>',
                'label' => $saved_field->get_setting( 'label' ),

            );
        }

        if( $saved_fields ){
            add_action( 'admin_footer', array( $this, 'add_saved_field_javascript' ) );
        }

        Ninja_Forms::template( 'admin-menu-settings.html.php', compact( 'tabs', 'active_tab', 'groups', 'grouped_settings', 'save_button_text', 'errors' ) );

    }

    public function add_saved_field_javascript()
    {
        //TODO: Move this.
        ?>
        <script type="text/javascript" >
            var ajaxurl = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
            var nf_ajax_nonce = '<?php echo wp_create_nonce( "ninja_forms_ajax_nonce" ); ?>';

            jQuery(document).ready(function($) {
                $( '.js-delete-saved-field' ).click( function(){

                    var that = this;

                    var data = {
                        'action': 'nf_delete_saved_field',
                        'field': {
                            id: $( that ).data( 'id' )
                        },
                        'security': nf_ajax_nonce
                    };

                    $.post( ajaxurl, data )
                        .done( function( response ) {
                            $( that ).closest( 'tr').fadeOut().remove();
                        });
                });
            });
        </script>
        <?php
    }

    private function update_settings()
    {
        if( ! isset( $_POST[ $this->_prefix ] ) ) return;

        $settings = $_POST[ 'ninja_forms' ];

        foreach( $settings as $id => $value ){
            $value = sanitize_text_field( $value );
            $value = apply_filters( 'ninja_forms_update_setting_' . $id, $value );
            Ninja_Forms()->update_setting( $id, $value );
            do_action( 'ninja_forms_save_setting_' . $id, $value );
        }
    }

    private function get_settings()
    {
        return apply_filters( 'ninja_forms_plugin_settings', array(
            'general' => Ninja_Forms()->config( 'PluginSettingsGeneral' ),
            'recaptcha' => Ninja_Forms()->config( 'PluginSettingsReCaptcha' ),
            'advanced' => Ninja_Forms()->config( 'PluginSettingsAdvanced' ),
        ));
    }

    private function prefix( $value ){
        return "{$this->_prefix}[$value]";
    }

} // End Class NF_Admin_Settings
