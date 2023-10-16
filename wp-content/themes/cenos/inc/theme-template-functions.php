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

if (!function_exists('cenos_body_class')) {
    function cenos_body_class($classes)
    {
        global $Cenos_theme;
        if ($Cenos_theme->is_maintenance_mode){
            $classes[] = 'maintenance-mode';
            return $classes;
        }
        // Adds a class of hfeed to non-singular pages.
        $site_layout = cenos_get_option('site_layout');
        //form_fields_style
        $on_device = cenos_on_device();
        if ($on_device){
            $classes[] = 'cenos-mobile';
        }
        if (cenos_get_option('header_vertical',false) && !$on_device){
            $classes[] = 'vertical-header';
        }
        $form_fields_style = cenos_get_option('form_fields_style');
        $btn_woo_style = cenos_get_option('btn_default_style');
        $page_pagination_style = cenos_get_option('page_pagination_style');
        $page_pagination_align = cenos_get_option('page_pagination_align');
        $classes[] = 'familab_theme';
        $classes[] = 'theme-' . $Cenos_theme->slug;
        $classes[] = 'theme-' . $Cenos_theme->template;
        $classes[] = 'site_layout_' . $site_layout;
        $classes[] = 'form_style_' . $form_fields_style;
        $classes[] = 'btn-woo-'.$btn_woo_style;
        $classes[] = 'pagination-' .$page_pagination_style;
        $classes[] = 'pagination-align-' .$page_pagination_align;
        if (in_array($site_layout, array('boxed', 'framed')) && cenos_get_option('content_box_shadow') == 1) {
            $classes[] = 'box-shadow';
        }
        if (cenos_is_woocommerce_activated()) {
            if (is_shop() || is_product_taxonomy()) {
                $classes[] = 'page-shop-option';
                $shop_page_layout = cenos_get_option('shop_page_layout');
                $classes[] = 'shop-page-layout-'.$shop_page_layout;
            } elseif (is_product()) {
                if (cenos_is_woo_single_has_backround()) {
                    $classes[] = 'single_product_has_background';
                }
                $woo_single_layout = cenos_get_option('woo_single_layout');
                $classes[] = 'single-product-layout-'.$woo_single_layout;
                if ($on_device && cenos_get_option('single_sticky_atc')) {
                    $classes[] = 'has-sticky-atc';
                }
            } elseif (is_checkout()){
                $checkout_layout = cenos_get_option('checkout_layout');
                $classes[] = 'checkout_layout_'.$checkout_layout;
            }
            if ($on_device && !is_product() && cenos_get_option('show_direction_bar')){
                $classes[] = 'has-direction-bar';
            }
        }
        $blog_sidebar = cenos_sidebar_layout();
        if (!empty($blog_sidebar)){
            $classes[] = 'blog-layout-'.$blog_sidebar['layout'];
        }
        return $classes;
    }
}

if (!function_exists('cenos_header')){
    function cenos_header(){
        $header_mobile_layout = cenos_get_option('header_mobile_layout');
        if (cenos_on_device()){
            if (cenos_is_woocommerce_activated()){
                if (cenos_get_option('show_direction_bar') && (is_shop() || is_product_taxonomy())){
                    //
                    return;
                }
                if (is_product()){
                    get_template_part('template-parts/headers/parts/mobile/header-mobile-single-product', null,[]);
                    return;
                }
            }
            get_template_part('template-parts/headers/header-mobile', $header_mobile_layout,['header_mobile_layout' => $header_mobile_layout]);
            return;
        }
        if (cenos_get_option('header_vertical',false)){
            get_template_part('template-parts/headers/header', 'vertical');
            return;
        }

        $header_layout = cenos_get_option( 'header_layout' );
        $header_class = ['header-layout','header-'.$header_layout];
        $cenos_device_hidden_class = cenos_device_hidden_class();
        $header_class[] = $cenos_device_hidden_class;
        if (cenos_get_option('header_divider') == 'border'){
            $header_class[] = 'cenos-border-color';
        }
        $show_sticky = cenos_get_option('show_sticky');
        if ($show_sticky == true){
            $header_class[] = 'fm-header-sticky main-header-sticky';
        }
        ?>
        <?php get_template_part( 'template-parts/headers/parts/topbar');?>
        <div class="<?php echo esc_attr( implode( ' ', $header_class ) ); ?>">
            <?php get_template_part( 'template-parts/headers/header', $header_layout);?>
        </div>
        <?php if ($show_sticky == true):
            $header_layout_has_bottom = ['layout3' => 1,'layout8' => 1,'layout10' => 1,'layout11' => 1]; ?>
            <div class="header-space <?php echo esc_attr($cenos_device_hidden_class.' header-'.$header_layout);?>">
                <div class="header-main"></div>
                <?php if (isset($header_layout_has_bottom[$header_layout])):?>
                <div class="header-bottom"></div>
                <?php endif; ?>
            </div>
        <?php endif;
        get_template_part('template-parts/headers/header-mobile', null,['header_mobile_layout' => ($header_mobile_layout == 'layout4') ? 'layout1':$header_mobile_layout]);
    }
}

if (!function_exists('cenos_breadcrumb')) {
    function cenos_breadcrumb(){
        if( !function_exists('breadcrumb_trail')) return;
        if( is_front_page()) return;
        $args = array(
            'container'       => 'div',
            'before'          => '',
            'after'           => '',
            'show_on_front'   => true,
            'network'         => false,
            'show_title'      => true,
            'show_browse'     => false,
            'post_taxonomy'   => array(),
            'echo'            => true
        );
        ?>
        <div class="cenos-breadcrumbs">
            <?php breadcrumb_trail($args); ?>
        </div>
        <?php
    }
}

if (!function_exists('cenos_header_control')){
    function cenos_header_control($control){
        $elements = cenos_get_option($control);
        if ($elements && !empty($elements)){
            $path = '';
            if (strpos($control,'mobile') !== false){
                $path = 'mobile/';
            }
            foreach ($elements as $part){
                if (strpos($part,'html') !== false){
                    get_template_part('template-parts/headers/parts/'.$path.'html',null,['html_part'=>$part]);
                } else {
                    get_template_part('template-parts/headers/parts/'.$path.$part,null,['class_tmp' => '']);
                }
            }
        }
    }
}

if (!function_exists('cenos_page_heading')){
    function cenos_page_heading(){
        $page_heading_content = false;
        $page_type = '';
        $page_headding_class = '';
        if (cenos_is_woocommerce_activated()){
            if (is_shop()){
                $page_heading_content = 'shop';
                $page_type = 'shop';
            } elseif (is_product_category()){
                $page_heading_content = 'shop';
                $page_type = 'product_cat';
            } elseif (is_product_taxonomy()){
                $page_heading_content = 'shop';
                $page_type = 'product_tax';
            }elseif (is_product()){
                return;
            }
        }
        if ($page_heading_content != 'shop'){
            $blog_heading = cenos_get_option('blog_heading');

            if (is_front_page()){
                $page_heading_content = false;
            } elseif (is_home()) {
                if ($blog_heading == true && cenos_blog_heading_display('blog')){
                    $page_heading_content = 'blog';
                    $page_type = 'blog_list';
                }
            } elseif (is_category() || is_archive()) {
                if ($blog_heading == true && cenos_blog_heading_display('list')){
                    $page_heading_content = 'blog';
                    $page_type = 'blog_cat';
                }
            } elseif (is_singular( 'post' )) {
                if ($blog_heading == true && cenos_blog_heading_display('post')){
                    $page_heading_content = 'blog';
                    $page_type = 'blog_single';
                }
            } elseif (is_page() && !is_home()){
                $page_id = get_the_ID();
                $page_heading_content = 'blog';
                $page_type = 'page_'.$page_id;
            }
            if ($blog_heading) {
                $blog_heading_mobile = cenos_get_option('blog_heading_mobile');
                if (!$blog_heading_mobile){
                    if (cenos_on_device()){
                        $page_heading_content = false;
                    }
                    $page_headding_class = cenos_device_hidden_class();
                }
            }
        } else {
            $shop_heading = cenos_get_option('shop_heading');
            if (!$shop_heading){
                $page_heading_content = false;
            } else {
                $shop_heading_mobile = cenos_get_option('shop_heading_mobile');
                if (!$shop_heading_mobile){
                    if (cenos_on_device()){
                        $page_heading_content = false;
                    }
                    $page_headding_class = cenos_device_hidden_class();
                }
            }
        }
        if (!empty($page_heading_content)){
            $page_heading_style = cenos_get_option($page_heading_content.'_heading_layout');
            get_template_part('template-parts/page-heading/page-heading-'.$page_heading_content,$page_heading_style,['page_type'=> $page_type, 'classes' => $page_headding_class]);
        }
    }
}

if (!function_exists('cenos_button_class')){
    function cenos_button_class($type = 'default'){
        $btn_style = cenos_get_option('btn_'.$type.'_style');
        $class = 'btn btn-'.$type.' btn-'.$btn_style;
        return $class;
    }
}

//modal content
if (!function_exists('cenos_control_element_exist')) {
    function cenos_control_element_exist($control, $control_key = array()) {
        if (empty($control_key)){
            $control_key = [
                'left_control',
                'right_control',
                'topbar_left',
                'topbar_center',
                'topbar_right',
                'bottom_left_control',
                'bottom_right_control'
            ];
        }
        if (!is_array($control_key)){
            $control_key = (array) $control_key;
        }
        $result = false;
        foreach ($control_key as $k){
            $element_control = cenos_get_option($k);
	    if (empty($element_control)){
	    	continue;
	    }
            $element_control_key = array_flip($element_control);
            if (isset($element_control_key[$control])) {
                $result = true;
                break;
            }
        }
        return $result;
    }
}
if (!function_exists('cenos_search_content')){
    function cenos_search_content(){
        $search_display_style = false;
        global $search_canvas_style,$search_modal_style;
        if (!cenos_on_device()) {
            if (cenos_control_element_exist('search')){
                $search_display_style = cenos_get_option('search_display_style');
            }
        }
        if (!$search_canvas_style && $search_display_style == 'canvas'){
            get_template_part('template-parts/headers/parts/search/search','canvas');
            $GLOBALS['search_canvas_style'] = true;
        } else {
            if (!$search_canvas_style && cenos_control_element_exist('search',['mobile_right_control'])) {
                get_template_part('template-parts/headers/parts/search/search','canvas');
                $GLOBALS['search_canvas_style'] = true;
            }
            if (!$search_modal_style && $search_display_style == 'modal') {
                $GLOBALS['search_modal_style'] = true;
                get_template_part('template-parts/headers/parts/search/search','modal');
            }
        }
    }
}

if (!function_exists('cenos_cart_content')){
    function cenos_cart_content(){
        global $cart_canvas_style;
        $cart_style = false;
        if (!cenos_on_device()) {
            if (cenos_control_element_exist('cart')){
                $cart_style = cenos_get_option('cart_style');
            }
        }
        if (!$cart_canvas_style && $cart_style == 'canvas'){
            get_template_part('template-parts/headers/parts/cart/cart','canvas');
            $GLOBALS['cart_canvas_style'] = true;
        } else {
            if (!$cart_canvas_style && cenos_control_element_exist('cart',['mobile_right_control','direction_component'])) {
                get_template_part('template-parts/headers/parts/cart/cart','canvas');
                $GLOBALS['cart_canvas_style'] = true;
            }
        }
    }
}

if (!function_exists('cenos_account_content')){
    function cenos_account_content(){
        if (!is_user_logged_in()){
            global $account_canvas_style,$account_modal_style;
            $account_style = false;
            if (!cenos_on_device()) {
                if (cenos_control_element_exist('account')){
                    $account_style = cenos_get_option('account_style');
                }
            }
            if (!$account_canvas_style && $account_style == 'canvas'){
                get_template_part('template-parts/headers/parts/account/account',$account_style);
                $GLOBALS['account_canvas_style'] = true;
            } else {
                if (!$account_canvas_style && cenos_control_element_exist('account',['mobile_right_control','direction_component'])) {
                    get_template_part('template-parts/headers/parts/account/account','canvas');
                    $GLOBALS['account_canvas_style'] = true;
                }
                if (!$account_modal_style && $account_style == 'modal') {
                    get_template_part('template-parts/headers/parts/account/account',$account_style);
                    $GLOBALS['account_modal_style'] = true;
                }
            }
        }
    }
}

if (!function_exists('cenos_mobile_header_content')){
    function cenos_mobile_header_content(){
        get_template_part('template-parts/headers/header-mobile-content');
    }
}

if (!function_exists('cenos_hamburger_content')){
    function cenos_hamburger_content(){
        if (!cenos_on_device()){
            if (cenos_control_element_exist('hamburger') || cenos_get_option('header_layout') == 'layout2'){
                get_template_part('template-parts/headers/parts/hamburger/hamburger','canvas');
            }
        }
    }
}

if ( ! function_exists( 'cenos_display_comments' ) ) {
    /**
     * cenos display comments
     *
     * @since  1.0.0
     */
    function cenos_display_comments() {
        // If comments are open or we have at least one comment, load up the comment template.
        if ( comments_open() || 0 !== intval( get_comments_number() ) ) :
            comments_template();
        endif;
    }
}

if ( ! function_exists( 'cenos_comment' ) ) {
    /**
     * cenos comment template
     *
     * @param array $comment the comment array.
     * @param array $args the comment args.
     * @param int   $depth the comment depth.
     * @since 1.0.0
     */
    function cenos_comment( $comment, $args, $depth ) {
        if ( 'div' === $args['style'] ) {
            $tag       = 'div';
            $add_below = 'comment';
        } else {
            $tag       = 'li';
            $add_below = 'div-comment';
        }
        $avatar = get_avatar( $comment, 128 );
        ?>
        <<?php echo esc_attr( $tag ); ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?> id="comment-<?php comment_ID(); ?>">
        <div class="comment-body">
            <?php if (!empty($avatar)):?>
            <div class="comment-meta user-avatar">
                <div class="comment-author vcard">
                    <?php cenos_esc_data($avatar); ?>
                </div>
            </div>
            <?php endif;?>
            <?php if ( 'div' !== $args['style'] ) : ?>
            <div id="div-comment-<?php comment_ID(); ?>" class="comment-content">
            <?php endif; ?>
                <div class="comment-meta comment-heading">
                    <div class="comment-info">
                        <?php if ( '0' === $comment->comment_approved ) : ?>
                            <em class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'cenos' ); ?></em>
                        <?php endif; ?>
                        <?php printf( '<cite class="author-display-name">%s</cite>', get_comment_author_link() ); ?>
                        <a href="<?php echo esc_url( htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ); ?>" class="comment-date">
                            <?php echo '<time datetime="' . get_comment_date( 'c' ) . '">' . get_comment_date('F j, Y g:i a') . '</time>'; ?>
                        </a>
                    </div>
                    <div class="comment-action">
                        <?php
                        comment_reply_link(
                            array_merge(
                                $args, array(
                                    'add_below' => $add_below,
                                    'depth'     => $depth,
                                    'max_depth' => $args['max_depth'],
                                )
                            )
                        );
                        ?>
                        <?php edit_comment_link(esc_html__( 'Edit', 'cenos' ), '  ', '' ); ?>
                    </div>
                </div>
                <div class="comment-text">
                    <?php comment_text(); ?>
                </div>
            <?php if ( 'div' !== $args['style'] ) : ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}

if ( ! function_exists( 'cenos_post_content' ) ) {
    /**
     * Display the post content with a link to the single post
     *
     * @since 1.0.0
     */
    function cenos_post_content() {
        ?>
        <div class="entry-content">
            <?php

            /**
             * Functions hooked in to cenos_post_content_before action.
             *
             * @hooked cenos_post_thumbnail - 10
             */
            do_action( 'cenos_post_content_before' );
            the_content();
            do_action( 'cenos_post_content_after' );
            wp_link_pages(
                array(
                    'before' => '<div class="page-links">' .esc_html__( 'Pages:', 'cenos' ),
                    'after'  => '</div>',
                )
            );
            ?>
        </div><!-- .entry-content -->
        <?php
    }
}

if ( ! function_exists( 'cenos_post_meta' ) ) {
    /**
     * Display the post meta
     * @var $meta_output Meta data output. posted_on|updated_on|author|comments|sticky_post
     * @since 1.0.0
     */
    function cenos_post_meta($meta_output = array(), $show_icon = true, $post = null) {
        global $post;
        if (empty($meta_output)) {
            $meta_output = ['posted_on','updated_on', 'author','comments', 'sticky_post'];
        } elseif (!is_array($meta_output)) {
            $meta_output = (array)$meta_output;
        }
        if ( 'post' !== get_post_type() ) {
            return;
        }
        $result = [];
        if (in_array('posted_on',$meta_output)){
            // Posted on.
            $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
            if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
                $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
            }

            $time_string = sprintf(
                $time_string,
                esc_attr( get_the_date( 'c' ) ),
                esc_html( get_the_date() )
            );
            $output_time_string = sprintf( '<a href="%1$s" rel="bookmark">%2$s</a>', esc_url( get_permalink() ), $time_string );
            $result['posted_on'] = '<span class="posted-on">'. ($show_icon ? cenos_get_svg_icon('calendar'):'').
                /* translators: %s: post date */
                sprintf(esc_html__( 'Posted on %s', 'cenos' ), $output_time_string ) .
                '</span>';
        }
        if (in_array('updated_on',$meta_output)){
            if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
                $time_string = '<time class="entry-date updated" datetime="%1$s">%2$s</time>';
                $time_string = sprintf(
                    $time_string,
                    esc_attr( get_the_modified_date( 'c' ) ),
                    esc_html( get_the_modified_date() )
                );
                $output_time_string = sprintf( '<a href="%1$s" rel="bookmark">%2$s</a>', esc_url( get_permalink() ), $time_string );
                $result['updated_on'] = '<span class="posted-on updated-on">' .($show_icon?cenos_get_svg_icon('calendar'):'').
                    /* translators: %s: post date */
                    sprintf(esc_html__( 'Updated on %s', 'cenos' ), $output_time_string ) .
                    '</span>';
            }
        }
        if (in_array('author',$meta_output)) {
            // Author.
            $result['author'] = sprintf(
                '<span class="post-author">%1$s %2$s<a href="%3$s" class="url fn" rel="author">%4$s</a></span>',
                $show_icon?cenos_get_svg_icon('user'):'',
               esc_html__('by', 'cenos'),
                esc_url(get_author_posts_url(get_the_author_meta('ID' , $post->post_author))),
                esc_html(get_the_author_meta( 'display_name', $post->post_author ))
            );
        }
        if (in_array('comments',$meta_output)) {
            // Comments.
            $comments = '';
            if (!post_password_required() && (comments_open() || 0 !== intval(get_comments_number()))) {
                $comments_number = get_comments_number_text(__('Leave a comment', 'cenos'),esc_html__('1 Comment', 'cenos'),esc_html__('% Comments', 'cenos'));
                $comments = sprintf(
                    '<span class="post-comments">%1$s <a href="%2$s">%3$s</a></span>',
                    $show_icon?cenos_get_svg_icon('chat'):'',
                    esc_url(get_comments_link()),
                    $comments_number
                );
            }
            $result['comments'] = $comments;
        }
        if (in_array('sticky_post',$meta_output)) {
            // Sticky.
            $sticky_post = '';
            if (is_sticky()) {
                $sticky_post = '<span class="post-sticky">' . ($show_icon?cenos_get_svg_icon('bookmark'):'') .esc_html__('Sticky post', 'cenos') . '</span>';
            }
            $result['sticky_post'] = $sticky_post;
        }
        foreach ($meta_output as $meta_get){
            if (isset($result[$meta_get])){
                cenos_esc_data($result[$meta_get]);
            }
        }
    }
}

if ( ! function_exists( 'cenos_post_taxonomy' ) ) {
    /**
     * Display the post taxonomies
     * @var $tax_output taxonomies data output. categories|tags
     * @since 2.4.0
     */
    function cenos_post_taxonomy($tax_output = array(), $show_icon = true) {
        if (empty($tax_output)) {
            $tax_output = ['categories', 'tags'];
        } elseif (!is_array($tax_output)) {
            $tax_output = (array)$tax_output;
        }
        $result = [];
        if (in_array('categories',$tax_output)){
            /* translators: used between list items, there is a space after the comma */
            $categories_list = get_the_category_list(esc_html__( ', ', 'cenos' ) );
            if ( $categories_list ) {
                $result['categories'] = '<aside class="entry-taxonomy"><div class="cat-links">'.($show_icon?cenos_get_svg_icon('folder'):'').esc_html( _n( 'Category:', 'Categories:', count( get_the_category() ), 'cenos' ) ).wp_kses( $categories_list ,'post').'</div></aside>';
            }
        }

        if (in_array('tags',$tax_output)) {
            /* translators: used between list items, there is a space after the comma */
            $tags_list = get_the_tag_list('',esc_html__(', ', 'cenos'));
            if ($tags_list) {
                $result['tags'] = '<aside class="entry-taxonomy"><div class="tags-links">'.($show_icon?cenos_get_svg_icon('tag'):'').esc_html( _n( 'Tag:', 'Tags:', count( get_the_tags() ), 'cenos' ) ).wp_kses( $tags_list ,'post').'</div></aside>';
            }
        }
        foreach ($tax_output as $tag_get){
            if (isset($result[$tag_get])){
                cenos_esc_data($result[$tag_get]);
            }
        }
    }
}

if ( ! function_exists( 'cenos_paging_nav' ) ) {
    /**
     * Display navigation to next/previous set of posts when applicable.
     */
    function cenos_paging_nav() {

        $args = array(
            'type'      => 'list',
            'next_text' => _x( 'Next', 'Next post', 'cenos' ).cenos_get_svg_icon('arrow-triangle-right'),
            'prev_text' => cenos_get_svg_icon('arrow-triangle-left')._x( 'Prev', 'Previous post', 'cenos' ),
        );

        the_posts_pagination( $args );
    }
}

if ( ! function_exists( 'cenos_post_nav' ) ) {
    /**
     * Display navigation to next/previous post when applicable.
     */
    function cenos_post_nav() {
        $args = array(
            'next_text' => '<span class="post_nav-text">' . esc_html__( 'Next post', 'cenos' ) . ' </span><span class="nav-post-title">%title</span>',
            'prev_text' => '<span class="post_nav-text">' . esc_html__( 'Previous post', 'cenos' ) . ' </span><span class="nav-post-title">%title</span>',
        );
        the_post_navigation( $args );
    }
}

if ( ! function_exists( 'cenos_post_thumbnail' ) ) {
    /**
     * Display post thumbnail
     *
     * @var $size thumbnail size. thumbnail|medium|large|full|$custom
     * @uses has_post_thumbnail()
     * @uses the_post_thumbnail
     * @param string $size the post thumbnail size.
     * @since 1.0.0
     */
    function cenos_post_thumbnail( $size = 'full' ) {
        if ( has_post_thumbnail() ) {
            the_post_thumbnail( $size );
        }
    }
}

if ( ! function_exists( 'cenos_currency_switcher' ) ) :
    /**
     * Print HTML of currency switcher
     * It requires plugin WooCommerce Currency Switcher installed
     */
    function cenos_currency_switcher( $direction = 'down' ) {
        if ( ! class_exists( 'WOOCS' ) ) {
            return;
        }
        global $WOOCS;
        $currencies    = $WOOCS->get_currencies();
        $currency_list = array();

        foreach ( $currencies as $key => $currency ) {
            if ( $WOOCS->current_currency == $key ) {
                array_unshift( $currency_list, sprintf(
                    '<li><a href="#" class="woocs_flag_view_item woocs_flag_view_item_current" data-currency="%s">%s</a></li>',
                    esc_attr( $currency['name'] ),
                    esc_html( $currency['name'] )
                ) );
            } else {
                $currency_list[] = sprintf(
                    '<li><a href="#" class="woocs_flag_view_item" data-currency="%s">%s</a></li>',
                    esc_attr( $currency['name'] ),
                    esc_html( $currency['name'] )
                );
            }
        }
        ?>
        <div class="currency list-dropdown <?php echo esc_attr( $direction ) ?>">
            <span class="label"><?php echo esc_html('Currency','cenos' ); ?></span>
            <div class="dropdown">
				<span class="current">
					<span class="selected"><?php echo esc_html( $currencies[ $WOOCS->current_currency ]['name'] ); ?></span>
					<?php cenos_svg_icon( 'angle-down' ) ?>
				</span>
                <ul>
                    <?php echo implode( "\n\t", $currency_list ); ?>
                </ul>
            </div>
        </div>
        <?php
    }
endif;

if ( ! function_exists( 'cenos_language_switcher' ) ) :
    /**
     * Print HTML of language switcher
     * It requires plugin WPML installed
     */
    function cenos_language_switcher($direction = 'down' ) {
        $languages = function_exists( 'icl_get_languages' ) ? icl_get_languages() : array();
        $languages = apply_filters( 'wpml_active_languages', $languages );
        if ( empty( $languages ) ) {
            return;
        }

        $lang_list = array();
        $current   = '';
        foreach ( (array) $languages as $code => $language ) {
            if ( ! $language['active'] ) {
                $lang_list[] = sprintf(
                    '<li class="%s"><a href="%s">%s</a></li>',
                    esc_attr( $code ),
                    esc_url( $language['url'] ),
                    esc_html( $language['native_name'] )
                );
            } else {
                $current = $language;
                array_unshift( $lang_list, sprintf(
                    '<li class="%s"><a href="%s">%s</a></li>',
                    esc_attr( $code ),
                    esc_url( $language['url'] ),
                    esc_html( $language['native_name'] )
                ) );
            }
        }
        ?>

        <div class="language list-dropdown <?php echo esc_attr( $direction ) ?>">
            <span class="label"><?php echo esc_html('Language','cenos' ); ?></span>
            <div class="dropdown">
				<span class="current">
					<span class="selected"><?php echo esc_html( $current['native_name'] ) ?></span>
					<?php cenos_svg_icon( 'angle-down' ) ?>
				</span>
                <ul>
                    <?php echo implode( "\n\t", $lang_list ); ?>
                </ul>
            </div>
        </div>

        <?php
    }
endif;

if (!function_exists('cenos_social_icon_list')){
    function cenos_social_icon_list($items = [],$show_icon = true, $show_title = false){
        if (!empty($items)):
            foreach ($items as $si):
                $si_info = cenos_get_option($si.'_link');
                if ($si_info == ''){
                    $si_info = '#';
                }?>
                <a href="<?php echo esc_url($si_info);?>" class="fm-social-icon-link fm-<?php echo esc_attr($si)?>">
                    <?php
                    if ($show_icon == true || $show_title == false){
                        //define icon name if social name difficult icon name in svg sprite
                        $icon_name = apply_filters('cenos_social_svg_icon_name',[]);
                        if (isset($icon_name[$si])){
                            cenos_svg_icon($icon_name[$si]);
                        } else {
                            cenos_svg_icon($si);
                        }
                    }
                    if ($show_title == true):?>
                        <span class="fm-social-icon-title"><?php echo cenos_social_label($si);?></span>
                    <?php endif;?>
                </a>
            <?php endforeach;
        endif;
    }
}

if (!function_exists('cenos_announcement_bar')){
    function cenos_announcement_bar(){
        $page_template = basename(get_page_template());
        if ($page_template == 'fullpage.php' || $page_template == 'no-footer.php'){
            return;
        }
        $show_announcement = cenos_get_option('show_announcement');
        if ($show_announcement == true && !isset($_COOKIE['announcement_dismissed'])) {
            $args = [];
            $show_announcement_mobile = cenos_get_option('show_announcement_mobile');
            if (!$show_announcement_mobile){
                if (cenos_on_device()){
                    return;
                }
                $args['classes'] = cenos_device_hidden_class();
            }

            $announcement_layout = cenos_get_option('announcement_layout');
            $args['position'] = cenos_get_option('announcement_position');
            get_template_part('template-parts/announcement/announcement',$announcement_layout,$args);
        }
    }
}

if (!function_exists('cenos_wp_loaded')){
    function cenos_wp_loaded(){
        //After WordPress is fully loaded
        global $Cenos_theme;
        if ($Cenos_theme->is_maintenance_mode){
            return;
        }
        $show_announcement = cenos_get_option('show_announcement');
        if ($show_announcement){
            $announcement_position = cenos_get_option('announcement_position');
            switch ($announcement_position){
               case 'top':
                   add_action('cenos_before_site','cenos_announcement_bar');
                   break;
               default:
                   add_action('cenos_after_footer','cenos_announcement_bar');
                   break;
            }
        }
    }
}

if (!function_exists('cenos_template_redirect')){
    function cenos_template_redirect(){
        global $Cenos_theme;
        if (!$Cenos_theme->is_maintenance_mode){
            return;
        }
        $mode     = cenos_get_option( 'maintenance_mode' );
        $page_id  = cenos_get_option( 'maintenance_page' );
        $code     = 'maintenance' == $mode ? 503 : 200;
        $page_url = $page_id ? get_page_link( $page_id ) : '';

        // Use default message.
        if ( ! $page_id || ! $page_url ) {
            if ( 'coming_soon' == $mode ) {
                $message = sprintf( '<h1>%s</h1><p>%s</p>', esc_html__( 'Coming Soon', 'cenos' ), esc_html__( 'Our website is under construction. We will be here soon with our new awesome site.', 'cenos' ) );
            } else {
                $message = sprintf( '<h1>%s</h1><p>%s</p>', esc_html__( 'Website Under Maintenance', 'cenos' ), esc_html__( 'Our website is currently undergoing scheduled maintenance. Please check back soon.', 'cenos' ) );
            }

            wp_die( $message, get_bloginfo( 'name' ), array( 'response' => $code ) );
        }
        if ( ! is_page( $page_id ) ) {
            wp_redirect( $page_url );
            exit;
        } else {
            if ( ! headers_sent() ) {
                status_header( $code );
            }
            add_action('cenos_header','cenos_maintenance_header');
        }
    }
}

if (!function_exists('cenos_maintenance_header')){
    function cenos_maintenance_header(){
        ?>
        <div class="site-header maintenance-header text-center">
            <div class="container">
                    <?php get_template_part( 'template-parts/headers/parts/logo' ); ?>
                </div>
            </div>
        </div>
    <?php }
}

if (!function_exists('cenos_footer')) {
    function cenos_footer(){
        global $elementor_instance;
        $footer_post = cenos_get_option('footer_post');
        if (!empty($footer_post) && $footer_post != 'none'):?>
            <div class="footer-wrap">
                <?php
                if( $elementor_instance && cenos_is_built_with_elementor($footer_post)){
                    cenos_esc_data($elementor_instance->frontend->get_builder_content_for_display($footer_post));
                } else {
                    echo do_shortcode(get_post_field('post_content', $footer_post));
                }
                ?>
            </div>
        <?php else:
            get_template_part('template-parts/footers/footer_main',null);
        endif;
    }
}

if (!function_exists('cenos_main_footer_part')){
    function cenos_main_footer_part($postion = 'left') {
        $part_items = cenos_get_option('footer_main_'.$postion,[]);
        if (empty($part_items)){
            return;
        }
    	$part_html = '';
        ob_start();
        ?>
        <?php foreach ($part_items as $item):
            $footer_item_key = 'footer_'.$item;
            if ($item == 'html'){
                $footer_item_key = 'footer_'.$item.'_'.$postion;
            }
            $item_text = cenos_get_option($footer_item_key);
            if (!empty($item_text)):?>
                <div class="footer-<?php echo esc_attr($item);?>">
                    <?php echo do_shortcode($item_text);?>
                </div>
            <?php endif;
        endforeach;
        $part_html = trim(ob_get_clean());
        if (!empty($part_html)):
        ?>
        <div class="footer-items footer-<?php echo esc_attr($postion);?>">
            <?php cenos_esc_data($part_html);?>
        </div>
    <?php
        endif;
    }
}

if (!function_exists('cenos_update_menu_middle_object')) {
    function cenos_update_menu_middle_object($items, $args){
        if ($args->theme_location == 'primary'){
            $header_layout = cenos_get_option( 'header_layout' );
            if ('layout4' != $header_layout){
                return $items;
            }
            $root_items = array();
            foreach ($items as $item){
                if ($item->menu_item_parent == 0){
                    $root_items[] = $item;
                }
            }
            if (empty($root_items)){
                return $items;
            }
            $mid_key =  ceil (sizeof($root_items)/2);
            $mid_id = $root_items[$mid_key]->ID;
            $mid_class = $root_items[$mid_key]->classes;
            $mid_class[] = 'cenos-menu-item-middle';
            foreach ($items as $k => $item){
                if ($item->ID == $mid_id){
                    $items[$k]->classes = $mid_class;
                }
            }
        }
        return $items;
    }
}

if (!function_exists('cenos_insert_logo_to_menu_items')) {
    function cenos_insert_logo_to_menu_items($items, $args){
        if ($args->theme_location == 'primary'){
            $header_layout = cenos_get_option( 'header_layout' );
            if ('layout4' != $header_layout){
                return $items;
            }
            ob_start();
            get_template_part( 'template-parts/headers/parts/logo' );
            $logo_content = ob_get_clean();
            $wrap_class = $args->menu_class ? $args->menu_class : '';
            $menu_str = explode('menu-item-middle',$items);
            $insert_pos = strrpos($menu_str[0],'</li>');
            $logo_str = '</ul><div class="site-branding">'.$logo_content.'</div> <ul class="'.$wrap_class.'">';
            $str_insert = substr_replace($menu_str[0],$logo_str,$insert_pos+5,0) ;
            if (isset($menu_str[1])){
                $items = $str_insert.$menu_str[1];
            }
        }
        return $items;
    }
}

if (!function_exists('cenos_sidebar_layout')) {
    function cenos_sidebar_layout() {
        global $cenos_sidebar_layout;
        if (empty($cenos_sidebar_layout)){
            $cenos_sidebar_layout['layout'] = 'none';
            if (cenos_is_woocommerce_activated()) {
                if (is_product_taxonomy() || is_shop() || is_product()){
                    $cenos_sidebar_layout = false;
                }
            }
            if (is_page()) {
                $page_id = get_the_ID();
                //get from page option
                $page_layout_option = cenos_get_post_meta($page_id, 'page_layout_option',true);
                if ($page_layout_option){
                    $cenos_sidebar_layout['layout'] = cenos_get_post_meta($page_id, 'page_layout',true);
                    $cenos_sidebar_layout['sidebar_use'] = cenos_get_post_meta($page_id, 'page_used_sidebar',true);
                } else {
                    $cenos_sidebar_layout['layout'] = cenos_get_option('blog_sidebar_layout');
                }
                //page_used_sidebar
            } elseif (is_singular( 'post' )) {
                //single blog single setting
                $cenos_sidebar_layout['layout'] = cenos_get_option('blog_singular_layout');
            } else {
                //get blog layout setting
                $cenos_sidebar_layout['layout'] = cenos_get_option('blog_sidebar_layout');
            }
            $GLOBALS['cenos_sidebar_layout'] = $cenos_sidebar_layout;
        }
        return $cenos_sidebar_layout;
    }
}

if (!function_exists('is_cenos_sidebar_active')){
    function is_cenos_sidebar_active() {
        $sidebar = cenos_sidebar_layout();
        if (empty($sidebar)){
            return false;
        }
        if  ($sidebar['layout'] != 'none') {
            if (isset($sidebar['sidebar_use'])) {
                $sidebar_use = $sidebar['sidebar_use'];
            } else {
                $sidebar_use = 'sidebar-main';
            }
            if (is_active_sidebar($sidebar_use)) {
                return $sidebar_use;
            }
        }
        return false;
    }
}

if (!function_exists('cenos_sidebar')) {
    function cenos_sidebar (){
        $sidebar_use = is_cenos_sidebar_active();
        if ($sidebar_use) :
            $sidebar = cenos_sidebar_layout(); ?>
            <div class="cenos-sidebar-content col col-lg-3 cenos-<?php echo esc_attr($sidebar['layout']);?> ">
                <?php dynamic_sidebar($sidebar_use);?>
            </div>
        <?php endif;
    }
}

if (!function_exists('cenos_open_div_row')) {
    function cenos_open_div_row() {
        echo '<div class="row">';
    }
}
if (!function_exists('cenos_close_div_row')) {
    function cenos_close_div_row() {
        echo '</div>';
    }
}
if (!function_exists('cenos_list_blog_categories')) {
    function cenos_list_blog_categories($args = []){
        if (cenos_get_option('blog_list_action_bar')) {
            $defaults = array(
                'child_of'            => 0,
                'depth'               => 0,
                'current_category'    => 0,
                'hierarchical'        => false,
                'separator'           => '',
                'use_desc_for_title'  => 0,
                'number'              => 5,
                'show_option_all'     => esc_html__('All','cenos'),
                'title_li'            => false
            );
            $parsed_args = wp_parse_args( $args, $defaults );
            ob_start();
            wp_list_categories($parsed_args);
            $list_category = ob_get_clean();
            printf('<ul class="blog_category_list%s">%s</ul>',
                (strpos($list_category, 'current-cat')!== false)? ' has-current-cat':'',
                $list_category
            );
        }
    }
}
if (!function_exists('cenos_blog_seach_form')){
    function cenos_blog_seach_form($args = []) {
        if (cenos_get_option('blog_list_action_bar')) {
            $defaults = array(
                'echo' => true,
                'aria_label' => '',
            );
            $parsed_args = wp_parse_args($args, $defaults);
            get_search_form($parsed_args);
        }
    }
}
if (!function_exists('cenos_widget_categories_args')) {
    function cenos_widget_categories_args($args) {
        $args['show_option_all'] = esc_html__('All','cenos');
        return $args;
    }
}
if (!function_exists('cenos_comment_form_before_fields')){
    function cenos_comment_form_before_fields(){
        echo '<div class="cenos_comment_fields_group">';
    }
}

if (!function_exists('cenos_comment_form_after_fields')) {
    function cenos_comment_form_after_fields() {
        //close div cenos_comment_fields_group
        echo '</div>';
    }
}

if (!function_exists('cenos_single_blog_related_responsive')) {
    function cenos_single_blog_related_responsive() {
        if( cenos_get_option('blog_singular_layout') == 'none'){
            return [
                'lg' => 2,
                'xl' => 3,//>=1200
            ];
        }else{
            return [
                'lg' => 2,
                'xl' => 3,//>=1200
            ];
        }
    }
}
if (!function_exists('cenos_vertical_header_wrap')){
    function cenos_vertical_header_wrap(){
        if (cenos_get_option('header_vertical',false)):
        ?>
        <div class="main-content-wrap">
        <?php
        endif;
    }
}
if (!function_exists('cenos_vertical_header_close_wrap')){
    function cenos_vertical_header_close_wrap(){
        if (cenos_get_option('header_vertical',false)):
        ?>
        </div><!-- close main-content-wrap-->
        <?php
        endif;
    }
}

if (!function_exists('cenos_scroll_to_top')){
    function cenos_scroll_to_top(){
        if (!cenos_on_device() && cenos_get_option('scroll_to_top')):?>
            <a href="#" class="scroll_to_top percent_circle" title="<?php esc_attr_e('Go to top','cenos');?>">
                <svg class="backtotop-round" viewbox="0 0 100 100" width="50" height="50">
                    <circle cx="50" cy="50" r="40" />
                </svg>
                <?php cenos_svg_icon('arrow-triangle-up',16,16,'up-icon');?>
            </a>
        <?php
        endif;
    }
}

if (!function_exists('cenos_mobile_direction_bar')) {
    function cenos_mobile_direction_bar(){
        if (!is_product() && cenos_on_device() && cenos_get_option('show_direction_bar')){
            $direction_component = cenos_get_option('direction_component');
            $is_shop = is_shop() || is_product_taxonomy();
            if ($direction_component && !empty($direction_component)):?>
                <div class="footer-direction-bar">
                    <?php if ($is_shop):?>
                    <div class="direction-element filter-box">
                        <a href="#." class="js-offcanvas-trigger" data-offcanvas-trigger="filter-canvas">
                            <span><?php cenos_svg_icon('filter');?></span>
                            <?php esc_html_e('Filter','cenos');?>
                        </a>
                    </div>
                    <?php endif;?>
                    <?php foreach ($direction_component as $part){
                        if ($is_shop && $part == 'account' ){
                            continue;
                        }
                        get_template_part('template-parts/headers/parts/direction/'.$part,null,['class_tmp' => '']);
                    }?>
                </div>
            <?php endif;
        }
    }
}

if (!function_exists('cenos_wvs_admin_enqueue_scripts')) {
    function cenos_wvs_admin_enqueue_scripts(){
        $screen = get_current_screen();
        if (!empty($screen) && ($screen->id == 'product_page_product_attributes' || $screen->id == 'edit-product_cat' || $screen->id == 'product' || strpos($screen->id,'edit-pa') !== false )) {
            return false;
        }
        if (isset($_GET['page']) && $_GET['page'] == 'woo-variation-swatches-settings'){
            return false;
        }
        return true;
    }
}

if (!function_exists('cenos_popup')) {
    function cenos_popup() {
        if (cenos_on_device() || (! is_front_page() && ! is_customize_preview())) {
            return;
        }
        if (! cenos_get_option( 'popup_enable' ) && ! is_customize_preview() ) {
            return;
        }

        if ( isset( $_COOKIE['cenos_popup'] ) && ! is_customize_preview() ) {
            return;
        }

        get_template_part( 'template-parts/popup/popup' );
    }
}
