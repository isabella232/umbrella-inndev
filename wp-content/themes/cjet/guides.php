<?php
/**
 * Template Name: Guide Page
 */
get_header();
$top_page = FALSE;
?>

<div id="content" class="row-fluid guide-page" role="main">
	<?php
		while ( have_posts() ) : the_post();

			// get the ID of the main page for a given guide
			$this_page_id = $post->ID;
			$ancestors = get_post_ancestors( $this_page_id );
			$page_type_id = end($ancestors); // the topmost parent is actually the "guides" page so let's back it up one
			$guide_type = get_post( $page_type_id )->post_name;

			if ( count($ancestors) === 1 ) {
				// this is the main page of the guide so we can just list all of its children
				$top_page = TRUE;
				$guide_parent_id = $post->ID;
			} else {
				// it's not and we need to do get the full page tree
				$guide_parent_id = prev($ancestors); // much better
			}

			// now get the complete tree of child pages for the guide's top page
			$children = wp_list_pages('title_li=&child_of=' . $guide_parent_id . '&echo=0');
			$attachments = get_posts( array(
				'post_type' => 'attachment',
				'posts_per_page' => -1,
				'post_parent' => $guide_parent_id,
				'exclude'     => get_post_thumbnail_id( $guide_parent_id ), //don't get the featured image
			) );
			?>

			<nav class="guide-nav span3 navbar">
				<!-- .btn-navbar is used as the toggle for collapsed navbar content -->
				<div class="container clearfix">
		      <a class="btn btn-navbar toggle-nav-bar" title="More">
		        <div class="bars">
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>
		        </div>
		      </a>

					<?php if ( $top_page ) { ?>
						<h4><?php _e('In This ' . ucfirst( rtrim($guide_type, 's') ), 'cjet'); ?></h4>
					<?php } else { ?>
						<h4 class="guide-top"><a href="<?php echo get_permalink($guide_parent_id); ?>"><?php echo get_the_title($guide_parent_id); ?></a></h4>
					<?php } ?>
					<ul class="guide-tree">
						<?php echo $children; ?>
					</ul>

					<?php
					// on interior guide pages, list resources attached to the parent guide page
					if ( $attachments ) : ?>
					<div class="resources">
						<h4><?php _e('Resources', 'cjet'); ?></h4>
						<ul class="guide-resources"><?php
							foreach ( $attachments as $attachment ) {
								//print_r( $attachment );
								$class = "mime-" . sanitize_title( $attachment->post_mime_type );
								echo '<li class="' . $class . ' data-design-thumbnail">';
								echo cjet_format_attachment_link( $attachment->ID );
								echo '</li>';
							}
						?>
						</ul></div>
							<?php
					endif;	// resources links
					?>
				</div>
			</nav>

			<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix span9'); ?>>

				<header class="entry-header">
					<h1 class="entry-title"><?php the_title(); ?></h1>
					<?php edit_post_link(__('Edit This Page', 'largo'), '<h5 class="byline"><span class="edit-link">', '</span></h5>'); ?>
				</header><!-- .entry-header -->

				<div class="entry-content">
					<?php the_content(); ?>

					<?php
					// if we're on a guide "top" page, show author information and whatnot
					// we can leverage Largo's author info widget here

						if ( $guide_type == 'courses' ) {
							$author_label = 'Course Instructor';
						} else {
							$author_label = 'Guide Author';
						}

						if ( $top_page && ( get_post_meta( $post->ID, 'cjet_hide_author', TRUE ) !== '1' ) ) {
							the_widget(
								'largo_author_widget',
								array( 'title' => $author_label )
							);
						}
					?>

					<?php // in-guide navigation ?>
					<nav id="nav-below" class="pager post-nav clearfix">
					<?php
						$pagelist = get_pages('sort_column=menu_order&sort_order=asc&child_of=' . $guide_parent_id );
						$pages = array();
						$pages[] = $guide_parent_id;
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
							printf( '<div class="next"><a href="%1$s"><h5>Start Reading &rarr;</h5></a></div>',
								get_permalink( $next_id ),
								get_the_title( $next_id )
							);
						} else {
							if (!empty($prev_id)) {
								printf( '<div class="previous"><a href="%1$s"><h5>Previous Section</h5><span class="meta-nav">%2$s</span></a></div>',
									get_permalink( $prev_id ),
									get_the_title($prev_id)
								);
							}

							if (!empty($next_id)) {
								printf( '<div class="next"><a href="%1$s"><h5>Next Section</h5><span class="meta-nav">%2$s</span></a></div>',
									get_permalink( $next_id ),
									get_the_title( $next_id )
								);
							}
						}
						?>

					</nav><!-- #nav-below -->
				</div><!-- .entry-content -->

			</article><!-- #post-<?php the_ID(); ?> -->

		<?php endwhile; // end of the loop.
	?>
</div><!--#content-->

<?php get_footer(); ?>