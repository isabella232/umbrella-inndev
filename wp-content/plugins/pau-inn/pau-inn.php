<?php
/*
Plugin Name: PauINN
Plugin URI: http://paupress.com/
Description: Custom functionality for INN's PauPress Deployment.
Version: 0.2
Author: havahula.org
Author URI: http://havahula.org
*/



/* -----------------------------------------------------------
	SETUP, OPTIONS & ACTIONS
   ----------------------------------------------------------- */

/**
 * Security: Shut it down if the plugin is called directly
 *
 * @since 0.1
 */
if ( !function_exists( 'add_action' ) ) {
	echo __( 'tschuss', 'paupress' );
	exit;
}


/**
 * Actions to hook into WordPress & PauPress
 *
 * @since 1.0.0
 */

//add_filter( 'paupress_push_menu', 'pauinn_push_menu' );
include_once( 'paupro-post-relationships.php' );
include_once( 'pau-inn-member-reports.php' );
add_action('admin_menu', 'pauinn_push_menu');
//add_action( 'init', 'pau_inn_init_members', 11 );

/**
 * Hook the PauPress Administration Menus
 *
 * @since 1.0.0
 */

function pauinn_push_menu( $paupress_menu = array() ) {

	add_submenu_page( 'options-general.php', 'INN Options', 'INN Options', 'manage_options', 'pauinn_options', 'pauinn_migration' );
	//$paupress_menu['pauinn_options'] = add_submenu_page( 'paupress_options', 'INN Options', 'INN Options', 'manage_options', 'pauinn_options', 'pauinn_options' );

	//return $paupress_menu;

}


/**
 * This is the wrapper function for the PauPress Options API. Your function should be called {prefix}_options.
 *
 * @since 1.0.2
 *
 * @param none This should take no parameters but should call the general paupress_options_form.
 *
 * @return html outputs the options page with the activated option tab + contents.
 */
function pauinn_options() {

	$navigation = apply_filters( 'pauinn_options_tabs', array(

		'pauinn_general_settings' => array(
									'title' => __( 'General', 'paupress' ),
									'subs' => false,
							),
	) );

	//paupress_options_form( __( 'PauPress – INN Options', 'paupress' ), $navigation );

}


function pauinn_general_settings() {

	return apply_filters( 'pauinn_general_settings', array(

		array( 'meta' => array(
								'source' => 'pauinn',
								'meta_key' => 'pauinn_migration',
								'name' => '',
								'help' => '',
								'options' => array(
													'field_type' => 'plugin',
													'req' => false,
													'public' => false,
													'choices' => 'pauinn_migration'
								)
		) ),

	));

}

add_action( 'init', 'pauinn_init' );
function pauinn_init() {
	global $wp_roles;

	// SET THE AGENT ROLE
	if ( !isset( $wp_roles->member ) ) {
		add_role( 'member', 'Member', array( 'read' => true ) );
	} else {
		//$wp_roles->remove_role( 'pp_agent' );
		//$wp_roles->add_cap( 'pp_agent', 'read' );
	}
	$wp_roles->add_cap( 'member', 'read' );
}

function pauinn_setup() {
	$prefix = 'inn_';
	 $meta_box = array(
	     array(
	       'name'  => 'Contact Phone',
	       'id'    => $prefix . 'phone',
	       'type'  => 'text',
	     ),
	     array(
	       'name'  => 'Year Founded',
	       'id'    => $prefix . 'founded',
	       'type'  => 'text',
	       'size'	=> 5,
	       'desc'   => "The full year of founding, if known."
	     ),
	     array(
	       'name'  => 'Member Since',
	       'id'    => $prefix . 'since',
	       'type'  => 'text',
	       'size'	=> 5,
	       'desc'   => "The year this group joined INN, if known."
	     ),
	     array(
	       'name'  => 'RSS',
	       'id'    => $prefix . 'rss',
	       'type'  => 'text',
	       'desc'   => "The URL of this member's main RSS feed, including http(s)://"
	     ),
	     array(
	       'name'  => 'Twitter handle',
	       'id'    => $prefix . 'twitter',
	       'type'  => 'text',
	       'desc'   => "The Twitter username for this member. Exclude @ sign."
	     ),
	     array(
	       'name'  => 'Facebook path',
	       'id'    => $prefix . 'facebook',
	       'type'  => 'text',
	       'desc'   => "The path of this member's FB account (the part that comes <em>after</em> http://facebook.com/)"
	     ),
	     array(
	       'name'  => 'Google+ URL',
	       'id'    => $prefix . 'googleplus',
	       'type'  => 'text',
	       'desc'   => "The URL of this member's Google+ account, including http(s)://"
	     ),
	     array(
	       'name'  => 'YouTube URL',
	       'id'    => $prefix . 'youtube',
	       'type'  => 'text',
	       'desc'   => "The URL of this member's YouTube account/channel, including http(s)://"
	     ),
	     array(
	       'name'  => 'Previous Post ID',
	       'id'    => $prefix . 'post_id',
	       'type'  => 'text',
	       'desc'   => ""
	     ),
	     array(
	       'name'  => 'Donation Link',
	       'id'    => $prefix . 'donate',
	       'type'  => 'text',
	       'desc'   => ""
	     ),
	 );

	 foreach ( $meta_box as $k => $v ) {
	 	$field[] = array( 'source' => 'user', 'name' => $v['name'], 'meta_key' => $v['id'], 'meta_type' => 'user', 'field_type' => $v['type'], 'default' => false, 'choices' => false, 'public' => false, 'label' => false, 'help' => array( 'default' => $v['desc'] ), 'show' => false, 'admin' => array( 'default' => 'edit' ), 'user' => array( 'default' => 'edit' ), 'class' => false );
	 }
	 foreach ( $field as $k => $v ) {
	 	//delete_option( '_pp_field_'.substr( $v['meta_key'], 4 ) );
	 	update_option( '_pp_field_'.$v['meta_key'], $v );
	 }
}

function inn_get_members( $meta = false, $echo = false ) {

	// THE QUERY
	$users = new WP_User_Query(  array( 'role' => 'Member', 'fields' => 'all_with_meta' ) );
	$members = array();

	// THE LOOP
	if ( ! empty( $users->results ) ) {
		foreach ( $users->results as $user ) {
			if ( false != $meta ) {
				$meta = get_user_meta( $user->data->ID );
				foreach ( $meta as $k => $v ) {
					$value = maybe_unserialize( $v );
					$field = paupress_get_option( $k );
				 	$user->data->$k = array( 'value' => $value[0], 'name' => $field['name'] );
				}
			}
			$members[$user->data->ID] = $user;
			if ( false != $echo ) {
				echo '<p>' . print_r( $user, true ) . '</p>';
			}
		}
	}

	if ( false != $echo ) {
		return false;
	} else {
		return $members;
	}

}

function pauinn_migration( $args = null ) {

	// SET THE DEFAULTS TO BE OVERRIDDEN AS DESIRED
	$defaults = array(
					'fdata' => false,
					'fvalue' => false,
					'faction' => false,
					'ftype' => false
				);

	// PARSE THE INCOMING ARGS
	$args = wp_parse_args( $args, $defaults );

	// EXTRACT THE VARIABLES
	extract( $args, EXTR_SKIP );



	if ( isset( $_GET['setup'] ) ) {
		echo '<h1>Initializing Fields</h1>';
		//pauinn_setup();
		return false;
	} else if ( isset( $_GET['test'] ) ) {
		echo '<h1>Testing Get Member Function</h1>';
		$members = inn_get_members( true, true );
		return false;
	} else if ( isset( $_GET['import'] ) ) {
		// OK TO GO
		return false;
	} else if ( isset( $_GET['fixit'] ) ) {
		// OK TO GO
		return false;
		echo '<h1>Fixing Emails!</h1>';
		$members = inn_get_members( true );
		foreach ( $members as $k => $v ) {
			$premail = get_post_meta( $v->data->inn_post_id['value'], 'inn_email', true );
			if ( false !== strpos( $premail, ',' ) ) {
				$remail = explode( ',', $premail );
				$premail = $remail[0];
			}
			$user_id = wp_update_user( array( 'ID' => $k, 'user_email' => $premail ) );
			if ( is_wp_error( $user_id ) ) {
				// There was an error, probably that user doesn't exist.
				$errors[] = $k;
			} else {
				// Success!
				$success[] = $k;
			}
		}
		echo '<h2>I had '.count($errors).' errors and '.count($success).' successes!</h2>';
		return false;
	} else {
		echo '<h1>Hello</h1>';
		/*
		$tax = paupress_get_option( 'ppu_focus_areas' );
		if ( false != $tax ) {
			delete_option( '_pp_field_ppu_focus_areas' );
			paupress_delete_wp_taxonomy( 'ppu_focus_areas' );
			print_r($tax);
		}
		*/
		return false;
	}
	echo '<h1>Proceeding With Import...</h1>';

	$mem = new WP_Query( array(
	   									'post_type' => 'inn_member',
	   									'orderby' => 'title',
	   									'order' => 'ASC',
	   									'posts_per_page'=> -1
	));

	$members = array();
	$userdata = array(
					'inn_site_url' => 'url',
					'post_name' => 'user_login',
					'inn_email' => 'user_email',
					'post_date' => 'user_registered',
	);

	$fields = array(
					'inn_donate' => 'inn_donate',
					'inn_twitter' => 'inn_twitter',
					'inn_facebook' => 'inn_facebook',
					'inn_googleplus' => 'inn_googleplus',
					//'inn_coords' => '',
					//'inn_address',
					'_thumbnail_id' => 'pp_avatar',
					'inn_rss' => 'inn_rss',
					'inn_github' => 'inn_github',
					'inn_youtube' => 'inn_youtube',
					'post_title' => 'organization',
					'inn_phone' => 'inn_phone',
					'inn_founded' => 'inn_founded',
					'inn_since' => 'inn_since',
					'post_content' => 'description',
					'ID' => 'inn_post_id',
	);
	$output = '';
	$count = 0;
	$success = 0;

	while( $mem->have_posts()) {
		$mem->next_post();
		$id = $mem->post->ID;
		$meta = get_post_custom( $id );
		foreach ( $meta as $k => $v ) {
			$mem->post->$k = $v[0];
		}
		$mem->post->logo_id = get_post_thumbnail_id( $id );
		$members[] = $mem->post;
		$error = '';
		$user = array();

		// SPLIT OUT ADDRESSES
		$test = preg_replace('/\s+/', '*', $mem->post->inn_address);

		// LOCATION PROBLEMS
		$good = array( 'Washington*DC', 'Chicago*IL', 'City*IA', 'Rancho.*NM', 'Montpelier*VT' );
		$gone = array( 'Washington,*DC', 'Chicago,*IL', 'City,*IA', 'Rancho,*NM', 'Montpelier,*VT' );
		$test = str_replace( $good, $gone, $test );

		// FUZE STATES AND CITIES
		$good = array( 'New*York', 'Puerto*Rico', 'San*Francisco', 'Hato*Rey', 'New*Haven', 'Silver*Springs', 'San*Diego', 'Menlo*Park', 'El*Paso', 'St.*Paul', 'St.*Louis', 'New*Orleans', 'Santa*Ana', 'Iowa*City' );
		$gone = array( 'New|York', 'Puerto|Rico', 'San|Francisco', 'Hato|Rey', 'New|Haven', 'Silver|Springs', 'San|Diego', 'Menlo|Park', 'El|Paso', 'St.|Paul', 'St.|Louis', 'New|Orleans', 'Santa|Ana', 'Iowa|City' );
		$test = str_replace( $good, $gone, $test );

		// STATES PROBLEMS
		$good = array( 'Florida', 'Connecticut', 'Montana', 'Minnesota', 'Puerto|Rico', 'D.C.' );
		$gone = array( 'FL', 'CT', 'MT', 'MN', 'PR', 'DC' );
		$test = str_replace( $good, $gone, $test );

		$pos = trim( substr( strrchr( $test, ',' ), 1 ) );
		$pre = trim( str_replace( $pos, '', $test ) );

		$pre = explode( '*', $pre );
		$pos = explode( '*', $pos );

		$one = implode( ' ', $pre );
		$two = array_pop( $pre );
		$odd = array_shift( $pos );

		$add = array();
		$add['address_one_1'] = trim( str_replace( $two, '', $one ) );
		$add['address_city_1'] = substr( str_replace( '|', ' ', $two ), 0, -1 );
		$add['address_state_1'] = array_shift( $pos );
		$add['address_postal_code_1'] = array_shift( $pos );
		$add['address_country_1'] = array_shift( $pos );
		if ( empty( $add['address_country_1'] ) ) {
			$add['address_country_1'] = 'US';
		} else {
			$add['address_country_1'] = 'CA';
		}
		$add['pp_user_type'] = 'org';

		// GEOCODING
		$edd = array();
		$geo = explode( ',', $mem->post->inn_coords );
		$edd['paupress_address_latitude_1'] = $geo[0];
		$edd['paupress_address_longitude_1'] = $geo[1];

		// OTHER STUFF
		foreach ( $fields as $k => $v ) {
			$add[$v] = $mem->post->$k;
		}
		foreach ( $userdata as $k => $v ) {
			if ( 'user_email' == $v ) {
				$premail = $mem->post->$k;
				if ( false !== strpos( $premail, ',' ) ) {
					$remail = explode( ',', $premail );
					$premail = $remail[0];
				}
				$user[$v] = $premail;
			} else {
				$user[$v] = $mem->post->$k;
			}
		}
		$user['role'] = 'member';
		$user['paupress_user_meta'] = $add;

		if ( !empty( $user ) ) {
			$output .= '<p>';
			$output .= '<strong>'.$mem->post->inn_address . '</strong>';
			foreach ( $user as $k => $v ) {
				$output .= '<br />';
				if ( empty( $v ) ) {
					$output .= '<span style="color:red;">'.$k.'='.$v.'</span>';
				} else {
					if ( is_array( $v ) ) {
						foreach ( $v as $vk => $vv ) {
							$output .= '<br />';
							if ( empty( $vv ) ) {
								$output .= '<span style="color:red;">'.$vk.'='.$vv.'</span>';
							} else {
								$output .= $vk.'='.$vv;
							}
						}
					} else {
						$output .= $k.'='.$v;
					}
				}
			}
			$output .= '</p>';
			//$success++;
			//if ( strlen( $add['postal'] ) != 5 ) { $error = 'color:red;'; } else { $success++; }
			//$output .= '<p style="'.$error.'">' . print_r( $add['postal'], true ) . '<br />' . implode( ' ', $add ) . '<br />' . $mem->post->inn_address . '</p>';

		}
		// PROCESS THE USER
		if ( isset( $_GET['limit'] ) && $count >= $_GET['limit'] ) {
			continue;
		} else {
			//$uid = paupress_insert_user( array( 'ivals' => $user, 'no_log' => true ) );
			if ( !is_wp_error( $uid ) ) {
				foreach( $edd as $k => $v ) {
					update_user_meta( $uid, $k, $v );
				}
				$success++;
			}
		}
		$count++;
	}

	echo '<h1>'.$count.' Members</h1>';
	echo '<h2>'.$success.' Successes</h2>';
	echo $output;

	// return $members;

}

// TEMPORARY FOR EXPORT
function pau_inn_init_members() {
//Members
 register_post_type( 'inn_member',
   array(
     'labels' => array(
       'name' => _x('Members', 'post type general name'),
       'singular_name' => _x('Member', 'post type singular name'),
       'add_new' => _x('Add New Member', 'new inn member'),
       'add_new_item' => __('Add New Member'),
       'edit_item' => __('Edit Member'),
       'new_item' => __('New Member'),
       'all_items' => __('All Members'),
       'view_item' => __('View Member'),
       'search_items' => __('Search Members'),
       'not_found' =>  __('No members found'),
       'not_found_in_trash' => __('No members found in Trash'),
       'parent_item_colon' => '',
       'menu_name' => __('INN Members')
     ),
   'menu_position' => 21,
   'show_ui' => true,
   'description' => 'INN Member publications/groups',
   'exclude_from_search' => false,
   'publicly_queryable' => true,
   'public' => true,
   'has_archive' => true,
   'rewrite' => array('slug' => 'member'),
   'hierarchical' => false,
   'supports' => array('title','editor','thumbnail'), //see add_post_type_support()  - leave editor blank for no Case Study
   )
 );
}


function pau_inn_forms( $id, $uid = false ) {

	if ( 'true' != get_option( 'paupanels_embed' ) ) {
		return false;
	}

	$pauinn = array( 'rel' => 'contact', 'pau_form' => $id );
	if ( false != $uid ) {
		$pauinn['uid'] = $uid;
	}
	$pauref = urlencode( serialize( $pauinn ) );
	$ptitle = 'contact';
	$output = '';

	$output .= '<div class="pauf-frame">';
	$output .= '<div id="paupanels-wrapper" class="pauf-wrap pauf-page">';
	$output .= '<div id="paupanel" style="display: block; min-height: 200px;" class="pauf-pane">';
	$output .= '<div class="container">';
	$output .= '<div id="paupress" class="pauf-press">';
	$output .= '</div></div></div></div></div>';
	$output .= '<script type="text/javascript">jQuery.noConflict();jQuery(document).ready(function() { var ref = "'.$pauref.'";var title = "'.$ptitle.'";jQuery("#paupanel").each(function(){ paupanels_toggle(ref,title); }); });</script>';

	return $output;

}
