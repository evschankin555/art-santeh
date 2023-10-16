! function($) {
    "use strict";
    const controls = window.top.wp.customize.control;
    var values = new Object(),
        timer,
        getValue = function(control){
            if (values.hasOwnProperty(control)) {
                return values[control];
            } else {
                return controls(control).params.value;
            }
        };
}(jQuery);
