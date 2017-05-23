<?php

/**
 * This is the wrapper function for the PauPress Options API. Your function should be called {prefix}_options.
 *
 * @since 1.0.2
 *
 * @param none This should take no parameters but should call the general paupress_options_form.
 *
 * @return html outputs the options page with the activated option tab + contents.
 */
function pauopps_options_tabs() {

	return apply_filters( 'pauopps_options_tabs', array( 
		
		'pauopps_general_settings' => array( 
									'title' => __( 'Opportunities Settings', 'paupress' ), 
									'subs' => array( 
													'pp_forms_pauopps_settings' => array( 
																				'title' => __( 'Opportunity Templates', 'paupress' ), 
																				'subs' => false, 
																				'args' => array( 'type' => 'pp_opportunity' ), 
																		),
													'pauopps_builder_settings' => array( 
																				'title' => '', 
																				'subs' => false, 
																		), 	
									), 
							),
		/*					
		'pp_forms_pauopps_settings' => array( 
									'title' => __( 'Opportunity Templates', 'paupress' ), 
									'subs' => false, 
									'args' => array( 'type' => 'pp_opportunity' ), 
							),		
		'pp_builder_settings' => array( 
									'title' => '', 
									'subs' => false, 
							), 
		*/
	) );
	
	//paupress_options_form( __( 'PauPress Opportunity Settings', 'paupress' ), $navigation );
	
}

function pauopps_builder_settings() {
	pp_builder_settings();
}


function pauopps_options_tabs_global( $options = null ) {
	$navigation = pauopps_options_tabs();
	return $options + $navigation;
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
function pauopps_options_form() {
	$navigation = pauopps_options_tabs();
	paupress_options_form( __( 'PauPress Opportunity Settings', 'paupress' ), $navigation );
}


function pauopps_license_settings( $fields ) {
	$fields[] = array( 'meta' => array( 
								'source' => 'paupress', 
								'meta_key' => 'pauopps_license_title', 
								'name' => __( 'Opportunities License Information', 'paupress' ), 
								'help' => '', 
								'description' => __( 'Please enter your license details below.', 'paupress' ), 
								'options' => array( 
													'field_type' => 'title',
													'req' => false, 
													'public' => false, 
													'choices' => false
								) 
		) );
		
	$fields[] = array( 'meta' => array( 
								'source' => 'paupress', 
								'meta_key' => 'pauopps_license_key', 
								'name' => __( 'Your license key', 'paupress' ), 
								'help' =>'', 
								'lpos' => 'none', 
								'options' => array( 
													'field_type' => 'text',
													'req' => false, 
													'public' => false, 
													'choices' => false,  
								) 
		) );	
	return $fields;
}


function pauopps_general_settings() {
	
	return apply_filters( 'pauopps_general_settings', array(
	
		array( 'meta' => array( 
								'source' => 'paupress', 
								'meta_key' => 'pauopps_general_title', 
								'name' => __( 'General Options', 'paupress' ), 
								'help' => '', 
								'description' => '', 
								'options' => array( 
													'field_type' => 'title',
													'req' => false, 
													'public' => false, 
													'choices' => false
								) 
		) ),
		
		array( 'meta' => array( 
								'source' => 'paupress', 
								'meta_key' => 'pauopps_pagination', 
								'name' => __( 'Set the number of Opportunities per page', 'paupress' ), 
								'help' => '', 
								'hpos' => 'pre', 
								'lpos' => 'top', 
								'field_type' => 'number',
								'req' => false, 
								'public' => false, 
								'default' => 10, 
								'choices' => false, 
								'class' => array( 'input-medium' ), 
		) ),
		
		array( 'meta' => array( 
								'source' => 'paupress', 
								'meta_key' => 'pauopps_stages_title', 
								'name' => __( 'Stage Options', 'paupress' ), 
								'help' => '', 
								'description' => __( 'Customize your stages below.', 'paupress' ), 
								'options' => array( 
													'field_type' => 'title',
													'req' => false, 
													'public' => false, 
													'choices' => false
								) 
		) ),
		
		array( 'meta' => array( 
								'source' => 'paupress', 
								'meta_key' => 'pauopps_stages', 
								'name' => __( 'Customize your stages', 'paupress' ), 
								'help' => __( 'Labels are what you see while Values are what the database uses.', 'paupress' ), 
								'hpos' => 'pre', 
								'lpos' => 'top', 
								'field_type' => 'multitext',
								'req' => false, 
								'public' => false, 
								'add_fields' => array( 
														'label' => array( 
																		'name' => __( 'Label', 'paupress' ), 
																		'type' => 'text', 
															), 
								), 
								'default' => pauopps_stages_default(), 
								'choices' => array( 
													array( 
															'value' => __( 'archive', 'paupress' ), 
															'label' => __( 'Archive', 'paupress' ) 
													), 
								),  
		) ),
					
	) );
	
}

function pauopps_stages_default() {
	return array( 
						array( 'value' => 'new', 'label' => 'New Lead' ), 
						array( 'value' => 'qualified', 'label' => 'Qualified Prospect' ), 
						array( 'value' => 'verbal', 'label' => 'Verbal Approval' ), 
						array( 'value' => 'won', 'label' => 'Closed (Won)' ), 
						array( 'value' => 'lost', 'label' => 'Closed (Lost)' ), 
	);
}


function pauopps_form_builder( $type, $form ) {

	global $type, $usertype, $settings, $required, $internal, $optional;
	switch ( $type ) {
		
		case 'pp_opportunity' :
			$args = array();
			$args['label'] =  __( 'Form Settings', 'paupress' );
			$args['prefix'] = 'settings';
			$args['action'] = 'edit';
			$args['type'] = 'option';
			$args['object'] = $form;
			pp_form_api_fields( array( pp_form_notify( $form ), pp_form_messages( $form ) ), $args );
			$args = array();
			$args['label'] =  __( 'Default Fields', 'paupress' );
			$args['prefix'] = 'ppf_required';
			$args['action'] = 'view';
			$args['type'] = 'option';
			$args['object'] = $form;
			pp_form_api_fields( array( po_required_fields_1(), po_required_fields_2() ), $args );
			pp_form_builder_optional_fields( $form, array( 'key' => 'internal', 'title' => __( 'Internal Fields', 'paupress' ) ) );
			pp_form_builder_optional_fields( $form, array( 'title' => __( 'External Fields', 'paupress' ) ) );
		break;
	
	}
}

function pp_forms_pauopps_settings( $args = false ) {
	return apply_filters( 'pp_forms_pauopps_settings', array(

		array( 'meta' => array( 
								'source' => 'paupress', 
								'meta_key' => 'pp_form_display', 
								'name' => '', 
								'help' => '', 
								'lpos' => 'none', 
								'args' => $args, 
								'field_type' => 'plugin', 
								'choices' => 'pp_forms_plugin' 
		) ), 

	));
	
}


function pauopps_form_notify( $fields, $form ) {
	if ( 'pp_opportunity' == $form['type'] ) {
		foreach ( $fields as $k => $v ) {
			if ( 'display' == $v['meta']['meta_key'] ) {
				$fields[$k] = array( 'meta' => array( 
											'source' => 'paupress', 
											'meta_key' => '_pp_trans_type', 
											'lpos' => 'left', 
											'name' => __( 'Enable payments', 'paupress' ), 
											'help' =>  __( 'If enabled, you have the option of sharing a link to allow online payment of the Opportunity amount.', 'paupress' ), 
											'field_type' => 'select', 
											'args' => $form, 
											'choices' => array( 
																'donation' => __( 'Donation', 'paupress' ), 
																'purchase' => __( 'Purchase', 'paupress' ), 
											), 
				) );
				$new_k = $k + 1;
				$fields[$new_k] = $v;
			}
		}
	}
	return $fields;
}


function pauopps_paumail_post_types( $post_types ) {
	
	$post_types['pp_opportunity'] = get_post_type_object( 'pp_opportunity' );
	return $post_types;
	
}