(function ($) {
    "use strict";
    var $window   = $( window ),
        $document = $( document ),
        $body     = $( 'body' );
    var CENOS_SETUP = CENOS_SETUP || {};

    CENOS_SETUP.init = function () {
        var _t = this;
        _t.Load();
    };
    CENOS_SETUP.Load = function() {
        $('.button-next').on( 'click', function(e) {
            var loading_button = familab_loading_button(this);
            if(!loading_button){
                return false;
            }
            if($(this).data('callback') && typeof callbacks[$(this).data('callback')] != 'undefined'){
                // we have to process a callback before continue with form submission
                callbacks[$(this).data('callback')](this);
                return false;
            }else{
                loading_content();
                return true;
            }
        });
        $('.button-upload').on( 'click', function(e) {
            e.preventDefault();
            renderMediaUploader();
        });
        $('.theme-presets a').on( 'click', function(e) {
            e.preventDefault();
            var $ul = $(this).parents('ul').first();
            $ul.find('.current').removeClass('current');
            var $li = $(this).parents('li').first();
            $li.addClass('current');
            var newcolor = $(this).data('style');
            var newheader = $(this).data('header');
            $('#new_style').val(newcolor);
            $('#new_header').val(newheader);
            return false;
        });
    };
    var callbacks = {
        install_plugins: function(btn){
            var plugins = new PluginManager();
            plugins.init(btn);
        },
        install_content: function(btn){
            var content = new ContentManager();
            content.init(btn);
        }
    };

    function loading_content(){
        $('.cenos-setup-content').block({
            message: null,
            overlayCSS: {
                background: '#fff',
                opacity: 0.6
            }
        });
    }

    function PluginManager(){

        var complete;
        var items_completed = 0;
        var current_item = '';
        var $current_node;
        var current_item_hash = '';
        var try_again = true;
        var current_time = 1;
        function ajax_callback(response){
            if(typeof response == 'object' && typeof response.message != 'undefined'){
                $current_node.find('span').text(response.message);
                if(typeof response.url != 'undefined'){
                    // we have an ajax url action to perform.
                    if(response.hash == current_item_hash && !try_again){
                        $current_node.find('span').text("failed");
                        find_next();
                    }else {
                        if(response.hash == current_item_hash){
                            try_again = false;
                        }
                        current_item_hash = response.hash;
                        jQuery.post(response.url, response, function(response2) {
                            process_current();
                            $current_node.find('span').text(response.message + cenos_setup_params.verify_text);
                        }).fail(ajax_callback);
                    }

                }else if(typeof response.done != 'undefined'){
                    // finished processing this plugin, move onto next
                    find_next();
                    try_again = true;
                }else{
                    // error processing this plugin
                    find_next();
                    try_again = true;
                }
            }else{
                // error - try again with next plugin
                $current_node.find('span').text("ajax error");
                find_next();
                try_again = true;
            }
        }
        function process_current(){
            if(current_item){
                // query our ajax handler to get the ajax to send to TGM
                // if we don't get a reply we can assume everything worked and continue onto the next one.
                jQuery.post(cenos_setup_params.ajaxurl, {
                    action: 'cenos_setup_plugins',
                    wpnonce: cenos_setup_params.wpnonce,
                    slug: current_item
                }, ajax_callback).fail(ajax_callback);
            }
        }
        function find_next(){
            var do_next = false;
            if($current_node){
                if(!$current_node.data('done_item')){
                    items_completed++;
                    $current_node.data('done_item',1);
                }
                $current_node.find('.spinner').css('visibility','hidden');
            }
            var $li = $('.cenos-wizard-plugins li');
            $li.each(function(){
                if(current_item == '' || do_next){
                    current_item = $(this).data('slug');
                    $current_node = $(this);
                    process_current();
                    do_next = false;
                }else if($(this).data('slug') == current_item){
                    do_next = true;
                }
            });
            if(items_completed >= $li.length){
                // finished all plugins!
                complete();
            }
        }

        return {
            init: function(btn){
                $('.cenos-wizard-plugins').addClass('installing');
                complete = function(){
                    loading_content();
                    window.location.href=btn.href;
                };
                find_next();
            }
        }
    }

    function ContentManager(){

        var complete;
        var items_completed = 0;
        var current_item = '';
        var $current_node;
        var current_item_hash = '';

        function ajax_callback(response) {
            if(typeof response == 'object' && typeof response.message != 'undefined'){
                $current_node.find('span').text(response.message);
                if(typeof response.url != 'undefined'){
                    // we have an ajax url action to perform.
                    if(response.hash == current_item_hash){
                        $current_node.find('span').text("failed");
                        find_next();
                    }else {
                        current_item_hash = response.hash;
                        jQuery.post(response.url, response, ajax_callback).fail(ajax_callback); // recuurrssionnnnn
                    }
                }else if(typeof response.done != 'undefined'){
                    // finished processing this plugin, move onto next
                    find_next();
                }else{
                    // error processing this plugin
                    find_next();
                }
            }else{
                // error - try again with next plugin
                $current_node.find('span').text("ajax error");
                find_next();
            }
        }
        function process_current(){
            if(current_item){

                var $check = $current_node.find('input:checkbox');
                if($check.is(':checked')) {
                    // process htis one!
                    jQuery.post(cenos_setup_params.ajaxurl, {
                        action: 'cenos_setup_content',
                        wpnonce: cenos_setup_params.wpnonce,
                        content: current_item
                    }, ajax_callback).fail(ajax_callback);
                }else{
                    $current_node.find('span').text("Skipping");
                    setTimeout(find_next,300);
                }
            }
        }
        function find_next(){
            var do_next = false;
            if($current_node){
                if(!$current_node.data('done_item')){
                    items_completed++;
                    $current_node.data('done_item',1);
                }
                $current_node.find('.spinner').css('visibility','hidden');
            }
            var $items = $('tr.cenos_default_content');
            var $enabled_items = $('tr.cenos_default_content input:checked');
            $items.each(function(){
                if (current_item == '' || do_next) {
                    current_item = $(this).data('content');
                    $current_node = $(this);
                    process_current();
                    do_next = false;
                } else if ($(this).data('content') == current_item) {
                    do_next = true;
                }
            });
            if(items_completed >= $items.length){
                // finished all items!
                complete();
            }
        }

        return {
            init: function(btn){
                $('.cenos-setup-pages').addClass('installing');
                $('.cenos-setup-pages').find('input').prop("disabled", true);
                complete = function(){
                    loading_content();
                    window.location.href=btn.href;
                };
                find_next();
            }
        }
    }

    function renderMediaUploader() {
        'use strict';

        var file_frame, attachment;

        if ( undefined !== file_frame ) {
            file_frame.open();
            return;
        }

        file_frame = wp.media.frames.file_frame = wp.media({
            title: 'Upload Logo',//jQuery( this ).data( 'uploader_title' ),
            button: {
                text: 'Select Logo' //jQuery( this ).data( 'uploader_button_text' )
            },
            multiple: false  // Set to true to allow multiple files to be selected
        });

        // When an image is selected, run a callback.
        file_frame.on( 'select', function() {
            // We set multiple to false so only get one image from the uploader
            attachment = file_frame.state().get('selection').first().toJSON();

            jQuery('.site-logo').attr('src',attachment.url);
            jQuery('#new_logo_id').val(attachment.id);
            // Do something with attachment.id and/or attachment.url here
        });
        // Now display the actual file_frame
        file_frame.open();
    }

    function familab_loading_button(btn){
        var $button = jQuery(btn);
        if($button.data('done-loading') == 'yes')return false;
        var existing_text = $button.text();
        var existing_width = $button.outerWidth();
        var loading_text = '⡀⡀⡀⡀⡀⡀⡀⡀⡀⡀⠄⠂⠁⠁⠂⠄';
        var completed = false;

        $button.css('width',existing_width);
        $button.addClass('familab_loading_button_current');
        var _modifier = $button.is('input') || $button.is('button') ? 'val' : 'text';
        $button[_modifier](loading_text);
        $button.data('done-loading','yes');

        var anim_index = [0,1,2];

        // animate the text indent
        function moo() {
            if (completed)return;
            var current_text = '';
            // increase each index up to the loading length
            for(var i = 0; i < anim_index.length; i++){
                anim_index[i] = anim_index[i]+1;
                if(anim_index[i] >= loading_text.length)anim_index[i] = 0;
                current_text += loading_text.charAt(anim_index[i]);
            }
            $button[_modifier](current_text);
            setTimeout(function(){ moo();},60);
        }

        moo();

        return {
            done: function(){
                completed = true;
                $button[_modifier](existing_text);
                $button.removeClass('familab_loading_button_current');
                $button.attr('disabled',false);
            }
        }

    }
    /**
     * Document ready
     */
    $( function () {
        CENOS_SETUP.init();
    });
})(jQuery, window, document);