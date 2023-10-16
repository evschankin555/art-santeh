<?php
if ( ! function_exists( 'cenos_cart_link' ) ) {
    /**
     * Cart Link
     * Displayed a link to the cart including the number of items present and the cart total
     *
     * @return void
     * @since  1.0.0
     */
    function cenos_cart_link() {
        global $woocommerce;
        $show_cart_counter = cenos_get_option('show_cart_counter');
        $show_total = cenos_get_option('show_cart_total');
        ?>
        <span class="mini-cart-contents">
            <?php
            if ($show_total == true):?>
                <span class="cart_sub_total"><?php cenos_esc_data($woocommerce->cart->get_cart_subtotal()); ?></span>
            <?php endif;
            if ($show_cart_counter == true):
                $cart_contents_count = intval( $woocommerce->cart->get_cart_contents_count() );
                ?>
                <span class="count cart-counter"><?php printf( _n( '%d item', '%d items', $cart_contents_count, 'cenos' ), $cart_contents_count ); ?><span><?php echo esc_html($cart_contents_count);?></span></span>
            <?php endif; ?>
        </span>
        <?php
    }
}

if ( ! function_exists( 'cenos_cart_link_fragment' ) ) {
    /**
     * Cart Fragments
     * Ensure cart contents update when products are added to the cart via AJAX
     *
     * @param  array $fragments Fragments to refresh via AJAX.
     * @return array            Fragments to refresh via AJAX
     */
    function cenos_cart_link_fragment( $fragments ) {
        ob_start();
        cenos_cart_link();
        $fragments['.mini-cart-contents'] = ob_get_clean();
        return $fragments;
    }
}

if (!function_exists('cenos_sub_categories_row')){
    function cenos_sub_categories_row(){
        if ( ! empty( $_GET['product_tag'] ) || ! empty( $_GET['product_cat'] ) ) {
            return;
        }
        $subcats = woocommerce_maybe_show_product_subcategories();

        echo empty( $subcats ) ? '' : '<ul class="products product-categories">' . $subcats . '</ul>';
    }
}

if (!function_exists('cenos_shop_control')){
    function cenos_shop_control(){
        if (is_shop() || is_product_taxonomy()):
            $shop_control_layout = cenos_get_option('shop_control_layout');
            ob_start();
            get_template_part('template-parts/shop/shop-control',$shop_control_layout,[]);
            $control_html = ob_get_clean();
            $shop_control_class = ['cenos-shop-control', $shop_control_layout];
            $shop_control_class = apply_filters('cenos_shop_control_class',$shop_control_class); ?>
            <div class="<?php echo implode(' ',$shop_control_class);?>">
                <?php cenos_esc_data($control_html);?>
            </div>
        <?php elseif (is_product()):?>
            <div class="cenos-product-control">
                <?php
                    $woo_single_breadcrumb = cenos_get_option('woo_single_breadcrumb');
                    if ($woo_single_breadcrumb == 'top'){
                        woocommerce_breadcrumb();
                    }
                ?>
                <?php cenos_woo_product_nav();//product pagination?>
            </div>
        <?php
        endif;//is_shop
    }
}

if (!function_exists('cenos_shop_product_tabs')){
    function cenos_shop_product_tabs($type = null){
        if ( ! woocommerce_products_will_display() ) {
            return;
        }
        $shop_control_use_tabs = cenos_get_option('shop_control_use_tabs');
        if (!$shop_control_use_tabs){
            return;
        }
        if (is_null($type)){
            $type   = cenos_get_option( 'shop_control_product_tabs' );
        }

        $tabs   = array();
        $active_all = true;

        if ( is_product_taxonomy() ) {
            $queried  = get_queried_object();
            $base_url = get_term_link( $queried );
        } else {
            $base_url = wc_get_page_permalink( 'shop' );
        }

        if (in_array($type,['category','tag'])) {
            $taxonomy = 'category' == $type ? 'product_cat' : 'product_tag';
            $terms    = cenos_get_terms_for_shop_toolbar($type);
            if ( empty( $terms )) {
                return;
            }
            foreach ( $terms as $term ) {
                $active = false;
                if ( is_tax( $taxonomy, $term->slug ) ) {
                    $active = true;
                    $active_all = false;
                }
                $tabs[] = sprintf(
                    '<a href="%s" class="tab-%s %s">%s</a>',
                    esc_url( get_term_link( $term ) ),
                    esc_attr( $term->slug ),
                    $active ? 'active' : '',
                    esc_html( $term->name )
                );
            }
        } else {
            $groups = (array) cenos_get_option( 'shop_control_tabs_groups' );
            if ( empty( $groups ) ) {
                return;
            }
            $labels = array(
                'best_sellers' => esc_html__( 'Best Sellers', 'cenos' ),
                'featured'     => esc_html__( 'Hot Products', 'cenos' ),
                'new'          => esc_html__( 'New Products', 'cenos' ),
                'sale'         => esc_html__( 'Sale Products', 'cenos' ),
            );
            foreach ( $groups as $group ) {
                $active = false;
                if ( isset( $_GET['products_group'] ) && $group == $_GET['products_group'] ) {
                    $active = true;
                    $active_all = false;
                }
                $tabs[] = sprintf(
                    '<a href="%s" class="tab-%s %s">%s</a>',
                    esc_url( add_query_arg( array( 'products_group' => $group ), $base_url ) ),
                    esc_attr( $group ),
                    $active ? 'active' : '',
                    $labels[ $group ]
                );
            }
        }

        if ( empty( $tabs ) ) {
            return;
        }

        array_unshift( $tabs, sprintf(
            '<a href="%s" class="tab-all %s">%s</a></li>',
            'group' == $type ? esc_url( $base_url ) : esc_url( wc_get_page_permalink( 'shop' ) ),
            $active_all ? 'active' : '',
            esc_html__( 'All Products', 'cenos' )
        ) );

        echo '<div class="cenos-products-tabs">';
        foreach ( $tabs as $tab ) {
            echo trim( $tab );
        }
        echo '</div>';
        add_filter('cenos_shop_control_class',function ($classes) {
            $classes[] = 'control_has_product_tabs';
            return $classes;
        });
    }
}

if (!function_exists('cenos_shop_categories_carousel')){
    function cenos_shop_categories_carousel(){
        if ( ! woocommerce_products_will_display() ) {
            return;
        }
        $terms = cenos_get_terms_for_shop_toolbar('category');
        if ( empty( $terms )) {
            $queried = get_queried_object();
            if ($queried->parent) {
                $parent_tearm = get_term( $queried->parent );
                $parent_title = $parent_tearm->name;
                $parent_link = get_term_link($parent_tearm);
                ?>
                <a href="<?php echo esc_url($parent_link);?>" class="cenos-parent-term-link" title="<?php echo esc_attr($parent_title);?>">
                    <?php cenos_svg_icon('arrow-triangle-left');?>
                    <span><?php esc_html_e('Get back to: ','cenos');echo esc_html($parent_title)?></span>
                </a>
                <?php
            }
        }else {
            $sw_data = [
                'centerInsufficientSlides' => true,
                'slidesPerView' => 3,
                'spaceBetween'  => 20,
                'breakpoints' => [
                    768 => ['slidesPerView' => 4],
                    992 => ['slidesPerView' => 5],
                ]
            ];
            global $wp_filesystem;
            require_once ( ABSPATH . '/wp-admin/includes/file.php' );
            WP_Filesystem();
            echo "<div class='cenos-carousel swiper-container' data-navigation='true' data-swiper='" . htmlspecialchars(json_encode($sw_data)) . "'><ul class='swiper-wrapper'>";
            $queried = false;
            if (is_product_taxonomy()){
                $queried = get_queried_object();
            }
            foreach ( $terms as $term ) {
                $active = false;
                if ($queried && $term->term_id ==  $queried->term_id) {
                    $active = true;
                }
                $term_link =  get_term_link( $term );
                $term_thumbnail = '';
                $get_woo_cat_thumb = true;
                $thumbnail_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
                if ( $thumbnail_id ) {
                $attachment_file = get_attached_file( $thumbnail_id );
                if ($attachment_file){
                    $file_info = pathinfo($attachment_file);
                    if (isset($file_info['extension']) && $file_info['extension'] == 'svg'){
                        if ( $wp_filesystem->exists( $attachment_file ) ) {
                            $term_thumbnail =  $wp_filesystem->get_contents( $attachment_file );
                        }
                        $get_woo_cat_thumb = false;
                    }
                }
                }
                if ($get_woo_cat_thumb){
                    ob_start();
                    woocommerce_subcategory_thumbnail($term);
                    $term_thumbnail = ob_get_clean();
                }
                echo '<li class="category-item'.($active ? ' category-active' : '').'">';
                echo sprintf(
                    '<a href="%s">%s</a>',
                    esc_url( $term_link ),
                    $term_thumbnail
                );
                echo sprintf(
                    '<h3><a href="%s">%s</a></h3>',
                    esc_url( $term_link ),
                    $term->name
                );
                echo sprintf(
                    '<span class="count">%s</span>',
                    $term->count
                );
                echo '</li>';
            }
            echo '</ul></div>';
        }
    }
}

if (!function_exists('cenos_shop_categories_dropdown')) {
    function cenos_shop_categories_dropdown(){
        if ( ! woocommerce_products_will_display() ) {
            return;
        }
        if (is_shop()){
            $current_title = esc_html__('Shop','cenos');
        } elseif (is_product_taxonomy()) {
            $queried = get_queried_object();
            $current_title = $queried->name;
        }
        $terms = cenos_get_terms_for_shop_toolbar('category',true);
        $d_class = ['categories_dropdown'];
        $dropdow_icon = '';
        if (!empty($terms)){
            $d_class[] = 'click_dropdown';
            $dropdow_icon = sprintf('<span class="dropdow-icon">%s</span>',cenos_get_svg_icon('unfold-more'));
        }
        ?>
        <div class="<?php echo implode(' ',$d_class);?>">
            <span class="current">
                <?php
                    echo esc_html($current_title);
                    cenos_esc_data($dropdow_icon);
                ?>
            </span>
            <?php if (!empty($terms)):?>
                <ul>
                    <?php foreach ($terms as $term):?>
                        <li class="category-item">
                            <?php printf(
                                '<a href="%1$s" title="%2$s">%2$s</a>',
                                get_term_link( $term ),
                                $term->name
                            ); ?>
                        </li>
                    <?php endforeach;?>
                </ul>
            <?php endif; ?>
        </div>
        <?php
    }
}

if (!function_exists('cenos_shop_quick_search')){
    function cenos_shop_quick_search() {
        if ( ! woocommerce_products_will_display() ) {
            return;
        }
        ?>
        <div class="shop-control-quick-search">
            <form class="shop-control-quick-search-form" action="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ) ?>" method="get">
                <?php
                $tags = wp_dropdown_categories( array(
                    'taxonomy'        => 'product_tag',
                    'show_option_all' => esc_attr__( 'everything', 'cenos' ),
                    'hide_empty'      => false,
                    'echo'            => false,
                    'value_field'     => 'slug',
                    'name'            => 'product_tag',
                    'hide_if_empty'   => true,
                    'orderby'         => 'count',
                    'order'           => 'DESC',
                    'selected'        => get_query_var( 'product_tag' ),
                ) );

                $categories = wp_dropdown_categories( array(
                    'taxonomy'        => 'product_cat',
                    'show_option_all' => esc_attr__( 'all categories', 'cenos' ),
                    'hide_empty'      => true,
                    'echo'            => false,
                    'value_field'     => 'slug',
                    'name'            => 'product_cat',
                    'hide_if_empty'   => true,
                    'selected'        => get_query_var( 'product_cat' ),
                ) );

                printf(
                    '<span>%s</span> %s %s %s',
                    esc_html__( "I'm interested in", 'cenos' ),
                    $tags ? $tags: '',
                    $tags ? esc_html__( 'for', 'cenos' ) : '',
                    $categories
                );
                ?>

                <button type="submit" value="<?php esc_attr_e( 'search', 'cenos' ) ?>" class="filter-submit"><?php esc_html_e( 'search', 'cenos' ); ?></button>

                <?php
                foreach ( $_GET as $key => $value ) {
                    if ( in_array( $key, array( 'product_tag', 'product_cat' ) ) ) {
                        continue;
                    }
                    printf( '<input type="hidden" name="%s" value="%s">', esc_attr( $key ), esc_attr( $value ) );
                }
                ?>
            </form>
        </div>
        <?php
        add_filter('cenos_shop_control_class',function ($classes) {
            $classes[] = 'control_has_quick_search';
            return $classes;
        });
    }
}

if (!function_exists('cenos_columns_switcher')){
    function cenos_columns_switcher() {
        if (! woocommerce_products_will_display()) {
            return;
        }
        $shop_list_style = cenos_get_option('shop_list_style');
        $current = apply_filters('loop_shop_columns',4);
        $columns = cenos_get_option( 'shop_control_columns' );
        $columns   = apply_filters( 'cenos_shop_control_columns',$columns);
        if ($current != 1){
            $columns[] = $current;
        }
        $columns   = array_unique( $columns );
        $columns   = array_filter( $columns );
        asort( $columns );
        $shop_page_url = get_permalink( wc_get_page_id( 'shop' ) );
        $get_var =  $_GET;
        if ($shop_list_style == 'grid' && isset($get_var['shop_columns'])){
            unset($get_var['shop_columns']);
        }
        $current_url = add_query_arg( $get_var, $shop_page_url );
        ?>
        <p class="columns-switcher">
            <?php
            foreach ( $columns as $column ) {
                $tag   = 'a';
                $class = 'column-switch-btn';
                $query_arg = ['shop_columns' => $column];
                if ($shop_list_style == 'list' || $shop_list_style == 'list2'){
                    $query_arg['list_style_switch'] = 'grid';
                }
                $column_link = 'href="'.esc_url( add_query_arg($query_arg, $current_url)).'"';
                if ($shop_list_style == 'grid' && $column == $current){
                    $class .= ' active';
                    $tag   = 'span';
                    $column_link = '';
                }
                printf(
                    '<%1$s href="%2$s" class="%3$s" %4$s>%5$s</%1$s>',
                    $tag,
                    $column_link,
                    $class,
                    'a' == $tag ? 'rel="nofollow"' : '',
                    $column
                );
            }
            ?>
        </p>
        <?php
    }
}

if (!function_exists('cenos_shop_filter_button')) {
    function cenos_shop_filter_button($show_on_sidebar = false, $shop_control_filter_style = null,$icon = ''){
        if (is_null($shop_control_filter_style)){
            $shop_control_filter_style = cenos_get_option('shop_control_filter_style');
        }
        $data_canvas = '';
        $data_mode = '';
        if ($shop_control_filter_style == 'sidebar'){
            if (!$show_on_sidebar) {
                return;
            }
            $data_mode = 'data-mode="mobile"';
        }
        elseif ($shop_control_filter_style == 'off_canvas'){
            $data_canvas = 'data-offcanvas-trigger="filter-canvas"';
            $data_mode = 'data-mode="canvas"';
        }
        else {
            $data_mode = 'data-mode="dropdown"';
        }
        $shop_control_filter_text = cenos_get_option('shop_control_filter_text');
        if ($icon == ''){
            $icon = 'filter-h';
        }
        ?>
        <div class="cenos-filter-button btn-for-<?php echo esc_attr($shop_control_filter_style);?>">
            <a href="#." class="cenos-filter-btn" <?php cenos_esc_data($data_mode)?> <?php cenos_esc_data($data_canvas)?>>
                <?php cenos_svg_icon($icon);?>
                <span class="filter-title"><?php echo esc_html($shop_control_filter_text);?></span>
            </a>
        </div>
        <?php
    }
}

if (!function_exists('cenos_dropdown_fillter_content')) {
    function cenos_dropdown_fillter_content(){
        if (cenos_on_device()){
            return;
        }
        $shop_control_filter = cenos_get_option('shop_control_filter');
        if (!$shop_control_filter) {
            return;
        }
        $shop_control_filter_style = cenos_get_option('shop_control_filter_style');
        if ($shop_control_filter_style != 'dropdown') {
            return;
        }
        ?>
        <div class="filter-content-wrap dropdown">
            <a href="#." class="cenos-close-filter-btn" data-mode="dropdown">
                <?php cenos_svg_icon('close');?>
                <span class="filter-title"><?php esc_html_e('Hide Filters','cenos');?></span>
            </a>
            <?php
            if (is_active_sidebar( 'product-filter' ) ) {
                dynamic_sidebar('product-filter');
            } ?>
        </div>
        <?php
    }
}

if (!function_exists('cenos_canvas_fillter_content')) {
    function cenos_canvas_fillter_content(){
        if (cenos_is_woocommerce_activated()){
            if (cenos_on_device()){
                return cenos_get_canvas_fillter_content(true, 'off_canvas','left');
            }
        }
        return cenos_get_canvas_fillter_content();
    }
}
if (!function_exists('cenos_get_canvas_fillter_content')) {
    function cenos_get_canvas_fillter_content($shop_control_filter = null, $shop_control_filter_style = null, $shop_control_filter_pos = null) {
        if (is_null($shop_control_filter)){
            $shop_control_filter = cenos_get_option('shop_control_filter');
        }
        if (!$shop_control_filter) {
            return;
        }
        if (is_null($shop_control_filter_style)){
            $shop_control_filter_style = cenos_get_option('shop_control_filter_style');
        }

        if (!cenos_is_woocommerce_activated() || !(is_shop() xor is_product_taxonomy()) || $shop_control_filter_style != 'off_canvas') {
            return;
        }
        if (is_null($shop_control_filter_pos)){
            $shop_control_filter_pos = cenos_get_option('shop_control_filter_pos');
        }

        ?>
        <aside class="js-offcanvas c-offcanvas is-closed"
               data-offcanvas-options='<?php cenos_esc_data('{"modifiers": "'.$shop_control_filter_pos.',overlay"}');?>'
               id="filter-canvas">
            <div class="offcanvas-content">
                <div class="offcanvas_box_head">
                    <h3><?php esc_html_e('Filters','cenos') ?></h3>
                    <button data-focus class="js-offcanvas-close cenos-close-btn" data-button-options='{"modifiers":"m1,m2"}'>
                        <?php cenos_svg_icon('close');?>
                        <span class="button-title"><?php esc_html_e('Close','cenos');?></span>
                    </button>
                </div>
                <div class="filter-content-wrap">
                    <?php
                    if (is_active_sidebar( 'product-filter' ) ) {
                        dynamic_sidebar('product-filter');
                    } ?>
                </div>
            </div>
        </aside>
        <?php
    }
}
if (!function_exists('cenos_sidebar_filter_content')) {
    function cenos_sidebar_filter_content() {
        $show_filter_sidebar = false;
        if (cenos_is_woocommerce_activated()) {
            if (is_product_taxonomy() || is_shop()){
                $show_filter_sidebar = true;
            }
        }
        $shop_control_filter = cenos_get_option('shop_control_filter');
        if (!$shop_control_filter || !$show_filter_sidebar) {
            return;
        }
        $shop_control_filter_style = cenos_get_option('shop_control_filter_style');
        if ($shop_control_filter_style == 'sidebar') {
            $sidebar_html = '';
            if (is_active_sidebar( 'product-filter' ) ) {
                ob_start();
                dynamic_sidebar('product-filter');
                $sidebar_html = ob_get_clean();
            }
            if (!empty($sidebar_html)) {
                $shop_control_filter_pos = cenos_get_option('shop_control_filter_pos');
                ?>
                <div class="row sidebar-<?php echo esc_attr($shop_control_filter_pos);?>">
                    <div class=" col-12 col-lg-3">
                        <?php cenos_shop_filter_button(true); ?>
                        <div class="filter-content-wrap mobile-dropdown">
                            <?php cenos_esc_data($sidebar_html);?>
                        </div>
                    </div>
                    <div class="col col-lg-9">
                <?php
                add_action('woocommerce_after_main_content','cenos_end_sidebar_filter_content',9);
            }
        }
    }
}

if (!function_exists('cenos_end_sidebar_filter_content')) {
    function cenos_end_sidebar_filter_content() {
        ?>
            </div> <!-- end .col in cenos_sidebar_filter_content -->
        </div> <!-- end .row.sidebar- in cenos_sidebar_filter_content -->
        <?php
    }
}

if (!function_exists('cenos_woo_breadcrumb_in_summary')){
    function cenos_woo_breadcrumb_in_summary(){
        if (cenos_get_option('woo_single_breadcrumb') == 'summary'):?>
            <div class="cenos-breadcrumb-summary">
                <?php  woocommerce_breadcrumb();?>
            </div>
        <?php endif;
    }
}

if (!function_exists('cenos_woo_product_nav')){
    function cenos_woo_product_nav(){
        $woo_single_product_nav = cenos_get_option('woo_single_product_nav');
        if ($woo_single_product_nav){
            $prev_post = cenos_get_adjacent_product(true);
            $next_post = cenos_get_adjacent_product(false);
            if ($prev_post || $next_post):?>
            <ul class="product-navigation">
                <?php if ($prev_post):
                    $pre_link = esc_url($prev_post->get_permalink());
                    $pre_name = wp_kses($prev_post->get_name(), 'post');
                    ?>

                <li class="product-nav-item item-prev hover_dropdown_wrapper">
                    <a href="<?php echo cenos_esc_data($pre_link); ?>" rel="prev" class="nav-btn">
                        <?php echo cenos_svg_icon('arrow-triangle-left'); ?>
                    </a>
                    <div class="dropdown_content">
                        <div class="product-nav-item-wrap">
                            <a class="product-nav-item-thumb" title="<?php cenos_esc_data($pre_name);  ?>" href="<?php cenos_esc_data( $pre_link ); ?>">
                            <?php echo wp_kses( $prev_post->get_image() ,'post') ?></a>
                            <div class="product-nav-item-info">
                                <a title="<?php cenos_esc_data($pre_name);; ?>" href="<?php cenos_esc_data( $pre_link); ?>">
                                    <?php cenos_esc_data($pre_name); ?>
                                </a>
                                <?php cenos_esc_data($prev_post->get_price_html()); ?>
                            </div>
                        </div>
                    </div>
                </li>
                <?php endif;?>
                <?php if ($next_post):
                    $next_link = esc_url($next_post->get_permalink());
                    $next_name = wp_kses( $next_post->get_name() ,'post');
                    ?>
                    <li class="product-nav-item item-next hover_dropdown_wrapper">
                        <a href="<?php echo cenos_esc_data($next_link); ?>" rel="next" class="nav-btn">
                            <?php echo cenos_svg_icon('arrow-triangle-right'); ?>
                        </a>
                        <div class="dropdown_content">
                            <div class="product-nav-item-wrap">
                                <a class="product-nav-item-thumb" title="<?php cenos_esc_data($next_name); ?>" href="<?php cenos_esc_data( $next_link ); ?>">
                                <?php echo wp_kses( $next_post->get_image() ,'post') ?></a>
                                <div class="product-nav-item-info">
                                    <a title="<?php cenos_esc_data($next_name); ?>" href="<?php echo cenos_esc_data( $next_link ); ?>">
                                        <?php cenos_esc_data($next_name); ?>
                                    </a>
                                    <?php cenos_esc_data($next_post->get_price_html()); ?>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php endif;?>
            </ul>
            <?php endif;
        }
    }
}

if (!function_exists('cenos_woo_add_to_cart_text')) {
    function cenos_woo_add_to_cart_text($text) {
        return cenos_add_to_cart_text($text);
    }
}
if (!function_exists('cenos_get_woo_add_to_cart_text')){
    function cenos_get_woo_add_to_cart_text($text) {
        ob_start();
        cenos_add_to_cart_text($text);
        return ob_get_clean();
    }
}
if (!function_exists('cenos_add_to_cart_text')) {
    function cenos_add_to_cart_text($text) {
        $woo_add_to_cart_icon = cenos_get_option('woo_add_to_cart_icon');
        if ($woo_add_to_cart_icon){
            cenos_svg_icon('cart-whell');
        }
        if (cenos_get_option('woo_add_to_cart_text') || !$woo_add_to_cart_icon):?>
            <span class="cenos-add-to-cart-text"><?php cenos_esc_data($text);?></span>
        <?php endif;
    }
}
if (!function_exists('cenos_get_select_option_text')) {
    function cenos_get_select_option_text() {
        return cenos_get_svg_icon('layers') . esc_html__( 'Select options', 'cenos' ) ;
    }
}

if (!function_exists('cenos_wvs_pro_loop_add_to_cart_args')) {
    function cenos_wvs_pro_loop_add_to_cart_args($args,$product) {
        if (isset($args['attributes']['data-select-options'])){
            $args['attributes']['data-select-options'] = wc_esc_json(apply_filters( 'woo_variation_swatches_archive_add_to_cart_select_options', apply_filters( 'woo_variation_swatches_archive_add_to_cart_button_text', apply_filters( 'variable_add_to_cart_text', $product->add_to_cart_text() ), $product ), $product ));
        }
        return $args;
    }
}

if (!function_exists('cenos_quantity_group_buttons')){
    function cenos_quantity_group_buttons(){
        ?>
        <div class="quantity-group-buttons">
            <a class="quantity-plus" href="#"><?php cenos_svg_icon('plus');?></a>
            <a class="quantity-minus" href="#"><?php cenos_svg_icon('minus');?></a>
        </div>
        <?php
    }
}

if (!function_exists('cenos_woo_share')) {
    function cenos_woo_share(){
        if (cenos_get_option('woo_product_share')) {
            $woo_product_share_items = cenos_get_option('woo_product_share_items');
            if ($woo_product_share_items && !empty($woo_product_share_items)):
                $socials = [
                    'facebook'   => [
                        'title' =>  esc_html__( 'Facebook', 'cenos' ),
                        'link'  => 'https://www.facebook.com/sharer/sharer.php',
                        'key'   => 'u'
                    ],
                    'twitter'    => [
                        'title' =>  esc_html__( 'Twitter', 'cenos' ),
                        'link'  => 'https://twitter.com/intent/tweet',
                        'title_key' => 'text',
                    ],
                    'googleplus' => [
                        'title' =>  esc_html__( 'Google Plus', 'cenos' ),
                        'link'  => 'https://plus.google.com/share',
                        'text'  =>  esc_html__( 'on Google+', 'cenos' ),
                        'icon'  =>  'google-plus',
                    ],
                    'pinterest'  => [
                        'title' =>  esc_html__( 'Pinterest', 'cenos' ),
                        'link'  => 'https://www.pinterest.com/pin/create/button/',
                        'title_key' => 'description',
                        'media' => 1,
                        'icon'  =>  'pinterest',
                    ],
                    'linkedin'  => [
                        'title' =>  esc_html__( 'Linkedin', 'cenos' ),
                        'link'  => 'https://www.linkedin.com/shareArticle',
                        'title_key' => 'title',
                    ],
                    'tumblr'     => [
                        'title' =>  esc_html__( 'Tumblr', 'cenos' ),
                        'link'  => 'https://www.tumblr.com/share/link',
                        'title_key' => 'name',
                    ],
                    'telegram'   => [
                        'title' =>  esc_html__( 'Telegram', 'cenos' ),
                        'link'  => 'https://t.me/share/url',
                    ],
                    'email'      => [
                        'title' =>  esc_html__( 'Email', 'cenos' ),
                        'text'  =>  esc_html__( 'Via Email', 'cenos' ),
                    ],
                ];
                $p_url = get_permalink();
                $p_title = get_the_title();
                ?>
                <div class="product-share share">
                    <label class="svg-icon icon-socials sharing-icon">
                        <?php cenos_svg_icon('share');?>
                        <span><?php esc_html_e( 'Share', 'cenos' ); ?></span>
                    </label>
                    <ul class="socials-sharing">
                        <?php
                        foreach ($woo_product_share_items as $social_item) :
                            $social_info = $socials[$social_item];
                            $text = isset($social_info['text'])? $social_info['text'] : esc_html__( 'on', 'cenos' ) . ' ' . $social_info['title'];
                            $icon = isset($social_info['icon'])? $social_info['icon'] : $social_item;
                            if ($social_item != 'email') {
                                $query_arg = [];
                                $url_key = isset($social_info['key']) ?  $social_info['key'] : 'url';
                                $query_arg[$url_key] = $p_url;
                                if (isset($social_info['title_key'])){
                                    $query_arg[$social_info['title_key']] = $p_title;
                                }
                                if (isset($social_info['media']) && $social_info['media']){
                                    $query_arg['media'] = get_the_post_thumbnail_url( null, 'full' );
                                }
                                $url = add_query_arg( $query_arg, $social_info['link'] );
                            } else {
                                $url  = 'mailto:?subject=' . $p_title . '&body=' .esc_html__( 'Check out this site:', 'cenos' ) . ' ' . $p_url;
                            }
                            ?>
                            <li>
                                <a class="hint--bounce hint--top" aria-label="<?php echo esc_attr($social_info['title']);?>" title="<?php echo sprintf( esc_attr__( 'Share "%s" "%s"', 'cenos'), get_the_title(),$text ); ?>" href="<?php echo esc_url($url);?>" target="_blank">
                                    <?php cenos_svg_icon($icon);?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif;
        }
    }
}

if (!function_exists('cenos_loop_shop_columns')) {
    function cenos_loop_shop_columns() {
        if (isset($_GET['list_style_switch'])){
            @setcookie('list_style_switch', $_GET['list_style_switch'], time() + WEEK_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN);
            if ('list' == $_GET['list_style_switch']){
                return 1;
            }
        }
        if (isset($_GET['shop_columns']) && ! empty( $_GET['shop_columns'] ) ) {
            $columns = intval($_GET['shop_columns']);
            @setcookie('shop_columns', $columns, time() + WEEK_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN);
        }elseif (isset($_GET['catalog_style'])){
            $columns = cenos_get_option('shop_columns');
        } elseif ( ! empty( $_COOKIE['shop_columns'] ) ) {
            $columns = intval( $_COOKIE['shop_columns'] );
        } else {
            $columns = cenos_get_option('shop_columns');
        }
        return $columns;
    }
}

if ( ! function_exists( 'cenos_promoted_products' ) ) {
    /**
     * Featured and On-Sale Products
     * Check for featured products then on-sale products and use the appropiate shortcode.
     * If neither exist, it can fallback to show recently added products.
     *
     * @since  1.5.1
     * @param integer $per_page total products to display.
     * @param integer $columns columns to arrange products in to.
     * @param boolean $recent_fallback Should the function display recent products as a fallback when there are no featured or on-sale products?.
     * @uses  cenos_is_woocommerce_activated()
     * @uses  wc_get_featured_product_ids()
     * @uses  wc_get_product_ids_on_sale()
     * @uses  cenos_do_shortcode()
     * @return void
     */
    function cenos_promoted_products( $per_page = '2', $columns = '2', $recent_fallback = true ) {
        if ( cenos_is_woocommerce_activated() ) {

            if ( wc_get_featured_product_ids() ) {

                echo '<h2>' . esc_html__( 'Featured Products', 'cenos' ) . '</h2>';

                echo cenos_do_shortcode(
                    'featured_products', array(
                        'per_page' => $per_page,
                        'columns'  => $columns,
                    )
                ); // WPCS: XSS ok.
            } elseif ( wc_get_product_ids_on_sale() ) {

                echo '<h2>' . esc_html__( 'On Sale Now', 'cenos' ) . '</h2>';

                echo cenos_do_shortcode(
                    'sale_products', array(
                        'per_page' => $per_page,
                        'columns'  => $columns,
                    )
                ); // WPCS: XSS ok.
            } elseif ( $recent_fallback ) {

                echo '<h2>' . esc_html__( 'New In Store', 'cenos' ) . '</h2>';

                echo cenos_do_shortcode(
                    'recent_products', array(
                        'per_page' => $per_page,
                        'columns'  => $columns,
                    )
                ); // WPCS: XSS ok.
            }
        }
    }
}

if (!function_exists('cenos_sale_flash')) {
    function cenos_sale_flash( $output, $post, $product ) {
        $shop_badges = cenos_get_option('shop_badges');
        if ( ! in_array( 'sale', $shop_badges) ) {
            return '';
        }

        $type       = cenos_get_option( 'shop_badge_sale_type' );
        $text       = cenos_get_option( 'shop_badge_sale_text' );
        $percentage = 0;

        if ( 'percent' == $type || false !== strpos( $text, '{%}' ) ) {
            if ( $product->get_type() == 'variable' ) {
                $available_variations = $product->get_available_variations();
                $max_percentage       = 0;
                $total_variations     = count( $available_variations );

                for ( $i = 0; $i < $total_variations; $i++ ) {
                    $variation_id        = $available_variations[ $i ]['variation_id'];
                    $variable_product    = new WC_Product_Variation( $variation_id );
                    $regular_price       = $variable_product->get_regular_price();
                    $sales_price         = $variable_product->get_sale_price();
                    $variable_percentage = $regular_price && $sales_price ? round( ( ( ( $regular_price - $sales_price ) / $regular_price ) * 100 ) ) : 0;

                    if ( $variable_percentage > $max_percentage ) {
                        $max_percentage = $variable_percentage;
                    }
                }

                $percentage = $max_percentage ? $max_percentage : $percentage;
            } elseif ( $product->get_regular_price() != 0 ) {
                $percentage = round( ( ( $product->get_regular_price() - $product->get_sale_price() ) / $product->get_regular_price() ) * 100 );
            }
            if ($percentage > 0) {
                if ( 'percent' == $type ) {
                    $output = '<span class="on_sale woocommerce-badge"><span>' . $percentage . '%</span></span>';
                } else {
                    $text = str_replace( '{%}', $percentage . '%', $text );
                    $output = '<span class="on_sale woocommerce-badge"><span>' . esc_html( $text ) . '</span></span>';
                }
            } else {
                $output = '';
            }
        } else {
            $output = '<span class="on_sale woocommerce-badge"><span>' . esc_html($text) . '</span></span>';
        }
        return $output;
    }
}

if (!function_exists('cenos_show_product_loop_flash_content')) {
    function cenos_show_product_loop_flash_content() {
        global $product;
        $results = [];
        $shop_badges = cenos_get_option('shop_badges');
        if (empty($shop_badges)) {
            return;
        }
        if ( $product->is_on_sale() && in_array( 'sale', $shop_badges) ) {
            ob_start();
            woocommerce_show_product_sale_flash();
            $results['sale'] = ob_get_clean();
        }
        if ( $product->is_featured() && in_array( 'hot', $shop_badges) ) {
            $text               = cenos_get_option( 'shop_badge_hot_text' );
            $text               = empty( $text ) ?esc_html__( 'Hot', 'cenos' ) : $text;
            $results['hot'] = '<span class="featured woocommerce-badge"><span>' .  $text . '</span></span>';
        }
        if ( in_array( 'new', $shop_badges) && in_array( $product->get_id(), cenos_woocommerce_get_new_product_ids() ) ) {
            $text          = cenos_get_option( 'shop_badge_new_text' );
            $text          = empty( $text ) ?esc_html__( 'New', 'cenos' ) : $text;
            $results['new'] = '<span class="new woocommerce-badge"><span>' .  $text . '</span></span>';
        }
        if ( in_array( 'sold_out', $shop_badges) && ! $product->is_in_stock() ) {
            $in_stock = false;

            // Double check if this is a variable product.
            if ( $product->is_type( 'variable' ) ) {
                $variations = $product->get_available_variations();
                foreach ( $variations as $variation ) {
                    if( $variation['is_in_stock'] ) {
                        $in_stock = true;
                        break;
                    }
                }
            }
            if ( ! $in_stock ) {
                $text               = cenos_get_option( 'shop_badge_sold_out_text' );
                $text               = empty( $text ) ?esc_html__( 'Sold Out', 'cenos' ) : $text;
                $results['sold_out'] = '<span class="sold_out woocommerce-badge">'.cenos_get_svg_icon('bag-empty').'<span>' .  $text . '</span></span>';
            }
        }
        if (!empty($shop_badges) && !empty($results)):?>
            <div class="cenos_flashs_group">
                <?php foreach ($shop_badges as $badge_k) {
                    if (isset($results[$badge_k])) {
                        cenos_esc_data($results[$badge_k] );
                    }
                } ?>
            </div>
<?php   endif;
    }
}


/* product item function */
if (!function_exists('cenos_product_item_wrapper_open')) {
    function cenos_product_item_wrapper_open() {
        echo '<div class="product-item-wrap">';
    }
}

if (!function_exists('cenos_product_item_infos_wrapper_open')) {
    function cenos_product_item_infos_wrapper_open() {
        echo '<div class="product-infos">';
    }
}

if (!function_exists('cenos_product_item_top_infos_wrapper_open')) {
    function cenos_product_item_top_infos_wrapper_open() {
        echo '<div class="product-top-infos">';
    }
}

if (!function_exists('cenos_product_item_price_wrapper_open')) {
    function cenos_product_item_price_wrapper_open() {
        echo '<div class="product-price-wrapper">';
    }
}
if (!function_exists('cenos_loop_product_thumbnail')) {
    /**
     * cenos template loop product thumbnail
     *
     * @param string $mode the thumbnail mode default|slider|zoom|second.
     * @since 1.0.0
     */
    function cenos_loop_product_thumbnail($mode = '') {
        global $product;
        if (is_null($product)){
            return;
        }
        switch ( $mode ) {
            case 'slider':
                $image_ids  = $product->get_gallery_image_ids();
                $image_size = apply_filters( 'single_product_archive_thumbnail_size', 'woocommerce_thumbnail' );
                if ( $image_ids ) {
                    $sw_data = [
                        'slidesPerView' => 1,
                        'preloadImages' => false,
                        'lazy' => true,
                    ];
                    echo '<div class="product-thumbnail product-thumbnails--slider cenos-carousel" data-swiper=' . htmlspecialchars(json_encode($sw_data)) . ' data-navigation="true">';
                    echo '<div class="swiper-wrapper">';
                    echo '<div class="swiper-slide">';
                } else {
                    echo '<div class="product-thumbnail">';
                }
                woocommerce_template_loop_product_link_open();
                woocommerce_template_loop_product_thumbnail();
                woocommerce_template_loop_product_link_close();
                if ( $image_ids ) {
                    echo '</div>';//close first .swiper-slide
                    foreach ( $image_ids as $image_id ) {
                        $src = wp_get_attachment_image_src( $image_id, $image_size );
                        if ( ! $src ) {
                            continue;
                        }
                        echo '<div class="swiper-slide">';
                        woocommerce_template_loop_product_link_open();
                        printf(
                            '<img src="%s" data-src="%s" width="%s" height="%s" alt="%s" class="swiper-lazy">',
                            'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQYV2M4c+bMfwAIMANkq3cY2wAAAABJRU5ErkJggg==',
                            esc_url( $src[0] ),
                            esc_attr( $src[1] ),
                            esc_attr( $src[2] ),
                            esc_attr( $product->get_title() )
                        );
                        woocommerce_template_loop_product_link_close();
                        echo '<div class="swiper-lazy-preloader"></div>';
                        echo '</div>';//close .swiper-slide
                    }
                    echo '</div>';//close swiper-wrapper
                }
                do_action('cenos_product_item_thumb_action');
                echo '</div>';
                break;
            case 'zoom';
                echo '<div class="product-thumbnail has-zoom-img">';
                $image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
                if ( $image ) {
                    $link = apply_filters( 'woocommerce_loop_product_link', get_the_permalink(), $product );
                    echo '<a href="' . esc_url( $link ) . '" class="woocommerce-LoopProduct-link product-thumbnail-zoom" data-zoom_image="' . $image[0] . '">';
                } else {
                    woocommerce_template_loop_product_link_open();
                }
                woocommerce_template_loop_product_thumbnail();
                woocommerce_template_loop_product_link_close();
                do_action('cenos_product_item_thumb_action');
                echo '</div>';
                break;
            case 'second':
                $image_ids = $product->get_gallery_image_ids();
                if ( ! empty( $image_ids ) ) {
                    echo '<div class="product-thumbnail product-thumbnails--hover">';
                } else {
                    echo '<div class="product-thumbnail">';
                }
                woocommerce_template_loop_product_link_open();
                woocommerce_template_loop_product_thumbnail();
                if ( ! empty( $image_ids ) ) {
                    $image_size = apply_filters( 'single_product_archive_thumbnail_size', 'woocommerce_thumbnail' );
                    echo wp_get_attachment_image( $image_ids[0], $image_size, false, array( 'class' => 'attachment-woocommerce_thumbnail size-woocommerce_thumbnail hover-image' ) );
                }
                woocommerce_template_loop_product_link_close();
                do_action('cenos_product_item_thumb_action');
                echo '</div>';
                break;
            default:
                echo '<div class="product-thumbnail">';
                woocommerce_template_loop_product_link_open();
                woocommerce_template_loop_product_thumbnail();
                woocommerce_template_loop_product_link_close();
                do_action('cenos_product_item_thumb_action');
                echo '</div>';
                break;
        }
    }
}
if (!function_exists('cenos_template_loop_product_thumbnail')) {
    function cenos_template_loop_product_thumbnail() {
        if (cenos_on_device() && cenos_get_option('mobile_product_items')){
            $mode = '';
        } else {
            $shop_list_style = cenos_get_option('shop_list_style');
            $product_item_style = cenos_get_option('product_item_style');
            $product_item_style = wc_get_loop_prop('product_item_style', $product_item_style);
            $sc = wc_get_loop_prop('is_fmtpl_widget', false);
            if ($sc){
                $shop_list_style = 'grid';
            }
            $mode = '';
            if ($shop_list_style == 'grid') {
                if ($product_item_style == 'slider') {
                    $mode = 'slider';
                } else {
                    $mode = cenos_get_option('product_item_hover_image');
                    $mode = wc_get_loop_prop('item_hover_image', $mode);
                }
            }
        }
        cenos_loop_product_thumbnail($mode);
    }
}
if (!function_exists('cenos_product_item_category')) {
    function cenos_product_item_category() {
        $product_item_category = wc_get_loop_prop('show_category',cenos_get_option('product_item_category'));
        if (!empty($product_item_category)) {
            global $product;
            if (is_null($product)){
                return;
            }
            $terms = wc_get_product_terms(
                $product->get_id(),
                'product_cat',
                apply_filters(
                    'woocommerce_breadcrumb_product_terms_args',
                    array(
                        'orderby' => 'parent',
                        'order'   => 'DESC',
                    )
                )
            );
            if ( $terms ) {
                $main_term = apply_filters('woocommerce_breadcrumb_main_term', $terms[0], $terms);
                printf(
                    '<a class="product-item-cat-link" href="%1$s" title="%2$s"><span class="product-item-cat-title">%2$s</span></a>',
                    get_term_link( $main_term ),
                    $main_term->name
                );
            }
        }
    }
}
if (!function_exists('cenos_product_item_title')) {
    function cenos_product_item_title() {
        echo '<h2 class="' . esc_attr( apply_filters( 'woocommerce_product_loop_title_classes', 'woocommerce-loop-product__title' ) ) . '">';
        woocommerce_template_loop_product_link_open();
        the_title();
        woocommerce_template_loop_product_link_close();
        echo '</h2>';
    }
}
if (!function_exists('cenos_show_single_wishlist_btn')) {
    function cenos_show_single_wishlist_btn() {
        if ((defined('TINVWL_FVERSION') || defined('TINVWL_VERSION')) && function_exists('tinvwl_view_addto_htmlout')){
            tinvwl_view_addto_htmlout();
        } elseif (class_exists('YITH_WCWL') && apply_filters( 'yith_wcwl_show_add_to_wishlist', true )) {
            echo do_shortcode( "[yith_wcwl_add_to_wishlist]" );
        }
    }
}
if (!function_exists('cenos_show_loop_wishlist_btn')) {
    function cenos_show_loop_wishlist_btn() {
        if ((defined('TINVWL_FVERSION') || defined('TINVWL_VERSION')) && function_exists('tinvwl_view_addto_htmlloop')){
            tinvwl_view_addto_htmlloop();
        } elseif (class_exists('YITH_WCWL') && 'yes' == get_option( 'yith_wcwl_show_on_loop', 'no' ) && apply_filters( 'yith_wcwl_show_add_to_wishlist', true )) {
            echo do_shortcode( "[yith_wcwl_add_to_wishlist]" );
        }
    }
}
if (!function_exists('cenos_show_quickview_btn')) {
    function cenos_show_quickview_btn() {
        //WOOSQ_VERSION
        if (defined('WOOSQ_VERSION')){
            echo do_shortcode( '[woosq]' );
        }
        elseif (class_exists('YITH_WCQV_Frontend')) {
            YITH_WCQV_Frontend::get_instance()->yith_add_quick_view_button();
        }
    }
}

//woosq_button_html
if (!function_exists('cenos_woosq_button_html')) {
    function cenos_woosq_button_html($button_content){
        if (strpos($button_content,'button') !== 'false'){
            $re_btn = '/<button .*?>(.*?)<\/button>/';
        } else {
            $re_btn = '/<a .*?>(.*?)<\/a>/';
        }
        $subst = '$1';
        $text_btn = preg_replace($re_btn, $subst, $button_content);;
        $content = explode($text_btn,$button_content);
        return $content[0].cenos_get_svg_icon('ico_search').$text_btn.$content[1];
    }
}


if (!function_exists('cenos_show_compare_btn')) {
    function cenos_show_compare_btn() {
        if (defined('WOOSC_VERSION')) {
            echo do_shortcode( '[woosc]' );
        } elseif (class_exists('YITH_Woocompare')) {
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
                $yith_woocompare->obj->add_compare_link();
            }
        }
    }
}
//cenos_woosc_button_text
if (!function_exists('cenos_woosc_button_text')){
    function cenos_woosc_button_text($button_text) {
        return cenos_get_svg_icon('ico_bar-chart').$button_text;
    }
}

//woosc_button_html
if (!function_exists('cenos_woosc_button_html')) {
    function cenos_woosc_button_html($button_content) {
        if (strpos($button_content,'button') !== 'false'){
            $re_btn = '/<button .*?>(.*?)<\/button>/';
        } else {
            $re_btn = '/<a .*?>(.*?)<\/a>/';
        }
        $subst = '$1';
        $text_btn = preg_replace($re_btn, $subst, $button_content);;
        $content = explode($text_btn,$button_content);
        return $content[0].cenos_get_svg_icon('ico_bar-chart').$text_btn.$content[1];
    }
}
if (!function_exists('cenos_product_item_thumb_right_action')) {
    function cenos_product_item_thumb_right_action() {
        if (cenos_on_device() && cenos_get_option('mobile_product_items')){
            $product_item_style = cenos_get_option('mobile_product_items_style');
        } else {
            $shop_list_style = cenos_get_option('shop_list_style');
            $product_item_style = cenos_get_option('product_item_style');
            $product_item_style = wc_get_loop_prop('product_item_style', $product_item_style);
            $sc = wc_get_loop_prop('is_fmtpl_widget', false);
            if ($sc){
                $shop_list_style = 'grid';
            }
            if ($shop_list_style == 'list' || $shop_list_style == 'list2'){
                return;
            }
        }
        $show_wishlist = false;
        $show_cart = false;
        $show_compare = false;
        $show_quickview = false;
        switch ($product_item_style) {
            case 'border':
            case 'style-1':
            case 'style-3':
            case 'style-4':
                $show_wishlist = true;
                $show_compare = true;
                $show_quickview = true;
                break;
            case 'style-5':
                $show_cart = true;
                $show_compare = true;
                $show_quickview = true;
                break;
            case 'style-2':
                $show_wishlist = true;
                $show_compare = true;
                break;
            case 'style-6':
                $show_wishlist = true;
                $show_cart = true;
                $show_compare = true;
                $show_quickview = true;
                break;
            default:
                break;
        }
        if ($show_wishlist || $show_compare || $show_cart || $show_quickview){
            echo '<div class="cenos_thumb_action right">';
            $wishlist_btn = '';
            if ($show_wishlist){
                ob_start();
                cenos_show_loop_wishlist_btn();
                $wishlist_btn =  ob_get_clean();
            }
            if ($show_cart){
                if (get_option( 'yith_wcwl_loop_position') == 'before_add_to_cart') {
                    cenos_esc_data($wishlist_btn);
                    woocommerce_template_loop_add_to_cart();
                } else {
                    woocommerce_template_loop_add_to_cart();
                    cenos_esc_data($wishlist_btn);
                }
            } else {
                cenos_esc_data($wishlist_btn);
            }
            if ($show_compare){
                cenos_show_compare_btn();
            }
            if ($show_quickview) {
                cenos_show_quickview_btn();
            }
            echo '</div><!-- close cenos_thumb_action right -->';
        }

    }
}
if (!function_exists('cenos_product_item_button_wrapper_open')) {
    function cenos_product_item_button_wrapper_open(){
        echo '<div class="cenos_item_button_wrapper">';
    }
}

//cenos_product_item_title_wrapper_open
if (!function_exists('cenos_product_item_title_wrapper_open')) {
    function cenos_product_item_title_wrapper_open(){
        echo '<div class="cenos_item_title_wrapper">';
    }
}
if (!function_exists('cenos_div_wrapper_close')) {
    function cenos_div_wrapper_close(){
        echo '</div>';
    }
}
if (!function_exists('cenos_product_item_thumb_bottom_action')) {
    function cenos_product_item_thumb_bottom_action() {
        if (cenos_on_device() && cenos_get_option('mobile_product_items')){
            $product_item_style = cenos_get_option('mobile_product_items_style');
        } else {
            $shop_list_style = cenos_get_option('shop_list_style');
            $product_item_style = cenos_get_option('product_item_style');
            $product_item_style = wc_get_loop_prop('product_item_style', $product_item_style);
            $sc = wc_get_loop_prop('is_fmtpl_widget', false);
            if ($sc){
                $shop_list_style = 'grid';
            }
            if ($shop_list_style == 'list' || $shop_list_style == 'list2'){
                return;
            }
        }

        $show_price = false;
        $show_cart = false;
        $show_quickview = false;
        $show_wishlist = false;
        $show_compare = false;
        switch ($product_item_style) {
            case 'border':
                $show_cart = true;
                break;
            case 'style-2':
                $show_cart = true;
                $show_quickview = true;
                break;
            case 'style-4':
                $show_cart = true;
                break;
            case 'style-5':
                $show_price = true;
                break;
            case 'style-7':
                $show_cart = true;
                $show_quickview = true;
                $show_wishlist = true;
                $show_compare = true;
                break;
            case 'layout_m_02':
                $show_cart = true;
                break;
            default:
                break;
        }
        if ($show_price || $show_cart || $show_quickview || $show_wishlist || $show_compare){
            echo '<div class="cenos_thumb_action bottom">';
            if ($show_price){
                woocommerce_template_loop_price();
            }
            $wishlist_btn = '';
            if ($show_wishlist){
                ob_start();
                cenos_show_loop_wishlist_btn();
                $wishlist_btn =  ob_get_clean();
            }
            if ($show_cart){
                if (get_option( 'yith_wcwl_loop_position') == 'before_add_to_cart') {
                    cenos_esc_data($wishlist_btn);
                    woocommerce_template_loop_add_to_cart();
                } else {
                    woocommerce_template_loop_add_to_cart();
                    cenos_esc_data($wishlist_btn);
                }
            } else {
                cenos_esc_data($wishlist_btn);
            }
            if ($show_quickview) {
                cenos_show_quickview_btn();
            }
            if ($show_compare){
                cenos_show_compare_btn();
            }
            echo '</div>';
        }
    }
}
if (!function_exists('cenos_yith_wcwl_add_to_wishlist_icon_html')) {
    function cenos_yith_wcwl_add_to_wishlist_icon_html() {
        return cenos_get_svg_icon('heart').cenos_get_svg_icon('heart-fill',16,16,'icon-hover');
    }
}
if (!function_exists('cenos_yith_woocompare_button_text')) {
    function cenos_yith_woocompare_button_text($button_text) {
        return cenos_get_svg_icon('shuffle').$button_text;
    }
}
if (!function_exists('cenos_yith_wcqv_button_label')) {
    function cenos_yith_wcqv_button_label($button_label) {
        return cenos_get_svg_icon('eye').$button_label;
    }
}
if ( ! function_exists( 'cenos_woocommerce_shop_loop_excerpt' ) ) {
    function cenos_woocommerce_shop_loop_excerpt() {
        ?>
        <p class="product-item-excerpt">
            <?php echo get_the_excerpt(); ?>
        </p>
        <?php
    }
}

if (!function_exists('cenos_tinvwl_wishlist_button')) {
    function cenos_tinvwl_wishlist_button($button_content){
        $content = explode('data-tinv-wl-action="add">',$button_content);
        return $content[0].'data-tinv-wl-action="add">'.cenos_get_svg_icon('heart').cenos_get_svg_icon('heart-fill',16,16,'icon-hover').$content[1];
    }
}


if (!function_exists('cenos_checkout_step')){
    function cenos_checkout_step(){
        $step = 0;
        if (is_cart()){
            $step = 1;
        } elseif (is_checkout()){
            $step = 2;
        }
        $shopping_title = esc_html__('Shopping Cart','cenos');
        $checkout_title =esc_html__('Checkout','cenos');
        ?>
        <ul class="cenos-checkout-step">
            <li class="step step-cart<?php echo esc_attr($step > 1?' finish':' active');?>">
                <a class="step-link" href="<?php echo esc_url(wc_get_cart_url());?>" title="<?php echo esc_attr($shopping_title);?>">
                    <span class="step-icon">
                        <?php if ($step == 1){
                            esc_html_e('1','cenos');
                        } else {
                            cenos_svg_icon('check');
                        }?>
                    </span>
                    <span class="label"><?php echo esc_html($shopping_title)?></span>
                </a>
            </li>
            <li class="step step-checkout<?php echo esc_attr($step > 1?' active':'');?>">
                <a class="step-link" href="<?php echo esc_url( wc_get_checkout_url() );?>" title="<?php echo esc_attr($checkout_title);?>">
                    <span class="step-icon">
                        <?php esc_html_e('2','cenos'); ?>
                    </span>
                    <span class="label"><?php echo esc_html($checkout_title);?></span>
                </a>
            </li>
            <?php //is_order_received_page?>
            <li class="step step-order">
                <span class="step-icon"><?php esc_html_e('3','cenos');?></span>
                <span class="label"><?php esc_html_e('Order Complete','cenos');?></span>
            </li>
        </ul>
<?php
    }
}
if (!function_exists('cenos_checkout_step_complete')){
    function cenos_checkout_step_complete($order_id){
        if ($order_id){
            $order_obj = wc_get_order( $order_id );
            if (!$order_obj){
                return;
            }
            $order_false = $order_obj->has_status('false');
            ?>
            <ul class="cenos-checkout-step">
                <li class="step step-cart finish">
                    <span class="step-icon"><?php cenos_svg_icon('check'); ?></span>
                    <span class="label"><?php esc_html_e('Shopping Cart','cenos')?></span>
                </li>
                <li class="step step-checkout finish">
                    <span class="step-icon"><?php cenos_svg_icon('check'); ?></span>
                    <span class="label"><?php esc_html_e('Checkout','cenos')?></span>
                </li>
                <?php //is_order_received_page?>
                <li class="step step-order<?php echo esc_attr($order_false?' active':' finish');?>">
                    <span class="step-icon">
                        <?php
                        if ($order_false){
                            esc_html_e('3','cenos');
                        } else {
                            cenos_svg_icon('check');
                        }
                        ?>
                    </span>
                    <span class="label"><?php esc_html_e('Order Complete','cenos');?></span>
                </li>
            </ul>
            <?php
        }
    }
}
if (!function_exists('cenos_widget_cart_item_quantity_input')){
    function cenos_widget_cart_item_quantity_input( $cart_item, $cart_item_key ) {
        $_product      = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
        $product_price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );

        if ( $_product->is_sold_individually() ) {
            $quantity = '<span class="quantity">1</span>';
        } else {
            $max_val = apply_filters( 'woocommerce_quantity_input_max', $_product->get_max_purchase_quantity(), $_product );
            $quantity = sprintf('<span class="quantity"><a class="quantity-minus" href="#">%s</a>
            <input type="number" id="%s" class="%s" step="%d" min="%d" max="%s" name="%s"
			value="%d" /><a class="quantity-plus" href="#">%s</a></span>',
                cenos_get_svg_icon('minus'),
                uniqid( 'quantity_' ),
                implode( ' ', apply_filters( 'woocommerce_quantity_input_classes', array( 'input-text', 'qty', 'text' ), $_product )),
                apply_filters( 'woocommerce_quantity_input_step', 1, $_product ),
                apply_filters( 'woocommerce_quantity_input_min', 0, $_product ),
                ($max_val > 0)? $max_val:'',
                "cart[{$cart_item_key}][qty]",
                $cart_item['quantity'],
                cenos_get_svg_icon('plus')
            );
        }

        return sprintf(
            '<span class="woocommerce-mini-cart-item__qty" data-nonce="%s">%s</span>',
            esc_attr( wp_create_nonce( 'cenos-update-mini-cart-qty--' . $cart_item_key ) ),
            $quantity
        );
    }
}

if (!function_exists('cenos_woocommerce_login_form_end')){
    function cenos_woocommerce_login_form_end() {
        if ('yes' != get_option( 'woocommerce_enable_myaccount_registration' )){
            $account_link = add_query_arg( 'from_type', 'register', get_permalink(  wc_get_page_id( 'myaccount' )  ) );
            printf('<p class="acount-form-switch">%1$s<a class="account-link" href="%2$s" title="%3$s">%3$s</a></p>',
                esc_html__('Not a member?','cenos'),
                $account_link,
                esc_html__('Register','cenos')
            );
        }
    }
}

if (!function_exists('cenos_woocommerce_register_form_end')){
    function cenos_woocommerce_register_form_end() {
        if ('yes' != get_option( 'woocommerce_enable_myaccount_registration' )) {
            $account_link = get_permalink(wc_get_page_id('myaccount'));
            printf('<p class="acount-form-switch">%1$s<a class="account-link" href="%2$s" title="%3$s">%3$s</a></p>',
                esc_html__('Already a member?', 'cenos'),
                $account_link,
                esc_html__('Login', 'cenos')
            );
        }
    }
}
if (!function_exists('cenos_woo_loop_add_to_cart_link')){
    function cenos_woo_loop_add_to_cart_link($html, $product, $args){
        if ($product->is_type( 'variable' )){
            $cart_icon = cenos_get_svg_icon('layers');
        } else {
            $cart_icon = cenos_get_svg_icon('cart-whell');
        }
        $cart_text = $product->add_to_cart_text();

        return sprintf( '<a href="%s" data-quantity="%s" class="%s" %s>%s %s</a>',
            esc_url( $product->add_to_cart_url() ),
            esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
            esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
            isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
            $cart_icon,
            $cart_text
        );
    }
}

if (!function_exists('cenos_single_sticky_atc')) {
    function cenos_single_sticky_atc(){
        if (!cenos_on_device() || !is_product() || !cenos_get_option('single_sticky_atc'))
            return;
        global $product;
	if ($product->is_type('grouped')){
            return;
        }
        $is_variable = $product->is_type( 'variable' );
        $p_title = $product->get_title();
        $product_atc_button = '';
        if (! $product->is_type( 'variable' ) ){
            ob_start();
            add_filter('cenos_get_option_woo_add_to_cart_icon','cenos_sticky_atc_icon');
            do_action( 'woocommerce_' . $product->get_type() . '_add_to_cart' );
            remove_filter('cenos_get_option_woo_add_to_cart_icon','cenos_sticky_atc_icon');
            $product_atc_button = ob_get_clean();
        }
        if ($is_variable || !empty($product_atc_button)):?>
            <div class="cenos-sticky-atc-btn">
                <div class="cenos-sticky-btn-container">
                    <a class="go_home" href="<?php echo esc_url( home_url() ) ?>" title="<?php echo esc_attr(get_bloginfo( 'name' ));?>">
                        <?php cenos_svg_icon('home-fill');?>
                    </a>
                    <div class="cenos-sticky-btn-content">
                        <div class="cenos-sticky-btn-info">
                            <h4 class="product-title"><?php echo esc_html($p_title); ?></h4>
                        </div>
                    </div>
                    <div class="cenos-sticky-btn-cart">
                        <?php if (! $product->is_type( 'variable' ) ):
                            cenos_esc_data($product_atc_button);
                            ?>
                        <?php else:?>
                            <a href="#." class="cenos-sticky-variations-atc" title="<?php echo esc_html($p_title); ?>">
                                <?php cenos_svg_icon('layers');?>
                                <span><?php esc_html_e( 'Select options', 'cenos' );?></span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif;
    }
}
if (!function_exists('cenos_sticky_atc_icon')) {
    function cenos_sticky_atc_icon($svg_icon){
        return cenos_svg_icon('plus');
    }
}

if (!function_exists('cenos_scroll_cart')) {
    function cenos_scroll_cart(){
        if (cenos_scroll_cart_enable()):
            $btn_class = '';
            if (cenos_on_device() && is_single()){
                $btn_class = ' on_single_mobile';
            }
            ?>
            <div class="scroll_btn_box scroll_cart_btn<?php echo esc_attr($btn_class);?>">
            <?php get_template_part('template-parts/headers/parts/direction/cart',null,['class_tmp' => 'scroll_cart']);?>
            </div>
        <?php endif;
    }
}
if (!function_exists('cenos_scroll_cart_enable')) {
    function cenos_scroll_cart_enable(){
        global $cenos_scroll_cart_enable;
        if (is_null($cenos_scroll_cart_enable)){
            $cenos_scroll_cart_enable = false;
            if (cenos_on_device()){
                if (is_product()){
                    $cenos_scroll_cart_enable = true;
                }
            } else {
                if (cenos_get_option('scroll_cart_btn')){
                    $cenos_scroll_cart_enable = true;
                }
            }
            $GLOBALS['cenos_scroll_cart_enable'] = $cenos_scroll_cart_enable;
        }
        return $cenos_scroll_cart_enable;
    }
}
if (!function_exists('cenos_scroll_wishlist')) {
    function cenos_scroll_wishlist(){
        if (!cenos_on_device() && cenos_get_option('scroll_wishlist_btn')):?>
            <div class="scroll_btn_box scroll_wishlist_btn">
                <?php get_template_part('template-parts/headers/parts/direction/wishlist',null,['class_tmp' => 'scroll_wishlist']);?>
            </div>
        <?php endif;
    }
}
if (!function_exists('cenos_related_products_args')){
    function cenos_related_products_args($args){
        $args['posts_per_page'] = 6;
        return $args;
    }
}
if (!function_exists('cenos_is_disable_product_360degree')){
    function cenos_is_disable_product_360degree($result){
        if (cenos_get_option('woo_single_image_360')){
            $result = false;
        }
        return $result;
    }
}
if (!function_exists('cenos_show_product_360deg_button')){
    function cenos_show_product_360deg_button(){
        global  $product;
        if (is_null($product) || empty( $product ) || ! $product->is_visible() || !cenos_get_option('woo_single_image_360')) {
            return;
        }
        $id = $product->get_id();
        $images = get_post_meta($id,'_gallery_360degree',true);
        if( !empty($images)){
            $btn_class = 'product-360-button';
            if (cenos_get_option('woo_single_image_style') == 'vertical'){
                $btn_class .= ' on_vertical_thumbs';
            }
            ?>
            <a class="<?php echo esc_attr($btn_class);?>" href="javascript:void(0)" data-toggle="modal" data-target="#single-product-360-view";><span><?php cenos_svg_icon('cenos-360-degrees');?></span><?php esc_html_e('360 Degree','cenos');?></a>
            <?php
        }
    }
}

if (!function_exists('cenos_show_product_360deg_modal')){
    function cenos_show_product_360deg_modal(){
        global  $product;
        if (is_null($product) || empty( $product ) || ! $product->is_visible() || !cenos_get_option('woo_single_image_360')) {
            return;
        }
        $id = $product->get_id();
        $images = get_post_meta($id,'_gallery_360degree',true);
        $i      = 0;
        $images_js_string = '';
        if( !empty($images)){
            $frames_count     = count( $images );
            ?>
            <div class="modal product-360-view-wrapper" id="single-product-360-view" tabindex="-1" role="dialog" aria-labelledby="fm-360-modal_Label" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-title" id="fm-360-modal_Label">
                            <button type="button" class="cenos-close-btn close" data-dismiss="modal" aria-label="Close">
                                <?php cenos_svg_icon('close');?>
                                <span class="button-title"><?php esc_html_e('Close','cenos');?></span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="cenos-threed-view threed-id-<?php echo esc_attr( $id ); ?>">
                                <ul class="threed-view-images">
                                    <?php if ( count( $images ) > 0 ) {
                                        foreach ( $images as $img_id ) {
                                            $i++;
                                            $img              = wp_get_attachment_image_src( $img_id, 'full' );
                                            $images_js_string .= "'" . $img[0] . "'";
                                            $width            = $img[1];
                                            $height           = $img[2];
                                            if ( $i < $frames_count ) {
                                                $images_js_string .= ",";
                                            }
                                        }
                                    } ?>
                                </ul>
                                <div class="spinner">
                                    <span>0%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
                jQuery(document).ready(function () {
                    window.addEventListener('load',
                        function (ev) {
                            jQuery('.threed-id-<?php echo esc_attr( $id ); ?>').ThreeSixty({
                                totalFrames: <?php echo esc_attr( $frames_count ); ?>,
                                endFrame: <?php echo esc_attr( $frames_count ); ?>,
                                currentFrame: 1,
                                imgList: '.threed-view-images',
                                progress: '.spinner',
                                imgArray: [<?php echo wp_specialchars_decode( $images_js_string ); ?>],
                                height: <?php echo esc_attr( $height ); ?>,
                                width: <?php echo esc_attr( $width ); ?>,
                                responsive: true,
                                navigation: true
                            });
                        }, false);
                });
            </script>
            <?php
        }
    }
}

if (!function_exists('cenos_show_product_video')) {
    function cenos_show_product_video($video_url = '', $lazy = true) {
        if (empty($video_url)){
            return;
        }
        $video_type = '';
        if (strpos($video_url,'youtube') !== false ){
            $video_type = 'youtube';
        } elseif (strpos($video_url,'vimeo') !== false ){
            $video_type = 'vimeo';
        } elseif (strpos($video_url,'dailymotion') !== false ){
            $video_type = 'dailymotion';
        }
        if (empty($video_type)){
            return;
        }
        $params = [];
        $settings = [
            'video_type' => $video_type,
            'controls' => cenos_get_option('woo_product_video_control'),
            'loop' => cenos_get_option('woo_product_video_control'),
            'mute' => cenos_get_option('woo_product_video_control'),
            'lazy_load' => $lazy,
        ];
        if ( $lazy) {
            $params['autoplay'] = '1';
        } else {
            $params['autoplay'] = '0';
        }

        $params['loop'] = $settings['loop'];
        $params['mute'] = $settings['mute'];
        $video_properties = Fmfw_Product_Video::get_video_properties( $video_url );
        if ( 'youtube' === $video_type ) {
            if ( $settings['loop'] ) {
                $params['playlist'] = $video_properties['video_id'];
            }
            $params['wmode'] = 'opaque';
            $params['controls'] = $settings['controls'];
            $params['enablejsapi'] = 1;
        } elseif ( 'vimeo' === $video_type ) {
            $params['autopause'] = '0';
        } elseif ( 'dailymotion' === $video_type ) {
            $params['controls'] = $settings['controls'];
            $params['endscreen-enable'] = '0';
            $params['queue-enable'] = '0';
            $params['api'] = 'postMessage';
        }
        $embed_options = [];
        if ( 'youtube' === $video_type ) {
            $embed_options['privacy'] = true;
        }
        $embed_options['lazy_load'] = $settings['lazy_load'];
        $frame_attributes = [
            'class' => 'fmfw-video-iframe',
            'allowfullscreen',
            'title' => sprintf(
            // translators: %s: Video provider
                esc_html__( '%s Video Player', 'cenos' ),
                $video_properties['provider']
            ),
        ];
        $video_embed_url = Fmfw_Product_Video::get_embed_url( $video_url, $params, $embed_options );
        if ( ! $video_embed_url ) {
            return null;
        }
        if ( ! $embed_options['lazy_load'] ) {
            $frame_attributes['src'] = $video_embed_url;
        } else {
            $frame_attributes['data-lazy-load'] = $video_embed_url;
        }
        $attributes_for_print = [];
        foreach ( $frame_attributes as $attribute_key => $attribute_value ) {
            $attribute_value = esc_attr( $attribute_value );

            if ( is_numeric( $attribute_key ) ) {
                $attributes_for_print[] = $attribute_value;
            } else {
                $attributes_for_print[] = sprintf( '%1$s="%2$s"', $attribute_key, $attribute_value );
            }
        }
        $attributes_for_print = implode( ' ', $attributes_for_print );
        $iframe_html = "<iframe $attributes_for_print></iframe>";
        cenos_esc_data($iframe_html);
    }
}
if (!function_exists('cenos_is_video_lazy_load')){
    function cenos_is_video_lazy_load(){
        $lazy = false;
        if (cenos_get_option('woo_product_video_lightbox')){
            $lazy = true;
        } else {
            $woo_single_layout = cenos_get_option('woo_single_layout');
            if (in_array($woo_single_layout,['no-sidebar','sidebar-left','sidebar-right','sidebar-left-full','sidebar-right-full'])){
                $image_style = cenos_get_option('woo_single_image_style');
                if ($image_style == 'default' || $image_style == 'vertical' || $image_style == 'slider'){
                    $lazy = true;
                }
            }
        }
        return $lazy;
    }
}
if (!function_exists('cenos_is_video_popup')){
    function cenos_is_video_popup(){
        $popup = false;
        if (cenos_get_option('woo_product_video_lightbox')){
            $popup = true;
        } else {
            $woo_single_layout = cenos_get_option('woo_single_layout');
            if (in_array($woo_single_layout,['no-sidebar','sidebar-left','sidebar-right','sidebar-left-full','sidebar-right-full'])){
                $image_style = cenos_get_option('woo_single_image_style');
                if ($image_style == 'slider'){
                    $popup = true;
                } else {
                    if (cenos_on_device() && in_array($image_style,['default','vertical'])){
                        $popup = true;
                    }
                }
            } elseif ($woo_single_layout == 'wide-gallery'){
                $popup = true;
            }
        }
        return $popup;
    }
}
if (!function_exists('cenos_product_thumb_video')) {
    function cenos_product_thumb_video() {
        if (!cenos_is_video_popup()):
            global  $product;
            if (is_null($product) || empty( $product ) || ! $product->is_visible()) {
                return;
            }
            $id = $product->get_id();
            $video_url = get_post_meta( $id, '_feature_video_url', true );
            if (empty(cenos_video_type_from_url($video_url))){
                return;
            }
            $lazy_video = cenos_is_video_lazy_load();
            $_feature_video_thumb = get_post_meta( $id, '_feature_video_thumb', true );
            $thumb_img = $_feature_video_thumb ? wp_get_attachment_thumb_url( $_feature_video_thumb ) : wc_placeholder_img_src('woocommerce_thumbnail');
            $single_img = $_feature_video_thumb ? wp_get_attachment_url( $_feature_video_thumb  ) : wc_placeholder_img_src('woocommerce_single');
            $gallery_class = ' video-frame';
            if ($lazy_video){
                $gallery_class .= ' lazy-video';
            } else {
                $gallery_class .=' scroll-video';
            }
            ?>
            <div data-thumb="<?php echo esc_url($thumb_img);?>" class="woocommerce-product-gallery__image<?php echo esc_attr($gallery_class);?>">
                <div>
                    <?php cenos_show_product_video($video_url,$lazy_video);?>
                    <img class="video-lazy-image" src="<?php echo esc_url($single_img);?>" alt="<?php esc_attr_e('video','cenos');?>"/>
                </div>
            </div>
        <?php endif;
    }
}
if (!function_exists('cenos_video_url_check')){
    function cenos_video_type_from_url($video_url) {
        $video_type = false;
        if (empty($video_url)){
            return $video_type;
        }
        if (strpos($video_url,'youtube') !== false ){
            $video_type = 'youtube';
        } elseif (strpos($video_url,'vimeo') !== false ){
            $video_type = 'vimeo';
        } elseif (strpos($video_url,'dailymotion') !== false ){
            $video_type = 'dailymotion';
        }
        return $video_type;
    }
}
if (!function_exists('cenos_product_popup_video_button')) {
    function cenos_product_popup_video_button() {
        if (cenos_is_video_popup()):
            global  $product;
            if (is_null($product) || empty( $product ) || ! $product->is_visible()) {
                return;
            }
            $id = $product->get_id();
            $video_url = get_post_meta( $id, '_feature_video_url', true );
            if (empty(cenos_video_type_from_url($video_url))){
                return;
            }
            $btn_class = 'product-video-button';
            if (cenos_get_option('woo_single_image_style') == 'vertical'){
                $btn_class .= ' on_vertical_thumbs';
            }
            ?>

            <a class="<?php echo esc_attr($btn_class); ?>" href="javascript:void(0)" data-toggle="modal" data-target="#single-product-video-view";><span><?php cenos_svg_icon('play');?></span><?php esc_html_e('Play Video','cenos');?></a>
        <?php endif;
    }
}
if (!function_exists('cenos_product_video_modal')) {
    function cenos_product_video_modal() {
        if (cenos_is_video_popup()):
            global  $product;
            if (is_null($product) || empty( $product ) || ! $product->is_visible()) {
                return;
            }
            $id = $product->get_id();
            $video_url = get_post_meta( $id, '_feature_video_url', true );
            if (empty(cenos_video_type_from_url($video_url))){
                return;
            } ?>
            <div class="modal product-video-view-wrapper" id="single-product-video-view" tabindex="-1" role="dialog" aria-labelledby="fm-video-modal_Label" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-title" id="fm-video-modal_Label">
                            <button type="button" class="cenos-close-btn close" data-dismiss="modal" aria-label="Close">
                                <?php cenos_svg_icon('close');?>
                                <span class="button-title"><?php esc_html_e('Close','cenos');?></span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <?php cenos_show_product_video($video_url);;?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif;
    }
}

if (!function_exists('cenos_product_show_dokan_sold_by')){
    function cenos_product_show_dokan_sold_by(){
        if (!function_exists('dokan_get_store_url')){
            return;
        }
        global $product;
        if (is_null($product) || empty( $product ) || ! $product->is_visible()) {
            return;
        }

        $id = $product->get_id();
        $author_id = get_post_field( 'post_author', $id );
        $author    = get_user_by( 'id', $author_id );
        if ( empty( $author ) ) {
            return;
        }
        $shop_info = get_user_meta( $author_id, 'dokan_profile_settings', true );
        if (empty($shop_info)){
            return;
        }

        $shop_name = $author->display_name;
        if ( $shop_info && isset( $shop_info['store_name'] ) && $shop_info['store_name'] ) {
            $shop_name = $shop_info['store_name'];
        }
        ?>
        <div class="sold-by-dokan-vendor">
            <span class="sold-by-label"><?php esc_html_e( 'Sold By:', 'cenos' ); ?> </span>
            <a href="<?php echo esc_url( dokan_get_store_url( $author_id ) ); ?>"><?php echo esc_html( $shop_name ); ?></a>
        </div>
        <?php
    }
}


if (!function_exists('cenos_woo_promo_info')){
    function cenos_woo_promo_info(){
        global $product;
        global $elementor_instance;
        if (is_null($product) || empty( $product ) || ! $product->is_visible()) {
            return;
        }
        $id = $product->get_id();
        $_promo_info = get_post_meta( $id, '_promo_info', true );
        if (!empty($_promo_info) && $_promo_info != 'none'):?>
            <div class="product-promo-wrap">
                <?php
                if( $elementor_instance && cenos_is_built_with_elementor($_promo_info)){
                    cenos_esc_data($elementor_instance->frontend->get_builder_content_for_display($_promo_info));
                } else {
                    echo do_shortcode(get_post_field('post_content', $_promo_info));
                }
                ?>
            </div>
        <?php endif;
    }
}
