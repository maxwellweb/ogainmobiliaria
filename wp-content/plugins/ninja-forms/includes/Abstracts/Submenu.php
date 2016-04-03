<?php if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * WordPress Menu Page Base Class
 */
abstract class NF_Abstracts_Submenu
{
    /**
     * (required) The slug name for the parent menu (or the file name of a standard WordPress admin page)
     *
     * @var string
     */
    public $parent_slug = '';

    /**
     * (required) The text to be displayed in the title tags of the page when the menu is selected
     *
     * @var string
     */
    public $page_title = '';

    /**
     * (required) The on-screen name text for the menu
     *
     * @var string
     */
    public $menu_title = '';

    /**
     * (required) The capability required for this menu to be displayed to the user.
     *
     * @var string
     */
    public $capability = 'manage_options';

    /**
     * (required) The slug name to refer to this menu by (should be unique for this menu).
     *
     * @var string
     */
    public $menu_slug = '';

    /**
     * (optional) The function that displays the page content for the menu page.
     *
     * @var string
     */
    public $function = 'display';

    public $priority = 10;

    /**
     * Constructor
     *
     * Translate text and add the 'admin_menu' action.
     */
    public function __construct()
    {
        if( ! $this->menu_title ) {
            $this->menu_title = $this->page_title;
        }

        if( ! $this->menu_slug ) {
            $this->menu_slug = 'nf-' . strtolower( preg_replace( '/[^A-Za-z0-9-]+/', '-', $this->menu_title ) );
        }

        $this->capability = apply_filters( 'submenu_' . $this->menu_slug . '_capability', $this->capability );

        add_action( 'admin_menu', array( $this, 'register' ), $this->priority );
    }

    /**
     * Register the menu page.
     */
    public function register()
    {
        $function = ( $this->function ) ? array( $this, $this->function ) : NULL;

        add_submenu_page(
            $this->parent_slug,
            $this->page_title,
            $this->menu_title,
            $this->capability,
            $this->menu_slug,
            $function
        );

        add_filter( 'admin_body_class', array( $this, 'body_class' ) );
    }

    public function body_class( $classes )
    {
        if( isset( $_GET['page'] ) && $_GET['page'] == $this->menu_slug ) {
            $classes = "$classes ninja-forms-app";
        }

        return $classes;
    }

    /**
     * Display the menu page.
     */
    public abstract function display();


}