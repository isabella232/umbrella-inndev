<?php
/**
 * Template Name: Landing Page (For Members, For Funders)
 * Description: Template for member and funder info.
 */
get_header();
?>

<section class="normal">
	<?php if (is_page('for-members')) { ?>
	<div class="row-fluid">
		<div class="span12">
			<h3><span><?php echo $post->post_title; ?></span></h3>
			<div class="page-content"><?php echo wpautop($post->post_content); ?></div>
		</div>
	</div>
	<div id="quick-links" class="row-fluid">
		<div class="span12">
			<h4>Quick links</h4>
			<ul>
				<li><a href="#">INN-Staff+Board</a></li>
				<li><a href="#">Pay Your Dues</a></li>
				<li><a href="#">FAQs</a></li>
				<li><a href="#">Member Directory</a></li>
			</ul>
		</div>
	</div>
	<div id="news-and-benefits" class="row-fluid">
		<div id="news" class="span7">
			<?php the_widget('largo_recent_posts_widget', array(
					'thumbnail_display' => false,
					'num_posts' => 5,
					'title' => 'Latest INN News',
					'show_byline' => true
				),
				array(
					'before_title' => '<h4>',
					'after_title' => '</h4>'
				)
			); ?>
		</div>
		<div id="benefits" class="span5">
			<h4>Member Benefits</h4>
			<ul>
				<li><a href="#">Technology training and web hosting</a></li>
				<li><a href="#">Revenue Generation and Cost-Savings Opportunities</a></li>
				<li><a href="#">Editorial Collaboration</a></li>
				<li><a href="#">Fiscal Sponsorship</a></li>
				<li><a href="#">Third-Party Resources: Software Insurance, Legal Advice</a></li>
				<li><a href="#">Marketing and Public Relations</a></li>
				<li><a href="#">Networking and Information Resources</a></li>
			</ul>
			<a class="learn-more" href="#">Learn more about member benefits</a>
		</div>
	</div>
	<?php } ?>
</section>

<section class="interstitial">
	<h3><a href="#"><span>Not a member yet?</span></a></h3>
</section>

<section class="normal">
	<h3><a href="#"><span>Our programs</span></a></h3>
</section>

<?php get_footer();
