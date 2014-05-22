<?php
/**
 * The Template for displaying all single posts.
 */
get_header();
?>

<div>
<div id="content" class="span8" role="main">

	<?php
		while ( have_posts() ) : the_post();
			get_template_part( 'content', 'single' );

			if ( is_active_sidebar( 'article-bottom' ) ) {
				echo '<div class="article-bottom">';
				dynamic_sidebar( 'article-bottom' );
				echo '</div>';
			}

			comments_template( '', true );
		endwhile;
	?>
</div>
</div><!--#content-->

<?php get_footer(); ?>