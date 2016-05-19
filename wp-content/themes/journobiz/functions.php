<?php

// Largo metabox API
require_once( get_template_directory() . "/inc/metabox-api.php");


// Constants
define( 'SHOW_GLOBAL_NAV', FALSE );


// Includes
$includes = array(
	'/inc/grantees.php',
	'/inc/metaboxes.php',
	'/inc/widgets.php',
	'/homepages/homepage.php'
);
foreach ( $includes as $include ) {
	require_once( get_stylesheet_directory() . $include );
}


// Enqueue custom js
function journobiz_enqueue() {
	wp_enqueue_script(
		'journobiz',
		get_stylesheet_directory_uri() . "/js/journobiz.js",
		array( 'jquery' ),
		false,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'journobiz_enqueue', 20 );


// TypeKit
function inn_head() { ?>
	<script type="text/javascript" src="//use.typekit.net/cui8tby.js"></script>
	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
	<link rel="author" name="Institute for Nonprofit News" data-paypal="kevin.davis@investigativenewsnetwork.org">
<?php
}
add_action( 'wp_head', 'inn_head' );