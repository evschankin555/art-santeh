<?php
if (!function_exists('fmtpl_products_grid_item_style')) {
    function fmtpl_products_grid_item_style($html,$settings,$fmtpl_query) {
//get products

        $mobile_item_style = false;
        if (cenos_on_device() && cenos_get_option('mobile_product_items')){
            $mobile_item_style = true;
        }
        $item_style = isset($settings['item_style']) ? $settings['item_style'] : '';
        $list_style = isset($settings['list_style']) ? $settings['list_style'] : 'grid';
        $columns = isset($settings['columns']) && !empty($settings['columns']) ? $settings['columns']: 4;
        $show_rating = isset($settings['show_rating']) && $settings['show_rating']=='yes' ? true: false;
        //show_rating
        if ($fmtpl_query->have_posts()) {
            $defaul_woo = false;
            if (!$mobile_item_style){
                $shop_list_style = cenos_get_option('shop_list_style');
                $product_item_style = cenos_get_option('product_item_style');
                if ($shop_list_style == 'grid' && $product_item_style == 'default' && $item_style !='default'){
                    $defaul_woo = true;
                    remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
                    remove_action('woocommerce_after_shop_loop_item_title','woocommerce_template_loop_price');
                    remove_action('woocommerce_after_shop_loop_item','woocommerce_template_loop_add_to_cart');
                    //remove YITH_Woocompare hook
                    if (class_exists('YITH_Woocompare')) {
                        global $yith_woocompare;
                        $is_ajax = ( defined( 'DOING_AJAX' ) && DOING_AJAX );
                        if( $yith_woocompare->is_frontend() || $is_ajax ) {
                            if( $is_ajax ){
                                if( !class_exists( 'YITH_Woocompare_Frontend' ) ){
                                    $file_name = YITH_WOOCOMPARE_DIR . 'includes/class.yith-woocompare-frontend.php';
                                    if( file_exists( $file_name ) ){
                                        require_once( $file_name );
                                    }
                                }
                                $yith_woocompare->obj = new YITH_Woocompare_Frontend();
                            }
                            remove_action( 'woocommerce_after_shop_loop_item', array( $yith_woocompare->obj, 'add_compare_link' ), 20 );
                        }
                    }
                    //remove YITH_WCQV hooks
                    if (class_exists('YITH_WCQV_Frontend')) {
                        remove_action('woocommerce_after_shop_loop_item',[YITH_WCQV_Frontend::get_instance(),'yith_add_quick_view_button'], 15);
                    }
                    if (defined('TINVWL_FVERSION') || defined('TINVWL_VERSION')){
                        remove_action( 'woocommerce_after_shop_loop_item', 'tinvwl_view_addto_htmlloop', 9 );
                        remove_action( 'woocommerce_before_shop_loop_item', 'tinvwl_view_addto_htmlloop', 9 );
                        remove_action( 'woocommerce_after_shop_loop_item', 'tinvwl_view_addto_htmlloop' );
                    }
                }
                wc_setup_loop(
                    array(
                        'columns'      => $columns,
                        'is_fmtpl_widget' => true,
                        'product_item_style' => $item_style,
                        'product_item_rating' => $show_rating
                    )
                );
                if (isset($settings['hover_img']) && !empty($settings['hover_img'])){
                    wc_set_loop_prop('item_hover_image',$settings['hover_img']);
                }
                if (isset($settings['show_category'])){
                    if ($settings['show_category'] == 'yes'){
                        wc_set_loop_prop('show_category',true);
                    } else {
                        wc_set_loop_prop('show_category',false);
                    }
                }
            }

            $close_tag = '';
            if ($list_style == 'grid') {
                if ($mobile_item_style){
                    $products_class = [cenos_products_loop_classes(2)];
                } else {
                    $products_class = ['products-grid-style','row'];
                    $products_class[] = 'product-item-'.$item_style;
                    $products_class[] = 'columns-'.$columns;
                    $products_class[] = isset($settings['columns_tablet']) && !empty($settings['columns_tablet']) ? 'medium-columns-'.$settings['columns_tablet']:'medium-columns-3';
                    $products_class[] = isset($settings['columns_mobile']) && !empty($settings['columns_mobile']) ? 'small-columns-'.$settings['columns_mobile']:'small-columns-2';
                }
                $html ='<div class="woocommerce fmtpl_products_grid"><ul class="products '.implode(' ',$products_class).'">';
                $close_tag = '</ul></div>';
            } elseif ($list_style == 'carousel') {
                $slides_count = $fmtpl_query->post_count;
                $layout = isset($settings['layout']) ? $settings['layout'] : 'default';
                $html = '';
                $navi_html = '';
                $pagi_class = 'swiper-pagination';
                if ($layout == 'layout3' || empty($settings['pagination'] )) {
                    $pagi_class .= ' disabled';
                }
                $pagi_html = '<div class="'.$pagi_class.'"></div>';
                if ( 1 < $slides_count && $settings['show_arrows'] ) {
                    $sw_btn_class ='';
                    if (isset($settings['show_arrows_mobile']) && $settings['show_arrows_mobile'] == 'no'){
                        $sw_btn_class .= ' hidden_on_mobile';
                    }
                    $navi_html .= '<div class="fmtpl-carousel-navigation-wrapper">';
                    $navi_html .= sprintf('<div class="elementor-swiper-button elementor-swiper-button-prev%s">%s<span>%s</span></div>',
                        $sw_btn_class,
                        (isset($settings['prev_icon_str']) && $settings['prev_icon_str'])? $settings['prev_icon_str']:'<i class="eicon-chevron-left" aria-hidden="true"></i>',
                       esc_html__( 'Previous', 'cenos' )
                    );
                    $navi_html .= sprintf('<div class="elementor-swiper-button elementor-swiper-button-next%s"><span>%s</span>%s</div>',
                        $sw_btn_class,
                       esc_html__( 'Next', 'cenos' ),
                        (isset($settings['next_icon_str']) && $settings['next_icon_str'])? $settings['next_icon_str']:'<i class="eicon-chevron-right" aria-hidden="true"></i>'
                    );
                    $navi_html .="</div>";
                }

                if ($layout == 'layout3' || $layout == 'layout4'){
                    $content_html = '<div class="fmtpl-carousel-box-title-wrapper"><div class="fmtpl-carousel-box-heading">';
                    if (isset($settings['title_text']) && !empty($settings['title_text'])) {
                        $highlight_title = (isset($settings['highlight_title']) && !empty($settings['highlight_title']))? $settings['highlight_title']:'';
                        $title = str_replace('%highlight%','<span class="highlight">'.$highlight_title.'</span>',$settings['title_text']);
                        $content_html .= '<h3 class="fmtpl-carousel-box-title">'.$title.'</h3>';
                    }
                    if (isset($settings['description']) && !empty($settings['description'])){
                        $content_html .= '<div class="fmtpl-carousel-box-desc">'.$settings['description'].'</div>';
                    }
                    $content_html .='</div>';

                    if (!empty($pagi_html)){
                        $content_html .= $pagi_html;
                    }
                    if (!empty($navi_html)){
                        $content_html .= $navi_html;
                    }
                    $content_html .='</div>';
                    $html .= $content_html;
                }
                wc_set_loop_prop('product_item_class','swiper-slide');
                if ($mobile_item_style){
                    $product_item_class = 'product-item-'.cenos_get_option('mobile_product_items_style');
                } else {
                    $product_item_class = 'product-item-'.$item_style;
                }
                $html .= '<ul class="swiper-wrapper products products-grid-style '.$product_item_class.'">';
                $close_tag = '</ul>';
                if ($layout != 'layout3' && $layout != 'layout4' && !empty($pagi_html)) {
                    $close_tag .= $pagi_html;
                }
                if ($layout != 'layout3' && $layout != 'layout4' && !empty($navi_html)){
                    $close_tag .= $navi_html;
                }
            }
            ob_start();
            while ($fmtpl_query->have_posts()) :
                $fmtpl_query->the_post();
                wc_get_template_part( 'content', 'product' );
            endwhile;
            $product_html = ob_get_clean();
            wc_reset_loop();
            if ($defaul_woo){
                add_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
                add_action('woocommerce_after_shop_loop_item_title','woocommerce_template_loop_price');
                add_action('woocommerce_after_shop_loop_item','woocommerce_template_loop_add_to_cart');
                //remove YITH_Woocompare hook
                if (class_exists('YITH_Woocompare')) {
                    global $yith_woocompare;
                    $is_ajax = ( defined( 'DOING_AJAX' ) && DOING_AJAX );
                    if( $yith_woocompare->is_frontend() || $is_ajax ) {
                        if( $is_ajax ){
                            if( !class_exists( 'YITH_Woocompare_Frontend' ) ){
                                $file_name = YITH_WOOCOMPARE_DIR . 'includes/class.yith-woocompare-frontend.php';
                                if( file_exists( $file_name ) ){
                                    require_once( $file_name );
                                }
                            }
                            $yith_woocompare->obj = new YITH_Woocompare_Frontend();
                        }
                        add_action( 'woocommerce_after_shop_loop_item', array( $yith_woocompare->obj, 'add_compare_link' ), 20 );
                    }
                }
                //remove YITH_WCQV hooks
                if (class_exists('YITH_WCQV_Frontend')) {
                    add_action('woocommerce_after_shop_loop_item',[YITH_WCQV_Frontend::get_instance(),'yith_add_quick_view_button'], 15);
                }
                if (defined('TINVWL_FVERSION') || defined('TINVWL_VERSION')){
                    if ( tinv_get_option( 'add_to_wishlist_catalog', 'show_in_loop' ) ) {
                        switch ( tinv_get_option( 'add_to_wishlist_catalog', 'position' ) ) {
                            case 'before':
                                add_action( 'woocommerce_after_shop_loop_item', 'tinvwl_view_addto_htmlloop', 9 );
                                break;
                            case 'above_thumb':
                                add_action( 'woocommerce_before_shop_loop_item', 'tinvwl_view_addto_htmlloop', 9 );
                                break;
                            case 'shortcode':
                                break;
                            case 'after':
                            default: // Compatibility with previous versions.
                                add_action( 'woocommerce_after_shop_loop_item', 'tinvwl_view_addto_htmlloop' );
                                break;
                        }
                    }
                }
            }
            wp_reset_postdata();
            $html .= $product_html.$close_tag;
        }
        return $html;
    }
}
if (!function_exists('fmtpl_carousel_products_widget_html')) {
    function fmtpl_carousel_products_widget_html($html, $settings ,$fmtpl_query) {
        $layout = isset($settings['layout']) ? $settings['layout'] : 'default';
        if ($layout == 'layout3' || $layout == 'layout4'){
            $html = '<div class="fmtpl-elementor-widget fmtpl-products fmtpl-products-layout-'.$layout.' carousel woocommerce">';
            $button_html = '';
            if ((isset($settings['button_text']) && !empty($settings['button_text'])) || (isset($settings['button_icon']) && ($settings['button_icon']['value']))) {
                $icon_str = isset($settings['button_icon_str'])?$settings['button_icon_str']:'';
                $btn_class = isset($settings['btn_icon_position'])? $settings['btn_icon_position'] : 'left';
                $button_html .= '<div class="fmtpl-products-button-wrapper"><a class="fmtpl-button-default fmtpl-products-button '.$btn_class.'" ' . $settings['link_attr_str'] . '>'.$icon_str.'<span class="fmtpl-btn-text">'.$settings['button_text'].'</span></a></div>';
            }

            if (isset($settings['btn_position']) && $settings['btn_position'] == 'before' && !empty($button_html)) {
                $html .= $button_html;
            }
            $settings['list_style'] = 'carousel';
            $html .= '<div class="fmtpl-elementor-swiper"><div class="fmtpl-elementor-main-swiper swiper-container">';
            $html .= fmtpl_getProducts_Html($settings,$fmtpl_query);
            if (isset($settings['btn_position']) && $settings['btn_position'] == 'after' && !empty($button_html)) {
                $html .= $button_html;
            }
            $html .='</div></div></div>';

        } else {
            $html = '<div class="fmtpl-elementor-widget fmtpl-products fmtpl-products-layout-'.$layout.' carousel woocommerce">';
            $content_html = '';
            if (isset($settings['title_text']) && !empty($settings['title_text'])) {
                $highlight_title = (isset($settings['highlight_title']) && !empty($settings['highlight_title']))? $settings['highlight_title']:'';
                $title = str_replace('%highlight%','<span class="highlight">'.$highlight_title.'</span>',$settings['title_text']);
                $content_html .= '<h3 class="fmtpl-carousel-box-title">'.$title.'</h3>';
            }
            if (isset($settings['show_divider']) && $settings['show_divider'] == 'yes'){
                $content_html .= '<div class="fmtpl-divider">&nbsp;</div>';
            }
            if (isset($settings['description']) && !empty($settings['description'])){
                $content_html .= '<div class="fmtpl-carousel-box-desc">'.$settings['description'].'</div>';
            }
            $button_html = '';
            if ((isset($settings['button_text']) && !empty($settings['button_text'])) || (isset($settings['button_icon']) && ($settings['button_icon']['value']))) {
                $icon_str = isset($settings['button_icon_str'])?$settings['button_icon_str']:'';
                $btn_class = isset($settings['btn_icon_position'])? $settings['btn_icon_position'] : 'left';
                $button_html .= '<div class="fmtpl-products-button-wrapper"><a class="fmtpl-button-default fmtpl-products-button '.$btn_class.'" ' . $settings['link_attr_str'] . '>'.$icon_str.'<span class="fmtpl-btn-text">'.$settings['button_text'].'</span></a></div>';
            }
            if ($content_html != '') {
                $html .= '<div class="fmtpl-carousel-box-heading">'.$content_html.'</div>';
            }
            if (isset($settings['btn_position']) && $settings['btn_position'] == 'before' && !empty($button_html)) {
                $html .= $button_html;
            }
            //get products
            $settings['list_style'] = 'carousel';
            $html .= '<div class="fmtpl-elementor-swiper"><div class="fmtpl-elementor-main-swiper swiper-container">';
            $html .= fmtpl_getProducts_Html($settings,$fmtpl_query);
            if (isset($settings['btn_position']) && $settings['btn_position'] == 'after' && !empty($button_html)) {
                $html .= $button_html;
            }
            $html .='</div></div></div>';
        }
        return $html;
    }
}
if (!function_exists('fmtpl_carousel_posts_widget_html')) {
    function fmtpl_carousel_posts_widget_html($html,$settings,$posts) {
        $post_count = count($posts);
        $layout = isset($settings['layout']) ? $settings['layout'] : 'default';
        $navi_html = '';
        $pagi_class = 'swiper-pagination';
        if ($layout == 'cenos_carousel_posts_02' || empty($settings['pagination'] )) {
            $pagi_class .= ' disabled';
        }
        $pagi_html = '<div class="'.$pagi_class.'"></div>';
        if ( 1 < $post_count && $settings['show_arrows'] ) {
            $sw_btn_class ='';
            if (isset($settings['show_arrows_mobile']) && $settings['show_arrows_mobile'] == 'no'){
                $sw_btn_class .= ' hidden_on_mobile';
            }
            $navi_html .= '<div class="fmtpl-carousel-navigation-wrapper">';
            $navi_html .= sprintf('<div class="elementor-swiper-button elementor-swiper-button-prev%s">%s<span>%s</span></div>',
                $sw_btn_class,
                (isset($settings['prev_icon_str']) && $settings['prev_icon_str'])? $settings['prev_icon_str']:'<i class="eicon-chevron-left" aria-hidden="true"></i>',
               esc_html__( 'Previous', 'cenos' )
            );
            $navi_html .= sprintf('<div class="elementor-swiper-button elementor-swiper-button-next%s"><span>%s</span>%s</div>',
                $sw_btn_class,
               esc_html__( 'Next', 'cenos' ),
                (isset($settings['next_icon_str']) && $settings['next_icon_str'])? $settings['next_icon_str']:'<i class="eicon-chevron-right" aria-hidden="true"></i>'
            );
            $navi_html .="</div>";
        }
        $html = '<div class="fmtpl-elementor-widget fmtpl-post-list fmtpl-post-layout-'.$layout.' carousel">';
        $content_html = '<div class="fmtpl-carousel-box-title-wrapper"><div class="fmtpl-carousel-box-heading">';
        if (isset($settings['title_text']) && !empty($settings['title_text'])) {
            $highlight_title = (isset($settings['highlight_title']) && !empty($settings['highlight_title']))? $settings['highlight_title']:'';
            $title = str_replace('%highlight%','<span class="highlight">'.$highlight_title.'</span>',$settings['title_text']);
            $content_html .= '<h3 class="fmtpl-carousel-box-title">'.$title.'</h3>';
        }
        if (isset($settings['description']) && !empty($settings['description'])){
            $content_html .= '<div class="fmtpl-carousel-box-desc">'.$settings['description'].'</div>';
        }
        $content_html .='</div>';
        if ($layout == 'cenos_carousel_posts_02' && !empty($pagi_html)){
            $content_html .= $pagi_html;
        }
        if (!empty($navi_html)){
            $content_html .= $navi_html;
        }
        $content_html .='</div>';
        $html .= '<div class="fmtpl-elementor-swiper"><div class="fmtpl-elementor-main-swiper swiper-container">';
        if ($content_html != '') {
            $html .= $content_html;
        }

        $html .= '<div class="swiper-wrapper">';
        ob_start();
        fmtpl_carousel_post_slide_item_html($posts,$settings);
        $slide_html = ob_get_clean();
        $html .= $slide_html;

        $html .= '</div><!-- close swiper-wrapper-->';
        if ($layout != 'cenos_carousel_posts_02' && !empty($pagi_html)) {
            $html .= $pagi_html;
        }

        $html .='</div><!--fmtpl-elementor-swiper--></div><!--fmtpl-elementor-main-swiper--></div><!-- close fmtpl-post-list-->';
        return $html;
    }
}
if (!function_exists('fmtpl_carousel_post_slide_item_html')) {
    function fmtpl_carousel_post_slide_item_html(array $posts, array $settings){
        $layout = isset($settings['layout']) ? $settings['layout'] : 'default';
        $customize_title = isset($settings['customize_title'])? $settings['customize_title']:[];
        $read_more_icon_str = isset($settings['read_more_icon_str'])? $settings['read_more_icon_str']:'';
        $read_more_class = isset($settings['read_more_class'])? $settings['read_more_class']:'';
        $post_count = count($posts);
	$post_image_size = $settings['post_image_size'];
        if ($post_image_size == 'custom' && isset($settings['post_image_custom_dimension'],$settings['post_image_custom_dimension']['width'],$settings['post_image_custom_dimension']['height'])){
            $post_image_size = [$settings['post_image_custom_dimension']['width'],$settings['post_image_custom_dimension']['height']];
        }
        ?>
        <div class="swiper-slide">
            <?php
            $i = 0;
            $j = 0;
            foreach ($posts as $post):
                $post_link = get_the_permalink( $post->ID );
                $special_class = '';
                $background_style = '';
                $open_special_wrap = '';
                if ($layout == 'cenos_carousel_posts_02'){
                    if ($i == 0){
                        $open_special_wrap = '<div class="fmtpl-swiper-item-wrap">';
                        $special_class .= ' background_thumb';
                        $background_thumb = get_the_post_thumbnail_url($post->ID,'medium_large');
                        if ($background_thumb){
                            $background_style = 'style="background-image: url('.$background_thumb.');"';
                        }
                    } else {
                        $special_class .= '';
                    }
                }

                $meta_html = '';
                if ( 'yes' === $settings['meta'] ){
                    $meta_html .=  '<div class="fmtpl-post-meta-wrap">';
                    if ( 'yes' === $settings['author_meta'] ){
                        $author_link = get_the_author_meta( 'url', $post->post_author  );
                        $meta_html .= sprintf('<span class="fmtpl-post-meta fmtpl-post-author">%s<span class="meta-title">%s</span> <span class="meta-content author-name"><a class="author-link" href="%s">%s</a></span></span>',
                            (isset($settings['author_icon_str']) && !empty($settings['author_icon_str']))? $settings['author_icon_str']:'',
                           esc_html__('By','cenos'),
                            $author_link,
                            get_the_author_meta( 'display_name', $post->post_author )
                        );
                    }
                    if ( 'yes' === $settings['date_meta'] ){
                        $meta_html .= sprintf('<span class="fmtpl-post-meta fmtpl-post-date">%s %s</span>',
                            (isset($settings['date_icon_str']) && !empty($settings['date_icon_str']))? $settings['date_icon_str']:'',
                            get_the_date( "M d, Y" )
                        );
                    }
                    if ( 'yes' === $settings['comment_meta'] ){
                        $comments_number = get_comments_number_text(false,esc_html__('1 Comment', 'cenos'),esc_html__('% Comments', 'cenos'), $post->ID);
                        $meta_html .= sprintf('<span class="fmtpl-post-meta fmtpl-post-comment">%s %s</span>',
                            (isset($settings['comment_icon_str']) && !empty($settings['comment_icon_str']))? $settings['comment_icon_str']:'',
                            $comments_number
                        );
                    }
                    $meta_html .= '</div>';
                } ?>
                <?php cenos_esc_data($open_special_wrap);?>
		        <div class="fmtpl-post-carousel-item<?php echo esc_attr($special_class);?>">
                    <?php if (!empty( $background_style)):?>
                        <div class="fmtpl-post-item-background" <?php cenos_esc_data($background_style);?>></div>
                    <?php endif;?>
                    <div class="fmtpl-post-thumb">
                        <a href="<?php echo esc_url( $post_link ); ?>">
                            <?php if ( 'yes' === $settings['feature_image'] ):
                                if (has_post_thumbnail($post)):
                                    echo get_the_post_thumbnail( $post->ID, $post_image_size );
                                else:?>
                                    <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP88B8AAuUB8e2ujYwAAAAASUVORK5CYII=" alt="<?php the_title_attribute(); ?>" />
                                <?php endif;
                            endif; ?>
                        </a>
                    </div>
                    <div class="fmtpl-post-content">
                        <?php if ($layout == 'cenos_carousel_posts_01') {
                            cenos_esc_data($meta_html);
                        }
                        if ('yes' === $settings['category_data'] ):
                            $categories = get_the_category( $post->ID );
                            if (!empty($categories)):
                            ?>
                            <div class="fmtpl-post-category">
                                <a href="<?php echo get_category_link($categories[0]);?>" title="<?php echo esc_attr($categories[0]->name);?>">
                                    <?php if (isset($settings['category_icon_str']) && !empty($settings['category_icon_str'])) {
                                        cenos_esc_data($settings['category_icon_str']);
                                    }

                                    echo esc_html( $categories[0]->name ); ?>
                                </a>
                            </div>
                            <?php endif;
                        endif;
                        $title = $post->post_title;
                        if ( 'selected' === $settings['show_post_by'] && array_key_exists( $post->ID, $customize_title ) ) {
                            $title = $customize_title[$post->ID];
                        }
                        printf( '<h3 %1$s><a href="%2$s">%3$s</a></h3>',
                            'class="fmtpl-post-title"',
                            esc_url( $post_link ),
                            esc_html( $title )
                        );
                        if ('yes' == $settings['excerpt']) {
                            printf( '<div class="fmtpl_post_excerpt">%1$s</div>',
                                $post->post_excerpt
                            );
                        }
                        ?>
                        <?php if ( isset($settings['read_more'],$settings['read_more_text']) && $settings['read_more']):?>
                            <span class="fmtpl-post-readmore">
                                <?php
                                printf('<a class="fmtpl-button-default fmtpl-carousel-item-btn %s" href="%s" title="%s">%s %s</a>',
                                    $read_more_class,
                                    esc_url( $post_link ),
                                    esc_html( $title ),
                                    $read_more_icon_str,
                                    $settings['read_more_text']
                                );?>
                            </span>
                        <?php endif;
                        if ($layout != 'cenos_carousel_posts_01') {
                            cenos_esc_data($meta_html);
                        }
                        ?>
                    </div>
                </div>
            <?php
                $j++;
                if ($layout == 'cenos_carousel_posts_02'):
                    if ($i == 2):
                        $i = 0;
                        ?>
                        </div> <!-- close_special_wrap .row-->
                        <?php if ($j < $post_count):?>
                            </div><!-- close swiper-slide -->
                            <div class="swiper-slide">
                        <?php endif;
                    else:
                        $i++;
                        if($j == $post_count):
                            echo '</div> <!-- close_special_wrap .row-->';
                        endif;
                    endif;
                else:
                    if ($j < $post_count):?>
                        </div><!-- close swiper-slide -->
                        <div class="swiper-slide">
                    <?php endif;
                endif;
            endforeach;?>
        </div>
        <?php
    }
}

if (!function_exists('fmtpl_carousel_reviews_widget_html')) {
    function fmtpl_carousel_reviews_widget_html($html,$settings,$slide_html) {
        $layout = isset($settings['layout']) ? $settings['layout'] : 'default';
        $slides_count = count( $settings['slides'] );
        if ($slides_count  && $layout == 'cenos_01'){
            $html = '<div class="fmtpl-elementor-widget fmtpl-carousel-reviews fmtpl-reviews-layout-'.$layout.' carousel elementor--star-style-star_unicode">';
            $navi_html = '';
            $pagi_class = 'swiper-pagination';
            if (empty($settings['pagination'] )) {
                $pagi_class .= ' disabled';
            }
            $pagi_html = '<div class="'.$pagi_class.'"></div>';
            if ( 1 < $slides_count && $settings['show_arrows'] ) {
                $sw_btn_class ='';
                if (isset($settings['show_arrows_mobile']) && $settings['show_arrows_mobile'] == 'no'){
                    $sw_btn_class .= ' hidden_on_mobile';
                }
                $navi_html .= '<div class="fmtpl-carousel-navigation-wrapper">';
                $navi_html .= sprintf('<div class="elementor-swiper-button elementor-swiper-button-prev%s">%s<span>%s</span></div>',
                    $sw_btn_class,
                    (isset($settings['prev_icon_str']) && $settings['prev_icon_str'])? $settings['prev_icon_str']:'<i class="eicon-chevron-left" aria-hidden="true"></i>',
                   esc_html__( 'Previous', 'cenos' )
                );
                $navi_html .= sprintf('<div class="elementor-swiper-button elementor-swiper-button-next%s"><span>%s</span>%s</div>',
                    $sw_btn_class,
                   esc_html__( 'Next', 'cenos' ),
                    (isset($settings['next_icon_str']) && $settings['next_icon_str'])? $settings['next_icon_str']:'<i class="eicon-chevron-right" aria-hidden="true"></i>'
                );
                $navi_html .="</div>";
            }

            $content_html = '<div class="fmtpl-carousel-box-title-wrapper"><div class="fmtpl-carousel-box-heading">';
            if (isset($settings['title_text']) && !empty($settings['title_text'])) {
                $highlight_title = (isset($settings['highlight_title']) && !empty($settings['highlight_title']))? $settings['highlight_title']:'';
                $title = str_replace('%highlight%','<span class="highlight">'.$highlight_title.'</span>',$settings['title_text']);
                $content_html .= '<h3 class="fmtpl-carousel-box-title">'.$title.'</h3>';
            }
            if (isset($settings['description']) && !empty($settings['description'])){
                $content_html .= '<div class="fmtpl-carousel-box-desc">'.$settings['description'].'</div>';
            }
            $content_html .='</div>';
            if (!empty($pagi_html)){
                $content_html .= $pagi_html;
            }
            if (!empty($navi_html)){
                $content_html .= $navi_html;
            }
            $content_html .='</div>';
            $html .= '<div class="fmtpl-elementor-swiper"><div class="fmtpl-elementor-main-swiper swiper-container">';
            if ($content_html != '') {
                $html .= '<div class="fmtpl-carousel-box-heading">'.$content_html.'</div>';
            }
            $html .= '<div class="swiper-wrapper">';
            $html .= $slide_html;
            $html .= '</div>';
            $html .='</div></div></div>';
        }
        return $html;
    }
}
if (!function_exists('fmtpl_instagram_widget_html')){
    function fmtpl_instagram_widget_html($html,$settings,$data){
        ob_start();
        ?>
        <div class="fmtpl-elementor-widget fmtpl-instagram <?php echo esc_attr($settings['layout']);?>">
            <?php if (!empty($settings['title'])) :
                $highlight_title = isset($settings['highlight_title'])? $settings['highlight_title']:'';
                $title = str_replace('%highlight%','<span class="highlight">'.$highlight_title.'</span>',$settings['title']);
                ?>
                <div class="instagram-title widget-title"><?php cenos_esc_data($title);?></div>
            <?php endif;?>
            <?php if (!empty($settings['description'])) :?>
                <div class="instagram-desc"><?php cenos_esc_data($settings['description']);?></div>
            <?php endif;?>
            <div class="instagram-feed-content">
                <?php foreach ( $data as $entry ) :
                    $url = $entry['link'];
                    ?>
                    <div class="fmins-item">
                        <div class="image-box">
                            <a title="<?php echo esc_attr($entry['caption']['text']); ?>" class="<?php echo esc_attr(($entry['type'] === 'video') ? 'ei-media-type-video' : 'ei-media-type-image'); ?>"
                               href="<?php echo esc_url($url); ?>">
                                <img alt="<?php echo esc_attr($entry['caption']['text']); ?>"
                                     src="<?php echo esc_attr($entry['images']['thumbnail']['url']); ?>">
                            </a>
                        </div>
                        <div class="img-overlay">
                            <span class="ins-icon">
                                <?php cenos_svg_icon('instagram');?>
                            </span>
                            <span class="ins-like">
                                <?php cenos_svg_icon('heart');?>
                                <?php echo esc_html($entry['likes']['count']);?>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php return ob_get_clean();
    }
}
if (!function_exists('fmtpl_banner_elementor_widget_html')){
    function fmtpl_banner_elementor_widget_html($html, $settings){
        $html = '<div class="fmtpl-elementor-widget fmtpl-banner '.$settings['layout'].'">';
        $style_str = isset($settings['style_str'])?$settings['style_str'] :'';
        if (!empty($settings['image_src']) && empty( $style_str )){
            $style_str = ' style="background-image: url(' . $settings['image_src'] . ');"';
        }
        if ( ! empty( $style_str ) ) {
            if ($settings['layout'] == 'cenos_banner_simple_02'){
                $html .= sprintf('<div class="fmtpl-banner-background gray_scale" %s></div>', $style_str);
            } else {
                $html .= sprintf('<div class="fmtpl-banner-background zoom" %s></div>', $style_str);
            }
        }
        if (isset($settings['overlay']) && !empty($settings['overlay']) || (isset($settings['overlay_hover']) && !empty($settings['overlay_hover']))) {
            $html .= '<div class="fmtpl-banner-overlay"></div>';
        }
        if ($settings['image_html'] != ''){
            if ((!isset($settings['button_text']) && !isset($settings['button_icon'])) ||
                (empty($settings['button_text']) && empty($settings['button_icon']['value']))) {
                // don't have button;

                if ($settings['link_attr_str'] != ''){
                    $html .= '<figure class="fmtpl-banner-box-img has-link"><a class="banner-img-link" '.$settings['link_attr_str'].'>' . $settings['image_html'] . '</a></figure>';
                } else {
                    $html .= '<figure class="fmtpl-banner-box-img">' . $settings['image_html'] . '</figure>';
                }

            } else {
                $html .= '<figure class="fmtpl-banner-box-img">' . $settings['image_html'] . '</figure>';
            }
        }

        $content_html = '';

        if (isset($settings['title_text'])) {
            $highlight_title = isset($settings['highlight_title'])? $settings['highlight_title']:'';
            $title = str_replace('%highlight%','<span class="highlight">'.$highlight_title.'</span>',$settings['title_text']);
            $content_html .= '<div class="fmtpl-widget-title fmtpl-banner-title ">'.$title.'</div>';
        }

        if (isset($settings['description'])){
            $content_html .= '<div class="banner-desc">'.$settings['description'].'</div>';
        }

        if ((isset($settings['button_text']) && !empty($settings['button_text'])) || (isset($settings['button_icon']) && !empty($settings['button_icon']['value']))) {


            $btn_class = isset($settings['btn_icon_position'])? $settings['btn_icon_position'] : 'left';

            $content_html .= '<a class="fmtpl-button-default fmtpl-banner-button '.$btn_class.'" ' . $settings['link_attr_str'] . '>'.$settings['icon_str'].'<span class="fmtpl-btn-text">'.$settings['button_text'].'</span></a>';
        }

        if ($content_html != '') {
            $html .= '<div class="fmtpl-banner-content">'.$content_html.'</div>';
        }
        $html .='</div>';

        return $html;
    }
}

if (!function_exists('fmtpl_category_banner_html')){
    function fmtpl_category_banner_html($htlm, $settings){
        $html = '<div class="fmtpl-elementor-widget fmtpl-category-banner '.$settings['layout'].'">';
        $style_str = isset($settings['image_src'])? ' style="background-image: url(' . $settings['image_src'] . ');"': '';
        $product_cat = false;
        if ( ! empty( $style_str ) ) {
            $html .= '<div class="fmtpl-category-banner-background zoom" '.$style_str.'></div>';
        }
        if (isset($settings['overlay']) && !empty($settings['overlay']) || (isset($settings['overlay_hover']) && !empty($settings['overlay_hover']))) {
            $html .= '<div class="fmtpl-category-banner-overlay"></div>';
        }
        $cat_link = '';
        if (isset($settings['category_id']) && !empty($settings['category_id'])){
            $product_cat = get_term( $settings['category_id'], 'product_cat' );
            if (!empty($product_cat)){
                $cat_link = get_term_link( $product_cat->term_id, 'product_cat' );
            }
        }

        if ($settings['image_html'] != ''){
            if ($cat_link != ''){
                $html .= '<figure class="fmtpl-category-banner-box-img has-link"><a class="banner-img-link" href="'.$cat_link.'">' . $settings['image_html'] . '</a></figure>';
            } else {
                $html .= '<figure class="fmtpl-category-banner-box-img">' . $settings['image_html'] . '</figure>';
            }
        }
        $content_html = '';
        $icon_class = '';
        if (isset($settings['icon_position'])){
            $icon_class = ' icon-pos-'.$settings['icon_position'];
        }
        $content_html .= '<div class="fmtpl-widget-title fmtpl-category-banner-title'.$icon_class.'">';
        if ($settings['icon_str'] != '' && isset($settings['icon_position']) && ($settings['icon_position'] == 'left' || $settings['icon_position'] == 'top')){
            $content_html .= $settings['icon_str'];
        }
        if (isset($settings['custom_title']) && $settings['custom_title'] == 'yes'){
            if (isset($settings['title_text'])) {
                $highlight_title = isset($settings['highlight_title'])? $settings['highlight_title']:'';
                $title = str_replace('%highlight%','<span class="highlight">'.$highlight_title.'</span>',$settings['title_text']);
                $content_html .= '<span class="title-content">';
                if ($cat_link) {
                    $content_html .= '<a class="category-link" href="'.$cat_link.'">'.$title.'</a>';
                } else {
                    $content_html .= $title;
                }
                $content_html .= '</span>';
            }
        } else {
            if ($product_cat) {
                $content_html .= '<span class="title-content">';
                $content_html .= '<a class="category-link" href="'.$cat_link.'">'.$product_cat->name.'</a>';
                $content_html .= '</span>';
            }
        }

        if ($settings['icon_str'] != '' && ($settings['icon_position'] == 'right' || $settings['icon_position'] == 'bottom')){
            $content_html .= $settings['icon_str'];
        }
        $content_html .= '</div>';

        $product_count_title = (isset($settings['product_count_title']) && !empty($settings['product_count_title'])) ? $settings['product_count_title']:esc_html__('Products ','cenos');
        if ($product_cat && isset($settings['show_product_count']) && $settings['show_product_count'] == 'yes'){
            $content_html .= '<div class="fmtpl-products-count '.(isset($settings['count_number_position'])? $settings['count_number_position']:'').'"><span class="products-count-title">'.$product_count_title.'</span><span class="count-value">'.$product_cat->count.'</span></div>';
            if ($cat_link) {
                $content_html .= sprintf('<a class="fmtpl-button-default fmtpl-banner-button left" href="%s"><span class="fmtpl-btn-icon">%s</span><span class="fmtpl-btn-text">%s</span></a>',$cat_link, cenos_get_svg_icon('arrow-triangle-right'),__('Shop now','cenos'));
            }
        }

        if (isset($settings['description'])){
            $content_html .= '<div class="banner-desc">'.$settings['description'].'</div>';
        }

        if ($content_html != '') {
            $html .= '<div class="fmtpl-category-banner-content">'.$content_html.'</div>';
        }
        $html .='</div>';
        return $html;
    }
}