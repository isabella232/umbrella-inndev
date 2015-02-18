<?php
/**
 * Single Post Template: One Column (Standard Layout)
 * Template Name: One Column (Standard Layout)
 * Description: Shows the post but does not load any sidebars.
 */
add_filter( 'body_class', function( $classes ) {
	$classes[] = 'normal';
	return $classes;
} );

get_header();

$about_pg_id = 2212;
$members_pg_id = 234260;
$main_span = 'span8';

//is this a page or a post in the projects post type
if ( is_page() || is_singular( 'pauinn_project' ) ) {

	//if this is the about page or a child, get that page tree
	if ( is_page( $about_pg_id ) || $about_pg_id == $post->post_parent ) {
		$main_span = 'span10';
		echo '<div class="internal-subnav span2">';
		echo 'about';
		echo '</div>';

	//else if this is the for members page or a child
	} else if ( is_page( $members_pg_id ) || $members_pg_id == $post->post_parent ) {
		$main_span = 'span10';
		echo '<div class="internal-subnav span2">';
		echo 'member';
		echo '</div>';

	//or a project page
	} else if ( is_singular( 'pauinn_project' ) ) {
		$main_span = 'span10';
		echo '<div class="internal-subnav span2">';
		echo '<h3>Programs</h3>';
		$terms = get_terms( 'pauinn_project_tax', array( 'hide_empty' => false ) );

		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
		    echo '<ul>';
		    foreach ( $terms as $term ) {
			    $term_link = '/project/' . $term->slug;
		    	echo '<li><a href="' . $term_link . '">' . $term->name . '</a></li>';
		    }
		    echo '</ul>';
		}
		echo '</div>';
	}
}
?>

<div id="content" class="<?php echo $main_span; ?>" role="main">
	<?php
		while ( have_posts() ) : the_post();

			$partial = ( is_page() ) ? 'page' : 'single';

			if ( is_singular( 'pauinn_project' ) ) {

				get_template_part( 'partials/content', 'page' );

				get_template_part( 'partials/content', 'projects' );

			} else if ( $partial === 'single' ) {

				get_template_part( 'partials/content', $partial );

				if ( is_active_sidebar( 'article-bottom' ) ) {

					do_action( 'largo_before_post_bottom_widget_area' );

					echo '<div class="article-bottom">';
					dynamic_sidebar( 'article-bottom' );
					echo '</div>';

					do_action( 'largo_after_post_bottom_widget_area' );

				}

				do_action(' largo_before_comments' );

				comments_template( '', true );

				do_action( 'largo_after_comments' );

			} else {

				get_template_part( 'partials/content', $partial );

			}

		endwhile;
	?>
</div>

<?php do_action( 'largo_after_content' ); ?>

<?php get_footer();
