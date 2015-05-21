<div id="boilerplate">
	<div class="row-fluid clearfix">
		<div class="span12">
			<div class="footer-bottom clearfix">
				<p class="copyright"><?php largo_copyright_message(); ?></p>
				<p class="footer-credit"><?php printf( __('This site built with <a href="%s">Project Largo</a> from <a href="%s">INN</a> and proudly powered by <a href="%s" rel="nofollow">WordPress</a>.', 'largo'),
						'http://largoproject.org',
						'http://inn.org',
						'http://wordpress.org'
					 );
				?></p>
			</div>
		</div>
	</div>

	<div class="row-fluid clearfix">
		<div class="span12">
			<?php largo_nav_menu(
				array(
					'theme_location' => 'footer-bottom',
					'container' => false,
					'depth' => 1
				) );
			?>
		</div>
		<p class="back-to-top"><a href="#top"><?php _e('Back to top', 'largo'); ?> &uarr;</a></p>
	</div>
</div>
