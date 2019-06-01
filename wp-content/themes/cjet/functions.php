<?php

// Include the Largo metabox API
require_once( get_template_directory() . '/largo-apis.php' );

// Includes
$includes = array(
	'/inc/metaboxes.php',
	'/inc/attachments.php',
	'/inc/sidebars.php',
	'/lib/file-un-attach/file-unattach.php',
	'/homepages/homepage.php'
);
foreach ( $includes as $include ) {
	require_once( get_stylesheet_directory() . $include );
}


// Custom script
function cjet_enqueue() {
	wp_enqueue_script( 'cjet-javascript', get_stylesheet_directory_uri() . '/js/cjet.js' );
}
add_action( 'wp_enqueue_scripts', 'cjet_enqueue', 11 );


// Typekit
function inn_typekit() { ?>
	<script type="text/javascript" src="//use.typekit.net/cui8tby.js"></script>
	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
<?php
}
add_action( 'wp_head', 'inn_typekit' );

// Breadcrumbs
function cjet_breadcrumbs() {
	if ( function_exists( 'bcn_display' ) && !is_home() ) {
		echo '<div id="breadcrumbs">';
		bcn_display();
		echo '</div>';
	}
}
add_action( 'largo_main_top', 'cjet_breadcrumbs' );

add_theme_support( 'custom-header' );


// Add excerpts to pages
function cjet_init() {
	add_post_type_support( 'page', 'excerpt' );
}
add_action( 'init', 'cjet_init' );

// Add extra theme options
function cjet_theme_options( $options ) {

	$options[] = array(
		'name' 	=> __('CJET', 'cjet'),
		'type' 	=> 'heading');

	$options[] = array(
		'desc' 	=> __('Enter a description of the online courses to appear on the homepage.', 'cjet'),
		'id' 	=> 'cjet_courses_intro',
		'std' 	=> '',
		'type' 	=> 'textarea');

	$options[] = array(
		'desc' 	=> __('Enter a description of the guides to appear on the homepage.', 'cjet'),
		'id' 	=> 'cjet_guides_intro',
		'std' 	=> '',
		'type' 	=> 'textarea');

	return $options;
}
add_filter('largo_options', 'cjet_theme_options');

/**
 * Enable shortcodes in Custom HTML Widget
 *
 * @link https://github.com/INN/umbrella-inndev/issues/68#issuecomment-497853084
 * @link https://core.trac.wordpress.org/browser/tags/5.2/src/wp-includes/widgets/class-wp-widget-custom-html.php#L158
 */
add_filter( 'widget_custom_html_content', 'shortcode_unautop');
add_filter( 'widget_custom_html_content', 'do_shortcode');
