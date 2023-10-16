(function ($, elementor) {
    "use strict";
    var Elementskit = {
        init: function () {
            var widgets = {
                'fmtpl-images-gallery.default': Elementskit.Gallery_init,
                'fmtpl-carousel-reviews.default': Elementskit.Carousel,
                'fmtpl-carousel-images.default': Elementskit.Carousel,
                'fmtpl-carousel-products.default': Elementskit.Carousel,
                'fmtpl-carousel-posts.default': Elementskit.Carousel,
                'fmtpl-carousel-testimonial.default': Elementskit.Carousel,
                'fmtpl-products-tabs.default': Elementskit.Loadmore_products,
                'fmtpl-countdown.default': Elementskit.CountDown,
                'fmtpl-deal.default': Elementskit.CountDown,
                'fmtpl-carousel-product-banner.default': Elementskit.Carousel,
                'fmtpl-carousel-categories-banner.default': Elementskit.Carousel,
                'fmtpl-writer-banner.default': Elementskit.TextRotate,
                'fmtpl-currency.default': Elementskit.CurrencySwitch,
                'fmtpl-product-tabs-carousel.default': Elementskit.Carousel,
                'fmtpl-slider.default': Elementskit.EasySlider,
                'smooth-banner.default': Elementskit.Smooth_banner,
                'fmtpl-products-grid.default': Elementskit.Loadmore_products
            };
            $.each(widgets, function (widget, callback) {
                elementor.hooks.addAction('frontend/element_ready/' + widget, callback);
            });
        },
        Gallery_init: function (e) {},
        Carousel: function (e) {
            var e_sw_selector = e.find('.fmtpl-elementor-main-swiper');
            if (e_sw_selector.length) {
                e_sw_selector.each(function () {
                    var $_this = $(this),
                        swiper_wrapper_child = $_this.find('.swiper-wrapper').children(),
                        elementorBreakpoint = elementor.config.breakpoints,
                        e_sw_setting = e.Fmtpl_getElementSettings(elementor) || false;

                    var $swiper_obj = false;
                    if ($_this.hasClass('swiper-container')){
                        $swiper_obj = $_this;
                    } else {
                        $swiper_obj = $_this.find('.swiper-container');
                    }

                    if (e_sw_setting == false){
                        return;
                    }
                    if (swiper_wrapper_child.length){
                        if (! swiper_wrapper_child.hasClass('swiper-slide')) {
                            swiper_wrapper_child.addClass('swiper-slide')
                        }
                    }

                    var $slides_per_view = e_sw_setting.slides_per_view || 1,
                        $slidesPerGroup = e_sw_setting.slides_to_scroll || $slides_per_view;
                    if (typeof e_sw_setting.space_between != 'undefined'){
                        var $spaceBetween = e_sw_setting.space_between.size || 0;
                    }
                    $slidesPerGroup = parseInt($slidesPerGroup);
                    var swiperOptions = {
                        slidesPerView: $slides_per_view,
                        slidesPerGroup: $slidesPerGroup,
                        loop: e_sw_setting.loop === 'yes',
                        speed: e_sw_setting.speed,
                        spaceBetween: $spaceBetween,
                        watchSlidesVisibility: true
                    };
                    if ('yes' === e_sw_setting.autoplay) {
                        swiperOptions.autoplay = {
                            delay: e_sw_setting.autoplay_speed,
                            disableOnInteraction: 'yes' === e_sw_setting.pause_on_interaction
                        };
                    }

                    var dataBreakPoint = {},
                        slides_per_view_tablet = e_sw_setting.slides_per_view_tablet || $slides_per_view,
                        slides_per_view_mobile = e_sw_setting.slides_per_view_mobile || e_sw_setting.slides_per_view_tablet || $slides_per_view,
                        slides_to_scroll_tablet =  e_sw_setting.slides_to_scroll_tablet || slides_per_view_tablet,
                        slides_to_scroll_mobile = e_sw_setting.slides_to_scroll_mobile || slides_per_view_mobile,
                        space_between_tablet = e_sw_setting.space_between_tablet.size || $spaceBetween,
                        space_between_mobile = e_sw_setting.space_between_mobile.size || space_between_tablet;
                    dataBreakPoint[elementorBreakpoint.xs] = {
                        slidesPerView: slides_per_view_mobile,
                        slidesPerGroup: slides_to_scroll_mobile,
                        spaceBetween: space_between_mobile
                    };
                    dataBreakPoint[elementorBreakpoint.md] = {
                        slidesPerView: slides_per_view_tablet,
                        slidesPerGroup: slides_to_scroll_tablet,
                        spaceBetween: space_between_tablet
                    };
                    dataBreakPoint[elementorBreakpoint.lg] = {
                        slidesPerView: swiperOptions.slidesPerView,
                        slidesPerGroup: swiperOptions.slidesPerGroup,
                        spaceBetween: $spaceBetween
                    };
                    swiperOptions.breakpoints = dataBreakPoint;
                    if (e_sw_setting.show_arrows == 'yes') {
                        swiperOptions.navigation = {
                            nextEl: $_this.find('.elementor-swiper-button-next'),
                            prevEl: $_this.find('.elementor-swiper-button-prev')
                        };
                    }
                    var pagination_type = 'bullets';
                    if (e_sw_setting.pagination && e_sw_setting.pagination != 'none') {
                        pagination_type = e_sw_setting.pagination;
                    }
                    swiperOptions.pagination = {
                        el: $_this.find('.swiper-pagination'),
                        type: pagination_type,
                        clickable: true
                    };
                    var newSwiper = new Swiper($swiper_obj,swiperOptions);
                });
            }
        },
        CountDown: function(e) {
            var count_element = e.find('[data-due_date]');
            if (count_element.length) {
                count_element.each(function () {
                    var t = $(this),
                        time = t.attr('data-due_date'),
                        t_hour = t.data('text-hour') || Fmtpl_JsVars.countDown_text.h,
                        t_min = t.data('text-min') || Fmtpl_JsVars.countDown_text.m,
                        t_day = t.data('text-day') || Fmtpl_JsVars.countDown_text.d,
                        t_sec = t.data('text-sec') || Fmtpl_JsVars.countDown_text.s;
                    t.countdown(time, function (event) {
                        var format = '<span class="box-count hrs"><span class="num">%H</span><span class="text">' + t_hour + '</span></span> <span class="box-count min"><span class="num">%M</span><span class="text">' + t_min + '</span></span> <span class="box-count secs"><span class="num">%S</span><span class="text">' + t_sec + '</span></span>';
                        if(event.offset.totalDays > 0) { format = '<span class="box-count days"><span class="num">%D</span> <span class="text">' + t_day + '</span></span>' + format; }
                        $(this).html(event.strftime(format));
                    });
                });
            }
        },
        TextRotate: function (e) {
            var TxtRotate = function(el, toRotate, period) {
                this.toRotate = toRotate;
                this.el = el;
                this.loopNum = 0;
                this.period = parseInt(period, 10) || 2000;
                this.txt = '';
                this.tick();
                this.isDeleting = false;
            };

            TxtRotate.prototype.tick = function() {
                var i = this.loopNum % this.toRotate.length;
                var fullTxt = this.toRotate[i];

                if (this.isDeleting) {
                    this.txt = fullTxt.substring(0, this.txt.length - 1);
                } else {
                    this.txt = fullTxt.substring(0, this.txt.length + 1);
                }

                this.el.innerHTML = '<span class="wrap">'+this.txt+'</span>';

                var that = this;
                var delta = 250 - Math.random() * 100;

                if (this.isDeleting) { delta /= 2; }

                if (!this.isDeleting && this.txt === fullTxt) {
                    delta = this.period;
                    this.isDeleting = true;
                } else if (this.isDeleting && this.txt === '') {
                    this.isDeleting = false;
                    this.loopNum++;
                    delta = 300;
                }

                setTimeout(function() {
                    that.tick(); 
                }, delta);
            };

            var elements = document.getElementsByClassName('txt-rotate');
            for (var i=0; i<elements.length; i++) {
                var toRotate = elements[i].getAttribute('data-rotate');
                var period = elements[i].getAttribute('data-period');
                if ('string' == typeof(toRotate)){
                    //toRotate = toRotate.replace(/\\/g, '');
                    toRotate = atob(toRotate);
                }
                if (toRotate) {
                    new TxtRotate(elements[i], JSON.parse(toRotate), period);
                }
            }
        },
        CurrencySwitch: function (e) {
            var e_currency = e.find('.fmtpl-currency');
            if (e_currency.length){
                $('body').on('click','.fmtpl-currency .currency_item a',function (i) {
                    i.preventDefault();
                    var _t = $(this);
                    if ( $.isFunction(woocs_redirect) ) {
                        woocs_redirect(_t.data('value'));
                    }
                });
            }
        },
        EasySlider: function (e) {
            var e_sw_selector = e.find('.fmtpl-swiper-slide');
            if (e_sw_selector.length) {
                const animateCSS = (element, animation,duration, delay, prefix = 'animate__') =>
                    // We create a Promise and return it
                    new Promise((resolve, reject) => {
                        const animationName = `${prefix}${animation}`;
                        const node = element.get(0);
                        if (typeof node == 'undefined'){
                            return;
                        }
                        if (parseFloat(duration) > 0){
                            node.style.setProperty('--animate-duration', duration);
                        }
                        if (parseFloat(delay) > 0) {
                            node.style.setProperty('--animate-delay', delay);
                            node.classList.add('animate__delay-1s');
                        }
                        node.classList.add(`${prefix}animated`, animationName);

                        // When the animation ends, we clean the classes and resolve the Promise
                        function handleAnimationEnd(event) {
                            event.stopPropagation();
                            node.classList.remove(`${prefix}animated`, animationName);
                            resolve('Animation ended');
                            node.style.transition = '';
                        }

                        node.addEventListener('animationend', handleAnimationEnd, {once: true});
                    });
                const setAnimateData = function(element, speed, show = false){
                    if (!element.length){
                        return;
                    }
                    var effect = element.data('effect'),
                        duration = element.data('duration'),
                        delay = element.data('delay');
                    if (typeof effect == "undefined"){
                        return;
                    }
                    if (typeof duration != "undefined"){
                        duration =  parseInt(duration)+'ms';
                    }
                    if (typeof delay != "undefined"){
                        delay =  parseInt(delay) + parseInt(speed) + 'ms';
                    }
                    animateCSS(element,effect,duration,delay);
                    if (show){
                        element.css('opacity', 1);
                    }
                };
                const initSlideAnmate = function(swiper_selector,swiper_obj ){
                    var slideimg = swiper_selector.find('.swiper-slide-active .fmtpl-elementor_image'),
                        speed = swiper_obj.params.speed,
                        slidebox = swiper_selector.find('.swiper-slide-active .fmtpl-elementor_content'),
                        slidetitle = swiper_selector.find('.swiper-slide-active .fmtpl-item-title'),
                        slidedivider = swiper_selector.find('.swiper-slide-active .fmtpl-item-divider'),
                        slidesub = swiper_selector.find('.swiper-slide-active .fmtpl-sub-title'),
                        slidebtn = swiper_selector.find('.swiper-slide-active .fmtpl-btn-wrap'),
                        slidedesc = swiper_selector.find('.swiper-slide-active .fmtpl-image-desc');
                    setAnimateData(slideimg,speed,true);
                    setAnimateData(slidebox,speed);
                    setAnimateData(slidetitle,speed);
                    setAnimateData(slidedivider,speed);
                    setAnimateData(slidesub,speed);
                    setAnimateData(slidebtn,speed);
                    setAnimateData(slidedesc,speed);
                };
                e_sw_selector.each(function () {
                    var $_this = $(this),
                        swiper_wrapper_child = $_this.find('.swiper-wrapper').children(),
                        e_sw_setting = e.Fmtpl_getElementSettings(elementor) || false,
                        $swiper_obj = false;

                    if (e_sw_setting == false){
                        return;
                    }
                    if ($_this.hasClass('swiper-container')){
                        $swiper_obj = $_this;
                    } else {
                        $swiper_obj = $_this.find('.swiper-container');
                    }
                    if (swiper_wrapper_child.length){
                        if (! swiper_wrapper_child.hasClass('swiper-slide')) {
                            swiper_wrapper_child.addClass('swiper-slide')
                        }
                    }
                    var interleaveOffset = 1;
                    var swiperOptions = {
                        preventInteractionOnTransition: true,
                        loop: true,
                        speed: 1000,
                        grabCursor: true,
                        watchSlidesProgress: true,
                        mousewheelControl: true,
                        keyboardControl: true,
                    };
                    if (e_sw_selector.hasClass('fmtpl-fade')){
                        swiperOptions.effect = 'fade';
                    }
                    if (e_sw_selector.hasClass('fmtpl-flip')){
                        swiperOptions.effect = 'flip';
                        swiperOptions.flipEffect = {
                            slideShadows: false,
                        }
                    }
                    if ('yes' === e_sw_setting.autoplay) {
                        swiperOptions.autoplay = {
                            delay: e_sw_setting.autoplay_speed,
                            disableOnInteraction: 'yes' === e_sw_setting.pause_on_interaction
                        };
                    }

                    if (e_sw_setting.show_arrows == 'yes') {
                        swiperOptions.navigation = {
                            nextEl: '.elementor-swiper-button-next',
                            prevEl: '.elementor-swiper-button-prev'
                        };
                    }
                    var pagination_type = 'bullets';
                    if (e_sw_setting.pagination && e_sw_setting.pagination != 'none') {
                        pagination_type = e_sw_setting.pagination;
                    }
                    swiperOptions.pagination = {
                        el: '.swiper-pagination',
                        type: pagination_type,
                        clickable: true
                    };
                    if (e_sw_selector.hasClass('fmtpl-masked')) {
                        swiperOptions.on = {
                            progress: function () {
                                var swiper = this;
                                for (var i = 0; i < swiper.slides.length; i++) {
                                    var slideProgress = swiper.slides[i].progress;
                                    var innerOffset = swiper.width * interleaveOffset;
                                    var innerTranslate = slideProgress * innerOffset;
                                    swiper.slides[i].querySelector(".fmtpl-slide-background").style.transform =
                                        "translate3d(" + innerTranslate + "px, 0, 0)";
                                }
                            },
                            touchStart: function () {
                                var swiper = this;
                                for (var i = 0; i < swiper.slides.length; i++) {
                                    swiper.slides[i].style.transition = "";
                                }
                            },
                            setTransition: function (speed) {
                                var swiper = this;
                                for (var i = 0; i < swiper.slides.length; i++) {
                                    swiper.slides[i].style.transition = speed + "ms";
                                    swiper.slides[i].querySelector(".fmtpl-slide-background").style.transition =
                                        speed + "ms";
                                }
                            },
                        };
                    }
                    swiperOptions.init = false;
                    var newSwiper = new Swiper($swiper_obj,swiperOptions);
                    //transitionStart
                    newSwiper.on('init', function () {
                        initSlideAnmate($swiper_obj, newSwiper);
                    });
                    newSwiper.init();
                    newSwiper.on('slideChangeTransitionStart', function () {
                        initSlideAnmate($swiper_obj, newSwiper);
                        var slideimg = $swiper_obj.find('.swiper-slide-active .fmtpl-elementor_image');
                        slideimg.css('opacity',0);
                    });
                    newSwiper.on('slideChangeTransitionEnd', function () {
                        var slideimg = $swiper_obj.find('.swiper-slide-active .fmtpl-elementor_image'),
                            delay =  slideimg.data('delay') || 0;
                        slideimg.css('transition','opacity 0.3s ease-in-out '+delay+'ms');
                        slideimg.css('opacity',1);

                    });
                });
            }
        },
        Smooth_banner: function (e) {
            var e_adv = e.find('.smooth-banner-box-img');
            if (e_adv.length) {
                e_adv.each(function () {
                    var $_this = $(this);
                    const img1 = $_this.find('img:first-child');
                    const img2 = $_this.find('img:last-child');
                    new hoverEffect({
                        parent: $_this[0],
                        intensity: $_this.data('intensity') || undefined,
                        speedIn: $_this.data('speedin') || undefined,
                        speedOut: $_this.data('speedout') || undefined,
                        easing: $_this.data('easing') || undefined,
                        hover: $_this.data('hover') || undefined,
                        image1: img1.attr('src'),
                        image2: img2.attr('src'),
                        displacementImage: $_this.data('displacement')
                    });
                });
            }
        },
        Loadmore_products: function (e) {
            var ajax_btn = e.find('.ajax_load_product_btn');
            if (ajax_btn.length) {
                $('body').on('click','.ajax_load_product_btn',function (ta) {
                    ta.preventDefault();
                    var _t = $(this),
                        el = false,
                        ajax_btn_target = _t.data('pruducts-target');
                    if (_t.hasClass('processing')){
                        return;
                    }
                    if (typeof ajax_btn_target == 'undefined'){
                        el = _t.closest('.fmtpl-products');
                    } else {
                        el = _t.closest(ajax_btn_target);
                    }
                    if (!el.length){
                        _t.removeClass('processing');
                        return;
                    }
                    var list_el = el.find('ul.products');
                    if (!list_el.length){
                        _t.removeClass('processing');
                        return;
                    }
                    _t.addClass('processing');
                    var settings = JSON.parse(decodeURIComponent(el.data('settings'))),
                        page = parseInt(el.attr('data-page'));
                    if (el.hasClass('disabled_load')){
                        _t.removeClass('processing');
                        return;
                    }
                    if (typeof settings == 'object'){
                        settings.page = page + 1;
                        var data = {
                            action: 'fmtpl_get_products_html',
                            nonce: Fmtpl_JsVars.ajax_nonce,
                            settings: settings,
                        };
                        $.post(Fmtpl_JsVars.ajaxurl, data, function (response) {
                            if (typeof response.data.html == 'undefined' || typeof response.data.max_num_pages == 'undefined'){
                                _t.removeClass('processing');
                                return;
                            }
                            var v_div = $('<div></div>').append(response.data.html),
                                max_num_pages = response.data.max_num_pages;
                            if (v_div.find('li.product').length){
                                var product_item = v_div.find('li.product');
                                product_item.each(function ( index, element ) {
                                    $(element).css({
                                        'opacity':'0',
                                        'transform': 'translatey(30px)',
                                        'transition': 'opacity 0.5s ease-in-out, transform 0.5s ease-in-out'
                                    }).addClass('fmtpl_load_'+index);
                                    list_el.append(element);
                                    setTimeout(function () {
                                        $('.fmtpl_load_'+index).css({
                                            'opacity':'1',
                                            'transform': 'translatey(0)'
                                        });
                                    }, index*300);
                                    setTimeout(function () {
                                        $('.fmtpl_load_'+index).removeClass('fmtpl_load_'+index).removeAttr('style');
                                    }, (500 + index*300));
                                });
                                el.attr('data-page',settings.page);
                            }
                            if (max_num_pages == settings.page){
                                el.addClass('disabled_load');
                            }
                            _t.removeClass('processing');
                        });
                    }
                });
            }
        }
    };
    $(window).on('elementor/frontend/init', Elementskit.init);
    $('a.fmtpl-tab-link[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var tab_id = $(e.target).attr('href'),
            swiper_container = $(tab_id).find('.swiper-container');
        if (swiper_container.length && !swiper_container.hasClass('fmtpl-tab-sw-updated')){
            swiper_container[0].swiper.update();
            swiper_container.addClass('fmtpl-tab-sw-updated');
        }
    })
}(jQuery, window.elementorFrontend));

(function ($) {
    "use strict";
    $.fn.Fmtpl_getmodelCID = function () {
        const cid = $(this).data('model-cid');
        return (typeof (cid) != "undefined") ? cid : false;
    };
    $.fn.Fmtpl_getElementSettings = function (elementorFrontend) {
        var elementSettings = {};
        const modelCID = this.Fmtpl_getmodelCID();
        if (this.hasClass('elementor-element-edit-mode') && modelCID) {
            var settings = elementorFrontend.config.elements.data[modelCID],
                attributes = settings.attributes;
            var type = attributes.widgetType || attributes.elType;

            if (attributes.isInner) {
                type = 'inner-' + type;
            }

            var settingsKeys = elementorFrontend.config.elements.keys[type];

            if (!settingsKeys) {
                settingsKeys = elementorFrontend.config.elements.keys[type] = [];
                jQuery.each(settings.controls, function (name, control) {
                    if (control.frontend_available) {
                        settingsKeys.push(name);
                    }
                });
            }

            jQuery.each(settings.getActiveControls(), function (controlKey) {
                if (-1 !== settingsKeys.indexOf(controlKey)) {
                    var value = attributes[controlKey];

                    if (value.toJSON) {
                        value = value.toJSON();
                    }

                    elementSettings[controlKey] = value;
                }
            });
        } else {
            elementSettings = this.data('settings') || {};
        }
        return elementSettings;
    };
})(jQuery);
