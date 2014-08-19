<?php

/**
 *  ==============  Membership Directory stuff  ==============
 */


function inn_member_functions() {

	//necessary for using is_plugin_active();

	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	/**
	 * Defined typically in paupress/utilities/paupress-fields.php
	 */
	 if ( ! function_exists('paupress_get_option') ) {

		 function paupress_get_option( $key, $ret_key = false ) {
			// DIFFERENTIATE WORDPRESS AND THIRD-PARTY DEFAULTS
			if ( '_pp_field' != substr( $key, 0, 9 ) ) {
				$option_key = '_pp_field_'.$key;
			} else {
				$option_key = $key;
			}

			// IF $ret_key IS TRUE, JUST RETURN THE KEY
			if ( false != $ret_key )
				return $option_key;

			// ELSE, RETURN THE OPTIONS CONTENTS
			return get_option( $option_key );

		}
	}

	/**
	 * Formerly (and possibly still) defined in PauINN
	 */
	if ( ! function_exists('inn_get_members') ) {
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
	}
}
/**
 * We use wp_head instead of something more reasonable like 'init' because when a plugin is first activated it's
 * loaded a bit differently (read: later than init) and we get function definition collisions.
 */
add_action( 'wp_head', 'inn_member_functions', 99 );

/**
 * For getting a list of states that members are in
 */
function inn_member_states() {
	$states = array(
		'AL' => __('Alabama', 'inn'),
		'AK' => __('Alaska', 'inn'),
		'AZ' => __('Arizona', 'inn'),
		'AR' => __('Arkansas', 'inn'),
		'CA' => __('California', 'inn'),
		'CO' => __('Colorado', 'inn'),
		'CT' => __('Connecticut', 'inn'),
		'DE' => __('Delaware', 'inn'),
		'DC' => __('District Of Columbia', 'inn'),
		'FL' => __('Florida', 'inn'),
		'GA' => __('Georgia', 'inn'),
		'HI' => __('Hawaii', 'inn'),
		'ID' => __('Idaho', 'inn'),
		'IL' => __('Illinois', 'inn'),
		'IN' => __('Indiana', 'inn'),
		'IA' => __('Iowa', 'inn'),
		'KS' => __('Kansas', 'inn'),
		'KY' => __('Kentucky', 'inn'),
		'LA' => __('Louisiana', 'inn'),
		'ME' => __('Maine', 'inn'),
		'MD' => __('Maryland', 'inn'),
		'MA' => __('Massachusetts', 'inn'),
		'MI' => __('Michigan', 'inn'),
		'MN' => __('Minnesota', 'inn'),
		'MS' => __('Mississippi', 'inn'),
		'MO' => __('Missouri', 'inn'),
		'MT' => __('Montana', 'inn'),
		'NE' => __('Nebraska', 'inn'),
		'NV' => __('Nevada', 'inn'),
		'NH' => __('New Hampshire', 'inn'),
		'NJ' => __('New Jersey', 'inn'),
		'NM' => __('New Mexico', 'inn'),
		'NY' => __('New York', 'inn'),
		'NC' => __('North Carolina', 'inn'),
		'ND' => __('North Dakota', 'inn'),
		'OH' => __('Ohio', 'inn'),
		'OK' => __('Oklahoma', 'inn'),
		'OR' => __('Oregon', 'inn'),
		'PA' => __('Pennsylvania', 'inn'),
		'RI' => __('Rhode Island', 'inn'),
		'SC' => __('South Carolina', 'inn'),
		'SD' => __('South Dakota', 'inn'),
		'TN' => __('Tennessee', 'inn'),
		'TX' => __('Texas', 'inn'),
		'UT' => __('Utah', 'inn'),
		'VT' => __('Vermont', 'inn'),
		'VA' => __('Virginia', 'inn'),
		'WA' => __('Washington', 'inn'),
		'WV' => __('West Virginia', 'inn'),
		'WI' => __('Wisconsin', 'inn'),
		'WY' => __('Wyoming', 'inn'),
		'AA' => __('Armed Forces Americas', 'inn'),
		'AE' => __('Armed Forces Europe', 'inn'),
		'AP' => __('Armed Forces Pacific', 'inn'),
		'AS' => __('American Samoa', 'inn'),
		'FM' => __('Micronesia', 'inn'),
		'GU' => __('Guam', 'inn'),
		'MH' => __('Marshall Islands', 'inn'),
		'PR' => __('Puerto Rico', 'inn'),
		'VI' => __('U.S. Virgin Islands', 'inn'),
		'intl' => __('International', 'inn'),
	);
	return $states;
}



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
	if ( !isset($_GET['letter']) ) $_GET['letter'] = null;

	//populate an array of potentially linked letters
	$links = array_merge( array("- All -"), array("num"), range('A','Z') );

	//populate an array of all the letters that have entries
	$members = inn_get_members( true );
	$member_firsts = array('- All -');

	foreach ($members as $mem) {
		$first = strtoupper($mem->data->display_name[0]);
		if ( is_numeric($first) ) $first = "num";
		if ( !in_array($first, $member_firsts) ) $member_firsts[] = $first;
	}

	//Loop thru and display links as appropriate
	echo '<select name="member-letter">';
	echo '<option value="" disabled selected>' . __('First Letter', 'inn') . '</option>';
	foreach( $links as $link ) {
		echo '<option ';
		if ( in_array($link, $member_firsts) ) {
			$url = $core_url;
			if ( $link != "- All -" )	$url .= "?letter=" . $link;
			if ( $link == "num" ) $link = "0-9";
			echo 'value="' . $url . '" ' . selected( $_GET['letter'], $link, false )  . '>' . $link;
		} else {
			echo 'value="' . $url . '" disabled>' . $link;
		}
		echo "</option>";
	}
	print "</select>";
}


/*
 * Make WP_User_Query support user_starts_with
 */
add_filter( 'pre_user_query', 'inn_user_starts_with' );
function inn_user_starts_with( $clauses ) {
	global $wpdb;
  //needs to handle digits, ugh
  if ( isset($clauses->query_vars['user_starts_with']) && $title_starts_with = $clauses->query_vars['user_starts_with'] ) {
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
	if ( !isset($_GET['focus']) ) $_GET['focus'] = NULL;

	echo '<select name="member-category">';
	echo '<option value="" disabled selected>' . __('Focus Area', 'inn') . '</option>';
	$terms = get_terms( INN_MEMBER_TAXONOMY, array( 'hide_empty' => FALSE ) );
	if ( !empty( $terms ) && !is_wp_error( $terms ) ) {
		array_unshift( $terms, (object)array( 'slug'=>'all', 'name' => '- All -' ) );
		foreach ($terms as $term) {
			$link = ($term->slug == 'all') ? "?" : "?focus=" . $term->slug;
			echo "<option ";
			echo 'value="' . $core_url . $link . '" ' . selected( $_GET['focus'], $term->slug, false )  . '>' . $term->name;
			echo "</option>";
		}
	}
	print "</select>";
}

/**
 * Outputs a list of states to filter users/members by
 */
function inn_member_states_list() {

	global $wp;
	$core_url = "/" . preg_replace( '/page\/(\d+)/', '', $wp->request );

	$selected = (isset($_GET['state']) ) ? $_GET['state'] : "all" ;

	echo '<select name="member-state">';
	echo '<option value="" disabled selected>' . __('State', 'inn') . '</option>';
	echo '<option value="', $core_url, '">- All -</option>';
	$states = inn_member_states();
	foreach ( $states as $abbrev => $name ) { ?>
		<option value="<?php echo $core_url . "?state=" . $abbrev; ?>" <?php selected( $abbrev, $selected ); ?>><?php echo $name; ?></option>
	<?php }
	print "</select>";
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