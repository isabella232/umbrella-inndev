<?php

add_filter( 'pp_get_post_to_edit_action_user', 'pauopps_get_post_to_edit_action_user', 10, 4 );
function pauopps_get_post_to_edit_action_user( $action, $post, $type, $pt ) {

	if ( false == $action ) {
		if ( isset( $_GET['post_parent'] ) ) {
			$post_parent = $_GET['post_parent'];
		} else if ( !empty( $post->post_parent ) ) {
			$post_parent = $post->post_parent;
		}
		
		if ( isset( $post_parent ) ) {
			$test = get_post( $post_parent );
			if ( 'pp_opportunity' == $test->post_type ) {
				$action = 'edit';
			}
		}
	}
	
	return $action;

}