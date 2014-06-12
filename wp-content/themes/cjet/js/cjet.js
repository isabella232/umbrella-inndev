jQuery(document).ready( function($) {

	$(".guide-nav a.btn").on('click', function() {
		$('.guide-nav .guide-tree, .guide-nav .resources').toggle();
	});

	$(window).on('resize', function() {
		if ( $(window).width() < 768 ) {
			$('.guide-nav').addClass('navbar');
			$('.guide-nav .guide-tree, .guide-nav .resources').hide();
		} else {
			$('.guide-nav').removeClass('navbar');
			$('.guide-nav .guide-tree, .guide-nav .resources').show();
		}
	});

	$(window).trigger('resize');

});