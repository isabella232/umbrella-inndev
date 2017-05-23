<?php
/*
Plugin Name: Presspoint Opportunities
Plugin URI: http://presspointcrm.com
Description: The Opportunities Package for Presspoint.
Version: 2.1.5
Author: Presspoint
Author URI: http://presspointcrm.com
Copyright: 2009-2015 Frank Neville-Hamilton. All rights reserved.
Text Domain: paupress
Domain Path: languages

Presspoint and PauPress are commercially licensed software and you should have received a copy of the license agreement along with this download. If not, you may request a copy by emailing us@paupress.com.
*/



/* -----------------------------------------------------------
	SETUP, OPTIONS & ACTIONS
   ----------------------------------------------------------- */

/**
 * Security: Shut it down if the plugin is called directly.
 *
 * @since 0.0.0
 */
if ( !function_exists( 'add_action' ) ) {
	echo "hi there!  i'm just a plugin, not much i can do when called directly.";
	exit;
}


/**
 * Constants: Keep us oriented.
 *
 * @since 0.0.0
 */
define( 'PAUOPPS_VER', '2.1.5' );
define( 'PAUOPPS_URL', plugin_dir_url( __FILE__ ) );
define( 'PAUOPPS_DIR', plugin_dir_path( __FILE__ ) );
define( 'PAUOPPS_SLUG', plugin_basename( __FILE__ ) );


/**
 * Includes: Include the necessary supporting scripts.
 *
 * @since 0.0.0 
*/
require_once( 'updates.php' );
new PPPLUGINAPI( 'http://paupress.com/', 1868, plugin_basename( __FILE__ ), true );

include_once( 'options/pauopps-settings.php' );
include_once( 'posts/pauopps-post-actions.php' );
include_once( 'posts/pauopps-post-actions-meta.php' );
include_once( 'posts/pauopps-table.php' );
//include_once( 'posts/pauopps-table2.php' );
include_once( 'users/pauopps-user-actions.php' );
include_once( 'reports/pauopps-reports.php' );
include_once( 'utilities/pauopps-general.php' );

/**
 * Queue up the supporting files.
 *
 * @since 0.1
 */
function pauopps_events_init_register(){
	wp_register_script( 'poCalendarJS', PAUOPPS_URL . 'assets/pe-calendar.js', array( 'jquery' ), pauopps_VER, false );
	wp_register_style( 'peCalendarCSS', PAUOPPS_URL . 'assets/pe-calendar.css', array(), pauopps_VER, false );
}
function pauopps_events_enqueue(){
	wp_enqueue_script( 'peCalendarJS', PAUOPPS_URL . 'assets/pe-calendar.js', array( 'jquery' ), pauopps_VER, false );
	wp_enqueue_style( 'peCalendarCSS', PAUOPPS_URL . 'assets/pe-calendar.css', array(), pauopps_VER, false );
}	

/**
 * Load the textdomain.
 *
 * @since 0.0.0 
*/
function pauopps_textdomain() {
	load_plugin_textdomain( 'paupress', false, dirname( PAUOPPS_SLUG ) . '/languages/' ); 
	
	$pauopps_t_strings = array(
									'opportunity' => __( 'Opportunity', 'paupress' ), 
									'opportunities' => __( 'Opportunities', 'paupress' ),
	);
	$pauopps_t_strings = apply_filters( 'pauopps_t_strings', $pauopps_t_strings );
	
	foreach ( $pauopps_t_strings as $k => $v ) {
		$pauopps_t_reserves = array( 'ver', 'url', 'dir', 'slug' );
		if ( !in_array( $k, $pauopps_t_reserves ) )
			define( strtoupper( 'pauopps_' . $k ), $v ); 
	}
	
}


/**
 * Menu: Display the settings menu.
 *
 * @since 0.0.0 
*/
function pauopps_push_menu( $paupress_menu ) {
	
	// ENSURES PAUPRESS IS LOADED ON OUR EDIT SCREEN
	$paupress_menu['pauopps'] = 'post.php';
	
	// CREATE THE ADMINISTRATIVE MENU
	$paupress_menu['pauopps_list'] = add_submenu_page( 'paupress_options', __( 'Opportunities', 'paupress' ), __( 'Opportunities', 'paupress' ), 'edit_'.strtolower(PAUOPPS_OPPORTUNITIES), 'pauopps_list', 'pauopps_list' );
	//$paupress_menu['pauopps_tasks'] = add_menu_page( __( 'Tasks', 'paupress' ), __( 'Tasks', 'paupress' ), 'manage_options', 'pauopps_tasks', 'pauopps_tasks', PAUPRESS_URL.'/assets/g/event-menu.png' );
	
	// ADD THE SETTINGS PAGE TO OUR POST TYPE
	$paupress_menu['pauopps_settings'] = add_submenu_page( NULL, __( 'PauPress Opportunity Settings', 'paupress' ), __( 'PauPress Opportunity Settings', 'paupress' ), 'add_users', 'pauopps_settings', 'pauopps_options_form' );
	
	return $paupress_menu;
			
}


/**
 * Requisite actions.
 *
 * @since 0.0.0
 */
// ADD THE SETTINGS MENU AND QUEUE BASE FILES
add_action( 'plugins_loaded', 'pauopps_textdomain' );
add_filter( 'paupress_push_menu', 'pauopps_push_menu' );
//add_action( 'wp_enqueue_scripts', 'paupress_events_enqueue' );
add_filter( 'paupress_options_tabs', 'pauopps_options_tabs_global' );
add_filter( 'paupro_settings', 'pauopps_license_settings' );


// ADD THE FLUSH FILTER
//add_filter( 'paupress_flush_permalinks', 'pauopps_flush_permalinks', 10, 2 );

// POSTS
add_action( 'init', 'pauopps_post' );
add_action( 'init', 'pauopps_role' );
add_action( 'init', 'pauopps_restrict_redirect' );
add_action( 'paupanels_switch', 'pauopps_switch' );
add_filter( 'paupress_form_post_insert_pre', 'pauopps_form_post_insert_pre', 10, 3 );
add_filter( 'paupress_form_post_mail_pre', 'pauopps_form_post_mail_pre', 10, 3 );
add_filter( 'paupress_modify_action_meta', 'pauopps_modify_action_meta', 10, 2 );

//add_filter( 'paupress_get_user_actions_meta', 'pauopps_push_user_actions_meta' );

// MAIL
add_filter( 'paumail_post_types', 'pauopps_paumail_post_types' );
add_filter( 'paumail_post_options', 'pauopps_post_options', 10, 2 );
add_filter( 'paumail_ajax_mail_by_post', 'pauopps_ajax_mail_by_post' );

// UTILITIES
add_action( 'pp_form_builder_switch', 'pauopps_form_builder', 10, 2 );
add_filter( 'paupress_form_notify', 'pauopps_form_notify', 10, 2 );
//add_filter( 'query_vars', 'pauopps_query_vars' );
//add_filter( 'paupress_modify_action_meta', 'pauopps_geocoder', 10, 3 );

// REMOVE THE TAXONOMY FROM PUBLIC VIEW
//add_filter( 'paucontent_public_taxonomies', 'pauopps_public_types' );

// REPORTS
add_action( 'paupress_action_search_meta', 'pauopps_action_search_meta', 10, 3 );
add_filter( 'paupress_action_detail_html', 'pauopps_action_detail_html', 2, 10 );
add_filter( 'paupress_action_detail_val', 'pauopps_action_detail_val', 2, 10 );
add_filter( 'paupro_ptdu', 'pauopps_ptdu' );