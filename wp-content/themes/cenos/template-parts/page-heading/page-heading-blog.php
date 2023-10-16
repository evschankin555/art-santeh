<?php
global $elementor_instance;
$page_heading_option = false;
$use_page_heading = true;
$page_bread = false;
$wrap_class = ['page-heading-wrap'];
if (isset($args['classes']) && !empty($args['classes'])) {
    $wrap_class[] = $args['classes'];
}
if (cenos_get_option('blog_heading_divider')){
    $wrap_class[] = 'has-divider';
}
if (is_page()){
    $page_id = get_the_ID();
    $page_heading_option = cenos_get_post_meta($page_id,'page_heading_option',true);
    if ($page_heading_option){
        $use_page_heading = cenos_get_post_meta($page_id,'use_page_heading',true);
    }
    $page_bread = cenos_get_post_meta($page_id,'page_breadcrumb_setting',true);
}
if ($page_heading_option ){
    if ($use_page_heading){
        $blog_heading_post = cenos_get_post_meta($page_id,'page_heading_block',true);
    }
} else {
    $blog_heading_post = cenos_get_option('blog_heading_post');
}
if ($page_bread){
    $blog_breadcrumb = cenos_get_post_meta($page_id,'page_breadcrumb',true);
} else {
    $blog_breadcrumb = cenos_get_option('blog_breadcrumb');
}
if ($use_page_heading):
    if (!empty($blog_heading_post) && $blog_heading_post != 'none') :?>
        <div class="<?php echo esc_attr(implode(' ', $wrap_class)); ?>">
            <?php
            if( $elementor_instance && cenos_is_built_with_elementor($blog_heading_post)){
                cenos_esc_data($elementor_instance->frontend->get_builder_content_for_display($blog_heading_post));
            } else {
                echo do_shortcode(get_post_field('post_content', $blog_heading_post));
            }
            ?>
        </div>
    <?php else:
        $page_type = '';
        if (isset($args['page_type'])){
            $page_type = $args['page_type'];
        }
        $key_prefix = 'background-';
        if ($page_heading_option ){
            $blog_heading_bg = cenos_get_post_meta($page_id,'page_heading_background',true);
            $blog_heading_text_color = cenos_get_post_meta($page_id,'page_heading_color',true);
            $key_prefix = '';
        } else {
            $blog_heading_bg = cenos_get_option('blog_heading_bg');
            $blog_heading_text_color = cenos_get_option('blog_heading_text_color');
        }
        $blog_heading_width = cenos_get_option('blog_heading_width');
        $blog_heading_align = cenos_get_option('blog_heading_align');
        $container_div = '';
        $close_container  = '';
        $content_container_class = 'container';
        if ($blog_heading_width == 'container'){
            $container_div = '<div class="container">';
            $close_container = '</div>';
            $content_container_class = '';
        }
        $page_attr = isset($page_type) ? $page_type : '';
        $page_title = '';
        switch ($page_type){
            case 'blog_list':
                $page_title = esc_html__('Latest Posts','cenos');
                break;
            case 'blog_cat':
                $page_title = get_the_archive_title();
                break;
            case 'blog_single':
                $page_title = get_the_title();
                break;
            default:
                if (strpos($page_type,'page_') !== false){
                    $page_attr = 'single-page';
                    $page_title = get_the_title();
                }
                break;
        }
        $overlay_div = '';
        if (isset($blog_heading_bg[$key_prefix.'image']) && !empty($blog_heading_bg[$key_prefix.'image'])
            && isset($blog_heading_bg[$key_prefix.'color']) && !empty($blog_heading_bg[$key_prefix.'color']))
        {
            $overlay_div = '<div class="page-heading-overlay">&nbsp;</div>';
        }
            $header_content_class = ['page-heading-content','blog-heading-content',$page_attr,'heading-'.$blog_heading_align]; ?>

        <div class="<?php echo esc_attr(implode(' ', $wrap_class)); ?>">
            <?php cenos_esc_data($container_div);?>
            <div class="<?php echo esc_attr(implode(' ', $header_content_class)); ?>">
                <?php cenos_esc_data($overlay_div); ?>
                <div class="<?php echo esc_attr($content_container_class);?>  page-heading-container blog-heading-container">
                    <h1 class="page-heading-title blog-title"><?php cenos_esc_data($page_title); ?></h1>
                    <?php
                        if ($blog_breadcrumb == 'in'){
                            cenos_breadcrumb();
                        }
                    ?>
                </div>
            </div>
            <?php cenos_esc_data($close_container);?>
        </div>
    <?php endif;?>
<?php endif;//end use_page_heading?>
    <?php if ($blog_breadcrumb == 'out'):?>
        <div class="header-breadcrumb-wrap">
            <div class="container">
                <?php cenos_breadcrumb();?>
            </div>
        </div>
<?php endif;?>
