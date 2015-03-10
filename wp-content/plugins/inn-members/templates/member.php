<?php
/**
 * NB: This is not a normal template snippet you can include with get_template_part(); it expects a $user object
 *
 * Reference this with include(locate_template('content-member.php')); instead
 */
$meta = get_user_meta( $user->ID );
$social = array('mail', 'rss', 'twitter', 'facebook', 'googleplus', 'youtube');
?>

<?php
	echo '<a href="' . get_author_posts_url( $user->ID ) . '">' . get_avatar( $user->ID ) . '</a>';

	echo '<h3><a href="' . get_author_posts_url( $user->ID ) . '">' . $user->data->display_name . '</a></h3>';

	if ( !empty( $meta['inn_since'][0] ) ) {
		echo '<p class="member-since">Member since ' . $meta["inn_since"][0] . '</p>';
	}
?>

<ul class="social">
	<?php
		if ( !empty ( $user->user_email ) ) {
			echo '<li><a href="mailto:' . $user->user_email  . '"><i class="icon-mail"></i></a></li>';
		}
		foreach ( $social as $network ) {
			if ( !empty( $meta['inn_'.$network][0] ) ) {
				if ( 'facebook' == $network ) {
					$url = "https://fb.com/" . $meta['inn_facebook'][0];
				} else if ( 'twitter' == $network ) {
					$url = "https://twitter.com/" . $meta['inn_twitter'][0];
				} else {
					$url = maybe_http( $meta['inn_'.$network][0] );
				}
				if ( 'googleplus' == $network ) $network = 'gplus';

				echo '<li><a href="' . $url . '" target="_blank"><i class="icon-' . $network . '"></i></a></li>';
			}
		}
	?>
</ul>

<?php
	if ( !empty( $user->data->user_url ) ) {
		echo '<p><a href="' . maybe_http( $user->data->user_url ) . '">Visit Website</a></p>';
	}
?>
