<?php


/* -----------------------------------------------------------
	ACTIONS & FILTERS
   ----------------------------------------------------------- */

// POST FUNCTIONS
add_action( 'pre_post_update', 'pauinn_pre_posts_to_tax', 5 );
add_action( 'save_post', 'pauinn_posts_to_tax' );
add_action( 'delete_post', 'pauinn_delete_terms' );
add_filter( 'wp_insert_post_data', 'pauinn_update_slug', 99, 2 );

/* -----------------------------------------------------------
	FUNCTIONS
   ----------------------------------------------------------- */

function pauinn_get_tax_types() {
	return array( 'pauinn_project' => 'pauinn_project_tax' );
}


// process the meta
function pauinn_posts_to_tax( $post_id ) {

	$cur_post = get_post( $post_id );

	// OPTION IN THE TYPES
	$types = pauinn_get_tax_types();
	if ( isset( $types[$cur_post->post_type] ) ) {
		$tax = $types[$cur_post->post_type];
	} else {
		return $post_id;
	}

	$arg = array();

	// IF THIS IS A CHILD...
	if ( 0 != (int) $cur_post->post_parent ) {
		$par_post = get_post( $cur_post->post_parent );
		$par_term = get_term_by( 'name', $par_post->post_title, $tax );
		if ( false == $par_term ) {
			$new_par = wp_insert_term( $par_post->post_title, $tax );
			$par_term_id = $new_par[0];
		} else {
			$par_term_id = $par_term->term_id;
		}

		$arg['parent'] = $par_term_id;

	} else {
		$arg['parent'] = 0;
	}

	// WE HAVE AN EXISTING POST
	if ( isset( $_POST['pre_post_title'] ) && $_POST['pre_post_title'] != 'Auto Draft' ) {

		if (
			isset( $_POST['pre_post_title'] ) &&
			$_POST['pre_post_title'] != 'Auto Draft' &&
			$cur_post->post_title != 'Auto Draft'
			//$cur_post->post_title != $_POST['pre_post_title']
		) {
			$arg['name'] = $cur_post->post_title;
			$arg['slug'] = $cur_post->post_name;
			$term = get_term_by( 'name', $_POST['pre_post_title'], $tax );
		}

    if ( false != $term ) {
			wp_update_term( $term->term_id, $tax, $arg );
		} else {
			wp_insert_term( $cur_post->post_title, $tax, $arg );
		}

	// WE HAVE A NEW POST
	} else {

		if ( isset( $_POST['post_title'] ) && $_POST['post_title'] != 'Auto Draft' )
			wp_insert_term( $_POST['post_title'], $tax, $arg );
	}

}

function pauinn_pre_posts_to_tax( $post_id ) {

	$pre_post = get_post( $post_id );

	// OPTION IN THE TYPES
	$types = pauinn_get_tax_types();
	if ( isset( $types[$pre_post->post_type] ) ) {
		$tax = $types[$pre_post->post_type];
	} else {
		return $post_id;
	}

  if ( isset( $pre_post->post_title ) && !empty( $pre_post->post_title ) ) {
		$_POST['pre_post_title'] = $pre_post->post_title;
		if ( !isset( $_POST['post_title'] ) ) {
			$title = sanitize_title( $pre_post->post_title );
		} else {
			$title = $pre_post->post_name;
		}
		$_POST['post_name'] = $title;
	}
}

function pauinn_delete_terms( $post_id ) {

	$del_post = get_post( $post_id );

}

function pauinn_delete_terms( $post_id ) {

	$del_post = get_post( $post_id );

	// OPTION IN THE TYPES
	$types = pauinn_get_tax_types();
	if ( isset( $types[$del_post->post_type] ) ) {
		$tax = $types[$del_post->post_type];
	} else {
		return $post_id;
	}

	$ppterm = get_term_by( 'slug', $del_post->post_name, $tax );
	$pptermID = $ppterm->term_id;
	wp_delete_term( $pptermID, $tax );
}

function pauinn_update_slug( $data, $postarr ) {

	if ( !isset( $data['ID'] ) ) {
		return $data;
	}
	$slug_post = get_post( $data['ID'] );

  // OPTION IN THE TYPES
	$types = pauinn_get_tax_types();
	if ( isset( $types[$slug_post->post_type] ) ) {
		$tax = $types[$slug_post->post_type];
	} else {
		return $data;
	}

  $data['post_name'] = sanitize_title( $data['post_title'] );

  return $data;
}
