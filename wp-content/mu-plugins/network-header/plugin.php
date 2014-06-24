<?php

if (!function_exists('largo_render_network_header')) {
	function largo_render_network_header($class=null) {
		include dirname(__FILE__) . "/templates/header.php";
	}
}

if (!function_exists('largo_render_network_header_menus')) {
	function largo_render_network_header_menus() {
		include dirname(__FILE__) . "/templates/menus.php";
	}
}

/* Include assets */
function enqueue_network_header_assets() {
	wp_register_style('network-header', WPMU_PLUGIN_URL . '/network-header/css/network-header.css', NULL, "0.1");
	wp_enqueue_style('network-header');

	wp_register_script('network-header', WPMU_PLUGIN_URL . '/network-header/js/network-header.js', array('jquery'), "0.1");
	wp_enqueue_script('network-header');
}
add_action('wp_enqueue_scripts', 'enqueue_network_header_assets');

/* Simple walker class modifies sub menu markup */
class Global_Nav_Walker extends Walker_Nav_Menu {
	function start_lvl(&$output, $depth=0, $args=array()) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<div class=\"network-header-sub-nav\"><ul>\n";
	}

	function end_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "$indent</ul></div>\n";
	}
}
