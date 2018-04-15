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
      $headerContainer.toggleClass('search-opened');
    });



    // menu
    var indentTop = 200;

    function checkHeaderScroll() {
      var scrollTop = $window.scrollTop();

      if (scrollTop > indentTop) {
        $headerContainer.addClass('is-scrolled');
      } else {
        $headerContainer.removeClass('is-scrolled');
      }
    }

    checkHeaderScroll();
    $window.on('scroll resize', checkHeaderScroll);

    $('.hamburger').on('click', function() {
      $(this).toggleClass('is-active');
      $('.main-navigation').toggleClass('is-active');
      $('body').toggleClass('menu-opened');
    });

    var $dropdownToggle = $('.dropdown-toggle'),
        $submenu = $('.sub-menu'),
        slidingTime = 300;

    $dropdownToggle.on('click', function (e) {
      $(this).next('.sub-menu').stop().slideToggle(slidingTime);
    });

    $window.on('resize', function () {
      if ($(window).width() > 992) {
        $submenu.show();
      }
    });

    // glossary
    $('.js-glossary').each(function () {
      new Glossary(this);
    });

    // scroll link in material
    $('.scroll-top').each(function () {
      new LinkScroll(this);
    });
  });



  // glossary class
  var Glossary = function (container) {
    this.body = $('body');
    this.container = $(container);
    this.init();
  };

  Glossary.prototype.init = function () {
    this.body.on('click', '.js-glossary-catalog-letter', this.scrollToLetter.bind(this));
  }

  Glossary.prototype.scrollToLetter = function (e) {
    e.preventDefault();
  }



  // link scrolling class
  var LinkScroll = function (element) {
    this.link = $(element);
    this.footer = $('.singleFooterIn');
    this.scrollIndentTop = 1200;
    this.bottomIndent = parseInt(this.link.css('bottom'));
    this.init();
  };

  LinkScroll.prototype.init = function () {
    this.checkLinkPosition();

    this.link.on('click', this.scrollToTop);
    $(window).on('scroll resize', this.checkLinkPosition.bind(this));
  }

  LinkScroll.prototype.checkLinkPosition = function () {
    var scrollTop = $(window).scrollTop(),
        windowHeight = $(window).height(),
        scrollIndentBottom = this.footer.offset().top + this.footer.height(),
        topPosition = this.footer.position().top + this.footer.innerHeight() - this.link.height();

    this.link.toggleClass('is-active', scrollTop > this.scrollIndentTop);

    if (scrollTop + windowHeight - this.bottomIndent > scrollIndentBottom) {
      this.link.addClass('is-absolute');
      this.link.css({
        top: topPosition + 'px',
        bottom: 'auto'
      });
    } else {
      this.link.removeClass('is-absolute');
      this.link.css({
        top: 'auto',
        'bottom': this.bottomIndent
      });
    }
  }

  LinkScroll.prototype.scrollToTop = function (e) {
    e.preventDefault();

    $('html, body').animate({
      scrollTop: 0
    }, 700);
  }
})( jQuery );
