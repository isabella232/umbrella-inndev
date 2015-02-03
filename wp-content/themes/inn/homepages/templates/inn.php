<?php $img_path = get_stylesheet_directory_uri() . '/homepages/assets/img/'; ?>

<section id="hero">
	<div class="content">
		<h1>Institute for Nonprofit News</h1>
		<h3>Supporting mission-driven journalism.</h3>
	</div>
</section>

<section id="about" class="normal">
	<div class="content">
		<h3>About INN</h3>
		<div class="row-fluid">
			<div class="span4">
				<img class="icon" src="<? echo $img_path . 'icons/missionglobe.svg'; ?>" />
				<h5>Mission</h5>
				<p>What we do and all of that stuff</p>
			</div>
			<div class="span4">
				<img class="icon" src="<? echo $img_path . 'icons/people.svg'; ?>" />
				<h5>People</h5>
				<p>What we do and all of that stuff</p>
			</div>
			<div class="span4">
				<img class="icon" src="<? echo $img_path . 'icons/news.svg'; ?>" />
				<h5>News</h5>
				<p>What we do and all of that stuff</p>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span4">
				<img class="icon" src="<? echo $img_path . 'icons/financial.svg'; ?>" />
				<h5>Financial</h5>
				<p>What we do and all of that stuff</p>
			</div>
			<div class="span4">
				<img class="icon" src="<? echo $img_path . 'icons/legal.svg'; ?>" />
				<h5>Legal</h5>
				<p>What we do and all of that stuff</p>
			</div>
			<div class="span4">
				<img class="icon" src="<? echo $img_path . 'icons/contact.svg'; ?>" />
				<h5>Contact</h5>
				<p>What we do and all of that stuff</p>
			</div>
		</div>
	</div>
</section>

<section id="email" class="normal">
	<div class="content">
		<img class="mail-icon" src="<? echo $img_path . 'icons/mail.svg'; ?>" />
		<h3>Getcha some email</h3>
		<p>in your inbox, duh. <strong>Catch it!</strong></p>

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
</section>

<section id="programs" class="normal">
	<div class="content">
		<h3>Programs</h3>
		<div class="row-fluid">
			<div class="span3">
				<img class="icon" src="<? echo $img_path . 'icons/missionglobe.svg'; ?>" />
				<h5>Mission</h5>
				<p>What we do and all of that stuff</p>
			</div>
			<div class="span3">
				<img class="icon" src="<? echo $img_path . 'icons/people.svg'; ?>" />
				<h5>People</h5>
				<p>What we do and all of that stuff</p>
			</div>
			<div class="span3">
				<img class="icon" src="<? echo $img_path . 'icons/news.svg'; ?>" />
				<h5>News</h5>
				<p>What we do and all of that stuff</p>
			</div>
			<div class="span3">
				<img class="icon" src="<? echo $img_path . 'icons/news.svg'; ?>" />
				<h5>News</h5>
				<p>What we do and all of that stuff</p>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span3">
				<img class="icon" src="<? echo $img_path . 'icons/missionglobe.svg'; ?>" />
				<h5>Mission</h5>
				<p>What we do and all of that stuff</p>
			</div>
			<div class="span3">
				<img class="icon" src="<? echo $img_path . 'icons/people.svg'; ?>" />
				<h5>People</h5>
				<p>What we do and all of that stuff</p>
			</div>
			<div class="span3">
				<img class="icon" src="<? echo $img_path . 'icons/news.svg'; ?>" />
				<h5>News</h5>
				<p>What we do and all of that stuff</p>
			</div>
			<div class="span3">
				<img class="icon" src="<? echo $img_path . 'icons/news.svg'; ?>" />
				<h5>News</h5>
				<p>What we do and all of that stuff</p>
			</div>
		</div>
	</div>
</section>

<section id="members" class="interstitial">
	<h3>Our Members</h3>
	<?php the_widget ( 'members_widget' ); ?>
</section>

<section id="member-info" class="normal">
	<div class="content">
		<h3>Membership Info</h3>
		<div class="row-fluid">
			<div class="span3">
				<img class="icon" src="<? echo $img_path . 'icons/missionglobe.svg'; ?>" />
				<h5>Member Benefits</h5>
				<p>What we do and all of that stuff</p>
			</div>
			<div class="span3">
				<img class="icon" src="<? echo $img_path . 'icons/people.svg'; ?>" />
				<h5>Membership Standards</h5>
				<p>What we do and all of that stuff</p>
			</div>
			<div class="span3">
				<img class="icon" src="<? echo $img_path . 'icons/news.svg'; ?>" />
				<h5>How To Join</h5>
				<p>What we do and all of that stuff</p>
			</div>
			<div class="span3">
				<img class="icon" src="<? echo $img_path . 'icons/news.svg'; ?>" />
				<h5>FAQ</h5>
				<p>What we do and all of that stuff</p>
			</div>
		</div>
	</div>
</section>

<section id="testimonial" class="normal">
	<div class="content">
		<img class="mail-icon" src="<? echo $img_path . 'icons/mail.svg'; ?>" />
		<p><strong>I love INN</strong> because it is the best and helped me do stuff! Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed gravida justo varius arcu accumsan eleifend. Etiam est libero, porta eu molestie sed, sollicitudin eget lorem.</p>
		<p class="credit">&ndash; Bob Smith, The News Organization</p>
		<a class="btn">Learn more about how great INN is</a>
	</div>
</section>

<section id="supporters" class="normal">
	<div class="content">
		<h3>INN Thanks Our Supporters</h3>
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
