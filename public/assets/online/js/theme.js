; (function ($) {
    "use strict";

    //*=============menu sticky js =============*//
    var $window = $(window);
    var didScroll,
        lastScrollTop = 0,
        delta = 5,
        $mainNav = $(".sticky-nav"),
        $body = $("body"),
        $mainNavHeight = $mainNav.outerHeight() + 15,
        scrollTop;

    $window.on("scroll", function () {
        didScroll = true;
        scrollTop = $(this).scrollTop();
    });

    setInterval(function () {
        if (didScroll) {
            if (Math.abs(lastScrollTop - scrollTop) <= delta) {
                return;
            }
            if (scrollTop > lastScrollTop && scrollTop > $mainNavHeight) {
                $mainNav
                    .removeClass("fadeInDown")
                    .addClass("fadeInUp")
                    .css("top", -$mainNavHeight);
                $body.removeClass("remove").addClass("add");
            } else {
                if (scrollTop + $(window).height() < $(document).height()) {
                    $mainNav
                        .removeClass("fadeInUp")
                        .addClass("fadeInDown")
                        .css("top", 0)
                        .addClass("gap");
                    $body.removeClass("add").addClass("remove");
                }
            }
            lastScrollTop = scrollTop;
            didScroll = false;
        }
    }, 200);

    if ($(".sticky-nav").length) {
        $(window).scroll(function () {
            var scroll = $(window).scrollTop();
            if (scroll) {
                $(".sticky-nav").addClass("navbar_fixed");
                $(".sticky-nav-doc .body_fixed").addClass("body_navbar_fixed");
            } else {
                $(".sticky-nav").removeClass("navbar_fixed");
                $(".sticky-nav-doc .body_fixed").removeClass("body_navbar_fixed");
            }
        });
    }

    $(document).ready(function () {
        $(window).scroll(function () {
            if ($(document).scrollTop() > 500) {
                $("body").addClass("test");
            } else {
                $("body").removeClass("test");
            }
        });
    });

    // scrollToTop
    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            $(".scrollToTop").fadeIn();
        } else {
            $(".scrollToTop").fadeOut();
        }
    });
    //Click event to scroll to top
    $(".scrollToTop").click(function () {
        $("html, body").animate(
            {
                scrollTop: 0,
            },
            800
        );
        return false;
    });

    /*  Menu Click js  */
    function Menu_js() {
        if ($(".submenu").length) {
            $(".submenu > .dropdown-toggle").click(function () {
                var location = $(this).attr("href");
                window.location.href = location;
                return false;
            });
        }
    }
    Menu_js();

    /*--------------- mobile dropdown js--------*/
    function active_dropdown2() {
        $(".menu > li .mobile_dropdown_icon").on("click", function (e) {
            $(this).parent().find("ul").first().slideToggle(300);
            $(this).parent().siblings().find("ul").hide(300);
        });
    }

    active_dropdown2();

    /* ==================================================
    # Responsive Menu
   ===============================================*/
    if (document.querySelectorAll('#ham-navigation').length) {
        document.addEventListener("DOMContentLoaded", () => {
            const menu = new MmenuLight(
                document.querySelector("#ham-navigation"),
                "(max-width: 991px)"
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
    }

    // data background
    function bgImg() {
        $("[data-background]").each(function () {
            $(this).css("background-image", "url(" + $(this).attr("data-background") + ")");
        });
    }

    // Category Slider
    if ($(".maan-category-active").length) {
        var swiper2 = new Swiper(".maan-category-active", {
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
        });
    }

    bgImg();

    // Category Slider
    if ($("#basicDate").length > 0) {
        $("#basicDate").flatpickr({
            enableTime: false,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d"
        })
    }

    // Responsive Menu
    if (document.querySelectorAll('#menu').length) {
        document.addEventListener(
            "DOMContentLoaded", () => {
                const menu = new MmenuLight(
                    document.querySelector("#menu"),
                    "(max-width: 991px)"
                );

                const navigator = menu.navigation();
                const drawer = menu.offcanvas();

                document.querySelector("a[href='#menu']")
                    .addEventListener("click", (evnt) => {
                        evnt.preventDefault();
                        drawer.open();
                    });
            }
        );
    }

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
                slidesPerView: 2,
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
                spaceBetween: 20,
                centeredSlides: false
            },

            320: {
                slidesPerView: 2,
                spaceBetween: 10,
                centeredSlides: false
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
                slidesPerView: 1,
                spaceBetween: 30
            },
            320: {
                slidesPerView: 1,
                spaceBetween: 0
            },
        }
    });






    var testimonial = new Swiper(".blogMobilePart", {
        slidesPerView: 1,
        centeredSlides: true,
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
                slidesPerView: 1,
                spaceBetween: 30
            },
            320: {
                slidesPerView: 1,
                spaceBetween: 0
            },
        }
    });





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



    var service = new Swiper(".myServiceSwiper", {
        slidesPerView: 3,
        slidesPerGroup: 1,
        loop: true,
        loopFillGroupWithBlank: true,
        autoplay: {
            delay: 2500,
            disableOnInteraction: false,
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        breakpoints: {
            0: {
                slidesPerView: 2,
            },
            320: {
                slidesPerView: 2,
            },

            640: {
                slidesPerView: 2,
            },

            1400: {
                slidesPerView: 2,
            },
        }
    });




    /* ==================================================
    #  Features
    ===============================================*/
    var menuSlider = new Swiper(".maan-menu-container", {
        slidesPerView: 1,
        spaceBetween: 0,
        loop: true,
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        breakpoints: {
            1400: {
                slidesPerView: 4,
                spaceBetween: 0
            },
            1200: {
                slidesPerView: 3,
                spaceBetween: 0
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


    // Category Slider
    if ($(".maan-category-active").length) {
        var category = new Swiper(".maan-category-active", {
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
        });
    }

    $(window).on("load", function () {

        if (!$('.grid').length) return;

        var $grid = $('.grid').isotope({
            itemSelector: '.element-item',
            layoutMode: 'fitRows'
        });

        var showAllLimit = parseInt($grid.attr('data-show-all-limit')) || 0;

        function applyShowAllLimit() {
            if (showAllLimit <= 0) return;
            const iso = $grid.data('isotope');
            if (!iso) return;

            $('.element-item').removeClass('hidden');

            iso.filteredItems.slice(showAllLimit).forEach(function (item) {
                $(item.element).addClass('hidden');
            });

            $grid.isotope('layout');
        }

        // Default Show All Active
        $('.button-group button').removeClass('is-active');
        $('.button-group button[data-menu-id="show-all"]').addClass('is-active');

        $grid.isotope({ filter: '*' });
        setTimeout(applyShowAllLimit, 0);

        // Click Handler
        $('.button-group').on('click', 'button', function () {

            const menuId = $(this).data('menu-id');

            $('.button-group button').removeClass('is-active');
            $(this).addClass('is-active');

            // Show All → limit only if showAllLimit > 0
            if (menuId === 'show-all') {
                $grid.isotope({ filter: '*' });
                setTimeout(applyShowAllLimit, 0);
                return;
            }

            // Specific menu
            $grid.isotope({
                filter: '[data-item-menu-id="' + menuId + '"]'
            });

            // remove hidden class
            $('.element-item').removeClass('hidden');
            $grid.isotope('layout');
        });

    });


    if (document.querySelectorAll('.inner-style-2, .select').length) {
        $('.inner-style-2').niceSelect();
        $('.select').niceSelect();
    }

    // init Isotope
    if (document.querySelectorAll('.grid-filter').length) {
        var $grid = $('.grid-filter').isotope({
            itemSelector: '.grid-item',
        });

        // store filter for each group
        var filters = {};

        $('.filters').on('change', function (event) {
            var $select = $(event.target);
            // get group key
            var filterGroup = $select.addClass('value-group');
            // set filter for group
            filters[filterGroup] = event.target.value;
            // combine filters
            var filterValue = concatValues(filters);
            // set filter for Isotope
            $grid.isotope({ filter: filterValue });
        });

        // flatten object by concatting values
        function concatValues(obj) {
            var value = '';
            for (var prop in obj) {
                value += obj[prop];
            }
            return value;
        }

        //****************************
        // Isotope Load more button
        //****************************
        var initShow = 6; //number of items loaded on init & onclick load more button
        var counter = 3; //counter for load more button
        var iso = $grid.data('isotope'); // get Isotope instance

        loadMore(initShow); //execute function onload

        function loadMore(toShow) {
            $grid.find(".hidden").removeClass("hidden");

            var hiddenElems = iso.filteredItems.slice(toShow, iso.filteredItems.length).map(function (item) {
                return item.element;
            });
            $(hiddenElems).addClass('hidden');
            $grid.isotope('layout');

            //when no more to load, hide show more button
            if (hiddenElems.length == 0) {
                jQuery("#load-more").hide();
            } else {
                jQuery("#load-more").show();
            };

        }

        //append load more button
        // $grid.after('<button class="" id="load-more"> Load More</button>');

        //when load more button clicked
        $("#load-more").click(function () {
            if ($('#filters').data('clicked')) {
                //when filter button clicked, set initial value for counter
                counter = initShow;
                $('#filters').data('clicked', false);
            } else {
                counter = counter;
            };

            counter = counter + initShow;

            loadMore(counter);
        });

        //when filter button clicked
        $("#filters").click(function () {
            $(this).data('clicked', true);

            loadMore(initShow);
        });
    }

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

    // Nice Number
    if (document.querySelectorAll('.quantity-number').length) {
        $('input[type="number"]').niceNumber();
    }

    // Nice Number
    if (document.querySelectorAll('.nice-number').length) {
        $('input[type="number"]').niceNumber();
    }

    //Map
    if ($("#map").length) {
        mapboxgl.accessToken = 'pk.eyJ1Ijoic2hha2liZHNoeSIsImEiOiJja3hibWN3ZnkwbXFlMnJreXRqczRucTliIn0.04cHwgcJ93kROlGOqooM8w';
        const map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/streets-v11',
            center: [-77.04, 38.907],
            zoom: 11.15
        });

        map.on('load', () => {
            map.addSource('places', {
                // This GeoJSON contains features that include an "icon"
                // property. The value of the "icon" property corresponds
                // to an image in the Mapbox Streets style's sprite.
                'type': 'geojson',
                'data': {
                    'type': 'FeatureCollection',
                    'features': [
                        {
                            'type': 'Feature',
                            'properties': {
                                'description':
                                    '<strong>Make it Mount Pleasant</strong><p><a href="http://www.mtpleasantdc.com/makeitmtpleasant" target="_blank" title="Opens in a new window">Make it Mount Pleasant</a> is a handmade and vintage market and afternoon of live entertainment and kids activities. 12:00-6:00 p.m.</p>',
                                'icon': 'theatre-15'
                            },
                            'geometry': {
                                'type': 'Point',
                                'coordinates': [-77.038659, 38.931567]
                            }
                        },
                        {
                            'type': 'Feature',
                            'properties': {
                                'description':
                                    '<strong>Mad Men Season Five Finale Watch Party</strong><p>Head to Lounge 201 (201 Massachusetts Avenue NE) Sunday for a <a href="http://madmens5finale.eventbrite.com/" target="_blank" title="Opens in a new window">Mad Men Season Five Finale Watch Party</a>, complete with 60s costume contest, Mad Men trivia, and retro food and drink. 8:00-11:00 p.m. $10 general admission, $20 admission and two hour open bar.</p>',
                                'icon': 'theatre-15'
                            },
                            'geometry': {
                                'type': 'Point',
                                'coordinates': [-77.003168, 38.894651]
                            }
                        },
                        {
                            'type': 'Feature',
                            'properties': {
                                'description':
                                    '<strong>Big Backyard Beach Bash and Wine Fest</strong><p>EatBar (2761 Washington Boulevard Arlington VA) is throwing a <a href="http://tallulaeatbar.ticketleap.com/2012beachblanket/" target="_blank" title="Opens in a new window">Big Backyard Beach Bash and Wine Fest</a> on Saturday, serving up conch fritters, fish tacos and crab sliders, and Red Apron hot dogs. 12:00-3:00 p.m. $25.grill hot dogs.</p>',
                                'icon': 'bar-15'
                            },
                            'geometry': {
                                'type': 'Point',
                                'coordinates': [-77.090372, 38.881189]
                            }
                        },
                        {
                            'type': 'Feature',
                            'properties': {
                                'description':
                                    '<strong>Ballston Arts & Crafts Market</strong><p>The <a href="http://ballstonarts-craftsmarket.blogspot.com/" target="_blank" title="Opens in a new window">Ballston Arts & Crafts Market</a> sets up shop next to the Ballston metro this Saturday for the first of five dates this summer. Nearly 35 artists and crafters will be on hand selling their wares. 10:00-4:00 p.m.</p>',
                                'icon': 'art-gallery-15'
                            },
                            'geometry': {
                                'type': 'Point',
                                'coordinates': [-77.111561, 38.882342]
                            }
                        },
                        {
                            'type': 'Feature',
                            'properties': {
                                'description':
                                    '<strong>Seersucker Bike Ride and Social</strong><p>Feeling dandy? Get fancy, grab your bike, and take part in this year\'s <a href="http://dandiesandquaintrelles.com/2012/04/the-seersucker-social-is-set-for-june-9th-save-the-date-and-start-planning-your-look/" target="_blank" title="Opens in a new window">Seersucker Social</a> bike ride from Dandies and Quaintrelles. After the ride enjoy a lawn party at Hillwood with jazz, cocktails, paper hat-making, and more. 11:00-7:00 p.m.</p>',
                                'icon': 'bicycle-15'
                            },
                            'geometry': {
                                'type': 'Point',
                                'coordinates': [-77.052477, 38.943951]
                            }
                        },
                        {
                            'type': 'Feature',
                            'properties': {
                                'description':
                                    '<strong>Capital Pride Parade</strong><p>The annual <a href="http://www.capitalpride.org/parade" target="_blank" title="Opens in a new window">Capital Pride Parade</a> makes its way through Dupont this Saturday. 4:30 p.m. Free.</p>',
                                'icon': 'rocket-15'
                            },
                            'geometry': {
                                'type': 'Point',
                                'coordinates': [-77.043444, 38.909664]
                            }
                        },
                        {
                            'type': 'Feature',
                            'properties': {
                                'description':
                                    '<strong>Muhsinah</strong><p>Jazz-influenced hip hop artist <a href="http://www.muhsinah.com" target="_blank" title="Opens in a new window">Muhsinah</a> plays the <a href="http://www.blackcatdc.com">Black Cat</a> (1811 14th Street NW) tonight with <a href="http://www.exitclov.com" target="_blank" title="Opens in a new window">Exit Clov</a> and <a href="http://godsilla.bandcamp.com" target="_blank" title="Opens in a new window">Gods’illa</a>. 9:00 p.m. $12.</p>',
                                'icon': 'music-15'
                            },
                            'geometry': {
                                'type': 'Point',
                                'coordinates': [-77.031706, 38.914581]
                            }
                        },
                        {
                            'type': 'Feature',
                            'properties': {
                                'description':
                                    '<strong>A Little Night Music</strong><p>The Arlington Players\' production of Stephen Sondheim\'s  <a href="http://www.thearlingtonplayers.org/drupal-6.20/node/4661/show" target="_blank" title="Opens in a new window"><em>A Little Night Music</em></a> comes to the Kogod Cradle at The Mead Center for American Theater (1101 6th Street SW) this weekend and next. 8:00 p.m.</p>',
                                'icon': 'music-15'
                            },
                            'geometry': {
                                'type': 'Point',
                                'coordinates': [-77.020945, 38.878241]
                            }
                        },
                        {
                            'type': 'Feature',
                            'properties': {
                                'description':
                                    '<strong>Truckeroo</strong><p><a href="http://www.truckeroodc.com/www/" target="_blank">Truckeroo</a> brings dozens of food trucks, live music, and games to half and M Street SE (across from Navy Yard Metro Station) today from 11:00 a.m. to 11:00 p.m.</p>',
                                'icon': 'music-15'
                            },
                            'geometry': {
                                'type': 'Point',
                                'coordinates': [-77.007481, 38.876516]
                            }
                        }
                    ]
                }
            });
            // Add a layer showing the places.
            map.addLayer({
                'id': 'places',
                'type': 'symbol',
                'source': 'places',
                'layout': {
                    'icon-image': '{icon}',
                    'icon-allow-overlap': true
                }
            });

            // When a click event occurs on a feature in the places layer, open a popup at the
            // location of the feature, with description HTML from its properties.
            map.on('click', 'places', (e) => {
                // Copy coordinates array.
                const coordinates = e.features[0].geometry.coordinates.slice();
                const description = e.features[0].properties.description;

                // Ensure that if the map is zoomed out such that multiple
                // copies of the feature are visible, the popup appears
                // over the copy being pointed to.
                while (Math.abs(e.lngLat.lng - coordinates[0]) > 180) {
                    coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;
                }

                new mapboxgl.Popup()
                    .setLngLat(coordinates)
                    .setHTML(description)
                    .addTo(map);
            });

            // Change the cursor to a pointer when the mouse is over the places layer.
            map.on('mouseenter', 'places', () => {
                map.getCanvas().style.cursor = 'pointer';
            });

            // Change it back to a pointer when it leaves.
            map.on('mouseleave', 'places', () => {
                map.getCanvas().style.cursor = '';
            });
        });
    }



    $(document).ready(function() {
        var $toggler = $('.navbar-toggler');
        var $menu = $('#navbarText');
        var hideTimeout;
        function showMenu() {
            clearTimeout(hideTimeout);
            if (!$menu.hasClass('show')) {
                $menu.stop(true, true).slideDown(400, function() {
                    $(this).addClass('show').css('display', '');
                });
                $toggler.removeClass('collapsed');
                $toggler.attr('aria-expanded', 'true');
            }
        }

        // Menu Close korar Function
        function hideMenu() {
            hideTimeout = setTimeout(function() {
                if (!$menu.is(':hover') && !$toggler.is(':hover')) {
                    $menu.stop(true, true).slideUp(400, function() {
                        $(this).removeClass('show').css('display', '');
                    });
                    $toggler.addClass('collapsed');
                    $toggler.attr('aria-expanded', 'false');
                }
            }, 200);
        }
        $toggler.on('mouseenter', showMenu);
        $toggler.on('mouseleave', hideMenu);

        $menu.on('mouseenter', function() {
            clearTimeout(hideTimeout);
        });
        $menu.on('mouseleave', hideMenu);
    });



    // dd-note-btn
    $(document).ready(function() {
        $('#add-note-btn').on('click', function() {
            $('#add-note-btn-container').hide();
            $('#note-input-box').fadeIn();
        });

        $('#close-note').on('click', function() {
            $('#note-input-box').hide();
            $('#add-note-btn-container').show();
        });
    });






    scrollCue.init();
})(jQuery);
