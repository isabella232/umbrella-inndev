<?php

function pauopps_action_search_meta( $fid, $key, $query = false ) {

	if ( 'pp_opportunity' == $fid ) {

		$imeta = array_merge( po_required_fields_1(), po_required_fields_2() );
		foreach ( $imeta as $ikey => $ival ) {
			
			$ikey = $ival['meta']['meta_key'];
			switch ( $ikey ) {
				
				case '_po_stage' :
				case '_po_probability' :
				case '_pp_agents' : 
				case '_pp_form' : 
					$opts = $ival['meta']['options']['choices'];
					if ( '_pp_form' == $ikey ) {
						$opts = pauopp_get_templates();
					}
					// class="chzn-select" style="width: 111px;"
					?>
					<input type="hidden" name="search[<?php echo $key; ?>][meta][<?php echo $ikey; ?>][op]" value="like" />
					<span class="half-panel">
					<select name="search[<?php echo $key; ?>][meta][<?php echo $ikey; ?>][op]">
						<option value="in"<?php if ( isset( $query['meta'][$ikey]['op'] ) ) selected( $query['meta'][$ikey]['op'], 'in' ); ?>><?php printf( __( '%s is', 'paupress' ), $ival['meta']['name'] ); ?></option>
						<option value="nin"<?php if ( isset( $query['meta'][$ikey]['op'] ) ) selected( $query['meta'][$ikey]['op'], 'nin' ); ?>><?php printf( __( '%s is not', 'paupress' ), $ival['meta']['name'] ); ?></option>
					</select>
					</span>
					<span class="half-panel">
					<select data-placeholder="<?php printf( __( 'All %s', 'paupress' ), $ival['meta']['name'] ); ?>" name="search[<?php echo $key; ?>][meta][<?php echo $ikey; ?>][val][]" multiple="multiple" class="chzn-select" style="width: 100px;">
						<?php 
							if ( '_po_stage' == $ikey ) {
								foreach ( $opts as $k => $v ) {
									if ( empty( $v['value'] ) ) { $v['value'] = $v['label']; }
									echo '<option value="'.$v['value'].'"';
									if ( isset( $query['meta'][$ikey]['val'] ) && pp_in_array_r( $v['value'], $query['meta'][$ikey]['val'] ) )
										echo ' selected';
									echo '>'.$v['label'].'</option>'; 
								}
							} else {
								foreach ( $opts as $k => $v ) {
									echo '<option value="'.$k.'"';
									if ( isset( $query['meta'][$ikey]['val'] ) && in_array( $k, $query['meta'][$ikey]['val'] ) )
										echo ' selected';
									echo '>'.$v.'</option>'; 
								}
							}
						?>
					</select>
					</span>
					<?php
				break;
				
				case '_po_amount' :
					?>
					<input type="hidden" name="search[<?php echo $key; ?>][meta][<?php echo $ikey; ?>][op]" value="like" />
					<span class="half-panel">
					<select name="search[<?php echo $key; ?>][meta][<?php echo $ikey; ?>][op]">
						<option value="min"<?php if ( isset( $query['meta'][$ikey]['op'] ) ) selected( $query['meta'][$ikey]['op'], 'min' ); ?>><?php _e( 'Amount is >=', 'paupress' ); ?></option>
						<option value="max"<?php if ( isset( $query['meta'][$ikey]['op'] ) ) selected( $query['meta'][$ikey]['op'], 'max' ); ?>><?php _e( 'Amount is <=', 'paupress' ); ?></option>
						<option value="is"<?php if ( isset( $query['meta'][$ikey]['op'] ) ) selected( $query['meta'][$ikey]['op'], 'is' ); ?>><?php _e( 'Amount is =', 'paupress' ); ?></option>
						<option value="not"<?php if ( isset( $query['meta'][$ikey]['op'] ) ) selected( $query['meta'][$ikey]['op'], 'not' ); ?>><?php _e( 'Amount is not', 'paupress' ); ?></option>
						<option value="bet"<?php if ( isset( $query['meta'][$ikey]['op'] ) ) selected( $query['meta'][$ikey]['op'], 'bet' ); ?>><?php _e( 'Amount between', 'paupress' ); ?></option>						
					</select>
					</span>
					<span class="half-panel">
					<input type="text" class="input-short" name="search[<?php echo $key; ?>][meta][<?php echo $ikey; ?>][val]" value="<?php if ( isset( $query['meta'][$ikey]['val'] ) ) { echo $query['meta'][$ikey]['val']; } else { echo '0'; } ?>" />
					</span>
					<?php
				break;
				
			}
		}			
	}
	
}


function pauopps_action_detail_val( $return_val, $value ) {

	if ( 'pp_opportunity' == $value['post_type'] ) {
		$id = $value['ID'];
		$title = $value['post_title'];
		$date = $value['post_date'];
		
		$form = get_option( get_post_meta( $id, '_pp_form', true ) );
		
		$imeta = array_merge( po_required_fields_1(), po_required_fields_2() );
		foreach ( $imeta as $ikey => $ival ) {
			$ikey = $ival['meta']['meta_key'];
			$return_val['action'][$id][$ikey] = get_post_meta( $id, $ikey, true );
		}
		
		if ( !empty( $form['internal'] ) ) {
			$internal = pp_array_flatten( $form['internal'] );
			foreach ( $internal as $k => $v ) {
				$return_val['action'][$id][$v] = get_post_meta( $id, $v, true );
			}
		}
		
		if ( !empty( $form['optional'] ) ) {
			$optional = pp_array_flatten( $form['optional'] );
			foreach ( $optional as $k => $v ) {
				$return_val['action'][$id][$v] = get_post_meta( $id, $v, true );
			}
		}
				
	}
	
	return $return_val;
	
}


function pauopps_action_detail_html( $inner_return_html, $value ) {
	
	// CHECK FOR VARIABLE TYPE TO REUSE THE FUNCTION
	if ( is_object( $value ) ) {
		$val = $value;
		$value = array( 
						'ID' => $val->ID, 
						'post_type' => $val->post_type, 
						'post_author' => $val->post_author, 
						'post_date' => $val->post_date, 
						'post_content' => $val->post_content, 
						'post_parent' => $val->post_parent
		);
	}
	
	if ( 'pp_opportunity' == $value['post_type'] ) {
		
		// SET VARIABLES
		$id = $value['ID'];
		$launch_id = $id;
		$title = $value['post_title'];
		$date = $value['post_date'];
		$onemeta = po_required_fields_1();
		$twometa = po_required_fields_2();
		$meta1 = '';
		$meta2 = '';
		$exout = array( '_po_user', '_pp_description', '_pp_form', '_po_id' );
		foreach ( $onemeta as $ikey => $ival ) {
			if ( in_array( $ival['meta']['meta_key'], $exout ) ) { continue; }
			$meta1 .= $ival['meta']['name'].': <strong>'.get_post_meta( $id, $ival['meta']['meta_key'], true ).'</strong> ';
		}
		foreach ( $twometa as $ikey => $ival ) {
			if ( in_array( $ival['meta']['meta_key'], $exout ) ) { continue; }
			$meta2 .= $ival['meta']['name'].': <strong>'.get_post_meta( $id, $ival['meta']['meta_key'], true ).'</strong> ';
		}
		$inner_return_html .= '<div>';
		$inner_return_html .= '<a href="' . admin_url( 'post.php?post='.$id.'&action=edit' ) . '">'.$title.'</a> Submitted: ' . date( 'D M d, Y g:i:s A', strtotime( $date ) ) . '<br />';
		$inner_return_html .= $meta1.'<br />';
		$inner_return_html .= $meta2.'<br />';
		$inner_return_html .= '</div>';

	}
	
	return $inner_return_html;
	
}