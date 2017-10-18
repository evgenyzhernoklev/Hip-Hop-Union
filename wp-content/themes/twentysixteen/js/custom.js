(function($) {
  $(document).ready( function() {
    $('.toggle-search').on('click', function(e) {
      e.preventDefault();
      var $container = $(this).closest('.navigationWrapper');

      $container.toggleClass('is-active');
    });
  });
})( jQuery );
