<?php
/*
Plugin Name: Ninja Forms
Plugin URI: http://ninjaforms.com/
Description: Ninja Forms is a webform builder with unparalleled ease of use and features.
Version: 2.9.38
Author: The WP Ninjas
Author URI: http://ninjaforms.com
Text Domain: ninja-forms
Domain Path: /lang/

Copyright 2015 WP Ninjas.
*/

require_once dirname( __FILE__ ) . '/lib/NF_VersionSwitcher.php';

if( version_compare( get_option( 'ninja_forms_version', '0.0.0' ), '3', '<' ) ) {
    update_option( 'ninja_forms_load_deprecated', TRUE );
}

if( get_option( 'ninja_forms_load_deprecated', FALSE )  && ! isset( $_POST[ 'nf2to3' ] ) ) {

    include 'deprecated/ninja-forms.php';

    register_activation_hook( __FILE__, 'ninja_forms_activation_deprecated' );
    function ninja_forms_activation_deprecated( $network_wide ){
        include_once 'deprecated/includes/activation.php';

        if( ! get_option( 'nf_aff', FALSE ) ) {
            update_option('ninja_forms_freemius', 1);
        }

        ninja_forms_activation( $network_wide );
    }

} else {

    add_action( 'wp_ajax_ninja_forms_ajax_migrate_database', 'ninja_forms_ajax_migrate_database' );
    function ninja_forms_ajax_migrate_database(){
        $migrations = new NF_Database_Migrations();
        $migrations->nuke( true, true );
        $migrations->migrate();
        echo json_encode( array( 'migrate' => 'true' ) );
        wp_die();
    }

    add_action( 'wp_ajax_ninja_forms_ajax_import_form', 'ninja_forms_ajax_import_form' );
    function ninja_forms_ajax_import_form(){
        $import = stripslashes( $_POST[ 'import' ] ); // TODO: How to sanitize serialized string?
        $form_id = ( isset( $_POST[ 'formID' ] ) ) ? absint( $_POST[ 'formID' ] ) : '';

        Ninja_Forms()->form()->import_form( $import, $form_id, TRUE );

        if( isset( $_POST[ 'flagged' ] ) && $_POST[ 'flagged' ] ){
            $form = Ninja_Forms()->form( $form_id )->get();
            $form->update_setting( 'lock', TRUE );
            $form->save();
        }


        echo json_encode( array( 'export' => $_POST[ 'import' ], 'import' => $import ) );
        wp_die();
    }

    add_action( 'wp_ajax_ninja_forms_ajax_import_fields', 'ninja_forms_ajax_import_fields' );
    function ninja_forms_ajax_import_fields(){
        $fields = stripslashes( $_POST[ 'fields' ] ); // TODO: How to sanitize serialized string?
        $fields = maybe_unserialize( $fields );

        foreach( $fields as $field ) {
            Ninja_Forms()->form()->import_field( $field, $field[ 'id' ], TRUE );
        }

        echo json_encode( array( 'export' => $_POST[ 'fields' ], 'import' => $fields ) );
        wp_die();
    }

    /**
     * Class Ninja_Forms
     */
    final class Ninja_Forms
    {

        /**
         * @since 3.0
         */
        const VERSION = '3.0';

        /**
         * @var Ninja_Forms
         * @since 2.7
         */
        private static $instance;

        /**
         * Plugin Directory
         *
         * @since 3.0
         * @var string $dir
         */
        public static $dir = '';

        /**
         * Plugin URL
         *
         * @since 3.0
         * @var string $url
         */
        public static $url = '';

        /**
         * Admin Menus
         *
         * @since 3.0
         * @var array
         */
        public $menus = array();

        /**
         * AJAX Controllers
         *
         * @since 3.0
         * @var array
         */
        public $controllers = array();

        /**
         * Form Fields
         *
         * @since 3.0
         * @var array
         */
        public $fields = array();

        /**
         * Form Actions
         *
         * @since 3.0
         * @var array
         */
        public $actions = array();

        /**
         * Model Factory
         *
         * @var object
         */
        public $factory = '';

        /**
         * Logger
         *
         * @var string
         */
        protected $_logger = '';

        /**
         * @var NF_Session
         */
        protected $session = '';

        /**
         * Plugin Settings
         *
         * @since 3.0
         * @var array
         */
        protected $settings = array();

        /**
         * Main Ninja_Forms Instance
         *
         * Insures that only one instance of Ninja_Forms exists in memory at any one
         * time. Also prevents needing to define globals all over the place.
         *
         * @since 2.7
         * @static
         * @staticvar array $instance
         * @return Ninja_Forms Highlander Instance
         */
        public static function instance()
        {
            if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Ninja_Forms ) ) {
                self::$instance = new Ninja_Forms;

                self::$dir = plugin_dir_path( __FILE__ );

                // Define old constants for backwards compatibility.
                if( ! defined( 'NF_PLUGIN_DIR' ) ){
                    define( 'NF_PLUGIN_DIR', self::$dir );
                    define( 'NINJA_FORMS_DIR', self::$dir );
                }

                self::$url = plugin_dir_url( __FILE__ );
                if( ! defined( 'NF_PLUGIN_URL' ) ){
                    define( 'NF_PLUGIN_URL', self::$url );
                }

                update_option( 'ninja_forms_version', self::VERSION );

                /*
                 * Register our autoloader
                 */
                spl_autoload_register( array( self::$instance, 'autoloader' ) );

                /*
                 * Admin Menus
                 */
                self::$instance->menus[ 'forms' ]           = new NF_Admin_Menus_Forms();
                self::$instance->menus[ 'all-forms' ]       = new NF_Admin_Menus_AllForms();
                self::$instance->menus[ 'add-new' ]         = new NF_Admin_Menus_AddNew();
                self::$instance->menus[ 'settings' ]        = new NF_Admin_Menus_Settings();
                self::$instance->menus[ 'add-ons' ]         = new NF_Admin_Menus_Addons();
                self::$instance->menus[ 'system_status']    = new NF_Admin_Menus_SystemStatus();
                self::$instance->menus[ 'submissions']      = new NF_Admin_Menus_Submissions();
                self::$instance->menus[ 'import-export']    = new NF_Admin_Menus_ImportExport();
                self::$instance->menus[ 'licenses']         = new NF_Admin_Menus_Licenses();

                /*
                 * Admin menus used for building out the admin UI
                 *
                 * TODO: removed once building is complete
                 */
                // self::$instance->menus[ 'add-field']        = new NF_Admin_Menus_AddField();
                // self::$instance->menus[ 'edit-field']       = new NF_Admin_Menus_EditField();
                // self::$instance->menus[ 'add-action']       = new NF_Admin_Menus_AddAction();
                // self::$instance->menus[ 'edit-action']      = new NF_Admin_Menus_EditAction();
                // self::$instance->menus[ 'edit-settings']    = new NF_Admin_Menus_EditSettings();
                // self::$instance->menus[ 'fields-layout']    = new NF_Admin_Menus_FieldsLayout();
                self::$instance->menus[ 'mock-data']        = new NF_Admin_Menus_MockData();
                // self::$instance->menus[ 'preview']          = new NF_Admin_Menus_Preview();

                /*
                 * AJAX Controllers
                 */
                self::$instance->controllers[ 'form' ]        = new NF_AJAX_Controllers_Form();
                self::$instance->controllers[ 'preview' ]     = new NF_AJAX_Controllers_Preview();
                self::$instance->controllers[ 'uploads' ]     = new NF_AJAX_Controllers_Uploads();
                self::$instance->controllers[ 'submission' ]  = new NF_AJAX_Controllers_Submission();
                self::$instance->controllers[ 'savedfields' ] = new NF_AJAX_Controllers_SavedFields();

                /*
                 * WP-CLI Commands
                 */
                if( class_exists( 'WP_CLI_Command' ) ) {
                    WP_CLI::add_command('ninja-forms', 'NF_WPCLI_NinjaFormsCommand');
                }

                /*
                 * Preview Page
                 */
                self::$instance->preview = new NF_Display_Preview();

                /*
                 * Shortcodes
                 */
                self::$instance->shortcodes = new NF_Display_Shortcodes();

                /*
                 * Temporary Shortcodes
                 *
                 * TODO: removed once building is complete
                 */
                require_once( self::$dir . 'includes/Display/Shortcodes/tmp-frontend.php' );
                require_once( self::$dir . 'includes/Display/Shortcodes/tmp-preview.php' );
                require_once( self::$dir . 'includes/Display/Shortcodes/tmp-frontendform.php' );
                require_once( self::$dir . 'includes/Display/Shortcodes/tmp-file-upload.php' );

                /*
                 * Submission CPT
                 */
                new NF_Admin_CPT_Submission();

                /*
                 * Logger
                 */
                self::$instance->_logger = new NF_Database_Logger();

                /*
                 * Merge Tags
                 */
                self::$instance->merge_tags[ 'user' ] = new NF_MergeTags_User();
                self::$instance->merge_tags[ 'post' ] = new NF_MergeTags_Post();
                self::$instance->merge_tags[ 'system' ] = new NF_MergeTags_System();
                self::$instance->merge_tags[ 'fields' ] = new NF_MergeTags_Fields();
                self::$instance->merge_tags[ 'calcs' ] = new NF_MergeTags_Calcs();
                self::$instance->merge_tags[ 'querystrings' ] = new NF_MergeTags_QueryStrings();

                /*
                 * Add Form Modal
                 */
                self::$instance->add_form_modal = new NF_Admin_AddFormModal();

                /*
                 * EOS Parser
                 */
                self::$instance->_eos[ 'parser' ] = require_once 'includes/Libraries/EOS/Parser.php';

                self::$instance->session = new NF_Session();

                /*
                 * Plugin Settings
                 */
                self::$instance->settings = get_option( 'ninja_forms_settings' );

                /*
                 * Admin Notices System
                 */
                self::$instance->notices = new NF_Admin_Notices();

                self::$instance->widgets[] = new NF_Widget();

                /*
                 * Activation Hook
                 * TODO: Move to a permanent home.
                 */
                register_activation_hook( __FILE__, array( self::$instance, 'activation' ) );

                new NF_Admin_Metaboxes_AppendAForm();

                /*
                 * Require EDD auto-update file
                 */
                if( ! class_exists( 'EDD_SL_Plugin_Updater' ) ) {
                    // Load our custom updater if it doesn't already exist
                    require_once( self::$dir . 'includes/Integrations/EDD/EDD_SL_Plugin_Updater.php');
                }
                require_once self::$dir . 'includes/Integrations/EDD/class-extension-updater.php';
            }

            add_action( 'admin_notices', array( self::$instance, 'admin_notices' ) );

            add_action( 'plugins_loaded', array( self::$instance, 'plugins_loaded' ) );

            return self::$instance;
        }

        public function admin_notices()
        {
            // Notices filter and run the notices function.
            $admin_notices = Ninja_Forms()->config( 'AdminNotices' );
            self::$instance->notices->admin_notice( apply_filters( 'nf_admin_notices', $admin_notices ) );
        }

        public function plugins_loaded()
        {
            /*
             * Field Class Registration
             */
            self::$instance->fields = apply_filters( 'ninja_forms_register_fields', self::load_classes( 'Fields' ) );

            /*
             * Form Action Registration
             */
            self::$instance->actions = apply_filters( 'ninja_forms_register_actions', self::load_classes( 'Actions' ) );

            /*
             * It's Ninja Time: Hook for Extensions
             */
            do_action( 'ninja_forms_loaded' );

        }

        /**
         * Autoloader
         *
         * Autoload Ninja Forms classes
         *
         * @param $class_name
         */
        public function autoloader( $class_name )
        {
            if( class_exists( $class_name ) ) return;

            /* Ninja Forms Prefix */
            if (false !== strpos($class_name, 'NF_')) {
                $class_name = str_replace('NF_', '', $class_name);
                $classes_dir = realpath(plugin_dir_path(__FILE__)) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR;
                $class_file = str_replace('_', DIRECTORY_SEPARATOR, $class_name) . '.php';
                if (file_exists($classes_dir . $class_file)) {
                    require_once $classes_dir . $class_file;
                }
            }

            /* WP Ninjas Prefix */
            if (false !== strpos($class_name, 'WPN_')) {
                $class_name = str_replace('WPN_', '', $class_name);
                $classes_dir = realpath(plugin_dir_path(__FILE__)) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR;
                $class_file = str_replace('_', DIRECTORY_SEPARATOR, $class_name) . '.php';
                if (file_exists($classes_dir . $class_file)) {
                    require_once $classes_dir . $class_file;
                }
            }
        }

        /*
         * PUBLIC API WRAPPERS
         */

        /**
         * Form Model Factory Wrapper
         *
         * @param $id
         * @return NF_Abstracts_ModelFactory
         */
        public function form( $id = '' )
        {
            global $wpdb;

            return new NF_Abstracts_ModelFactory( $wpdb, $id );
        }

        /**
         * Logger Class Wrapper
         *
         * Example Use:
         * Ninja_Forms()->logger()->log( 'debug', "Hello, {name}!", array( 'name' => 'world' ) );
         * Ninja_Forms()->logger()->debug( "Hello, {name}!", array( 'name' => 'world' ) );
         *
         * @return string
         */
        public function logger()
        {
            return $this->_logger;
        }

        public function eos()
        {
            return new Parser();
        }

        public function session()
        {
            return $this->session;
        }

	    /**
	     * Get a setting
	     *
	     * @param string     $key
	     * @param bool|false $default
	     * @return bool
	     */
        public function get_setting( $key = '', $default = false )
        {
            if( empty( $key ) || ! isset( $this->settings[ $key ] ) ) return $default;

            return $this->settings[ $key ];
        }

	    /**
	     * Get all the settings
	     *
	     * @return array
	     */
        public function get_settings()
        {
            return ( is_array( $this->settings ) ) ? $this->settings : array();
        }

	    /**
	     * Update a setting
	     *
	     * @param string           $key
	     * @param mixed           $value
	     * @param bool|false $defer_update Defer the database update of all settings
	     */
        public function update_setting( $key, $value, $defer_update = false )
        {
	        $this->settings[ $key ] = $value;
	        if ( ! $defer_update ) {
		        $this->update_settings();
	        }
        }

	    /**
	     * Save settings to database
	     *
	     * @param array $settings
	     */
        public function update_settings( $settings = array() )
        {
            if( ! is_array( $this->settings ) ) $this->settings = array( $this->settings );

            if( $settings && is_array( $settings ) ) {
                $this->settings = array_merge($this->settings, $settings);
            }

            update_option( 'ninja_forms_settings', $this->settings );
        }


        /**
         * Display Wrapper
         *
         * @param $form_id
         */
        public function display( $form_id, $preview = FALSE )
        {
            if( ! $form_id ) return;

            $noscript_message = __( 'Notice: JavaScript is required for this content.', 'ninja-forms' );
            $noscript_message = apply_filters( 'ninja_forms_noscript_message', $noscript_message );

            Ninja_Forms()->template( 'display-noscript-message.html.php', array( 'message' => $noscript_message ) );

            if( ! $preview ) {
                NF_Display_Render::localize($form_id);
            } else {
                NF_Display_Render::localize_preview($form_id);
            }
        }



        /*
         * PRIVATE METHODS
         */

        /**
         * Load Classes from Directory
         *
         * @param string $prefix
         * @return array
         */
        private static function load_classes( $prefix = '' )
        {
            $return = array();

            $subdirectory = str_replace( '_', DIRECTORY_SEPARATOR, str_replace( 'NF_', '', $prefix ) );

            $directory = 'includes/' . $subdirectory;

            foreach (scandir( self::$dir . $directory ) as $path) {

                $path = explode( DIRECTORY_SEPARATOR, str_replace( self::$dir, '', $path ) );
                $filename = str_replace( '.php', '', end( $path ) );

                $class_name = 'NF_' . $prefix . '_' . $filename;

                if( ! class_exists( $class_name ) ) continue;

                $return[ strtolower( $filename ) ] = new $class_name;
            }

            return $return;
        }



        /*
         * STATIC METHODS
         */

        /**
         * Template
         *
         * @param string $file_name
         * @param array $data
         */
        public static function template( $file_name = '', array $data = array() )
        {
            if( ! $file_name ) return;

            extract( $data );

            include self::$dir . 'includes/Templates/' . $file_name;
        }

        /**
         * Config
         *
         * @param $file_name
         * @return mixed
         */
        public static function config( $file_name )
        {
            return include self::$dir . 'includes/Config/' . $file_name . '.php';
        }

        /**
         * Activation
         */
        public function activation() {
            update_option( 'ninja_forms_freemius', 1 );
            $migrations = new NF_Database_Migrations();
            $migrations->migrate();
        }

    } // End Class Ninja_Forms



    /**
     * The main function responsible for returning The Highlander Ninja_Forms
     * Instance to functions everywhere.
     *
     * Use this function like you would a global variable, except without needing
     * to declare the global.
     *
     * Example: <?php $nf = Ninja_Forms(); ?>
     *
     * @since 2.7
     * @return Ninja_Forms Highlander Instance
     */
    function Ninja_Forms()
    {
        return Ninja_Forms::instance();
    }

    Ninja_Forms();

    /*
    |--------------------------------------------------------------------------
    | Uninstall Hook
    |--------------------------------------------------------------------------
    */

    if ( nf_is_freemius_on() ) {
        // Override plugin's version, should be executed before Freemius init.
        nf_override_plugin_version();
        // Init Freemius.
        nf_fs();
        nf_fs()->add_action( 'after_uninstall', 'ninja_forms_uninstall' );
    } else {
        register_uninstall_hook( __FILE__, 'ninja_forms_uninstall' );
    }

    function ninja_forms_uninstall(){

        if( Ninja_Forms()->get_setting( 'delete_on_uninstall ') ) {
            require_once plugin_dir_path(__FILE__) . '/includes/Database/Migrations.php';
            $migrations = new NF_Database_Migrations();
            $migrations->nuke(TRUE, TRUE);
        }
    }

}
