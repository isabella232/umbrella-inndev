<?php
/**
 * Template Name: Landing Page (For Members, For Funders)
 * Description: Template for member and funder info.
 */
get_header();

$img_path = get_stylesheet_directory_uri() . '/images/';
?>

<section class="normal container">
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
				<li><a href="/about/people/">INN Staff+Board</a></li>
				<li><a href="/for-members/dues/">Pay Your Dues</a></li>
				<li><a href="/about/membership-faqs/">FAQs</a></li>
				<li><a href="/members">Member Directory</a></li>
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
				<li>Technology training and web hosting</li>
				<li>Revenue Generation and Cost-Savings Opportunities</li>
				<li>Editorial Collaboration</li>
				<li>Fiscal Sponsorship</li>
				<li>Third-Party Resources: Software Insurance, Legal Advice</li>
				<li>Marketing and Public Relations</li>
				<li>Networking and Information Resources</li>
			</ul>
			<a class="learn-more" href="/for-members/member-benefits/">Learn more about member benefits</a>
		</div>
	</div>
	<?php } ?>
</section>

<section id="membership-callout" class="interstitial branding">
	<div class="content">
		<img class="member-icon" src="<? echo $img_path . 'red_boxes.png'; ?>" />
		<div class="content-inner">
			<h3>Not a member yet?</h3>
			<p>Join the growing community of sustainable nonprofit news organizations that are changing the way we do journalism.</p>
			<a class="btn" href="/for-members/become-a-member/">Learn more</a>
		</div>
	</div>
</section>

<?php get_template_part('partials/programs'); ?>

<?php get_footer();
