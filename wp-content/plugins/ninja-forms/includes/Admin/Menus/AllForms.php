<?php if ( ! defined( 'ABSPATH' ) ) exit;

final class NF_Admin_Menus_AllForms extends NF_Abstracts_Submenu
{
    public $parent_slug = 'ninja-forms';

    public $page_title = 'All Forms';

    public $menu_slug = 'admin.php?page=ninja-forms';

    public $priority = 1;

    public function __construct()
    {
        parent::__construct();
    }

    public function display()
    {
        // This section intentionally left blank.
    }

} // End Class NF_Admin_Settings
