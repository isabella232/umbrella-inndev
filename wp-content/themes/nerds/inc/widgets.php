<?php

function inn_widgets() {
	$register = array(
		'inn_mailchimp_signup_widget'	=> '/inc/widgets/mailchimp-signup.php'
	);
	foreach ( $register as $key => $val ) {
		require_once( get_stylesheet_directory() . $val );
		register_widget( $key );
	}
}
add_action( 'widgets_init', 'inn_widgets' );
