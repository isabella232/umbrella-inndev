<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 */
?>
	</div> <!-- #main -->

</div><!-- #page -->

<div class="footer-bg clearfix">
	<nav id="largo-footer" class="clearfix">
		<div class="navbar-inner clearfix">
			<ul>
				<?php
					$args = array(
						'theme_location' => 'main-nav',
						'depth' => 0,
						'container' => false,
						'items_wrap' => '%3$s',
						'menu_class' => 'nav',
						'walker' => new Bootstrap_Walker_Nav_Menu()
					);
					largo_nav_menu( $args );
				?>
			</ul>
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/homepages/assets/img/logos/INN-Logo-Primary-White-240x80.png" />
		</div>
	</nav>
</div>

<?php wp_footer(); ?>

</body>
</html>
