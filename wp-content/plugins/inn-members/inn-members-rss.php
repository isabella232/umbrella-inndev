<?php

/**
 *  ==============  Membership RSS Feed Import stuff  ==============
 */
if ( !defined( 'MEMBER_POST_COUNT' ) ) {
	define( 'MEMBER_POST_COUNT', 20);
}
if ( !defined( 'MEMBER_EXPIRE_MEDIA' ) ) {
	define( 'MEMBER_EXPIRE_MEDIA', true);
}

/**
 * Define the custom post type
 */
function inn_init_member_content() {

	//post type for rss-imported member content
  register_post_type( 'network_content',
    array(
      'labels' => array(
        'name' => _x('Network Content', 'post type general name'),
        'singular_name' => _x('Content Item', 'post type singular name'),
        'add_new' => _x('Add Network Item', 'new inn network content item '),
        'add_new_item' => __('Add Network Content'),
        'edit_item' => __('Edit Item'),
        'new_item' => __('New Item'),
        'all_items' => __('All Network Content'),
        'view_item' => __('View Item'),
        'search_items' => __('Search Network Content'),
        'not_found' =>  __('No content found'),
        'not_found_in_trash' => __('No content found in Trash'),
        'parent_item_colon' => '',
        'menu_name' => __('Network Content')
      ),
    'menu_position' => 22,
    'show_ui' => true,
    'description' => 'Content imported from INN member sites',
    'exclude_from_search' => true,
    'publicly_queryable' => true,
    'public' => true,
    'has_archive' => true,
    'rewrite' => array('slug' => 'network-content'),
    'hierarchical' => false,
    'supports' => array('title','editor','thumbnail','author','custom-fields','excerpt'),
    'taxonomies' => array('category', 'post_tag')
    )
  );
}
add_action( 'init', 'inn_init_member_content', 8 );	//need to make this exist before cron initiates

function inn_init_member_tax() {
	register_taxonomy_for_object_type( 'prominence', 'network_content' );
}
add_action( 'init', 'inn_init_member_tax', 11 );

/**
 * Override permalinks for posts from feeds
 */
function feed_post_permalink( $url ) {
	global $post;
	$source_url = get_post_meta( $post->ID, 'rssmi_source_link', TRUE );
	if ( $source_url && $post->post_type == 'network_content' ) return $source_url;
	return $url;
}
add_filter('the_permalink', 'feed_post_permalink');
add_filter('the_permalink_rss', 'feed_post_permalink');

/**
 * Change the post manager page
 */
function inn_nc_cols( $columns ) {
	//remove tags, add thumb, source
	$columns = array(
		'cb' => '<input type="checkbox">',
		'thumb' => '⚘',
		'title' => 'Title',
		'coauthors' => 'Author(s)',
		'prominence' => 'Prominence',
		'categories' => 'Categories',
		'date' => 'Date'
	);

	return $columns;
}
add_filter('manage_network_content_posts_columns' , 'inn_nc_cols');

function inn_custom_column( $column, $post_id ) {
    switch ( $column ) {
      case 'thumb' :
      	echo get_the_post_thumbnail( $post_id, array(32,32) );
        break;

      case 'source' :
      	$member_id = get_post_meta( $post_id , 'from_member_id' , true );
      	if ( $member_id ) {
      		$mem = get_post( $member_id );
          echo $mem->post_title;
				}
        break;

      case 'prominence' :
      	$terms = get_the_term_list( $post_id, 'prominence', '', ', ', '');
      	echo ( $terms ) ? $terms : '—';
      	break;
    }
}
add_action( 'manage_network_content_posts_custom_column' , 'inn_custom_column', 10, 2 );

function inn_col_style() {
	?>
	<style>
		.column-thumb { width: 32px; }
		th#thumb { font-size: 150%; text-align: center; color: #666;	}
	</style>
	<?php
}
add_action( 'admin_head', 'inn_col_style' );

/**
 * Loop thru all our inn_rss usermetas and make sure they're all registered with the Multi RSS plugin
 */
function inn_register_feeds() {

	$current_feeds = get_option( 'rss_import_items', false );

	$feed_id = 0;

	if ( count($current_feeds) ) {
		@list( $x, $y, $feed_id ) = explode('_', end($current_feeds) );
		$feed_id++;
		reset( $current_feeds );
	} else {
		$current_feeds = array();
	}

	// if ( !$current_feeds ) return;
	// $current_feeds is an array of the form feed_name_1, feed_url_1, feed_cat_1, feed_name_2, etc
	// feed_cat_N will = 0 if no categories are configured

	// get our members with feeds
	 	$members = new WP_User_Query( array(
	 	'role' => 'Member',
	 	'fields' => 'all_with_meta',
 		'meta_query' => array(
 			array(
 				'key' => 'inn_rss',
 				'value' => 'NULL',
 				'compare' => '!='
 			)
 		)
 	) );

 	if ( !empty($members->results) ) {
	 	foreach( $members->results as $member ) {
		 	// get the RSS url
		 	$rss_url = get_user_meta( $member->ID, 'inn_rss', TRUE );
		 	if ( !in_array($rss_url, $current_feeds, TRUE) ) {
			 	// add this rss_url to the array
			 	$current_feeds[ 'feed_name_' . $feed_id ] = "INN Member: " . $member->data->display_name;
			 	$current_feeds[ 'feed_url_' . $feed_id  ] = $rss_url;
			 	$current_feeds[ 'feed_cat_' . $feed_id++  ] = 0;
		 	}
	 	}
	}

	update_option( 'rss_import_items', $current_feeds );

}
add_action('init', 'inn_register_feeds');