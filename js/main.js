(function ($) {
  'use strict';

  // Go to top
  function goTop() {
    if ($(window).width() >= 992) {
      $(window).scroll(function () {
        if ($(window).scrollTop() === 0) {
          $('#go_top').stop().animate({
            right: -45
          }, 0);
        } else {
          $('#go_top').stop().animate({
            right: 0
          }, 0);
        }
      });

      $("#go_top").click(function () {
        $('body,html').animate({
          scrollTop: 0
        }, 800);
        return false;
      });
    }
  };

  // Isotope
  function isotopeInit(){
    $('[data-init="isotope"]').each(function(){
      var $el = $(this);
      var $container = $el.imagesLoaded(function() {
        $container.isotope({
          layoutMode: 'packery',
          itemSelector: '[data-grid="grid-item"]',
          percentPosition: true,
          getSortData: {
            // name: '.name',
            // price: '.price'
          },
          masonry: {
            columnWidth: '.grid-sizer',
          }
        });
      });
    });

    // Count number item
    $('[data-type="filter-isotope"] a').each(function() {
      var $el = $(this),
          _textFilter = $el.data('filter').replace('.',''),
          _length = 0;

      if(_textFilter == "*") {
        _length = $('[data-init="isotope"]').children().length;

        $el.find('.portfolios_count').text('(' + (_length - 1) + ')');
      } else {
        _length = $('[data-init="isotope"]').find('.' + _textFilter).children().length;

        $el.find('.portfolios_count').text('(' + _length + ')');
      }
    });

    // Click filter item
    $('[data-type="filter-isotope"] a').on('click', function(e){
        e.preventDefault();
        var filterValue = $(this).attr('data-filter');
        var sortValue = $(this).attr('data-sort');
        var textValue = $(this).text();

        $(this).parent().find('.current').removeClass('current');
        $(this).addClass('current');

        $(this).parents('.dropdown').find('.btn-name').text(textValue);

        if($(this).parent().hasClass('group-filter')){
          $('[data-init="isotope"]').isotope({
              filter: filterValue
          });
        } else if($(this).parent().hasClass('group-sort')){
          $('[data-init="isotope"]').isotope({
              sortBy: sortValue
          });
        }
    });
  };

  // Masonry
  function masonryInit(){
    var $grid = $('[data-init="masonry"]').imagesLoaded( function() {
      // init Masonry after all images have loaded
      $grid.masonry({
        itemSelector: '[data-grid="grid-item"]',
        columnWidth: '.grid-sizer',
        percentPosition: true
      });
    });
  };

  // ImagesLoaded
  function imagesLoadedInit() {
    var imgLoad = imagesLoaded($('[data-imgloaded="imagesLoaded"]'));
    imgLoad.on('progress', onProgress);
  };

  // triggered after each item is loaded
  function onProgress( imgLoad, image ) {
    // change class if the image is loaded or broken
    var $item = $( image.img ).parents('[class*="__img"]');
    $item.removeClass('is-loading');
    if ( !image.isLoaded ) {
      $item.addClass('is-broken');
    }
  };

  // GALLERY MAGNIFIC POPUP
  function galleryMagnificPopup() {
    $('[data-init="gallery"]').each(function() {
      $(this).magnificPopup({
        delegate: '[data-gal="gallery"] [data-img="popup"]',
        type: 'image',
        gallery:{
          enabled:true
        }
      });
    });
  };

  // VIDEO MAGNIFIC POPUP
  function videoMagnificPopup() {
    $('[data-init="video-popup"]').each(function() {
      $(this).find('a').magnificPopup({
        // disableOn: 400,
        type: 'iframe',
        mainClass: 'mfp-fade',
        removalDelay: 160,
        preloader: false,
        fixedContentPos: false
      });
    });
  };

  // Play Video
  function stmPlayIframeVideo() {
    $('.video-bg').click(function (e) {
      e.preventDefault();

      var addPlay = $(this).parent().find('iframe').attr('src');

      $(this).animate({top: - $(this).outerHeight()}, 500, function(){
        $(this).parent().addClass('open').height($(this).outerHeight());
        $(this).parent().find('iframe').attr('src', addPlay + '?autoplay=1');
      });
    });
  };

  // Slick slider
  function slickInit() {
    $('[data-init="slick"]').each(function () {
      var $el = $(this);

      var breakpointsWidth = {tn: 319, xs: 479, ss: 519, sm: 767, md: 991, lg: 1199};

      var slickDefault = {
        dots: true,
        arrows: false,

        fade: true,
        infinite: true,
        autoplay: true,
        pauseOnHover: true,
        speed: 1000,
        adaptiveHeight: true,

        slidesToShow: 1,
        slidesToScroll: 1,

        mobileFirst: true
      };

      // Merge settings.
      var settings = $.extend(slickDefault, $el.data());
      delete settings.init;

      // Build breakpoints.
      if (settings.breakpoints) {
        var _responsive = [];
        var _breakpoints = settings.breakpoints;

        var buildBreakpoints = function (key, show, scroll) {
          if(show !== 0) {
            if (breakpointsWidth[key] != 1199) {
              _responsive.push({
                breakpoint: breakpointsWidth[key],
                settings: {
                  slidesToShow: parseInt(show),
                  slidesToScroll: parseInt(show),
                  dots: false,
                  arrows: true,
                }
              });
            };
          };
        };

        if (typeof _breakpoints === "object") {
          $.each(_breakpoints, buildBreakpoints);
        }

        delete settings.breakpoints;
        settings.responsive = _responsive;
      }

      // console.log(settings);

      $el.slick(settings);
    });
  };

  // Waypoints
  function waypointEffect(){

    $('[data-waypoint="waypointEffect"]').each(function(index) {
      var waypoints = $('[data-waypoint="waypointEffect"]').waypoint({
        handler: function(direction) {
          if(direction === 'down') {
            $(this).addClass('fadeIn');
          } else {
            $(this).removeClass('fadeIn');
          }
        },
        offset: '90%'
      });
    });

    $('.waypointNavbar').waypoint({
      handler: function(direction) {
        if(direction === 'down') {
          $('#header').find('.navbar').removeClass('affix--bg-white');
        } else {
          $('#header').find('.navbar').removeClass('affix--bg-white');
        }
      },
      offset: 75
    });

    $('.waypointNavbar.navbar--bg-black').waypoint({
      handler: function(direction) {
        if(direction === 'down') {
          $('#header').find('.navbar').addClass('affix--bg-white');
        } else {
          $('#header').find('.navbar').addClass('affix--bg-white');
        }
      },
      offset: 75
    });

    $('.page-title.page-title-single').waypoint({
      handler: function(direction) {
        $('#header').find('.navbar').addClass('navbar-single');
      },
      offset: 0
    });
  };

  $(function () {
    // Init bootstrap plugins.
    $('[data-toggle="popover"]').popover();
    $('[data-toggle="tooltip"]').tooltip();

    // Hoverdir
    $('[data-gal="gallery"] [data-hover="hoverdir"]').each( function() { $(this).hoverdir(); } );

    // Maps
    $('.overlay-map').click(function () {
        $(this).remove();
    });

    // Navbar
    $('.navbar-header').each(function(index, el) {
      var _count = 0;

      // Navbar toggle
      $('.navbar-header .navbar-toggle').click(function(e){
        e.preventDefault();
        var _target = $(this).data('target');

        $(this).stop().toggleClass('on');
        $('body').stop().toggleClass('overHide');
        $(_target).stop().toggleClass('on');
        $(_target).find('.navbar-nav').stop().toggleClass('on');

        if($(this).hasClass('on')) {
          $(_target).stop().animate({ width: '100%' }, 800);
          $('.site-container').stop().animate({ right: '100%' }, 800);
          $('.navbar').addClass('affix-header');
        } else {
          $(_target).stop().animate({ width: 0 }, 800);
          $('.site-container').stop().animate({ right: 0 }, 800);

          removeSpan($('.navbar-nav'));

          $('.navbar').removeClass('affix-header');

          _count = 0;
        }
      });

      // Dropdown menu
      $('.navbar-nav').each(function() {
        var _box = $(this);

        $('.navbar-nav__link').click(function(e) {
          var _target = $(this).data('target');
          var _link = $(this);
          var _text = $(this).html();

          if(_link.parent().children().hasClass('navbar-child')) {
            e.preventDefault();

            _count ++;

            $('.navigation__breadcrumb').append('<span data-target-child="#target-child-'+ _count +'">'+_text+'</span>');
            $(this).attr('id','target-child-'+ _count);

            _link.parent().stop().toggleClass('on');

            _box.stop().animate({left: -(_box.outerWidth() * _count)}, 300);

            $('.navigation__breadcrumb span').each(function(index) {
              $(this).on('click', function() {
                var _target = $(this).data('target-child');

                if(index === 0) {
                  removeSpan(_box);

                  _count = 0;
                } else {
                  _count --;
                  $(this).remove();
                  _box.stop().animate({left: -(_box.outerWidth() * _count)}, 300);
                  $(_target).parent().removeClass('on');
                  $(_target).removeAttr('id');
                }
              });
            });
          }
        });

        // Remove span in Navigation breadcrumb
        $('.navigation__breadcrumb label').click(function() {
          removeSpan(_box);

          _count = 0;
        });
      });

      // Reset Navigation
      function removeSpan($element) {
        $element.stop().animate({left: 0}, 300);
        $('.navigation__breadcrumb').find('span').remove();
        $element.find('.on').removeClass('on');
        $element.find('.navbar-nav__link').removeAttr('id');
      };
    });


    // Replace planning title
    $('.planning__title').each(function(){
      var _text = $(this).text().replace(' +','<br>+');

      $(this).html(_text);
    });

    // Scroll page
    $('.arrow-scroll').on('click', function(e) {
      e.preventDefault();

      $('html, body').animate({scrollTop: $($(this).data('target')).offset().top}, 'slow');
    });

    function debounce(func, wait, immediate) {
      var timeout;
      return function() {
        var context = this, args = arguments;
        var later = function() {
          timeout = null;
          if (!immediate) func.apply(context, args);
        };
        var callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
      };
    };

    // Add height CSS .page-title
    function page_title() {
      $('.page-title').each(function(){

        // Convert img page-title on mobile
        if($(window).width() < 768) {
          $(this).parent().css('height', '100vh');
          fixImageMobile($(this));
        } else {
          $(this).parent().removeAttr('style');
          refixImageMobile($(this));
        };
        
        if($('.page-title').hasClass('page-title--home')) {
          $(this).parent().css('height', '120vh');
        } else if($('.page-title').hasClass('page-title-single')) {
          $(this).parent().css('height', '107vh');
          fixImageMobile($(this));
        };
      });
    };

    var resizePageTitle = debounce(function() {
      page_title();
    }, 250);

    window.addEventListener('resize', resizePageTitle);

    // Fix Image Mobile
    function fixImageMobile(element) {
      var _bx = element.find('.page-title__people'),
          _img = _bx.find('img'),
          _src = _img.attr('src');

      _img.fadeOut();
      _bx.css('background-image', 'url('+ _src +')').addClass('fix-mobile');
    };

    function refixImageMobile(element) {
      var _bx = element.find('.page-title__people'),
          _img = _bx.find('img');

      _img.fadeIn();
      _bx.removeAttr('style').removeClass('fix-mobile');
    };

    // Edit embed-responsive-audio
    $('.embed-responsive-audio').each(function() {
      if($(this).children().hasClass('wp-audio-shortcode')) {
        $(this).css('padding-top','6%');
      };
    });

    //Google map
    $(function() { JFFUtils.gMapInit('#map'); });

    // Placeholder fallback
    if(!Modernizr.input.placeholder){
      $('[placeholder]').focus(function() {
        var input = $(this);
        if (input.val() == input.attr('placeholder')) {
          input.val('');
          input.removeClass('placeholder');
        }
      }).blur(function() {
        var input = $(this);
        if (input.val() == '' || input.val() == input.attr('placeholder')) {
          input.addClass('placeholder');
          input.val(input.attr('placeholder'));
        }
      }).blur();
      $('[placeholder]').parents('form').submit(function() {
        $(this).find('[placeholder]').each(function() {
          var input = $(this);
          if (input.val() == input.attr('placeholder')) {
            input.val('');
          }
        })
      });
    };

    // Call functions here.
    goTop();
    slickInit();
    stmPlayIframeVideo();
    galleryMagnificPopup();
    videoMagnificPopup();
    imagesLoadedInit();
    isotopeInit();
    masonryInit();
    waypointEffect();
    page_title();

  });

  $(window).load(function(){
    $('body').addClass('fadeIn');
  });

})(jQuery);
