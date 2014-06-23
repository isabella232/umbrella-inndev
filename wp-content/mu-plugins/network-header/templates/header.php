<?php switch_to_blog(BLOG_ID_CURRENT_SITE); ?>
<div class="network-header">
	<nav>
		<ul class="network-header-main-nav">
			<li class="network-home">
				<a href="<?php echo network_site_url(); ?>"><span><?php echo get_site_option('name'); ?></span></a>
			</li>
			<?php
			$nav_args = array(
				'theme_location' => 'navbar-categories',
				'depth' => 2,
				'container' => false,
				'items_wrap' => '%3$s',
				'walker' => new Global_Nav_Walker()
			);
			largo_cached_nav_menu($nav_args);
			?>
		</ul>
	</nav>

	<?php if ( of_get_option( 'show_donate_button') )
		largo_donate_button();
	?>

	<div class="search">
		<form class="form-search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<i class="icon-search toggle" title="<?php esc_attr_e('Search', 'largo'); ?>" role="button"></i>
			<div class="input-append">
				<span class="text-input-wrapper"><input type="text" placeholder="<?php esc_attr_e('Search', 'largo'); ?>" class="input-medium appendedInputButton search-query" value="" name="s" /></span><button type="submit" class="search-submit btn"><?php _e('GO', 'largo'); ?></button>
			</div>
		</form>
	</div>
</div>
<?php restore_current_blog(); ?>
