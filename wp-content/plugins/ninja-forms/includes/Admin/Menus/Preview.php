<?php if ( ! defined( 'ABSPATH' ) ) exit;

final class NF_Admin_Menus_Preview extends NF_Abstracts_Submenu
{
    public $parent_slug = 'ninja-forms';

    public $page_title = 'Preview';

    public $priority = 9001;

    public function __construct()
    {
        parent::__construct();
    }

    public function display()
    {
        Ninja_Forms()->template( 'admin-menu-preview.html.php' );
    }

}
