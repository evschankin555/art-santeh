(function ($, elementor) {
    "use strict";
    var Cenos_Element = {
        init: function () {
            var widgets = {
                'cenos-advance-banner.default': Cenos_Element.Adv_banner,
            };
            $.each(widgets, function (widget, callback) {
                elementor.hooks.addAction('frontend/element_ready/' + widget, callback);
            });
        },
        Adv_banner: function (e) {
            var e_adv = e.find('.cenos-advance-banner-box-img');
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
        }
    };
    $(window).on('elementor/frontend/init', Cenos_Element.init);
}(jQuery, window.elementorFrontend));