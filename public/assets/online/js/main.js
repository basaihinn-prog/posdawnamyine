(function ($) {
  "use strict";


  /* ==================================================
    # Responsive Menu
   ===============================================*/
  document.addEventListener("DOMContentLoaded", () => {
    const menu = new MmenuLight(
      document.querySelector("#ham-navigation"),
      "(max-width: 1199px)"
    );

    const navigator = menu.navigation({
      selectedClass: "Selected",
      slidingSubmenus: true,
      theme: "light",
      title: "Menu",
    });
    const drawer = menu.offcanvas({
      position: "left",
    });

    document
      .querySelector('a[href="#ham-navigation"]')
      .addEventListener("click", (evnt) => {
        evnt.preventDefault();
        drawer.open();
      });
  });

  /* ==================================================
    # Data Background
   ===============================================*/
  $("[data-background]").each(function () {
    $(this).css("background-image", "url(" + $(this).attr("data-background") + ")")
  });


  /* ==================================================
  # Stikey Js  
 ===============================================*/
  (function () {
    var nav = $('.maan-menu-nav-sec');
    var scrolled = false;
    $(window).on('scroll', function () {
      if (120 < $(window).scrollTop() && !scrolled) {
        nav.addClass('sticky_menu animated fadeInDown').animate({ 'margin-top': '0px' });
        scrolled = true;
      }
      if (90 > $(window).scrollTop() && scrolled) {
        nav.removeClass('sticky_menu animated fadeInDown').css('margin-top', '0px');
        scrolled = false
      }
    });
  }());


  /* ==================================================
#  Menu Nav 
===============================================*/

  function smoothSctollTop() {

    $('.main-menu ul li > a,.mean-nav ul li > a').on('click', function (event) {

      var target = $(this.getAttribute('href'));

      if (target.length) {

        event.preventDefault();

        $('html, body').stop().animate({

          scrollTop: target.offset().top - 100

        }, 1000);

      }

    });

  }

  smoothSctollTop();


  /* ==================================================
#  services
===============================================*/
  var blog = new Swiper(".maan-blog-image-container", {
    slidesPerView: 4,
    spaceBetween: 20,
    slidesPerGroup: 1,
    loop: true,
    loopFillGroupWithBlank: true,
    pagination: {
      el: ".maan-blog-swiper-dotted",
      clickable: true,
    },
    breakpoints: {
      1200: {
        slidesPerView: 4,
        spaceBetween: 20
      },
      1100: {
        slidesPerView: 3,
        spaceBetween: 20
      },
      991: {
        slidesPerView: 3,
        spaceBetween: 20
      },
      768: {
        slidesPerView: 2,
        spaceBetween: 20
      },
      600: {
        slidesPerView: 2,
        spaceBetween: 20
      },
      320: {
        slidesPerView: 1,
        spaceBetween: 0
      },
    }
  });


  /* ==================================================
#  Features
===============================================*/
  var feature = new Swiper(".maan-feature-container", {
    slidesPerView: 1,
    spaceBetween: 0,
    slidesPerGroup: 1,
    centeredSlides: true,
    loop: true,
    loopFillGroupWithBlank: true,
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    breakpoints: {
      1400: {
        slidesPerView: 3,
        spaceBetween: 30
      },
      1200: {
        slidesPerView: 2,
        spaceBetween: 30
      },

      640: {
        slidesPerView: 2,
        spaceBetween: 20
      },

      320: {
        slidesPerView: 1,
        spaceBetween: 10
      },
    }
  });

  /* ==================================================
#  Testimonial
===============================================*/
  var testimonial = new Swiper(".maan-testimonial-container", {
    slidesPerView: 3,
    spaceBetween: 0,
    slidesPerGroup: 1,
    loop: true,
    loopFillGroupWithBlank: true,
    pagination: {
      el: ".maan-testimonial-swiper-dotted",
      clickable: true,
    },
    breakpoints: {
      1200: {
        slidesPerView: 3,
        spaceBetween: 0
      },
      1024: {
        slidesPerView: 3,
        spaceBetween: 0
      },
      768: {
        slidesPerView: 2,
        spaceBetween: 0
      },
      640: {
        slidesPerView: 2,
        spaceBetween: 30
      },
      320: {
        slidesPerView: 1,
        spaceBetween: 0
      },
    }
  });

  /* ==================================================
#  Banner
===============================================*/

  var service = new Swiper(".maan-service-container", {
    slidesPerView: 3,
    slidesPerGroup: 1,
    loop: true,
    loopFillGroupWithBlank: true,
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    breakpoints: {      
      320: {
        slidesPerView: 1,
      },

      640: {
        slidesPerView: 2,
      },

      1400: {
        slidesPerView: 3,
      },
    }
  });

  /* ==================================================
#  Product
===============================================*/
  var menuContainerSlider = new Swiper(".maan-menu-container", {
    slidesPerView: 4,
    spaceBetween: 0,
    slidesPerGroup: 1,
    loop: true,
    loopFillGroupWithBlank: true,
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    breakpoints: {
      1200: {
        slidesPerView: 4,
        spaceBetween: 0
      },
      1024: {
        slidesPerView: 3,
        spaceBetween: 0
      },
      768: {
        slidesPerView: 2,
        spaceBetween: 0
      },
      640: {
        slidesPerView: 2,
        spaceBetween: 0
      },
      320: {
        slidesPerView: 1,
        spaceBetween: 0
      },
    }
  });


  /* ==================================================
#  menu isotope
===============================================*/
  let $btns = $('.maan-menu-section .button-group button');


  $btns.click(function (e) {

    $('.maan-menu-section .button-group button').removeClass('active');
    e.target.classList.add('active');

    let selector = $(e.target).attr('data-filter');
    $('.maan-menu-section .grid').isotope({
      filter: selector
    });

    return false;
  })

  // niceSelect 
  $(document).ready(function () {
    $('select').niceSelect();
  });

  // datepicker 
  $(function () {
    $("#datepicker").datepicker();
  });

  /* ==================================================
#  Menu Details slider
===============================================*/
  var galleryTop = new Swiper('.gallery-top', {
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
    spaceBetween: 0,
    slidesPerView: 1,
    loop: true,
    loopedSlides: 1
  });
  var galleryThumbs = new Swiper('.gallery-thumbs', {
    spaceBetween: 0,
    centeredSlides: true,
    slidesPerView: 4,
    // touchRatio: 0.2,
    slideToClickedSlide: true,
    loop: true,
    loopedSlides: 1
  });
  galleryTop.controller.control = galleryThumbs;
  galleryThumbs.controller.control = galleryTop;


  /* ==================================================
#  Load More 
===============================================*/

  $(document).ready(function () {
    $(".maan-menu-section").slice(0, 4).show();
    $("#loadMore").on("click", function (e) {
      e.preventDefault();
      $(".loadcontent:hidden").slice(0, 4).slideDown();
      if ($(".loadcontent:hidden").length == 0) {
        $("#loadMore").text("No Content").addClass("noContent");
      }
    });

  })
  
  // cart modal js 
  $('.maan-cart-open').click(function () {

    $('body').addClass('maan-cart-activee');
  });

  $('.cart-overlay, .cart-close').click(function () {

    $('body').removeClass('maan-cart-activee');
  });

  // nice number 
  $('input[type="number"]').niceNumber();



})(jQuery);