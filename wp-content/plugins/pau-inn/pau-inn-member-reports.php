<?php


/* -----------------------------------------------------------
	ACTIONS & FILTERS
   ----------------------------------------------------------- */
   
add_filter( 'paupress_get_init_user_actions', 'innmemrep_init_user_actions' );


/* -----------------------------------------------------------
	SETUP
   ----------------------------------------------------------- */

/**
 * Simple array to define the actions that directly affect users.
 *
 * @since 1.0.2
 *
 * @param This function accepts no parameters.
 *
 * @return arr. Standardized array of action data.
 */

function innmemrep_user_actions() {
	
	return apply_filters( 'innmemrep_user_actions', array( 
					
			'inn_mem_rep' => array( 
								'source' => 'pau-inn', 
								'type' => 'inn_mem_rep', 
								'single' => __( 'Member Report', 'paupress' ), 
								'plural' => __( 'Member Reports', 'paupress' ), 
								'class' => '', 
								'options' => array( 
													'admin' => true, 
													'user' => true, 
													'public' => false, 
													'reports' => true, 
													'choices' => false
								), 
								
								'args' => array(
													'public' => true, 
													'publicly_queryable' => true, 
													'exclude_from_search' => false, 
													'show_ui' => false, 
								),
			), 
						
	) );

}

/**
 * Retrieves the user actions and opens up a filter.
 *
 * @since 1.0.2
 *
 * @param This function accepts no parameters.
 *
 * @return arr. The filtered Actions Array.
 */

function innmemrep_get_user_actions() {
	return apply_filters( 'innmemrep_get_user_actions', innmemrep_user_actions() ); 
}

/**
 * Pushes the user actions to PauPress for init.
 *
 * @since 1.0.2
 *
 * @param arr. The PauPress User Actions Array.
 *
 * @return arr. The filtered Actions Array.
 */

function innmemrep_init_user_actions( $paupress_user_actions ) {
	
	$innmemrep_user_actions = innmemrep_get_user_actions();
	
	foreach ( $innmemrep_user_actions as $key => $value )
		$paupress_user_actions[$key] = $value;
	
	return $paupress_user_actions;
}


add_filter( 'pp_get_post_to_edit_fields', 'innmemrep_get_fields', 10, 2 );
function innmemrep_get_fields( $choices, $post ) {

	if ( 'inn_mem_rep' == $post->post_type ) {
	
		if ( isset( $_GET['fiscal'] ) || isset( $_GET['ID'] ) ) {
	
			$tempchoice = $choices[0];
			$choices[0] = array();
			
			foreach( $tempchoice as $k => $v ) {
				if ( 'post_title' == $v['meta']['meta_key'] ) {
					$v['meta']['options']['field_type'] = 'hidden';
					if ( isset( $_GET['fiscal'] ) ) {
						$v['meta']['default'] = 'Report for ' . $_GET['fiscal'];
					} else {
						$v['meta']['default'] = 'Report for ' . get_post_meta( $_GET['ID'], '_inn_mem_fiscal', true );
					}
					$choices[0][] = $v;
				}
			}
			
			$col_1 = innmemrep_get_col_1();
			foreach( $col_1 as $k => $v ) {
				$choices[0][] = $v;
			}
			$choices[1] = innmemrep_get_col_2();
			/*
			$tempevents = pp_events_fields();
			foreach( $tempevents as $k => $v ) {
				if ( '_pe_location_postal_code' != $v['meta']['meta_key'] ) {
					$choices[0][] = $v;
				} else {
					break;
				}
			}
			//$choices[0] = array_merge( $choices[0], pp_events_fields() );
			*/
			
			/*
			1. Total anticipated revenue for the current fiscal year
			2. Earned income revenue for the current fiscal year 
			3. Average monthly unique visitors for the past 3 month period
			4. Average monthly page views for the past 3 month period
			5. Total Twitter followers (all company accounts) 
			6. Total Facebook likes (all company accounts)
			7. Number of media partners 
			8. Total estimated reach of content via media partners
			*/
			
		} else {
			$choices = array( array(), array() );
			return $choices;
		}
	}
	
	return $choices;

}


function innmemrep_get_col_1() {
	return array(
		array( 'meta' => array( 
						'source' => '', 
						'meta_key' => '_inn_mem_fiscal', 
						'name' => '',
						'help' => '', 
						'lpos' => 'none', 
						'field_type' => 'hidden',
						'req' => false, 
						'public' => false, 
						'choices' => false, 
		) ),
		
		array( 'meta' => array( 
						'source' => '', 
						'meta_key' => '_inn_mem_revenue_title', 
						'name' => __( 'Revenue', 'inn' ),
						'help' => '', 
						'lpos' => 'top', 
						'field_type' => 'title',
						'req' => false, 
						'public' => false, 
						'choices' => false, 
		) ),
		
		array( 'meta' => array( 
						'source' => '', 
						'meta_key' => '_inn_mem_anticipated', 
						'name' => __( 'Anticipated Revenue', 'inn' ),
						'help' => __( 'Total anticipated revenue for the current fiscal year', 'inn' ), 
						'hpos' => 'pre', 
						'lpos' => 'top', 
						'field_type' => 'number',
						'req' => false, 
						'public' => false, 
						'choices' => false, 
		) ), 
		
		array( 'meta' => array( 
						'source' => '', 
						'meta_key' => '_inn_mem_revenue', 
						'name' => __( 'Earned Revenue', 'inn' ),
						'help' => __( 'Earned income revenue for the current fiscal year', 'inn' ), 
						'hpos' => 'pre', 
						'lpos' => 'top', 
						'field_type' => 'number',
						'req' => false, 
						'public' => false, 
						'choices' => false, 
		) ), 
		
		array( 'meta' => array( 
						'source' => '', 
						'meta_key' => '_inn_mem_traffic_title', 
						'name' => __( 'Traffic', 'inn' ),
						'help' => '', 
						'lpos' => 'top', 
						'field_type' => 'title',
						'req' => false, 
						'public' => false, 
						'choices' => false, 
		) ),
		
		array( 'meta' => array( 
						'source' => '', 
						'meta_key' => '_inn_mem_visitors', 
						'name' => __( 'Visitors', 'inn' ),
						'help' => __( 'Average Monthly Unique Visitors for the past 3 month period', 'inn' ), 
						'hpos' => 'pre', 
						'lpos' => 'top', 
						'field_type' => 'number',
						'req' => false, 
						'public' => false, 
						'choices' => false, 
		) ), 
		
		array( 'meta' => array( 
						'source' => '', 
						'meta_key' => '_inn_mem_views', 
						'name' => __( 'Page Views', 'inn' ),
						'help' => __( 'Average monthly page views for the past 3 month period', 'inn' ), 
						'hpos' => 'pre', 
						'lpos' => 'top', 
						'field_type' => 'number',
						'req' => false, 
						'public' => false, 
						'choices' => false, 
		) ), 
	);
}

function innmemrep_get_col_2() {
	return array(
		array( 'meta' => array( 
						'source' => '', 
						'meta_key' => '_inn_mem_social_title', 
						'name' => __( 'Social', 'inn' ),
						'help' => '', 
						'lpos' => 'top', 
						'field_type' => 'title',
						'req' => false, 
						'public' => false, 
						'choices' => false, 
		) ),
		
		array( 'meta' => array( 
						'source' => '', 
						'meta_key' => '_inn_mem_twitter', 
						'name' => __( 'Twitter', 'inn' ),
						'help' => __( 'Total Twitter Followers for all corporate accounts', 'inn' ), 
						'hpos' => 'pre', 
						'lpos' => 'top', 
						'field_type' => 'number',
						'req' => false, 
						'public' => false, 
						'choices' => false, 
		) ), 
		
		array( 'meta' => array( 
						'source' => '', 
						'meta_key' => '_inn_mem_facebook', 
						'name' => __( 'Facebook', 'inn' ),
						'help' => __( 'Total Facebook likes for all corporate accounts', 'inn' ), 
						'hpos' => 'pre', 
						'lpos' => 'top', 
						'field_type' => 'number',
						'req' => false, 
						'public' => false, 
						'choices' => false, 
		) ), 
		
		array( 'meta' => array( 
						'source' => '', 
						'meta_key' => '_inn_mem_reach_title', 
						'name' => __( 'Reach', 'inn' ),
						'help' => '', 
						'lpos' => 'top', 
						'field_type' => 'title',
						'req' => false, 
						'public' => false, 
						'choices' => false, 
		) ),
		
		array( 'meta' => array( 
						'source' => '', 
						'meta_key' => '_inn_mem_partners', 
						'name' => __( 'Media Partners', 'inn' ),
						'help' => __( 'umber of media partners', 'inn' ), 
						'hpos' => 'pre', 
						'lpos' => 'top', 
						'field_type' => 'number',
						'req' => false, 
						'public' => false, 
						'choices' => false, 
		) ), 
		
		array( 'meta' => array( 
						'source' => '', 
						'meta_key' => '_inn_mem_reach', 
						'name' => __( 'Partner Reach', 'inn' ),
						'help' => __( 'Total estimated reach of content via media partners', 'inn' ), 
						'hpos' => 'pre', 
						'lpos' => 'top', 
						'field_type' => 'number',
						'req' => false, 
						'public' => false, 
						'choices' => false, 
		) ), 
	);
}


add_action( 'pp_get_post_to_edit_pre_form', 'innmemrep_get_post_to_edit_pre_form', 10, 2 );
function innmemrep_get_post_to_edit_pre_form( $post, $type ) {
	if ( 'inn_mem_rep' == $post->post_type ) {
		if ( isset( $_GET['fiscal'] ) || isset( $_GET['ID'] ) ) {
			if ( isset( $_GET['fiscal'] ) ) {
				$fiscal = $_GET['fiscal'];
			} else if ( isset( $_GET['ID'] ) ) {
				$fiscal = $_GET['ID'];
			}
		?>
			<script type="text/javascript">
				jQuery.noConflict();
				jQuery(document).ready(function(){
					if ( !jQuery('._inn_mem_fiscal:first').val() ) {
						jQuery('._inn_mem_fiscal:first').val('<?php echo $fiscal; ?>');
					}
				});
			</script>
		<?php
		} else {
		?>
			<h2>Please choose a fiscal year to edit.</h2>
			<script type="text/javascript">
				jQuery.noConflict();
				jQuery(document).ready(function(){
					jQuery('#pp-submit-modal').hide();
				});
			</script>
		<?php
		}
	}
}


function inn_mem_rep_widget( $args = null ) {

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
		
	$user_id = $fdata->ID;
	$fiscals = array( 2015, 2014, 2013 );
	$yearstd = array();
	$ffields = array_merge( innmemrep_get_col_1(), innmemrep_get_col_2() );
	foreach ( $ffields as $k => $v ) {
		if ( 'title' != $v['meta']['field_type'] ) {
			$fields[$v['meta']['meta_key']] = $v;
		}
	}
	$fields['edit'] = true;
	
	$qargs = array();
	$qargs['post_type'] = 'inn_mem_rep';
	$qargs['author'] = $user_id;
	$qargs['posts_per_page'] = -1;
	$qargs['order'] = 'DESC';
	//$qargs['meta_query'][] = array( 'key' => '_inn_mem_fiscal', 'value' => array( 2015, 2010 ), 'type' => 'NUMERIC', 'compare' => 'BETWEEN' );
	
	$query = new WP_Query( $qargs );
	$wp_calendar['query'] = $args;
	
	// RUN THE QUERY AND PUSH THE ARRAYS
	if ( $query ) {
		while ( $query->have_posts() ) : $query->the_post();
			$fiscal = get_post_meta( $query->post->ID, '_inn_mem_fiscal', true );
			if ( !empty( $fiscal ) ) {
				foreach ( $fields as $k => $v ) {
					if ( 'edit' == $k ) {
						$yearstd[$fiscal][$k] = $query->post->ID;
					} else {
						$yearstd[$fiscal][$k] = get_post_meta( $query->post->ID, $k, true );
					}
				}
				
			}
		endwhile;
	}
			
	?>
	<table class="wp-list-table widefat" cellspacing="5">
		<thead>
			<tr>
				<?php foreach ( $fields as $k => $v ) { echo '<th style="text-align: center; vertical-align: top; font-size: 11px; font-weight: bold; height: 25px;" class="manage-column">'.$v['meta']['name'].'</th>'; } ?>
				<th></th>
			</tr>
		</thead>
		<tbody>
		<?php 
			foreach ( $fiscals as $year ) {
				if ( $year %2 != 0 ) {
					echo '<tr class="alternate">';
				} else {
					echo '<tr>';
				}
				foreach ( $fields as $k => $v ) { 
					if ( '_inn_mem_fiscal' == $k ) {
						echo '<td><strong>'.$year.'</strong></td>';
					} else if ( isset( $yearstd[$year][$k] ) && !empty( $yearstd[$year][$k] ) ) {
						if ( 'edit' == $k ) {
							echo '<td style="text-align: center;"><a class="thickbox button" href="' .  wp_nonce_url( admin_url( 'users.php?page=paupress_modal_action&action=getaction&ID='.$yearstd[$year][$k] ), 'get_action', 'action_nonce' ) . '&TB_iframe=true&height=500&width=900">edit</a></td>';
						} else {
							echo '<td style="text-align: center;">'.$yearstd[$year][$k].'</td>';
						}
					} else {
						if ( 'edit' == $k ) {
							echo '<td style="text-align: center;"><a class="thickbox button" href="' .  wp_nonce_url( admin_url( 'users.php?page=paupress_modal_action&action=getaction&post_type=inn_mem_rep&uid='.$user_id.'&fiscal=' . $year ), 'get_action', 'action_nonce' ) . '&TB_iframe=true&height=500&width=900">edit</a></td>';
						} else {
							echo '<td style="text-align: center;" class="disabled">no data</td>';
						}
					}
				} 
				echo '</tr>';
			}
		?>
		</tbody>
	</table>
	<?php
}


// REPORTS
add_action( 'paupress_action_search_meta', 'inn_action_search_meta', 10, 3 );
add_filter( 'paupress_action_detail_html', 'inn_action_detail_html', 2, 10 );
add_filter( 'paupress_action_detail_val', 'inn_action_detail_val', 2, 10 );
function inn_action_search_meta( $fid, $key, $query = false ) {

	if ( 'inn_mem_rep' == $fid ) {

		$imeta = array_merge( innmemrep_get_col_1(), innmemrep_get_col_2() );
		foreach ( $imeta as $ikey => $ival ) {
					
			if ( 'title' == $ival['meta']['field_type'] ) { continue; }
			if ( '_inn_mem_fiscal' == $ival['meta']['meta_key'] ) { $ival['meta']['name'] = 'year'; }
			
			$ikey = $ival['meta']['meta_key'];
			?>
			<input type="hidden" name="search[<?php echo $key; ?>][meta][<?php echo $ikey; ?>][op]" value="like" />
			<span class="half-panel">
			<select name="search[<?php echo $key; ?>][meta][<?php echo $ikey; ?>][op]">
				<option value="show"<?php if ( isset( $query['meta'][$ikey]['op'] ) ) selected( $query['meta'][$ikey]['op'], 'show' ); ?>><?php printf( __( '%s', 'paupress' ), $ival['meta']['name'] ); ?></option>
				<option value="min"<?php if ( isset( $query['meta'][$ikey]['op'] ) ) selected( $query['meta'][$ikey]['op'], 'min' ); ?>><?php printf( __( '%s is >=', 'paupress' ), $ival['meta']['name'] ); ?></option>
				<option value="max"<?php if ( isset( $query['meta'][$ikey]['op'] ) ) selected( $query['meta'][$ikey]['op'], 'max' ); ?>><?php printf( __( '%s is <=', 'paupress' ), $ival['meta']['name'] ); ?></option>
				<option value="is"<?php if ( isset( $query['meta'][$ikey]['op'] ) ) selected( $query['meta'][$ikey]['op'], 'is' ); ?>><?php printf( __( '%s is =', 'paupress' ), $ival['meta']['name'] ); ?></option>
				<option value="not"<?php if ( isset( $query['meta'][$ikey]['op'] ) ) selected( $query['meta'][$ikey]['op'], 'not' ); ?>><?php printf( __( '%s is not', 'paupress' ), $ival['meta']['name'] ); ?></option>
				<option value="bet"<?php if ( isset( $query['meta'][$ikey]['op'] ) ) selected( $query['meta'][$ikey]['op'], 'bet' ); ?>><?php printf( __( '%s between', 'paupress' ), $ival['meta']['name'] ); ?></option>						
			</select>
			</span>
			<span class="half-panel">
			<input type="text" class="input-short" name="search[<?php echo $key; ?>][meta][<?php echo $ikey; ?>][val]" value="<?php if ( isset( $query['meta'][$ikey]['val'] ) ) { echo $query['meta'][$ikey]['val']; } ?>" />
			</span>
			<?php				

		}
					
	}
	
}


function inn_action_detail_html( $inner_return_html, $value ) {
	
	// CHECK FOR VARIABLE TYPE TO REUSE THE FUNCTION
	if ( is_object( $value ) ) {
		$val = $value;
		$value = array( 'ID' => $val->ID, 'post_type' => $val->post_type, 'post_author' => $val->post_author, 'post_date' => $val->post_date, 'post_content' => $val->post_content, 'post_parent' => $val->post_parent );
	}
	
	if ( 'inn_mem_rep' == $value['post_type'] ) {
		
		// SET VARIABLES
		$id = $value['ID'];
		$launch_id = $id;
		$title = $value['post_title'];
		$date = $value['post_date'];
		$onemeta = innmemrep_get_col_1();
		$twometa = innmemrep_get_col_2();
		$meta1 = '';
		$meta2 = '';
		foreach ( $onemeta as $ikey => $ival ) {
			if ( 'title' == $ival['meta']['field_type'] || '_inn_mem_fiscal' == $ival['meta']['meta_key'] ) { continue; }
			if ( '_inn_mem_fiscal' == $ival['meta']['meta_key'] ) { $ival['meta']['name'] = 'year'; }
			
			$meta1 .= $ival['meta']['name'].': <strong>'.get_post_meta( $id, $ival['meta']['meta_key'], true ).'</strong> ';
		}
		foreach ( $twometa as $ikey => $ival ) {
			if ( 'title' == $ival['meta']['field_type'] || '_inn_mem_fiscal' == $ival['meta']['meta_key'] ) { continue; }
			if ( '_inn_mem_fiscal' == $ival['meta']['meta_key'] ) { $ival['meta']['name'] = 'year'; }
			
			$meta2 .= $ival['meta']['name'].': <strong>'.get_post_meta( $id, $ival['meta']['meta_key'], true ).'</strong> ';
		}
		$inner_return_html .= '<div>';
		$inner_return_html .= '<a href="' . wp_nonce_url( admin_url( 'users.php?page=paupress_modal_action&action=getaction&ID='.$launch_id.'&type=user' ), 'get_action', 'action_nonce' ) . '&TB_iframe=true&height=500&width=900&modal=true" class="thickbox">'.$title.'</a> Submitted: ' . date( 'D M d, Y g:i:s A', strtotime( $date ) ) . '<br />';
		$inner_return_html .= $meta1.'<br />';
		$inner_return_html .= $meta2.'<br />';
		$inner_return_html .= '</div>';
			
	}
	
	return $inner_return_html;
	
}


function inn_action_detail_val( $return_val, $value ) {

	if ( 'inn_mem_rep' == $value['post_type'] ) {
		$id = $value['ID'];
		$title = $value['post_title'];
		$date = $value['post_date'];
				
		$imeta = array_merge( innmemrep_get_col_1(), innmemrep_get_col_2() );
		foreach ( $imeta as $ikey => $ival ) {
			if ( 'title' == $ival['meta']['field_type'] ) { continue; }
			if ( '_inn_mem_fiscal' == $ival['meta']['meta_key'] ) { $ival['meta']['name'] = 'year'; }
			$ikey = $ival['meta']['meta_key'];
			$return_val['action'][$id][$ikey] = get_post_meta( $id, $ikey, true );
		}
		
	}
	
	return $return_val;
	
}