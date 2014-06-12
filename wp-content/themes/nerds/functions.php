<?php
/**
 * Largo APIs
 */
require_once( get_template_directory() . '/largo-apis.php' );

/**
 * Misc includes
 */
$includes = array(
	'/inc/users.php',
	'/inc/widgets.php',
	'/inc/metaboxes.php'
);

// Perform load
foreach ( $includes as $include ) {
	require_once( get_stylesheet_directory() . $include );
}

function override_largo_core_js() {
	wp_dequeue_script('largoCore');
	wp_enqueue_script('nerdCore', get_stylesheet_directory_uri() . '/js/nerdCore.js', array( 'jquery' ), '1.0', true );
}
add_action( 'wp_enqueue_scripts', 'override_largo_core_js', 20 );

function inn_typekit() { ?>
	<script type="text/javascript" src="//use.typekit.net/mmy6iwx.js"></script>
	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
<?php
}
add_action( 'wp_head', 'inn_typekit' );

/**
 * Add top term to single post template
 */
function single_post_top() {
	if ( largo_has_categories_or_tags() ) {
		echo '<h5 class="top-tag">';
		largo_top_term();
		echo '</h5>';
	}
}
add_action( 'largo_before_post_header', 'single_post_top' );