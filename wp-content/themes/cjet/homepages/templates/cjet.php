<section id="biggo">
	<?php dynamic_sidebar( 'homepage-top' ); ?>
</section>

<section id="topics">
	<h2><?php _e('Explore Topics', 'cjet'); ?></h2>

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


	<div id="topics-container">
		<?php dynamic_sidebar( 'homepage-topics' ); ?>
	</div>
</section><!-- #topics -->
