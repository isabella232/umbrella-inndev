<h1 class="entry-title">Network News</h1>
<?php
	global $wp_query;
	$query_args = array (
		'posts_per_page'	=> 10,
		'post_status'		=> 'publish',
	);
	$wp_query = new WP_Query( $query_args );

	wp_localize_script(
		'load-more-posts', 'LMP', array(
			'ajax_url' => admin_url('admin-ajax.php'),
			'paged' => (!empty($wp_query->query_vars['paged']))? $wp_query->query_vars['paged'] : 0,
			'query' => $wp_query->query
		)
	);

	if ( $wp_query->have_posts() ) {
		while ( $wp_query->have_posts() ) : $wp_query->the_post();
			get_template_part( 'partials/content', 'home' );
		endwhile;
		largo_content_nav( 'nav-below' );
	}

	wp_reset_query();
?>

