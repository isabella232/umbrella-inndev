<?php
/**
 * INNovation mailchimp newsletter shortcodes
 *
 * @link https://github.com/INN/umbrella-inndev/issues/71
 * @since 0.2
 */

namespace INN\Plugin\INNovation;

/**
 * The shortcode for the INNovation Newsletter archive
 *
 * @param array $atts unused
 * @param string $content unused
 * @param string $tag unused
 * @since 0.2
 */
function innovation_newsletter_archive( $atts, $content, $tag ) {
	$return = <<<'EOT'
<!-- innovation_newsletter_archive; see https://github.com/INN/umbrella-inndev/tree/master/wp-content/plugins/inn-misc-functionality -->
<script language="javascript" src="//inn.us1.list-manage.com/generate-js/?u=81670c9d1b5fbeba1c29f2865&fid=9&show=10" type="text/javascript"></script>
EOT;
	return $return;
}
add_shortcode( 'innovation_newsletter_archive', __NAMESPACE__ . '\\innovation_newsletter_archive' );

/**
 * The shortcode for the INNovation Newsletter signup form
 *
 * @param array $atts unused
 * @param string $content unused
 * @param string $tag unused
 * @since 0.2
 */
function innovation_newsletter_form( $atts, $content, $tag ) {
	$return = <<<'EOT'
<!-- innovation_newsletter_form; see https://github.com/INN/umbrella-inndev/tree/master/wp-content/plugins/inn-misc-functionality -->
<link href="//cdn-images.mailchimp.com/embedcode/classic-10_7.css" rel="stylesheet" type="text/css">
<style type="text/css">
    #mc_embed_signup{background:#fff; clear:left; font:14px Helvetica,Arial,sans-serif; }
    #mc_embed_signup .mc-field-group li label{ vertical-align: middle; margin-left: 0.5em; }
    #mc_embed_signup .mc-field-group.input-group input { vertical-align: middle; }
    #mc_embed_signup .mc-field-group.input-group input[type="text"] { height: 3em; }
    #mc_embed_signup form { padding: 0; }
</style>
<div id="mc_embed_signup">
	<form action="https://inn.us1.list-manage.com/subscribe/post?u=81670c9d1b5fbeba1c29f2865&amp;id=19bec3393e" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
		<div id="mc_embed_signup_scroll">
		<h2>Subscribe</h2>
		<div class="indicates-required"><span class="asterisk">*</span> indicates required</div>
		<div class="mc-field-group">
			<label for="mce-EMAIL">Email Address  <span class="asterisk">*</span>
			</label>
			<input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL">
		</div>
		<div class="mc-field-group">
			<label for="mce-FNAME">First Name </label>
			<input type="text" value="" name="FNAME" class="" id="mce-FNAME">
		</div>
		<div class="mc-field-group">
			<label for="mce-LNAME">Last Name </label>
			<input type="text" value="" name="LNAME" class="" id="mce-LNAME">
		</div>
		<div class="mc-field-group">
			<label for="mce-MMERGE3">Organization </label>
			<input type="text" value="" name="MMERGE3" class="" id="mce-MMERGE3">
		</div>
		<div class="mc-field-group">
			<label for="mce-MMERGE4">Job Title </label>
			<input type="text" value="" name="MMERGE4" class="" id="mce-MMERGE4">
		</div>
		<div class="mc-field-group input-group">
			<label for="MMERGE5">Do you work for an INN member organization? </label>
			<ul>
				<li><input type="radio" value="Yes" name="MMERGE5" id="mce-MMERGE5-0"><label for="mce-MMERGE5-0">Yes</label></li>
				<li><input type="radio" value="No" name="MMERGE5" id="mce-MMERGE5-1"><label for="mce-MMERGE5-1">No</label></li>
			</ul>
		</div>
		<div class="mc-field-group">
			<label for="mce-MMERGE8">What newsletter information are you interested in? </label>
			<input type="text" value="" name="MMERGE8" class="" id="mce-MMERGE8">
		</div>
		<div class="mc-field-group input-group">
			<label for="group[4101]">Who are you? </label>
			<ul><li><input type="radio" value="1" name="group[4101]" id="mce-group[4101]-4101-0"> <label for="mce-group[4101]-4101-0">INN Member</label></li>
				<li><input type="radio" value="2" name="group[4101]" id="mce-group[4101]-4101-1"> <label for="mce-group[4101]-4101-1">Funder</label></li>
				<li><input type="radio" value="4" name="group[4101]" id="mce-group[4101]-4101-2"> <label for="mce-group[4101]-4101-2">I am not an INN Member/Funder, but I am interested in nonprofit news.</label></li>
			</ul>
		</div>
		<div id="mce-responses" class="clear">
			<div class="response" id="mce-error-response" style="display:none"></div>
			<div class="response" id="mce-success-response" style="display:none"></div>
		</div>

		<div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_81670c9d1b5fbeba1c29f2865_19bec3393e" tabindex="-1" value=""></div>

		<div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
		</div>
	</form>
</div>
<script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script>
<script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';fnames[3]='MMERGE3';ftypes[3]='text';fnames[4]='MMERGE4';ftypes[4]='text';fnames[5]='MMERGE5';ftypes[5]='radio';fnames[6]='INDEXUSER';ftypes[6]='text';fnames[7]='INDEXPASS';ftypes[7]='text';fnames[8]='MMERGE8';ftypes[8]='text';}(jQuery));var $mcj = jQuery.noConflict(true);</script>
EOT;
	return $return;
}
add_shortcode( 'innovation_newsletter_form', __NAMESPACE__ . '\\innovation_newsletter_form' );
