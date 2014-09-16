<div class="sticky-nav-wrapper">
	<div class="sticky-nav-holder show" data-hide-at-top="<?php echo (is_front_page() || is_home()) ? 'true' : 'false'; ?>">
		<?php do_action( 'largo_before_sticky_nav_container' ); ?>
		<div class="sticky-nav-container">
			<nav id="sticky-nav" class="sticky-navbar navbar clearfix">
			    <div class="container">
					<!-- .btn-navbar is used as the toggle for collapsed navbar content -->
					<a class="btn btn-navbar toggle-nav-bar" title="<?php esc_attr_e('More', 'largo'); ?>">
			        <div class="bars">
				        <span class="icon-bar"></span>
				        <span class="icon-bar"></span>
				        <span class="icon-bar"></span>
			        </div>
					</a>

					<ul class="nav">
			        	<li class="home-link"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php largo_home_icon( 'icon-white' ); ?></a></li>
						<li class="divider-vertical"></li>
					</ul>

					<div class="nav-shelf">
						<ul class="nav">
						<?php
							$args = array(
								'theme_location' 	=> 'main-nav',
								'depth'				=> 0,
								'container'	 		=> false,
								'items_wrap' 		=> '%3$s',
								'menu_class' 		=> 'nav',
								'walker'	 		=> new Bootstrap_Walker_Nav_Menu()
							);
							largo_cached_nav_menu($args);
						?>
						</ul>
					</div>
			    </div>
			</nav>
		</div>
	</div>
</div>