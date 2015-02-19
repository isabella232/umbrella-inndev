<?php $img_path = get_stylesheet_directory_uri() . '/homepages/assets/img/'; ?>

<section id="hero">
	<div class="content">
		<h2>Supporting mission-driven journalism.</h2>
	</div>
</section>

<section id="about" class="normal">
	<div class="content">
		<h3><span>About INN</span></h3>
		<div class="row-fluid">
			<div class="span4">
				<a href="/about/"><img src="<? echo $img_path . 'icons/missionglobe.svg'; ?>" /></a>
				<h5><a href="/about/">Mission</a></h5>
				<p>How we started, what we do and why</p>
			</div>
			<div class="span4">
				<a href="/people/"><img src="<? echo $img_path . 'icons/people.svg'; ?>" /></a>
				<h5><a href="/people/">People</a></h5>
				<p>Our staff and board</p>
			</div>
			<div class="span4">
				<a href="/news/"><img src="<? echo $img_path . 'icons/news.svg'; ?>" /></a>
				<h5><a href="/news/">News</a></h5>
				<p>The latest news about our programs</p>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span4">
				<a href="/financials/"><img src="<? echo $img_path . 'icons/financial.svg'; ?>" /></a>
				<h5><a href="/financials/">Financial</a></h5>
				<p>How we're funded, tax forms and our major donors</p>
			</div>
			<div class="span4">
				<a href="/legal/"><img src="<? echo $img_path . 'icons/legal.svg'; ?>" /></a>
				<h5><a href="/legal/">Legal</a></h5>
				<p>The fine print, bylaws, policies and more</p>
			</div>
			<div class="span4">
				<a href="/contact/"><img src="<? echo $img_path . 'icons/contact.svg'; ?>" /></a>
				<h5><a href="/contact/">Contact</a></h5>
				<p>We'd love to hear from you</p>
			</div>
		</div>
	</div>
</section>

<section id="email" class="interstitial">
	<div class="content">
		<img class="mail-icon" src="" />
		<div class="content-inner">
			<h3>Stay Up To Date</h3>
			<p>News from INN and our members. <strong>Delivered weekly.</strong></p>

			<form action="//investigativenewsnetwork.us1.list-manage.com/subscribe/post?u=81670c9d1b5fbeba1c29f2865&amp;id=e3e0b6be7f" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
			    <div id="mc_embed_signup_scroll">
					<div class="mc-field-group">
						<input type="email" value="email address" name="EMAIL" class="required email" id="mce-EMAIL">
					</div>
					<div id="mce-responses" class="clear">
						<div class="response" id="mce-error-response" style="display:none"></div>
						<div class="response" id="mce-success-response" style="display:none"></div>
					</div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
				    <div style="position: absolute; left: -5000px;"><input type="text" name="b_81670c9d1b5fbeba1c29f2865_e3e0b6be7f" tabindex="-1" value=""></div>
				    <input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="btn">
				</div>
			</form>
		</div>
	</div>
</section>

<section id="programs" class="normal">
	<div class="content">
		<h3><span>What We Offer</span></h3>
		<?php
			$terms = get_terms( 'pauinn_project_tax', array( 'hide_empty' => false ) );

			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {

				echo '<div class="row-fluid">';

				$count = 1;

				foreach ( $terms as $term ) {

					$post = get_posts(array(
						'name' => $term->slug,
						'posts_per_page' => 1,
						'post_type' => 'pauinn_project',
						'post_status' => 'publish'
					));
					?>

					<div class="span4">
						<?php echo '<a href="' . get_permalink( $post[0]->ID ) . '">' . get_the_post_thumbnail( $post[0]->ID ) . '</a>'; ?>
						<?php echo '<h5><a href="' . get_permalink( $post[0]->ID ) . '">' .  get_the_title( $post[0]->ID ) . '</a></h5>'; ?>
						<?php echo '<p>' . $post[0]->post_excerpt . '</p>'; ?>
					</div>

					<?php
					if ( $count % 3 == 0 ) {
						echo '</div><div class="row-fluid">';
					}
					$count++;
				}

				echo '</div>';
			}
		?>
	</div>
</section>

<section id="hire-us" class="interstitial">
	<div class="content">
		<h3>Need a little extra help?</h3>
		<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed gravida justo varius arcu accumsan eleifend. Etiam est libero, porta eu molestie sed, sollicitudin eget lorem.</p>
		<div class="row-fluid">
			<div class="span4">
				<img class="icon" src="<? echo $img_path . 'icons/missionglobe.svg'; ?>" />
				<h5>Member Benefits</h5>
				<p>Exclusive access to discounts and programs</p>
			</div>
			<div class="span4">
				<img class="icon" src="<? echo $img_path . 'icons/missionglobe.svg'; ?>" />
				<h5>Member Benefits</h5>
				<p>Exclusive access to discounts and programs</p>
			</div>
			<div class="span4">
				<img class="icon" src="<? echo $img_path . 'icons/missionglobe.svg'; ?>" />
				<h5>Member Benefits</h5>
				<p>Exclusive access to discounts and programs</p>
			</div>
		</div>
		<a class="btn btn-primary">Learn more about how great INN is</a>
	</div>
</section>

<section id="members">
	<h3><a href="/members/"><span>Our Members</span></a></h3>
	<?php the_widget ( 'members_widget' ); ?>
</section>

<section id="member-info" class="normal">
	<div class="content">
		<h3><span>Membership Info</span></h3>
		<div class="row-fluid">
			<div class="span4">
				<a href="/for-members/member-benefits/"><img class="icon" src="<? echo $img_path . 'icons/missionglobe.svg'; ?>" /></a>
				<h5><a href="/for-members/member-benefits/">Member Benefits</a></h5>
				<p>Exclusive access to discounts and programs</p>
			</div>
			<div class="span4">
				<a href="/for-members/become-a-member/"><img class="icon" src="<? echo $img_path . 'icons/news.svg'; ?>" /></a>
				<h5><a href="/for-members/become-a-member/">Become a Member</a></h5>
				<p>Fill out an application</p>
			</div>
			<div class="span4">
				<a href="/for-members/membership-faqs/"><img class="icon" src="<? echo $img_path . 'icons/news.svg'; ?>" /></a>
				<h5><a href="/for-members/membership-faqs/">FAQs</a></h5>
				<p>Answers to all your questions</p>
			</div>
		</div>
	</div>
</section>

<section id="testimonial" class="interstitial">
	<div class="content">
		<img class="mail-icon" src="<? echo $img_path . 'icons/mail.svg'; ?>" />
		<p><strong>I love INN</strong> because it is the best and helped me do stuff! Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed gravida justo varius arcu accumsan eleifend. Etiam est libero, porta eu molestie sed, sollicitudin eget lorem.</p>
		<p class="credit">&ndash; Bob Smith, The News Organization</p>
		<a class="btn">Learn more about how great INN is</a>
	</div>
</section>

<section id="supporters" class="normal">
	<div class="content">
		<h3><span>INN Thanks Our Supporters</span></h3>
		<div class="row-fluid">
			<ul class="span4">
				<li><a href="http://www.atlanticphilanthropies.org/">Atlantic Philanthropies</a></li>
				<li><a href="http://democracyfund.org/">Democracy Fund</a></li>
				<li><a href="http://www.hewlett.org/">The William and Flora Hewlett Foundation</a></li>
				<li><a href="http://www.pclbfoundation.org/">The Peter and Carmen Lucia Buck Foundation</a></li>
				<li>Buzz Woolley</li>
			</ul>
			<ul class="span4">
				<li><a href="http://www.journalismfoundation.org/default.asp">Ethics and Excellence in Journalism Foundation</a></li>
				<li><a href="http://www.knightfoundation.org/">The John S. and James L. Knight Foundation</a></li>
				<li><a href="http://www.macfound.org/">John D. &amp; Catherine T. MacArthur Foundation</a></li>
				<li><a href="http://www.mccormickfoundation.org/">Robert R. McCormick Foundation</a></li>
				<li>Karin Winner</li>
			</ul>
			<ul class="span4">
				<li><a href="http://www.opensocietyfoundations.org/">Open Society Foundations</a></li>
				<li><a href="http://www.thepattersonfoundation.org/">The Patterson Foundation</a></li>
				<li><a href="http://rbf.org/">Rockefeller Brothers Fund</a></li>
				<li><a href="/about/people/">The INN Board</a></div></li>
			</ul>
		</div>
	</div>
</section>
