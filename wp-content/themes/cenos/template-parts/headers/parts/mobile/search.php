<?php
$search_class = ['header-element', 'search_box', 'button'];
$search_btn_class[] = 'js-offcanvas-trigger';
$search_dropdown = false;
$search_btn_attr = 'data-offcanvas-trigger="search-canvas"';

if (isset($args['class_tmp']) && $args['class_tmp'] != ''){
    $search_class[] =  $args['class_tmp'];
}
?>
<div class="<?php echo esc_attr(implode(' ', $search_class)); ?>">
    <?php
    $search_btn_class = ['search-btn', 'search-btn-canvas'];
    //if want show search button text set $search_btn_text = true
    $search_btn_text = false;
    $search_btn_text_content = '';
    if ($search_btn_text == true){
        $search_btn_class[] = 'show_text';
        $search_btn_text_content ='<span class="search-btn-title">'. cenos_get_option('search_btn_text_content').'</span>';
    }
    ?>
    <a href="javascript:void(0)" class="<?php echo esc_attr(implode(' ', $search_btn_class)); ?>" <?php cenos_esc_data($search_btn_attr)?>>
        <?php cenos_svg_icon('magnify',32,32) ?>
        <?php cenos_esc_data($search_btn_text_content); ?>
    </a>
</div>


