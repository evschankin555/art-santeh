! function($) {
    "use strict";
    wp.customize.panel( 'blog', function( panel ) {
        panel.expanded.bind( function( isExpanded ) {
            if ( isExpanded ) {
                if (typeof cenos_data.blog_url != 'undefined' && cenos_data.blog_url != ''){
                    wp.customize.previewer.previewUrl.set(cenos_data.blog_url);
                }
            }
        } );
    } );
    wp.customize.section( 'sh_header', function( section ) {
        section.expanded.bind( function( isExpanded ) {
            if ( isExpanded ) {
                if (typeof cenos_data.shop_url != 'undefined' && cenos_data.shop_url != ''){
                    wp.customize.previewer.previewUrl.set(cenos_data.shop_url);
                }
            }
        } );
    } );
    wp.customize.section( 'sh_badges', function( section ) {
        section.expanded.bind( function( isExpanded ) {
            if ( isExpanded ) {
                if (typeof cenos_data.shop_url != 'undefined' && cenos_data.shop_url != ''){
                    wp.customize.previewer.previewUrl.set(cenos_data.shop_url);
                }
            }
        } );
    } );
    wp.customize.section( 'sh_control', function( section ) {
        section.expanded.bind( function( isExpanded ) {
            if ( isExpanded ) {
                if (typeof cenos_data.shop_url != 'undefined' && cenos_data.shop_url != ''){
                    wp.customize.previewer.previewUrl.set(cenos_data.shop_url);
                }
            }
        } );
    } );
    wp.customize.section( 'woo_single', function( section ) {
        section.expanded.bind( function( isExpanded ) {
            if ( isExpanded ) {
                if (typeof cenos_data.product_link != 'undefined' && cenos_data.product_link != ''){
                    wp.customize.previewer.previewUrl.set(cenos_data.product_link);
                }
            }
        } );
    } );
    $(document).on('click','.goto_section_btn',function(e){
        e.preventDefault();
        const se = $(this).data('section');
        if (wp.customize.section(se)) {
            wp.customize.section(se).focus();
        } else if (wp.customize.panel(se)) {
            wp.customize.panel(se).focus();
        }
    });
}(jQuery);


