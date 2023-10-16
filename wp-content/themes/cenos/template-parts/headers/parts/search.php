<?php
$search_type = cenos_get_option('search_type');
$search_class = ['header-element', 'search_box', $search_type];
$search_dropdown = true;
if (isset($args['class_tmp']) && $args['class_tmp'] != ''){
    $search_class[] =  $args['class_tmp'];
}
?>
<div class="<?php echo esc_attr(implode(' ', $search_class)); ?>">
    <?php
    if ($search_type == 'button') :
        $search_display_style = cenos_get_option('search_display_style');
        $search_btn_class = ['search-btn', 'search-btn-'.$search_display_style];
        $search_btn_text = cenos_get_option('search_btn_text');
        $search_btn_text_content = '';
        switch ($search_display_style){
            case 'modal':
                $search_dropdown = false;
                $search_btn_attr = 'data-toggle="modal" data-target="#fm-search-modal"';
                break;
            case 'canvas':
                $search_btn_class[] = 'js-offcanvas-trigger';
                $search_dropdown = false;
                $search_btn_attr = 'data-offcanvas-trigger="search-canvas"';
                break;
            default:
                $search_btn_attr = '';
                break;
        }

        if ($search_btn_text == true){
            $search_btn_class[] = 'show_text';
            $search_btn_text_content ='<span class="search-btn-title">'. cenos_get_option('search_btn_text_content').'</span>';
        }
        ?>
            <a href="javascript:void(0)" class="<?php echo esc_attr(implode(' ', $search_btn_class)); ?>" <?php cenos_esc_data($search_btn_attr)?>>
                <?php cenos_svg_icon('magnify',32,32) ?>
                <?php cenos_esc_data($search_btn_text_content); ?>
            </a>

    <?php endif;
    if ($search_dropdown == true):
    ?>
        <div class="search_form_wrapper dropdown">
            <div class="search_form_content">
                <?php get_template_part('template-parts/headers/parts/search/search', 'form_content'); ?>
            </div>
            <div class="search_result"></div>
        </div>
    <?php endif; ?>
</div>


