<?php

//USEFUL CONSTANTS
define( 'INN_MEMBER_TAXONOMY', 'ppu_focus_areas' );


/**
 * Load typekit stylesheet stuff
 */
function inn_typekit() { ?>
	<script type="text/javascript" src="//use.typekit.net/mmy6iwx.js"></script>
	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
<?php
}
add_action( 'wp_head', 'inn_typekit' );


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
 * Custom Widgets
 */
add_action('widgets_init', 'inn_widgets', 11);

function inn_widgets() {
  register_widget('resources_widget'); //for homepage
  register_widget('resource_widget'); //for category archives

	register_sidebar( array(
		'name' 			=> __( 'INN Homepage Bottom', 'inn' ),
		'description' 	=> __( 'A widget area at the bottom of the INN homepage', 'inn' ),
		'id' 			=> 'inn-home-bottom',
		'before_widget' => '<div id="%1$s" class="%2$s">',
		'after_widget' 	=> '</div>',
		'before_title' 	=> '<h3 class="widgettitle">',
		'after_title' 	=> '</h3>',
		'class' => 'span12'
	) );

	register_sidebar( array(
		'name' 			=> __( 'Category Header', 'inn' ),
		'description' 	=> __( 'A widget area sandwiched between the title and list of items in a category archive', 'inn' ),
		'id' 			=> 'category-topper',
		'before_widget' => '<div id="%1$s" class="%2$s">',
		'after_widget' 	=> '</div>',
		'before_title' 	=> '<h3 class="widgettitle">',
		'after_title' 	=> '</h3>'
	) );

	// get rid of the unused widget areas
	unregister_sidebar( 'homepage-bottom' );
	unregister_sidebar( 'header-ads' );
	unregister_sidebar( 'footer-2' );
	unregister_sidebar( 'footer-3' );

}


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
