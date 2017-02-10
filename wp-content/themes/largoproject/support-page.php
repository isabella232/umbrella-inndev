<?php
/**
 * Template Name: Support Page
 */
get_header();
$top_page = FALSE;

?>
 <div id="content" class="homepage-content clearfix span12">
 	<section id="self-help" class="clearfix">
	 	<div class="max-width-container clearfix">
	 		<h2 id="support-header">Self-Help</h2>
			<div id="knowledge-base" class="clearfix">
				<h3><a href="http://support.largoproject.org/support/solutions">Knowledge Base</p></h3></a>
				<p>Dive into our knowledge base for our most popular questions and answers.</p>
				<div class="row-fluid">
					<a class="span3" href="http://support.largoproject.org/article/5-how-can-i-improve-the-load-time-of-my-site">How can I improve the load time of my site?</a>
					<a class="span3" href="http://support.largoproject.org/article/8-i-lost-my-password">I lost my password. How can I find it?</a>
					<a class="span3" href="http://support.largoproject.org/article/27-how-to-make-post-sticky">How can I make a post sticky?</a>
					<a class="span3" href="http://support.largoproject.org/article/15-how-can-i-add-a-link-to-a-menu">How can I add a link to a menu?</a>
				</div>
			</div>
			<div id="user-guides" class="clearfix">
				<div class="span6 clearfix">
					<h3><a href="https://largo.inn.org/guides/">User Guides</a></h3>
					<p>These guides will help you get the most out of Largo and answer many frequently-asked questions.</p>
				</div>
				<div class="span6 clearfix" >
					<div id="guide-btn-wrap" class="clearfix">
						<a href="https://largo.readthedocs.io/developers/index.html" class="btn user-guide-btn" id="btn-developer">For Developers</a>
						<a href="https://largo.inn.org/guides/administrators/" class="btn user-guide-btn" id="btn-admin">For Administrators</a>
						<a href="https://largo.inn.org/guides/authors/" class="btn user-guide-btn" id="btn-author">For Authors</a>
					</div>
				</div>
			</div>
		</div>
	</section>
	<section id="consulting-services" class="clearfix">
		<div class="max-width-container">
			<h5 id="support-header">Consulting Services</h5>
			<div class="row-fluid">
				<div class="span6">
					<h3><a href="https://nerds.inn.org/category/office-hours/">Office Hours</a></h3>
					<p>Every Friday from 2-3 pm ET we hold open office hours via Zoom.us videocalls where anyone can come and talk to our team about projects or talk about general design, tech or product questions. You can signup for a 20 minute spot or just drop by. All are welcome!</p>
					<a href="https://docs.google.com/spreadsheets/d/1p-twn2D8oow7vXBfkcdYcZnVA4z8Q42OMs77KlHwf-g/edit" class="btn">Sign Up for a Slot</a>
				</div>
				<div class="span6">
					<img class="animated-gif" src="<?php echo get_stylesheet_directory_uri(); ?>/homepages/assets/img/illustrations/office-hours.gif" />
					<img class="still-img" src="<?php echo get_stylesheet_directory_uri(); ?>/homepages/assets/img/illustrations/office-hours.png" />
				</div>
			</div>
		</div>
	</section>
	<section id="largo-support" class="largo-section clearfix hire-us">
		<div class="max-width-container">
			<h2>Hire Us</h2>
			<h4>We offer affordable consulting services (at an even more discounted rate for members).<br> Our team can provide:</h4>
			<div class="row-fluid">
				<div class="span4">
					<img class="frame-2" src="<?php echo get_stylesheet_directory_uri(); ?>/homepages/assets/img/icons/hire-us-01B.svg" />
					<img class="icon" src="<?php echo get_stylesheet_directory_uri(); ?>/homepages/assets/img/icons/hire-us-01.svg" />
					<h5>Content Migration</h5>
					<p>Transferring your existing content onto the new platform.</p>
				</div>
				<div class="span4">
					<img class="icon" src="<?php echo get_stylesheet_directory_uri(); ?>/homepages/assets/img/icons/hire-us-02.svg" /></a>
					<img class="frame-2" src="<?php echo get_stylesheet_directory_uri(); ?>/homepages/assets/img/icons/hire-us-02B.svg" /></a>
					<h5>Design Customization</h5>
					<p>Tailoring the aesthetics of the site to your organization’s needs</p>
				</div>
				<div class="span4">
					<img class="icon" src="<?php echo get_stylesheet_directory_uri(); ?>/homepages/assets/img/icons/hire-us-03.svg" /></a>
					<img class="frame-2" src="<?php echo get_stylesheet_directory_uri(); ?>/homepages/assets/img/icons/hire-us-03B.svg" /></a>
					<h5>Custom Development</h5>
					<p>Building new capabilities and tools for a better experience</p>
				</div>
			</div>
			<a href="https://inn.org/hire-us/" class="btn" id="learn-more">Learn More</a>
		</div>
	</section>
	<section id="help-desk">
		<div class="max-width-container">
			<h5 id="support-header">Help Desk</h5>
			<p>If you haven't found an answer to your question in <a href="https://largo.inn.org/guides/">our guides</a>, our support team is ready to help! Please include as much detail as possible in your support ticket and remember to include the following:</p>
			<ul class=" bold italic">
				<li>Only one topic or question per ticket.</li>
				<li>The URL of your website and links to specific pages.</li>
			<a href="mailto:support@inn.org" class="btn support-tkt-btn">Submit a Support Ticket by email</a>
		</div>
	</section>
</div>
<?php get_footer(); ?>
