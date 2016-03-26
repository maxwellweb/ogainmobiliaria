<?php if ( ! defined( 'ABSPATH' ) ) exit;

final class NF_Admin_Menus_EditSettings extends NF_Abstracts_Submenu
{
    public $parent_slug = 'ninja-forms';    

    public $page_title = 'Edit Settings';

    public $priority = 9001;

    public function __construct()
    {
        parent::__construct();
    }

    public function display()
    {
        Ninja_Forms::template( 'admin-menu-edit-settings.html.php' );
        wp_enqueue_style( 'nf-builder', Ninja_Forms::$url . 'assets/css/builder.css' );
    }

}
