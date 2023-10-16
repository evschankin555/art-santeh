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
add_filter('body_class', 'cenos_body_class');

add_action( 'template_redirect', 'cenos_template_redirect', 1 );

if (cenos_is_enabled_maintenance()){
    return;
}

//After WordPress is fully loaded
add_action('wp','cenos_wp_loaded',11);

add_filter( 'wp_nav_menu_args', 'cenos_nav_menu_args' );

add_action('cenos_header','cenos_header');

add_action('cenos_header_control','cenos_header_control');
add_action('cenos_before_content','cenos_vertical_header_wrap');
add_action('cenos_after_footer','cenos_vertical_header_close_wrap',100);
add_action('fmtpl_after_footer','cenos_vertical_header_close_wrap',100);

add_action('cenos_before_content','cenos_page_heading');

add_action('cenos_footer','cenos_footer');
//modal - canvas content
add_action( 'wp_footer', 'cenos_search_content' );
add_action( 'wp_footer', 'cenos_cart_content' );
add_action( 'wp_footer', 'cenos_account_content' );
add_action( 'wp_footer', 'cenos_mobile_header_content' );
add_action( 'wp_footer', 'cenos_hamburger_content' );
add_action( 'wp_footer', 'cenos_scroll_to_top' );
add_action( 'wp_footer', 'cenos_mobile_direction_bar' );
add_action( 'wp_footer', 'cenos_popup' );

/**
 * Blog Posts
 */

add_action( 'cenos_single_post_bottom', 'cenos_post_taxonomy', 5 );
add_action( 'cenos_single_post_bottom', 'cenos_post_nav', 10 );
add_action( 'cenos_single_post_bottom', 'cenos_display_comments', 20 );

add_action( 'cenos_post_content_before', 'cenos_post_thumbnail', 10 );

//logo in center menu
add_filter( 'wp_nav_menu_objects','cenos_update_menu_middle_object', 10, 2 );
add_filter( 'wp_nav_menu_items','cenos_insert_logo_to_menu_items', 10, 2 );

add_action('cenos_sidebar','cenos_sidebar');

//cenos_loop_before
add_action('cenos_loop_before','cenos_open_div_row');
add_action('cenos_loop_after','cenos_close_div_row');
add_action('cenos_loop_after', 'cenos_paging_nav', 11 );
add_action('cenos_archive_before_content', 'cenos_list_blog_categories');
add_action('cenos_archive_before_content', 'cenos_blog_seach_form');
add_filter('get_the_archive_title_prefix','__return_false');
add_filter('widget_categories_args','cenos_widget_categories_args', 10, 2);
add_action('comment_form_before_fields', 'cenos_comment_form_before_fields');
add_action('comment_form_after_fields', 'cenos_comment_form_after_fields');

add_filter('woocs_drop_down_view','__return_false');

add_filter('disable_wvs_admin_enqueue_scripts','cenos_wvs_admin_enqueue_scripts',11);
add_action( 'wp_ajax_nopriv_cenos_login_authenticate', 'cenos_login_authenticate' );