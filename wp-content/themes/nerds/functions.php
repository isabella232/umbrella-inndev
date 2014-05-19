<?php

function override_largo_core_js() {
	wp_dequeue_script('largoCore');
	wp_enqueue_script('nerdCore', get_stylesheet_directory_uri() . '/js/nerdCore.js', array( 'jquery' ), '1.0', true );
}
add_action( 'wp_enqueue_scripts', 'override_largo_core_js', 20 );
