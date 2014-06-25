(function() {
  var $ = jQuery,
      phoneBreakPoint = 420,
      subNavHideTimeout,
      intentTimeout,
      navSelector = '.network-header nav',
      subNavContainer = $(navSelector).find('.sub-nav-container');

  var displaySubNav = function(subNav, name) {
    if (!subNavContainer.length) {
      $(navSelector).find('.network-header-main-nav').after('<div class="sub-nav-container" />');
      subNavContainer = $(navSelector).find('.sub-nav-container');
    }

    if (subNav.length) {
      subNavContainer.data('menu-name', name);
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

    subNavContainer.slideUp(250, function() {
      $('ul.network-header-main-nav > li').removeClass('active');
    });
  };

  var subNavIsVisible = function(name) {
    return $('.sub-nav-container').is(':visible') && $('.sub-nav-container').data('menu-name') == name;
  };

  // Desktop
  var showMenu = function(e) {
    $(this).siblings().removeClass('active');
    $(this).addClass('active');

    if (subNavHideTimeout)
      clearTimeout(subNavHideTimeout);

    if (intentTimeout)
      clearTimeout(intentTimeout);

    var menuName = $(this).find('> a').text(),
        subNav = $(this).find('.network-header-sub-nav');

    if (!subNavIsVisible(menuName) && subNav.length) {
      intentTimeout = setTimeout(displaySubNav.bind(null, subNav, menuName), 250);
      return false;
    }

    if (!subNav.length)
      subNavContainer.slideUp(250);
  };

  var onMouseLeave = function(e) {
    if (subNavHideTimeout)
      clearTimeout(subNavHideTimeout);

    if (intentTimeout)
      clearTimeout(intentTimeout);

    subNavHideTimeout = setTimeout(hideSubNav, 500);

    return false;
  };

  var bindEvents = function() {
    $('ul.network-header-main-nav > li').on('mouseover', showMenu);
    $('ul.network-header-main-nav > li').on('touchstart', showMenu);
    $('.network-header').on('mouseleave', onMouseLeave);
  };

  var unbindEvents = function() {
    $('ul.network-header-main-nav > li').off();
    $('.network-header').off();
  };

  // Mobile
  var mobileShowMenu = function() {
    var subNav = $(this).parent().find('.network-header-sub-nav');
    if (subNav.length && !subNav.is(':visible')) {
      $(this).parent().siblings().find('.network-header-sub-nav').removeClass('visible');
      $(this).parent().find('.network-header-sub-nav').addClass('visible');
      return false;
    }

    window.location.href = $(this).attr('href');
    return true;
  };

  var mobileSubNavClick = function() {
    window.location.href = $(this).attr('href');
    return true;
  };

  var bindMobileEvents = function() {
    $('.network-header ul.network-header-main-nav > li.menu-item > a').on('touchstart', mobileShowMenu);
    $('.network-header ul.network-header-main-nav > li.menu-item > a').on('click', mobileShowMenu);

    $('.network-header ul.network-header-main-nav > li.menu-item li a').on('touchstart', mobileSubNavClick);
    $('.network-header ul.network-header-main-nav > li.menu-item li a').on('click', mobileSubNavClick);
  };

  var unbindMobileEvents = function() {
    $('.network-header ul.network-header-main-nav > li.menu-item > a').off();
    $('.network-header ul.network-header-main-nav > li.menu-item li a').off();
  };

  $(function() {
    $('.mobile-toggle').click(function() {
      $('.network-header ul.network-header-main-nav > li.menu-item').toggleClass('visible');
    });

    if ($(window).width() <= phoneBreakPoint)
      bindMobileEvents();
    else
      bindEvents();

    var resizeTimeout;
    $(window).on('resize', function() {
      if (resizeTimeout)
        clearTimeout(resizeTimeout);

      resizeTimeout = setTimeout(function() {
        if ($(window).width() <= phoneBreakPoint) {
          unbindEvents();
          bindMobileEvents();
        } else {
          unbindMobileEvents();
          bindEvents();
        }
      }, 500);
    });
  });
})();
