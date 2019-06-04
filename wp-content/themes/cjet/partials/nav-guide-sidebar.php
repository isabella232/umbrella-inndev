<?php
/**
 * The Guide Nav Sidebar
 *
 * Expects the following variables to be defined:
 *
 * @param Int|Bool $page_parent_id The parent page of the current Guide
 *
 */

// now get the complete tree of child pages for the guide's top page
if ( is_int( $page_parent_id ) ) {
	$children = wp_list_pages('title_li=&child_of=' . $page_parent_id . '&echo=0');
} else {
	$children = '';
}

/*
 * Commented out until we're sure of what we want to do with attachments
$attachments = get_posts( array(
	'post_type' => 'attachment',
	'posts_per_page' => -1,
	'post_parent' => $page_parent_id,
	'exclude'     => get_post_thumbnail_id( $page_parent_id ), //don't get the featured image
) );
*/
?>
<nav class="guide-nav span3">
	<!-- .btn-navbar is used as the toggle for collapsed navbar content -->
	<div class="container clearfix">
		<a class="btn btn-navbar toggle-nav-bar" title="More">
			<div class="bars">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</div>
		</a>

		<?php if ( ! empty( $children ) ) { ?>
			<?php if ( $top_page ) { ?>
				<h4 class="guide-top">
					<?php esc_html_e( 'In This ' . ucfirst( rtrim( $page_type, 's') ), 'cjet' ); ?>
				</h4>
			<?php } else { ?>
				<h4 class="guide-top">
					<a href="<?php echo esc_attr( get_permalink( $page_parent_id ) ); ?>">
						<?php echo get_the_title( $page_parent_id ); ?>
					</a>
				</h4>
			<?php } ?>
		<?php } ?>

		<ul class="guide-tree">
			<?php echo $children; ?>

			<?php dynamic_sidebar( 'guide-sidebar-below-toc' ); ?>
		</ul>

		<?php
		/*
		 * Commented out until we're sure of what we want to do with attachments
		// on interior guide pages, list resources attached to the parent guide page
		// if ( $attachments ) : ?>
		<!-- <div class="resources">
			<h4><?php _e('Related Resources', 'cjet'); ?></h4>
			<ul class="guide-resources"><?php
				// foreach ( $attachments as $attachment ) {
				// 	//print_r( $attachment );
				// 	$class = "mime-" . sanitize_title( $attachment->post_mime_type );
				// 	echo '<li class="' . $class . ' data-design-thumbnail">';
				// 	echo cjet_format_attachment_link( $attachment->ID );
				// 	echo '</li>';
				// }
			?> -->
			<!-- </ul></div> -->
				<?php
		// endif;	// resources links

		*/
		?>
	</div>
</nav>

