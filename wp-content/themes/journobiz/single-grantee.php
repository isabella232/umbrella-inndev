<?php
/**
 * The Template for displaying all single posts.
 */
get_header();
$details = get_post_meta( get_the_ID(), 'grantee_details', true );
?>
<div>
<div id="content" class="span12" role="main">
	<?php
		while ( have_posts() ) : the_post();
		global $post;
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'hnews item' ); ?> itemscope itemtype="http://schema.org/Article">
		<header>
			<div class="img-container"><?php the_post_thumbnail('full'); ?></div>
			<div class="info span5">
	 			<h1 class="entry-title" itemprop="headline"><?php the_title(); ?></h1>
	 			<h5 class="top-tag"><?php echo $details['org-name']; ?></h5>
	 			<h5 class="top-tag"><?php echo __('Award Amount: ', 'journobiz') . $details['award-amount']; ?></h5>
	 			<?php largo_excerpt( $post, 3, false ); ?>
			</div>
	 		<meta itemprop="description" content="<?php echo strip_tags(largo_excerpt( $post, 5, false, '', false ) ); ?>" />
	 		<meta itemprop="datePublished" content="<?php echo get_the_date( 'c' ); ?>" />
	 		<meta itemprop="dateModified" content="<?php echo get_the_modified_date( 'c' ); ?>" />
	 		<?php
	 			if ( has_post_thumbnail( $post->ID ) ) {
					$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'thumbnail' );
					echo '<meta itemprop="image" content="' . $image[0] . '" />';
				}
	 		?>
	 		<nav class="prev-next">
	 			<?php next_post_link( '%link', '<span>Next Project&nbsp;</span>▶' ); ?>
	 			<?php previous_post_link( '%link', '◀<span>Previous Project</span>' ); ?>
	 		</nav>
		</header><!-- / entry header -->

		<div class="grantee-links">
			<ul>
				<li><a href="<?php echo wp_get_attachment_url( $details['proposal-id'] ); ?>"><i class="icon-doc-text"></i><?php _e('Read the Proposal (PDF)', 'journobiz'); ?></a></li>
				<li><a href="<?php echo wp_get_attachment_url( $details['budget-id'] ); ?>"><i class="icon-dollar"></i><?php _e('Project Budget (PDF)', 'journobiz'); ?></a></li>
				<li><a href="javascript:void(0);" class="timeline-control"><i class="icon-clock"></i><?php _e('Project Timeline', 'journobiz'); ?></a></li>
			</ul>
		</div>
		<div class="timeline">
			<iframe src="<?php echo preg_replace('/&height=(\d{2,4})/', '', $details['timeline-src']) . "&height=500"; ?>" width="100%" height="500" frameborder="0"></iframe>
			<a href="javascript:void(0);" class="close">Close <i class="icon-cancel"></i></a>
		</div>

		<div class="span8 grantee-posts">
		<h3><?php _e('Project Updates', 'journobiz'); ?></h3>
		<?php
			$current_url = get_permalink();
			$grantee_posts = posts_by_grantee( get_the_ID(), get_query_var('paged') );
			while( $grantee_posts->have_posts() ) : $grantee_posts->the_post();
				get_template_part('content');
			endwhile;

			if ($grantee_posts->post_count == 0) {
				echo "<p class='none-found'>" . __("No updates posted yet.", "journobiz") . "</p>";
			}

			//pagination
			if ( $grantee_posts->max_num_pages > 1 ) {
				if ( $grantee_posts->query_vars['paged'] < $grantee_posts->max_num_pages - 1 ) {
					//fix how 0 = 1 in paged
					$older_page = $grantee_posts->query_vars['paged'] + 1;
					if ( $older_page == 1 ) $older_page = 2;	//because paged 0 is the same as paged 1
					echo '<a href="' . $current_url . 'page/' . $older_page . '" rel="prev">Older</a>';
				}
				if ( $grantee_posts->query_vars['paged'] > 0 ) {
				 if ( $grantee_posts->query_vars['paged'] == 2 ) {
					echo '<a href="' . $current_url . '" rel="next">Newer</a>';
				 } else {
					echo '<a href="' . $current_url . 'page/' . ($grantee_posts->query_vars['paged'] - 1) . '" rel="next">Newer</a>';
					}
				}
			}

			//print_r($grantee_posts);
			wp_reset_postdata();
		?>
		</div>
	<?php
		endwhile;
	?>
</div>
</div><!--#content-->
<?php get_footer(); ?>