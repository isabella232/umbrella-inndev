<?php if (is_front_page() || is_home()) : ?>
	<header id="site-header" class="clearfix" itemscope itemtype="http://schema.org/Organization">
		<h1 class="visuallyhidden">
			<a itemprop="url" href="<?php echo get_site_url(); ?>"><span itemprop="name">Journo.biz</span></a>
		</h1>

		<a itemprop="url" href="<?php echo get_site_url(); ?>">
			<img itemprop="logo" class="logo" src="<?php echo get_stylesheet_directory_uri(); ?>/img/journobiz.png">
		</a>

		<div class="newsletter-signup">
			<form action="//investigativenewsnetwork.us1.list-manage.com/subscribe/post?u=81670c9d1b5fbeba1c29f2865&amp;id=e3e0b6be7f"
				method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
				<label>Subscribe to our free newsletter</label>
				<fieldset>
					<input required type="email" value="" name="EMAIL" class="required email_address" id="mce-EMAIL" placeholder="Email address">
					<input required type="text" value="" name="FNAME" class="first_name" id="mce-FNAME" placeholder="First name">
					<input required type="text" value="" name="LNAME" class="last_name" id="mce-LNAME" placeholder="Last name">
					<input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="btn submit">
					<!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
					<div style="position: absolute; left: -5000px;"><input type="text" name="b_81670c9d1b5fbeba1c29f2865_e3e0b6be7f" tabindex="-1" value=""></div>
					<div class="error"></div>
				</fieldset>
			</form>
		</div>
	</header>
<?php endif; ?>

<header class="print-header">
	<p><strong><?php echo esc_html( get_bloginfo( 'name' ) ); ?></strong> (<?php echo esc_url( $current_url ); ?>)</p>
</header>
