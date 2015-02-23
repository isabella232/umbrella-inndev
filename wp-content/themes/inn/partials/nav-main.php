<div class="sticky-nav-wrapper">
	<div class="sticky-nav-holder show"
		data-hide-at-top="<?php echo (is_front_page() || is_home()) ? 'true' : 'false'; ?>">

		<?php do_action( 'largo_before_sticky_nav_container' ); ?>

		<div class="sticky-nav-container">
			<nav id="sticky-nav" class="sticky-navbar navbar">
				<div class="container">
					<div class="nav-right">
						<ul id="header-extras"><?php
							if ( of_get_option( 'show_donate_button') ) {
								if ($donate_link = of_get_option('donate_link')) { ?>
								<li>
									<a class="donate-link" href="<?php echo esc_url($donate_link); ?>">
										<span><i class="icon-heart"></i><?php echo esc_html(of_get_option('donate_button_text')); ?></span>
									</a>
								</li><?php
								}
							} ?>
							<li id="sticky-nav-search">
								<a href="#" class="toggle flip-horizontal">
									<i class="icon-search" title="<?php esc_attr_e('Search', 'largo'); ?>" role="button"></i>
								</a>
								<form class="form-search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
									<div class="input-append">
										<span class="text-input-wrapper">
											<input type="text" placeholder="<?php esc_attr_e('Search', 'largo'); ?>"
												class="input-medium appendedInputButton search-query" value="" name="s" />
										</span>
										<button type="submit" class="search-submit btn"><?php _e('Go', 'largo'); ?></button>
									</div>
								</form>
							</li>
						</ul>

					<?php if ( of_get_option( 'show_header_social') ) { ?>
						<ul id="header-social" class="social-icons visible-desktop">
							<?php largo_social_links(); ?>
						</ul>
					<?php } ?>

					</div>

					<!-- .btn-navbar is used as the toggle for collapsed navbar content -->
					<a class="btn btn-navbar toggle-nav-bar" title="<?php esc_attr_e('More', 'largo'); ?>">
						<div class="bars">
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</div>
					</a>

					<div class="nav-shelf">
						<ul class="nav">
							<li class="home-link">
								<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
									<img src="<?php echo get_stylesheet_directory_uri() . '/images/logo.png'; ?>" /></a>
							</li>
						</ul>
						<ul class="nav items">
							<?php
								$args = array(
								'theme_location' => 'main-nav',
								'depth'		 => 0,
								'container'	 => false,
								'items_wrap' => '%3$s',
								'menu_class' => 'nav',
								'walker'	 => new Bootstrap_Walker_Nav_Menu()
								);
								largo_nav_menu($args);
							?>
						</ul>
					</div>
				</div>
			</nav>
		</div>
	</div>
</div>
