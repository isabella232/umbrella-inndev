<?php
/**
 * The Template for displaying authors
 * This is for INN Members only, if the given author isn't an INN member, we'll fall back to archive.php
 */

//get the author information, see if they're a member
$author = get_queried_object();
$meta = get_user_meta( $author->ID );

//some sort of test to see if this is a member
if ( $meta['user_type'] !== 'member' ) {
	get_template_part('archive');
}

get_header();
?>

<div id="content" class="span8" role="main">

	<?php
		$social = array('rss', 'twitter', 'facebook', 'googleplus', 'youtube');
	?>
	<article id="post-<?php $author->ID; ?>" <?php post_class('clearfix'); ?>>
		<?php echo get_avatar( $author->ID, 120); // should use thumbnail size, but can we? ?>
		<div>
		<header>
	 		<h2 class="entry-title">
		 			<?php $author->display_name; ?>
	 		</h2>
	 		<h5 class="history byline">
	 			<?php if ( !empty($meta['inn_founded'][0]) ) echo "Founded " . $meta['inn_founded'][0] . ";"; ?>
	 			<?php if ( !empty($meta['inn_since'][0]) ) echo "Member of INN since " . $meta['inn_since'][0]; ?>
	 		</h5>
		</header>
		<div class="entry-content">
			<?php echo apply_filters('the_content', $author->user_description); ?>
		</div><!-- .entry-content -->
		<footer>
			<?php if ( !empty($meta['inn_site_url'][0])) : ?>
				<h6><strong>Website:</strong> <a href="<?php echo safe_url($meta['inn_site_url'][0]); ?>"><?php echo $meta['inn_site_url'][0]; ?></a></h6>
			<?php endif; ?>
			<ul class="social"><?php
				foreach ($social as $network) {
					if ( !empty($meta['inn_'.$network][0])) {
						if ( 'facebook' == $network ) {
							$url = "https://fb.com/" . $meta['inn_facebook'][0];
						} else if ( 'twitter' == $network ) {
							$url = "https://twitter.com/" . $meta['inn_twitter'][0];
						} else {
							$url = safe_url( $meta['inn_'.$network][0] );
						}
						if ( 'googleplus' == $network ) $network = 'gplus';
						?>
						<li><a href="<?php echo $url; ?>" target="_blank"><i class="icon-<?php echo $network; ?>"></i></a></li>
						<?php
					}
				}
			?>
			</ul>
		</footer>
		</div>
	</article><!-- #post-<?php the_ID(); ?> -->

	<?php

		$rss_url = $meta['rss_url'];

		//load up recent stories
		if ($rss_url) :
			$oldpost = $post;
			//get our posts
			$wp_query->query( array(
				'post_type' => 'network_content',
				'posts_per_page' => 5,
				'paged' => (get_query_var('paged')) ? get_query_var('paged') : 1,
				'suppress_filters' => false,
				'author' => $author->ID
				)
			);

			if ( have_posts() ) : ?>
				<div class="recent-posts-wrapper stories">
					<h3 class="recent-posts clearfix">
					<?php
						printf(__('Recent %s<a class="rss-link" href="%s"><i class="icon-rss"></i></a>', 'largo'),
							of_get_option('posts_term_plural', 'stories'),
							$rss_url
						);
					?>
				</h3>
				<?php

				while ( have_posts() ) : the_post();
					get_template_part( 'content', 'archive' );
				endwhile;

				largo_content_nav( 'nav-below' );
				echo '</div>';
			endif;	//have_posts

			//put everything back
			$post = $oldpost;
			setup_postdata( $post );
		endif; //rss_url ?>

</div><!--#content-->

<?php get_sidebar(); ?>
<?php get_footer(); ?>