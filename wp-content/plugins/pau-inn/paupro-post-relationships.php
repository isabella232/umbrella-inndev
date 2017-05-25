<?php


/* -----------------------------------------------------------
	ACTIONS & FILTERS
   ----------------------------------------------------------- */
<<<<<<< HEAD
=======
   
// SETUP
add_action( 'init', 'pauinn_project_post' );
add_action( 'init', 'pauinn_project_taxonomy', 1 );
>>>>>>> staging

// POST FUNCTIONS
add_action( 'pre_post_update', 'pauinn_pre_posts_to_tax', 5 );
add_action( 'save_post', 'pauinn_posts_to_tax' );
add_action( 'delete_post', 'pauinn_delete_terms' );
add_filter( 'wp_insert_post_data', 'pauinn_update_slug', 99, 2 );

<<<<<<< HEAD
=======


/* -----------------------------------------------------------
	SETUP
   ----------------------------------------------------------- */

function pauinn_project_post() {  
  
  $labels = array(
    'name' => 'Projects',
    'singular_name' => 'Project',
    'add_new' => sprintf( __( 'Add New %1$s' ), 'Project' ),
    'add_new_item' => sprintf( __( 'Add New %1$s' ), 'Project' ),
    'edit_item' => sprintf( __( 'Edit %1$s' ), 'Projects' ),
    'new_item' => sprintf( __( 'New %1$s' ), 'Project' ),
    'view_item' => sprintf( __( 'View %1$s' ), 'Project' ),
    'search_items' =>  sprintf( __( 'Search %1$s' ), 'Projects' ),
    'not_found' =>  sprintf( __( 'No %1$s Found' ), 'Projects' ),
    'not_found_in_trash' => sprintf( __( 'No %1$s Found in Trash' ), 'Projects' ), 
    'parent_item_colon' => ''
  );
  
  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'exclude_from_search' => false,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'project' ),
    'has_archive' => true,
    'show_in_menu' => true, 
    'capability_type' => 'page',
    'hierarchical' => false,
    'menu_position' => null,
    'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt' )
  ); 
  
  register_post_type( 'pauinn_project', $args );

}

function pauinn_project_taxonomy() {

	$labels = array(
	    'name' => 'Projects',
	    'singular_name' => 'Project',
	    'search_items' =>  sprintf( __( 'Search %1$s' ), 'Projects' ),
	    'all_items' => sprintf( __( 'All %1$s' ), 'Projects' ),
	    'parent_item' => sprintf( __( 'Parent %1$s' ), 'Project' ),
	    'parent_item_colon' => sprintf( __( 'Parent %1$s:' ), 'Project' ),
	    'edit_item' => sprintf( __( 'Edit %1$s' ), 'Projects' ), 
	    'update_item' => sprintf( __( 'Update %1$s' ), 'Projects' ),
	    'add_new_item' => sprintf( __( 'Add New %1$s' ), 'Projects' ),
	    'new_item_name' => sprintf( __( 'New %1$s Name' ), 'Projects' ),
	  ); 	

	register_taxonomy(
	  	'pauinn_project_tax', array( 'pauinn_project', 'pp_opportunity', 'post' ), array(
	    'hierarchical' => true,
	    'labels' => $labels,
	    'show_ui' => true,
	    'show_in_nav_menus' => true, 
	    'show_admin_column' => true, 
	    'query_var' => true,
	    'rewrite' => array( 'slug' => 'projects', 'with_front' => true, 'hierarchical' => true ),
	));

}



>>>>>>> staging
/* -----------------------------------------------------------
	FUNCTIONS
   ----------------------------------------------------------- */

function pauinn_get_tax_types() {
	return array( 'pauinn_project' => 'pauinn_project_tax' );
}


// process the meta
function pauinn_posts_to_tax( $post_id ) {

	$cur_post = get_post( $post_id );
<<<<<<< HEAD

=======
	
>>>>>>> staging
	// OPTION IN THE TYPES
	$types = pauinn_get_tax_types();
	if ( isset( $types[$cur_post->post_type] ) ) {
		$tax = $types[$cur_post->post_type];
	} else {
		return $post_id;
	}
<<<<<<< HEAD

	$arg = array();

=======
	
	$arg = array();
	
>>>>>>> staging
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
<<<<<<< HEAD

		$arg['parent'] = $par_term_id;

=======
		
		$arg['parent'] = $par_term_id;
		
>>>>>>> staging
	} else {
		$arg['parent'] = 0;
	}

	// WE HAVE AN EXISTING POST
	if ( isset( $_POST['pre_post_title'] ) && $_POST['pre_post_title'] != 'Auto Draft' ) {

<<<<<<< HEAD
		if (
			isset( $_POST['pre_post_title'] ) &&
			$_POST['pre_post_title'] != 'Auto Draft' &&
			$cur_post->post_title != 'Auto Draft'
			//$cur_post->post_title != $_POST['pre_post_title']
=======
		if ( 
			isset( $_POST['pre_post_title'] ) && 
			$_POST['pre_post_title'] != 'Auto Draft' && 
			$cur_post->post_title != 'Auto Draft' 
			//$cur_post->post_title != $_POST['pre_post_title'] 
>>>>>>> staging
		) {
			$arg['name'] = $cur_post->post_title;
			$arg['slug'] = $cur_post->post_name;
			$term = get_term_by( 'name', $_POST['pre_post_title'], $tax );
		}
<<<<<<< HEAD

=======
				
>>>>>>> staging
		if ( false != $term ) {
			wp_update_term( $term->term_id, $tax, $arg );
		} else {
			wp_insert_term( $cur_post->post_title, $tax, $arg );
		}
<<<<<<< HEAD

	// WE HAVE A NEW POST
	} else {

		if ( isset( $_POST['post_title'] ) && $_POST['post_title'] != 'Auto Draft' )
			wp_insert_term( $_POST['post_title'], $tax, $arg );

=======
				
	// WE HAVE A NEW POST
	} else {
		
		if ( isset( $_POST['post_title'] ) && $_POST['post_title'] != 'Auto Draft' )
			wp_insert_term( $_POST['post_title'], $tax, $arg );
		
>>>>>>> staging
	}

}

function pauinn_pre_posts_to_tax( $post_id ) {
<<<<<<< HEAD

	$pre_post = get_post( $post_id );

=======
	
	$pre_post = get_post( $post_id );
	
>>>>>>> staging
	// OPTION IN THE TYPES
	$types = pauinn_get_tax_types();
	if ( isset( $types[$pre_post->post_type] ) ) {
		$tax = $types[$pre_post->post_type];
	} else {
		return $post_id;
	}
<<<<<<< HEAD

=======
			
>>>>>>> staging
	if ( isset( $pre_post->post_title ) && !empty( $pre_post->post_title ) ) {
		$_POST['pre_post_title'] = $pre_post->post_title;
		if ( !isset( $_POST['post_title'] ) ) {
			$title = sanitize_title( $pre_post->post_title );
		} else {
			$title = $pre_post->post_name;
		}
		$_POST['post_name'] = $title;
	}
<<<<<<< HEAD

}

function pauinn_delete_terms( $post_id ) {

	$del_post = get_post( $post_id );

=======
		
}

function pauinn_delete_terms( $post_id ) {
	
	$del_post = get_post( $post_id );
	
>>>>>>> staging
	// OPTION IN THE TYPES
	$types = pauinn_get_tax_types();
	if ( isset( $types[$del_post->post_type] ) ) {
		$tax = $types[$del_post->post_type];
	} else {
		return $post_id;
	}
<<<<<<< HEAD

	$ppterm = get_term_by( 'slug', $del_post->post_name, $tax );
	$pptermID = $ppterm->term_id;
	wp_delete_term( $pptermID, $tax );

=======
	
	$ppterm = get_term_by( 'slug', $del_post->post_name, $tax );
	$pptermID = $ppterm->term_id;
	wp_delete_term( $pptermID, $tax );
	
>>>>>>> staging
}

function pauinn_update_slug( $data, $postarr ) {

	if ( !isset( $data['ID'] ) ) {
		return $data;
	}
	$slug_post = get_post( $data['ID'] );
<<<<<<< HEAD

=======
	
>>>>>>> staging
	// OPTION IN THE TYPES
	$types = pauinn_get_tax_types();
	if ( isset( $types[$slug_post->post_type] ) ) {
		$tax = $types[$slug_post->post_type];
	} else {
		return $data;
	}
<<<<<<< HEAD

    $data['post_name'] = sanitize_title( $data['post_title'] );

=======
	
    $data['post_name'] = sanitize_title( $data['post_title'] );
    
>>>>>>> staging
    return $data;
}
