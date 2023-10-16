(function ($) {
    "use strict";
    var $window   = $( window ),
        $document = $( document ),
        $body     = $( 'body' );
    var FMTPL = FMTPL || {};

    FMTPL.init = function () {
        this.Template_Type_Condition();
    };
    /**
     * Document ready
     */
    FMTPL.Template_Type_Condition = function() {
        $('#fmtpl_template_type').on('change',function () {
            var template_type = $(this).children("option:selected").val();
            if (template_type == 'type_header' || template_type == 'type_footer'){
                $('#fmtpl_condition_group').removeClass('fmtpl-hidden');
            } else {
                $('#fmtpl_condition_group').addClass('fmtpl-hidden');
            }
        });
    };
    $( function () {
        FMTPL.init();
    });
})(jQuery, window, document);