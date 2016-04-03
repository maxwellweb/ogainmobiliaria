<?php
/*
Plugin Name: WPCasa Listings Map
Plugin URI: https://wpcasa.com/downloads/wpcasa-listings-map
Description: Show all listings as markers on a central Google Map using a shortcode.
Version: 1.0.0
Author: WPSight
Author URI: http://wpsight.com
Requires at least: 4.0
Tested up to: 4.3.1
Text Domain: wpcasa-listings-map
Domain Path: /languages

	Copyright: 2015 Simon Rimkus
	License: GNU General Public License v2.0 or later
	License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * WPSight_Listings_Map class
 */
class WPSight_Listings_Map {

	/**
	 *	Constructor
	 */
	public function __construct() {

		// Define constants
		
		if ( ! defined( 'WPSIGHT_NAME' ) )
			define( 'WPSIGHT_NAME', 'WPCasa' );

		if ( ! defined( 'WPSIGHT_DOMAIN' ) )
			define( 'WPSIGHT_DOMAIN', 'wpcasa' );

		define( 'WPSIGHT_LISTINGS_MAP_NAME', 'WPCasa Listings Map' );
		define( 'WPSIGHT_LISTINGS_MAP_DOMAIN', 'wpcasa-listings-map' );
		define( 'WPSIGHT_LISTINGS_MAP_VERSION', '1.0.0' );
		define( 'WPSIGHT_LISTINGS_MAP_PLUGIN_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
		define( 'WPSIGHT_LISTINGS_MAP_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );

		// Cookie constants

		define( 'WPSIGHT_LISTINGS_MAP_COOKIE', WPSIGHT_DOMAIN . '_listings_map' );

		// Include functions
		include 'wpcasa-listings-map-functions.php';

		// Include shortcode
		include 'includes/class-wpsight-listings-map-shortcode.php';
		
		// Include admin part
		
		if ( is_admin() ) {
			include( WPSIGHT_LISTINGS_MAP_PLUGIN_DIR . '/includes/admin/class-wpsight-listings-map-admin.php' );
			$this->admin = new WPSight_Listings_Map_Admin();
		}

		// Actions

		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );

	}

	/**
	 *	init()
	 *	
	 *	Initialize the plugin when WPCasa is loaded
	 *	
	 *	@param	object	$wpsight
	 *	@return	object	$wpsight->listings_map
	 *	
	 *	@since 1.0.0
	 */
	public static function init( $wpsight ) {
		
		if ( ! isset( $wpsight->listings_map ) )
			$wpsight->listings_map = new self();

		do_action_ref_array( 'wpsight_init_listings_map', array( &$wpsight ) );

		return $wpsight->listings_map;

	}

	/**
	 *	load_plugin_textdomain()
	 *	
	 *	Set up localization for this plugin
	 *	loading the text domain.
	 *	
	 *	@uses	load_plugin_textdomain()
	 *	
	 *	@since 1.0.0
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'wpcasa-listings-map', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 *	frontend_scripts()
	 *	
	 *	Register and enqueue JS scripts and CSS styles.
	 *	Also localize some JS to use PHP constants.
	 *	
	 *	@uses	wpsight_get_option()
	 *	@uses	wpsight_get_template_part()
	 *	@uses	wp_enqueue_script()
	 *	@uses	wp_enqueue_style()
	 *	@uses	wp_localize_script()
	 *	
	 *	@since 1.0.0
	 */
	public function frontend_scripts() {

		// Script debugging?
		$suffix = SCRIPT_DEBUG ? '' : '.min';

		if ( ! is_admin() ) {
			wp_enqueue_style( 'wpsight-listings-map', WPSIGHT_LISTINGS_MAP_PLUGIN_URL . '/assets/css/wpsight-listings-map' . $suffix . '.css' );
			wp_register_script( 'wpsight-map-googleapi', '//maps.googleapis.com/maps/api/js', null, WPSIGHT_LISTINGS_MAP_VERSION );
			wp_register_script( 'wpsight-map-infobox', '//google-maps-utility-library-v3.googlecode.com/svn/trunk/infobox/src/infobox.js', array( 'wpsight-map-googleapi' ), WPSIGHT_LISTINGS_MAP_VERSION );
			wp_register_script( 'wpsight-map-frontend', WPSIGHT_LISTINGS_MAP_PLUGIN_URL . '/assets/js/wpcasa-listings-map' . $suffix . '.js', array( 'wpsight-map-googleapi', 'wpsight-map-infobox' ), WPSIGHT_LISTINGS_MAP_VERSION );
		}

	}

	/**
	 *	activation()
	 *	
	 *	Callback for register_activation_hook
	 *	to create a default favorites page with
	 *	the [wpsight_favorites] shortcode and
	 *	to create some default options to be
	 *	used by this plugin.
	 *	
	 *	@uses	wpsight_get_option()
	 *	@uses	wp_insert_post()
	 *	@uses	wpsight_add_option()
	 *	
	 *	@since 1.0.0
	 */
	public static function activation() {

		// Create favorites page

		$page_data = array(
			'post_title'		=> _x( 'Listings Map', 'listings map page title', 'wpsight-listings-map' ),
			'post_content'		=> '[wpsight_listings_map]',
			'post_type'			=> 'page',
			'post_status'		=> 'publish',
			'comment_status'	=> 'closed',
			'ping_status'		=> 'closed'
		);

		$page_id = ! wpsight_get_option( 'listings_map_page' ) ? wp_insert_post( $page_data ) : wpsight_get_option( 'listings_map_page' );

		// Add some default options

		$options = array(
			'listings_map_page'			=> $page_id,
			'listings_map_nr'			=> 50,
			'listings_map_width'		=> '100%',
			'listings_map_height'		=> '800px',
			'listings_map_type'			=> 'ROADMAP',
			'listings_map_control_type'	=> '1',
			'listings_map_control_nav'	=> '1',
			'listings_map_scrollwheel'	=> '0',
			'listings_map_streetview'	=> '1'
		);

		foreach ( $options as $option => $value ) {

			if ( wpsight_get_option( $option ) )
				continue;

			wpsight_add_option( $option, $value );

		}

	}

}

// Activation Hook
register_activation_hook( __FILE__, array( 'WPSight_Listings_Map', 'activation' ) );

// Initialize plugin on wpsight_init
add_action( 'wpsight_init', array( 'WPSight_Listings_Map', 'init' ) );
