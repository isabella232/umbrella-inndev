<?php

add_action( 'pre_get_posts', 'po_admin_head' );
function po_admin_head() {
	global $pagenow, $post, $current_user;
	if ( 'post.php' == $pagenow && 'pp_opportunity' == $post->post_type ) {
		$agents = get_post_meta( $post->ID, '_pp_agents', true );
		$oktogo = false;
		if ( is_array( $agents ) ) {
			foreach ( $agents as $k => $v ) {
				if ( $current_user->ID == $v['value'] ) {
					$oktogo = $v['value'];
				}
			}
		} else {
			$oktogo = $agents;
		}
		// CHECK THE PERMISSIONS
		if ( !current_user_can( 'manage_options' ) && $oktogo != $current_user->ID ) {
			wp_redirect( admin_url( 'admin.php?page=pauopps_list' ) );
			die();
		}
	}
	
}

/**
 * Register info meta box
 *
 * @author havahula.org
 * @param none
 * @return none
 */
add_action( 'add_meta_boxes', 'po_add_meta_boxes' );
function po_add_meta_boxes() {

	global $post, $current_user, $wp_meta_boxes;

	$posts = array( 'pp_opportunity' );
	if ( in_array( $post->post_type, $posts ) ) {
		
		$form = get_option( get_post_meta( $post->ID, '_pp_form', true ) );
		
		$args = array();
		$args['action'] = 'edit';
		if ( isset( $_GET['view'] ) ) { 
			$args['action'] = 'view';
		}
		
		/* TEMPORARY
		$agents = get_post_meta( $post->ID, '_pp_agents', true );
		if ( false == $agents )
			$agents = array();
		
		if ( !is_array( $agents ) )
			$agents = array( array( 'value' => $agents, 'type' => null ) );
			
		foreach( $agents as $ak => $av ) {
			if ( $current_user->ID == $av['value'] && 'ok' == $av['type'] ) {
				$args['action'] = 'view';
			}
		}
		*/
		$args['form'] = $form;
		$args['type'] = 'post';
		$args['object'] = $post;
		$args['caps'] = 'admin';
		$args['block'] = false;
		$args = apply_filters( 'po_add_meta_boxes', $args );
		
		$boxes = array( 
						
						'pp_require_meta' => array( 
													'title' => __( 'Required Information', 'paupress' ), 
													'cb' => 'po_require_meta', 
													'context' => 'normal', 
													'priority' => 'high', 
													'args' => $args, 
						), 
						'pp_updated_meta' => array( 
													'title' => __( 'Update', 'paupress' ), 
													'cb' => 'po_updated_meta', 
													'context' => 'side', 
													'priority' => 'high', 
													'args' => $args, 
						), 
						'pp_internal_meta' => array( 
													'title' => __( 'Custom fields – Internal Only', 'paupress' ), 
													'cb' => 'po_internal_meta', 
													'context' => 'normal', 
													'priority' => 'high', 
													'args' => $args, 
						), 
						'pp_external_meta' => array( 
													'title' => __( 'Custom fields – Visible on Public Website', 'paupress' ), 
													'cb' => 'po_external_meta', 
													'context' => 'normal', 
													'priority' => 'high', 
													'args' => $args, 
						), 
						'pp_activity_meta' => array( 
													'title' => __( 'Activity', 'paupress' ), 
													'cb' => 'pp_user_activity', 
													'context' => 'normal', 
													'priority' => 'high', 
													'args' => $args, 
						), 
						
						/*
						'pp_tasklist_meta' => array( 
													'title' => __( 'Tasks', 'paupress' ), 
													'cb' => 'po_tasklist_meta', 
													'context' => 'advanced', 
													'priority' => 'default', 
													'args' => $args, 
						), 
						*/
		);
		
	    foreach ( $posts as $p ) {
	    	foreach ( $boxes as $k => $v ) {
	        	add_meta_box( $k, $v['title'], $v['cb'], $p, $v['context'], $v['priority'], array_merge( $v['args'], array( 'post_type' => $p ) ) );
	        }
	        $screen = convert_to_screen( $p );
	        $page = $screen->id;
	        foreach ( $wp_meta_boxes[$page] as $k => $v ) {
	        	foreach ( $v as $vk => $vv ) {
	        		foreach ( $vv as $vvk => $vvv ) {
		        		if ( isset( $boxes[$vvk] ) || 'commentsdiv' == $vvk ) { } else {
		        			unset( $wp_meta_boxes[$page][$k][$vk][$vvk] );
		        		}
		        	}
	        	}
	        }
	    }
	}
}


function po_require_meta( $post, $args ) {
		
	// SET THE SECURITY. 
	// WE'LL USE PAUPRESS' BUILT-IN NONCE TO USE THE META-SAVER
	// BUT ONLY ON THE BACKEND
	global $pagenow;
	if ( 'post.php' == $pagenow )
		paupress_post_actions_nonce_field();
		
	/* CALL OUR FIELD ARRAY
	$args = array();
	$args['action'] = 'edit';
	if ( isset( $_GET['view'] ) ) { 
		$args['action'] = 'view';
	}
	$args['type'] = 'post';
	$args['object'] = $post;
	*/
	pp_form_api_fields( array( po_required_fields_1(), po_required_fields_2() ), $args['args'] );
		
}


/**
 * Do the meta box
 *
 * @author havahula.org
 * @param none
 * @return html output
 */
function po_updated_meta( $post, $args ) {

	// SET THE SECURITY. 
	// WE'LL USE PAUPRESS' BUILT-IN NONCE TO USE THE META-SAVER
	// BUT ONLY ON THE BACKEND
	global $pagenow;
	if ( 'post.php' == $pagenow )
		paupress_post_actions_nonce_field();
		
	if ( isset( $_GET['view'] ) ) { echo '<script type="text/javascript">window.print();</script>'; }
				
	echo '<div class="left">';
	$localurl = explode( 'post.php', $_SERVER['REQUEST_URI'] );
	if ( isset( $_GET['view'] ) ) {
		echo '<a class="button" href="' . str_replace( '&view=1', '', admin_url( 'post.php' . $localurl[1] ) ) . '">Edit</a>';
	} else {
		echo '<a class="button" href="' . admin_url( 'post.php' . $localurl[1] ) . '&view=1">Print</a>';
	}
	echo '</div>';
	
	echo '<div class="right">';
	//echo '<a style="float: left;" href="' . wp_nonce_url( admin_url( 'post.php?post=' . $_GET['post'] . '&action=trash' ) ) . '">Delete Opportunity</a>';
	echo '<input name="original_publish" type="hidden" id="original_publish" value="Update">';
	echo '<input name="save" type="submit" class="button button-primary button-large pp-submit" accesskey="p" value="Update">';
	echo '</div><div style="clear: both;"></div>';
	
}


/**
 * Do the meta box
 *
 * @author havahula.org
 * @param none
 * @return html output
 */
function po_internal_meta( $post, $args ) {

	// SET THE SECURITY. 
	// WE'LL USE PAUPRESS' BUILT-IN NONCE TO USE THE META-SAVER
	/* BUT ONLY ON THE BACKEND
	global $pagenow;
	if ( 'post.php' == $pagenow )
		paupress_post_actions_nonce_field();
		
	// CALL OUR FIELD ARRAY
	$form = get_option( get_post_meta( $post->ID, '_pp_form', true ) );
	$args = array();
	$args['action'] = 'edit';
	if ( isset( $_GET['view'] ) ) { 
		$args['action'] = 'view';
	}
	$args['type'] = 'post';
	$args['object'] = $post;
	$args['caps'] = 'admin';
	$args['block'] = false;
	$args['form'] = $form;	
	*/
	if ( !empty( $args['args']['form']['internal'] ) ) {
		pp_form_dbs_fields( $args['args']['form']['internal'], $args['args'] );
	}	

}


/**
 * Do the meta box
 *
 * @author havahula.org
 * @param none
 * @return html output
 */
function po_external_meta( $post, $args ) {

	// SET THE SECURITY. 
	// WE'LL USE PAUPRESS' BUILT-IN NONCE TO USE THE META-SAVER
	/* BUT ONLY ON THE BACKEND
	global $pagenow;
	if ( 'post.php' == $pagenow )
		paupress_post_actions_nonce_field();
				
	// CALL OUR FIELD ARRAY
	$form = get_option( get_post_meta( $post->ID, '_pp_form', true ) );
	
	$args = array();
	$args['action'] = 'edit';
	if ( isset( $_GET['view'] ) ) { 
		$args['action'] = 'view';
	}
	$args['type'] = 'post';
	$args['object'] = $post;
	$args['caps'] = 'admin';
	$args['block'] = false;
	$args['form'] = $form;
	*/
	if ( !empty( $args['args']['form']['optional'] ) ) {
		pp_form_dbs_fields( $args['args']['form']['optional'], $args['args'] );
	}
	
}


function pp_user_activity( $post, $args ) {
		
	// SET THE SECURITY. 
	// WE'LL USE PAUPRESS' BUILT-IN NONCE TO USE THE META-SAVER
	// BUT ONLY ON THE BACKEND
	global $pagenow;
	if ( 'post.php' == $pagenow )
		paupress_post_actions_nonce_field();
		
	/* CALL OUR FIELD ARRAY
	$args = array();
	$args['action'] = 'edit';
	if ( isset( $_GET['view'] ) ) { 
		$args['action'] = 'view';
	}
	$args['type'] = 'post';
	$args['object'] = $post;
	*/
	$targs = $args['args']['object'];
	$hargs = array();
	$hargs['user_id'] = $targs->post_author;
	$hargs['action'] = 'edit';
	$hargs['paupress_cap'] = 'admin';
	$hargs['ok_edit'] = true;
	$hargs['action_query'] = array( 'post_parent' => $targs->ID );
	
	echo '<div class="right">'.pp_get_user_action_launch( $hargs ).'</div>';
	pp_user_history( $hargs );
	echo '<div style="clear: both;"></div>';
}


/**
 * Do the meta box
 *
 * @author havahula.org
 * @param none
 * @return html output
 */
function po_tasklist_meta( $post, $args ) {

	$tasks = array();
	$tasks[] = array(
						's' => 'A Sample Title', 
						'a' => '245', 
						'd' => '09/05/2014', 
						'u' => 'Not Started', 
						'p' => 'High', 
						'g' => '142', 
	);
	$tasks[] = array(
						's' => 'No Related Client', 
						'a' => false, 
						'd' => '09/16/2014', 
						'u' => 'Not Started', 
						'p' => 'High', 
						'g' => '142', 
	);	
	$tasks[] = array(
						's' => 'No Due Date', 
						'a' => '245', 
						'd' => false, 
						'u' => 'Not Started', 
						'p' => 'High', 
						'g' => '142', 
	);	
	echo '<table class="wp-list-table widefat fixed" style="border: none;">';
	echo '<thead><tr><td>Title</td><td>Client</td><td>Due</td><td>Status</td><td>Priority</td><td>Assigned:</td></tr></thead>';
	foreach ( $tasks as $k => $v ) {
		//$client = get_user_by( 'id', $a );
		//$agent = get_user_by( 'id', $g );
		echo '<tr><td><input type="checkbox" class="task-check" />&nbsp;<a href="">'.$v['s'].'</a></td><td><a href="">'.pp_get_user_name( $v['a'] ).'</a></td><td>'.$v['d'].'</td><td>'.$v['u'].'</td><td>'.$v['p'].'</td><td><a href="">'.pp_get_user_name( $v['g'] ).'</a></td></tr></thead>';
	}
	echo '</table>';
	?>
	<script type="text/javascript">
		jQuery.noConflict();
		jQuery(document).ready(function(){
			jQuery(document).on('click','.task-check',function(){
				if ( jQuery(this).is(':checked') ) {
					jQuery(this).closest('tr').addClass('pp-disabled');
				} else {
					jQuery(this).closest('tr').removeClass('pp-disabled');
				}
			});
		});
	</script>
	<?php
}



/**
 * Do the meta box
 *
 * @author havahula.org
 * @param none
 * @return html output
 */
//add_action( 'edit_form_after_title', 'po_required_meta' );
function po_required_meta() {
	
	global $post;
	
	if ( 'pp_opportunity' == $post->post_type ) {

		// SET THE SECURITY. 
		// WE'LL USE PAUPRESS' BUILT-IN NONCE TO USE THE META-SAVER
		// BUT ONLY ON THE BACKEND
		global $pagenow;
		if ( 'post.php' == $pagenow )
			paupress_post_actions_nonce_field();
			
		// CALL OUR FIELD ARRAY
		$args = array();
		$args['action'] = 'edit';
		if ( isset( $_GET['view'] ) ) { 
			$args['action'] = 'view';
		}
		$args['type'] = 'post';
		$args['object'] = $post;
		pp_form_api_fields( array( po_required_fields_1(), po_required_fields_2() ), $args['args'] );
		
	}

}


function po_id() {
	global $post;
	if ( !empty( $post ) ) {
		printf( __( 'ID: %s', 'paupress' ), '<strong>'.$post->ID.'</strong>' );
	}
}


function po_form() {
	global $post;
	if ( !empty( $post ) ) {
		$form = get_option( get_post_meta( $post->ID, '_pp_form', true ) );
		if ( !empty( $form['settings']['_pp_trans_type'] ) ) {
			$new_fields[] = array( 
								'meta' => array( 
									'source' => 'pauopps', 
									'meta_key' => '_pp_opps_amount', 
									'name' => __( 'Enable online payment?', 'paupress' ), 
									'help' => '', 
									'lpos' => 'none', 
									'options' => array( 
														'field_type' => 'checkbox', 
														'req' => false, 
														'public' => false, 
														'choices' => false 
									) 
								), 
								'action' => 'edit', 
								'type' => 'post', 
								'id' => $post->ID, 
			);
			echo '<ul>';
			foreach ( $new_fields as $k => $v ) {
				paupress_get_field( $v );
			}
			echo '</ul>';
			$pauref = urlencode( serialize( array( 'rel' => 'pp_public_opp', 'pau_form' => $post->ID ) ) );
			echo '<p class="code-help"><strong>' . __( 'Direct Link', 'paupress' ) . '</strong><br /><textarea readonly class="fin-btn">' . home_url() . '?pauref=' . $pauref . '</textarea></p>';
		}
		printf( __( 'Template: %s', 'paupress' ), '<em>'.$form['title'].'</em>' );
	}
}


function po_user() {
	global $post;
	if ( !empty( $post ) ) {
		$u = get_user_by( 'id', $post->post_author );
		echo '<a href="' . admin_url( 'users.php?page=paupress_edit_user&user_id=' . $u->ID ) . '">' . pp_get_user_name( $u ) . '</a>';
	}
}


function po_agent() {
	global $post;
	if ( !empty( $post ) ) {
		$agent = get_post_meta( $post->ID, '_pp_agent', true );
		if ( !empty( $agent ) ) {
			$u = get_user_by( 'id', $agent );
			echo '<a href="' . admin_url( 'users.php?page=paupress_edit_user&user_id=' . $u->ID ) . '">' . pp_get_user_name( $u ) . '</a>';
		} else {
			echo __( 'System Generated', 'paupress' );
		}
	}
}


// THERE BE DRAGONS...
/**
 * Set the fields left
 *
 * @author havahula.org
 * @param none
 * @return none
 */
function po_required_fields_1() {

	return apply_filters( 'pauopps_required_fields_1', array( 
		
		array( 'meta' => array( 
								'source' => 'paupress', 
								'meta_key' => '_po_user', 
								'name' => __( 'Client', 'paupress' ),
								'help' => '', 
								'options' => array( 
													'field_type' => 'plugin',
													'req' => false, 
													'class' => false, 
													'public' => false, 
													'choices' => 'po_user'
								) 
		) ), 
		
		array( 'meta' => array( 
								'source' => 'paupress', 
								'meta_key' => '_pp_agent', 
								'name' => __( 'Created By', 'paupress' ),
								'help' => '', 
								'options' => array( 
													'field_type' => 'plugin',
													'req' => false, 
													'public' => false, 
													'choices' => 'po_agent',  
								) 
		) ),
				
		array( 'meta' => array( 
								'source' => 'paupress', 
								'meta_key' => '_po_stage', 
								'name' => __( 'Stage', 'paupress' ),
								'help' => '', 
								'options' => array( 
													'field_type' => 'select',
													'req' => false, 
													'public' => false, 
													'choices' => pauopps_stages() 
								) 
		) ), 
		
		array( 'meta' => array( 
								'source' => 'paupress', 
								'meta_key' => '_po_probability', 
								'name' => __( 'Probability', 'paupress' ),
								'help' => '', 
								'options' => array( 
													'field_type' => 'select',
													'req' => false, 
													'public' => false, 
													'choices' => pauopps_probabilities() 
								) 
		) ), 
		
		array( 'meta' => array( 
								'source' => 'paupress', 
								'meta_key' => '_pp_description', 
								'name' => __( 'Description', 'paupress' ),
								'help' => '', 
								'field_type' => 'textarea',
								'req' => false, 
								'public' => false, 
								'choices' => false,  
		) ), 
		
									
	) );

}


/**
 * Set the fields right
 *
 * @author havahula.org
 * @param none
 * @return none
 */
function po_required_fields_2() {

	return apply_filters( 'pauopps_required_fields_2', array(  
		
		array( 'meta' => array( 
								'source' => 'paupress', 
								'meta_key' => '_pp_agents', 
								'name' => __( 'Agent', 'paupress' ),
								'help' => '', 
								'options' => array( 
													'field_type' => 'select',
													'req' => false, 
													'public' => false, 
													//'choices' => 'po_agents', 
													'choices' => po_agents(), 
								) 
		) ), 
		
		array( 'meta' => array( 
								'source' => 'paupress', 
								'meta_key' => '_po_close_date', 
								'name' => __( 'Close Date', 'paupress' ),
								'help' => '', 
								'options' => array( 
													'field_type' => 'date',
													'req' => false, 
													'public' => false, 
													'choices' => false 
								) 
		) ), 
		
		array( 'meta' => array( 
								'source' => 'paupress', 
								'meta_key' => '_po_amount', 
								'name' => __( 'Amount', 'paupress' ),
								'help' => '', 
								'options' => array( 
													'field_type' => 'number',
													'req' => false, 
													'public' => false, 
													'choices' => false 
								) 
		) ),  
		
		array( 'meta' => array( 
								'source' => 'paupress', 
								'meta_key' => '_pp_form', 
								'name' => '',
								'help' => '', 
								'options' => array( 
													'field_type' => 'plugin',
													'req' => false, 
													'public' => false, 
													'choices' => 'po_form' 
								) 
		) ),
		
		array( 'meta' => array( 
								'source' => 'paupress', 
								'meta_key' => '_po_id', 
								'name' => '',
								'help' => '', 
								'options' => array( 
													'field_type' => 'plugin',
													'req' => false, 
													'public' => false, 
													'choices' => 'po_id' 
								) 
		) ),
		
	) );

}


function pauopps_stages() {
	$stages = get_option( 'pauopps_stages' );
	if ( empty( $stages ) )
		$stages = pauopps_stages_default();
		
	return apply_filters( 'pauopps_stages', $stages );
}

function pauopps_probabilities() {
	return array( 
					'10' => '10%', 
					'25' => '25%', 
					'50' => '50%', 
					'75' => '75%', 
					'90' => '90%', 
	);
}


function po_agents() {
	global $wpdb;
	$query = $wpdb->prepare( "SELECT DISTINCT $wpdb->users.ID FROM $wpdb->users INNER JOIN $wpdb->usermeta ON ($wpdb->users.ID = $wpdb->usermeta.user_id) WHERE 1=1 AND ($wpdb->usermeta.meta_key = 'wp_capabilities' AND $wpdb->usermeta.meta_value LIKE '%s' || $wpdb->usermeta.meta_value LIKE '%s') ORDER BY $wpdb->users.ID DESC", '%s:13:"administrator"%', '%s:8:"pp_agent"%' );
	$results = $wpdb->get_results( $query, ARRAY_A );
	$agents = array();
	foreach( $results as $k => $v ) {
		$u = get_user_by( 'id', $v['ID'] );
		$agents[$u->ID] = $u->first_name . ' ' . $u->last_name;
	}
	return $agents;
}

add_filter( 'pp_field_meta_post_get', 'pauopps_field_meta_post_get', 10, 2 );
function pauopps_field_meta_post_get( $meta, $field ) {
	global $current_user;
	if ( '_pp_agents' == $meta['meta_key'] ) {
		if ( !empty( $field['value'] ) && !current_user_can( 'manage_options' ) ) {
			$meta['disabled'] = 'disabled';
		}
	}
	return $meta;
}


// THIS IS A POSSIBLY HIGH VARIATION POINT
function po2_agents( $args = null ) {

	// SET THE DEFAULTS TO BE OVERRIDDEN AS DESIRED
	$defaults = array( 
					'fdata' => false, 
					'fvalue' => false, 
					'faction' => false, 
					'ftype' => false, 
					'fmeta' => false, 
				);
	
	// PARSE THE INCOMING ARGS
	$args = wp_parse_args( $args, $defaults );

	// EXTRACT THE VARIABLES
	extract( $args, EXTR_SKIP );
	
	//print_r($args);
	global $current_user, $post;
	if ( current_user_can( 'manage_options' ) ) { //$current_user->ID == get_post_meta( $post->ID, '_pp_agent', true )
		
		$field = array( 
						'source' => '', 
						'meta_key' => $fmeta['meta_key'], 
						'name' => '',
						'help' => '', 
						'lpos' => 'top', 
						'field_type' => 'multitext',
						'req' => false, 
						'public' => false, 
						'choices' => array( 
											'no' => __( 'Refuse', 'paupress' ), 
											'ok' => __( 'Signed', 'paupress' ) 
						), 
						'lookup' => 'user', 
		);
		
		$args = array();
		$args['meta'] = $field;
		$args['type'] = 'post';
		$args['action'] = 'edit';
		$args['id'] = $post->ID;
		
		echo '<ul>';
		paupress_get_field( $args );
		echo '</ul>';
		
	} else {
		
		$agents = get_post_meta( $post->ID, $fmeta['meta_key'], true );
		$agent = false;
		if ( false == $agents )
			$agents = array();
			
		if ( !is_array( $agents ) )
			$agents = array( array( 'value' => $agents, 'type' => false ) );
		
		foreach ( $agents as $k => $v ) {
			if ( $current_user->ID == $v['value'] ) {
				$agent = $v;
			}
		}
		
		if ( false != $agent ) {
			if ( 'ok' != $agent['type'] ) { 
				
				$choices[] = array( 
					array( 'meta' => array( 
											'source' => 'paupress', 
											'meta_key' => '_pp_single_action', 
											'name' => sprintf( __( 'Sign this %s?', 'compaid' ), pp_get_user_name( $current_user ) ), 
											'help' =>'', 
											'lpos' => 'top', 
											'field_type' => 'select',
											'req' => false, 
											'public' => false, 
											'choices' => array( 
																'no' => __( 'Declined', 'paupress' ), 
																'ok' => __( 'Signed', 'paupress' ) 
											), 
					) ), 
					array( 'meta' => array( 
											'source' => 'paupress', 
											'meta_key' => '_pp_single_agent', 
											'name' => '', 
											'help' =>'', 
											'lpos' => 'none', 
											'field_type' => 'hidden',
											'req' => false, 
											'public' => false, 
											'choices' => $current_user->ID, 
					) ),
				);
				
				pp_form_api_fields( $choices, array( 'type' => 'post', 'object' => $post, 'block' => false ) );
			
			} else {
				echo '<p>'.sprintf( __( 'You have signed this %s', 'compaid' ), pp_get_user_name( $current_user ) ).'</p>'; 
			}
			
		}
		
	}

}

add_filter( 'paupress_modify_action_meta', 'po_modify_action_meta', 10, 4 );
function po_modify_action_meta( $_pp_post, $id, $post_type, $post_data ) {
	if ( 'pp_opportunity' == $post_type ) {
	
		$opportunity = get_post( $id );
		$agents = get_post_meta( $id, '_pp_agents', true );
		
		// IF WE'RE SETTING AN AGENT, NOTIFY THE AGENT
		if ( !empty( $_pp_post['_pp_agents'] ) ) {
		
			if ( false == $agents || $agents != $_pp_post['_pp_agents'] ) {

				$agent = get_user_by( 'id', $_pp_post['_pp_agents'] );
				
				$message = '<p>' . sprintf( __( 'You have been assigned a new opportunity: %1$s', 'paupress' ), $opportunity->post_title ) . '</p>';
				$message .= '<p>' . sprintf( __( 'Please login to see the details: %1$s', 'paupress' ), admin_url( '/post.php?post='.$id.'&action=edit' ) ) . '</p>'; 
				
				// RESET MAIL ARGUMENTS
				$mail_args = array();
				$mail_args['to'] = $agent->user_email;
				$mail_args['from_name'] = get_option( 'blogname' );
				$mail_args['from_email'] = get_option( 'admin_email' );
				$mail_args['subject'] = sprintf( __( 'New Opportunity %1$s', 'paupress' ), pp_get_user_name( $opportunity->post_author ) );
				$mail_args['message'] = $message;

				paumail_wp_email( $mail_args );
				
			}
		
		}
					
	}
	
	return $_pp_post;

}


//add_action( 'paupress_process_action_meta', 'po_process_action_meta', 10, 2 );
function po_process_action_meta( $id, $post_type ) {
	
	if ( 'pp_opportunity' == $post_type ) {

		// NOW, DECIDE IF WE SHOULD LOCK THE ASSESSMENT
		$agents = get_post_meta( $id, '_pp_agents', true );
		$closed = true;
		if ( false == $agents )
			return false;

		foreach ( $agents as $k => $v ) {
			if ( 'ok' != $v['type'] ) {
				$closed = false;
			}
		}
		/*
		if ( false != $closed ) {
			$rate = 0;
			$form = get_option( get_post_meta( $id, '_pp_form', true ) );
			foreach ( $form['internal'] as $k => $v ) {
				$field = paupress_get_option( $k );
				if ( 'textarea' != $field['field_type'] ) {
					$rate = $rate + floatval( $_POST['_pp_post'][$k] );
				}
			}
			update_user_meta( $_POST['post_author'], paupress_key( 'pp_rating', 'paupress' ), $rate );
		}
		*/
		
	}
	
}


function pauopps_post_options( $fields, $post ) {
	if ( 'pp_opportunity' == $post->post_type ) {
		foreach ( $fields as $k => $v ) {
			switch( $v['meta']['meta_key'] ) {
				case '_pp_mail_status' :
					$fields[$k]['meta']['options']['field_type'] = 'hidden';
					$fields[$k]['meta']['options']['default'] = 'send';
					$fields[$k]['meta']['lpos'] = 'none';
				break;
				
				case '_pp_mail_method' :
					$fields[$k]['meta']['options']['field_type'] = 'hidden';
					$fields[$k]['meta']['options']['default'] = 'wp_html';
					$fields[$k]['meta']['lpos'] = 'none';
				break;
				
				case '_pp_mail_segment' :
					$fields[$k]['meta']['options']['field_type'] = 'hidden';
					$fields[$k]['meta']['options']['default'] = 'null';
					$fields[$k]['meta']['lpos'] = 'none';
				break;
				
				case '_pp_mail_destination' :
					$fields[$k]['meta']['options']['field_type'] = 'hidden';
					$fields[$k]['meta']['options']['default'] = 'send';
					$fields[$k]['meta']['lpos'] = 'none';
				break;
				
				case '_pp_mail_log' :
					$fields[$k]['meta']['options']['field_type'] = 'hidden';
					$fields[$k]['meta']['options']['default'] = 'true';
					$fields[$k]['meta']['lpos'] = 'none';
				break;
				
				case '_pp_mail_template' :
					$fields[$k]['meta']['options']['field_type'] = 'hidden';
					$fields[$k]['meta']['options']['default'] = 'wp_1';
					$fields[$k]['meta']['lpos'] = 'none';
				break;
			}
		}
		$new_fields[] = array( 
							'meta' => array( 
								'source' => 'pauopps', 
								'meta_key' => '_pp_opps_note', 
								'name' => __( 'Message', 'paupress' ), 
								'help' => '', 
								'options' => array( 
													'field_type' => 'textarea', 
													'req' => false, 
													'public' => false, 
													'choices' => false 
								) 
							), 
							'action' => 'edit', 
							'type' => 'post', 
							'id' => $post->ID, 
		);
		/*
		$new_fields[] = array( 
							'meta' => array( 
								'source' => 'pauopps', 
								'meta_key' => '_pp_opps_amount', 
								'name' => __( 'Include the amount and an option to pay?', 'paupress' ), 
								'help' => '', 
								'options' => array( 
													'field_type' => 'checkbox', 
													'req' => false, 
													'public' => false, 
													'choices' => false 
								) 
							), 
							'action' => 'edit', 
							'type' => 'post', 
							'id' => $post->ID, 
		);
		
		$new_fields[] = array( 
							'meta' => array( 
								'source' => 'pauopps', 
								'meta_key' => '_pp_opps_fields', 
								'name' => __( 'Include the Custom Public Fields?', 'paupress' ), 
								'help' => '', 
								'options' => array( 
													'field_type' => 'checkbox', 
													'req' => false, 
													'public' => false, 
													'choices' => false 
								) 
							), 
							'action' => 'edit', 
							'type' => 'post', 
							'id' => $post->ID, 
		);
		*/
		$new_fields[] = array( 
							'meta' => array( 
								'source' => 'pauopps', 
								'meta_key' => '_pp_opps_users', 
								'name' => '', 
								'help' => '', 
								'options' => array( 
													'field_type' => 'plugin', 
													'default' => false, 
													'public' => false, 
													'choices' => 'pp_opps_users',  
								) 
							), 
							'action' => 'edit', 
							'type' => 'post', 
							'id' => $post->ID, 
		);
		
		$receipt = array_shift( $fields );
		$returna = array_merge( $new_fields, $fields );
		array_unshift( $returna, $receipt );
		$fields = $returna;
	}
	
	return $fields;
}


function pp_opps_users() {
	global $post;
	echo '<input type="hidden" name="users[]" value="'.$post->post_author.'" />';
}


function pauopps_ajax_mail_by_post( $args ) {
	$post = get_post( $args['post_id'] );
	if ( 'pp_opportunity' == $post->post_type ) {
		$form = get_option( get_post_meta( $post->ID, '_pp_form', true ) );
		ob_start();
		echo wpautop( stripslashes( $args['_pp_opps_note'] ) );
		
		//if ( 'true' == $args['_pp_opps_fields'] ) {
			$fargs = array();
			$fargs['caps'] = 'user';
			$fargs['type'] = 'post';
			$fargs['block'] = false;
			$fargs['action'] = 'view';
			$fargs['object'] = $post;
			pp_form_dbs_fields( $form['optional'], $fargs );
		//}
		
		//if ( 'true' == $args['_pp_opps_amount'] ) {
			$pauref = urlencode( serialize( array( 'rel' => 'pp_public_opp', 'pau_form' => $post->ID ) ) );
			$amount = paupay_amount( get_post_meta( $post->ID, '_pp_base_amount', true ) );
			$t_type = $form['settings']['_pp_trans_type'];
			$t_types = paupay_get_trans_types();
			if ( false != get_option( 'paupay_button_background' ) ) {
				$bg = get_option( 'paupay_button_background' );
			}else {
				$bg = '#990000';
			}
			if ( false != get_option( 'paupay_button_color' ) ) {
				$co = get_option( 'paupay_button_color' );
			} else {
				$co = '#FFFFFF';
			}
			?>
			<p style="text-align: center">
				<table cellspacing="0" cellpadding="0">
					<tr>
						<td align="center" width="300" height="40" bgcolor="<?php echo $bg; ?>" style="-webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; color: <?php echo $co; ?>; display: block;">
							<a href="<?php echo home_url() . '?pauref=' . $pauref; ?>" style="font-size:16px; text-decoration: none; line-height:40px; width:100%; display:inline-block"><span style="color: <?php echo $co; ?>"><?php printf( __( 'Make Your %1$s Now - %2$s', 'paupress' ), $t_types[$t_type], $amount ); ?></span></a>
						</td> 
					</tr>
				</table>
			</p>
			<?php
		//}
		
		$post->post_content = ob_get_clean();
		$args['post'] = $post;
	}
	return $args;
}


function pauopps_modify_action_meta( $paupress_data, $id ) {

	if ( 'pp_opportunity' == get_post_type( $id ) ) {
		if ( !empty( $paupress_data['_pp_opps_amount'] ) && 'true' == $paupress_data['_pp_opps_amount'] ) {
			$form = get_option( get_post_meta( $id, '_pp_form', true ) );
			$pauref = urlencode( serialize( array( 'rel' => 'pp_public_opp', 'pau_form' => $id ) ) );			
			
			update_post_meta( $id, '_pp_base_amount', $paupress_data['_po_amount'] );
			update_post_meta( $id, '_pp_trans_type', $form['settings']['_pp_trans_type'] );
			update_post_meta( $id, '_pp_inventory', '1' );
			update_post_meta( $id, '_pp_alternate_url', home_url() . '?pauref=' . $pauref );
		}
	}		
	return $paupress_data;
	
}