<?php
/*
 * Global Navigation Menu
 *
 * Shown at the very top of a Largo site on a (default) thin dark bar.
 * Not shown when sticky navigation is displayed, so typically only the home page.
 * This menu is appended to the offcanvas nav menu (see partial/nav-sticky.php).
 *
 * @package Largo
 * @link http://largo.readthedocs.io/users/menus.html#available-menu-areas
 */

if (
	! is_single() && ! is_singular()
	|| ! of_get_option( 'main_nav_hide_article', false )
	|| is_front_page()
) {
?>
	<div class="global-nav-bg">
		<div class="global-nav">
			<nav id="top-nav" class="span12">
				<span class="visuallyhidden">
					<a href="#main" title="<?php esc_attr_e( 'Skip to content', 'largo' ); ?>"><?php _e( 'Skip to content', 'largo' ); ?></a>
				</span>
				<?php
					/* Global Navigation Menu Query */
					$top_args = array(
						'theme_location' => 'global-nav',
						'depth'		 => 1,
						'container'	 => false,
					);
					largo_nav_menu($top_args);
				?>
				<div class="nav-right">
					<?php
					/* Check to display Social Media Icons */
					if ( of_get_option( 'show_header_social') ) { ?>
						<ul id="header-social" class="social-icons visible-desktop">
							<?php largo_social_links(); ?>
						</ul>
					<?php }
					/* Check to display Donate Button */
					if ( of_get_option( 'show_donate_button') ) {
						largo_donate_button();
					}
					?>
				<!-- END Header Search -->
				</div>
			</nav>
		</div> <!-- /.global-nav -->
	</div> <!-- /.global-nav-bg -->
<?php }
