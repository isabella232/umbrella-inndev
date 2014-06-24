(function() {
	var $ = jQuery,
		subNavHideTimeout,
    intentTimeout;

	var displaySubNav = function(subNav) {
		var navSelector = '.network-header nav',
			subNavContainer = $(navSelector).find('.sub-nav-container');

		if (!subNavContainer.length) {
			$(navSelector).find('.network-header-main-nav').after('<div class="sub-nav-container" />');
			subNavContainer = $(navSelector).find('.sub-nav-container');
		}

    if (subNav.length) {
      subNavContainer.html(subNav.html());
      subNavContainer.slideDown(350);
    } else
      subNavContainer.slideUp(250);
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

      if (intentTimeout)
        clearTimeout(intentTimeout);

			var subNav = $(this).find('.network-header-sub-nav');
      intentTimeout = setTimeout(displaySubNav.bind(null, subNav), 250);

			return false;
		});

		$('.network-header').on('mouseleave', function(e) {
			if (subNavHideTimeout)
				clearTimeout(subNavHideTimeout);

      if (intentTimeout)
        clearTimeout(intentTimeout);

			subNavHideTimeout = setTimeout(hideSubNav, 500);

			return false;
		});
	});
})();
