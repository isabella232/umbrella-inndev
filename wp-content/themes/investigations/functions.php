<?php
// Constants
define( 'SHOW_GLOBAL_NAV', FALSE );
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
	<script type="text/javascript" src="//use.typekit.net/cui8tby.js"></script>
	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
<?php
}
add_action( 'wp_head', 'inn_typekit' );


// Add network header and footer
add_action( 'largo_top', 'largo_render_network_header' );
add_action( 'largo_before_footer_boilerplate', 'largo_render_network_footer' );