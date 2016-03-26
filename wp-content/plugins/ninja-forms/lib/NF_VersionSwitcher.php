<?php

final class NF_VersionSwitcher
{
    public function __construct()
    {
        $this->listener();

        if( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            add_action('admin_bar_menu', array($this, 'admin_bar_menu'), 999);
        }
    }

    public function listener()
    {
        if( isset( $_GET[ 'nf-switcher' ] ) ){

            switch( $_GET[ 'nf-switcher' ] ){
                case 'upgrade':
                    update_option( 'ninja_forms_load_deprecated', FALSE );
                    nf_fs_upgrade();
                    break;
                case 'rollback':
                    update_option( 'ninja_forms_load_deprecated', TRUE );
                    nf_fs_downgrade();
                    break;
            }

            header( 'Location: ' . admin_url( 'admin.php?page=ninja-forms' ) );
        }
    }

    public function admin_bar_menu( $wp_admin_bar )
    {
        $args = array(
            'id'    => 'nf',
            'title' => 'Ninja Forms DEBUG',
            'href'  => '#',
        );
        $wp_admin_bar->add_node( $args );
        $args = array(
            'id' => 'nf_switcher',
            'href' => admin_url(),
            'parent' => 'nf'
        );
        if( ! get_option( 'ninja_forms_load_deprecated' ) ) {
            $args[ 'title' ] = 'DEBUG: Switch to 2.9.x';
            $args[ 'href' ] .= '?nf-switcher=rollback';
        } else {
            $args[ 'title' ] = 'DEBUG: Switch to 3.0.x';
            $args[ 'href' ] .= '?nf-switcher=upgrade';
        }
        $wp_admin_bar->add_node($args);
    }

}

new NF_VersionSwitcher();

/*
|--------------------------------------------------------------------------
| Freemius Integration
|--------------------------------------------------------------------------
*/

function nf_plugin_version( $version ) {
    return get_option( 'ninja_forms_version', '2.9' );
}

// Create a helper function for easy SDK access.
function nf_fs() {
    global $nf_fs;

    if ( ! isset( $nf_fs ) ) {
        // Include Freemius SDK.
        require_once dirname( __FILE__ ) . '/Freemius/start.php';

        $nf_fs = fs_dynamic_init( array(
            'slug'           => 'ninja-forms',
            'id'             => '209',
            'public_key'     => 'pk_f2f84038951d45fc8e4ff9747da6d',
            'is_premium'     => false,
            'has_addons'     => false,
            'has_paid_plans' => false,
            'menu'           => array(
                'slug'    => 'ninja-forms',
                'account' => false,
                'support' => false,
                'contact' => false,
            ),
        ) );
    }

    return $nf_fs;
}

function nf_is_freemius_on() {
    return get_option( 'ninja_forms_freemius', 0 );
}

function nf_override_plugin_version() {
    // Init Freemius and override plugin's version.
    if ( ! has_filter( 'fs_plugin_version_ninja-forms' ) ) {
        add_filter( 'fs_plugin_version_ninja-forms', 'nf_plugin_version' );
    }
}

function ninja_forms_actions() {
    if ( empty( $_POST['ninja_action'] ) || ! in_array( $_POST['ninja_action'], array(
            'upgrade',
            'downgrade',
            'opt_out'
        ) )
    ) {
        return;
    }

    switch ( $_POST['ninja_action'] ) {
        case 'upgrade':
            nf_fs_upgrade();
            break;
        case 'downgrade':
            nf_fs_downgrade();
            break;
        case 'opt_out':
            nf_fs_optout();
            break;
    }
}

function nf_fs_upgrade(){
    update_option( 'ninja_forms_version', '3.0' );

    nf_override_plugin_version();

    if ( ! nf_fs()->is_registered() && nf_fs()->has_api_connectivity() ) {
        if ( nf_fs()->opt_in() ) {
            // Successful opt-in into Freemius.

            // Turn Freemius on.
            update_option( 'ninja_forms_freemius', 1 );
        }
    } else if ( nf_fs()->is_registered() ) {
        // Send immediate re-upgrade event.
        nf_fs()->_run_sync_install();
    }
}

function nf_fs_downgrade(){
    update_option( 'ninja_forms_version', '2.9' );

    if ( ! nf_is_freemius_on() ) return;

    if ( nf_fs()->is_registered() ) {
        // Send immediate downgrade event.
        nf_fs()->_run_sync_install();
    }
}

function nf_fs_optout(){
    nf_fs()->delete_account_event();
    // Turn Freemius off.
    update_option( 'ninja_forms_freemius', 0 );
}

function nf_fs_custom_message( $message, $user_first_name, $plugin_title, $user_login, $site_link, $freemius_link ) {
    return sprintf(
        __fs( 'hey-x' ) . '<br>' .
        __( 'Please help us improve %2$s! If you opt-in, some data about your installation of %2$s will be sent to %5$s (this does NOT include your submissions). If you skip this, that\'s okay! %2$s will still work just fine.', 'ninja-forms' ),
        $user_first_name,
        '<b>' . $plugin_title . '</b>',
        '<b>' . $user_login . '</b>',
        $site_link,
        $freemius_link
    );
}

// Maybe Init Freemius
if ( nf_is_freemius_on() ) {
    // Override plugin's version, should be executed before Freemius init.
    nf_override_plugin_version();

    // Init Freemius.
    nf_fs();
    nf_fs()->add_filter('connect_message', 'nf_fs_custom_message', 10, 6);
    nf_fs()->add_filter('connect_message_on_update', 'nf_fs_custom_message', 10, 6);
}