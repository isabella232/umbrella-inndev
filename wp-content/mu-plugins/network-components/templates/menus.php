<?php switch_to_blog( BLOG_ID_CURRENT_SITE ); ?>
	<nav>
		<ul class="network-header-main-nav">
			<li class="network-home">
				<a href="http://investigativenewsnetwork.org/"><span>Investigative News Network</span></a>
			</li>
			<?php
			$nav_args = array(
				'theme_location' => 'network-header',
				'depth' => 2,
				'container' => false,
				'items_wrap' => '%3$s',
				'walker' => new Global_Nav_Walker()
			);
			largo_cached_nav_menu( $nav_args );
			?>
		</ul>
		<button class="mobile-toggle">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
	</nav>

	<?php if ( of_get_option( 'show_donate_button' ) ) { largo_donate_button(); } ?>

	<div class="gcs_container search">
		<script>
			(function() {
				var cx = '<?php echo of_get_option('gcs_id'); ?>';
				var gcse = document.createElement('script');
				gcse.type = 'text/javascript';
				gcse.async = true;
				gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
					'//www.google.com/cse/cse.js?cx=' + cx;
				var s = document.getElementsByTagName('script')[0];
				s.parentNode.insertBefore(gcse, s);
			})();
		</script>
		<gcse:search></gcse:search>
	</div>

<?php restore_current_blog(); ?>
