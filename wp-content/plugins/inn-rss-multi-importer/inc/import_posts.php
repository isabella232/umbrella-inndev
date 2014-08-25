<?php
$feedID = (isset( $_GET[ 'rssmi_feedID' ] ) ? $_GET['rssmi_feedID'] : NULL);
$catID = (isset( $_GET[ 'rssmi_catID' ] ) ? $_GET['rssmi_catID'] :  NULL);

if ( !IS_NULL($feedID) || !IS_NULL($catID) ) {
	if( !class_exists('SimplePie') ){
		require_once(ABSPATH . WPINC . '/class-simplepie.php');
	}

	class SimplePie_RSSMI extends SimplePie {}

	$post_options = get_option('rss_post_options');

	if ( $post_options['active'] == 1 ) {
		if ( !IS_NULL($feedID) ) {
			$result = wp_rss_multi_importer_post($feedID);  /// Used for external cron jobs
		} else {
			$result = wp_rss_multi_importer_post($catID);
		}

		if ( $result == TRUE ){
			echo "success";
		}
		die();
	}
}



/**
 * Delete meta keys indicating the source
 * CShop: appears to be deprecated, functionality is commented out...
 */
function deleteArticles(){

	global $wpdb;

  $mypostids = $wpdb->get_results("select * from $wpdb->postmeta where meta_key LIKE '%rssmi_source_link%");
  foreach( $mypostids as $mypost ) {
		//	delete_post_meta($mypost->ID, 'rssmi_source_link');
  }
}

/**
 * Set the featured image for an imported post?
 */
function setFeaturedImage( $post_id, $url, $featuredImageTitle ) {
  // Download file to temp location and setup a fake $_FILE handler
  // with a new name based on the post_id
  $tmp_name = download_url( $url );
	//echo $tmp_name;
  $file_array['name'] = $post_id. '-thumb.jpg';  // new filename based on slug
  $file_array['tmp_name'] = $tmp_name;

  // If error storing temporarily, unlink
  if ( is_wp_error( $tmp_name ) ) {
		@unlink($file_array['tmp_name']);
		$file_array['tmp_name'] = '';
  }

  // do validation and storage .  Make a description based on the Post_ID
  $attachment_id = media_handle_sideload( $file_array, $post_id, 'Thumbnail for ' .$post_id);

  // If error storing permanently, unlink
  if ( is_wp_error($attachment_id) ) {
		$error_string = $attachment_id->get_error_message();
    @unlink($file_array['tmp_name']);
    return;
  }

  // Set as the post attachment
 $post_result= add_post_meta( $post_id, '_thumbnail_id', $attachment_id, true );
 //echo $post_result);
}

/**
 * Runs to convert feed items to posts
 */
function rssmi_import_feed_post() {

	$post_options = get_option('rss_post_options');

	if ( $post_options['active'] == 1 ) {
		wp_rss_multi_importer_post();
	}
}

/**
 * RSS ajax callback... wp_ajax_fetch_now seems to be a custom action?
 */
add_action('wp_ajax_fetch_now', 'fetch_rss_callback');

function fetch_rss_callback() {

	$post_options = get_option('rss_post_options');

	if ( $post_options['active'] == 1 ) {

		$result = wp_rss_multi_importer_post();

		// apparently 3 is a an error code?
		if ( $result === 3 ) {
			echo '<h3>There was a problem with fetching feeds.  This is likely due to a settings problem or invalid feeds.</h3>';
		} elseif ( $result === 4 ) {
			echo '<h3>There were no new feed items to add.</h3>';
		} else {
			echo '<h3>The most recent feeds have been put into posts.</h3>';
		}

	} else {
		echo '<h3>Nothing was done because you have not activated this service.</h3>';
	}

	die();
}

/**
 * ?
 */
function rssmi_delete_feed_post_admin() {
	rssmi_delete_posts_admin();
}


/**
 * ?
 */
add_action('wp_ajax_fetch_delete', 'fetch_rss_callback_delete');
function fetch_rss_callback_delete() {
	rssmi_delete_feed_post_admin();
}

/**
 * ?
 */
function filter_id_callback2( $val ) {
	if ($val != null && $val !=99999) {
		return true;
	}
}

function filter_id_callback( $val ) {
	foreach ( $val as $thisval ) {
    if ($thisval != null) {
			return true;
		}
	}
}

/**
 * ?
 */
function get_values_for_id_keys( $mapping, $keys ) {
	foreach($keys as $key) {
		$output_arr[] = $mapping[$key];
  }
  return $output_arr;
}

/**
 * strip querystring stuff out of a url or something?
 */
function strip_qs_var( $sourcestr, $url, $key ) {
	if ( strpos($url,$sourcestr) > 0 ) {
		return preg_replace( '/('.$key.'=.*?)&/', '', $url );
	} else {
		return $url;
	}
}

/**
 * Adds/removes a title filter
 * Why is this floating in space?
 */
$post_filter_options = get_option('rss_post_options');   // make title of post on listing page clickable
if( isset( $post_filter_options['titleFilter'] ) && $post_filter_options['titleFilter'] == 1 ) {
	add_filter( 'the_title', 'ta_modified_post_title' );
} else {
	remove_filter( 'the_title', 'ta_modified_post_title' );
}

/**
 * Filter post title (optionally, see above)
 */
function ta_modified_post_title( $title ) {
	$post_options = get_option('rss_post_options');
	$targetWindow = $post_options['targetWindow'];
	if( $targetWindow == 0 ) {
		$openWindow='class="colorbox"';
	} elseif ( $targetWindow == 1 ) {
		$openWindow='target=_self';
	} else {
		$openWindow='target=_blank ';
	}

  if ( in_the_loop() && !is_page() ) {
		global $wp_query;
		$postID = $wp_query->post->ID;
		$myLink = get_post_meta( $postID, 'rssmi_source_link' , true );
		if ( !empty( $myLink ) ) {
			$myTitle = $wp_query->post->post_title;
			$myLinkTitle = '<a href=' . $myLink . ' ' . $openWindow . '>' . $myTitle . '</a>';  // change how the link opens here
			return $myLinkTitle;
		}
  }
  return $title;
}

/**
 * Returns an array of all the categories posts can be classified by?
 */
function isAllCat() {
	$post_options = get_option('rss_post_options');
	$catSize= count( $post_options['categoryid'] );

	for ( $l=1; $l <= $catSize; $l++ ) {
		if( $post_options['categoryid']['plugcatid'][$l] == 0 ) {
			$allCats[] = $post_options['categoryid']['wpcatid'][$l];
		}
	}
	return $allCats;
}

/**
 * gets all wp category IDs as an array
 */
function getAllWPCats() {
	$category_ids = get_all_category_ids();
	foreach( $category_ids as $cat_id ) {
		if ( $cat_id == 1 ) continue;
 		$getAllWPCats[] = $cat_id;
	}
	return $getAllWPCats;
}

/**
 * Import posts? a single post? Something....
 * This is the main function here.
 */
function wp_rss_multi_importer_post( $feedID = NULL, $catID = NULL ) {

	$postMsg = FALSE;

	require_once( ABSPATH . "wp-admin" . '/includes/media.php' );
	require_once( ABSPATH . "wp-admin" . '/includes/file.php'  );
	require_once( ABSPATH . "wp-admin" . '/includes/image.php' );

	if( !function_exists( "wprssmi_hourly_feed" ) ) {
		function wprssmi_hourly_feed() { return 0; }  // no caching of RSS feed
	}

	add_filter( 'wp_feed_cache_transient_lifetime', 'wprssmi_hourly_feed' );

  $options = get_option('rss_import_options','option not found');
	$option_items = get_option('rss_import_items','option not found');
	$post_options = get_option('rss_post_options', 'option not found');
	$category_tags = get_option('rss_import_categories_images', 'option not found');

	global $fopenIsSet;
	$fopenIsSet = ini_get('allow_url_fopen');

	if ( $option_items == false ) return 3;

	if ( !empty( $option_items ) ) {
		$cat_array = preg_grep("^feed_cat_^", array_keys($option_items) );

		if ( count($cat_array) == 0 ) {  // for backward compatibility
			$noExistCat = 1;
		} else {
			$noExistCat = 0;
		}
	}

	if ( !IS_NULL($feedID) ) {
		$feedIDArray = explode( ",", $feedID );
	}

  if ( !empty( $option_items ) ) {

		global $setFeaturedImage, $RSSdefaultImage, $morestyle, $ftp, $anyimage, $maximgwidth;

		//GET PARAMETERS
		$size              = count( $option_items );
		$sortDir           = 0;  // 1 is ascending
		$maxperPage        = ( isset( $options['maxperPage'] ) ? $options['maxperPage'] : 5 );
		$setFeaturedImage  = $post_options['setFeaturedImage'];
		$addSource         = ( isset( $post_options['addSource'] ) ? $post_options['addSource'] : null );
		$sourceAnchorText  = $post_options['sourceAnchorText'];
		$maxposts          = $post_options['maxfeed'];
		$post_status       = $post_options['post_status'];
		$addAuthor         = ( isset( $post_options['addAuthor'] ) ? $post_options['addAuthor'] : null );
		$bloguserid        = $post_options['bloguserid'];
		$post_format       = $post_options['post_format'];
		$postTags          = ( isset( $post_options['postTags'] ) ? $post_options['postTags'] : null );
		$RSSdefaultImage   = $post_options['RSSdefaultImage'];   // 0- process normally, 1=use default for category, 2=replace when no image available
		$serverTimezone    = $post_options['timezone'];
		$autoDelete        = ( isset( $post_options['autoDelete'] ) ? $post_options['autoDelete'] : null );
		$sourceWords       = $post_options['sourceWords'];
		$readMore          = $post_options['readmore'];
		$showVideo         = ( isset( $post_options['showVideo'] ) ? $post_options['showVideo'] : null );
		$includeExcerpt    = ( isset( $post_options['includeExcerpt'] ) ? $post_options['includeExcerpt'] : null );
		$addRSSCategories  = ( isset( $post_options['addRSSCategories'] ) ? $post_options['addRSSCategories'] : null );
		$morestyle         = ' ...read more';
		$sourceWords_Label = $post_options['sourceWords_Label'];

		// primitive override of $morestyle
		if ( !is_null($readMore) && $readMore != '' ) {
			$morestyle = $readMore;
		}

		// source prefix text
		switch ($sourceWords) {
	    case 1:
	        $sourceLable = 'Source';
	        break;
	    case 2:
	        $sourceLable = 'Via';
	        break;
	    case 3:
	        $sourceLable = 'Read more here';
	        break;
			case 4:
			    $sourceLable = 'From';
			    break;
			case 5:
				$sourceLable = $sourceWords_Label;
				break;
		  default:
		    $sourceLable = 'Source';
		}

		// get current date, optionally adjusted for timezone
		if ( isset( $serverTimezone ) && $serverTimezone != '' ) {
			date_default_timezone_set( $serverTimezone );
			$rightNow = date("Y-m-d H:i:s", time() );
		} else {
			$rightNow = date("Y-m-d H:i:s", time() );
		}

		// get category ids to assign post to?
		if ( $post_options['categoryid']['wpcatid'][1] !== null ) {
			$wpcatids = array_filter( $post_options['categoryid']['wpcatid'], 'filter_id_callback' ); //array of post blog categories that have been entered
		}

		// ???
		if ( !empty( $wpcatids ) ) {
			$catArray = get_values_for_id_keys( $post_options['categoryid']['plugcatid'], array_keys( $wpcatids ) );  //array of plugin categories that have an association with post blog categories
			$catArray = array_diff( $catArray, array('') );
		} else {
			$catArray = array(0);
		}

		// change to provided category ID if using external CRON
		if( !IS_NULL($catID) ) {
			$catArray=array($catID);
		}

		$targetWindow = $post_options['targetWindow'];  // 0=LB, 1=same, 2=new

		// set an attribution prefix string
		if( empty( $options['sourcename'] ) ) {
			$attribution = '';
		} else {
			$attribution = $options['sourcename'] . ': ';
		}

		$ftp = 1;  //identify pass to excerpt_functions comes from feed to post
		$anyimage = 1;  // to identify any image in description

		$maximgwidth    = $post_options['maximgwidth'];;
		$descNum        = $post_options['descnum'];
		$stripAll       = $post_options['stripAll'];
		$maxperfetch    = $post_options['maxperfetch'];
		$showsocial     = ( isset( $post_options['showsocial'] ) ? $post_options['showsocial'] : null );
		$overridedate   = ( isset( $post_options['overridedate'] ) ? $post_options['overridedate'] : null );
		$commentStatus  = ( isset( $post_options['commentstatus'] ) ? $post_options['commentstatus']: null );
		$noFollow       = ( isset( $post_options['noFollow'] ) ? $post_options['noFollow'] : 0 );
		$floatType      = ( isset( $post_options['floatType'] ) ? $post_options['floatType'] : 0 );
		$stripSome      = ( isset( $post_options['stripSome'] ) ? $post_options['stripSome'] : null );

		// set the comment status
		if ( $commentStatus == '1' ) {
			$comment_status='closed';
		} else {
			$comment_status='open';
		}

		$adjustImageSize = 1;

		// set the image float
		if ( $floatType == '1' ) {
			$float = "left";
		} else {
			$float = "none";
		}

		/**
		 * We've finished setting up all our variables, now let's loop thru everything else passed in, looking to build $myfeeds array
		 */
		for ( $i=1; $i <= $size; $i++ ) {

			//  condition here that id number is here
			$key = key( $option_items );

			if ( !strpos( $key, '_' ) > 0 ) continue; //this makes sure only feeds are included here...everything else are options themselves

   		$rssName = $option_items[$key];

			$rssID = str_replace( 'feed_name_', '', $key );  //get feed ID number

			// increment as url is next field after name, apparently
			next( $option_items );
   		$key = key( $option_items );
   		$rssURL = $option_items[ $key ];

   		// increment as category/ies is next field after URL
	 		next( $option_items );
	 		$key = key( $option_items );
	 		$rssCatID = $option_items[$key];

	 		//makes sure only desired categories are included
	 		if ( ( ( !in_array( 0, $catArray ) && in_array( $option_items[$key], $catArray ) ) )
	 			|| in_array( 0, $catArray )
	 			|| $noExistCat == 1
	 			|| !empty($feedIDArray)) {

		 			if ( !empty( $feedIDArray ) ) {	//only pick up specific feed arrary if indicated in querystring [?]
			 			if ( !in_array( $rssID, $feedIDArray ) ) {
				 			if ( count( $cat_array ) > 0 ) { // for backward compatibility [?]
					 			next( $option_items ); //skip feed category
					 		}
					 		continue;
					 	}
					}

					// ADD THIS FEED
					$myfeeds[] = array( "FeedName" => $rssName, "FeedURL" => $rssURL, "FeedCatID" => $rssCatID ); //with Feed Category ID
			} // end category check

			$cat_array = preg_grep( "^feed_cat_^", array_keys($option_items) );  // for backward compatibility
			if ( count( $cat_array ) > 0 ) {
				next($option_items); //skip feed category
			}
		} // end looping thru passed-in stuff to get our feeds list

		/**
		 * WE HAVE OUR FEEDS LIST!!!!!!
		 * Make sure necessary limit was set and valid feeds were found
		 */
	  if ( $maxposts == "" || empty( $myfeeds ) ) return 3;  // check to confirm they set options

		//configure $targetWindow
		if( $targetWindow == 0 ) {
			$openWindow = 'class="colorbox"';
		} elseif ( $targetWindow == 1 ) {
			$openWindow = 'target="_self"';
		} else {
			$openWindow = 'target="_blank"';
		}

		// some other settings
		$directFetch = 1;
		$timeout = ( isset( $timeout ) ? $timeout : 10 );
		$forceFeed = 1;

		// LOOP THRU FEEDS WE FOUND!
		foreach ( $myfeeds as $feeditem ) {

			// get the feed URL
			$url = (string)$feeditem["FeedURL"];

			// loop thru URL to get it to conform to some standard, trimming off the front... [because why?]
			while ( stristr($url, 'http') != $url ) $url = substr($url, 1);

			// if we somehow destroyed URL with the above, give up
			if ( empty( $url ) ) continue;

			// final URL cleanup
			$url = esc_url_raw(strip_tags($url));

			// FETCH THE FEED!
			if ( $directFetch == 1 ) {
				$feed = wp_rss_fetchFeed( $url, $timeout, $forceFeed, $showVideo );
			} else {
				$feed = fetch_feed( $url );
			}

			if ( is_wp_error( $feed ) ) {
				if ( $size < 4 ) {
					return 3;
					exit; //um....
				} else {
					//echo $feed->get_error_message();
					continue; //skip this one
				}
			}

			// # of articles?
			$maxfeed = $feed->get_item_quantity(0);

			if ( $feedAuthor = $feed->get_author() ) {
				$feedAuthor=$feed->get_author()->get_name();
			}

			if ( $feedHomePage = $feed->get_link() ) {
				$feedHomePage=$feed->get_link();
			}


			// LOOP THRU ITEMS, ORDER DEPENDS ON SETTINGS
			// (this is idiotic)
			if ( $sortDir == 1 ) {

				for ( $i = $maxfeed - 1 ; $i >= $maxfeed - $maxposts; $i-- ) {

					$item = $feed->get_item($i);
					if (empty($item))	continue;
					$thisTitle = html_entity_decode( $item->get_title(), ENT_QUOTES, 'UTF-8' );

					if( include_post( $feeditem["FeedCatID"], $item->get_content(), $thisTitle ) == 0 ) continue;   // FILTER

					// set mediaImage to the thumbnail or link
					if ( $enclosure = $item->get_enclosure() ) {
						if( !is_null( $item->get_enclosure()->get_thumbnail() ) ) {
							$mediaImage = $item->get_enclosure()->get_thumbnail();
						} else if ( !is_null( $item->get_enclosure()->get_link() ) ) {
							$mediaImage = $item->get_enclosure()->get_link();
						}
					}

					// set itemAuthor to the get_author or fed author values
					if ( $itemAuthor = $item->get_author() ) {
						$itemAuthor = ( !is_null($item->get_author()->get_name() ) ? $item->get_author()->get_name() : $item->get_author()->get_email() );
						$itemAuthor = html_entity_decode($itemAuthor, ENT_QUOTES, 'UTF-8' );
					} else if ( !is_null($feedAuthor) ) {
						$itemAuthor = $feedAuthor;
						$itemAuthor = html_entity_decode($itemAuthor, ENT_QUOTES, 'UTF-8' );
					}

					// build a new item in "myarray" that contains the relevant information for creating a post
					$myarray[] = array(
						"mystrdate"     => strtotime($item->get_date()),
						"mytitle"       => $thisTitle,
						"mylink"        => $item->get_permalink(),
						"myGroup"       => $feeditem["FeedName"],
						"mydesc"        => $item->get_content(),
						"myimage"       => $mediaImage,
						"mycatid"       => $feeditem["FeedCatID"],
						"myAuthor"      => $itemAuthor,
						"feedURL"       => $feeditem["FeedURL"],
						"feedHomePage"  => $feedHomePage,
						"itemcategory"  => $item->get_category()
					);

					unset($mediaImage);
					unset($itemAuthor);
				}

			} else {	// $sortDir != 1....

				for ( $i=0; $i <= $maxposts - 1; $i++ ) {

					$item = $feed->get_item( $i );
					if ( empty( $item ) )	continue;
					$thisTitle = html_entity_decode( $item->get_title(), ENT_QUOTES, 'UTF-8' );

					if ( !is_null( $item->get_categories() ) ) {
						$categoryTerms = "";
						foreach ( $item->get_categories() as $category ) {
				    	$categoryTerms .= $category->get_term() . ', ';
				    }
						$postCategories = rtrim( $categoryTerms, ', ' );
					} else {
						$postCategories = NULL;
					}

					if( include_post( $feeditem["FeedCatID"], $item->get_content(), $thisTitle ) == 0 ) continue; // FILTER

					// set mediaImage to the thumbnail or link
					if ( $enclosure = $item->get_enclosure() ) {
						if( !is_null( $item->get_enclosure()->get_thumbnail() ) ) {
							$mediaImage=$item->get_enclosure()->get_thumbnail();
						} else if ( !is_null($item->get_enclosure()->get_link() ) ) {
							$mediaImage=$item->get_enclosure()->get_link();
						}
						$mediaImage = ( isset($mediaImage) ? $mediaImage : null );
					}

					if ( $itemAuthor = $item->get_author() ) {
						$itemAuthor = ( !is_null($item->get_author()->get_name() ) ? $item->get_author()->get_name() : $item->get_author()->get_email());
						$itemAuthor = html_entity_decode( $itemAuthor, ENT_QUOTES, 'UTF-8' );
					} else if ( !is_null( $feedAuthor ) ) {
						$itemAuthor = $feedAuthor;
						$itemAuthor = html_entity_decode( $itemAuthor, ENT_QUOTES, 'UTF-8' );
					}

					$myarray[] = array(
						"mystrdate"     => strtotime( $item->get_date() ),
						"mytitle"       => $thisTitle,
						"mylink"        => $item->get_permalink(),
						"myGroup"       => $feeditem["FeedName"],
						"mydesc"        => $item->get_content(),
						"myimage"       => $mediaImage,
						"mycatid"       => $feeditem["FeedCatID"],
						"myAuthor"      => $itemAuthor,
						"feedURL"       => $feeditem["FeedURL"],
						"itemcategory"  => $postCategories
					);

					unset($postCategories);
					unset($mediaImage);
					unset($itemAuthor);
				} // looping thru posts

			} // end $sortDir question

		} // end looping thru $myfeeds

		/**
		 * At this point everything in all the feeds has been shoved into $myarray....
		 */

		//  CHECK $myarray BEFORE DOING ANYTHING ELSE //
		if ( isset( $dumpthis ) && $dumpthis == 1 ) {
			var_dump($myarray);
		}
		if ( !isset($myarray) || empty($myarray) ) return 3;


		// build an array of dates from $myarray to use for sorting
		foreach ($myarray as $key => $row) {
    	$dates[$key]  = $row["mystrdate"];
		}

		//SORT, DEPENDING ON SETTINGS
		// why there's a test for this above as well, I have no clue
		if( $sortDir == 1 ) {
			array_multisort( $dates, SORT_ASC, $myarray );
		} else {
			array_multisort( $dates, SORT_DESC, $myarray );
		}

		// init counters
		$total = 0;
		$added = 0;

		global $wpdb; // get all links that have been previously processed
		$wpdb->show_errors = true;

		// Now we loop thru $myarray and setup individual posts...
		foreach($myarray as $items) {
			$total++;
			if ( $total > $maxperfetch ) break;
			$thisLink  = trim( $items["mylink"] );
			$thisTitle = trim( $items["mytitle"] );
			$orig_video_link = $items["mylink"];

			// VIDEO CHECK
			if ( $targetWindow == 0 || $showVideo == 1 ) {
				$vitem            = $items["mylink"];
				$getVideoArray    = rssmi_video( $items["mylink"], $targetWindow );
				$openWindow       = $getVideoArray[1];
				$items["mylink"]  = $getVideoArray[0];
				$vt               = $getVideoArray[2];
			}

			$thisLink = strip_qs_var( 'bing.com', $thisLink, 'tid' );  // clean time-based links from Bing
			$thisLink = esc_url( $thisLink );

			$wpdb->flush();

			//  CHECK THAT THIS LINK HAS NOT ALREADY BEEN IMPORTED
			$postQuery = $wpdb->prepare(
			  "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'rssmi_source_link' and meta_value LIKE %s",
			  "%" . $thisLink . "%"
			);
			$mypostids=$wpdb->get_col( $postQuery );

			if ( !is_array( $mypostids ) ) {
				echo "Bad database connection";  //  Check to make sure the database was queried
			}

			// if this is new, add it
			if ( ( empty( $mypostids ) && $mypostids !== false) ) {
				$added++;
				$thisContent = '';
  			$post = array();
  			$post['post_status'] = $post_status;
				if ( $overridedate == 1 ) {
					$post['post_date'] = $rightNow;
				} else {
					$post['post_date'] = date( 'Y-m-d H:i:s', $items['mystrdate'] );
				}

				$post['post_title'] = $thisTitle;

				$authorPrep="By ";

				if( !empty( $items["myAuthor"] ) && $addAuthor == 1 ) {
					$thisContent .=  '<span style="font-style:italic; font-size:16px;">'.$authorPrep.' <a '.$openWindow.' href="'.$items["mylink"].'"" '.($noFollow==1 ? 'rel=nofollow':'').'">'.$items["myAuthor"].'</a></span>  ';
				}

				// build the excerpt
				$items["feedHomePage"] = isset($items["feedHomePage"]) ? $items["feedHomePage"] : null;
				$thisExcerpt = showexcerpt(
					$items["mydesc"],
					$descNum,
					$openWindow,
					$stripAll,
					$items["mylink"],
					$adjustImageSize,
					$float,
					$noFollow,
					$items["myimage"],
					$items["mycatid"],
					$stripSome,
					$items["feedHomePage"]
				);

				// handle videos from YT/Vimeo in excerpt
				if ( (strpos( $items["mylink"], 'www.youtube.com' ) > 0 || strpos($items["mylink"],'player.vimeo') > 0 )
					&& $showVideo == 1 ) {

					if ( $vt == 'yt' ) {
						$thisExcerpt = rssmi_yt_video_content( $items["mydesc"] ) . "<br>";
					} else if ( $vt == 'vm' ) {
						$thisExcerpt = rssmi_vimeo_video_content( $items["mydesc"] ) . "<br>";
					}

					$thisExcerpt .= '<iframe class="rss_multi_frame" title=".$items["mytitle"]." width="420" height="315" src="'.$items["mylink"].'" frameborder="0" allowfullscreen allowTransparency="true"></iframe>';
				}

				// content begins with excerpt...
				$thisContent .= $thisExcerpt;

				// optionally add source to content
				if ( $addSource == 1) {
					switch ($sourceAnchorText) {
						case 1:
					  	$anchorText=$items["myGroup"];
					    break;
					  case 2:
					  	$anchorText=$items["mytitle"];
					    break;
					  case 3:
					  	$anchorText=$items["mylink"];
					    break;
					  default:
					  	$anchorText=$items["myGroup"];
					}

					$thisContent .= ' <p>'.$sourceLable.': <a href='.$items["mylink"].'  '.$openWindow.'  title="'.$items["mytitle"].'" '.($noFollow==1 ? 'rel=nofollow':'').'>'.$anchorText.'</a></p>';
				}

				// optionally add categories to content
				if( !empty( $items["itemcategory"] )  && $addRSSCategories == 1 ) {
					$thisContent .= ' <p>Category: <a href='.$items["mylink"].'  '.$openWindow.'  title="'.$items["mytitle"].'" '.($noFollow==1 ? 'rel=nofollow':'').'>'.$items["itemcategory"].'</a></p>';
				}

				// optionally add sharing links to content
				if ( $showsocial == 1 ) {
					$thisContent .= '<span style="margin-left:10px;"><a href="http://www.facebook.com/sharer/sharer.php?u='.$items["mylink"].'"><img src="'.WP_RSS_MULTI_IMAGES.'facebook.png"/></a>&nbsp;&nbsp;<a href="http://twitter.com/intent/tweet?text='.rawurlencode($items["mytitle"]).'%20'.$items["mylink"].'"><img src="'.WP_RSS_MULTI_IMAGES.'twitter.png"/></a>&nbsp;&nbsp;<a href="http://plus.google.com/share?url='.rawurlencode($items["mylink"]).'"><img src="'.WP_RSS_MULTI_IMAGES.'gplus.png"/></a></span>';
				}

				$post['post_content'] = $thisContent;
				if ($includeExcerpt == 1 ) {
					$post['post_excerpt'] = $thisExcerpt;
				}

				$mycatid = $items["mycatid"];
				$blogcatid = array();
				if ( !empty( $post_options['categoryid'] ) ) {
					$catkey = array_search( $mycatid, $post_options['categoryid']['plugcatid'] );
					$blogcatid = $post_options['categoryid']['wpcatid'][$catkey];
				} else {
					$blogcatid = 0;
				}

				//this gets all the wp categories indicated when All is chosen in the first position
				if ( $post_options['categoryid']['plugcatid'][1] == '0' ) {
					$allblogcatid=$post_options['categoryid']['wpcatid'][1];
					if ( is_array( $blogcatid ) ) {
						$blogcatid = array_merge ( $blogcatid, $allblogcatid );
						$blogcatid = array_unique( $blogcatid );
					} else {
						$blogcatid = $allblogcatid;
					}
				}

				$post['post_category'] =$blogcatid;
				if ( is_null( $bloguserid ) || empty( $bloguserid ) ) {
					$bloguserid = 1;
				}  //check that userid isn't empty else give it admin status

				$post['post_author'] = $bloguserid;
				$post['comment_status'] = $comment_status;

				if ( !empty( $category_tags[$mycatid]['tags'] ) ) {
					$postTags = $category_tags[$mycatid]['tags'];
				}

				if( $postTags != '' ) {
					$post['tags_input'] =$postTags;
				}

				if ( $showVideo == 1 ) {
					global $allowedposttags;
					$allowedposttags['iframe'] = array( 'src' => array() );
				}

				// Cornershop work: allow for customizing post type and other modifications
			 	// try to get the user associated with this feed
			 	$member = false;
			 	$members = new WP_User_Query( array(
			 		'meta_query' => array(
			 			array(
			 				'key' => 'rss_url',	//temporary
			 				'value' => $feeditem["FeedURL"],
			 				'compare' => '='
			 			)
			 		)
			 	) );

			 	if ( !empty($members->results) ) {
				 	$member = $members->results[0];
				}
				if ( $member ) {
					$post['post_author'] = $member->ID;
				}

				// set post type
				$post['post_type'] = 'network_content';

				// insert post
			 	$post_id = wp_insert_post($post);

			 	// if we have an author, try to add that into 'largo_byline_text'

			 	// add the URL of the feed as a meta, just in case
			 	add_post_meta( $post_id, 'feed_url', $feeditem["FeedURL"]);

			 	// add the from_member_id, just in case
			 	if ( $member ) {
				 	add_post_meta( $post_id, 'from_member_id', $member->ID);
			 	}

				if( add_post_meta( $post_id, 'rssmi_source_link', $thisLink ) != false ) {
					if ( $setFeaturedImage == 1 || $setFeaturedImage == 2 ) {
						global $featuredImage;
						if ( isset( $featuredImage ) ) {
							//facebook correction
							if ( strpos( $featuredImage, "fbcdn" ) > 0 ) {
								if ( strpos( $featuredImage, ".png" ) > 0 ) {
									$fb_feature_img = str_replace( '_s.png', '_n.png', $featuredImage );
								} else {
									$fb_feature_img = str_replace( '_s.jpg', '_n.jpg', $featuredImage );
								}
								if ( rssmi_remoteFileExists( $fb_feature_img ) ) {
									$featuredImage = str_replace($featuredImage, $fb_feature_img, $featuredImage);
								}
							}
							$featuredImageTitle = $thisTitle;
							setFeaturedImage( $post_id, $featuredImage, $featuredImageTitle );
							unset( $featuredImage );
						}
					}
				} else {
					wp_delete_post($post_id, true);
					unset($post);
					unset($myposttitle);
					unset($mypostids);
					continue;
				}

				unset($post);
				unset($myposttitle);
				unset($mypostids);
			}  // closing from line 684
		} // end foreach myarray[]

		if ( $added == 0 ) return 4;
		$postMsg = TRUE;
	} // end !empty $option_items

	if ( $autoDelete == 1 ){
		rssmi_delete_posts();
	}

	return $postMsg;

} // end wp_rss_multi_importer_post()