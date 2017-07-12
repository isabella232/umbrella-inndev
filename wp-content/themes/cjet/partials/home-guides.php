<?php
$guides_parent = get_page_by_path('guides');
$guides = get_pages( array(
	'sort_column' => 'post_date',
	'sort_order' => 'DESC',
	'parent' => $guides_parent->ID
));
?>
<section id="guides">
	<h1><?php _e('Guides', 'cjet'); ?></h1>
	<p class="description"><?php
		if ( of_get_option('cjet_guides_intro') ) {
			echo of_get_option('cjet_guides_intro');
		} else {
			_e('Edit this description under Appearance > Theme Options.', 'cjet');
		}
	?></p>
	<ul>
	<?php
		foreach ( $guides as $guide ) : ?>
			<li>
				<article>
					<a href="<?php echo get_permalink( $guide->ID ); ?>" title="Permalink to <?php echo esc_attr( $guide->post_title ); ?>"><?php echo apply_filters('the_content', get_the_post_thumbnail( $guide->ID, 'medium' ) ); ?></a>
					<h4><a href="<?php echo get_permalink( $guide->ID ); ?>" title="Permalink to <?php echo esc_attr( $guide->post_title ); ?>"><?php echo $guide->post_title; ?></a></h4>
					<p><?php echo $guide->post_excerpt; ?></p>
				</article>
			</li>
			<?php
		endforeach;
	?>
	</ul>
</section><!-- #guides -->


