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


// Add network header and footer
add_action( 'largo_before_sticky_nav_container', 'largo_render_network_header' );
add_action( 'largo_top', function() {
	if (is_home() || is_front_page())
		largo_render_network_header();
});
add_action( 'largo_before_footer_boilerplate', 'largo_render_network_footer' );


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
function inn_typekit() { ?>
	<script type="text/javascript" src="//use.typekit.net/mmy6iwx.js"></script>
	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
<?php
}
add_action( 'wp_head', 'inn_typekit' );


// Register an additional sidebar region for the homepage
function journobiz_register_sidebars() {
	register_sidebar( array(
		'name' => 'Sidebar Home',
		'id' => 'sidebar-home',
		'description' => 'The sidebar on the homepage',
		'before_widget' => '<aside id="%1$s" class="%2$s clearfix">',
		'after_widget' 	=> "</aside>",
		'before_title' 	=> '<h3 class="widgettitle">',
		'after_title' 	=> '</h3>',
	) );
}
add_action( 'widgets_init', 'journobiz_register_sidebars' );
