<?php

// typekit
function inn_typekit() { ?>
	<script type="text/javascript" src="//use.typekit.net/mmy6iwx.js"></script>
	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
<?php
}
add_action( 'wp_head', 'inn_typekit' );


// Largo metaboxen
require_once( get_template_directory() . "/inc/metabox-api.php");


// includes
$includes = array(
	'/inc/grantees.php'
);

// Perform load
foreach ( $includes as $include ) {
	require_once( get_stylesheet_directory() . $include );
}

function journobiz_enqueue() {
	wp_dequeue_script('largoCore');
	wp_enqueue_script(
		'journobiz',
		get_stylesheet_directory_uri() . "/js/journobiz.js",
		array( 'jquery' ),
		false,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'journobiz_enqueue', 20 );