<?php if ( ! defined( 'ABSPATH' ) ) exit;

final class NF_Admin_Menus_Addons extends NF_Abstracts_Submenu
{
    public $parent_slug = 'ninja-forms';

    public $page_title = 'Add-Ons';

    public $priority = 13;

    public function __construct()
    {
        parent::__construct();
    }

    public function display()
    {
        wp_enqueue_style( 'nf-admin-addons', Ninja_Forms::$url . 'assets/css/admin-addons.css' );
        $items = wp_remote_get( 'https://ninjaforms.com/?extend_feed=jlhrbgf89734go7387o4g3h' );
        $items = wp_remote_retrieve_body( $items );
        $items = json_decode( $items, true );
        //shuffle( $items );

        $notices = array();

        foreach ($items as $item) {
            $plugin_data = array();
            if( !empty( $item['plugin'] ) && file_exists( WP_PLUGIN_DIR.'/'.$item['plugin'] ) ){
                $plugin_data = get_plugin_data( WP_PLUGIN_DIR.'/'.$item['plugin'], false, true );
            }

            $version = isset ( $plugin_data['Version'] ) ? $plugin_data['Version'] : '';

            if ( ! empty ( $version ) && $version < $item['version'] ) {

                $notices[] = array(
                    'title' => $item[ 'title' ],
                    'old_version' => $version,
                    'new_version' => $item[ 'version' ]
                );
            }
        }

        Ninja_Forms::template( 'admin-menu-addons.html.php', compact( 'items', 'notices' ) );
    }

} // End Class NF_Admin_Addons
