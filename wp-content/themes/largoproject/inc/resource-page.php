<?php
/**
 * Functions specific to the resources pages
 */

/**
 * Let pages be categorized
 */
function largoproject_page_enhancements() {
	register_taxonomy_for_object_type( 'category', 'page' );
	register_taxonomy_for_object_type( 'post_tag', 'page' );
	add_post_type_support( 'page', 'excerpt' );
}
add_action( 'init', 'largoproject_page_enhancements' );

/**
 * Get a page in this $cat_id
 * copied from: https://github.com/INN/inn/blob/master/inn_resources.php#L70
 * @constant LARGOPROJECT_RESOURCES_PARENT_ID  the ID of the page that is the root of the resources section
 */
function largoproject_resources_get_top_item( $cat_id ) {
	//try to get a Page tagged with this $cat_id that has a parent of KCON_RESOURCES_PARENT_ID
	//sort by guide_rank metafield, if present
	$args = array(
		'posts_per_page' => 1,
		'cat' => $cat_id,
		'post_type' => 'page',
		'meta_key' => 'guide_rank',
		'orderby' => 'meta_value_num',
		'order' => 'ASC',
		'post_parent' => LARGOPROJECT_RESOURCES_PARENT_ID,
		'suppress_filters' => 1,
		'update_post_term_cache' => 0,
		'update_post_meta_cache' => 0
	);
	$the_page = new WP_Query( $args );
	
	//if at first you don't succeed...
	//try again with 
	if ( !$the_page->have_posts() ) {
		$args = array_merge( $args, array(
			'meta_key' => '',
			'orderby' => 'date',
			'order' => 'DESC'
		) );
		$the_page->query( $args );
	}

	if ( is_string( $the_page )) {
		print $the_page;
		return false;
	}
	wp_reset_postdata();	//always give back
	
	return $the_page;
}
