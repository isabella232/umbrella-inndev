<?php

//USEFUL CONSTANTS
define( 'INN_MEMBER_TAXONOMY', 'ppu_focus_areas' );


// Includes
$includes = array(
	'/homepages/homepage.php'
);
foreach ( $includes as $include ) {
	require_once( get_stylesheet_directory() . $include );
}


// Typekit
function inn_typekit() { ?>
	<script src="//use.typekit.net/cui8tby.js"></script>
	<script>try{Typekit.load();}catch(e){}</script>
<?php
}
add_action( 'wp_head', 'inn_typekit' );


// Enable excerpts for pages
function inn_init() {
	add_post_type_support( 'page', 'excerpt' );
}
add_action('init', 'inn_init');


/**
 * Load custom JS
 */
function inn_enqueue() {
	if ( !is_admin() ) {
		wp_enqueue_script( 'inn-tools', get_stylesheet_directory_uri() . '/js/inn.js', array('jquery'), '1.0.0', true );
	}
}
add_action( 'wp_enqueue_scripts', 'inn_enqueue' );


/**
 * Track things
 */
function largo_google_analytics() {
		if ( !is_user_logged_in() ) : // don't track logged in users ?>
			<script>
			    var _gaq = _gaq || [];
			    var pluginUrl = '//www.google-analytics.com/plugins/ga/inpage_linkid.js';
				_gaq.push(['_require', 'inpage_linkid', pluginUrl]);
			<?php if ( of_get_option( 'ga_id', true ) ) : // make sure the ga_id setting is defined ?>
				_gaq.push(['_setAccount', '<?php echo of_get_option( "ga_id" ) ?>']);
				_gaq.push(['_trackPageview']);
			<?php endif; ?>
			    _gaq.push(
					["largo._setAccount", "UA-17578670-4"],
					["largo._setCustomVar", 1, "SiteName", "<?php bloginfo('name') ?>"],
					["largo._setDomainName", "<?php echo str_replace( 'http://' , '' , home_url()) ?>"],
					["largo._setAllowLinker", true],
					["largo._trackPageview"]
				);

			    (function() {
				    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
				})();
			</script>
	<?php endif;
}

/**
 * Add custom RSS feeds for member stories
 * Template used is feed-member-stories.php
 */
function add_query_vars_filter( $vars ){
  $vars[] = 'top_stories';
  return $vars;
}
add_filter( 'query_vars', 'add_query_vars_filter' );

function inn_member_stories_rss() {
	add_filter('pre_option_rss_use_excerpt', '__return_zero');
	load_template( get_stylesheet_directory() . '/feed-member-stories.php' );
}
add_feed('member_stories', 'inn_member_stories_rss');
