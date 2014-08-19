<?php

/**
 * CJET theme stuff
 *
 * Most of the brains live in Largo, of course
 */
require_once( get_template_directory() . '/largo-apis.php' );

/**
 * Misc includes
 */
$includes = array(
	'/inc/metaboxes.php',
	'/lib/file-un-attach/file-unattach.php'
);

// Perform load
foreach ( $includes as $include ) {
	require_once( get_stylesheet_directory() . $include );
}


/**
 * Formats an attachment link for inclusion on Guide pages, with icon, filesize & type, etc.
 *
 * @access public
 * @param integer $attachment_id
 * @return void
 */
function cjet_format_attachment_link( $attachment_id ) {

	$extras = cjet_attachment_extras( $attachment_id );

	switch ( $extras['human_type'] ) {
		case "Image":
			$human_type_css_class = "picture";
			break;
		case "Video":
			$human_type_css_class = "play";
			break;
		case "Excel":
			$human_type_css_class = "table";
			break;
		case "Word":
			$human_type_css_class = "doc-text";
			break;
		case "PDF":
			$human_type_css_class = "doc-text-inv";
			break;
		default:
			$human_type_css_class = "download";
	}

	$details = array();
	if ( !empty($extras['human_type'] )) $details[] = $extras['human_type'];
	if ( !empty($extras['filesize'] )) $details[] = size_format($extras['filesize']);

	$output = sprintf('<a href="%s" title="%s"><i class="%s"></i> %s <span class="attachment-meta">(%s)</span></a>',
		wp_get_attachment_url( $attachment_id ),
		__('Permalink to', 'cjet') . ' ' . esc_attr( get_the_title( $attachment_id ) ),
		'icon-' . $human_type_css_class,
		get_the_title( $attachment_id ),
		implode(", ", $details)
	);
	return $output;
}


/**
 * Gets some extra information (simplified filetype and file size) about the provided attachment ID and returns it as an array.
 *
 * @access public
 * @param integer $attachment_id
 * @return array
 */
function cjet_attachment_extras( $attachment_id ) {

	$human_type = "";
	$mime = get_post_mime_type( $attachment_id );
	switch( $mime ) {
		case "image/jpeg":
		case "image/jpg":
		case "image/png":
		case "image/gif":
			$human_type = "Image";
			break;
		case "video/mpeg":
		case "video/mp4":
		case "video/quicktime":
			$human_type = "Video";
			break;
		case "application/vnd.ms-excel":
		case "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet":
		case "text/csv":
			$human_type = "Excel";
			break;
		case "application/msword":
		case "application/vnd.openxmlformats-officedocument.wordprocessingml.document":
			$human_type = "Word";
			break;
		case "application/pdf":
			$human_type = "PDF";
			break;
		//case "application/vnd.ms-powerpoint"
	}

	return array(
		'human_type' => $human_type,
		'filesize' => @filesize( get_attached_file( $attachment_id )),
	);

}

/**
 * Load up stuff we need
 */
function cjet_enqueue() {
	//Get our JS file
	wp_enqueue_script( 'cjet-javascript', get_stylesheet_directory_uri() . '/js/cjet.js' );
}
add_action( 'wp_enqueue_scripts', 'cjet_enqueue', 11 );

function inn_typekit() { ?>
	<script type="text/javascript" src="//use.typekit.net/mmy6iwx.js"></script>
	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
<?php
}
add_action( 'wp_head', 'inn_typekit' );

/**
 *  Image size stuff for homepage, picturefill, etc
 */
if( !defined('PICTUREFILL_WP_VERSION') ) {
  require_once(get_template_directory() . '/inc/picturefill/picturefill-wp.php');
}

if( FALSE === get_option("large_crop") ) {
	add_option("large_crop", "1");
	add_option("medium_crop", "1");
} else {
	update_option("large_crop", "1");
	update_option("medium_crop", "1");
}

add_theme_support( 'custom-header' );

/**
 * Meta field for hiding the author box
 */
largo_add_meta_box(
	'author_display',
	__('Author Display', 'cjet'),
	'cjet_author_display_control',
	'page'
);


function cjet_author_display_control() {

	global $post;

	//if this isn't a parent page, this setting is irrelevant
	$ancestors = get_post_ancestors( $post->ID );
	if ( count( $ancestors ) !== 1 ) {
		echo "<em>" . __('This setting is only relevant in the context of guide/course landing pages', 'cjet'). "</em>";
	} else {
		$value = get_post_meta( $post->ID, 'cjet_hide_author', true );
		?><input type="checkbox" name="cjet_hide_author" value="1" <?php checked( $value, 1); ?> /> Hide the author bio for this guide/course
		<?php
	}
}
largo_register_meta_input( 'cjet_hide_author' );

/**
 * Adding excerpts to Pages
 */
function cjet_init() {
	add_post_type_support( 'page', 'excerpt' );
}
add_action( 'init', 'cjet_init' );

/**
 * Adding extra theme options
 */
function cjet_theme_options( $options ) {

	$options[] = array(
		'name' 	=> __('CJET', 'cjet'),
		'type' 	=> 'heading');

	$options[] = array(
		'desc' 	=> __('Enter a description of the online courses to appear on the homepage.', 'cjet'),
		'id' 	=> 'cjet_courses_intro',
		'std' 	=> '',
		'type' 	=> 'textarea');

	$options[] = array(
		'desc' 	=> __('Enter a description of the guides to appear on the homepage.', 'cjet'),
		'id' 	=> 'cjet_guides_intro',
		'std' 	=> '',
		'type' 	=> 'textarea');

	return $options;
}
add_filter('largo_options', 'cjet_theme_options');

function cjet_register_sidebars() {
	register_sidebar( array(
		'name' 			=> __( 'Homepage Callout', 'cjet' ),
		'id' 			=> 'homepage-callout',
		'description' 	=> __( 'Homepage Callout Section', 'cjet' ),
		'before_widget' => '<aside id="%1$s" class="%2$s clearfix">',
		'after_widget' 	=> "</aside>",
		'before_title' 	=> '<h3 class="widgettitle">',
		'after_title' 	=> '</h3>',
	) );
}
add_action( 'widgets_init', 'cjet_register_sidebars' );