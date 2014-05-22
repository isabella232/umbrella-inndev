<?php
/**
 * Add a subtitle metabox
 */
largo_add_meta_box(
	'subtitle',
	'Subtitle',
	'subtitle_meta_box_display',
	'post',
	'normal',
	'core'
);
function subtitle_meta_box_display() {
	global $post;
	$values = get_post_custom( $post->ID );
	wp_nonce_field( 'largo_meta_box_nonce', 'meta_box_nonce' );
	?>
	<label for="subtitle"><?php _e('Subtitle', 'largo'); ?></label>
	<textarea name="subtitle" id="subtitle" class="widefat" rows="2" cols="20"><?php if ( isset ( $values['subtitle'] ) ) echo $values['subtitle'][0]; ?></textarea>
	<?php
	largo_register_meta_input( 'subtitle' );
}

/**
 * Add featured image display metabox
 * (allows author to override automatic display of featured image as the "cover image" on single post page)
 */
largo_add_meta_box(
	'featured-image-display',
	'Featured Image Display',
	'featured_image_metabox_meta_box_display',
	'post',
	'side',
	'default'
);
function featured_image_metabox_meta_box_display() {
	global $post;
	$values = get_post_custom( $post->ID );
	$checked = isset($values["featured-image-display"][0]) ? 'checked="checked" ' : '';
	wp_nonce_field( 'largo_meta_box_nonce', 'meta_box_nonce' );
	?>
	<input type="checkbox" name="featured-image-display" <?php echo $checked; ?>/> Override display of featured image for this post?
	<?php
}

/**
 * largo metabox API doesn't yet support checkboxes as an input type
 * so we need a special function to save this field (for now)
 */
function save_details($post_ID = 0) {
    $post_ID = (int) $post_ID;
    $post_type = get_post_type( $post_ID );
    $post_status = get_post_status( $post_ID );

    if ( $post_type && isset($_POST['featured-image-display']) && $_POST['featured-image-display'] == 'on' ) {
    	update_post_meta( $post_ID, 'featured-image-display', 'false' );
    } else {
    	delete_post_meta( $post_ID, 'featured-image-display' );
    }
   return $post_ID;
}
add_action('save_post', 'save_details');