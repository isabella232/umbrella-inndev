(function() {
  var $ = jQuery,
      phoneBreakPoint = 420,
      subNavHideTimeout,
      intentTimeout,
      mobileNavContainer,
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
    $(this).addClass('open');
    mobileNavContainer.find('ul').html(
      $(navSelector).find('.network-header-main-nav').html());
    mobileNavContainer.addClass('show');
  };

  var mobileHideMenu = function() {
    $(this).removeClass('open');
    mobileNavContainer.removeClass('show');
    $(document).off('touchstart');
  };

  var setupMobile = function() {
    $('body').append('<div class="mobile-nav-container"><ul></ul></div>');
    mobileNavContainer = $('.mobile-nav-container');
    $('.mobile-toggle').toggle(mobileShowMenu, mobileHideMenu);
    $('.mobile-nav-container').on('touchmove', function(e) {
      if ($(this).height() >= $(this)[0].scrollHeight) {
        e.stopPropagation();
        return false;
      }
    });
  };

  $(function() {
    // Mobile stuff
    setupMobile();

    // Desktop
    bindEvents();
  });
})();
