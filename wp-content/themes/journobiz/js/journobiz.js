jQuery(document).ready(function($) {

	$('.timeline-control, .timeline .close').click(function() {
		$('.timeline').slideToggle('fast');
	});

});