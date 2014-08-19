jQuery(document).ready( function($) {

	//membership widget behaviors
	$(".inn-members-widget .members a").on( 'click', function( ) {
		var member_url = this.href,
			the_link = this;
		$(this).addClass('icon-spinner');
		$('.member-details').load( member_url + " article.inn_member", function ( resp, status, xhr ) {
			$(this).parent().fadeIn('fast');
			$("body").addClass('member-widget-open');
			$(the_link).removeClass('icon-spinner');
		});
		return false;
	});

	$(".member-details-wrapper .close").on( 'click', function() {
		$(this).parent().fadeOut('fast');
		$("body").removeClass('member-widget-open');
	});

	$(".members-menu a").each( function() {
		if ( this.href ==  window.location.href.slice(0, -1) || this.href == window.location.href ) {
			$(this).parent().remove();
		}
	});

	// Adding classes to header social for CSS hiding
	$('#header-social i').each( function() {
		$(this).closest('li').addClass( $(this).attr('class') + '-parent' );
	});

	// Visibility toggling, mostly for member contact forms
	$('.toggle').on('click', function() {
		target = $(this).data('toggler');
		$( target ).slideToggle('fast');
	});

	//hide the member contact form, this is a hack to get around weird paupress sizing
	$('.toggle').trigger('click');

	// Submitting links for membership directory states
	$('.member-nav select').on('change', function() {
		window.location = $(this).val();
	});


});