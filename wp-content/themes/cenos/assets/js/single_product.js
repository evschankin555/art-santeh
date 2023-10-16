(function ($) {
    "use strict";
    var CENOS_SP =  CENOS_SP || {};
    var messageFn = function(action, src) {
        if (src.search("vimeo") > 0) { // case for Vimeo
            return JSON.stringify({
                method: action
            });
        } else if (src.search("youtube") > 0) { // case for youTube
            return JSON.stringify({
                event: 'command',
                func: action + 'Video'
            });
        } else { // case for other video services (hurriyet videos, dailymotion etc..)
            return action;
        }
    };
    CENOS_SP.init = function () {
        CENOS_SP.thumb_control();
        CENOS_SP.sticky_Sidebar();
        CENOS_SP.video_lightbox();
        CENOS_SP.video_scroll();
    };

    CENOS_SP.thumb_control = function(){
        var g = $('.woocommerce-product-gallery');
        if (g.hasClass('gallery_style_vertical') || g.hasClass('gallery_style_default')) {
            var control_thumbs = g.find('.flex-control-thumbs');
            const resize_img = function () {
                const vp = g.find('.flex-viewport');
                if (g.hasClass('gallery_style_vertical') && vp.length){
                    const viewport_w = vp.width();
                    if (viewport_w){
                        vp.find('.woocommerce-product-gallery__image img:not(.zoomImg)').css('max-width',viewport_w+'px');
                    }
                }
            };
            if (control_thumbs.length){
                const flex_viewport = g.find('.flex-viewport');
                if ($('.flex-viewport .video-frame').length){
                    flex_viewport.addClass('has-video');
                }
                resize_img();
                var dataResponsive = g.data('responsive') || false;
                var ct_run = {
                    init_video_slide: function () {
                        if (!$('.flex-viewport .video-frame').length){
                            return;
                        }
                        if (!$('.flex-viewport .video-frame').hasClass('processed')){
                            var prev_item = $('.flex-viewport .video-frame').prev();
                            if (typeof prev_item != 'undefined'){
                                var frame_height = $('.flex-viewport .video-frame').prev('.woocommerce-product-gallery__image').innerHeight();
                                $('.flex-viewport .video-frame').css('height',frame_height +'px');
                                $('.flex-viewport .video-frame').addClass('processed');
                            }
                        }

                        if ($('.flex-active-slide').hasClass('video-frame')){
                            var video_frame = $('.flex-active-slide.video-frame').find(('.fmfw-video-iframe'));
                            if (video_frame.hasClass('playing')){
                                video_frame[0].contentWindow.postMessage(messageFn('play',video_frame.attr('src')),'*');
                            } else {
                                var lazy_video = video_frame.first().data('lazy-load'),
                                    video_src = video_frame.first().attr('src');
                                if (typeof lazy_video != 'undefined' && lazy_video){
                                    video_src = lazy_video;
                                }
                                if (typeof video_src != 'undefined'){
                                    var new_video_src = video_src.replace('&autoplay=0', '');
                                    video_frame.first().attr('src',new_video_src + '&autoplay=1');
                                    video_frame.addClass('playing');
                                }
                            }
                        } else {
                            var video_frame = $('.flex-viewport .fmfw-video-iframe');
                            if (video_frame.hasClass('playing')) {
                                video_frame[0].contentWindow.postMessage(messageFn('pause',video_frame.attr('src')),'*');
                            }
                        }
                    },
                    run:function () {
                        setTimeout(function () {
                            control_thumbs.wrap( "<div class='gallery_thumb_swiper swiper-container'></div>" );
                            control_thumbs.addClass( "swiper-wrapper" );
                            control_thumbs.find('li').addClass('swiper-slide');
                            $('<div class="swiper-pagination"></div>').insertAfter(control_thumbs);
                            if (g.hasClass('gallery_style_vertical')){
                                $(cenos_product_JsVars.v_prev_html).insertAfter(control_thumbs);
                                $(cenos_product_JsVars.v_next_html).insertAfter(control_thumbs);
                            } else {
                                $(cenos_product_JsVars.prev_html).insertAfter(control_thumbs);
                                $(cenos_product_JsVars.next_html).insertAfter(control_thumbs);
                            }
                            var thumbSwiperSetting = {
                                slidesPerView: 1,
                                spaceBetween: 10,
                                slideToClickedSlide: true,
                                pagination: {
                                    el: control_thumbs.siblings('.swiper-pagination'),
                                    clickable: true,
                                },
                                navigation: {
                                    nextEl: control_thumbs.siblings('.cenos-next'),
                                    prevEl: control_thumbs.siblings('.cenos-prev'),
                                }
                            };
                            if (g.hasClass('gallery_style_vertical')){
                                thumbSwiperSetting.direction = 'vertical';
                            }
                            if ('string' == typeof(dataResponsive)){
                                dataResponsive = dataResponsive.replace(/\\/g, '');
                                dataResponsive = JSON.parse(dataResponsive);
                                thumbSwiperSetting.breakpoints = dataResponsive;
                            } else {
                                thumbSwiperSetting.breakpoints = dataResponsive;
                            }


                            var thumbSwiper = new Swiper ('.gallery_thumb_swiper', thumbSwiperSetting );
                            $(document).on('click','.swiper-pagination-bullet',function () {
                                $(thumbSwiper.slides[thumbSwiper.activeIndex]).find('img').trigger('click');
                                ct_run.init_video_slide();
                            });

                            $(document).on('click','.flex-control-thumbs li > img',function () {
                                ct_run.init_video_slide();
                            });

                        }, 101);
                    }
                };
                ct_run.run();
                $(window).on('resize',function () {
                    var flex_viewport =  g.find('.flex-viewport'),
                        flex_viewport_height = flex_viewport.find('.woocommerce-product-gallery__image').outerHeight(),
                        window_size = $('body').innerWidth();
                    window_size += CENOS_SP.get_scrollbar_width();
                    if (window_size > 991){
                        if ( flex_viewport_height && flex_viewport ) {
                            flex_viewport.height( flex_viewport_height );
                        }
                        resize_img();
                    }
                });
            }
        }
        else if (g.closest('.product-main').hasClass('wide-gallery'))  { 
            var gallery_wrapper = g.find('.woocommerce-product-gallery__wrapper'),
                window_size = $('body').innerWidth();
            window_size += CENOS_SP.get_scrollbar_width();
            if (gallery_wrapper.length) {
                gallery_wrapper.wrap( "<div class='wide_gallery_swiper swiper-container'></div>" );
                gallery_wrapper.addClass( "swiper-wrapper" );
                gallery_wrapper.find('.woocommerce-product-gallery__image').addClass('swiper-slide');
                $('<div class="swiper-pagination"></div>').insertAfter(gallery_wrapper);
                var _slide_config = {
                    spaceBetween: 30,
                    loop: true,
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true,
                    },
                };
                if (window_size < 992){
                    _slide_config.centeredSlides = false;
                    _slide_config.slidesPerView = 1;
                } else {
                    _slide_config.centeredSlides = true;
                    if (window_size < 1200){
                        _slide_config.slidesPerView = 2;
                    } else {
                        _slide_config.slidesPerView = 4;
                    }
                }
                var wide_gallery_swiper = new Swiper('.wide_gallery_swiper', _slide_config);
                wide_gallery_swiper.on('resize', function () {
                    var window_size = $('body').innerWidth();
                    window_size += CENOS_SP.get_scrollbar_width();
                    if (window_size < 992){
                        wide_gallery_swiper.params.centeredSlides = false;
                        wide_gallery_swiper.params.slidesPerView = 1;
                    } else {
                        wide_gallery_swiper.params.centeredSlides = true;
                        if (window_size < 1200){
                            wide_gallery_swiper.params.slidesPerView = 2;
                        } else {
                            wide_gallery_swiper.params.slidesPerView = 4;
                        }
                    }
                    wide_gallery_swiper.init();
                });
            }
        }
    };
    CENOS_SP.sticky_Sidebar = function (){
        if ($('.product-main.sticky-layout').length) {
            var summary_sticky = $('.product-main.sticky-layout').find('.sticky-wrapper');
            if (summary_sticky.length) {
                summary_sticky.stickySidebar({
                    topSpacing: 60,
                    containerSelector: '.product-info',
                    innerWrapperSelector: '.sticky-inner',
                    resizeSensor: true,
                });
                setTimeout(function(){
                    window.dispatchEvent(new Event('resize'));
                },1000);
                setTimeout(function(){
                    window.dispatchEvent(new Event('resize'));
                },3000);
            }
        }
    };
    CENOS_SP.get_scrollbar_width = function() {
        var $inner = $('<div style="width: 100%; height:200px;">test</div>'),
            $outer = $('<div style="width:200px;height:150px; position: absolute; top: 0; left: 0; visibility: hidden; overflow:hidden;"></div>').append($inner),
            inner  = $inner[ 0 ],
            outer  = $outer[ 0 ];
        $('body').append(outer);
        var width1 = inner.offsetWidth;
        $outer.css('overflow', 'scroll');
        var width2 = outer.clientWidth;
        $outer.remove();
        return (width1 - width2);
    };

    CENOS_SP.video_lightbox = function() {
        if ($('#single-product-video-view').length){
            $('#single-product-video-view').on('show.bs.modal', function(){
                var _t = $(this),
                    video_frame = _t.find('.fmfw-video-iframe');
                if (video_frame.hasClass('playing')){
                    video_frame[0].contentWindow.postMessage(messageFn('play',video_frame.attr('src')),'*');
                } else {
                    var lazy_video = video_frame.first().data('lazy-load'),
                        video_src = video_frame.first().attr('src');
                    if (typeof lazy_video != 'undefined' && lazy_video){
                        video_src = lazy_video;
                    }
                    if (typeof video_src != 'undefined'){
                        var new_video_src = video_src.replace('&autoplay=0', '');
                        video_frame.first().attr('src',new_video_src + '&autoplay=1');
                        video_frame.addClass('playing');
                    }
                }
            });
            $('#single-product-video-view').on('hide.bs.modal', function(){
                var video_frame = $(this).find('.fmfw-video-iframe');
                if (video_frame.hasClass('playing')) {
                    video_frame[0].contentWindow.postMessage(messageFn('pause',video_frame.attr('src')),'*');
                }
            });
        }
    };

    CENOS_SP.video_scroll = function() {
        if ($('.video-frame.scroll-video').length){
            var calculateVisibilityForDiv = function(e){
                var windowHeight = $(window).height(),
                    docScroll = $(document).scrollTop(),
                    divPosition = e.offset().top,
                    divHeight = e.height(),
                    hiddenBefore = docScroll - divPosition,
                    hiddenAfter = (divPosition + divHeight) - (docScroll + windowHeight);

                if ((docScroll > divPosition + divHeight) || (divPosition > docScroll + windowHeight)) {
                    return 0;
                } else {
                    var result = 100;

                    if (hiddenBefore > 0) {
                        result -= (hiddenBefore * 100) / divHeight;
                    }

                    if (hiddenAfter > 0) {
                        result -= (hiddenAfter * 100) / divHeight;
                    }
                    return result;
                }
            };

            var scroll_video = $('.video-frame.scroll-video'),
                video_frame = scroll_video.find('.fmfw-video-iframe'),
                div_percent = calculateVisibilityForDiv(scroll_video);
            if (div_percent > 25){
                if (video_frame.hasClass('playing')){
                    if (video_frame.hasClass('pause')){
                        video_frame.removeClass('pause');
                        video_frame[0].contentWindow.postMessage(messageFn('play',video_frame.attr('src')),'*');
                    }
                } else {
                    var video_src = video_frame.first().attr('src');
                    if (typeof video_src != 'undefined'){
                        var new_video_src = video_src.replace('&autoplay=0', '');
                        video_frame.first().attr('src',new_video_src + '&autoplay=1');
                        video_frame.addClass('playing');
                    }
                }
            } else if(div_percent < 20) {
                if (video_frame.hasClass('playing')) {
                    video_frame.addClass('pause');
                    video_frame[0].contentWindow.postMessage(messageFn('pause',video_frame.attr('src')),'*');
                }
            }
        }
    };

    CENOS_SP.delay = function(callback, ms) {
        var timer = 0;
        return function() {
            var context = this, args = arguments;
            clearTimeout(timer);
            timer = setTimeout(function () {
                callback.apply(context, args);
            }, ms || 0);
        };
    };

    /**
     * Document ready
     */
    $( function () {
        CENOS_SP.init();
    });
    $(window).scroll(function () {
        CENOS_SP.delay(CENOS_SP.video_scroll(),500);
    });

})(jQuery, window, document);
