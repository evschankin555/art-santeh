<?php
if (!function_exists('cenos_responsive_css')){
    function cenos_responsive_css(){
        global $wp_filesystem;
        // Initialize the WP filesystem, no more using 'file-put-contents' function
        if (empty($wp_filesystem)) {
            require_once(ABSPATH . '/wp-admin/includes/file.php');
            WP_Filesystem();
        }
        $file_responsive = CENOS_TEMPLATE_DIRECTORY.'/assets/css/site_responsive.css';
        $css = '';
        if( $wp_filesystem && file_exists($file_responsive)) {
            $css = $wp_filesystem->get_contents( $file_responsive );
        }
        return $css;
    }
}
$site_layout = cenos_get_option('site_layout');
if (in_array($site_layout,array('boxed','framed'))){
    $site_width = cenos_get_option('site_width');
    $css .= 'body.site_layout_'.$site_layout.'{ max-width:'. cenos_css_unit($site_width).';}';
    if ($site_layout == 'framed'){
        $css .= cenos_dimension_style('framed_margin','body.site_layout_framed');
    }
}
$container_width = cenos_get_option('container_width');
if ( ! isset( $content_width ) ) {
    $content_width = $container_width;
}
$container_width_value = cenos_demention_format($container_width);
$unit = str_replace($container_width_value,'',$container_width_value);
$container_width_value = (double)$container_width_value;
if ($unit == ''){
    $unit = 'px';
}

$css .= '@media(min-width: '.((int)$container_width_value + 30).$unit.') {';
$css .= '.container{ max-width:'. cenos_css_unit($container_width).';}';
$css .= '.elementor-section.elementor-section-boxed > .elementor-container { max-width:'. cenos_css_unit($container_width).';}';
$css .= cenos_responsive_css();
$css .= '}';

$css .= cenos_background_style('site_bg','html');
$site_advance_style = cenos_get_option('site_advance_style');
if ($site_advance_style == true){
    $css .= cenos_background_style('site_content_bg','#content');
    $css .= cenos_dimension_style('site_content_dimensions','#content');
}
