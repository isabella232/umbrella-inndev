jQuery(document).ready( function($) {
	$(".guide-nav a.btn").on('click', function() {
		$('.guide-nav').toggleClass('open');
	});

	$(window).on('resize', function() {
		if ( ! $(window).width() < 768 ) {
			$('.guide-nav').removeClass('open');
		}
	});

	$(window).trigger('resize');
});
