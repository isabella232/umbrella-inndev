<?php
/**
 * NB: This is not a normal template snippet you can include with get_template_part(); it expects a $user object
 *
 * Reference this with include(locate_template('content-member.php')); instead
 */
  $meta = get_user_meta( $user->ID );;
	$social = array('rss', 'twitter', 'facebook', 'googleplus', 'youtube');
?>
	<article id="post-<?php $user->ID; ?>" class="inn_member clearfix">
		<a href="<?php echo get_author_posts_url( $user->ID ); ?>"><?php echo get_image_tag(
			$meta['paupress_pp_avatar'][0],
			esc_attr($user->data->display_name),
			esc_attr($user->data->display_name),
			'left',
			'thumbnail'
		); ?></a>
		<div>
		<header>
	 		<h2 class="entry-title">
		 			<a href="<?php echo get_author_posts_url( $user->ID ); ?>"><?php echo $user->data->display_name; ?></a>
	 		</h2>
	 		<h5 class="history byline">
	 			<?php if ( !empty($meta['inn_founded'][0]) ) echo "Founded " . $meta['inn_founded'][0] . ";"; ?>
	 			<?php if ( !empty($meta['inn_since'][0]) ) echo "Member of INN since " . $meta['inn_since'][0]; ?>
	 		</h5>
		</header>
		<div class="entry-content">
			<?php echo apply_filters( 'the_content', $meta['description'][0] ); ?>
		</div><!-- .entry-content -->
		<footer>
			<?php if ( !empty($user->data->user_url)) : ?>
				<h6><strong>Website:</strong> <a href="<?php echo maybe_http($user->data->user_url); ?>"><?php echo $user->data->user_url; ?></a></h6>
			<?php endif; ?>
			<ul class="social"><?php
				foreach ($social as $network) {
					$network_field = 'inn_' . $network;
					if ( !empty($meta[$network_field][0])) {
						if ( 'facebook' == $network ) {
							$url = "https://fb.com/" . $meta[$network_field][0];
						} else if ( 'twitter' == $network ) {
							$url = "https://twitter.com/" . $meta[$network_field][0];
						} else {
							$url = maybe_http( $meta[$network_field][0] );
						}
						if ( 'googleplus' == $network ) $network = 'gplus';
						?>
						<li><a href="<?php echo $url; ?>" target="_blank"><i class="icon-<?php echo $network; ?>"></i></a></li><?php
					}
				}
				if ( !empty($meta['inn_donate'][0])) { ?>
					<li class="donate-btn"><a href="<?php echo maybe_http( $meta['inn_donate'][0] ); ?>" target="_blank"><i class="icon-heart"></i>Donate Now</a></li>
				<?php } ?>
			</ul>
		</footer>
		</div>
	</article><!-- #post-<?php the_ID(); ?> -->