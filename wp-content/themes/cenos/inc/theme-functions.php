<?php
/**
 * 1.0.0
 * @package    Cenos
 * @author     Familab <contact@familab.net>
 * @copyright  Copyright (C) 2018 familab.net. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://familab.net
 */

if ( ! function_exists( 'cenos_is_woocommerce_activated' ) ) {
    /**
     * Returns true if WooCommerce plugin is activated
     *
     * @return bool
     */
    function cenos_is_woocommerce_activated() {
        return class_exists( 'WooCommerce' );
    }
}

if (!function_exists('cenos_is_elementor_activated')) {
    function cenos_is_elementor_activated() {
        return defined( 'ELEMENTOR_VERSION' ) ? true : false;
    }
}

if (!function_exists('cenos_is_built_with_elementor')) {
    function cenos_is_built_with_elementor($post_id) {
        if (empty($post_id)){
            return false;
        }
        if (cenos_is_elementor_activated()){
            global $elementor_instance;
            if (version_compare( ELEMENTOR_VERSION, '3.2.0', '<' ) ) {
                return $elementor_instance->db->is_built_with_elementor($post_id);
            }
    	    $document = $elementor_instance->documents->get( $post_id );
            if ( ! $document ) {
                return false;
            }
            return $document->is_built_with_elementor();
        }
        return false;
    }
}

if (!function_exists('cenos_responsive_breakpoints')) {
    function cenos_responsive_breakpoints() {
        $default_breakpoints = [
            'xs' => 0,
            'sm' => 576,
            'md' => 768,
            'lg' => 992,
            'xl' => 1200,
            'xxl' => 1440,
        ];
        return $default_breakpoints;
    }
}
if (!function_exists('cenos_elementor_breakpoints')) {
    function cenos_elementor_breakpoints() {
        $elementor__breakpoints = [];
        if (cenos_is_elementor_activated()) {
            $elementor__breakpoints = \Elementor\Core\Responsive\Responsive::get_breakpoints();
        }
        return $elementor__breakpoints;
    }
}
if (!function_exists('cenos_nav_menu_args')){
    /**
     * Add a walder object for all nav menu
     *
     * @since  1.0.0
     *
     * @param  array $args The default args
     *
     * @return array
     */
    function cenos_nav_menu_args( $args ) {
        $change_walker = false;
        if ( $args['theme_location'] ==  'primary' && has_nav_menu('primary')) {
            $args['walker'] = new Cenos_Walker_Mega_Menu();
            $change_walker = true;
        } else {
            if (has_nav_menu($args['theme_location'])){
                $args['walker'] = new Cenos_Walker_Default_Menu();
                $change_walker = true;
            }
        }
        if ($change_walker){
            $args['fallback_cb'] = false;
        } else {
            if ($args['theme_location'] == 'mobile'){
                $args['menu_class'] .= ' default-nav-menu';
                $args['walker'] = new Cenos_Walker_Page_Menu();
            }
        }
        return $args;
    }
}
if (!function_exists('cenos_is_multi_language')){
    function cenos_is_multi_language(){
        $languages = function_exists( 'icl_get_languages' ) ? icl_get_languages() : array();
        $languages = apply_filters( 'wpml_active_languages', $languages );

        if ( empty( $languages ) ) {
            return false;
        }
        return true;
    }
}

if (!function_exists('cenos_customize_field_setting')){
    function cenos_customize_field_setting($className =''){
        if (!class_exists($className)) return array();
        global $customize_field_class;
        if (empty($customize_field_class) || !isset($customize_field_class[$className])){
            $fields = new $className();
            $customize_field_class[$className] = $fields;
            $GLOBALS['customize_field_setting'] = $customize_field_class;
        }
        return $customize_field_class[$className]->getSetting();
    }
}

if (!function_exists('cenos_social_label')){
    function cenos_social_label($name = ''){
        global $fm_social;
        if (empty($fm_social)){
            if (!class_exists('Social_Fields')) return '';
            global $customize_field_class;
            if (empty($customize_field_class) || !isset($customize_field_class['Social_Fields'])){
                $social_obj = new Social_Fields();
                $customize_field_class['Social_Fields'] = $social_obj;
                $GLOBALS['customize_field_setting'] = $customize_field_class;
            }
            $fm_social = $customize_field_class['Social_Fields']->getChoices();
            $GLOBALS['fm_social'] = $fm_social;
        }
        if (isset($fm_social[$name])){
            return $fm_social[$name];
        }
        return '';
    }
}

if ( ! function_exists( 'cenos_parse_args' ) ) {
    function cenos_parse_args( $args, $default = array() ) {
        $args   = (array) $args;
        $result = $default;
        foreach ( $args as $key => $value ) {
            if ( is_array( $value ) && isset( $result[ $key ] ) ) {
                $result[ $key ] = cenos_parse_args( $value, $result[ $key ] );
            } else {
                $result[ $key ] = $value;
            }
        }
        return $result;
    }
}

if (!function_exists('cenos_get_theme_mod')){
    function cenos_get_theme_mod($name, $default = false ){
        global $cenos_mods;
        if ( isset( $cenos_mods[ $name ] ) ) {
            return apply_filters( "theme_mod_{$name}", $cenos_mods[ $name ] );
        }

        if ( is_string( $default ) ) {
            $default = sprintf( $default, CENOS_TEMPLATE_DIRECTORY_URI, CENOS_STYLESHEET_DIRECTORY_URI );
        }
        return apply_filters( "theme_mod_{$name}", $default );
    }
}

if (!function_exists('cenos_get_option')){
    /**
     * This is a short hand function for getting setting value from customizer
     *
     * @param string $name
     * @param array $default
     *
     * @return bool|string
     */
    function cenos_get_option( $name , $default = false) {
        if (class_exists('Cenos_theme')){
            global $Cenos_theme;
            if ( empty( $Cenos_theme->customizer ) ) {
                return cenos_get_theme_mod($name,$default);
            }
            $value = $Cenos_theme->customizer->get_option( $name );
        } else {
            $value =  cenos_get_theme_mod( $name, $default );
        }

        return apply_filters( 'cenos_get_option_'.$name, $value, $name );
    }
}

if (!function_exists('cenos_get_post_meta')) {
    function cenos_get_post_meta( $post_id, $key = '', $single = false ) {
        global $Cenos_post_meta;
        if( empty($Cenos_post_meta) || !isset($Cenos_post_meta[$post_id.$key]) ){
            $Cenos_post_meta[$post_id.$key] = get_post_meta($post_id,$key,$single);
            $GLOBALS['Cenos_post_meta'] = $Cenos_post_meta;
        }
        return $Cenos_post_meta[$post_id.$key];
    }
}
if (!function_exists('cenos_get_option_default')){
    /**
     * Get default option values
     *
     * @param $name
     *
     * @return mixed
     */
    function cenos_get_option_default( $name ) {
        global $Cenos_theme;

        if ( empty( $Cenos_theme->customizer ) ) {
            return false;
        }

        return $Cenos_theme->customizer->get_option_default( $name );
    }
}

if (!function_exists('cenos_esc_data')){
    function cenos_esc_data($string){
        echo trim($string);
    }
}

if (!function_exists('cenos_demention_format')){
    function cenos_demention_format($demention_str,$max_value = 1){
        if (!$demention_str){
            return $demention_str;
        }
        $demention_str = preg_replace('!\s+!', ' ', trim($demention_str));
        $arr = explode(' ',$demention_str,$max_value+1);
        if (sizeof($arr) <= $max_value){
            return $demention_str;
        }
        return trim(str_replace(end($arr),'',$demention_str));
    }
}

if (!function_exists('cenos_dimension_style')){
    function cenos_dimension_style($option_key,$selector){
        $style ='';
        if ($selector != ''){
            $dimension_option = cenos_get_option($option_key);
            if ($dimension_option && !empty($dimension_option)){
                $dimension_style = '';
                foreach ($dimension_option as $css_demension_key => $value){
                    if ($value != ''){
                        $dimension_style .= $css_demension_key.':'.cenos_css_unit($value).';';
                    }
                }
                if ($dimension_style != ''){
                    $style .= $selector.'{'.$dimension_style.'}';
                }
            }
        }
        return $style;
    }
}

if (!function_exists('cenos_background_style')){
    function cenos_background_style($option_key,$selector){
        $style ='';
        if ($selector != '' && $option_key != ''){
            $bg_option = cenos_get_option($option_key);
            if (isset($bg_option['background-image']) && $bg_option['background-image'] !=''){
                $style .= $selector.'{background-image: url('.$bg_option['background-image'].');';
                foreach ($bg_option as $key => $value){
                    if ($key !== 'background-image' && $value != ''){
                        $style .=  $key.':'.$value.';';
                    }
                }
                $style .= '}';
            }elseif (isset($bg_option['background-color']) && $bg_option['background-color'] !='') {
                $style .= $selector.'{background-color: '.$bg_option['background-color'].';}';
            }
        }
        return $style;
    }
}

if (!function_exists('cenos_color_style')){
    function cenos_color_style($option_key,$selector){
        $style ='';
        if ($selector != '' && $option_key != ''){
            $color_option = cenos_get_option($option_key);
            if ($color_option !=''){
                $style .= $selector.','.$selector.' p,'.$selector.' a{color:'.$color_option.';}';
                $style .= $selector.' svg.stroke{stroke:'.$color_option.';}';
                $style .= $selector.' svg.fill{fill:'.$color_option.';}';
            }
        }
        return $style;
    }
}

if (!function_exists('cenos_css_unit')) {
    function cenos_css_unit($value = false, $max_value = 1){
        if ($value === false || $value == ''){
            return false;
        }
        $value = cenos_demention_format($value,$max_value);
        // Whitelist values.
        if (0 === $value || '0' === $value || 'auto' === $value || 'inherit' === $value || 'initial' === $value ) {
            return $value;
        }
        $str_calc = strpos($value,'calc(');
        $close_calc = strpos($value,')');
        // Skip checking if calc().
        if ($str_calc !== false && $close_calc !== false ) {
            return $value;
        }
        $multiples = explode(' ',$value);
        if (sizeof($multiples) > 1){
            $multi_str = '';
            foreach ($multiples as $i){
                if ($i){
                    $i_v =  cenos_css_unit($i);
                    $multi_str .= ' '.$i_v;
                }

            }
            return trim($multi_str);
        }
        $validUnits = [ 'fr' => 1, 'rem' => 1, 'em' => 1, 'ex' => 1, '%' => 1, 'px' => 1, 'cm' => 1, 'mm' => 1, 'in' => 1, 'pt' => 1, 'pc' => 1, 'ch' => 1, 'vh' => 1, 'vw' => 1, 'vmin' => 1, 'vmax' => 1 ];
        // Get the numeric value.
        $numericValue = (double)$value;
        $unit = str_replace($numericValue,'',$value);
        if ($unit == ''){
            return $value.'px';
        } elseif (isset($validUnits[$unit])){
            return $value;
        } else {
            return $numericValue.'px';
        }
    }
}

if (!function_exists('cenos_is_enabled_maintenance')){
    function cenos_is_enabled_maintenance() {
        if ( ! cenos_get_option( 'maintenance_enable' ) ) {
            return false;
        }
        if ( current_user_can( 'super admin' ) || current_user_can( 'administrator' ) ) {
            return false;
        }
        return true;
    }
}

if (!function_exists('cenos_is_init_metabox')) {
    function cenos_is_init_metabox() {
        if (!defined( 'RWMB_VER' )) {
            return false;
        }
        global $pagenow;
        $now_check = array( 'edit.php' => 1, 'post.php' => 1, 'post-new.php' => 1);
        if (!empty($pagenow) && isset($now_check[$pagenow])) {
            return true;
        }
        return false;
    }
}

if (!function_exists('cenos_get_sidebars')) {
    function cenos_get_sidebars() {
        global $wp_registered_sidebars;
        $sidebars =  array();
        foreach ( $wp_registered_sidebars as $sidebar ){
            $sidebars[  $sidebar['id'] ] =   $sidebar['name'];
        }
        return $sidebars;
    }
}
if (!function_exists('cenos_get_block_post_options')) {
    function cenos_get_block_post_options( ) {
        $none_str = esc_html__('-- None --','cenos');
        $post_options = [false => $none_str];
        if (function_exists('fmtpl_get_block_post')){
            $posts = fmtpl_get_block_post();
            if ( $posts ) {
                foreach ( $posts as $post ) {
                    $post_options[ $post->ID ] = $post->post_title;
                }
            }
        }
        return $post_options;
    }
}

if (!function_exists('cenos_get_site_header_class')) {
    function cenos_get_site_header_class(){
        $header_class = ['site-header'];
        if (cenos_is_transparent_header()) {
            $header_class[] = 'cenos_transparent_header';
        }
        return implode(' ',$header_class);
    }
}
if (!function_exists('cenos_is_transparent_header')){
    function cenos_is_transparent_header(){
        if (is_404()){
            //not have transparent header
            return false;
        }
        $shop_id = false;
        if (cenos_is_woocommerce_activated()){
            if (is_product_taxonomy() || is_shop()){
                $shop_id = get_option( 'woocommerce_shop_page_id' );
                $page_header_transparent = cenos_get_post_meta($shop_id, 'page_header_transparent',true);
                if ($page_header_transparent) {
                    return true;
                }
            } elseif (is_product()) {
                //not have transparent header
                return false;
            }
        }
        if (is_page()) {
            $page_id = get_the_ID();
            $page_header_transparent = cenos_get_post_meta($page_id, 'page_header_transparent',true);
            if ($page_header_transparent) {
                return true;
            }
        }
        $option_transparent = cenos_get_option('show_transparent');
        if ($option_transparent) {
            $transparent_page = cenos_get_option('transparent_page');
            if ($transparent_page == 'all') {
                if (is_singular( 'post' )){
                    $bl_heading = cenos_get_option('blog_heading');
                    if ($bl_heading == true && cenos_blog_heading_display('post')){
                        return true;
                    }
                    return false;
                }
                return true;
            } elseif ($transparent_page == 'custom') {
                $custom_transparent_page = array_flip(cenos_get_option('custom_transparent_page'));
                if (is_front_page() && isset($custom_transparent_page['home'])) {
                    return true;
                } elseif($shop_id && isset($custom_transparent_page['shop'])) {
                    return true;
                }
            }
            elseif ($transparent_page == 'home' && is_front_page()){
                return true;
            }
        }

        return false;
    }
}
if (!function_exists('cenos_do_shortcode')) {
    /**
     * Call a shortcode function by tag name.
     *
     * @since  1.4.6
     *
     * @param string $tag     The shortcode whose function to call.
     * @param array  $atts    The attributes to pass to the shortcode function. Optional.
     * @param array  $content The shortcode's content. Default is null (none).
     *
     * @return string|bool False on failure, the result of the shortcode on success.
     */
    function cenos_do_shortcode( $tag, array $atts = array(), $content = null ) {
        global $shortcode_tags;
        if ( ! isset( $shortcode_tags[ $tag ] ) ) {
            return false;
        }
        return call_user_func( $shortcode_tags[ $tag ], $atts, $content, $tag );
    }
}
if (!function_exists('cenos_is_hex_color')) {
    function cenos_is_hex_color($color) {
        if(preg_match('/^#[a-fA-F0-9]{6}$/i', $color) || preg_match('/^#[a-fA-F0-9]{3}$/i', $color)) //hex color is valid
        {
            return true;
        }
        return false;
    }
}
if (!function_exists('cenos_convert_color_hex2rgba')) {
    function cenos_convert_color_hex2rgba($hex, $alpha = 0.9) {
        $hex = str_replace('#', '', $hex);
        $hex_length = strlen($hex);
        if ($hex_length == 6){
            $split_hex_color = str_split( $hex, 2 );
        } elseif ($hex_length == 3){
            $split_hex_color = str_split( $hex, 1 );
        }
        $rgb['r'] = hexdec($split_hex_color[0]);
        $rgb['g'] = hexdec($split_hex_color[1]);
        $rgb['b'] = hexdec($split_hex_color[2]);
        if ($alpha) {
            $rgb['a'] = $alpha;
        }
        return 'rgba('.$rgb['r'].','.$rgb['g'].','.$rgb['b'].','.$rgb['a'].')';
    }
}
if (!function_exists('cenos_lumdiff')) {
    function cenos_lumdiff($R1, $G1, $B1, $R2, $G2, $B2)
    {
        $L1 = 0.2126 * pow($R1 / 255, 2.2) +
            0.7152 * pow($G1 / 255, 2.2) +
            0.0722 * pow($B1 / 255, 2.2);
        $L2 = 0.2126 * pow($R2 / 255, 2.2) +
            0.7152 * pow($G2 / 255, 2.2) +
            0.0722 * pow($B2 / 255, 2.2);
        if ($L1 > $L2) {
            return ($L1 + 0.05) / ($L2 + 0.05);
        } else {
            return ($L2 + 0.05) / ($L1 + 0.05);
        }
    }
}
if (!function_exists('cenos_overlay_color')) {
    function cenos_overlay_color($color) {
        if (cenos_is_hex_color($color)){
            $color = cenos_convert_color_hex2rgba($color,0.9);
        } elseif (strpos('rgba',$color) !== false){
            $rgba = sscanf($color, "rgba(%d, %d, %d, %f)");
            if ($rgba[3] > 0.9){
                $rgba[3] = 0.9;
            }
            $color = 'rgba('.$rgba[0].','.$rgba[1].','.$rgba[2].','.$rgba[3].')';
        } elseif (strpos('rgb',$color) !== false){
            $rgb = sscanf($color, "rgb(%d, %d, %d)");
            $color = 'rgba('.$rgb[0].','.$rgb[1].','.$rgb[2].',0.9)';
        }
        return $color;
    }
}
if (!function_exists('cenos_on_device')) {
    function cenos_on_device() {
        global $on_device;
        if (is_null($on_device)){
            $detect = false;
            if (function_exists('fmfw_get_mobile_detect')){
                $detect = fmfw_get_mobile_detect();
            }
            if (!$detect){
                $on_device = wp_is_mobile();
            } else {
                if ($detect->isMobile()){
                    $mobile_breakpoint = cenos_get_option('mobile_breakpoint');
                    if ($mobile_breakpoint == 'mobile'){
                        if (!$detect->isTablet()){
                            $on_device = true;
                        } else {
                            $on_device = false;
                        }
                    } else {
                        $on_device = true;
                    }
                } else {
                    $on_device = false;
                }
            }
            $GLOBALS['on_device'] = $on_device;
        }
        return $on_device;
    }
}
if (!function_exists('cenos_device_hidden_class')) {
    function cenos_device_hidden_class() {
        $mobile_breakpoint = cenos_get_option('mobile_breakpoint');
        if ($mobile_breakpoint == 'mobile'){
            return 'hidden_on_mobile';
        }
        return 'hidden_on_device';
    }
}
if (!function_exists('cenos_not_device_hidden_class')) {
    function cenos_not_device_hidden_class() {
        $mobile_breakpoint = cenos_get_option('mobile_breakpoint');
        if ($mobile_breakpoint == 'mobile'){
            return 'not_mobile_hidden';
        }
        return 'not_device_hidden';
    }
}

if (!function_exists('cenos_woof_ajax_pagination')) {
    function cenos_woof_ajax_pagination() {
        if (class_exists('WOOF')) {
            if (get_option('woof_try_ajax', 0)) {
                return true;
            }
        }
        return false;
    }
}
if (!function_exists('cenos_check_active_plugin')){
    function cenos_check_active_plugin($slug){
        $instance = call_user_func(array(get_class($GLOBALS['tgmpa']), 'get_instance'));
        $wp_active_plugins = get_option( 'active_plugins', array() );
        $wp_check_active_plugin = in_array($slug,$wp_active_plugins,true);
        if (is_multisite()) {
            $site_active_plugins = get_site_option( 'active_sitewide_plugins' );
            if ( isset( $site_active_plugins[ $slug ] ) ) {
                $wp_check_active_plugin = true;
            }
        }
        return ( ( ! empty( $instance->plugins[ $slug ]['is_callable'] ) && is_callable( $instance->plugins[ $slug ]['is_callable'] ) ) || $wp_check_active_plugin );
    }
}


if (!function_exists('cenos_header_color_general')) {
    function cenos_header_color_general($color = '#242424',$color_hover = '#e36c02',$mode = 'default',$page_id = false){
        if (empty($color) && empty($color_hover)){
            return;
        }
        $selector_header_elements ='.header-main .header-element';
        $selector_header_nav ='#site-navigation > .nav-menu > li > a';
        $header_color_selector = [
            'header' => [
                'color' => [
                    $selector_header_elements,
                    $selector_header_elements.'.search_box.form .fm-search-form .search_text_input',
                    $selector_header_elements.'.search_box.form .fm-search-form button[type=submit]',
                    $selector_header_elements.'.search_form_wrapper .fm-search-form .search_text_input',
                    $selector_header_elements.'.search_form_wrapper .fm-search-form button[type=submit]',
                    $selector_header_elements.'.search_box.button > a',
                    $selector_header_elements.'.search_box.button > a span',
                    $selector_header_elements.'.cart_box > a',
                    $selector_header_elements.'.cart_box > a span',
                    $selector_header_elements.'.my-account-box > a',
                    $selector_header_elements.'.my-account-box > a span',
                    $selector_header_elements.'.wishlist-box > a',
                    $selector_header_elements.'.wishlist-box > a span',
                    $selector_header_elements.'.hamburger-box > a',
                    $selector_header_elements.'.hamburger-box > a span',
                    $selector_header_elements.'.contact-info-box > a',
                    $selector_header_elements.'.contact-info-box > a span',
                    $selector_header_elements.'.social-icon-box > a',
                    $selector_header_elements.'.social-icon-box > a span',
                    $selector_header_elements.'.currency-box > span.label',
                    $selector_header_elements.'.language-box > span.label',
                    $selector_header_elements.'.currency-box .dropdown > .current',
                    $selector_header_elements.'.currency-box .dropdown > .current span',
                    $selector_header_elements.'.language-box .dropdown > .current',
                    $selector_header_elements.'.language-box .dropdown > .current span',
                    $selector_header_elements.'.contact-info-box',
                    $selector_header_elements.'.html_right_1',
                    $selector_header_elements.'.html_left_1',
                    $selector_header_nav,
                    $selector_header_nav.' span',
                ],
                'hover' => [
                    $selector_header_elements.'.search_box.button > a:hover',
                    $selector_header_elements.'.search_box.button > a:hover span',
                    $selector_header_elements.'.cart_box > a:hover',
                    $selector_header_elements.'.cart_box > a:hover span',
                    $selector_header_elements.'.my-account-box > a:hover',
                    $selector_header_elements.'.my-account-box > a:hover span',
                    $selector_header_elements.'.wishlist-box > a:hover',
                    $selector_header_elements.'.wishlist-box > a:hover span',
                    $selector_header_elements.'.hamburger-box > a:hover',
                    $selector_header_elements.'.hamburger-box > a:hover span',
                    $selector_header_elements.'.contact-info-box > a:hover',
                    $selector_header_elements.'.contact-info-box > a:hover span',
                    $selector_header_elements.'.social-icon-box > a:hover',
                    $selector_header_elements.'.social-icon-box > a:hover span',
                    $selector_header_nav.':hover',
                    $selector_header_nav.':hover span',
                ],
                'fill' => [
                    $selector_header_elements.'.search_box.form .fm-search-form button[type=submit] svg',
                    $selector_header_elements.'.search_form_wrapper .fm-search-form button[type=submit] svg',
                    $selector_header_elements.'.search_box.button > a svg',
                    $selector_header_elements.'.search_box.button > a svg.fm-icon',
                    $selector_header_elements.'.cart_box > a svg',
                    $selector_header_elements.'.cart_box > a svg.fm-icon',
                    $selector_header_elements.'.my-account-box > a svg',
                    $selector_header_elements.'.my-account-box > a svg.fm-icon',
                    $selector_header_elements.'.wishlist-box > a svg',
                    $selector_header_elements.'.wishlist-box > a svg.fm-icon',
                    $selector_header_elements.'.hamburger-box > a svg',
                    $selector_header_elements.'.hamburger-box > a svg.fm-icon',
                    $selector_header_elements.'.contact-info-box > a svg',
                    $selector_header_elements.'.contact-info-box > a svg.fm-icon',
                    $selector_header_elements.'.social-icon-box > a svg',
                    $selector_header_elements.'.social-icon-box > a svg.fm-icon',
                    $selector_header_elements.'.currency-box .dropdown > .current svg',
                    $selector_header_elements.'.language-box .dropdown > .current svg',
                    $selector_header_elements.'.contact-info-box svg',
                    $selector_header_elements.'.contact-info-box svg.fm-icon',
                    $selector_header_elements.'.html_right_1 svg',
                    $selector_header_elements.'.html_right_1 svg.fm-icon',
                    $selector_header_elements.'.html_left_1 svg',
                    $selector_header_elements.'.html_left_1 svg.fm-icon',
                    $selector_header_nav.' svg',
                    $selector_header_nav.' svg.fm-icon',
                ],
                'hover_fill' => [
                    $selector_header_elements.'.search_box.button > a:hover svg',
                    $selector_header_elements.'.search_box.button > a:hover svg.fm-icon',
                    $selector_header_elements.'.cart_box > a:hover svg',
                    $selector_header_elements.'.cart_box > a:hover svg.fm-icon',
                    $selector_header_elements.'.my-account-box > a:hover svg',
                    $selector_header_elements.'.my-account-box > a:hover svg.fm-icon',
                    $selector_header_elements.'.wishlist-box > a:hover svg',
                    $selector_header_elements.'.wishlist-box > a:hover svg.fm-icon',
                    $selector_header_elements.'.hamburger-box > a:hover svg',
                    $selector_header_elements.'.hamburger-box > a:hover svg.fm-icon',
                    $selector_header_elements.'.contact-info-box > a:hover svg',
                    $selector_header_elements.'.contact-info-box > a:hover svg.fm-icon',
                    $selector_header_elements.'.social-icon-box > a:hover svg',
                    $selector_header_elements.'.social-icon-box > a:hover svg.fm-icon',
                    $selector_header_nav.':hover svg',
                    $selector_header_nav.':hover svg.fm-icon',
                ]
            ],
        ];
        $page_option_class = '';
        if ($page_id){
            $page_option_class = '.page-id-'.$page_id;
        }
        if ($mode =='transparent'){
            $prefix_class = 'body.familab_theme'.$page_option_class.' .site-header.cenos_transparent_header ';
        }elseif ($mode == 'sticky') {
            $prefix_class = 'body.familab_theme'.$page_option_class.' .site-header .header-layout.headroom--pinned.headroom--not-top ';
        } else {
            $prefix_class = '';
            if (!empty($page_option_class)){
                $prefix_class = 'body'.$page_option_class.' ';
            }
        }
        if (!empty($prefix_class)){
            $check_types = ['color','hover','fill','hover_fill'];
            foreach ( $check_types as $check_type){
                if (!empty($header_color_selector['header'][$check_type])){
                    foreach  ($header_color_selector['header'][$check_type] as &$selector){
                        $selector = $prefix_class.$selector;
                    }
                }
            }
        }
        $css = '';
        if (!empty($color)){
            if (!empty($header_color_selector['header']['color'])){
                $css .= implode(',',$header_color_selector['header']['color']);
                $css .= '{color:'.$color.';}';
            }
            if (!empty($header_color_selector['header']['fill'])){
                $css .= implode(',',$header_color_selector['header']['fill']);
                $css .= '{fill:'.$color.';}';
            }
        }
        if (!empty($color_hover)){
            if (!empty($header_color_selector['header']['hover'])){
                $css .= implode(',',$header_color_selector['header']['hover']);
                $css .= '{color:'.$color_hover.';}';
            }
            if (!empty($header_color_selector['header']['hover_fill'])){
                $css .= implode(',',$header_color_selector['header']['hover_fill']);
                $css .= '{fill:'.$color_hover.';}';
            }
        }
        return $css;
    }
}
if (!function_exists('get_elementor_site_setting')) {
    function get_elementor_site_setting() {
        global  $e_site_settings;
        if (is_null($e_site_settings)){
            $posts = get_posts(array('name' => 'default-kit', 'post_type' => 'elementor_library','post_status'=> 'publish'));
            if (isset($posts[0]) && !empty($posts[0])){
                $e_site_settings =  get_post_meta($posts[0]->ID,'_elementor_page_settings',true);
                $GLOBALS['e_site_settings'] = $e_site_settings;
            }
        }
        return $e_site_settings;
    }
}


add_filter('pre_wp_nav_menu', function ($output, $args) {
    if (!cenos_get_option('header_quick_menu')) {
        return $output;
    }
    /* This section is from wp_nav_menu(). It is here to find a menu when none is provided. */
    // @codingStandardsIgnoreStart
    // Get the nav menu based on the requested menu
    $menu = wp_get_nav_menu_object($args->menu);
    // Get the nav menu based on the theme_location
    if (!$menu && $args->theme_location && ($locations = get_nav_menu_locations()) && isset($locations[$args->theme_location]))
        $menu = wp_get_nav_menu_object($locations[$args->theme_location]);
    // get the first menu that has items if we still can't find a menu
    if (!$menu && !$args->theme_location) {
        $menus = wp_get_nav_menus();
        foreach ($menus as $menu_maybe) {
            if ($menu_items = wp_get_nav_menu_items($menu_maybe->term_id, array('update_post_term_cache' => false))) {
                $menu = $menu_maybe;
                break;
            }
        }
    }
    if (empty($args->menu)) {
        if (empty($menu)){
            return $output;
        }
        $args->menu = $menu;
    }
    // @codingStandardsIgnoreEnd
    /* End of the section from wp_nav_menu(). It was a pleasure, ladies and gents. */
    global $wp_query;
    $sign_args = (array)$args;
    unset($sign_args['items_wrap']);
    unset($sign_args['walker']);
    $menu_signature = md5(wp_json_encode($sign_args) . $wp_query->query_vars_hash);
    // We don’t actually need the references to all the cached versions of this menu,
    // but we need to make sure the cache is not out of sync - transients are unreliable.
    $cached_versions = get_transient('menu-cache-menuid-' . $args->menu->term_id);
    if (false !== $cached_versions) {
        $cached_output = get_transient('menu-cache-' . $menu_signature);
        if (!empty($cached_output)) {
            $output = $cached_output;
        }
    }
    return $output;
}, 10, 2);

add_filter('wp_nav_menu', function ($nav_menu, $args) {
    if (!cenos_get_option('header_quick_menu')) {
        return $nav_menu;
    }
    global $wp_query;
    $sign_args = (array)$args;
    unset($sign_args['items_wrap']);
    unset($sign_args['walker']);
    $menu_signature = md5(wp_json_encode($sign_args) . $wp_query->query_vars_hash);
    if (isset($args->menu->term_id)) {
        set_transient('menu-cache-' . $menu_signature, $nav_menu);
        // Store a reference to this version of the menu, so we can purge it when needed.
        $cached_versions = get_transient('menu-cache-menuid-' . $args->menu->term_id);
        if (false === $cached_versions) {
            $cached_versions = [];
        } else {
            $cached_versions = json_decode($cached_versions, true);
        }
        if (!in_array($menu_signature, $cached_versions, true)) {
            $cached_versions[] = $menu_signature;
        }
        set_transient('menu-cache-menuid-' . $args->menu->term_id, wp_json_encode($cached_versions), 15552000);
    }
    return $nav_menu;
}, 10, 2);

add_action('wp_update_nav_menu', function ($menu_id, $menu_data = null) {
    if (!cenos_get_option('header_quick_menu')) {
        return;
    }
    if (is_array($menu_data) && isset($menu_data['menu-name'])) {
        $menu = wp_get_nav_menu_object($menu_data['menu-name']);
        if (isset($menu->term_id)) {
            // Get all cached versions of this menu and delete them.
            $cached_versions = get_transient('menu-cache-menuid-' . $menu->term_id);
            if (false !== $cached_versions) {
                $cached_versions = json_decode($cached_versions, true);
                foreach ($cached_versions as $menu_signature) {
                    delete_transient('menu-cache-' . $menu_signature);
                }
                set_transient('menu-cache-menuid-' . $menu->term_id, wp_json_encode([]), 15552000);
            }
        }
    }
}, 10, 2);

if (!function_exists('cenos_check_page_type')){
    function cenos_check_page_type(){
        global $page_mode;
        if (is_null($page_mode)){
            $page_mode = false;
            if (cenos_is_woocommerce_activated()){
                if (is_shop() || is_product_category() || is_product_taxonomy() || is_product()){
                    $page_mode = 'woo';
                }
            }
            if (!$page_mode){
                if (is_home() || is_category() || is_archive() || is_singular( 'post' )) {
                    $page_mode = 'blog';
                } else {
                    $page_mode = 'global';
                }
            }
            $GLOBALS['page_mode'] = $page_mode;
        }
        return $page_mode;
    }
}
if (!function_exists('cenos_login_authenticate')){
    function cenos_login_authenticate() {
        check_ajax_referer( 'woocommerce-login', 'security' );

        $creds = array(
            'user_login'    => trim( wp_unslash( $_POST['username'] ) ),
            'user_password' => sanitize_text_field( $_POST['password'] ),
            'remember'      => ! empty( $_POST['rememberme'] ),
        );

        // Apply WooCommerce filters
        if ( class_exists( 'WooCommerce' ) ) {
            $validation_error = new WP_Error();
            $validation_error = apply_filters( 'woocommerce_process_login_errors', $validation_error, $creds['user_login'], $creds['user_password'] );

            if ( $validation_error->get_error_code() ) {
                wp_send_json_error( $validation_error->get_error_message() );
            }

            if ( empty( $creds['user_login'] ) ) {
                wp_send_json_error( esc_html__( 'Username is required.', 'cenos' ) );
            }

            // On multisite, ensure user exists on current site, if not add them before allowing login.
            if ( is_multisite() ) {
                $user_data = get_user_by( is_email( $creds['user_login'] ) ? 'email' : 'login', $creds['user_login'] );

                if ( $user_data && ! is_user_member_of_blog( $user_data->ID, get_current_blog_id() ) ) {
                    add_user_to_blog( get_current_blog_id(), $user_data->ID, 'customer' );
                }
            }

            $creds = apply_filters( 'woocommerce_login_credentials', $creds );
        }

        $user = wp_signon( $creds, is_ssl() );

        if ( is_wp_error( $user ) ) {
            wp_send_json_error( $user->get_error_message() );
        } else {
            wp_send_json_success( $user );
        }
    }
}

if (!function_exists('cenos_blog_heading_display')){
    function cenos_blog_heading_display($mode = ''){
        global $blog_heading_display_mode;
        if (is_null($blog_heading_display_mode) || !isset($blog_heading_display_mode[$mode])){
            $blog_heading_display = cenos_get_option('blog_heading_display');
            $blog_heading_display = array_flip($blog_heading_display);
            if (isset($blog_heading_display[$mode])){
                $blog_heading_display_mode[$mode] = true;
            } else {
                $blog_heading_display_mode[$mode] = false;
            }
            $GLOBALS['blog_heading_display_mode'] = $blog_heading_display_mode;
        }
        return $blog_heading_display_mode[$mode];
    }
}

if (!function_exists('cenos_media_content')){
    function cenos_media_content($media_url, $alt_text = '', $class = ''){
        global $wp_filesystem;
        require_once ( ABSPATH . '/wp-admin/includes/file.php' );
        WP_Filesystem();
        $svg_media_content = '';
        $file_path = str_replace(get_site_url().'/wp-content',WP_CONTENT_DIR,$media_url);
        $class_attr = empty($class)? '': ' class="'.$class.'"';
        if ( $wp_filesystem->exists( $file_path )) {
            $file_info = pathinfo($media_url);
            if (isset($file_info['extension']) && $file_info['extension'] == 'svg'){
                $svg_media_content =  $wp_filesystem->get_contents($media_url);
            }
            if (!empty($svg_media_content)){
                if (!empty($class)){
                    $svg_media_content = '<span'.$class_attr.'>'.$svg_media_content.'</span>';
                }
                return $svg_media_content;
            } else {
                return '<img src="'.$media_url.'" alt="'.$alt_text.'"'.$class_attr.'/>';
            }
        } else {
            return '<img src="'.$media_url.'" alt="'.$alt_text.'"'.$class_attr.'/>';
        }
    }
}