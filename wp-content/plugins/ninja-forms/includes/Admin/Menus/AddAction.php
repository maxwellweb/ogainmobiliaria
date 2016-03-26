<?php if ( ! defined( 'ABSPATH' ) ) exit;

final class NF_Admin_Menus_AddAction extends NF_Abstracts_Submenu
{
    public $parent_slug = 'ninja-forms';    

    public $page_title = 'Add Action';

    public $priority = 9001;

    public function __construct()
    {
        parent::__construct();
    }

    public function display()
    {
        Ninja_Forms::template( 'admin-menu-add-action.html.php' );
        wp_enqueue_style( 'nf-builder', Ninja_Forms::$url . 'assets/css/builder.css' );
    }

}
