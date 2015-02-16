<?php
/**
 * Template for displaying an INN member profile
 * This is for INN Members only, and overrides author.php (or archive.php) in the theme
 */
get_header();

$member = get_queried_object();
$meta = get_user_meta( $member->ID );
$social = array('rss', 'twitter', 'facebook', 'googleplus', 'youtube');
?>

<div id="content" class="row-fluid" role="main">
	<article id="author-<?php echo $member->ID; ?>" class="inn_member clearfix">
		<div class="span3">
			<?php
				echo get_image_tag(
					$meta['paupress_pp_avatar'][0],
					esc_attr( $member->data->display_name ),
					esc_attr( $member->data->display_name ),
					'left',
					'medium'
				);

				if ( !empty( $meta['inn_founded'][0] ) ) {
					echo '<p class="founded">Founded ' . $meta["inn_founded"][0] . '</p>';
				}
				if ( !empty( $meta['inn_since'][0] ) ) {
					echo '<p class="member-since">INN member since ' . $meta["inn_since"][0] . '</p>';
				}
			?>

			<ul class="social">
				<?php
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
		</div>
		<div class="span9">
			<h1 class="entry-title"><?php echo $member->data->display_name; ?></h1>
			<div class="entry-content">
				<?php
					echo apply_filters( 'the_content', $member->user_description );

					if ( !empty( $meta[INN_MEMBER_TAXONOMY][0] ) ) {
						$term_list = array();
						$foci = maybe_unserialize( $meta[INN_MEMBER_TAXONOMY][0] );
						foreach ( $foci as $term_id ) {
							$term = get_term_by( 'id', $term_id, INN_MEMBER_TAXONOMY );
							if ( $term ) {
								$term_list[] = $term->name;
							}
						}
						echo '<p><strong>Focus Areas:</strong> <span>' . implode( ", ", $term_list ) . '</span></p>';
					}

					if ( !empty ( $meta['inn_donate'][0] ) ) {
						echo '<a class="btn donate" href="' . $meta['inn_donate'][0] . '">Donate Now</a>';
					}

					if ( !empty( $member->data->user_url ) ) {
						echo '<a class="btn website" href="' . maybe_http( $member->data->user_url ) . '">Visit Website</a>';
					}

					if ( !empty ( $member->user_email ) ) {
						echo '<a class="btn email" href="mailto:' . $member->user_email  . '">Contact This Member</a>';
					}


				?>
			</div>
		</div>
	</article>
</div>

<?php get_footer(); ?>