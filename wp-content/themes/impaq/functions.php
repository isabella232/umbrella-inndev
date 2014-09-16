<?php

// Constants
define( 'SHOW_GLOBAL_NAV', FALSE );
define( 'SHOW_STICKY_NAV', FALSE );
define( 'SHOW_MAIN_NAV', FALSE );


// Includes
$includes = array(
	'/homepages/homepage.php'
);
foreach ( $includes as $include ) {
	require_once( get_stylesheet_directory() . $include );
}


// Typekit
function inn_typekit() { ?>
	<script type="text/javascript" src="//use.typekit.net/mmy6iwx.js"></script>
	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
<?php
}
add_action( 'wp_head', 'inn_typekit' );


// Add network header
add_action( 'largo_top', 'largo_render_network_header' );


// Add impaq branding
function impaq_header() { ?>
	<header id="branding">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo get_stylesheet_directory_uri() . '/img/impaq-logo.png' ?>" /></a>
		<h5 class="tagline">An innovative social fundraising tool for foundations and nonprofits</h5>
	</header>
<?
}
add_action( 'largo_main_top', 'impaq_header' );