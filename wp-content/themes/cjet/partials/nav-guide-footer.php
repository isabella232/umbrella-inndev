<?php
/**
 * The previous/next buttons from the nav template.
 *
 * Expected variables:
 *
 * @param Int $page_parent_id the ID of the parent post of this page
 * @param Bool $top_page Whether this page is the top page of a guide, according to business logic
 */
?>
<nav id="nav-below" class="pager post-nav clearfix">
<?php
	$pagelist = get_pages('sort_column=menu_order&sort_order=asc&child_of=' . $page_parent_id );
	$pages = array();
	$pages[] = $page_parent_id;
	$prev_id = $next_id = '';
	foreach ( $pagelist as $page ) {
	   $pages[] += $page->ID;
	}

	$current = array_search(get_the_ID(), $pages);
	if ( array_key_exists($current-1, $pages)) {
		$prev_id = $pages[$current-1];
	}
	if ( array_key_exists($current+1, $pages)) {
		$next_id = $pages[$current+1];
	}

	if ( $top_page ) {
		if ( $page_type == 'courses' ) {
			$link_text = __('Get Started', 'cjet');
		} else {
			$link_text = __('Start Reading', 'cjet');
		}
		printf(
			'<div class="next"><a href="%1$s" rel="next"><i class="dashicons dashicons-arrow-right-alt"></i><span class="meta-nav">%2$s</span></a></div>',
			get_permalink( $next_id ),
			$link_text
		);
	} else {
		if (!empty($prev_id)) {
			printf(
				'<div class="previous"><a href="%1$s" rel="prev"><i class="dashicons dashicons-arrow-left-alt"></i><span class="meta-nav">%2$s</span></a></div>',
				get_permalink( $prev_id ),
				get_the_title($prev_id)
			);
		}

		if (!empty($next_id)) {
			printf(
				'<div class="next"><a href="%1$s" rel="next"><i class="dashicons dashicons-arrow-right-alt"></i><span class="meta-nav">%2$s</span></a></div>',
				get_permalink( $next_id ),
				get_the_title( $next_id )
			);
		}
	}
	?>

</nav><!-- #nav-below -->
