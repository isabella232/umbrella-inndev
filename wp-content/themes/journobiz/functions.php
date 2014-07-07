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
		'taxonomies'          => array( 'category', 'post_tag' ),
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

}

// Hook into the 'init' action to register CPTs
add_action( 'init', 'jb_grantees', 0 );


/**
 * Largo metaboxen
 */
require_once( get_template_directory() . "/inc/metabox-api.php");

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
function save_grantees($post_ID = 0) {
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
add_action('save_post', 'save_grantees');