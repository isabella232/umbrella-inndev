<section id="top">
	<?php dynamic_sidebar( 'homepage-top' ); ?>
</section>

<section id="guides">
	<h2><?php _e('Explore guides', 'cjet'); ?></h2>

	<p class="description"><?php
		if ( of_get_option('cjet_guides_intro') ) {
			echo of_get_option('cjet_guides_intro');
		} else {
			printf(
				'<!-- %1$s -->',
				_e('Edit this description under Appearance > Theme Options.', 'cjet')
			);
		}
	?></p>


	<div id="guides-container">
		<?php dynamic_sidebar( 'homepage-guides' ); ?>
	</div>
</section><!-- #guides -->
