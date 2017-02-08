<?php
/**
 * Template Name: Support Page
 */
get_header();
$top_page = FALSE;

?>
 <div id="content" class="homepage-content clearfix span12">
 	<span id="page-title"><h2>Support</h2></span>
 	<section id="self-help" class="clearfix">
	 	<div class="max-width-container">
			<h5>Self-Help</h5>
			<div id="knowledge-base" class="clearfix">
				<h2><a href="http://support.largoproject.org/support/solutions">Knowledge Base</p></h2></a>
				<p>Dive into our knowledge base for our most popular questions and answers.</p>
				<div class="row-fluid">
					<div class="span2">
						<a href="http://support.largoproject.org/support/solutions/articles/14000024125-how-can-i-improve-the-load-time-of-my-site-">How can I improve the load time of my site?</a>
					</div>
					<div class="span2">
						<a href="http://support.largoproject.org/support/solutions/articles/14000019997-i-lost-my-password-how-can-i-find-it-">I lost my password. How can I find it?</a>
					</div>
					<div class="span2">
						<a href="http://support.largoproject.org/support/solutions/articles/14000018948-how-can-i-make-a-post-sticky-">How can I make a post sticky?</a>
					</div>
					<div class="span2">
						<a href="http://support.largoproject.org/support/solutions/articles/14000018942-how-can-i-add-a-link-to-a-menu-">How can I add a link to a menu?</a>
					</div>
				</div>
			</div>
			<div id="user-guides">
				<div class="span6">
					<h2>User Guides</h2>
					<p>Isitatibus is magnis pro idebit, sundeles re moloreicae veliam res as estemque volumet remqui occusti ustias evendun.</p>
				</div>
				<div class="span5">
					<a href="https://largo.readthedocs.io/developers/index.html" class="btn user-guide-btn" id="btn-developer">For Developers</a>
					<a href="https://largo.inn.org/guides/administrators/" class="btn user-guide-btn" id="btn-admin">For Administrators</a>
					<a href="https://largo.inn.org/guides/authors/" class="btn user-guide-btn" id="btn-author">For Authors</a>
				</div>
			</div>
		</div>
	</section>
	<section id="consulting-services" class="clearfix">
		<div class="max-width-container">
			<h5 id="support-header">Consulting Services</h5>
			<div class="row-fluid">
				<div class="span6">
					<a href="https://nerds.inn.org/category/office-hours/"><h2>Office Hours</h2></a>
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
			<h4>We offer affordable consulting services (at an even more discounted rate for members). Our team can provide:</h4>
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
			<div class="row-fluid">
				<div class="span7">
					<h3>Help Desk</h3>
					<p>Faceperis est, que volesto ex evel molupta nonsercidi bea sedi il ipsa dustiaectem fugit faceste nis eum atioreictia ist porepratatin.</p>
				</div>
				<div class="span4">
					<a href="http://support.largoproject.org/support/tickets/new" class="btn support-tkt-btn">Submit a Support Ticket</a>
				</div>
			</div>
		</div>
	</section>
</div>
<?php get_footer(); ?>