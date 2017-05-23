<?php

// REGISTER THE POST TYPE
function pauopps_post() {  
	
  $pp_opp = strtolower( PAUOPPS_OPPORTUNITY );
  $pp_opps = strtolower( PAUOPPS_OPPORTUNITIES );
  $pp_caps = array( 
  						'publish_posts' => 'publish_pp_'.$pp_opps,
  						'edit_posts' => 'edit_pp_'.$pp_opps,
  						'edit_others_posts' => 'edit_others_pp_'.$pp_opps,
  						'delete_posts' => 'delete_pp_'.$pp_opps,
  						'delete_others_posts' => 'delete_other_pp_'.$pp_opps,
  						'read_private_posts' => 'read_private_pp_'.$pp_opps,
  						'edit_post' => 'edit_pp_'.$pp_opp,
  						'delete_post' => 'delete_pp_'.$pp_opp,
  						'read_post' => 'read_pp_'.$pp_opp, 
  						
  						'publish_posts' => 'publish_'.$pp_opps,
  						'edit_posts' => 'edit_'.$pp_opps,
  						'edit_others_posts' => 'edit_others_'.$pp_opps,
  						'delete_posts' => 'delete_'.$pp_opps,
  						'delete_others_posts' => 'delete_other_'.$pp_opps,
  						'read_private_posts' => 'read_private_'.$pp_opps,
  						'edit_post' => 'edit_'.$pp_opp,
  						'delete_post' => 'delete_'.$pp_opp,
  						'read_post' => 'read_'.$pp_opp
  );
  $pp_agent_caps= array( 
  						'edit_others_posts',
  						'delete_posts',
  						'delete_others_posts',
  						'delete_post'
  );
  
  $labels = array(
    'name' => PAUOPPS_OPPORTUNITIES,
    'singular_name' => PAUOPPS_OPPORTUNITY,
    'add_new' => sprintf( __( 'Add New %1$s', 'paupress' ), PAUOPPS_OPPORTUNITY ),
    'add_new_item' => sprintf( __( 'Add New %1$s', 'paupress' ), PAUOPPS_OPPORTUNITY ),
    'edit_item' => sprintf( __( 'Edit %1$s', 'paupress' ), PAUOPPS_OPPORTUNITY ),
    'new_item' => sprintf( __( 'New %1$s', 'paupress' ), PAUOPPS_OPPORTUNITY ),
    'view_item' => sprintf( __( 'View %1$s', 'paupress' ), PAUOPPS_OPPORTUNITY ),
    'search_items' =>  sprintf( __( 'Search %1$s', 'paupress' ), PAUOPPS_OPPORTUNITIES ),
    'not_found' =>  sprintf( __( 'No %1$s Found', 'paupress' ), PAUOPPS_OPPORTUNITIES ),
    'not_found_in_trash' => sprintf( __( 'No %1$s Found in Trash', 'paupress' ), PAUOPPS_OPPORTUNITIES ), 
    'parent_item_colon' => ''
  );
  
  $args = array(
   /*
    'labels' => $labels,
    'public' => false,
    'publicly_queryable' => false,
    'show_ui' => false, 
    'exclude_from_search' => true,
    'query_var' => false,
    'rewrite' => array( 'slug' => 'opportunity' ),
    'has_archive' => false,
    'show_in_menu' => false, 
    'menu_icon' => PAUPRESS_URL . '/assets/g/event-menu.png', 
    'capability_type' => 'post',
    'hierarchical' => false,
    'menu_position' => null,
    'supports' => array( 'title', 'editor', 'comments' )
    */
    'labels' => $labels, 
    'description' => '', 
    'public' => false, 
    'publicly_queryable' => false, 
    'exclude_from_search' => true, 
    'show_ui' => true, 
    'menu_position' => null, 
    'menu_icon' => null, 
    'capability_type' => 'post', 
    'capabilities' => $pp_caps, 
    'map_meta_cap' => false, 
    'hierarchical' => false, 
    'taxonomies' => array(), 
    'permalink_epmask' => EP_PERMALINK, 
    'has_archive' => false, 
    'rewrite' => false, 
    'query_var' => true, 
    'can_export' => true, 
    'show_in_nav_menus' => false, 
    'show_in_menu' => false, 
    'supports' => array( 'title' ), // , 'comments'
    
    
  ); 
  register_post_type( 'pp_opportunity', $args );
  
  global $wp_roles;
  
  // SET THE AGENT ROLE
  if ( !isset( $wp_roles->pp_agent ) ) {
  	add_role( 'pp_agent', __( 'Agent', 'paupress' ), array( 'read' => true ) );
  	//print_r($wp_roles);
  } else {
 	//print_r($wp_roles);
	//$wp_roles->remove_role( 'pp_agent' );
	//$wp_roles->add_cap( 'pp_agent', 'read' );
  }
  $wp_roles->add_cap( 'pp_agent', 'read' );
  
  foreach ( $pp_caps as $k => $v ) {
  	$wp_roles->add_cap( 'administrator', $v );
  	//if ( !in_array( $k, $pp_agent_caps ) ) {
  		$wp_roles->add_cap( 'pp_agent', $v );
  	//}
  }

}

// MODIFY THE MESSAGES
add_filter( 'post_updated_messages', 'pauopps_updated_messages' );
function pauopps_updated_messages( $messages ) {

	$messages['pp_opportunity'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => PAUOPPS_OPPORTUNITY . ' ' . PAUPRESS_UPDATED,
		2 => __('Custom field updated.'),
		3 => __('Custom field deleted.'),
		4 => PAUOPPS_OPPORTUNITY . ' ' . PAUPRESS_UPDATED,
		5 => isset($_GET['revision']) ? sprintf( __('%s restored to revision from %s'), $pp_opp, wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => PAUOPPS_OPPORTUNITY . ' ' . PAUPRESS_SAVED, // FORMERLY PUBLISHED
		7 => PAUOPPS_OPPORTUNITY . ' ' . PAUPRESS_SAVED,
		8 => PAUOPPS_OPPORTUNITY . ' ' . PAUPRESS_SAVED, // FORMERLY SUBMITTED
		9 => PAUOPPS_OPPORTUNITY . ' ' . PAUPRESS_SAVED, // sprintf( __('Page scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview page</a>'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) )
		10 => PAUOPPS_OPPORTUNITY . ' ' . PAUPRESS_UPDATED, // sprintf( __('Page draft updated. <a target="_blank" href="%s">Preview page</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) )
	);
	return $messages;
}

function pauopps_role() {
	
}


add_action( 'do_meta_boxes', 'pauopps_remove_metaboxes' );
function pauopps_remove_metaboxes() {
	remove_meta_box( 'commentstatusdiv', 'pp_opportunity', 'normal' );
}


function pauopps_list() {
?>
	<div class="wrap">
		<form method="get" action="" enctype="multipart/form-data">
		<input type="hidden" name="page" value="<?php echo $_GET['page']; ?>" />
		<h2><?php echo PAUOPPS_OPPORTUNITY; ?></h2>
		<div class="right" style="width: 100%; padding-bottom: 10px;">
			<a id="pp-new-opp" class="button-primary"><?php _e( 'New Opportunity', 'paupress' ); ?></a>
			<script type="text/javascript">
				jQuery(document).ready(function(){
					jQuery(document).on('click', '#pp-new-opp', function() {
						tb_show(null,paupressAdminAjax.ajaxadmin+'/users.php?page=paupress_modal_action&action=newopp&TB_iframe=true&height=500&width=900',null);
					});
				});
			</script>
		</div>
	<?php
	
	$opps = new PP_Opps_Table();
	$opps->prepare_items();
	$opps->display();
	
	echo '</form>';
	echo '</div>';
	
}


function pauopps_tasks() {
?>
	<div class="wrap">
		<form method="post" action="" enctype="multipart/form-data">
		<h2>Tasks</h2>
	<?php
	
	$opps = new PP_Tasks_Table();
	$opps->prepare_items();
	$opps->display();
	
	echo '</form>';
	echo '</div>';
	
}


function pauopps_newopp_1() {

	return array( 
		
		array( 'meta' => array( 
								'source' => 'paupress', 
								'meta_key' => 'mi', 
								'name' => PAUPRESS_SELSOM,
								'help' => '', 
								'field_type' => 'lookup',
								'req' => false, 
								'public' => false, 
								'meta_target' => 'user', 
								'class' => array( 'pp-modal' ), 
		) ), 
									
	);

}


function pauopps_newopp_2() {

	return array( 
		
		array( 'meta' => array( 
								'source' => 'paupress', 
								'meta_key' => 'template', 
								'name' => PAUPRESS_SELTEM,
								'help' => '', 
								'field_type' => 'radio',
								'class' => array( 'pauopps-template' ), 
								'req' => false, 
								'public' => false, 
								'choices' => pauopp_get_templates() 
		) ), 
									
	);

}

function pauopp_get_templates() {
	global $wpdb;
	$ftype = substr( 'pp_opportunity', 0 );
	$ctype = '\'%s:4:"type";s:'.strlen($ftype).':"'.$ftype.'"%\'';
	$query = "SELECT $wpdb->options.option_name, $wpdb->options.option_value FROM $wpdb->options WHERE $wpdb->options.option_name LIKE '_pp_form_%' AND $wpdb->options.option_value LIKE $ctype";
	$dback = $wpdb->get_results( $query, ARRAY_A );
	$opt = array();
	foreach( $dback as $k => $v ) {
		$f = maybe_unserialize( $v['option_value'] );
		$opt[$f['id']] = $f['title'];
	}
	return $opt;
}


add_action( 'paumodal_switch', 'pauopps_modal_switch' );
function pauopps_modal_switch( $action ) {
	switch ( $action ) {
		
		case 'newopp' :
    	?>
    	<form id="pautainer" action="">
    	<?php
    		$mi = false;
			if ( isset( $_GET['mi'] ) ) {
				$user = get_user_by( 'id', $_GET['mi'] );
				if ( $user ) {
					$re = $user->first_name . ' ' . $user->last_name;
					$mi = $user->ID;
				}
			}
			
			$args = array();
			$args['action'] = 'edit';
			$args['type'] = 'post';
			$args['object'] = false;
			pp_form_api_fields( array( pauopps_newopp_1(), pauopps_newopp_2() ), $args );
		/*
		?>
    	
    		<?php if ( false != $mi ) { ?>
    			<input type="hidden" class="actual" name="mi" value="<?php echo $mi; ?>" />
    		<?php } else { ?>
			<div>
				<input type="text" class="pp-autocomplete-user" id="pp-ss-user" placeholder="Choose someone" value="" />
				<input type="hidden" class="pp-lookup-value" name="mi" value="" />
			</div>
			<?php } ?>
			
		<?php
			global $wpdb;
			$ftype = substr( 'opportunity', 0 );
			$ctype = '\'%s:4:"type";s:'.strlen($ftype).':"'.$ftype.'"%\'';
			$query = "SELECT $wpdb->options.option_name, $wpdb->options.option_value FROM $wpdb->options WHERE $wpdb->options.option_name LIKE '_pp_form_%' AND $wpdb->options.option_value LIKE $ctype";
			$dback = $wpdb->get_results( $query, ARRAY_A );
			$opt = array();
			foreach( $dback as $k => $v ) {
				$f = maybe_unserialize( $v['option_value'] );
				echo '<input type="radio" name="template" value="' . $f['id'] . '" /> ' . $f['title'];
			}
		*/			
		?>
			<input id="post-go" type="submit" class="button-primary" name="post-go" value="Submit" />
		</form>	
			<script type="text/javascript">
				jQuery(document).ready(function(){
					jQuery(document).on('click','#post-go',function(){
						
						jQuery(this).closest('form').submit( function() { return false; } );
						
						if ( 
							jQuery('.pauopps-template:checked').length && 
							jQuery('#mi').val() 
						) {
						} else {
							console.log(jQuery('.pauopps-template:checked').val());
							return false;
						}
						
						// CDATA TO AVOID VALIDATION ERRORS
						//<![CDATA[
						var str = jQuery(this).closest('form').serialize();
						// ]]>
						  
						jQuery.post( 
							paupressAdminAjax.ajaxurl, { 
							action : 'pauopps_autocomplete_done', 
							data : str, 
							paupress_admin_nonce : paupressAdminAjax.paupress_admin_nonce
							},
						    function( response ) {
						        window.top.location.href = response;
						        //jQuery('#fa').closest('.box').html(response);
						    }
						);
						
					});
				});
			</script>
		<?php
		break;
	}
}

add_action( 'wp_ajax_pauopps_autocomplete_done', 'pauopps_autocomplete_done' );
function pauopps_autocomplete_done() {

	// SET THE DEFAULTS TO BE OVERRIDDEN AS DESIRED
	if ( !empty( $_POST['data'] ) ) {
	
		global $current_user;
		parse_str( $_POST['data'], $_POST );
		
		$postdata['post_status'] = 'publish';
		$postdata['post_author'] = $_POST['_pp_post']['mi'];
		$postdata['post_type'] = 'pp_opportunity';
		$postdata['post_title'] = sprintf( __( 'New %1$s', 'paupress' ), PAUOPPS_OPPORTUNITY );
		$post_id = wp_insert_post( $postdata, true );
		
		update_post_meta( $post_id, '_pp_form', $_POST['_pp_post']['template'] );
		update_post_meta( $post_id, '_pp_agent', $current_user->ID );
		update_post_meta( $post_id, '_pp_agents', $current_user->ID );
		
		do_action( 'pauopps_insert_post_new', $post_id );
		
		echo admin_url() . 'post.php?post='.$post_id.'&action=edit';
	
	}
	
	die();
}


function pauopps_switch( $rel ) {

	global $rel;
	//if ( isset( $_POST['rel'] ) ) { $rel = $_POST['rel']; }
		
	switch( $_POST['rel'] ) {
		
		case 'pp_public_opp' : 
			
			$form = get_option( get_post_meta( $_POST['pau_form'], '_pp_form', true ) );
			$args = array();
			$args['caps'] = 'user';
			$args['type'] = 'post';
			$args['block'] = false;
			$args['action'] = 'view';
			$args['object'] = get_post( $_POST['pau_form'] );
			pp_form_dbs_fields( $form['optional'], $args );
			echo '<div class="right">' . paupay_add_to_cart( $_POST['pau_form'], array(), array( 'text' => __( 'Add to Cart', 'paupress' ) ) ) . '</div>';
			
		break;
		/*	
			if ( !empty( $_POST['pp_form_submission'] ) ) {
								
				// BAIL IF WE DON'T HAVE A FORM
				$form = false;
				if ( isset( $_POST['pau_form'] ) ) {
					$form_id = $_POST['pau_form'];
					$form = get_option( $_POST['pau_form'] );
				}
				if ( false == $form ) {
					echo __( 'There appears to be a problem.', 'paupress' );
					die();
				}
				
				// TITLE
				$contact_title = $form['title'] . ': ';
				
				// NOTIFICATIONS
				$form_notifications = true;
				if ( !empty( $form['settings']['notify_enable'] ) && 'false' == $form['settings']['notify_enable'] ) {
					$form_notifications = false;
				}
								
				// DEFAULTS
				$sender = false;
				$email = false;
				$fname = false;
				$lname = false;
				
				if ( isset( $form['settings']['update_user'] ) && 'true' == $form['settings']['update_user'] ) {
					$update_user = true;
				}
				
				// CONDITIONS FOR NAME
				if ( isset( $_POST['paupress_user_meta']['first_name'] ) )
					$fname =  $_POST['paupress_user_meta']['first_name'];
				
				if ( isset( $_POST['paupress_user_meta']['last_name'] ) )
					$lname =  $_POST['paupress_user_meta']['last_name'];
						
				$name = $fname . ' ' . $lname;
				
				// EMAIL
				if ( isset( $_POST['email'] ) )
					$email = $_POST['email'];
				
				// USER TARGET IF IT'S A USER-TO-USER EMAIL
				if ( isset( $_POST['uid'] ) ) {
					$uid = $_POST['uid'];
					unset( $_POST['uid'] );
				}
				
				// SUBJECT
				if ( isset( $_POST['_pp_post']['pp_form_title'] ) ) {
					$subject = $_POST['_pp_post']['pp_form_title'];
				} else if ( isset( $form['settings']['notify_subject'] ) ) {
					$subject = $form['settings']['notify_subject'];
				} else {
					$subject = $contact_title . __( 'Submission', 'paupress' );
				}
				
				// MESSAGE
				if ( isset( $_POST['_pp_post']['pp_form_content'] ) ) {
					$message = $_POST['_pp_post']['pp_form_content'];
				} else {
					$message = '';
				}
				
				// THANK YOU
				$thankyou = stripslashes_deep( $form['settings']['thanks'] );
				if ( empty( $thankyou ) ) {
					$thankyou = __( 'Thank you.', 'paupress' );
				}
				$signcount = 2 + round( str_word_count( strip_tags( $thankyou ) ) / 4 );
								
				// CC
				$cc_me = 'false';
				if ( isset( $_POST['_pp_post']['pp_form_cc'] ) ) {
					$cc_me = $_POST['_pp_post']['pp_form_cc'];
					unset( $_POST['_pp_post']['pp_form_cc'] );
				}
				
				// EMAIL
				// NEED TO DO A SECONDARY CHECK FOR ALTERNATE EMAILS
				if ( !empty( $email ) )			
					$sender = get_user_by( 'email', $email );
					
				// PREP THE INSERTION
				$log_args = array();
				$log_args['ivals'] = $_POST;  
				$log_args['no_log'] = true;
				
				// IF THEY DON'T EXIST, ADD THEM!
				if ( !$sender ) {
		
					// IF THEY ARE CONTACTING ANOTHER USER, LOG THEM, OTHERWISE, BYPASS
					// FIRST TIME COMMUNICATIONS ARE GOING TO BE LOGGED NO MATTER WHAT
					if ( isset( $uid ) ) {
						$log_args['log_type'] = 'contact_form'; 
						$log_args['form_id'] = $form_id; 
						$log_args['title'] = $subject;
						$log_args['content'] = __( 'User to User Communication', 'paupress' ); 
						$log_args['no_log'] = false;
					}
					
					$user_id = paupress_insert_user( $log_args );
					$sender = get_user_by( 'id', $user_id );
					
				// IF THEY DO EXIST, AND WE'RE UPDATING...
				} else if ( isset( $update_user ) ) {
				
					$log_args['update'] = true;
					$log_args['match'] = 'email';
					
					$user_id = paupress_insert_user( $log_args );
					$sender = get_user_by( 'id', $user_id );
								
				}
				
				// IF THEY ARE CONTACTING ANOTHER USER, DISREGARD FOR PRIVACY FOR NOW
				if ( !isset( $uid ) ) {
				
					// CREATE THE OPPORTUNITY!
					$postdata['post_title'] = 'New Opportunity';
					$postdata['post_content'] = $message;
					$postdata['post_status'] = 'publish';
					$postdata['post_author'] = $sender->ID;
					$postdata['post_type'] = 'pp_opportunity';
						
					$post_id = wp_insert_post( $postdata, true );
					
					// UPDATE THE META
					if ( intval( $post_id ) ) {
						update_post_meta( $post_id, '_pp_log_type', 'contact_form' );
						update_post_meta( $post_id, '_pp_form', $form_id );
					}
					
					if ( isset( $_POST['_pp_post'] ) ) {
						foreach( $_POST['_pp_post'] as $k => $v ) {
							if ( !in_array( $k, array( 'pp_form_title', 'pp_form_content' ) ) ) 
								update_post_meta( $post_id, $k, $v );
						}
					}
										
					// APPEND ADDITIONAL PROFILE FIELDS TO THE MESSAGE
					if ( isset( $_POST['paupress_user_meta'] ) ) {
						foreach ( $_POST['paupress_user_meta'] as $k => $v ) {
							$option = paupress_get_option( $k );
							$message .= '<p>' . $option['name'] . ": " . stripslashes( urldecode( $v ) ) . '</p>';
						}
					}
					
					// APPEND ADDITIONAL GENERAL FIELDS TO MESSAGE
					if ( isset( $_POST['_pp_post'] ) ) {
						foreach( $_POST['_pp_post'] as $k => $v ) {
							$option = paupress_get_option( $k );
							$message .= '<p>' . $option['name'] . ": " . stripslashes( urldecode( $v ) ) . '</p>';
						}
					}
					
					do_action( 'pauopps_insert_post_new', $post_id );
					
				}
									
				// PREP THE FORM FOR MAIL NOTIFICATIONS
				// SENDER INFORMATION
				// EMAIL COMES FROM $_POST['email']
				// NAME COMES FROM $_POST['name']
				$default_email = get_option( 'admin_email' );
				$default_name = get_option( 'blogname' );
				
				$notify_email = $default_email;
				if ( !empty( $form['settings']['notify_from'] ) ) {
					$notify_email = $form['settings']['notify_from'];
				}
				$notify_name = $default_name;
				if ( !empty( $form['settings']['notify_from_name'] ) ) {
					$notify_name = $form['settings']['notify_from_name'];
				}
				
				// NEED AN OPTION FOR MAIL NOTIFICATIONS
				// IF THIS IS GOING TO ANOTHER SITE USER...
				$to_email = array();
				$to_email = explode( ',', $form['settings']['notify'] );
				foreach( $to_email as $tek => $tev ) {
					$to_email[$tek] = trim( $tev );
				}
				$to_email = apply_filters( 'pauopps_form_recipients', $to_email, $sender, $form );
				
				// IF WE'RE EMAILING, DO IT NOW
				if ( !empty( $to_email ) ) {
				
					$admin_msg = '';
					$admin_msg .= '<p>' . sprintf( __( 'Origin: %1$s', 'paupress' ), get_option( 'blogname' ) ) . '</p>';
					if ( !empty( $contact_title ) ) {
						$admin_msg .= '<p>' . sprintf( __( 'Form: %1$s', 'paupress' ), $contact_title ) . '</p>';
					}
					$admin_msg .= '<p>' . sprintf( __( 'Sender: %1$s', 'paupress' ), $name . ' (<a href="mailto:'.$email.'">'.$email.'</a>)' ) . '</p>';
					$admin_msg .= $message;
				
					// SEND THE MAIL
					if ( false != $form_notifications ) {
						$mail_args = array();
						$mail_args['to'] = $to_email;
						$mail_args['from_name'] = $notify_name;
						$mail_args['from_email'] = $notify_email;
						$mail_args['subject'] = $subject;
						$mail_args['message'] = $admin_msg;
						paumail_wp_email( $mail_args );
						//paupress_error_log( array( 'app' => 'paumail', 'type' => 'user', 'id' => $sender->ID, 'error' => $form_id.' '.print_r($to_email,true).' '.$subject.' '.$admin_msg ) );
					}
					
					// COPY THEM BUT NOT ON THE SAME EMAIL				
					if ( 'true' == $cc_me ) {
						$user_msg = '<p>' . sprintf( __( 'You asked to be copied on the message below that you sent via the website: %1$s', 'paupress' ), get_option( 'blogname' ) ) . '</p>';
						$user_msg .= $message;
						
						// RESET MAIL ARGUMENTS
						$mail_args = array();
						$mail_args['to'] = $to_email;
						$mail_args['from_name'] = $default_name;
						$mail_args['from_email'] = $default_email;
						$mail_args['subject'] = $subject;
						$mail_args['message'] = $user_msg;
		
						paumail_wp_email( $mail_args );
					}
					
					// IF THEY HAVE ATTACHED AN AUTORESPONDER
					if ( isset( $form['settings']['auto_action'] ) && !empty( $form['settings']['auto_action'] ) ) {
						$args = array( 'users' => array( $sender->ID ), 'no_receipt' => true );
						paumail_process_campaign( $form['settings']['auto_action'], $args );
					}
					
					
				}
				
				// THANK YOU
				?>
				<p class="pp-form-thanks" data-thanks="<?php echo $form_id; ?>">
					<?php echo apply_filters( 'the_content', $thankyou ); ?>
				</p>
				<?php
				die();
						
			} else {
									
				// THE FORM
				if ( !empty( $_POST['pau_form'] ) ) {
					$form = get_option( $_POST['pau_form'] );
				}
				
				if ( empty( $form ) )
					return false;
				
				// ENSURE WE HAVE AN EMAIL ADDRESS SET
				$form_email = false;
				$form_email_search = pp_array_flatten( $form['optional'] );
				if ( 
					!in_array( 'email', $form_email_search ) && 
					isset( $form['settings']['update_user'] ) && 
					'true' == $form['settings']['update_user'] 
				) {
					$form['optional']['email'] = true;
				}
				
				
				if ( !empty( $form['settings']['greeting'] ) ) {
					echo '<div class="pp-form-greeting">';
					echo apply_filters( 'the_content', stripslashes_deep( $form['settings']['greeting'] ) );
					echo '</div>';
				}
				?>
				<form class="paupanels-form" enctype="multipart/form-data" action="" method="POST">
					<?php							
						$args = array();
						if ( is_user_logged_in() ) {
							global $current_user;
							$args['object'] = $current_user;
						}
						$args['caps'] = 'user';
						//$args['post_val'] = $post_vars;
						$args['block'] = false;
						$args['action'] = 'register';
						$args['form'] = $form;
						
						pp_form_dbs_fields( $form['optional'], $args );
					
					?>
					
					<div class="continue">
						<?php 
							if ( isset( $_POST['uid'] ) || isset( $_GET['uid'] ) )
								echo '<input type="hidden" name="uid" value="'.$_POST['uid'].'" />';
						?>
						<input type="hidden" name="rel" value="pp_opportunity" />
						<input type="hidden" name="paupress_user_meta[pp_user_type]" value="ind" />
						<input type="hidden" name="pau_form" value="<?php echo $form['id']; ?>" />
						<input type="hidden" name="pp_form_submission" value="1" />
						<input type="submit" name="_paupress[submission]" value="<?php _e( 'Submit', 'paupress' ); ?>" class="button" />				
						<?php 
							echo   paupanels_build_panel_link( 
								array( 'links' => apply_filters( 'paupress_contact_links', 
									array( 
										//paupanels_get_panel_link( 'action=login' ), 
									) 
								) )
							);
						?>
					</div>
				</form>
				<?php
			}
		
		break;
		*/
	}

}


function pauopps_ptdu( $post_types ) {
	$post_types['pp_opportunity'] = true;
	return $post_types;
}


function pauopps_form_post_insert_pre( $postdata, $form, $sender ) {
	if ( 'pp_opportunity' == $form['type'] ) {
		$postdata['post_title'] = sprintf( __( '%1$s (Website): %2$s', 'paupress' ), PAUOPPS_OPPORTUNITY, pp_get_user_name( $sender ) );
		$postdata['post_status'] = 'publish';
		$postdata['post_type'] = 'pp_opportunity';
	}
	return $postdata;
}


function pauopps_form_post_mail_pre( $mailargs, $form, $sender ) {	
	if ( 'pp_opportunity' == $form['type'] ) {
		$mailargs['subject'] = $mailargs['subject'] . ': ' . pp_get_user_name( $sender );
	}
	return $mailargs;
}