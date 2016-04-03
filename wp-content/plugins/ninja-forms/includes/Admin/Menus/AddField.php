<?php if ( ! defined( 'ABSPATH' ) ) exit;

final class NF_Admin_Menus_AddField extends NF_Abstracts_Submenu
{
    public $parent_slug = 'ninja-forms';

    public $page_title = 'Add Field';

    public $priority = 9001;

    public function __construct()
    {
        parent::__construct();
    }

    public function display()
    {
        Ninja_Forms::template( 'admin-menu-add-field.html.php' );
        wp_enqueue_style( 'nf-builder', Ninja_Forms::$url . 'assets/css/builder.css' );
        wp_enqueue_style( 'qtip2', Ninja_Forms::$url . 'assets/css/jquery.qtip.css' );

        wp_enqueue_script( 'backbone-marionette', Ninja_Forms::$url . 'assets/js/lib/backbone.marionette.min.js', array( 'jquery', 'backbone' ) );
        wp_enqueue_script( 'backbone-radio', Ninja_Forms::$url . 'assets/js/lib/backbone.radio.min.js', array( 'jquery', 'backbone' ) );
        wp_enqueue_script( 'jquery-perfect-scrollbar', Ninja_Forms::$url . 'assets/js/lib/perfect-scrollbar.jquery.min.js', array( 'jquery' ) );
        wp_enqueue_script( 'jquery-hotkeys-new', Ninja_Forms::$url . 'assets/js/lib/jquery.hotkeys.js' );
        wp_enqueue_script( 'jquery-qtip2', Ninja_Forms::$url . 'assets/js/lib/jquery.qtip.js' );

        wp_enqueue_script( 'requirejs', Ninja_Forms::$url . 'assets/js/lib/require.js', array( 'jquery', 'backbone' ) );
        wp_enqueue_script( 'nf-builder', Ninja_Forms::$url . 'assets/js/builder/main.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-draggable', 'jquery-ui-droppable', 'jquery-ui-sortable' ) );

        wp_localize_script( 'nf-builder', 'nfAdmin', array( 'ajaxNonce' => wp_create_nonce( 'ninja_forms_ajax_nonce' ), 'requireBaseUrl' => Ninja_Forms::$url . 'assets/js/' ) );

    }

}
