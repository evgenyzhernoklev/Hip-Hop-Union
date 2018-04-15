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
    this.window = $(window);
    this.body = $('body');
    this.header = $('.header');
    this.container = $(container);
    this.catalog = this.container.find('.js-glossary-catalog');
    this.catalogLinks = this.catalog.find('.js-glossary-catalog-letter');
    this.catalogList = [];
    this.posts = this.container.find('.js-glossary-post');
    this.postLetters = this.posts.find('.js-glossary-post-letter');
    this.postLettersList = [];
    this.postPositions = [];
    this.init();
  };

  Glossary.prototype.init = function () {
    var self = this;

    this.checkActiveCatalogLettes();
    this.updateCatalogPosition();
    this.collectPostPositions();
    this.checkActiveLetter();

    this.body.on('click', '.js-glossary-catalog-letter', this.scrollToLetter.bind(this));
    this.window.on({
      'scroll': function() {
        self.updateCatalogPosition();
        self.checkActiveLetter();
      },
      'resize': function() {
        self.postPositions = [];
        self.collectPostPositions();
        self.checkActiveLetter();
      }
    });
  }

  Glossary.prototype.checkActiveCatalogLettes = function () {
    var self = this;

    this.catalogLinks.each(function (index, element) {
      self.catalogList.push($(element).text().toLowerCase());
    });
    this.postLetters.each(function (index, element) {
      self.postLettersList.push($(element).text().toLowerCase());
    });

    for (var i = 0; i < this.catalogList.length; i++) {
      var itemIndex = this.postLettersList.indexOf(this.catalogList[i]);

      if (!~itemIndex) {
        this.catalogLinks.eq(i).addClass('is-disabled');
      }
    }

    this.catalogLinksActive = this.catalogLinks.filter(function() {
      return !$(this).hasClass('is-disabled');
    });
  }

  Glossary.prototype.scrollToLetter = function (e) {
    e.preventDefault();
    var letter = $(e.target).text().toLowerCase(),
        targetIndex = this.postLettersList.indexOf(letter);

    if (~targetIndex) {
      var menuHeight = this.header.innerHeight(),
          catalogHeight = this.catalog .innerHeight(),
          targetPosition = this.postLetters.eq(targetIndex)
            .closest('.js-glossary-post')
            .offset().top - menuHeight - catalogHeight;

      $('html, body').animate({
        scrollTop: targetPosition
      }, 700);
    }
  }

  Glossary.prototype.updateCatalogPosition = function () {
    var scrollTop = this.window.scrollTop(),
        headerHeight = this.header.innerHeight(),
        topPosition = this.container.offset().top,
        isFixed = scrollTop + headerHeight >= topPosition;

    this.container.toggleClass('is-fixed', isFixed);
    this.body.toggleClass('with-catalog', isFixed);
  }

  Glossary.prototype.collectPostPositions = function () {
    var self = this;

    this.posts.each(function(index, element) {
      var topPosition = $(element).offset().top,
          elementHeight = $(element).innerHeight(),
          elementPosition = [topPosition, topPosition + elementHeight];

      self.postPositions.push(elementPosition);
    });
  }

  Glossary.prototype.checkActiveLetter = function () {
    var self = this,
        arr = this.postPositions,
        currentPosition = this.window.scrollTop() +
          this.header.innerHeight() + this.catalog.innerHeight();

    arr.forEach(function(item, i, arr) {
      if (currentPosition >= item[0] && currentPosition < item[1]) {
        self.catalogLinksActive.removeClass('is-active');
        self.catalogLinksActive.eq(i).addClass('is-active');
      }
    });
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
