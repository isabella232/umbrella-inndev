(function() {
	var $ = jQuery,
		subNavHideTimeout;

	var displaySubNav = function(subNav) {
		var navSelector = '.network-header nav',
			subNavContainer = $(navSelector).find('.sub-nav-container');

		if (!subNavContainer.length) {
			$(navSelector).find('.network-header-main-nav').after('<div class="sub-nav-container" />');
			subNavContainer = $(navSelector).find('.sub-nav-container');
		}

		subNavContainer.html(subNav.html());
		subNavContainer.slideDown(350);
	}

	var hideSubNav = function() {
		var navSelector = '.network-header nav',
		subNavContainer = $(navSelector).find('.sub-nav-container');

		if (!subNavContainer.length)
			return false;

		subNavContainer.slideUp(250);
	};

	$(function() {
		$('ul.network-header-main-nav > li').on('mouseover', function(e) {
			if (subNavHideTimeout)
				clearTimeout(subNavHideTimeout);

			var subNav = $(this).find('.network-header-sub-nav');

			if (subNav.length)
				displaySubNav(subNav);

			return false;
		});

		$('.network-header').on('mouseleave', function(e) {
			if (subNavHideTimeout)
				clearTimeout(subNavHideTimeout);

			subNavHideTimeout = setTimeout(hideSubNav, 500);

			return false;
		});
	});
})();
