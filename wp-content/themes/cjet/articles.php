<?php
/**
 * Template Name: Article Page
 */
get_header();
$top_page = FALSE;
?>

<div id="content" class="row-fluid guide-page" role="main">
	<?php
		while ( have_posts() ) : the_post();

			// get the ID of the main page for a given article
			$this_page_id = $post->ID;
			$ancestors = get_post_ancestors( $this_page_id );

			if ( count( $ancestors ) < 1 ) {
				// this is the main page of the article so we can just list all of its children
				$top_page = TRUE;
				$page_parent_id = $post->ID;
			} else {
				// it's not and we need to do get the full page tree
				// https://developer.wordpress.org/reference/functions/get_post_ancestors/ returns post IDs in order from child to parent
				$page_parent_id = end($ancestors);
			}

			?>

			<?php
				include( locate_template( 'partials/nav-guide-sidebar.php' ) );
			?>

			<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix span9'); ?>>

				<header class="entry-header">
					<h1 class="entry-title"><?php the_title(); ?></h1>
					<?php edit_post_link(__('Edit This Page', 'largo'), '<h5 class="byline"><span class="edit-link">', '</span></h5>'); ?>
				</header><!-- .entry-header -->

				<div class="entry-content">
					<?php the_content(); ?>

					<?php
					// if we're on a article "top" page, show author information and whatnot
					// we can leverage Largo's author info widget here

						if ( $top_page ) {
							$author_label = '<h3 class="widgettitle guide-author">' . __( 'Article Author', 'cjet' ) . '</h3>';
						}

						if ( $top_page && ( get_post_meta( $post->ID, 'cjet_hide_author', TRUE ) !== '1' ) ) {
							the_widget( 'largo_author_widget' );
						}
					?>

				</div><!-- .entry-content -->

			</article><!-- #post-<?php the_ID(); ?> -->

		<?php endwhile; // end of the loop.
	?>
</div><!--#content-->

<?php get_footer(); ?>
