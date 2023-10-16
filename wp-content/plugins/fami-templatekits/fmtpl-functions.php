<?php
use Elementor\Icons_Manager;
if (!function_exists('fmtpl_is_elementor_activated')) {
    function fmtpl_is_elementor_activated() {
        global $fmtpl_elementor_activated;
        if (empty($fmtpl_elementor_activated)) {
            if ( did_action( 'elementor/loaded' ) ) {
                $fmtpl_elementor_activated = true;
            } else {
                $fmtpl_elementor_activated = false;
            }
            $GLOBALS['fmtpl_elementor_activated'] = $fmtpl_elementor_activated;
        }
        return $fmtpl_elementor_activated;
    }
}

if (!function_exists('fmtpl_header_enabled')) {
    function fmtpl_header_enabled()
    {
        $header_id = Fami_TemplateKits::get_settings('type_header', '');
        $status = false;

        if ('' !== $header_id) {
            $status = true;
        }
        return $status;
    }
}

if (!function_exists('fmtpl_footer_enabled')) {
    function fmtpl_footer_enabled()
    {
        $footer_id = Fami_TemplateKits::get_settings('type_footer', '');
        $status = false;

        if ('' !== $footer_id) {
            $status = true;
        }

        return $status;
    }
}

if (!function_exists('fmtpl_get_header_id')) {
    function fmtpl_get_header_id()
    {
        $header_id = Fami_TemplateKits::get_settings('type_header', '');

        if ('' === $header_id) {
            $header_id = false;
        }
        return apply_filters( 'wpml_object_id', $header_id, 'fmtpl_blocks', TRUE  );
    }
}

if (!function_exists('fmtpl_get_footer_id')) {
    function fmtpl_get_footer_id()
    {
        $footer_id = Fami_TemplateKits::get_settings('type_footer', '');
        if ('' === $footer_id) {
            $footer_id = false;
        }

        return apply_filters( 'wpml_object_id', $footer_id, 'fmtpl_blocks', TRUE  );
    }
}

if (!function_exists('fmtpl_render_header')) {
    function fmtpl_render_header()
    { ?>
        <header id="masthead" itemscope="itemscope" itemtype="https://schema.org/WPHeader">
            <?php Fami_TemplateKits::get_header_content(); ?>
        </header>

        <?php

    }
}
if (!function_exists('fmtpl_render_footer')) {
    function fmtpl_render_footer()
    {
        ?>
        <footer itemtype="https://schema.org/WPFooter" itemscope="itemscope" class="site-footer">
            <?php Fami_TemplateKits::get_footer_content(); ?>
        </footer>
        <?php
    }
}
if (!function_exists('fmtpl_get_post_types')) {
    function fmtpl_get_post_types ( $args = array(), $diff_key = array() ) {
        $default = [
            'public' => true,
            'show_in_nav_menus' => true
        ];
        $args = array_merge( $default, $args );
        $post_types = get_post_types( $args , 'objects' );
        $post_types = wp_list_pluck( $post_types, 'label', 'name' );
        if( !empty( $diff_key ) ){
            $post_types = array_diff_key( $post_types, $diff_key );
        }
        return $post_types;
    }
}

if ( ! function_exists( 'fmtpl_is_woocommerce_activated' ) ) {
    /**
     * Returns true if WooCommerce plugin is activated
     *
     * @return bool
     */
    function fmtpl_is_woocommerce_activated() {
        return class_exists( 'WooCommerce' );
    }
}

if (!function_exists('fmtpl_getProducts')) {
    function fmtpl_getProducts( $atts, $args = array(), $ignore_sticky_posts = 1 ){
        if (!fmtpl_is_woocommerce_activated()){
            return false;
        }
        //extract( $atts );
        //$target            = isset( $target ) ? $target : 'recent-product';
        $meta_query        = WC()->query->get_meta_query();
        $tax_query         = WC()->query->get_tax_query();
        $args['post_type'] = 'product';
        if (isset( $atts['category_id'] ) && $atts['category_id'] ) {
            $tax_query[] = array(
                'taxonomy' => 'product_cat',
                'terms'    => is_array( $atts['category_id'] ) ? array_map( 'sanitize_title', $atts['category_id'] ) : array_map( 'sanitize_title', explode( ',', $atts['category_id'] ) ),
                'field'    => 'id',
                'operator' => 'IN',
            );
        }
        $args['post_status']         = 'publish';
        $args['ignore_sticky_posts'] = $ignore_sticky_posts;
        $args['suppress_filter']     = true;
        if ( isset( $atts['max_items'] ) && $atts['max_items'] ) {
            $args['posts_per_page'] = $atts['max_items'];
        }
        $ordering_args = WC()->query->get_catalog_ordering_args();

        $orderby       = isset( $atts['orderby'] ) ? $atts['orderby'] : $ordering_args['orderby'];
        $order         = isset( $atts['order'] ) ? $atts['order'] : $ordering_args['order'];
        $meta_key      = isset( $atts['meta_key'] ) ? $atts['meta_key'] : $ordering_args['meta_key'];
        switch ( $atts['source'] ):
            case 'bestselling' :
                $args['meta_key'] = 'total_sales';
                $args['orderby']  = 'meta_value_num';
                $args['order']    = $order;
                break;
            case 'top_rated' :
                $args['meta_key'] = '_wc_average_rating';
                $args['orderby']  = 'meta_value_num';
                $args['order']    = $order;
                break;
            case 'manual' :
                if ( !empty( $atts['product_ids'] ) ) {
                    $args['posts_per_page'] = -1;
                    $args['post__in'] = array_map( 'trim', $atts['product_ids']);
                    $args['orderby']  = 'post__in';
                }
               /* if ( !empty( $skus ) ) {
                    $meta_query[] = array(
                        'key'     => '_sku',
                        'value'   => array_map( 'trim', explode( ',', $skus ) ),
                        'compare' => 'IN',
                    );
                }*/
                break;
            case 'featured' :
                $tax_query[] = array(
                    'taxonomy' => 'product_visibility',
                    'field'    => 'name',
                    'terms'    => 'featured',
                    'operator' => 'IN',
                );
                break;
            case 'product_attribute' :
                $tax_query[] = array(
                    array(
                        'taxonomy' => strstr( $atts['attribute'], 'pa_' ) ? sanitize_title( $atts['attribute'] ) : 'pa_' . sanitize_title( $atts['attribute'] ),
                        'terms'    => $atts['filter'] ? array_map( 'sanitize_title', explode( ',', $atts['filter'] ) ) : array(),
                        'field'    => 'slug',
                        'operator' => 'IN',
                    ),
                );
                break;
            case 'latest' :
                $newness =  isset($args['newness']) ? $args['newness']: 30;
                $args['date_query'] = array(
                    array(
                        'after'     => '' . $newness . ' days ago',
                        'inclusive' => true,
                    ),
                );
                if ( $orderby == '_sale_price' ) {
                    $orderby = 'date';
                    $order   = 'DESC';
                }
                $args['orderby'] = $orderby;
                $args['order']   = $order;
                break;
            case 'on_sale' :
                $product_ids_on_sale = wc_get_product_ids_on_sale();
                $args['post__in']    = array_merge( array( 0 ), $product_ids_on_sale );
                if ( $orderby == '_sale_price' ) {
                    $orderby = 'date';
                    $order   = 'DESC';
                }
                $args['orderby'] = $orderby;
                $args['order']   = $order;
                break;
            default :
                $args['orderby'] = $orderby;
                $args['order']   = $order;
                if ( isset( $ordering_args['meta_key'] ) ) {
                    $args['meta_key'] = $ordering_args['meta_key'];
                }
                WC()->query->remove_ordering_args();
                break;
        endswitch;
        $args['meta_query'] = $meta_query;
        $args['tax_query']  = $tax_query;
        if ( isset( $atts['page'] ) && $atts['page'] > 1) {
            $args['paged'] = absint( $atts['page'] );
        }
        $fmtpl_products = new WP_Query( apply_filters( 'woocommerce_shortcode_products_query', $args, $atts ,'products') );
        wp_reset_query();
        return $fmtpl_products;
    }
}
if (!function_exists('fmtpl_getProducts_Html')) {
    function fmtpl_getProducts_Html($atts, $query,$only_items = false) {
        $product_html = '';
        if ($query->have_posts()) {
            $item_style = isset($atts['item_style']) ? $atts['item_style'] : '';
            $columns = isset($atts['columns']) && !empty($atts['columns']) ? $atts['columns']: 4;
            $list_style = isset($atts['list_style']) ? $atts['list_style']:'grid';
            $show_arrows = (isset($atts['show_arrows']) && $atts['show_arrows'] == 'yes') ? true: false;
            $show_arrows_text = (isset($atts['show_arrows_text']) && $atts['show_arrows_text'] == 'yes') ? true: false;
            $show_rating = (isset($atts['show_rating']) && $atts['show_rating'] == 'yes') ? true: false;
            //$settings['show_rating']
            if ($list_style == 'carousel' && $show_arrows && (isset($atts['prev_icon']) || isset($atts['next_icon']))){
                if (isset($atts['prev_icon'])){
                ob_start();
                Icons_Manager::render_icon( $atts[ 'prev_icon' ]);
                $atts['prev_icon_str'] = ob_get_clean();
                }
                if (isset($atts['next_icon'])) {
                ob_start();
                Icons_Manager::render_icon( $atts[ 'next_icon' ]);
                $atts['next_icon_str'] = ob_get_clean();
                }
            }
            $product_html = apply_filters('fmtpl-products-sc-html','',$atts,$query);
            if (empty($product_html)) {
                wc_setup_loop(
                    array(
                        'columns'      => $columns,
                        'is_fmtpl_widget' => true,
                        'product_item_style' => $item_style,
                        'product_item_rating' => $show_rating
                    )
                );
                if (isset($atts['product_img_size']) && !empty($atts['product_img_size'])){
                    wc_set_loop_prop('product_loop_image_size',$atts['product_img_size']);
                }
                if (isset($atts['hover_img']) && !empty($atts['hover_img'])){
                    wc_set_loop_prop('item_hover_image',$atts['hover_img']);
                }
                if (isset($atts['show_category']) && $atts['show_category'] == 'yes'){
                    wc_set_loop_prop('show_category',true);
                }
                if ($only_items):
                    ob_start();
                    while ($query->have_posts()) {
                        $query->the_post();
                        wc_get_template_part('content', 'product');
                    }
                    $product_html .= ob_get_clean();
                    wp_reset_postdata();
                elseif ($list_style == 'grid') :
                    $products_class = ['row'];
                    $products_class[] = 'columns-' . $columns;
                    $products_class[] = isset($atts['columns_tablet']) && !empty($atts['columns_tablet']) ? 'medium-columns-' . $atts['columns_tablet'] : 'medium-columns-3';
                    $products_class[] = isset($atts['columns_mobile']) && !empty($atts['columns_mobile']) ? 'small-columns-' . $atts['columns_mobile'] : 'small-columns-2';
                    $product_html = '<ul class="products ' . implode(' ', $products_class) . '">';
                    ob_start();
                    while ($query->have_posts()) {
                        $query->the_post();
                        wc_get_template_part('content', 'product');
                    }
                    $product_html .= ob_get_clean() . '</ul>';
                    wp_reset_postdata();
                else:
                    $class_name = isset($atts['widget_name'])? $atts['widget_name']:'';
                    ob_start(); ?>
                    <ul class="swiper-wrapper products">
                        <?php
                        //product_item_class
                        wc_set_loop_prop('product_item_class','swiper-slide');
                        while ($query->have_posts()) :
                            $query->the_post();
                            wc_get_template_part('content', 'product');
                            ?>
                        <?php endwhile;
                        wp_reset_postdata();
                        ?>
                    </ul>
                    <?php
                    $slides_count = $query->post_count;
                    if ( 1 < $slides_count ) : ?>
                        <?php
                        $pagi_class = 'swiper-pagination';
                        if (empty($settings['pagination'] )) {
                            $pagi_class .= ' disabled';
                        }
                        ?>
                        <div class="<?php echo $pagi_class;?>"></div>
                        <?php if ( $show_arrows ) :
                            $sw_btn_class ='';
                            if (isset($atts['show_arrows_mobile']) && $atts['show_arrows_mobile'] == 'no'){
                                $sw_btn_class .= ' hidden_on_mobile';
                            }
                            ?>
                            <div class="elementor-swiper-button elementor-swiper-button-prev<?php echo $sw_btn_class;?>">
                                <?php if (isset($atts['prev_icon_str'])) :
                                    echo $atts['prev_icon_str'];
                                else :?>
                                    <i class="eicon-chevron-left" aria-hidden="true"></i>
                                <?php endif;?>
                                <span><?php _e( 'Previous', 'fami-templatekits' ); ?></span>
                            </div>
                            <div class="elementor-swiper-button elementor-swiper-button-next<?php echo $sw_btn_class;?>">
                                <span><?php _e( 'Next', 'fami-templatekits' ); ?></span>
                                <?php if (isset($atts['next_icon_str'])) :
                                    echo $atts['next_icon_str'];
                                else :?>
                                    <i class="eicon-chevron-right" aria-hidden="true"></i>
                                <?php endif;?>
                            </div>
                        <?php endif;
                    endif;
                    $product_html = ob_get_clean();
                endif;
                wc_reset_loop();
            }
        }
        return $product_html;
    }
}
if (!function_exists('fmtpl_get_block_post')){
    function fmtpl_get_block_post($type = 'custom') {
        $args = array(
            'numberposts' => -1,
            'post_type'   => 'fmtpl_blocks',
            'meta_key'         => 'fmtpl_template_type',
            'meta_value'       => $type,
        );
        //fmtpl_template_type
        return get_posts( $args );
    }
}
if (!function_exists('fmtpl_get_current_theme')) {
    function fmtpl_get_current_theme()
    {
        $theme = wp_get_theme();
        if (!empty($theme['Template'])) {
            $theme = wp_get_theme($theme['Template']);
        }
        return $theme;
    }
}

if (!function_exists('fmtpl_get_products_elementor_settings')){
    function fmtpl_get_products_elementor_settings($settings){
        $targets = ['category_id','max_items','orderby','order','meta_key','source','product_ids','attribute','filter','page',
            'item_style','columns','show_rating','product_img_size','hover_img','show_category'];
        $results = [];
        foreach ($targets as $key){
            if (isset($settings[$key])) {
                $results[$key] = $settings[$key];
            }
        }
        return $results;
    }
}

if (!function_exists('fmtpl_ajax_get_products_html')){
    function fmtpl_ajax_get_products_html(){
        $nonce = $_POST['nonce'];
        if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) ){
            die();
        }
        $settings = false;
        if (isset($_POST['settings']) && $_POST['settings']){
            $settings = $_POST['settings'];
        }
        if (empty($settings)){
            die();
        }
        $html = '';
        $fmtpl_query = fmtpl_getProducts($settings);
        $result = [];
        $result['max_num_pages'] = $fmtpl_query->max_num_pages;
        $result['html'] = fmtpl_getProducts_Html($settings,$fmtpl_query,true);
        wp_send_json_success($result);
    }
}