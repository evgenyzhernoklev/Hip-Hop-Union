(function($) {
  $(document).ready( function() {
    var $window = $(window),
        $body = $('body'),
        $headerContainer = $('.header');



    // search
    $('.toggle-search').on('click', function(e) {
      e.preventDefault();
      var $container = $(this).closest('.navigationWrapper');

      $container.toggleClass('is-active');
    });



    // menu
    var scrollTop = 0,
        indentTop = 200;

    $window.on('load scroll resize', function() {
      scrollTop = $window.scrollTop();

      if (scrollTop > indentTop) {
        $headerContainer.addClass('is-scrolled');
      } else {
        $headerContainer.removeClass('is-scrolled');
      }
    });
  });
})( jQuery );
