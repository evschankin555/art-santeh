(function ($) {
    "use strict";
    var $window   = $( window ),
        $document = $( document ),
        $body     = $( 'body' );
    var CENOS = CENOS || {};
    CENOS.init = function () {
        var _t = this;
        _t.offCanvas();
        _t.stickyHeader();
        _t.stickyHeaderMobile();
        _t.Vertical_Header();
        _t.Announcement();
        _t.CountDown();
        _t.SwiperInit();
        _t.woo_quantily();
        _t.Search();
        _t.Live_Search();
        _t.FillterBtn();
        _t.ToggleSubMenu();
        _t.productThumbnailZoom();
        _t.woofPagination();
        _t.WL_update();
        _t.Category_background();
        _t.Scroll_To_top();
        _t.stickyAtc();
        _t.Select2Init();
        _t.ClickDropdown();
        _t.popup();
        _t.loginPanelAuthenticate();
        setTimeout(function () {
            _t.Middle_Arrow();
            _t.woofClearButton();
            _t.checkScrollbar();
            _t.initPerfectScroll();
        },100);
    };
    CENOS.offCanvas = function () {
        $document.trigger("enhance");
    };
    CENOS.miniCart = function () {
        if ($('.cart_box').length){
            try {
            var wc_fragments = $.parseJSON(sessionStorage.getItem(wc_cart_fragments_params.fragment_name));

            if (wc_fragments && wc_fragments['div.widget_shopping_cart_content']) {

                $.each(wc_fragments, function (key, value) {
                    $(key).replaceWith(value);
                });

                $(document.body).trigger('wc_fragments_loaded');
            } else {
                throw 'No fragment';
            }

        } catch (err) {
            return;
            }
        }
    };
    CENOS.stickyHeader = function () {
        if (!$('.main-header-sticky').length){
            return;
        }
        var $header = $( '.main-header-sticky' ),
            $topbar = $( '#topbar' ),
            $top_announcement = $('.announcement-top'),
            offset = 1,
            t_op = true;
        if ($top_announcement.length){
            offset += $top_announcement.outerHeight();
            t_op = false;
        }
        if ( $topbar.length ) {
            offset += $topbar.outerHeight();
            t_op = false;
        }
        if (t_op) {
            offset += 2;
        }

        var bottomOffset = offset + $header.outerHeight();

        var stickyHeader = new Headroom( $header.get(0), {
            offset: offset,
            bottomOffset: bottomOffset
        });

        stickyHeader.init();
    };
    CENOS.stickyHeaderMobile = function () {
        if ($('.header-mobile-sticky').length == 0){
            return;
        }
        var $header = $( '.header-mobile-sticky' ),
            $top_announcement = $('.announcement-top'),
            offset = 1,
            t_op = true;
        if ($top_announcement.length){
            offset += $top_announcement.outerHeight();
            t_op = false;
        }
        if (t_op) {

            offset += 2;
        }

        var bottomOffset = offset + $header.outerHeight();
        var MobileStickyHeader = new Headroom( $header.get(0), {
            offset: offset,
            bottomOffset: bottomOffset,
        });

        MobileStickyHeader.init();
    };
    CENOS.Announcement = function () {
        if ($('.cenos-announcement-box').length == 0){
            return;
        }
        $document.on('click', '.cenos-announcement-box .close', function() {
            var dismiss_attr = $(this).attr('data-dismiss');
            if (typeof dismiss_attr !== typeof undefined && dismiss_attr !== false) {
                Cookies.set('announcement_dismissed',true);
            }
            $(this).closest('.cenos-announcement-box').addClass('closed');
        });
    };
    CENOS.CountDown = function() {
        if ($('[data-countdown]').length) {
            $('[data-countdown]').each(function () {
                var t = $(this),
                    time = t.attr('data-countdown'),
                    t_hour = t.data('text-hour') || cenos_JsVars.countDown_text.h,
                    t_min = t.data('text-min') || cenos_JsVars.countDown_text.m,
                    t_day = t.data('text-day') || cenos_JsVars.countDown_text.d,
                    t_sec = t.data('text-sec') || cenos_JsVars.countDown_text.s;
                t.countdown(time, function (event) {
                    var format = '<span class="box-count hrs"><span class="num">%H</span><span class="text">' + t_hour + '</span></span> <span class="box-count min"><span class="num">%M</span><span class="text">' + t_min + '</span></span> <span class="box-count secs"><span class="num">%S</span><span class="text">' + t_sec + '</span></span>';
                    if(event.offset.totalDays > 0) { format = '<span class="box-count days"><span class="num">%D</span> <span class="text">' + t_day + '</span></span>' + format; }
                    $(this).html(event.strftime(format));
                });
            });
        }
    };
    CENOS.SwiperInit = function(){
        var sw_selector = '.cenos-carousel';
        if ($(sw_selector).length > 0){
            $(sw_selector).each(function () {
                var $_this = $(this);
                if ($_this.hasClass('swiper-container-initialized')){
                    return;
                }
                var dataOption = $_this.data('swiper') || false,
                    sw_pani = $_this.data('pagination') || false,
                    sw_navi = $_this.data('navigation') || false;
                if (dataOption == false){
                    return;
                }
                if (!$_this.closest('.cenos-carousel-wrap').length){
                    $_this.wrap('<div class="cenos-carousel-wrap"></div>');
                }
                if (!$_this.find('.swiper-slide').length){
                    $_this.find('li').addClass('swiper-slide');
                }
                if ('string' == typeof(dataOption)){
                  dataOption = dataOption.replace(/\\/g, '');
                  dataOption = JSON.parse(dataOption);
                }
                var $carousel_wrap = $_this.closest('.cenos-carousel-wrap');
                if (sw_navi){
                    var has_navi = false;
                    if ($_this.find('.cenos_navigation_wrap').length || $carousel_wrap.find('.cenos_navigation_wrap').length){
                        has_navi = true;
                    }
                    if (!has_navi){
                        $_this.append('<div class="cenos_navigation_wrap"><div class="swiper-arrow-button swiper-button-next">'+cenos_JsVars.next_icon+'</div><div class="swiper-arrow-button swiper-button-prev">'+cenos_JsVars.prev_icon+'</div></div>');
                    }
                    dataOption.navigation = {
                        nextEl: $carousel_wrap.find('.swiper-button-next'),
                        prevEl: $carousel_wrap.find('.swiper-button-prev'),
                    };
                }
                var sw_pagination_use = ['page','dynamic','progress','fraction'];
                if (sw_pagination_use.indexOf(sw_pani) != -1) {
                        $_this.append('<div class="swiper-pagination"></div>');
                    dataOption.pagination = {
                        el: '.swiper-pagination'
                    };
                    switch (sw_pani) {
                        case 'dynamic':
                            dataOption.pagination.dynamicBullets = true;
                            dataOption.pagination.clickable = true;
                            break;
                        case 'progress':
                            dataOption.pagination.type = 'progressbar';
                            break;
                        case 'fraction':
                            dataOption.pagination.type = 'fraction';
                            break;
                        default:
                            dataOption.pagination.clickable = true;
                            break;
                    }
                }
                var cenos_Swiper = new Swiper($_this,dataOption);
            });
        }
    };
    CENOS.woo_quantily = function () {
        $body.on('click', '.quantity .quantity-plus', CENOS.delay(function () {
            var obj_qty = $(this).closest('.quantity').find('input.qty'),
                val_qty = parseInt(obj_qty.val()),
                max_qty = parseInt(obj_qty.attr('max')),
                step_qty = parseInt(obj_qty.attr('step'));
            if (isNaN(val_qty)){
                val_qty = 0;
            }
            if (isNaN(step_qty)){
                step_qty = 1;
            }
            val_qty = val_qty + step_qty;
            if (max_qty && val_qty > max_qty) {
                val_qty = max_qty;
            }
            obj_qty.val(val_qty);
            obj_qty.trigger("change");
            obj_qty.cenos_ajax_update_quantity();
            return false;
        }, 200));
        $body.on('click', '.quantity .quantity-minus',CENOS.delay( function () {
            var obj_qty = $(this).closest('.quantity').find('input.qty'),
                val_qty = parseInt(obj_qty.val()),
                min_qty = parseInt(obj_qty.attr('min')),
                max_qty = parseInt(obj_qty.attr('max')),
                step_qty = parseInt(obj_qty.attr('step'));
            val_qty = val_qty - step_qty;
            if (min_qty && val_qty < min_qty) {
                val_qty = min_qty;
            }
            if (!min_qty && val_qty < 0) {
                val_qty = 0;
            }
            obj_qty.val(val_qty);
            obj_qty.trigger("change");
            obj_qty.cenos_ajax_update_quantity();
            return false;
        }, 200));
        $.fn.cenos_ajax_update_quantity = function () {
            var item_hash = $( this ).attr( 'name' ).replace(/cart\[([\w]+)\]\[qty\]/g, "$1"),
                item_quantity = $( this ).val(),
                currentVal = parseFloat(item_quantity);
            var data = {
                action: 'cenos_update_quantity',
                hash: item_hash,
                quantity: currentVal
            };
            $.post(cenos_JsVars.ajaxurl, data, function (response) {
                if ( !response || !response.fragments) {
                    return;
                }
                if ($("[name='update_cart']").length){
                    $("[name='update_cart']").removeAttr('disabled').trigger("click");
                }
                $(document.body).trigger('wc_fragment_refresh');
            });
        };
    };
    CENOS.delay = function(callback, ms) {
        var timer = 0;
        return function() {
            var context = this, args = arguments;
            clearTimeout(timer);
            timer = setTimeout(function () {
                callback.apply(context, args);
            }, ms || 0);
        };
    };
    CENOS.Search = function() {
        if ($('.fm-search-form').length == 0){
            return;
        }
        $('.fm-search-form input[name="s"]').on('keyup', CENOS.delay(function (e) {
            var q = $(this).val(),
                search_form = $(this).closest('.fm-search-form');
            if (q ==''){
                search_form.find('.btn_clear_text').removeClass('show');
                search_form.find('.search-result').html('');
                $(this).attr('last-search-field', q);
                return;
            }
            search_form.find('.btn_clear_text').addClass('show');
            if (q == $(this).attr('last-search-field')) { return;}
            $(this).attr('last-search-field', q);
        }, 500));
        $document.on('click', '.fm-search-form .btn_clear_text', function (e) {
            e.preventDefault();
            var search_form = $(this).closest('.fm-search-form'),
                s_input = search_form.find('input[name="s"]');
            search_form.trigger("reset");
            s_input.val('').attr('last-search-field', '').focus();
            if (search_form.hasClass('ajax_search')){
                s_input.typeahead('val', '');
            }
            $(this).removeClass('show');
        });
    };
    CENOS.Live_Search = function(){
        if ($('.fm-search-form.ajax_search').length == 0){
            return;
        }
        var search_form = $('.fm-search-form.ajax_search'),
            search_options = cenos_JsVars.search_options;

        var searchProducts = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('title'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            prefetch: cenos_JsVars.ajaxurl + '?action=live_search_products',
            limit: search_options.limit_results,
            remote: {
                url: cenos_JsVars.ajaxurl + '?action=live_search_products',
                prepare: function(query, settings) {
                    settings.url += '&s=' + query;
                    var cat_val = false;
                    if (search_form.find('.search_categories').length){
                        cat_val = search_form.find('.search_categories').val();
                    }
                    if (!cat_val) return settings;
                    settings.url + '&cat=' + encodeURIComponent(cat_val);
                    return settings;
                },
            }
        });
        var initSearch = false;
        $document.on('focus', 'input.search_text_input', function (){
            if(!initSearch) {
                searchProducts.initialize();
                initSearch = true;
            }
        });
        $('.search_text_input').typeahead({
            classNames: {
                menu: 'fm-search-results',
                dataset: 'fm-dataset'
            },
            minLength: 3,
            hint: true,
            highlight: true,
            backdrop: {
                "opacity": 0.8,
                "filter": "alpha(opacity=80)",
            },
            backdropOnFocus: true,
            callback: {
                onInit: function () {
                    searchProducts.initialize();
                },
                onSubmit: function(node, form, item, event) {
                    form.submit();
                }
            }
        },
        {
            name: 'search',
            source: searchProducts.ttAdapter(),
            display: 'title',
            limit: search_options.limit_results,
            templates: {
                empty: [
                    '<div class="empty-message">',
                    search_options.empty_msg,
                    '</div>'
                ].join('\n'),
                suggestion: Handlebars.compile(search_options.live_search_template)
            }
        }).on('typeahead:asyncrequest', function() {
            search_form.append('<span class="cenos-ajax-search-loader"></span>');
        }).on('typeahead:asynccancel typeahead:asyncreceive', function() {
            $('.cenos-ajax-search-loader').remove();
        }).on('typeahead:open', function () {
            var _input = $(this),
                search_form_wrapper = _input.closest('.search_form_wrapper'),
                result_w = search_form_wrapper.find('.fm-dataset-search');
            if (search_form_wrapper.closest('.offcanvas-content').length){
                search_form_wrapper.closest('.offcanvas-content').addClass('result_search_active');
            } else if (search_form_wrapper.closest('.modal-content').length) {
                search_form_wrapper.closest('.modal-content').addClass('result_search_active');
            } else {
                search_form_wrapper.addClass('result_search_active');
            }
            if (!cenos_JsVars.on_devices){
                new PerfectScrollbar( result_w.get( 0 ), {
                    suppressScrollX: true
                });
            }

        }).on('typeahead:idle', function () {
            var _input = $(this),
                search_form_wrapper = _input.closest('.search_form_wrapper');
            if (search_form_wrapper.closest('.offcanvas-content').length){
                search_form_wrapper.closest('.offcanvas-content').removeClass('result_search_active');
            } else if (search_form_wrapper.closest('.modal-content').length) {
                search_form_wrapper.closest('.modal-content').removeClass('result_search_active');
            }
        });
    };
    CENOS.FillterBtn = function() {
        if ($('.cenos-filter-btn').length){
            var filter_btn = $('.cenos-filter-btn'),
                mode = filter_btn.data('mode');
            if (typeof mode != 'undefined' && (mode == 'dropdown' || mode == 'mobile')){
                $document.on('click','.cenos-filter-btn',function (e) {
                    e.preventDefault();
                    if (mode == 'dropdown'){
                        if ($('.filter-content-wrap.dropdown').length){
                            $('.filter-content-wrap.dropdown').toggleClass('show');
                        }
                    } else if( mode == 'mobile' ) {
                        //mobile-dropdown
                        if ($('.filter-content-wrap.mobile-dropdown').length){
                            $('.filter-content-wrap.mobile-dropdown').toggleClass('show');
                        }
                    }

                });
                $document.on('click','.cenos-close-filter-btn',function (e) {
                    e.preventDefault();
                    if (mode == 'dropdown'){
                        if ($('.filter-content-wrap.dropdown').length){
                            $('.filter-content-wrap.dropdown').removeClass('show');
                        }
                    } else if( mode == 'mobile' ) {
                        //mobile-dropdown
                        if ($('.filter-content-wrap.mobile-dropdown').length){
                            $('.filter-content-wrap.mobile-dropdown').removeClass('show');
                        }
                    }
                });
            }
        }
    };
    CENOS.ToggleSubMenu = function() {
        $document.on('click','.toggle-submenu',function (e) {
            e.preventDefault();
            const pr = $(this).closest('li');
            if (pr.hasClass('menu-item-has-children') || pr.hasClass('page_item_has_children')){
                pr.toggleClass('open');
            }
        });
    };
    CENOS.Select2Init = function() {
        if ($('body').hasClass('wcfm-dashboard-page')){
            return;
        }
        if ($('select:not(.hide)').length){
            setTimeout(function () {
                $('select').each(function () {
                    var _o = $(this);
                    if (_o.attr("name") != 'rating'){
                        if (!_o.hasClass("select2-hidden-accessible")) {
                            _o.select2({
                                //dropdownParent: _o.parent(),
                                minimumResultsForSearch: Infinity
                            });
                        }
                    }
                });
            },50);
        }
    };
    CENOS.productThumbnailZoom = function() {
        if ($( '.product-thumbnail-zoom' ).length == 0) {
            return;
        }
        $( '.product-thumbnail-zoom' ).each( function() {
            var $el = $( this );
            $el.zoom( {
                url: $el.attr( 'data-zoom_image' )
            } );
        } );
    };
    CENOS.woofClearButton = function() {
        var woof_products_top_panel =  $('.woof_products_top_panel');
        if (woof_products_top_panel.length) {
            if (woof_products_top_panel.find('.cenos_reset_filter_button').length == 0 && woof_products_top_panel.find('.woof_remove_ppi').length){
                woof_products_top_panel.prepend(cenos_JsVars.clear_filter);
            }
        }
    };
    CENOS.woofPagination = function() {
        if (!cenos_JsVars.is_woof){
            return;
        }
        var url_page_num = function (url) {
            var p = 1,
                res = url.split("paged=");
            if (typeof res[1] !== 'undefined'){
                p = parseInt(res[1]);
            } else {
                res = url.split("page/");
                if (typeof res[1] !== 'undefined'){
                    p = parseInt(res[1]);
                } else {
                    res = url.split("page=");
                    if (typeof res[1] !== 'undefined'){
                        p = parseInt(res[1]);
                    }
                }
            }
            return p;
        };
        if ($('.cenos_woof_pagination_trigger').length) {
            var cenos_woof_pagi = $('.cenos_woof_pagination_trigger'),
                next_btn = cenos_woof_pagi.find('.next.page-numbers');
            if (!next_btn.length || next_btn.hasClass('disabled')){
                $('.cenos_woof_pagination_trigger').addClass('disabled');
                return;
            }
            var l = next_btn.attr('href'),
                next_page = url_page_num(l);

            if (cenos_woof_pagi.hasClass('scroll')) {
                var pxFromWindowBottomToBottom,
                    pxFromMenuToBottom;

                $window.scroll(function () {
                    pxFromMenuToBottom = Math.round($document.height() - cenos_woof_pagi.offset().top);
                    pxFromWindowBottomToBottom = 0 + $document.height() - ($window.scrollTop()) - $window.height();
                    if ((pxFromWindowBottomToBottom) < pxFromMenuToBottom) {
                        if (woof_current_values.page == next_page){
                            return;
                        }
                        woof_show_info_popup(woof_lang_loading);
                        woof_ajax_page_num = next_page;
                        woof_is_ajax = true;
                        woof_current_values.page = next_page;
                        var next_link = woof_get_submit_link();
                        var data_shortcode = $('#woof_results_by_ajax').data('shortcode');
                        var data = {
                            action: "woof_draw_products",
                            fmaction: 'cenos_load_more',
                            link: next_link,
                            page: woof_ajax_page_num,
                            shortcode: data_shortcode,
                            woof_shortcode: $('div.woof').data('shortcode')
                        };
                        $.post(woof_ajaxurl, data, function (content) {
                            content = $.parseJSON(content);
                            if(typeof content.products != "undefined"){
                                var vitual_div = $('<div></div>').append(content.products),
                                    pds = vitual_div.find('.cenos-product-item'),
                                    pagi = vitual_div.find('ul.page-numbers'),
                                    result_count = vitual_div.find('.woocommerce-result-count');
                                if (pds.length) {
                                    $.each(pds,function (i,e) {
                                        setTimeout(function () {
                                            $(e).appendTo($('#woof_results_by_ajax .products')).hide().fadeIn('slow');
                                        }, i*250);
                                    });
                                }
                                if (pagi.length) {
                                    $('#woof_results_by_ajax ul.page-numbers').html(pagi.html());
                                }
                                if (result_count.length) {
                                    $('#woof_results_by_ajax .woocommerce-result-count').html(result_count.html());
                                }
                                next_btn = pagi.find('.next.page-numbers');
                                if (next_btn.length && !next_btn.hasClass('disabled')){
                                    l = next_btn.attr('href');
                                    next_page = url_page_num(l);
                                } else {
                                    cenos_woof_pagi.addClass('disabled');
                                }
                            }
                        }).always(function() {
                            woof_hide_info_popup();
                        });
                    }
                });
            }
            else if (cenos_woof_pagi.hasClass('load')) {
                $document.on('click','.cenos_woof_pagination_trigger_action',function (el) {
                    el.preventDefault();
                    var _btn_action = $(this);
                    if (_btn_action.hasClass('disabled') || woof_current_values.page == next_page){
                        return;
                    }
                    woof_show_info_popup(woof_lang_loading);
                    woof_ajax_page_num = next_page;
                    woof_is_ajax = true;
                    woof_current_values.page = next_page;
                    var next_link = woof_get_submit_link();
                    var data_shortcode = $('#woof_results_by_ajax').data('shortcode');
                    var data = {
                        action: "woof_draw_products",
                        fmaction: 'cenos_load_more',
                        link: next_link,
                        page: woof_ajax_page_num,
                        shortcode: data_shortcode,
                        woof_shortcode: $('div.woof').data('shortcode')
                    };
                    $.post(woof_ajaxurl, data, function (content) {
                        content = $.parseJSON(content);
                        if(typeof content.products != "undefined"){
                            var vitual_div = $('<div></div>').append(content.products),
                                pds = vitual_div.find('.cenos-product-item'),
                                pagi = vitual_div.find('ul.page-numbers'),
                                result_count = vitual_div.find('.woocommerce-result-count');
                            if (pds.length) {
                                $.each(pds,function (i,e) {
                                    setTimeout(function () {
                                        $(e).appendTo($('#woof_results_by_ajax .products')).hide().fadeIn('slow');
                                    }, i*250);
                                });
                            }
                            if (pagi.length) {
                                $('#woof_results_by_ajax ul.page-numbers').html(pagi.html());
                            }
                            if (result_count.length) {
                                $('#woof_results_by_ajax .woocommerce-result-count').html(result_count.html());
                            }
                            next_btn = pagi.find('.next.page-numbers');
                            if (next_btn.length){
                                l = next_btn.attr('href');
                                next_page = url_page_num(l);
                                if (woof_current_values.page == next_page) {
                                    _btn_action.addClass('disabled');
                                    _btn_action.closest('.cenos_woof_pagination_trigger').addClass('disabled');
                                }
                            } else {
                                _btn_action.addClass('disabled');
                                _btn_action.closest('.cenos_woof_pagination_trigger').addClass('disabled');
                            }
                        }
                    }).always(function() {
                        woof_hide_info_popup();
                    });
                });
            }
        }
        $document.on('click','.cenos_reset_filter_button',function (e) {
            e.preventDefault();
            if ($(this).hasClass('has_demo')){
              woof_ajax_page_num = 1;
              woof_ajax_redraw = 0;
              woof_reset_btn_action=true;
              var new_woof_value = {};
              if (woof_is_permalink) {
                  if (woof_current_values.hasOwnProperty('header_style')){
                      new_woof_value.header_style = woof_current_values.header_style;
                  }
                  if (woof_current_values.hasOwnProperty('announcement_style')){
                      new_woof_value.announcement_style = woof_current_values.announcement_style;
                  }
                  if (woof_current_values.hasOwnProperty('product_style')){
                      new_woof_value.product_style = woof_current_values.product_style;
                  }
                  if (woof_current_values.hasOwnProperty('catalog_style')){
                      new_woof_value.catalog_style = woof_current_values.catalog_style;
                  }
                  woof_current_values = new_woof_value;
                  woof_submit_link(woof_get_submit_link().split("page/")[0]);
              } else {
                var link = woof_shop_page;
                if (woof_current_values.hasOwnProperty('header_style')){
                    new_woof_value.header_style = woof_current_values.header_style;
                }
                if (woof_current_values.hasOwnProperty('announcement_style')){
                    new_woof_value.announcement_style = woof_current_values.announcement_style;
                }
                if (woof_current_values.hasOwnProperty('product_style')){
                    new_woof_value.product_style = woof_current_values.product_style;
                }
                if (woof_current_values.hasOwnProperty('catalog_style')){
                    new_woof_value.catalog_style = woof_current_values.catalog_style;
                }
                if (woof_current_values.hasOwnProperty('page_id')) {
                    link = location.protocol + '//' + location.host + "/?page_id=" + woof_current_values.page_id;
                    new_woof_value.page_id = woof_current_values.page_id;
                    woof_current_values = new_woof_value;
                    woof_get_submit_link();
                }
                woof_submit_link(link);
                if (woof_is_ajax) {
                    history.pushState({}, "", link);
                    woof_current_values = new_woof_value;
                }
              }
            } else {
                woof_ajax_page_num = 1;
                woof_ajax_redraw = 0;
                woof_reset_btn_action = true;
                if (woof_is_permalink) {
                    woof_current_values = {};
                    woof_submit_link(woof_get_submit_link().split("page/")[0]);
                } else {
                    var link = woof_shop_page;
                    if (woof_current_values.hasOwnProperty('page_id')) {
                        link = location.protocol + '//' + location.host + "/?page_id=" + woof_current_values.page_id;
                        woof_current_values = {'page_id': woof_current_values.page_id};
                        woof_get_submit_link();
                    }
                    woof_submit_link(link);
                    if (woof_is_ajax) {
                        history.pushState({}, "", link);
                        if (woof_current_values.hasOwnProperty('page_id')) {
                            woof_current_values = {'page_id': woof_current_values.page_id};
                        } else {
                            woof_current_values = {};
                        }
                    }
                }
                return false;
            }
        });
    };
    CENOS.WL_update = function(){
        var update_wishlist_count = function(){
            var data = {
                action: 'cenos_get_wishlist_count'
            };
            $.post(cenos_JsVars.ajaxurl, data, function (response) {
                if (!response) {
                    return false;
                }
                if ($('.wishlist_counter').length) {
                    $('.wishlist_counter').html(response);
                } else {
                    if ($('.wishlist-btn').length){
                        if ($('.wishlist-btn').hasClass('has-counter')){
                            $('.wishlist-btn').append('<span class="wishlist_counter">' + response + '</span>');
                        }
                    }

                }
            });
        };
        $body.on('added_to_wishlist', function (t,el_wrap) {
            $('#yith-wcwl-popup-message').remove();
            update_wishlist_count();
        });
        $body.on('removed_from_wishlist', function (el, el_wrap) {
            update_wishlist_count();
        });
        $body.on('tinvwl_wishlist_added_status',function (el, el_wrap) {
            update_wishlist_count();
        });
    };
    CENOS.Category_background = function() {
        if ($('.cenos-category-background').length){
            $(document).on("mouseover",'.categories-item' ,function () {
                var $this = $(this);
                if ($this.hasClass('selected')){
                    return false;
                }
                var id = $(this).data('id');
                $('.cenos-category-background .categories-item').removeClass('selected');
                $this.addClass('selected');

                if ($('.cenos-category-background .cat-bg').length){
                    $('.cenos-category-background .cat-bg').removeClass('selected');
                    $('#'+id).addClass('selected');
                }
            });
        }
    };
    CENOS.Vertical_Header = function() {
        if ($body.hasClass('vertical-header')){
            $document.on('click','.menu-switch-link',function (e) {
                e.preventDefault();
                if (!$('.site-header').hasClass('header-open')){
                    $body.addClass('no-scroll');
                    $('.site-header').addClass('header-open');
                }
            });
            $document.on('click','.header-vertical-switch-btn',function (e) {
                e.preventDefault();
                if ($('.site-header').hasClass('header-open')){
                    $body.removeClass('no-scroll');
                    $('.site-header').removeClass('header-open');
                }
            });
        }
    };
    CENOS.Middle_Arrow = function(){
        if ($('.fmtpl-products.carousel').length){
            $('.fmtpl-products.carousel').each(function () {
                var _c = $(this);
                if (_c.hasClass('fmtpl-products-layout-layout1') || _c.hasClass('fmtpl-products-layout-default')){
                    var img_height = _c.find('.product-thumbnail img').outerHeight();
                    if (img_height){
                        _c.find('.fmtpl-elementor-main-swiper .elementor-swiper-button').css('top',img_height/2 +'px');
                    }
                }
            });

        }
    };
    CENOS.scroll_top_toggle =  function () {
        var currentPos = $window.scrollTop();
        if ($('.scroll_to_top').length){
            if (currentPos > 1000) {
                $('.scroll_to_top').addClass('show');
            } else {
                $('.scroll_to_top').removeClass('show');
            }
            var scrollPercent = Math.round(100 * currentPos / ($document.height() - $window.height()));
            var $round = $('.backtotop-round'),
                roundRadius = $round.find('circle').attr('r'),
                roundCircum = 2 * roundRadius * Math.PI,
                roundDraw = scrollPercent * roundCircum / 100;
            $round.css('stroke-dasharray', roundDraw  + ' 999');
        }
        if ($('.scroll_btn_box').length){
            $('.scroll_btn_box').each(function () {
                if ($(this).hasClass('on_single_mobile')){
                    if ($(this).hasClass('scroll_cart_btn')){
                        if (currentPos > 100) {
                            $(this).addClass('show');
                        } else {
                            $(this).removeClass('show');
                        }
                    }
                } else {
                    if (currentPos > 1000) {
                        $(this).addClass('show');
                    } else {
                        $(this).removeClass('show');
                    }
                }
            });

        }
    };
    CENOS.Scroll_To_top = function() {
        $document.on('click', 'a.scroll_to_top', function (e) {
            e.preventDefault();
            $('html, body').animate({scrollTop: 0}, 800);
            return false;
        });
    };
    CENOS.stickyAtcToggle = function(){
        if ($('.cenos-sticky-atc-btn').length){
            var $summary = $('.summary .single_add_to_cart_button');
            if (!$summary.length){
                $('body').removeClass('has-sticky-atc');
                return false;
            }
            var summaryOffset = $summary.offset().top + $summary.outerHeight(),
                windowScroll = $window.scrollTop(),
                windowHeight = $window.height(),
                documentHeight = $document.height();
            if (summaryOffset < windowScroll && windowScroll + windowHeight != documentHeight) {
                $('.cenos-sticky-atc-btn').addClass('show');
            } else if (summaryOffset > windowScroll) {
                $('.cenos-sticky-atc-btn').removeClass('show');
            }
        } else {
            $('body').removeClass('has-sticky-atc');
        }
    };
    CENOS.stickyAtc = function() {
        if ($('.cenos-sticky-variations-atc').length){
            $document.on('click', '.cenos-sticky-variations-atc', function (e) {
                e.preventDefault();
                var $variations_form = $('.variations_form'),
                    variationsOffset = $variations_form.offset().top - 100;
                $('html, body').animate({scrollTop: variationsOffset}, 800);
                return false;
            });
        }
    };
    CENOS.popup = function() {
        if ( ! cenos_JsVars.popup ) {
            return;
        }
        var days = parseInt( cenos_JsVars.popup_frequency ),
            delay = parseInt( cenos_JsVars.popup_visible_delay );

        if (document.cookie.match( /^(.*;)?\s*cenos_popup\s*=\s*[^;]+(.*)?$/ ) ) {
            return;
        }

        delay = Math.max( delay, 0 );
        delay = 'delay' === cenos_JsVars.popup_visible ? delay : 0;

        $( window ).on( 'load', function() {
            setTimeout( function() {
                $('#popup-modal').modal('show');
            }, delay * 1000 );
        } );
        $('#popup-modal').on('shown.bs.modal', function (e) {
            var date = new Date(),
                value = date.getTime();
            date.setTime( date.getTime() + (days * 24 * 60 * 60 * 1000) );
            if (days > 0){
                document.cookie = 'cenos_popup=' + value + ';expires=' + date.toGMTString() + ';path=/';
            }
        });
        $document.on('click','.disable_popup_again',function (e) {
            e.preventDefault();
            Cookies.set('cenos_popup',true);
            $('#popup-modal').modal('hide');
        });
    };

    CENOS.initPerfectScroll = function(){
        if (cenos_JsVars.on_devices){
            return;
        }
        if ($('.woocommerce-mini-cart').length){
            new PerfectScrollbar( $('.woocommerce-mini-cart').get( 0 ), {
                suppressScrollX: true
            } );
        }
        if ($('.filter-content-wrap').length){
            new PerfectScrollbar( $('.filter-content-wrap').get( 0 ), {
                suppressScrollX: true
            } );
        }
        if ($('.hamburger-screen-content').length){
            new PerfectScrollbar( $('.hamburger-screen-content').get( 0 ), {
                suppressScrollX: true
            } );
        }
    };
    CENOS.checkScrollbar = function(){
        if ($document.height() > $window.height()) {
            $body.addClass('hasVerticalScrollbar');
        } else {
            $body.removeClass('hasVerticalScrollbar');
        }
    };
    CENOS.ClickDropdown = function() {
        if ($('.click_dropdown').length) {
            $document.on('click','.click_dropdown .current',function (e) {
                e.preventDefault();
                $(this).closest('.click_dropdown').toggleClass('opened');
            });
            $document.on('click',function (e) {
                if (!$(e.target).closest('.click_dropdown').length && $('.click_dropdown').hasClass('opened')){
                    e.preventDefault();
                    $('.click_dropdown').removeClass('opened');
                }
            });
        }
    };
    CENOS.loginPanelAuthenticate = function() {
        $( '#modal-fm-login, #canvas-fm-login' ).on( 'submit', 'form.login', function authenticate( event ) {
            var username = $( 'input[name=username]', this ).val(),
                password = $( 'input[name=password]', this ).val(),
                remember = $( 'input[name=rememberme]', this ).is( ':checked' ),
                nonce = $( 'input[name=woocommerce-login-nonce]', this ).val(),
                $button = $( '[type=submit]', this ),
                $form = $( this ),
                $box = $form.next( '.woocommerce-error' );

            if ( ! username ) {
                $( 'input[name=username]', this ).focus();

                return false;
            }

            if ( ! password ) {
                $( 'input[name=password]', this ).focus();

                return false;
            }

            $form.find( '.woocommerce-error' ).remove();
            $button.html( '<span class="spinner"></span>' );

            if ( $box.length ) {
                $box.fadeOut();
            }
            $.post(
                cenos_JsVars.ajaxurl,
                {
                    action: 'cenos_login_authenticate',
                    security: nonce,
                    username: username,
                    password: password,
                    remember: remember
                },
                function( response ) {
                    if ( ! response.success ) {
                        if ( ! $box.length ) {
                            $box = $( '<div class="woocommerce-error" role="alert"/>' );
                            $box.append( '<ul class="error-message" />' );
                            $box.hide().prependTo( $form );
                        }
                        $box.find( '.error-message' ).html( '<li>' + response.data + '</li>' );
                        $box.fadeIn();
                        $button.html( $button.attr( 'value' ) );
                    } else {
                        $button.html( $button.data( 'signed' ) );
                        window.location.reload();
                    }
                }
            );

            event.preventDefault();
        } ).on( 'click', '.woocommerce-error .close-message', function() {
            // Remove the error message to fix the layout issue.
            $( this ).closest( '.woocommerce-error' ).fadeOut( function() {
                $( this ).remove();
            } );

            return false;
        } );
    };
    /**
     * Document ready
     */
    $( function () {
        CENOS.init();
        $document.on('woof_ajax_done',function () {
            if ($('#woof_results_by_ajax .cenos-carousel').length){
                CENOS.SwiperInit();
            }
            CENOS.woofClearButton();
            if ($('#woof_results_by_ajax [data-offcanvas-trigger]').length) {
                CENOS.offCanvas();
            }
            if ($('#woof_results_by_ajax select:not(.select2-hidden-accessible)').length) {
                CENOS.Select2Init();
            }
            setTimeout(function () {
                $('.variations_form:not(.wvs-loaded)').each(function () {
                    $(this).wc_variation_form();
                });
            },100);
        });
        $document.ajaxComplete(function (event, xhr, settings ) {
            if (typeof settings.data == 'undefined'){
                return;
            }
            var data_str = settings.data.toString();
            if (!cenos_JsVars.on_devices){
                if (settings.url.indexOf('get_refreshed_fragments')){
                    if ($('.woocommerce-mini-cart').length){
                        new PerfectScrollbar( $('.woocommerce-mini-cart').get(0), {
                            suppressScrollX: true
                        } );
                    }
                }
            }
            if (cenos_JsVars.scoll_on_pagination && data_str.indexOf('woof_draw_products') !== -1 && data_str.indexOf('cenos_load_more') === -1 && $('.woof_shortcode_output').length){
                $('html,body').animate({
                    scrollTop: $('.woof_shortcode_output').offset().top - 100
                }, 'slow');
            }
        });
        $document.on('click','.woocommerce-product-gallery__image > a',function (e) {
            e.preventDefault();
        });
        $body.on('added_to_cart', function (event, fragments, cart_hash, button) {
            if (typeof cenos_JsVars.shop_added_to_cart_notice == 'undefined' || !cenos_JsVars.shop_added_to_cart_notice || typeof cenos_JsVars.shop_added_to_cart_message == 'undefined'){
                return;
            }
            var _option = {
                message: cenos_JsVars.shop_added_to_cart_message,
                type: 'success',
                small: true,
                delay: 3000,
                placement: {
                    x: 'right',
                    y: 'bottom'
                },
                animShow: 'fadeInRight',
                animHide: 'fadeOutRight',
            };
            var _p = button.closest('.cenos-product-item');
            if (typeof cenos_JsVars.shop_cart_notice_name != 'undefined' && cenos_JsVars.shop_cart_notice_name){
                var p_name = _p.find('.woocommerce-loop-product__title a').text();
                _option.title = p_name;
            }
            if (typeof cenos_JsVars.shop_cart_notice_image != 'undefined' && cenos_JsVars.shop_cart_notice_image){
                var p_img = _p.find('.product-thumbnail img'),
                    p_src = '';
                if (p_img.length){
                    p_src = p_img.attr('src');
                    _option.image = {
                        src: p_src,
                        class: 'product-image'
                    }
                }
            }
            $.iGrowl(_option);
        });
        $document.on('click','.woocommerce-product-gallery__image > a',function (e) {
            e.preventDefault();
        });
        var TO = false;
        $window.resize(function() {
            if(TO !== false)
                clearTimeout(TO);
            TO = setTimeout(function () {
                CENOS.Middle_Arrow();
                CENOS.checkScrollbar();
            },250);
        });
        $window.scroll(function () {
            CENOS.scroll_top_toggle();
            CENOS.stickyAtcToggle();
        });
    });
})(jQuery, window, document);
