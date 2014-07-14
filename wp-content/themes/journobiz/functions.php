<?php
function inn_typekit() { ?>
	<script type="text/javascript" src="//use.typekit.net/mmy6iwx.js"></script>
	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
<?php
}
add_action( 'wp_head', 'inn_typekit' );


// Register Custom Post Type
function jb_grantees() {

	$labels = array(
		'name'                => _x( 'Grantees', 'Post Type General Name', 'journobiz' ),
		'singular_name'       => _x( 'Grantee', 'Post Type Singular Name', 'journobiz' ),
		'menu_name'           => __( 'Grantees', 'journobiz' ),
		'parent_item_colon'   => __( 'Parent Grantee:', 'journobiz' ),
		'all_items'           => __( 'All Grantees', 'journobiz' ),
		'view_item'           => __( 'View Grantee', 'journobiz' ),
		'add_new_item'        => __( 'Add New Grantee', 'journobiz' ),
		'add_new'             => __( 'Add New', 'journobiz' ),
		'edit_item'           => __( 'Edit Grantee', 'journobiz' ),
		'update_item'         => __( 'Update Grantee', 'journobiz' ),
		'search_items'        => __( 'Search Grantees', 'journobiz' ),
		'not_found'           => __( 'Not Found', 'journobiz' ),
		'not_found_in_trash'  => __( 'Not Found in Trash', 'journobiz' ),
	);
	$args = array(
		'label'               => __( 'grantee', 'journobiz' ),
		'description'         => __( 'Grant recipients', 'journobiz' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'revisions', ),
		'taxonomies'          => array( 'category' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'menu_icon'						=> 'dashicons-pressthis',
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
	);
	register_post_type( 'grantee', $args );

	// And the taxonomy for Funding Rounds
	$labels = array(
		'name'                       => _x( 'Funding Rounds', 'Taxonomy General Name', 'journobiz' ),
		'singular_name'              => _x( 'Round', 'Taxonomy Singular Name', 'journobiz' ),
		'menu_name'                  => __( 'Funding Rounds', 'journobiz' ),
		'all_items'                  => __( 'All Rounds', 'journobiz' ),
		'parent_item'                => __( 'Parent Round', 'journobiz' ),
		'parent_item_colon'          => __( 'Parent Round:', 'journobiz' ),
		'new_item_name'              => __( 'New Round', 'journobiz' ),
		'add_new_item'               => __( 'Add New Round', 'journobiz' ),
		'edit_item'                  => __( 'Edit Round', 'journobiz' ),
		'update_item'                => __( 'Update Round', 'journobiz' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'journobiz' ),
		'search_items'               => __( 'Search Round', 'journobiz' ),
		'add_or_remove_items'        => __( 'Add or remove rounds', 'journobiz' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'journobiz' ),
		'not_found'                  => __( 'Not Found', 'journobiz' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
	);
	register_taxonomy( 'round', array( 'grantee','page' ), $args );

}

// Hook into the 'init' action to register CPTs
add_action( 'init', 'jb_grantees', 0 );


/**
 * Largo metaboxen
 */
require_once( get_template_directory() . "/inc/metabox-api.php");


/**
 * Add a metabox to posts to tag Grantees like categories
 */
largo_add_meta_box(
	'post-grantees',
	'Related Grantees',
	'grantees_metabox_display',
	'post',
	'side',
	'high'
);

function grantees_metabox_display() {
	global $post;
	$selected = get_post_meta( $post->ID, 'post-grantees', TRUE );

	//some of the below is inspired by wp_nav_menu_item_post_type_meta_box()
	$args = array(
		'order' => 'ASC',
		'orderby' => 'title',
		'post_type' => 'grantee',
		'suppress_filters' => true,
		'update_post_term_cache' => false,
		'update_post_meta_cache' => false,
		'posts_per_page' => -1,
		'post_status' => 'publish'
	);
	$grantees = new WP_Query( $args );
	if ( !$grantees->post_count ) {
		echo '<p>' . __( 'No grantees found', 'journobiz') . '</p>';
		return;
	}
	?>
	<div id="posttype-grantees" class="posttypediv">
		<div class="tabs-panel">
			<ul id="posttype-grantees">
			<?php
			while ( $grantees->have_posts() ):
				$grantees->the_post();
				$is_checked = ( in_array( get_the_ID(), $selected ) ) ? "checked" : "";
				?>
				<li>
					<label class="selectit">
					<input type="checkbox" name="post-grantees[]" value="<?php echo get_the_ID(); ?>" <?php echo $is_checked; ?>>
					<?php the_title(); ?>
					</label>
				</li>
			<?php
			endwhile;
			wp_reset_postdata();
			?>
			</ul>
		</div>
	</div>
	<?php
}

/**
 * largo metabox API doesn't yet support checkboxes as an input type
 * so we need a special function to save this field (for now)
 */
function save_post_grantees($post_ID = 0) {
  $post_ID = (int) $post_ID;
  $post_type = get_post_type( $post_ID );
  $post_status = get_post_status( $post_ID );

  if ( $post_type && isset($_POST['post-grantees']) && is_array($_POST['post-grantees']) ) {
  	update_post_meta( $post_ID, 'post-grantees', $_POST['post-grantees'] );
  } else {
  	delete_post_meta( $post_ID, 'post-grantees' );
  }
  return $post_ID;
}
add_action('save_post', 'save_post_grantees');


/**
 * Metabox for extra fields for Grantees
 */
/**
 * Loads the image management javascript
 */
function journobiz_admin_enqueue() {
	global $typenow;
	if( $typenow == 'grantee' ) {
	    wp_enqueue_media();

	    // Registers and enqueues the required javascript.
	    wp_register_script( 'meta-box-file', get_stylesheet_directory_uri() . '/js/media-fields.js', array( 'jquery' ) );
	    wp_localize_script( 'meta-box-file', 'meta_file',
	        array(
	            'title' => __( 'Select File', 'journobiz' ),
	            'button' => __( 'Use this file', 'journobiz' ),
	        )
	    );
	    wp_enqueue_script( 'meta-box-file' );
	}
}
add_action( 'admin_enqueue_scripts', 'journobiz_admin_enqueue' );

function journobiz_enqueue() {
	wp_enqueue_script(
		'journobiz',
		get_stylesheet_directory_uri() . "/js/journobiz.js",
		array( 'jquery' ),
		false,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'journobiz_enqueue' );

largo_add_meta_box(
	'grantee-details',
	'Grantee Details',
	'grantee_details_display',
	'grantee',
	'normal',
	'high'
);

function grantee_details_display() {
	global $post;
	$meta = get_post_meta( $post->ID, 'grantee_details', true );	// why third arg is true to get a serialized array is beyond me

	//get filenames for friendlier display
	if ( isset($meta['budget-id']) ) {
		$meta['budget-filename'] = basename( get_attached_file( $meta['budget-id'] ));
	}
	if ( isset($meta['proposal-id']) ) {
		$meta['proposal-filename'] = basename( get_attached_file( $meta['proposal-id'] ));
	}

	$simple_fields = array(
		'org-name' => __( 'Organization Name', 'journobiz' ),
		'award-amount' => __( 'Award Amount', 'journobiz' ),
		'timeline-src' => __( 'TimelineJS URL', 'journobiz' )
	);

	?>
	<div id="grantee-details">
	<?php
		foreach( $simple_fields as $field_id => $label ) {
			?>
			<p class="<?php esc_attr_e($field_id); ?>-wrapper">
				<label for="<?php esc_attr_e($field_id); ?>"><?php echo $label; ?></label>
				<input type="text" name="grantee_details[<?php esc_attr_e($field_id); ?>]" value="<?php esc_attr_e( $meta[$field_id] ); ?>" class="large-text" />
			</p>
			<?php
		}
	?>

		<p class="why-wrapper">
			<label for="why"><?php _e( 'Why Funded', 'journobiz' ) ?></label>
			<textarea name="grantee_details[why]" class="large-text"><?php echo esc_textarea( $meta['why'] ); ?></textarea>
		</p>

		<p>
    	<label for="meta-budget-filename" class="prfx-row-title"><?php _e( 'Budget PDF', 'journobiz' )?></label><br>
			<input type="text" id="meta-budget-filename" class="regular-text" value="<?php if ( isset ( $meta['budget-filename'] ) ) echo $meta['budget-filename']; ?>" disabled="disabled" />
			<input type="button" id="meta-budget-button" class="button" value="<?php _e( 'Select File', 'journobiz' )?>" />
			<input type="hidden" name="grantee_details[budget-id]" id="meta-budget-id" value="<?php esc_attr_e( $meta['budget-id'] ); ?>">
		</p>

		<p>
    	<label for="meta-proposal-filename" class="prfx-row-title"><?php _e( 'Proposal PDF', 'journobiz' )?></label><br>
			<input type="text" id="meta-proposal-filename" class="regular-text" value="<?php if ( isset ( $meta['proposal-filename'] ) ) echo $meta['proposal-filename']; ?>" disabled="disabled" />
			<input type="button" id="meta-proposal-button" class="button" value="<?php _e( 'Select File', 'journobiz' )?>" />
			<input type="hidden" name="grantee_details[proposal-id]" id="meta-proposal-id" value="<?php esc_attr_e( $meta['proposal-id'] ); ?>">
		</p>
	</div>
	<?php
}

/**
 * Special function to save grantee fields
 */
function save_grantee_details($post_ID = 0) {
  $post_ID = (int) $post_ID;
  $post_type = get_post_type( $post_ID );
  $post_status = get_post_status( $post_ID );
	$fields = array('org-name', 'award-amount', 'timeline-src', 'why', 'budget-id', 'proposal-id' );

  if ( $post_type && $post_type == 'grantee' ) {
  	$grantee_details = array();
  	foreach( $fields as $field ) {
	  	if ( isset($_POST['grantee_details'][$field]) ) {
		  	$grantee_details[$field] = $_POST['grantee_details'][$field];
	  	}
  	}
  	update_post_meta( $post_ID, 'grantee_details', $grantee_details );
  } else {
  	delete_post_meta( $post_ID, 'grantee_details' );
  }
  return $post_ID;
}
add_action('save_post', 'save_grantee_details');



/**
 * Convenience method for getting posts by grantee id
 */
function posts_by_grantee( $grantee_id = NULL, $paged = 0 ) {
	if ( !$grantee_id ) {
		if ( get_post_type() == 'grantee' ) {
			$grantee_id = get_the_ID();
		} else {
			return false;
		}
	}

	// query to grab posts
	$args = array(
		'post_type' => 'post',
		'post_status' => 'publish',
		'paged' => $paged,
		'meta_query' => array(
			array(
				'key' => 'post-grantees',
				'value' => $grantee_id,
				'compare' => 'LIKE'
			)
		)
	);

	return new WP_Query( $args );
}

/**
 * Allow pagination parameters on single grantee URLs
 */
add_filter('redirect_canonical','journobiz_disable_redirect');
function journobiz_diable_redirect($redirect_url) {
	if ( is_singular('grantee') ) return false;
}