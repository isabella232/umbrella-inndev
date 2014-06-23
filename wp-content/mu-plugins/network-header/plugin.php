<?php

function render_network_header() {
	include dirname(__FILE__) . "/templates/header.php";
}

function enqueue_network_header_css() {
	wp_register_style('network-header', plugins_url('/css/network-header.css', __FILE__), NULL, "0.1");
	wp_enqueue_style('network-header');

	wp_register_script('network-header', plugins_url('/js/network-header.js', __FILE__), array('jquery'), "0.1");
	wp_enqueue_script('network-header');
}
add_action('wp_enqueue_scripts', 'enqueue_network_header_css');


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
