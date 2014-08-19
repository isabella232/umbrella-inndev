<?php
/**
 * The Template for displaying INN members
 * This is for INN Members only, and overrides author.php (or archive.php) in the theme
 */
get_header();

$author = $user = get_queried_object();
$meta = get_user_meta( $author->ID );
?>

<div id="content" class="span8" role="main">

	<?php
		$social = array('rss', 'twitter', 'facebook', 'googleplus', 'youtube');
	?>
	<article id="author-<?php echo $author->ID; ?>" class="inn_member clearfix">
		<?php echo get_image_tag(
			$meta['paupress_pp_avatar'][0],
			esc_attr($user->data->display_name),
			esc_attr($user->data->display_name),
			'left',
			'thumbnail'
		); ?>
		<div>
		<header>
	 		<h2 class="entry-title">
		 			<?php echo $author->data->display_name; ?>
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
			<?php if ( !empty($author->data->user_url) ) : ?>
				<h6><strong>Website:</strong> <a href="<?php echo safe_url($author->data->user_url); ?>"><?php echo $author->data->user_url; ?></a></h6>
			<?php endif; ?>

			<?php if ( !empty($meta[INN_MEMBER_TAXONOMY][0]) ) : ?>
				<?php
					$term_list = array();
					$foci = maybe_unserialize($meta[INN_MEMBER_TAXONOMY][0]);
					foreach ($foci as $term_id) {
						$term = get_term_by( 'id', $term_id, INN_MEMBER_TAXONOMY );
						if ( $term ) {
							$term_list[] = $term->name;
						}
					}

			?><h6><strong>Focus Areas:</strong> <span><?php echo implode(", ", $term_list); ?></span></h6>
			<?php endif; ?>

			<?php if ( function_exists('pau_inn_forms')) : ?>
			<div class="contact">
				<h6><strong>Got a News Tip? <a href="#" class="toggle" data-toggler="#contact-form">Contact <?php echo $author->data->display_name; ?></a></strong></h6>
				<div id="contact-form">
					<?php echo pau_inn_forms( '_pp_form_8b64e924248b055cfc4a6b009d62406a', $author->ID ); ?>
				</div>
			</div>
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

		//load up recent stories
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

	?>
</div><!--#content-->

<?php get_sidebar(); ?>
<?php get_footer(); ?>