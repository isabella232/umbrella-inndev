<?php
/**
 * The default template for displaying content
 */
$hero_class = largo_hero_class( $post->ID, FALSE );
$featured = has_term( 'homepage-featured', 'prominence' );
$meta_values = get_post_meta( $post->ID );
$member_values = get_user_meta( $meta_values['from_member_id'][0] );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?>>
	<?php
		if ( $featured && ( has_post_thumbnail() || $values['youtube_url'] ) ) {
	?>
		<header>
			<div class="hero span12 <?php echo $hero_class; ?>">
			<?php
				if ( $youtube_url = $values['youtube_url'][0] ) {
					echo '<div class="embed-container">';
					largo_youtube_iframe_from_url( $youtube_url );
					echo '</div>';
				} elseif( has_post_thumbnail() ){
					the_post_thumbnail( 'full' );
				}
			?>
			</div>
		</header>
	<?php
		}
		$entry_classes = 'entry-content';
		if ( $featured ) $entry_classes .= ' span10 with-hero';
		echo '<div class="' . $entry_classes . '">';

		printf('<h5 class="top-tag"><span class="member-name"><a href="%s">%s</a></span> <span class="donate"><a href="%s">donate</a></span></h5>',
			get_author_posts_url( $meta_values['from_member_id'][0] ),
			$member_values['organization'][0],
			$member_values['inn_donate'][0]
		);


		if ( !$featured ) {
			echo '<a href="' . get_permalink() . '">' . get_the_post_thumbnail() . '</a>';
		}
	?>

	 	<h2 class="entry-title">
	 		<a href="<?php echo $meta_values['rssmi_source_link'][0]; ?>" title="<?php the_title_attribute( array( 'before' => __( 'Permalink to', 'largo' ) . ' ' ) )?>" rel="bookmark"><?php the_title(); ?></a>
	 	</h2>

	 	<h5 class="byline"><?php largo_byline(); ?></h5>

	 	<?php edit_post_link(); ?>

		<?php largo_excerpt( $post, 5, false, '', true, false, false ); ?>

		</div><!-- .entry-content -->

</article><!-- #post-<?php the_ID(); ?> -->