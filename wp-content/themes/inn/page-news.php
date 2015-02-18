<?php
/**
 * Template Name: News
 * Description: Template for the "network news" page, just shows all the posts, more or less
 */
get_header();
?>

<div id="content" class="span8 stories" role="main">
	<h1 class="entry-title">Network News</h1>
	<?php
		$query_args = array (
			'posts_per_page'	=> 10,
			'post_status'		=> 'publish',
			'paged'				=> $paged
		);
		$wp_query = new WP_Query( $query_args );

		if ( $wp_query->have_posts() ) {
			while ( $wp_query->have_posts() ) : $wp_query->the_post();
				get_template_part( 'partials/content', 'archive' );
			endwhile;
			largo_content_nav( 'nav-below' );
		}

		wp_reset_postdata();
	?>
</div><!--#content-->

<?php get_footer();
