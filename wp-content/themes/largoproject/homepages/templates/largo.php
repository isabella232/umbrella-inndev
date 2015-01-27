<div id="content" class="homepage-content clearfix span12">

	<section>
		<h2>Features</h2>
		<h4>Designed for publishers, built for developers.</h4>
		<div class="row-fluid">
			<div class="span4">
				<img class="icon" src="<?php echo get_stylesheet_directory_uri(); ?>/homepages/assets/img/icons/responsive.svg" />
				<h5>Responsive Design</h5>
				<p>Clean, modern and mobile first. Largo is designed to look great on any device.</p>
			</div>
			<div class="span4">
				<img class="icon" src="<?php echo get_stylesheet_directory_uri(); ?>/homepages/assets/img/icons/workflow.svg" />
				<h5>Powerful Publishing Tools</h5>
				<p>Unlike other WordPress "news" themes, Largo is built by <a href="http://nerds.investigativenewsnetwork.org/">real news nerds</a>. We obsess about workflow, too.</p>
			</div>
			<div class="span4">
				<img class="icon" src="<?php echo get_stylesheet_directory_uri(); ?>/homepages/assets/img/icons/devfriendly.svg" />
				<h5>Developer Friendly</h5>
				<p>Logical organization, <a href="http://largo.readthedocs.org/developers/fordevelopers.html">documentation</a> and <a href="https://github.com/INN/Largo-Sample-Child-Theme">a sample child theme</a> make it easy to use Largo for your next project.</p>
			</div>
		</div>

	</section>

	<div class="row-fluid interstitial">
		<h3>Largo is a project of INN</h3>
		<p><a href="http://investigativenewsnetwork.org/">INN</a> is a nonprofit organization supporting a network of <a href="http://investigativenewsnetwork.org/member/">100+ nonprofit news organizations</a>. Projects like Largo depend on your support.</p>
		<a class="btn btn-donate" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=M7T6234BREMG2">Donate Now</a>
	</div>

	<section>
		<h2>Support</h2>
		<h4>Need assistance getting started with Largo? We're here to help.</h4>
		<div class="row-fluid">
			<div class="span4">
				<a href="http://largo.readthedocs.org/"><img class="icon" src="<?php echo get_stylesheet_directory_uri(); ?>/homepages/assets/img/icons/docs.svg" /></a>
				<h5><a href="http://largo.readthedocs.org/">Documentation</a></h5>
				<p>Get up and running with Largo and explore many of our more advanced features.</p>
			</div>
			<div class="span4">
				<a href="http://confluence.investigativenewsnetwork.org/display/LKB/Largo+Knowledge+Base"><img class="icon" src="<?php echo get_stylesheet_directory_uri(); ?>/homepages/assets/img/icons/faq.svg" /></a>
				<h5><a href="http://confluence.investigativenewsnetwork.org/display/LKB/Largo+Knowledge+Base">Knowledge Base</a></h5>
				<p>Answers to our most frequently asked questions.</p>
			</div>
			<div class="span4">
				<a href="http://jira.investigativenewsnetwork.org/servicedesk/customer/portal/4"><img class="icon" src="<?php echo get_stylesheet_directory_uri(); ?>/homepages/assets/img/icons/helpdesk.svg" /></a>
				<h5><a href="http://jira.investigativenewsnetwork.org/servicedesk/customer/portal/4">Help Desk</a></h5>
				<p>Need more help? <a href="http://jira.investigativenewsnetwork.org/servicedesk/customer/portal/4">Submit a support request</a>.</p>
			</div>
		</div>
	</section>

	<div class="row-fluid interstitial news">
		<h3>Latest News</h3>
		<?php
			switch_to_blog( 7 );

			$args = array (
				'showposts' => 1,
				'post_status' => 'publish',
				'cat' => 130 // Largo
			);

			$query = new WP_Query( $args );

          	if ( $query->have_posts() ) {
          		while ( $query->have_posts() ) : $query->the_post();
          			echo '<h5><a href="' . get_permalink() . '">' . get_the_title() . '</a></h5>';
          			largo_excerpt();
          			echo '<p class="more"><a href="http://nerds.investigativenewsnetwork.org/category/largo/">More Project Updates</a></p>';
          		endwhile;
          	}

			restore_current_blog();
		?>
	</div>

	<section id="showcase">
		<h2>Showcase</h2>
		<h4>Over 100 sites are using Largo. Here are a few:</h4>
		<div class="row-fluid">
			<div class="span4">
				<a href="http://wisconsinwatch.org"><img src="<?php echo get_stylesheet_directory_uri(); ?>/homepages/assets/img/screenshots/wcij.png" /></a>
			</div>
			<div class="span4">
				<a href="http://ctmirror.org"><img src="<?php echo get_stylesheet_directory_uri(); ?>/homepages/assets/img/screenshots/ctmirror.png" /></a>
			</div>
			<div class="span4">
				<a href="http://kycir.org"><img src="<?php echo get_stylesheet_directory_uri(); ?>/homepages/assets/img/screenshots/kycir.png" /></a>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span4">
				<a href="http://periodismoinvestigativo.com/"><img src="<?php echo get_stylesheet_directory_uri(); ?>/homepages/assets/img/screenshots/cpipr.png" /></a>
			</div>
			<div class="span4">
				<a href="http://www.yellowstonegate.com/"><img src="<?php echo get_stylesheet_directory_uri(); ?>/homepages/assets/img/screenshots/yellowstone.png" /></a>
			</div>
			<div class="span4">
				<a href="http://dohanews.co/"><img src="<?php echo get_stylesheet_directory_uri(); ?>/homepages/assets/img/screenshots/doha.png" /></a>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span4">
				<a href="http://journo.biz/"><img src="<?php echo get_stylesheet_directory_uri(); ?>/homepages/assets/img/screenshots/journobiz.png" /></a>
			</div>
			<div class="span4">
				<a href="http://nicaraguadispatch.com/"><img src="<?php echo get_stylesheet_directory_uri(); ?>/homepages/assets/img/screenshots/nicaragua.png" /></a>
			</div>
			<div class="span4">
				<a href="http://gijn.org"><img src="<?php echo get_stylesheet_directory_uri(); ?>/homepages/assets/img/screenshots/gijn.png" /></a>
			</div>
		</div>
	</section>

</div>