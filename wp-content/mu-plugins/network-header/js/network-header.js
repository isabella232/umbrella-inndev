(function() {
  var $ = jQuery,
  subNavHideTimeout,
  intentTimeout;

  var displaySubNav = function(subNav, name) {
    var navSelector = '.network-header nav',
    subNavContainer = $(navSelector).find('.sub-nav-container');

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

    subNavContainer.slideUp(250);
  };

  var subNavIsVisible = function(name) {
    return $('.sub-nav-container').is(':visible') && $('.sub-nav-container').data('menu-name') == name;
  };

  $(function() {
    var showMenu = function(e) {
      if (subNavHideTimeout)
        clearTimeout(subNavHideTimeout);

      if (intentTimeout)
        clearTimeout(intentTimeout);

      var menuName = $(this).find('> a').text();
      if (!subNavIsVisible(menuName)) {
        var subNav = $(this).find('.network-header-sub-nav');
        intentTimeout = setTimeout(displaySubNav.bind(null, subNav, menuName), 250);
        return false;
      }
    };
    $('ul.network-header-main-nav > li').on('mouseover', showMenu);
    $('ul.network-header-main-nav > li').on('touchstart', showMenu);

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
