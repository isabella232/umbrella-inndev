<?php
// constants
define( 'LARGOPROJECT_RESOURCES_PARENT_ID', 108447); 

// Includes
function largo_child_require_files() {
	$includes = array(
		'/homepages/homepage.php',
		'/inc/resource-page.php'
	);
	foreach ( $includes as $include ) {
		require_once( get_stylesheet_directory() . $include );
	}
}
add_action( 'after_setup_theme', 'largo_child_require_files' );


// Typekit
function inn_typekit() { ?>
	<script type="text/javascript" src="//use.typekit.net/cui8tby.js"></script>
	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
<?php
}
add_action( 'wp_head', 'inn_typekit' );

function largo_load_hero() {
	get_template_part( 'partials/largo', 'hero' );
}
add_action( 'largo_after_global_nav', 'largo_load_hero'  );

/**
 * Include compiled style.css
 */
function largo_child_stylesheet() {
	wp_dequeue_style( 'largo-child-styles' );
	$suffix = '';
	wp_enqueue_style( 'rr', get_stylesheet_directory_uri() . '/css/style' . $suffix . '.css' );
}
add_action( 'wp_enqueue_scripts', 'largo_child_stylesheet', 20 );