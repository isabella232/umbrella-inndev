<?php

// TypeKit
function inn_typekit() { ?>
	<script type="text/javascript" src="//use.typekit.net/mmy6iwx.js"></script>
	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
<?php
}
add_action( 'wp_head', 'inn_typekit' );


// Largo metaboxen
require_once( get_template_directory() . "/inc/metabox-api.php");


// Includes
$includes = array(
	'/inc/grantees.php',
	'/inc/metaboxes.php',
	'/homepages/homepage.php'
);

// Perform load
foreach ( $includes as $include ) {
	require_once( get_stylesheet_directory() . $include );
}

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

// Add top term to single post template
function single_post_top() {
	if ( largo_has_categories_or_tags() ) {
		echo '<h5 class="top-tag">';
		largo_top_term();
		echo '</h5>';
	}
}
add_action( 'largo_before_post_header', 'single_post_top' );