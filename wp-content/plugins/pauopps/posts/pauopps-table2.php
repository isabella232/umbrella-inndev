<?php


if ( !class_exists( 'PP_List_Table' ) ) {
	$pdir = explode( 'plugins/', plugin_dir_path( __FILE__ ) );
    require_once( $pdir[0] . 'plugins/presspoint/fields/pp-list-table.php' );
}

class PP_Tasks_Table extends PP_List_Table {
    
	function pp_opps_query() {
		
		$tasks = array();
		$tasks[] = array(
							's' => 'Related to an Event', 
							'a' => '245', 
							'o' => 'A Sample Event', 
							'd' => '09/05/2014', 
							'u' => 'Not Started', 
							'p' => 'High', 
							'g' => '142', 
		);
		$tasks[] = array(
							's' => 'Related to an Opportunity', 
							'a' => '244', 
							'o' => 'A Sample Opportunity', 
							'd' => '09/16/2014', 
							'u' => 'Not Started', 
							'p' => 'High', 
							'g' => '142', 
		);
		$tasks[] = array(
							's' => 'No Related Action', 
							'a' => '244', 
							'o' => false, 
							'd' => '09/16/2014', 
							'u' => 'Not Started', 
							'p' => 'High', 
							'g' => '142', 
		);	
		$tasks[] = array(
							's' => 'No Due Date', 
							'a' => '245', 
							'o' => 'A Sample Opportunity', 
							'd' => false, 
							'u' => 'Not Started', 
							'p' => 'High', 
							'g' => '142', 
		);
		return $tasks;
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
        $actions['edit'] = '<a href="' . admin_url() . 'post.php?post='.$item['s'].'&action=edit">edit</a>'; 
 
        return sprintf('%1$s %2$s',
            /*$1%s*/ '<span id="'.$item['s'].'">' . $item['name'] . '</span>',
            /*$2%s*/ $this->row_actions($actions)
        );
    }
    
	function column_a($item){
	    return '<a href="">'.pp_get_user_name( $item['a'] ).'</a>';
	}
	function column_g($item){
	    return '<a href="">'.pp_get_user_name( $item['g'] ).'</a>';
	}
	function column_s($item){
	    return '<a href="">'.$item['s'].'</a>';
	}
	function column_o($item){
	    return '<a href="">'.$item['o'].'</a>';
	}
/*
 	function column_d($item){
 	    return date_i18n( get_option( 'date_format' ), strtotime( $item['d'] ) );
 	}
*/    
    function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],
            /*$2%s*/ $item['s']
        );
    }
    
    
    function get_columns(){
        $columns = array(
            'cb'        => '<input type="checkbox" />', //Render a checkbox instead of text
            's'			=> 'Title',
            'o'		 	=> 'Action', 
            'a'		 	=> 'User', 
            'd'			=> 'Date', 
            'u'		 	=> 'Status', 
            'p'		 	=> 'Priority', 
			'g'			=> 'Assigned',
        );
        return $columns;
    }
    

    function get_sortable_columns() {
        $sortable_columns = array(
            'name'			=> array('name',true), 
            'form'			=> array('form',true),
            'date'			=> array('date',true),
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
        if( 'delete' === $this->current_action() ) {
        	$ids = $_REQUEST[$this->_args['singular']];
        	if ( current_user_can( 'edit_users' ) ) {
	        	foreach ( $ids as $id ) {
	        		wp_delete_post( $id );
	        	}
            }	
            
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
        $per_page = apply_filters( 'pp_opps_per_page_paginate', 25 );
        
        
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
            $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'd'; //If no sort, default to title
            $order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'desc'; //If no order, default to asc
            $result = strcmp($a[$orderby], $b[$orderby]); //Determine sort order
            return ($order==='asc') ? $result : -$result; //Send final sort direction to usort
        }
        usort($data, 'usort_reorder');
        
        
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
    }
    
}