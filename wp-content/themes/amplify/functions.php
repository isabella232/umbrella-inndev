<?php
/**
 * Amplify Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package amplify
 */


/**
 * Include theme files
 *
 * Based off of how Largo loads files: https://github.com/INN/Largo/blob/master/functions.php#L358
 *
 * 1. hook function Largo() on after_setup_theme
 * 2. function Largo() runs Largo::get_instance()
 * 3. Largo::get_instance() runs Largo::require_files()
 *
 * This function is intended to be easily copied between child themes, and for that reason is not prefixed with this child theme's normal prefix.
 *
 * @link https://github.com/INN/Largo/blob/master/functions.php#L145
 */
function largo_child_require_files() {
	$includes = array(
	);

	foreach ( $includes as $include ) {
		require_once( get_stylesheet_directory() . $include );
	}
}
add_action( 'after_setup_theme', 'largo_child_require_files' );

/**
 * Enqueue scripts and styles.
 */
function largo_parent_theme_enqueue_styles() {
	wp_dequeue_style( 'largo-child-styles' );

	wp_enqueue_style( 'largo-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'amplify-style',
		get_stylesheet_directory_uri() . '/css/style.css',
		array( 'largo-stylesheet' ),
		filemtime( get_stylesheet_directory() . '/css/child-style.css' )
	);
}
add_action( 'wp_enqueue_scripts', 'largo_parent_theme_enqueue_styles', 20 );

/**
 * Add query vars specific to the Amplify child theme
 * 
 * @param array $vars The array of available query vars
 * 
 * @return array $vars The modified array of available query vars
 */
function amplify_add_query_vars( $vars ) {
	
	$vars[] = 'amplify-feed';
	
	return $vars;

}
add_filter( 'query_vars', 'amplify_add_query_vars' );

/**
 * Load the Amplify embed feed template if specific query param is present
 * 
 * @param str $template The path of the current template
 * 
 * @return str $template The path of the template to include
 */
function amplify_load_feed_template( $template ) { 

    if ( ! is_admin() ) {
        // if the params are set, use our amplify feed template if it exists
		if( isset( $_GET['amplify-feed'] ) && locate_template( 'amplify-feed.php' ) ){
			return get_stylesheet_directory().'/amplify-feed.php';
		// else, continue with whatever template was being loaded
		} else {
			return $template;
		}
    }
	return $template;
	
}
add_filter( 'template_include', 'amplify_load_feed_template' );

/**
 * Add the "More link" input field to the tag edit page for 'lr-tags' taxonomy
 * 
 * @param obj $context The wp term that is being modified
 */
function amplify_add_saved_link_roundups_more_link_metabox( $context = '' ) {

	if( 'lr-tags' == $context->taxonomy ){
		
		$term_more_link = get_term_link( $context, $context->taxonomy );

		// Post ID here is the id of the post that Largo uses to keep track of the term's metadata. See largo_get_term_meta_post.
		$post_id = largo_get_term_meta_post( $context->taxonomy, $context->term_id );

		if( metadata_exists( 'post', $post_id, 'lr_more_link' ) ) {
			$term_more_link = get_post_meta( $post_id, 'lr_more_link', true );
		}

		?>
		<tr class="form-field">
			<th scope="row" valign="top"><?php _e('More link', 'amplify'); ?></th>
			<td>
				<input class="widefat" id="more_link" name="lr_more_link" type="url" value="<?php echo $term_more_link; ?>" />
				<p class="description">If the "More" link that is displayed needs to redirect to somewhere other than the tag archive page, change it here.</p>
			</td>
		</tr>
		<?php

	}

}
add_action( 'edit_tag_form_fields', 'amplify_add_saved_link_roundups_more_link_metabox');

/**
 * Function to save specific meta for LR tags	
 */
function save_lr_tag_meta( $term_id ) {

	// we'll need to get the post id of the term that Largo uses to keep track of its meta. See largo_get_term_meta_post.
	$post_id = largo_get_term_meta_post( 'lr-tags', $term_id );

	// save the "More" link meta if posible
	if( isset( $_POST['lr_more_link'] ) && ! empty( $_POST['lr_more_link'] ) ) {
		update_post_meta( $post_id, 'lr_more_link', sanitize_url( $_POST['lr_more_link'] ) );
	}

}
add_action( 'edit_term', 'save_lr_tag_meta' );