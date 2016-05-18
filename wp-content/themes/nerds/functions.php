<?php

// Largo metabox API
require_once( get_template_directory() . '/largo-apis.php' );


// Constants
define( 'SHOW_GLOBAL_NAV', FALSE );


// Includes
$includes = array(
	'/inc/widgets.php',
);
foreach ( $includes as $include ) {
	require_once( get_stylesheet_directory() . $include );
}


// Add network header and footer
add_action( 'largo_before_sticky_nav_container', 'largo_render_network_header' );
add_action( 'largo_before_footer_boilerplate', 'largo_render_network_footer' );


// Enqueue custom js
function nerds_enqueue() {
	wp_enqueue_script(
		'nerdCore',
		get_stylesheet_directory_uri() . "/js/nerdCore.js",
		array( 'jquery' ),
		false,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'nerds_enqueue', 20 );


// Typekit
function inn_head() { ?>
	<script src="//use.typekit.net/cui8tby.js"></script>
	<script>try{Typekit.load();}catch(e){}</script>
	<link rel="author" name="Institute for Nonprofit News" data-paypal="kevin.davis@investigativenewsnetwork.org">
<?php
}
add_action( 'wp_head', 'inn_head' );