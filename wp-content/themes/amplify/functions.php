<?php
/**
 * Amplify Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package amplify
 */


/**
 * Include theme files
 *
 * Based off of how Largo loads files: https://github.com/INN/Largo/blob/master/functions.php#L358
 *
 * 1. hook function Largo() on after_setup_theme
 * 2. function Largo() runs Largo::get_instance()
 * 3. Largo::get_instance() runs Largo::require_files()
 *
 * This function is intended to be easily copied between child themes, and for that reason is not prefixed with this child theme's normal prefix.
 *
 * @link https://github.com/INN/Largo/blob/master/functions.php#L145
 */
function largo_child_require_files() {
	$includes = array(
	);

	foreach ( $includes as $include ) {
		require_once( get_stylesheet_directory() . $include );
	}
}
add_action( 'after_setup_theme', 'largo_child_require_files' );

/**
 * Enqueue scripts and styles.
 */
function largo_parent_theme_enqueue_styles() {
	wp_dequeue_style( 'largo-child-styles' );

	wp_enqueue_style( 'largo-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'amplify-style',
		get_stylesheet_directory_uri() . '/css/style.css',
		array( 'largo-stylesheet' ),
		filemtime( get_stylesheet_directory() . '/css/child-style.css' )
	);
}
add_action( 'wp_enqueue_scripts', 'largo_parent_theme_enqueue_styles', 20 );
