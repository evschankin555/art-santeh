<?php
if (!class_exists('Cenos_Svg_Icon')){
    class Cenos_Svg_Icon{
        protected $stroke_svg;
        protected $fill_svg;
        public function get_icon_class($icon = ''){
            return trim('fill');
        }
    }
}
$cenos_svg_icon = new Cenos_Svg_Icon();

if (!function_exists('cenos_svg_sprite')){
    function cenos_svg_sprite(){
        echo '<div id="svg-defs" class="svg-defs hidden">';
        include_once CENOS_TEMPLATE_DIRECTORY . '/assets/images/sprite.svg';
        echo '</div>';
    }
}
add_action('wp_body_open','cenos_svg_sprite');
add_action('elementor/theme/before_do_header','cenos_svg_sprite');
add_action('elementor/page_templates/canvas/before_content','cenos_svg_sprite');

if (!function_exists('cenos_svg_icon')){
    function cenos_svg_icon($id, $width = 16, $height = 16, $class=''){
        if ($id == ''){
            return;
        }
        global $cenos_svg_icon;
        ?>
        <svg viewBox="0 0 <?php echo esc_attr($width)?> <?php echo esc_attr($height)?>" class="<?php echo esc_attr($class); ?> fm-icon <?php echo esc_attr($cenos_svg_icon->get_icon_class($id));?>">
            <use xlink:href="#<?php echo esc_attr($id);?>"></use>
        </svg>
        <?php
    }
}
if (!function_exists('cenos_get_svg_icon')){
    function cenos_get_svg_icon($id, $width = 16, $height = 16, $class=''){
        ob_start();
        cenos_svg_icon($id,$width,$height,$class);
        return ob_get_clean();
    }
}


