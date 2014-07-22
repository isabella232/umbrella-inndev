<?php

/**
 *  ==============  Membership Directory stuff  ==============
 */

/**
 * Define the custom post type
 * Needed for featured images still
 */
function inn_init_members() {

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

	//set an image size for the member widget
	add_image_size( 'member-thumbnail', 60, 60, true );

	//build a menu for the widget and listing header
	register_nav_menu( 'membership', 'Members Menu' );

}
add_action( 'init', 'inn_init_members', 11 );


/**
 * Widget listing members
 */
class members_widget extends WP_Widget {

  function __construct() {
    $options = get_option('members_options');
    $widget_ops = array( 'classname' => 'inn-members-widget', 'description' => 'A list of INN members, showing logo icons' );
    $control_ops = array( 'width' => 300, 'height' => 250, 'id_base' => 'members-widget' );
    $this->WP_Widget( 'members-widget', 'INN Member List', $widget_ops, $control_ops );
  }


  function widget($args, $instance) {
    extract($args);
    echo $before_widget;
		$menu = wp_nav_menu( array(
			'theme_location' => 'membership',
			'container' => false,
			'menu_class' => 'members-menu in-widget',
			'depth' => 1,
			'echo' => 0)
		);

    if (!empty($instance['title'])) echo $before_title . '<span>' . $instance['title'] . '</span>' . $menu . $after_title; ?>

    <div class="member-wrapper widget-content hidden-phone">
	    <ul class="members">
	    <?php
	      $counter = 1;
	      $member_list = inn_get_members( true );
	      foreach ($member_list as $member) :
	      	if ( !$member->data->paupress_pp_avatar['value'] ) continue;	//skip members without logos
	      ?>
	        <li id="member-list-<?php echo $member->ID;?>" class="<?php echo $member->data->paupress_pp_avatar['value']; ?>">
	        	<a href="<?php echo get_author_posts_url($member->ID) ?>" class="member-thumb" title="<?php esc_attr_e($member->display_name) ?>">
	        		<?php echo wp_get_attachment_image(
	        			$member->data->paupress_pp_avatar['value'],
	        			'member-thumbnail',
	        			0,
	        			array(
	        				'alt' => $member->data->display_name
	        			)
	        		); ?>
	        	</a>
	        </li>
	      <?php endforeach; ?>
	    </ul>
	    <div class="member-details-wrapper">
	    	<span class="close"><i class="icon-cancel"></i></span>
	    	<div class="member-details"></div>
	    </div>
    </div>
  <?php
    echo $after_widget;
  }

  function update($new_instance, $old_instance) {
    $instance = $old_instance;
    /* Strip tags (if needed) and update the widget settings. */
    $instance['title'] = strip_tags( $new_instance['title'] );
    return $instance;
  }

  function form($instance) { ?>
    <p>
     <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e("Title"); ?>:</label>
     <input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" class="widefat" />
    </p>
  <?php
  }
}


/**
 * Map on archive page
 */
function inn_member_map() {

	$members = inn_get_members( true );
	$api_key = "AIzaSyD82h0mNBtvoOmhC3N4YZwqJ_xLkS8yTuw";
	?>
	<div id="map-container">
	</div>
	<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=<?php echo $api_key; ?>&sensor=false"></script>
	<script type="text/javascript">
		//convenience objects
		var $map = jQuery("#map-container"),
			gm = google.maps,
			infoWin = new gm.InfoWindow({ content: "default" }),
			markers = [];

		//new look!
		gm.visualRefresh = true;

		//create the map
		var gMap = new gm.Map(document.getElementById("map-container"), {
			center: new gm.LatLng(39.828328, -98.579416),
			zoom: 4,
			mapTypeId: google.maps.MapTypeId.TERRAIN
		});

		// Function for creating a marker on the map
		function createMarker( markerinfo ) {
			var marker = new gm.Marker({
				map: gMap,
				draggable: false,
				animation: gm.Animation.DROP,
				position: markerinfo.latLng,
				title: markerinfo.title
			});
			marker.data = markerinfo.d;

			//event listening
			gm.event.addListener(marker, 'click', function() {
				infoWin.setContent( marker.data );
				infoWin.open(gMap, marker);
			});

			//just making sure we have these?
			markers.push(marker);
		}

		// The array of places
		var marker_list = [
		<?php
		 foreach ( $members as $member ) :
		 	//skip members without coordinates
		 	if ( !isset($member->data->paupress_address_latitude_1['value']) || empty($member->data->paupress_address_latitude_1['value'])) continue;
		 	$info = sprintf('<div class="map-popup"><a href="%s" class="map-name">%s</a><br/><a href="%s" target="_blank">%s</a></div>',
		 		get_author_posts_url($member->ID),
		 		htmlspecialchars($member->display_name, ENT_QUOTES),
		 		$member->data->user_url,
		 		$member->data->user_url
		 	);
			 ?>{
title: "<?php echo htmlspecialchars($member->display_name, ENT_QUOTES); ?>",
latLng: new gm.LatLng(<?php echo $member->data->paupress_address_latitude_1['value'] . "," . $member->data->paupress_address_longitude_1['value'] ?>),
d: '<?php echo $info; ?>'
},<?php
		 endforeach;
		?>
		];

		//now load 'em up
		for (var i = 0; i < marker_list.length; i++) {
			(function(newmarker, idx) {
				setTimeout( function() {
					createMarker( newmarker );
				}, idx * 20 );
			})(marker_list[i], i);
		}

	</script>
	<?php
}

/**
 * Alphabetical links
 */
function inn_member_alpha_links() {

	global $wp;
	$core_url = "/" . preg_replace( '/page\/(\d+)/', '', $wp->request );

	//populate an array of potentially linked letters
	$links = array_merge( array("num"), range('A','Z'), array("All") );

	//populate an array of all the letters that have entries
	$members = inn_get_members( true );
	$member_firsts = array('All');

	foreach ($members as $mem) {
		$first = strtoupper($mem->data->display_name[0]);
		if ( is_numeric($first) ) $first = "num";
		if ( !in_array($first, $member_firsts) ) $member_firsts[] = $first;
	}

	//Loop thru and display links as appropriate
	print '<div class="member-nav"><ul>';
	foreach( $links as $link ) {
		$class = ( $link == $_GET['letter'] ) ? 'class="current-letter"' : "" ;
		print "<li $class>";
		if ( in_array($link, $member_firsts) ) {
			$url = $core_url;
			if ( $link != "All" )	$url .= "?letter=" . $link;
			if ( $link == "num" ) $link = "0-9";
			printf('<a href="%s">%s</a>', $url, $link);
		} else {
			print $link;
		}
	}
	print "</ul></div>";
}


/*
 * Make WP_User_Query support user_starts_with
 */
add_filter( 'pre_user_query', 'inn_user_starts_with' );
function inn_user_starts_with( $clauses ) {
	global $wpdb;
  //needs to handle digits, ugh
  if ( $title_starts_with = $clauses->query_vars['user_starts_with'] ) {
  	if ( 'num' == $title_starts_with ) {
	  	$clauses->query_where .= ' AND ' . $wpdb->users . '.display_name NOT REGEXP \'^[[:alpha:]]\'';
  	} else {
  		$clauses->query_where .= ' AND UPPER(' . $wpdb->users . '.display_name) LIKE \'' . esc_sql( like_escape( $title_starts_with ) ) . '%\'';
		}
  }
  return $clauses;
}

/**
 * Converts a taxonomy term slug into a serialized string of its ID for use in searching usermeta
 */
function inn_prep_user_term( $slug, $taxonomy ) {
	$term_object = get_term_by( 'slug', $slug, $taxonomy );
	// strip first character off, because... why?
	//$locate = substr( $term_object->term_id, 1, -1 );
	$locate = $term_object->term_id;
	return 's:' . strlen($locate) . ':"' . $locate . '"';
}

/**
 * Outputs a list of categories to filter users/members by
 */
function inn_member_categories_list() {

	global $wp;
	$core_url = "/" . preg_replace( '/page\/(\d+)/', '', $wp->request );

	print '<div class="member-nav member-focus-nav"><ul>';
	$terms = get_terms( INN_MEMBER_TAXONOMY, array( 'hide_empty' => FALSE ) );
	if ( !empty( $terms ) && !is_wp_error( $terms ) ) {
		$terms[] = (object)array( 'slug'=>'all', 'name' => 'All' );
		foreach ($terms as $term) {
			$link = ($term->slug == 'all') ? "" : "?focus=" . $term->slug;
			$class = ( $link == $_GET['focus'] ) ? 'class="current-letter"' : "" ;
			print "<li $class>";
			printf('<a href="%s">%s</a>', $core_url . $link, $term->name );
			print "</li>";
		}
	}
	print "</ul></div>";
}

/**
 * Outputs a list of states to filter users/members by
 */
function inn_member_states_list() {
	$selected = (isset($_GET['state']) ) ? $_GET['state'] : "all" ;
	echo '<div class="member-nav member-state-nav"><select name="member-state">';
	echo '<option value="all">All</option>';
	$states = paupress_get_helper_states();
	$states['US']['intl'] = __('International', 'inn');
	foreach ( $states['US'] as $abbrev => $name ) { ?>
		<option value="<?php echo $abbrev; ?>" <?php selected( $abbrev, $selected ); ?>><?php echo $name; ?></option>
	<?php }
	print "</select><button>GO</button></div>";
}


/**
 * Kill redirect so pagination works for single members that have RSS items
 * See http://petetasker.wordpress.com/2012/05/18/wordpress-pagination-on-custom-posts/
 */
function inn_disable_member_redirect( $redirect_url ) {
	if (is_singular('inn_member')) $redirect_url = false;
	return $redirect_url;
}
add_filter('redirect_canonical', 'inn_disable_member_redirect');


/**
 * Load up the RSS handling
 */
require_once('inn_members_rss.php');