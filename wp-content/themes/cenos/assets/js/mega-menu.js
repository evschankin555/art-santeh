;(function ($) {
    "use strict"; // Start of use strict
    /* ---------------------------------------------
     Resize mega menu
     --------------------------------------------- */
    function responsive_megamenu_item(container, element) {
        if ( container != 'undefined' ) {
            var container_width  = 0,
                container_offset = container.offset();

            if ( typeof container_offset != 'undefined' ) {
                container_width = container.innerWidth();
                setTimeout(function () {
                    $(element).children('.mega-menu-container').css({'max-width': container_width + 'px'});
                    var sub_menu_width = $(element).children('.mega-menu-container').outerWidth(),
                        item_width     = $(element).outerWidth();
                    $(element).children('.mega-menu-container').css({'left': '-' + (sub_menu_width / 2 - item_width / 2) + 'px'});
                    var container_left  = container_offset.left,
                        container_right = (container_left + container_width),
                        item_left       = $(element).offset().left,
                        overflow_left   = (sub_menu_width / 2 > (item_left - container_left - 30)),
                        overflow_right  = ((sub_menu_width / 2 + item_left) > container_right - 30),
                        left = null;

                    if ( overflow_left ) {
                        left = (item_left - container_left - 30);
                        $(element).children('.mega-menu-container').css({'left': -left + 'px'});
                    }
                    if ( overflow_right && !overflow_left ) {
                        left = (item_left - container_left) - (container_width - sub_menu_width);
                        $(element).children('.mega-menu-container').css({'left': -left + 'px'});
                    }
                }, 100);
            }
        }
    }

    $.fn.cenos_resize_megamenu = function () {
        var _this = $(this);
        _this.on('cenos_resize_megamenu', function () {
            var window_size = $('body').innerWidth();
            window_size += cenos_get_scrollbar_width();
            if ( $(this).length > 0 && window_size > 991 ) {
                $(this).each(function () {
                    var _container        = $('.header-container');
                    responsive_megamenu_item(_container, $(this));
                });
            }
        }).trigger('cenos_resize_megamenu');
        var m_rs = false;
        $(window).on('resize', function () {
            if(m_rs !== false)
                clearTimeout(m_rs);
            m_rs = setTimeout(function () {
                _this.trigger('cenos_resize_megamenu');
            },250);
        });
    };

    function cenos_get_scrollbar_width() {
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
    }
    /* ---------------------------------------------
     Scripts load
     --------------------------------------------- */
    window.addEventListener('DOMContentLoaded',
        function (ev) {
            //for non ajax menu load
            if ($('.main-navigation .menu-item-custom-width').length){
                $('.main-navigation .menu-item-custom-width').cenos_resize_megamenu();

            }
        }, false);
})(jQuery); // End of use strict