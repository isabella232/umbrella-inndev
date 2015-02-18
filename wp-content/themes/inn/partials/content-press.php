<?php
	get_template_part( 'partials/content', 'page' );

	global $wp_query;
	// argo links
	$query_args = array (
		'posts_per_page'	=> 10,
		'post_type' 		=> 'argolinks',
		'post_status'		=> 'publish'
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

		echo '<h3>INN in the Press</h3>';

		while ( $wp_query->have_posts() ) : $wp_query->the_post();
			get_template_part( 'partials/content', 'argolinks' );
		endwhile;
		largo_content_nav( 'nav-below' );
	}

	wp_reset_query();
?>
