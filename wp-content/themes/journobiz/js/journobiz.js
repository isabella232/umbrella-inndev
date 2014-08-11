jQuery(document).ready(function($) {

	// timelines on grantee pages
	$('.timeline-control, .timeline .close').click(function() {
		$('.timeline').slideToggle('fast');
	});


	// Newsletter signup form interaction
	$('#site-header .newsletter-signup .email_address').focus(function() {
		$(this).siblings('.first_name, .last_name, .submit').show();
	});

	$(document).mouseup(function(e) {
		var container = $("#site-header .newsletter-signup");
		if (!container.is(e.target) && container.has(e.target).length === 0)
			container.find('.first_name, .last_name, .submit, .error').hide();
	});

	$('#site-header .newsletter-signup form').submit(function() {
		var valid = true;
		$('#site-header .newsletter-signup .error').hide();
		$(this).find('input').each(function(){
			if (!$(this)[0].checkValidity()) {
				valid = false;
				$(this).focus();
				$('#site-header .newsletter-signup .error')
					.text('Please complete the signup form.')
					.fadeIn(250);
				return false;
			}
		});
		if (!valid)
			return false;
		else {
			$("#site-header .newsletter-signup").find('.first_name, .last_name, .submit, .error').hide();
			$('#site-header .newsletter-signup input.submit').attr({ disabled: 'disabled', value: 'Submitted' });
		}
	});

});