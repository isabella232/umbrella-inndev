<?php
/**
 * Template Name: For Members, For Funders
 * Description: Template for member and funder info.
 */
get_header();
?>

<section class="normal">
	<h3><span><?php echo $post->post_title; ?></span></h3>
	<div class="page-content"><?php echo $post->post_content; ?></div>
	<div class="quick-links">
		<ul>
			<li><a href="#">INN-Staff+Board</a></li>
			<li><a href="#">Pay Your Dues</a></li>
			<li><a href="#">FAQs</a></li>
			<li><a href="#">Member Directory</a></li>
		</ul>
	</div>
</section>

<section class="interstitial">
	<h3><a href="#"><span>Not a member yet?</span></a></h3>
</section>

<?php get_footer();
