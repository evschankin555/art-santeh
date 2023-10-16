<?php
include_once (dirname(__FILE__).'/settings.php');//important do not remove this line
$none_str =esc_html__('-- None --','cenos');

$block_choices = cenos_get_block_post_options();

$page_choices = [false => $none_str];
$all_pages = get_pages();

if($all_pages) {
    foreach ($all_pages as $page_obj) {
        $page_choices[$page_obj->ID] = $page_obj->post_title;
    }
}

include_once 'general/general.php';
include_once 'layout/site_layout.php';
include_once 'header/header.php';
include_once 'color_style/style.php';
include_once 'typography/typography.php';
include_once 'woocommerce/woo_setting.php';
include_once 'woocommerce/checkout.php';
include_once 'announcement/announcement.php';
include_once 'scrollbar/scrollbar.php';
if (cenos_is_woocommerce_activated()){
    include_once 'mobile/mobile.php';
    include_once 'mobile/direction.php';
}
include_once 'blog/blog.php';
include_once 'footer/footer.php';
