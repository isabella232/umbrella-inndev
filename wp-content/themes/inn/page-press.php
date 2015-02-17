<?php
/**
 * Template Name: Press
 * Description: Template for the "press" pages, shows the page content plus posts from argolinks
 */
get_header();
?>

<div id="content" class="span8" role="main">
	<?php

		// regular page output
		while ( have_posts() ) : the_post();
			get_template_part( 'partials/content', 'page' );
		endwhile;

		// argo links
		$query_args = array (
			'posts_per_page'	=> 10,
			'post_type' 		=> 'argolinks',
			'post_status'		=> 'publish'
		);
		$my_query = new WP_Query( $query_args );

		if ( $my_query->have_posts() ) {

			echo '<h3>INN in the Press</h3>';

			while ( $my_query->have_posts() ) : $my_query->the_post();
				get_template_part( 'partials/content', 'argolinks' );
			endwhile;

			largo_content_nav( 'nav-below' );

		}

		wp_reset_postdata();

	?>
</div><!--#content-->

<?php get_footer();
