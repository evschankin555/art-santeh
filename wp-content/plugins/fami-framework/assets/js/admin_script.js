;(function ($) {
    $(document).on('click', '.tr-tab:not(".disabled"), .tr-tab-ex:not(".disabled")', function (e) {
        e.preventDefault();
        if (!$(this).hasClass('active')) {
            var tab_id = $(this).data('tab');
            $('.tr-tab, .tr-tab-ex').removeClass('active');
            $('.tr-tab-content > .tab-pane').removeClass('active');
            $('.tr-tab[data-tab="' + tab_id + '"],.tr-tab-ex[data-tab="' + tab_id + '"]').addClass('active');
            $("#" + tab_id).addClass('active');
        }
    });
    $(document).on('click', '.tr-tab.disabled, .tr-tab-ex.disabled, .welcome-icon.disabled', function (e) {
        e.preventDefault();
        return false;
    });
    $(document).on('click','.fmfw_preset_action', function (e) {
        e.preventDefault();
        var $_this = $(this),
            package = $_this.data('package'),
            version = $_this.data('version'),
            fmfw_card = $_this.closest('.fmfw-card');
        fmfw_card.addClass('loading');
        $.ajax({
            type: "POST",
            url: ajax_var.url,
            data: {action: 'preset_action',package:package, version:version , nonce: ajax_var.nonce},
            success:
                function(result){
                    fmfw_card.removeClass('loading');
                    var alert_type = 'success';
                    if (!result.status){
                        alert_type = 'error'
                    }
                    var msg  = result.msg;
                    if (typeof result.msg == 'undefined'){
                        msg = ' ';
                    }
                    $.iGrowl({
                        message: msg,
                        small: true,
                        delay: 3000,
                        placement: {
                            x: 'right',
                            y: 'bottom'
                        },
                        type: alert_type,
                        animShow: 'fadeInRight',
                        animHide: 'fadeOutRight',
                    });
                }
        });
    });
})(jQuery, window, document);