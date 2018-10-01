<?php
/**
 * Plugin Name:     Inn Misc Functionality
 * Plugin URI:      PLUGIN SITE HERE
 * Description:     Functions adding functionality to INN.org's content
 * Author:          INN Labs
 * Author URI:      https://labs.inn.org/
 * Text Domain:     inn-misc-functionality
 * Domain Path:     /languages
 * Version:         0.1.0
 */

// Your code starts here.
$includes = array(
	'/inc/safe_style_css.php',
);
foreach ( $includes as $include ) {
	if ( 0 === validate_file( dirname( __FILE__ ) . $include ) ) {
		require_once( dirname( __FILE__ ) . $include );
	}
}
