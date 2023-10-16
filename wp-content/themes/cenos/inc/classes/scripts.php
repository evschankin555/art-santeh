<?php

class Scripts
{
    /**
     * Whether we're debugging scripts or not.
     *
     * @access private
     * @since 1.0
     * @var bool
     */
    private $script_debug = false;

    /**
     * An array of async scripts.
     *
     * @access private
     * @since 1.0
     * @var array
     */
    private $async_scripts = [];

    /**
     * An array of widgets for which the CSS has already been added.
     *
     * @static
     * @access private
     * @since 1.0
     * @var array
     */
    private static $widgets = [];

    /**
     * An array of blocks used in this page.
     *
     * @static
     * @access private
     * @since 1.0.2
     * @var array
     */
    private static $blocks = [];

    /**
     * Constructor.
     *
     * @since 1.0
     * @access public
     */
    public function __construct() {
        $this->script_debug  = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG );
        add_filter('fmfw_framework_async_scripts',[$this,'add_async_script']);
        $this->async_scripts = apply_filters( 'cenos_async_scripts', $this->async_scripts );
        add_action( 'wp_enqueue_scripts', [ $this, 'scripts' ] ,100);
        add_action( 'customize_preview_init', [ $this, 'customize_preview_init' ]);
        add_action( 'customize_controls_print_styles',[$this, 'customize_controls_print_styles']);
        add_action( 'customize_controls_print_footer_scripts', [ $this, 'customize_controls_print_footer_scripts' ],1);
        add_action( 'wp_head', [$this,'print_page_option_css']);
        add_action( 'wp_default_scripts', [$this,'wp_default_custom_scripts']);
    }
    public function add_async_script($scripts){
        if (!empty($this->async_scripts)){
            $scripts = array_merge($scripts,$this->async_scripts);
        }
        return $scripts;
    }

    public function wp_default_custom_scripts( $scripts ){
        $scripts->add( 'wp-color-picker', "/wp-admin/js/color-picker.js", array( 'iris' ), false, 1 );
        did_action( 'init' ) && $scripts->localize(
            'wp-color-picker',
            'wpColorPickerL10n',
            array(
                'clear'            => esc_html__( 'Clear' ,'cenos'),
                'clearAriaLabel'   => esc_html__( 'Clear color' ,'cenos'),
                'defaultString'    => esc_html__( 'Default' ,'cenos'),
                'defaultAriaLabel' => esc_html__( 'Select default color' ,'cenos'),
                'pick'             => esc_html__( 'Select Color' ,'cenos'),
                'defaultLabel'     => esc_html__( 'Color value' ,'cenos'),
            )
        );
    }
    /**
     * Enqueue scripts.
     *
     * @access public
     * @since 1.0
     */
    public function scripts() {
        global $Cenos_theme;
        $cenos_demo_overide = false;
        $on_devices = cenos_on_device();
        $suffix  = (defined( 'SCRIPT_DEBUG' ) &&  SCRIPT_DEBUG) ? '' : '.min';
        $suffix = '';
        if (function_exists('is_fami_demo_has_overide') && is_fami_demo_has_overide()){
            $cenos_demo_overide = true;
        }
        if ($Cenos_theme->is_maintenance_mode){
            wp_enqueue_style( 'cenos-style', CENOS_TEMPLATE_DIRECTORY_URI . 'assets/css/maintenance.css', [], $Cenos_theme->version );
            return;
        }
        $shop_added_to_cart_notice = cenos_get_option('shop_added_to_cart_notice',false);
        $scoll_on_pagination = cenos_get_option('scoll_on_pagination');
        $cenos_js_var = [
            'ajaxurl'       => admin_url( 'admin-ajax.php' ),
            'ajax_nonce'    => wp_create_nonce('ajax-nonce'),
            'prev_icon'     => cenos_get_svg_icon('arrow-triangle-left'),
            'next_icon'     => cenos_get_svg_icon('arrow-triangle-right'),
            'clear_filter'  => sprintf('<a href="#." class="cenos_reset_filter_button%s" >%s %s</a>',$cenos_demo_overide? ' has_demo':'',cenos_get_svg_icon('trash-fill'),__('Clear Filters','cenos')),
            'is_woof'       => cenos_woof_ajax_pagination(),
            'shop_added_to_cart_notice' => $shop_added_to_cart_notice,
            'scoll_on_pagination' => $scoll_on_pagination,
            'on_devices' => $on_devices,
        ];
        if ($shop_added_to_cart_notice){
            $cenos_js_var['shop_added_to_cart_message'] = cenos_get_option('shop_added_to_cart_message',esc_html__('was added to cart successfully', 'cenos'));
            $cenos_js_var['shop_cart_notice_name'] = cenos_get_option('shop_cart_notice_name',true);
            $cenos_js_var['shop_cart_notice_image'] = cenos_get_option('shop_cart_notice_image',true);
        }

        $page_template = basename(get_page_template());
        if (!$on_devices){
            wp_enqueue_style( 'perfect_scrollbar', CENOS_TEMPLATE_DIRECTORY_URI . 'assets/vendors/perfect-scrollbar/perfect-scrollbar.css', [], '1.5.0' );
            wp_enqueue_script('perfect_scrollbar',CENOS_TEMPLATE_DIRECTORY_URI . 'assets/vendors/perfect-scrollbar/perfect-scrollbar' . $suffix . '.js',['jquery'],'1.5.0',true);
        }

        $this->cenos_enqueue_google_fonts_url();
        wp_enqueue_style('bootstrap',CENOS_TEMPLATE_DIRECTORY_URI . 'assets/vendors/bootstrap/css/bootstrap.min.css',[],'4.3.1');
        wp_enqueue_style( 'animate', CENOS_TEMPLATE_DIRECTORY_URI . 'assets/css/animate.css', [], '3.7.2' );
        wp_enqueue_style( 'select2', CENOS_TEMPLATE_DIRECTORY_URI . 'assets/vendors/select2/css/select2.css', [], '4.0.13' );
        wp_enqueue_style( 'cenos-style', CENOS_TEMPLATE_DIRECTORY_URI . 'assets/css/theme.css', [], $Cenos_theme->version );
        wp_enqueue_style( 'cenos-style-fmtpl', CENOS_TEMPLATE_DIRECTORY_URI . 'assets/css/fmtpl_sc.css', [], $Cenos_theme->version );
        wp_enqueue_style( 'cenos-off-canvas', CENOS_TEMPLATE_DIRECTORY_URI . 'assets/vendors/js-offcanvas/js-offcanvas.css', [], '1.2.9' );
        if ($shop_added_to_cart_notice){
            wp_enqueue_style( 'igrowl', CENOS_TEMPLATE_DIRECTORY_URI. 'assets/vendors/igrowl/css/igrowl.min.css', [], '3.0.1' );
            wp_enqueue_script( 'igrowl', CENOS_TEMPLATE_DIRECTORY_URI. 'assets/vendors/igrowl/js/igrowl.min.js', array( 'jquery' ), '3.0.1', true );
        }

        $show_sticky = cenos_get_option('show_sticky');
        $show_sticky_mobile = cenos_get_option('show_sticky_mobile');
        if ($show_sticky == true || $show_sticky_mobile == true) {
            wp_enqueue_script('headroom',CENOS_TEMPLATE_DIRECTORY_URI . 'assets/js/headroom.js',[],'0.11.0',true);
        }
        $customize_file = $Cenos_theme->customizer->get_current_customize_file();

        $customize_style = CENOS_TEMPLATE_DIRECTORY.'/assets/css/'.$customize_file;
        if (!file_exists($customize_style)) {
            $Cenos_theme->customizer->general_customize_file();
        }

        $main_color_file = $Cenos_theme->customizer->get_current_customize_file('main_color');
        $main_color_style = CENOS_TEMPLATE_DIRECTORY.'/assets/css/'.$main_color_file;

        if (!file_exists($main_color_style)) {
            $Cenos_theme->customizer->general_customize_file('main_color');
        }
        $body_color_file = $Cenos_theme->customizer->get_current_customize_file('body_color');
        $body_color_style = CENOS_TEMPLATE_DIRECTORY.'/assets/css/'.$body_color_file;

        if (!file_exists($body_color_style)) {
            $Cenos_theme->customizer->general_customize_file('body_color');
        }


        if (!$cenos_demo_overide && !is_customize_preview()){
            wp_enqueue_style( 'cenos-customize', CENOS_TEMPLATE_DIRECTORY_URI . 'assets/css/'.$customize_file, [], $Cenos_theme->version );
            wp_enqueue_style( 'cenos-customize-main-color', CENOS_TEMPLATE_DIRECTORY_URI . 'assets/css/'.$main_color_file, [], $Cenos_theme->version );
            wp_enqueue_style( 'cenos-customize-body-color', CENOS_TEMPLATE_DIRECTORY_URI . 'assets/css/'.$body_color_file, [], $Cenos_theme->version );
        }
        if ($cenos_demo_overide) {
            add_action( 'wp_head', [$this,'print_inline_css']);
        }
        wp_enqueue_script( 'jquery-cookie', CENOS_TEMPLATE_DIRECTORY_URI . 'assets/js/js.cookie.js' , ['jquery'], false, true );
        if ($this->enqueue_countdown()){
            wp_enqueue_script( 'jquery-countdown', CENOS_TEMPLATE_DIRECTORY_URI . 'assets/js/jquery.countdown.js' , ['jquery'], false, true );
            $cenos_js_var['countDown_text'] = [
                'w' => esc_html__('Weeks','cenos'),
                's' => esc_html__('Secs','cenos'),
                'm' => esc_html__('Mins','cenos'),
                'h' => esc_html__('Hours','cenos'),
                'd' => esc_html__('Days','cenos'),
            ];
        }
        if (cenos_get_option('search_ajax')){
            wp_enqueue_script('typeahead', CENOS_TEMPLATE_DIRECTORY_URI . 'assets/js/typeahead.bundle.js', ['jquery'], '1.3.1', true);
            wp_enqueue_script('handlebars', CENOS_TEMPLATE_DIRECTORY_URI . 'assets/js/handlebars.min.js', ['jquery'], '4.7.6', true);
            $cenos_js_var['search_options'] = [
                'live_search_template' =>
                    '<div class="search-result-item">' .
                    '<a href="{{url}}" class="item-link" title="{{title}}">' .
                    '<img src="{{image}}" class="result-item-thumb" height="60" width="60" />' .
                    '</a>' .
                    '<div class="result_item_info">' .
                    '<p class="result_item_title">{{title}}</p>' .
                    '<div class="result_item_price">{{{price}}}</div>' .
                    '</div>' .
                    '</div>',
                'empty_msg' => esc_html__('unable to find any products that match the current query','cenos'),
                'limit_results' => cenos_get_option('limit_results_search',5),
            ];
        }

        $this->enqueue_script_by_option();
        wp_enqueue_script('bootstrap',CENOS_TEMPLATE_DIRECTORY_URI . 'assets/vendors/bootstrap/js/bootstrap.min.js',['jquery'],'4.3.1',true);
        wp_enqueue_script('cenos-off-canvas',CENOS_TEMPLATE_DIRECTORY_URI . 'assets/vendors/js-offcanvas/js-offcanvas.pkgd.min.js',['jquery'],'1.2.9',true);
        wp_enqueue_script('cenos-script',CENOS_TEMPLATE_DIRECTORY_URI . 'assets/js/theme.js',['jquery'],$Cenos_theme->version,true);
        wp_enqueue_script('cenos-mega-menu',CENOS_TEMPLATE_DIRECTORY_URI . 'assets/js/mega-menu.js',['jquery'],$Cenos_theme->version,true);
        wp_enqueue_script('select2',CENOS_TEMPLATE_DIRECTORY_URI . 'assets/vendors/select2/js/select2' . $suffix . '.js',['jquery'],'4.0.13',true);
        wp_enqueue_script( 'igrowl', CENOS_TEMPLATE_DIRECTORY_URI. 'assets/vendors/igrowl/js/igrowl.min.js', array( 'jquery' ), '3.0.1', true );
        if ($page_template == 'fullpage.php') {
            wp_enqueue_script( 'cenos-fullpage', CENOS_TEMPLATE_DIRECTORY_URI . 'assets/vendors/fullpage/jquery.fullpage.js', ['jquery'], '2.9.7',true );
            wp_enqueue_style( 'cenos-fullpage', CENOS_TEMPLATE_DIRECTORY_URI . 'assets/vendors/fullpage/jquery.fullpage.css', [], '2.9.7' );
        }

        $popup_enable = cenos_get_option('popup_enable');
        if ($popup_enable){
            $cenos_js_var['popup' ] = $popup_enable;
            $cenos_js_var['popup_frequency' ] = cenos_get_option( 'popup_frequency' );
            $cenos_js_var['popup_visible' ] = cenos_get_option( 'popup_visible' );
            $cenos_js_var['popup_visible_delay' ] = cenos_get_option( 'popup_visible_delay' );
        }

        wp_localize_script( 'cenos-script', 'cenos_JsVars', $cenos_js_var);
        if( is_singular() && comments_open() && ( get_option( 'thread_comments' ) == 1) ) {
            wp_enqueue_script( 'comment-reply', '/wp-includes/js/comment-reply.min.js', array(), false, true );
        }
    }

    public function enqueue_countdown(){
        $show_announcement = cenos_get_option('show_announcement');
        $show_announcement_countdown = cenos_get_option('show_announcement_countdown');
        if ($show_announcement == true && $show_announcement_countdown == true){
            return true;
        }
        return false;
    }
    public function enqueue_script_by_option(){
        global $Cenos_theme;
        $enable_swiper = false;
        if (is_singular()){
            $enable_swiper = true;
        }
        if (cenos_is_woocommerce_activated()) {
            if ( is_product()) {
                $enable_swiper = true;
                if (cenos_get_option('woo_single_layout') == 'sticky') {
                    wp_enqueue_script('resize-sensor', CENOS_TEMPLATE_DIRECTORY_URI . 'assets/vendors/sticky-sidebar/ResizeSensor.js', [], '1.0.0', true);
                    wp_enqueue_script('sticky-sidebar', CENOS_TEMPLATE_DIRECTORY_URI . 'assets/vendors/sticky-sidebar/jquery.sticky-sidebar.js', ['jquery'], '3.3.4', true);
                }
                wp_enqueue_script('cenos-single-product', CENOS_TEMPLATE_DIRECTORY_URI . 'assets/js/single_product.js', [], $Cenos_theme->version, true);
                wp_localize_script('cenos-single-product', 'cenos_product_JsVars', [
                    'prev_html' => '<a href="#" class="cenos-prev">' . cenos_get_svg_icon('arrow-triangle-left') . '</a>',
                    'next_html' => '<a href="#" class="cenos-next">' . cenos_get_svg_icon('arrow-triangle-right') . '</a>',
                    'v_prev_html' => '<a href="#" class="cenos-prev">' . cenos_get_svg_icon('arrow-triangle-up') . '</a>',
                    'v_next_html' => '<a href="#" class="cenos-next">' . cenos_get_svg_icon('arrow-triangle-down') . '</a>',
                ]);
                if (current_theme_supports('fmfw-product-360degree') && cenos_get_option('woo_single_image_360')){
                    wp_enqueue_script('threesixty', CENOS_TEMPLATE_DIRECTORY_URI . 'assets/js/threesixty.min.js', [], '2.0.5', true);
                }
            }
            if (is_shop() || is_product_taxonomy()){
                $shop_list_style = cenos_get_option('shop_list_style');
                $product_item_style = cenos_get_option('product_item_style');
                if ( $shop_list_style == 'grid' && !in_array($product_item_style,['default','slider'])  && cenos_get_option('product_item_hover_image') == 'zoom'){
                    wp_enqueue_script( 'zoom', CENOS_TEMPLATE_DIRECTORY_URI.'assets/js/jquery.zoom.min.js', array( 'jquery' ), '1.7.18', true );
                }
                if (cenos_get_option('shop_control_categories') || ($shop_list_style == 'grid' && $product_item_style == 'slider')){
                    $enable_swiper = true;
                }
            }
        }
        if ($enable_swiper) {
            wp_enqueue_script( 'swiper', CENOS_TEMPLATE_DIRECTORY_URI . 'assets/vendors/swiper/swiper.js' , ['jquery'], '5.3.1', true );
            wp_enqueue_style( 'swiper', CENOS_TEMPLATE_DIRECTORY_URI . 'assets/vendors/swiper/swiper.css', [], '5.3.1' );
        }
    }
    /**
     * Adds extra customizer scripts.
     *
     * @access public
     * @since 1.0
     * @return void
     */
    public function customize_preview_init() {
        add_action( 'wp_head', [$this,'print_inline_css']);
    }

    public function print_inline_css(){
        global $Cenos_theme;
        $css = '<style type="text/css" id="cenos-customize-inline-css">';
        $css .= $Cenos_theme->customizer->general_style();
        $css .= $Cenos_theme->customizer->general_style('main_color');
        $css .= $Cenos_theme->customizer->general_style('body_color');
        $css .= '</style>';
        cenos_esc_data($css);
    }

    public function print_page_option_css() {
        $inline_css = '';
        $page_option = false;
        $page_id = false;
        if (cenos_is_woocommerce_activated()){
            if (is_product_category()) {
                $page_id = get_option('woocommerce_shop_page_id');
                $page_option = true;
                $body_page_class = 'body.page-shop-option';
                $current_cat = get_queried_object();
                if (!is_wp_error($current_cat) && isset($current_cat->term_id)) {
                    $current_cat_id = $current_cat->term_id;
                    $category_background_id = absint(get_term_meta($current_cat_id, 'fmfw_product_cat_background', true));
                    if ($category_background_id > 0) {
                        $category_background_img = wp_get_attachment_url($category_background_id);
                        if ($category_background_img) {
                            $inline_css .= 'body .shop-heading-content{background-image: url(' . $category_background_img . ');';
                        }
                    }
                }
            } elseif (is_shop()) {
                $page_id = get_option('woocommerce_shop_page_id');
                $page_option = true;
                $body_page_class = 'body.page-shop-option';
            }
        }
        if (is_page()){
            $page_id = get_the_ID();
            $page_option = true;
            $body_page_class = 'body.page-id-'.$page_id;
        }

        if ($page_option && $page_id){

            $page_layout_option = cenos_get_post_meta($page_id, 'page_layout_option',true);
            if ($page_layout_option) {
                $content_padding_top = cenos_get_post_meta($page_id, 'content_padding_top',true);
                $content_padding_bottom = cenos_get_post_meta($page_id, 'content_padding_bottom',true);
                $padding_style = '';
                if ($content_padding_top != ''){
                    $padding_style .= 'padding-top:'.cenos_css_unit($content_padding_top).';';
                }
                if ($content_padding_bottom != ''){
                    $padding_style .= 'padding-bottom:'.cenos_css_unit($content_padding_bottom).';';
                }
                if (!empty($padding_style)){
                    $inline_css .= $body_page_class.' #content{'.$padding_style.'}';
                }
            }
            $page_header_option = cenos_get_post_meta($page_id,'page_header_option',true);
            if ($page_header_option){
                $header_dimensions = cenos_get_post_meta($page_id,'header_dimensions',true);
                if ($header_dimensions != ''){
                    $header_dimensions = cenos_css_unit($header_dimensions);
                    if ($header_dimensions !== false){
                        $inline_css .= $body_page_class.' .header-container, '.$body_page_class.' .footer-container{width:'.$header_dimensions.';}';
                    }
                }
                $header_bg_color = cenos_get_post_meta($page_id,'header_bg_color', true);
                if ($header_bg_color != ''){
                    $inline_css .= $body_page_class.' .site-header:not(.cenos_transparent_header) .header-layout{background-color:'.$header_bg_color.';}';
                    $sticky_bg_color = cenos_get_option('sticky_bg_color');
                    if ($sticky_bg_color != ''){
                        $inline_css .= $body_page_class.' .site-header .header-layout.headroom--pinned.headroom--not-top{background-color:'.$sticky_bg_color.';}';
                    }
                }
                $header_color = cenos_get_post_meta($page_id,'header_color',true);
                $header_hover_color = cenos_get_post_meta($page_id,'header_hover_color',true);
                $inline_css .= cenos_header_color_general($header_color,$header_hover_color,'default',$page_id);
                $header_divider = cenos_get_post_meta($page_id,'header_divider',true);
                if ($header_divider == 'none'){
                    $inline_css .= $body_page_class.' .header-layout{';
                    $inline_css .='box-shadow: none;border-bottom: none;';
                    $inline_css .='}';
                } elseif ($header_divider == 'shadow'){
                    $inline_css .= $body_page_class.' .header-layout{';
                    $inline_css .='border-bottom: none;';
                    $inline_css .='box-shadow: 0 7px 6px -4px rgba(0, 0, 0, 0.08), 0 7px 6px rgba(0, 0, 0, 0.08);border-bottom: none;';
                    $inline_css .='}';
                }else{
                    $inline_css .= $body_page_class.' .header-layout{';
                    $inline_css .='box-shadow: none;';
                    $header_divider_color = cenos_get_post_meta($page_id,'header_divider_color', true);
                    if ($header_divider_color == ''){
                        $header_divider_color = 'transparent';
                    }
                    $inline_css .='border-bottom-width: 1px;border-bottom-style: solid; border-bottom-color: '.$header_divider_color.';';
                    $inline_css .='}';
                }
            }

            $page_header_transparent = cenos_get_post_meta($page_id, 'page_header_transparent',true);
            if ($page_header_transparent) {
                $page_header_transparent_bg = cenos_get_post_meta($page_id, 'page_header_transparent_bg',true);
                if ($page_header_transparent_bg){
                    $page_header_transparent_bg_color = cenos_get_post_meta($page_id, 'page_header_transparent_bg_color',true);
                    if (!empty($page_header_transparent_bg_color)){
                        $inline_css .= $body_page_class.' .cenos_transparent_header{';
                        $inline_css .= 'background-color:'.$page_header_transparent_bg_color.';';
                        $inline_css .='}';
                    }
                    $page_header_transparent_text_color = cenos_get_post_meta($page_id, 'page_header_transparent_text_color',true);
                    $page_header_transparent_text_hover_color = cenos_get_post_meta($page_id, 'page_header_transparent_text_hover_color',true);
                    $inline_css .= cenos_header_color_general($page_header_transparent_text_color,$page_header_transparent_text_hover_color,'transparent',$page_id);
                    if (!empty($page_header_transparent_text_hover_color)){
                        $inline_css .= $body_page_class.' .site-header.cenos_transparent_header .site-navigation a:after{';
                        $inline_css .= 'background-color:'.$page_header_transparent_text_hover_color.';';
                        $inline_css .='}';
                    } elseif (!empty($page_header_transparent_text_color)){
                        $inline_css .= $body_page_class.' .site-header.cenos_transparent_header .site-navigation a:after{';
                        $inline_css .= 'background-color:'.$page_header_transparent_text_color.';';
                        $inline_css .='}';
                    }
                }
            }
            $page_heading_option = cenos_get_post_meta($page_id,'page_heading_option',true);
            if ($page_heading_option ){
                $blog_heading_post = cenos_get_post_meta($page_id,'page_heading_block',true);
                if (!$blog_heading_post || $blog_heading_post == 'none'){
                    $blog_heading_bg = cenos_get_post_meta($page_id,'page_heading_background',true);
                    $blog_heading_ovelay = false;
                    $page_heading_css = '';
                    if( isset($blog_heading_bg['image']) && $blog_heading_bg['image']!=''){
                        $blog_heading_ovelay = true;
                        $page_heading_css .= ' background-image: url("'.$blog_heading_bg['image'].'");';
                    } else {
                        $page_heading_css .= ' background-image: none;';
                    }
                    if( isset($blog_heading_bg['repeat']) && $blog_heading_bg['repeat']!=''){
                        $page_heading_css .= ' background-repeat: '.$blog_heading_bg['repeat'].';';
                    }
                    if( isset($blog_heading_bg['position']) && $blog_heading_bg['position']!=''){
                        $page_heading_css .= ' background-position: '.$blog_heading_bg['position'].';';
                    }
                    if( isset($blog_heading_bg['attachment']) && $blog_heading_bg['attachment']!=''){
                        $page_heading_css .= ' background-attachment: '.$blog_heading_bg['attachment'].';';
                    }
                    if( isset($blog_heading_bg['size']) && $blog_heading_bg['size']!=''){
                        $page_heading_css .= ' background-size:'.$blog_heading_bg['size'].';';
                    }
                    $page_heading_selector = $body_page_class.' .blog-heading-content';
                    if( isset($blog_heading_bg['color']) && $blog_heading_bg['color']!=''){
                        if ($blog_heading_ovelay){
                            $inline_css .= $page_heading_selector.' .page-heading-overlay{'.$blog_heading_bg['color'].';}';
                        } else {
                            $page_heading_css .= 'background-color: '.$blog_heading_bg['color'].';';
                        }
                    }
                    if ($page_heading_css != ''){
                        $inline_css .= $page_heading_selector.'{'.$page_heading_css.'}';
                    }

                    $blog_heading_text_color = cenos_get_post_meta($page_id,'page_heading_color',true);
                    if ($blog_heading_text_color !=''){
                        $inline_css .= $page_heading_selector.','.$page_heading_selector.' p,'.$page_heading_selector.' a,'.$page_heading_selector.' .page-heading-title{color:'.$blog_heading_text_color.';}';
                        $inline_css .= $page_heading_selector.' svg.stroke{stroke:'.$blog_heading_text_color.';}';
                        $inline_css .= $page_heading_selector.' svg.fill{fill:'.$blog_heading_text_color.';}';
                    }
                    $page_heading_height = cenos_get_post_meta($page_id,'page_heading_height',true);
                    if ($page_heading_height !== false && $page_heading_height != ''){
                        $inline_css .= $page_heading_selector.'{height:'.cenos_css_unit($page_heading_height).';}';
                    }
                }
            }
            $page_topbar_background_option = cenos_get_post_meta($page_id,'page_topbar_background_setting',true);
            $page_topbar_selector = $body_page_class.' .site-header .top-bar';
            if ($page_topbar_background_option){
                $page_topbar_background = cenos_get_post_meta($page_id,'page_topbar_background',true);
                $page_topbar_css = '';
                if( isset($page_topbar_background['image']) && $page_topbar_background['image']!=''){
                    $page_topbar_css .= ' background-image: url("'.$page_topbar_background['image'].'");';
                }
                if( isset($page_topbar_background['repeat']) && $page_topbar_background['repeat']!=''){
                    $page_topbar_css .= ' background-repeat: '.$page_topbar_background['repeat'].';';
                }
                if( isset($page_topbar_background['position']) && $page_topbar_background['position']!=''){
                    $page_topbar_css .= ' background-position: '.$page_topbar_background['position'].';';
                }
                if( isset($page_topbar_background['attachment']) && $page_topbar_background['attachment']!=''){
                    $page_topbar_css .= ' background-attachment: '.$page_topbar_background['attachment'].';';
                }
                if( isset($page_topbar_background['size']) && $page_topbar_background['size']!=''){
                    $page_topbar_css .= ' background-size:'.$page_topbar_background['size'].';';
                }

                if( isset($page_topbar_background['color']) && $page_topbar_background['color']!=''){
                    $page_topbar_css .= 'background-color: '.$page_topbar_background['color'].';';
                }
                if ($page_topbar_css != ''){
                    $inline_css .= $page_topbar_selector.'{'.$page_topbar_css.'}';
                }
            }
            $page_topbar_divider_option = cenos_get_post_meta($page_id,'page_topbar_divider_setting',true);
            if ($page_topbar_divider_option) {
                $page_topbar_divider_color = cenos_get_post_meta($page_id,'page_topbar_divider_color',true);
                if ($page_topbar_divider_color != ''){
                    $inline_css .=$page_topbar_selector .'{';
                    $inline_css .='border-bottom-width: 1px;border-bottom-style: solid; border-bottom-color: '.$page_topbar_divider_color.';';
                    $inline_css .='}';
                } else {
                    $inline_css .=$page_topbar_selector .'{';
                    $inline_css .='border-bottom:none;';
                    $inline_css .='}';
                }
            }
        }
        $page_template = basename(get_page_template());
        if ($page_template == 'fullpage.php'){
            $_elementor_page_settings = get_elementor_site_setting();
            $site_container_w = '1140px';
            if (!empty($_elementor_page_settings) && isset($_elementor_page_settings['container_width']) && isset($_elementor_page_settings['container_width']['size']) && $_elementor_page_settings['container_width']['size']){
                $site_container_w = $_elementor_page_settings['container_width']['size'].'px';
            }
            $inline_css .= '.page-template-fullpage .elementor-section .elementor-container{ max-width:'.$site_container_w.';}';
        }
        if (!empty($inline_css)){
            $css = '<style type="text/css" id="cenos-page-option-css">';
            $css .= $inline_css;
            $css .= '</style>';
            cenos_esc_data($css);
        }
    }

    public function customize_controls_print_footer_scripts(){
        global $Cenos_theme;
        $customize_preview_js_var = [
            'blog_url'  => get_permalink(get_option( 'page_for_posts' )),
        ];
        if (cenos_is_woocommerce_activated()){
            $customize_preview_js_var['shop_url'] = get_permalink( wc_get_page_id( 'shop' ));
            $all_ids = get_posts( array(
                'post_type' => 'product',
                'numberposts' => 1,
                'post_status' => 'publish',
                'fields' => 'ids',
            ) );
            if (!empty($all_ids)){
                $customize_preview_js_var['product_link'] = get_permalink($all_ids[0]);
            }
        }
        wp_enqueue_script('cenos-customize-panel-preview',CENOS_TEMPLATE_DIRECTORY_URI . 'assets/js/admin/customize_panel_preview.js',[ 'jquery', 'customize-base' ], $Cenos_theme->version, false );
        wp_localize_script('cenos-customize-panel-preview','cenos_data',$customize_preview_js_var);
    }

    public function customize_controls_print_styles() {
        global $Cenos_theme;
        wp_enqueue_style('cenos-customize-admin-fonts',CENOS_TEMPLATE_DIRECTORY_URI . 'assets/css/icomoon.css',[], $Cenos_theme->version);
        wp_enqueue_style('cenos-customize-admin-style',CENOS_TEMPLATE_DIRECTORY_URI . 'assets/css/admin/customize-admin-style.css',[], $Cenos_theme->version);
    }

    public function cenos_enqueue_google_fonts_url(){
        $font_families = array();
        $font_weights = array();
        $font_subsets  = array( 'latin', 'latin-ext' );
        $ignore_fonts = apply_filters('cenos_ignore_fonts',[]);
        global $fmfw_fonts;
        $settings = array(
            'typo_body',
            'typo_h1',
            'typo_h2',
            'typo_h3',
            'typo_h4',
            'typo_h5',
            'typo_h6',
            'announcement_typo',
            'typo_main_nav'
        );
        foreach ( $settings as $setting ) {
            $typography = cenos_get_option( $setting );
            if (isset( $typography['font-family'] ) && ! empty( $typography['font-family'] )) {
                $check_font_family = explode(',',$typography['font-family'])[0];
                if (in_array($check_font_family,$ignore_fonts)) {
                    continue;
                }
                if (!empty($fmfw_fonts) && isset($fmfw_fonts[$check_font_family])){
                    continue;
                }
                if (isset($typography['font-weight']) && (!isset($font_weights[ $typography['font-family'] ]) || !isset($font_weights[ $typography['font-family']][$typography['font-weight']]))){
                    $font_weights[ $typography['font-family']][$typography['font-weight']] = $typography['font-weight'];
                }
                if (! array_key_exists( $typography['font-family'], $font_families )){
                    $font_families[ $typography['font-family'] ] = trim( trim( $typography['font-family'] ), ',' );
                }
                if ( isset( $typography['subsets'] ) ) {
                    if ( is_array( $typography['subsets'] ) ) {
                        $font_subsets = array_merge( $font_subsets, $typography['subsets'] );
                    } else {
                        $font_subsets[] = $typography['subsets'];
                    }
                }
            }
        }
        if ( ! empty( $font_families ) ) {
            foreach ($font_families as $font_k => $font_v){
                if (isset($font_weights[$font_k]) && !empty($font_weights[$font_k])){
                    $font_families[$font_k] = $font_v.':'.implode(',',$font_weights[$font_k]);
                }
            }
            $font_subsets = array_unique( $font_subsets );
            $query_args   = array(
                'family' => urlencode( implode( '|', $font_families ) ),
                'subset' => urlencode( implode( ',', $font_subsets ) ),
            );
            $fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
            wp_enqueue_style( 'cenos-googlefonts', esc_url_raw( $fonts_url ), [], null );
        }
    }
}

return new Scripts();
