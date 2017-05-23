<?php


if ( !class_exists( 'PP_List_Table' ) ) {
	$pdir = explode( 'plugins/', plugin_dir_path( __FILE__ ) );
    require_once( $pdir[0] . 'plugins/presspoint/fields/pp-list-table.php' );
}

class PP_Opps_Table extends PP_List_Table {
    
	function pp_opps_query() {
		
		global $wpdb, $current_user;
		/*
		// UPDATE THE TABLES
		$wpdb->update( 
			$wpdb->postmeta, 
			array( 
				'meta_key' => '_pp_agents',	// string
			), 
			array( 'meta_key' => '_po_agent' ), 
			array( 
				'%s',	// value1
			), 
			array( '%s' ) 
		);
		
		// UPDATE THE TABLES
		$wpdb->update( 
			$wpdb->postmeta, 
			array( 
				'meta_key' => '_pp_agent',	// string
			), 
			array( 'meta_key' => '_po_created_by' ), 
			array( 
				'%s',	// value1
			), 
			array( '%s' ) 
		);
		*/
		
		// PULL THE STATUSES
		$stages = pauopps_stages();
		$staged = array();
		$meta_query = array();
		foreach ( $stages as $stage ) {
			if ( 'archive' == $stage['type'] ) {
				$staged[] = $stage['value'];
			}
		}
		if ( !empty( $staged ) ) {
			$meta_query = array(
									array( 
											'key' => '_po_stage', 
											'value' => $staged, 
											'compare' => 'NOT IN', 
									)
			);
		}
		
		// LET USER SELECTION OVERRIDE IT
		if ( !empty( $_REQUEST['status'] ) ) {
			if ( 'no_status' == $_REQUEST['status'] ) {
				$meta_query = array(
										array( 
												'key' => '_po_stage', 
												'value' => 'bug #23268', 
												'compare' => 'NOT EXISTS', 
										)
				);
			} else {
				$meta_query = array(
										array( 
												'key' => '_po_stage', 
												'value' => $_REQUEST['status'], 
												'compare' => 'IN', 
										)
				);
			}
		}
		
		$query = array( 
						'post_type' => array( 'pp_opportunity' ), 
						'post_status' => array( 'publish','draft' ), 
						'orderby' => 'modified',
						'order' => 'DESC',
						'posts_per_page' => -1, 
		);
		
		// ADD THE META QUERIES
		if ( !empty( $meta_query ) ) {
			$query['meta_query'] = $meta_query;
		}
		
		if ( !current_user_can( 'manage_options' ) ) {
			$agent = $current_user->ID;
			$query['meta_query'][] = array( 
											'key' => '_pp_agents', 
											'value' => $agent, 
											'compare' => 'LIKE', 
			);
		}
		$results = new WP_Query( $query );			
		$opps = array();
		
		if ( $results->have_posts() ) {
			while ( $results->have_posts() ) {
				$result = $results->next_post();
				
				$state = false;
				$status = get_post_meta( $result->ID, '_po_stage', true );
			
				if ( false != $status ) {
					foreach ( $stages as $stage ) {
						if ( $status == $stage['value'] || $status == $stage['label'] ) {
							$state = $stage['label'];
						}
					}
				}
				if ( !empty( $_REQUEST['s'] ) ) {
					if ( false === stripos( $result->post_title, $_REQUEST['s'] ) ) {
						continue;
					}
				}
			
				$form = get_option( get_post_meta( $result->ID, '_pp_form', true ) );
				$opps[] = array( 
								'ID' => $result->ID, 
								'name' => $result->post_title, 
								'agent' => pp_get_user_name( get_post_meta( $result->ID, '_pp_agents', true ) ), 
								'client' => pp_get_user_name( $result->post_author ), 
								'dadd' => $result->post_date, 
								'dmod' => $result->post_modified, 
								'form' => $form['title'], 
								'status' => $state,  
				);
			}
		}
		return $opps;
	}
    
    function __construct(){
        global $status, $page;
                
        //Set parent defaults
        parent::__construct( array(
            'singular'  => 'pp_opps_query', 
            'plural'    => 'pp_opps_queries', 
            'ajax'      => true 
        ) );
        
    }
        
    function column_default($item, $column_name){
        switch($column_name){
            default :
            	if ( isset( $item[$column_name] ) ) {
            		return $item[$column_name];
            	} else {
                	return false;
              	}
    		break;
        }
    }
    
    function column_name($item){
        $actions = array();
        $actions['edit'] = '<a href="' . admin_url() . 'post.php?post='.$item['ID'].'&action=edit">edit</a>'; 
 
        return sprintf('%1$s %2$s',
            /*$1%s*/ '<span id="'.$item['ID'].'">' . $item['name'] . '</span>',
            /*$2%s*/ $this->row_actions($actions)
        );
    }
    
	function column_form($item){
	    return $item['form'];
	}

	function column_status($item){
	    return $item['status'];
	}
	
 	function column_dadd($item){
 	    return date_i18n( get_option( 'date_format' ), strtotime( $item['dadd'] ) ) . '<br />' . date_i18n( get_option( 'time_format' ), strtotime( $item['dadd'] ) );
 	}
 	function column_dmod($item){
 	    return date_i18n( get_option( 'date_format' ), strtotime( $item['dmod'] ) ) . '<br />' . date_i18n( get_option( 'time_format' ), strtotime( $item['dmod'] ) );
 	}
    
    function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],
            /*$2%s*/ $item['ID']
        );
    }
    
    
    function get_columns(){
        $columns = array(
            'cb'        	=> '<input type="checkbox" />', //Render a checkbox instead of text
            'name'			=> __( 'Title', 'paupress' ), 
            'form'		 	=> __( 'Form', 'paupress' ), 
            'client'		=> __( 'Client', 'paupress' ), 
            'dadd'		 	=> __( 'Created', 'paupress' ), 
            'dmod'		 	=> __( 'Modified', 'paupress' ), 
            'agent'		 	=> __( 'Agent', 'paupress' ), 
			'status'		=> __( 'Status','paupress' ), 
        );
        return $columns;
    }
    

    function get_sortable_columns() {
        $sortable_columns = array(
            'name'			=> array('name',true), 
            'form'			=> array('form',true),
            'dadd'			=> array('dadd',true),
            'dmod'			=> array('dmod',true),
            'agent'			=> array('agent',true),
            'status'		=> array('status',true),
        );
        return $sortable_columns;
    }
    
    
    function get_bulk_actions() {
        $actions = array(
            'delete'    => 'Delete'
        );
        return $actions;
    }
    
    
    function process_bulk_action() {
        
        //Detect when a bulk action is being triggered...
        if ( 'delete' === $this->current_action() ) {
        	$ids = $_REQUEST[$this->_args['singular']];
        	if ( current_user_can( 'edit_users' ) ) {
	        	foreach ( $ids as $id ) {
	        		wp_delete_post( $id );
	        	}
            }	
            
        }
        
    }
    
    function extra_tablenav( $which ) {
    
    	global $pagenow;
    
    	if ( 'top' == $which ) {
			echo '<div class="alignleft actions bulkactions">';
			//echo '<form enctype="multipart/form-data" action="'.admin_url( $pagenow ).'" method="GET">';
			
			$perpage = get_option( 'pauopps_pagination' );
			if ( !empty( $_REQUEST['per_page'] ) ) {
				$perpage = $_REQUEST['per_page'];
			} else if ( empty( $perpage ) ) {
				$perpage = 10;
			}
			echo ' <span style="float: left;">' . __( 'Results per page', 'paupress' ) . ' <input type="text" class="input-short" name="per_page" value="' . $perpage . '" /></span> '; 
			
			$stages = pauopps_stages();
			$stages[] = array( 'label' => __( 'No Status', 'paupress' ), 'value' => 'no_status' );
			
			echo '<select name="status">';
			echo '<option value="">'.__( 'Filter Status', 'paupress' ).'</option>';
			foreach ( $stages as $k => $v ) {
				echo '<option value="'.$v['value'].'"'. selected( $_REQUEST['status'], $v['value'], true ) . '>'.$v['label'].'</option>';
			}
			echo '</select>';
			
			echo '<input type="submit" name="mytypes" class="button" value="Apply">';
			//echo '</form>';
			echo ' <a class="button-primary" style="margin-left: 20px;" href="' . admin_url( '/' . $pagenow .'?page=' . $_REQUEST['page'] ) . '">' . __( 'Reset filters', 'paupress' ) . '</a>';
			echo '</div>';
    	}
    }
    
    
    /** ************************************************************************
     * REQUIRED! This is where you prepare your data for display. This method will
     * usually be used to query the database, sort and filter the data, and generally
     * get it ready to be displayed. At a minimum, we should set $this->items and
     * $this->set_pagination_args(), although the following properties and methods
     * are frequently interacted with here...
     * 
     * @uses $this->_column_headers
     * @uses $this->items
     * @uses $this->get_columns()
     * @uses $this->get_sortable_columns()
     * @uses $this->get_pagenum()
     * @uses $this->set_pagination_args()
     **************************************************************************/
    function prepare_items() {
        
        /**
         * First, lets decide how many records per page to show
         */
        $pre_page = get_option( 'pauopps_pagination' );
        if ( !empty( $_REQUEST['per_page'] ) ) {
        	$pre_page = $_REQUEST['per_page'];
        } else if ( empty( $pre_page ) ) {
       		$pre_page = 10;
        }
        $per_page = apply_filters( 'pp_opps_per_page_paginate', $pre_page );
        
        
        /**
         * REQUIRED. Now we need to define our column headers. This includes a complete
         * array of columns to be displayed (slugs & titles), a list of columns
         * to keep hidden, and a list of columns that are sortable. Each of these
         * can be defined in another method (as we've done here) before being
         * used to build the value for our _column_headers property.
         */
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        
        
        /**
         * REQUIRED. Finally, we build an array to be used by the class for column 
         * headers. The $this->_column_headers property takes an array which contains
         * 3 other arrays. One for all columns, one for hidden columns, and one
         * for sortable columns.
         */
        $this->_column_headers = array($columns, $hidden, $sortable);
        
        
        /**
         * Optional. You can handle your bulk actions however you see fit. In this
         * case, we'll handle them within our package just to keep things clean.
         */
        $this->process_bulk_action();
        
        
        /**
         * Instead of querying a database, we're going to fetch the example data
         * property we created for use in this plugin. This makes this example 
         * package slightly different than one you might build on your own. In 
         * this example, we'll be using array manipulation to sort and paginate 
         * our data. In a real-world implementation, you will probably want to 
         * use sort and pagination data to build a custom query instead, as you'll
         * be able to use your precisely-queried data immediately.
         */
        $data = $this->pp_opps_query();
                
        
        /**
         * This checks for sorting input and sorts the data in our array accordingly.
         * 
         * In a real-world situation involving a database, you would probably want 
         * to handle sorting by passing the 'orderby' and 'order' values directly 
         * to a custom query. The returned data will be pre-sorted, and this array
         * sorting technique would be unnecessary.
         */
        function usort_reorder($a,$b){
            $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'dmod'; //If no sort, default to title
            $order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'desc'; //If no order, default to asc
            $result = strcmp($a[$orderby], $b[$orderby]); //Determine sort order
            return ($order==='asc') ? $result : -$result; //Send final sort direction to usort
        }
        if ( !empty($_REQUEST['orderby']) ) {
        	usort($data, 'usort_reorder');
        }
        
        
        /***********************************************************************
         * ---------------------------------------------------------------------
         * vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
         * 
         * In a real-world situation, this is where you would place your query.
         * 
         * ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
         * ---------------------------------------------------------------------
         **********************************************************************/
        
                
        /**
         * REQUIRED for pagination. Let's figure out what page the user is currently 
         * looking at. We'll need this later, so you should always include it in 
         * your own package classes.
         */
        $current_page = $this->get_pagenum();
        
        /**
         * REQUIRED for pagination. Let's check how many items are in our data array. 
         * In real-world use, this would be the total number of items in your database, 
         * without filtering. We'll need this later, so you should always include it 
         * in your own package classes.
         */
        $total_items = count($data);
        
        
        /**
         * The WP_List_Table class does not handle pagination for us, so we need
         * to ensure that the data is trimmed to only the current page. We can use
         * array_slice() to 
         */
        $data = array_slice($data,(($current_page-1)*$per_page),$per_page);
        
        
        
        /**
         * REQUIRED. Now we can add our *sorted* data to the items property, where 
         * it can be used by the rest of the class.
         */
        $this->items = $data;
        
        
        /**
         * REQUIRED. We also have to register our pagination options & calculations.
         */
        $this->set_pagination_args( array(
            'total_items' => $total_items,                  //WE have to calculate the total number of items
            'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
            'total_pages' => ceil($total_items/$per_page)   //WE have to calculate the total number of pages
        ) );
        
		$this->search_box('search', 'search_id');
        
    }
    
}