<?php
if (!function_exists('cenos_load_woocommerce'))
{
    function cenos_load_woocommerce() {
        require_once 'woocommerce-template-functions.php';
        require_once 'woocommerce-template-hooks.php';
    }
}

if (!function_exists('cenos_woocommerce_gallery_thumbnail_size')) {
    function cenos_woocommerce_gallery_thumbnail_size($size)
    {
        $size['width'] = 115;
        $cropping      = get_option( 'woocommerce_thumbnail_cropping', '1:1' );
        if ( 'uncropped' === $cropping ) {
            $size['height'] = $size['width'];
            $size['crop']   = 0;
        } elseif ( 'custom' === $cropping ) {
            $width          = max( 1, get_option( 'woocommerce_thumbnail_cropping_custom_width', '4' ) );
            $height         = max( 1, get_option( 'woocommerce_thumbnail_cropping_custom_height', '3' ) );
            $size['height'] = absint( round( ( $size['width'] / $width ) * $height ) );
            $size['crop']   = 1;
        } else {
            $cropping_split = explode( ':', $cropping );
            $width          = max( 1, current( $cropping_split ) );
            $height         = max( 1, end( $cropping_split ) );
            $size['height'] = absint( round( ( $size['width'] / $width ) * $height ) );
            $size['crop']   = 1;
        }
        return $size;
    }
}

if (!function_exists('cenos_woo_quantity_input_max')) {
    function cenos_woo_quantity_input_max($max, $product) {
        if (!cenos_get_option('woo_single_show_quantity')){
            //hidden quantity set max = min
            $max = $product->get_min_purchase_quantity();
        }
        return $max;
    }
}

if (!function_exists('cenos_woo_external_add_to_cart')) {
    function cenos_woo_external_add_to_cart() {
        global $product;

        if ( ! $product->add_to_cart_url() ) {
            return;
        }
        ob_start();
        $product->single_add_to_cart_text();
        $single_add_to_cart_text = ob_get_clean();
        wc_get_template(
            'single-product/add-to-cart/external.php',
            array(
                'product_url' => $product->add_to_cart_url(),
                'button_text' => $single_add_to_cart_text,
            )
        );
    }
}
if (!function_exists('cenos_woocommerce_breadcrumb_args')) {
    function cenos_woocommerce_breadcrumb_args($args) {
        $args['delimiter'] = '<span>&nbsp;&#47;&nbsp;</span>';
        return $args;
    }
}
if (!function_exists('cenos_get_wishlist_count')) {
    function cenos_get_wishlist_count() {
        $result = false;
        if ((defined('TINVWL_FVERSION') || defined('TINVWL_VERSION'))) {
            global $wl;
            if (empty($wl)){
                $wl = tinv_wishlist_get();
                $GLOBALS['wl'] = $wl;
            }
            if ($wl){
                $wl_products = new TInvWL_Product($wl);
                $result = $wl_products->get_wishlist(array( 'count' => 9999999 ),true);
            }
        } elseif (class_exists('YITH_WCWL')) {
            $result = yith_wcwl_count_all_products();
        }
        wp_send_json($result);
    }
}
if (!function_exists('cenos_ajax_update_qty_cart')){
    function cenos_ajax_update_qty_cart() {
        if (!isset($_POST['hash']) || empty( $_POST['hash'] ) || ! isset( $_POST['quantity'] ) ) {
            wp_send_json_error();
            exit;
        }
        // Set item key as the hash found in input.qty's name
        $cart_item_key = $_POST['hash'];
        // Get the array of values owned by the product we're updating
        $threeball_product_values = WC()->cart->get_cart_item( $cart_item_key );
        if (empty($threeball_product_values)){
            wp_send_json_error();
            exit;
        }
        // Get the quantity of the item in the cart
        $threeball_product_quantity = apply_filters( 'woocommerce_stock_amount_cart_item', apply_filters( 'woocommerce_stock_amount', preg_replace( "/[^0-9\.]/", '', filter_var($_POST['quantity'], FILTER_SANITIZE_NUMBER_INT)) ), $cart_item_key );
        // Update cart validation
        $passed_validation  = apply_filters( 'woocommerce_update_cart_validation', true, $cart_item_key, $threeball_product_values, $threeball_product_quantity );
        $result = false;
        // Update the quantity of the item in the cart
        if ( $passed_validation ) {
            $result = WC()->cart->set_quantity( $cart_item_key, $threeball_product_quantity, true );
            if ($result){
                WC_AJAX::get_refreshed_fragments();
            } else {
                wp_send_json_error();
                exit;
            }
        } else {
            wp_send_json_error();
            exit;
        }
    }
}

if (!function_exists('cenos_get_terms_for_shop_toolbar')) {
    function cenos_get_terms_for_shop_toolbar($type = 'category', $use_sub_cat = null){
        $terms = array();
        $taxonomy = 'category' == $type ? 'product_cat' : 'product_tag';
        $slugs = trim(cenos_get_option( 'shop_control_'.$type.'_items' ));
        if (is_null($use_sub_cat)){
            $use_sub_cat = cenos_get_option('shop_control_sub_'.$type);
        }

        if (is_tax($taxonomy) && $use_sub_cat) {
            if ($use_sub_cat) {
                $queried = get_queried_object();
                $args = array(
                    'taxonomy' => $taxonomy,
                    'parent' => $queried->term_id,
                    'orderby'  => 'menu_order',
                );
                if (is_numeric($slugs)) {
                    $args['number'] = intval($slugs);
                }
                $the_query = new WP_Term_Query($args);
                $terms = $the_query->get_terms();
            }
        }
        else {
            if ( is_numeric( $slugs ) ) {
                $the_query = new WP_Term_Query(array(
                    'taxonomy' => $taxonomy,
                    'orderby'  => 'menu_order',
                    'parent'   => 0,
                    'number'   => intval( $slugs ),
                ) );
                $terms = $the_query->get_terms();
            } elseif ( ! empty( $slugs ) ) {
                $slugs = explode( ',', $slugs );

                if ( empty( $slugs ) ) {
                    return;
                }
                $the_query = new WP_Term_Query(array(
                    'taxonomy' => $taxonomy,
                    'orderby' => 'slug__in',
                    'slug' => $slugs,
                ) );
                $terms = $the_query->get_terms();
            } else {
                $the_query = new WP_Term_Query(array(
                    'taxonomy' => $taxonomy,
                    'orderby'  => 'menu_order',
                    'parent'   => 0,
                ) );
                $terms = $the_query->get_terms();
            }
        }
        if ( empty( $terms ) || is_wp_error( $terms ) ) {
            return [];
        }
        return $terms;
    }
}

if (!function_exists('cenos_catalog_orderby_text')){
    function cenos_catalog_orderby_text($options){
        $options['menu_order'] = esc_attr__( 'Sort by', 'cenos' );
        return $options;
    }
}

if (!function_exists('cenos_woo_single_layout')){
    function cenos_woo_single_layout() {
        if (cenos_is_mobile_single_product_layout()){
            $mobile_single_product_layout = cenos_get_option('mobile_single_product_layout');
            get_template_part( 'woocommerce/single-product/layouts/mobile/product',$mobile_single_product_layout,['temp_class' => $mobile_single_product_layout] );
        } else {
            $product_single_layout = [
                'sidebar-left'      => 'sidebar',
                'sidebar-right'     => 'sidebar',
                'sidebar-left-full' => 'sidebar-full',
                'sidebar-right-full' => 'sidebar-full',
            ];
            $woo_single_image_width = cenos_get_option('woo_single_image_width');
            $img_col_class = [
                'small'     => 'col-lg-4 single_gallery_small',
                'medium'    => 'col-lg-6 single_gallery_medium',
                'large'     => 'col-lg-8 single_gallery_large',
                'full'      => 'col-lg-12 single_gallery_full',
            ];
            $woo_single_layout = cenos_get_option('woo_single_layout');
            $img_col = isset($img_col_class[$woo_single_image_width])? $img_col_class[$woo_single_image_width]:'col-lg-6 single_gallery_medium';


            $args  = [
                'woo_single_layout'=>$woo_single_layout,
                'img_col'=>$img_col,
            ];
            if (cenos_is_woo_single_has_backround()){
                $args['has_background'] = true;
            }
            if (isset($product_single_layout[$woo_single_layout])){
                get_template_part( 'woocommerce/single-product/layouts/product',$product_single_layout[$woo_single_layout], $args );
            } else {
                get_template_part( 'woocommerce/single-product/layouts/product',$woo_single_layout, $args );
            }
        }
    }
}
if (!function_exists('cenos_is_woo_single_has_backround')) {
    function cenos_is_woo_single_has_backround() {
        global $woo_single_bg;
        if (empty($woo_single_bg)) {
            $woo_single_bg = 'not';
            $woo_single_layout = cenos_get_option('woo_single_layout');
            if (in_array($woo_single_layout, ['no-sidebar','sidebar-left','sidebar-right'])){
                $woo_single_product_background = cenos_get_option('woo_single_product_background');
                if (isset($woo_single_product_background['background-image']) && !empty($woo_single_product_background['background-image']) ||
                    isset($woo_single_product_background['background-color']) && !empty($woo_single_product_background['background-color'])) {
                    $woo_single_bg = true;
                }
            }
            $GLOBALS['woo_single_bg'] = $woo_single_bg;
        }
        if ($woo_single_bg === true){
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('cenos_get_adjacent_product')){
    function cenos_get_adjacent_product($prev = true){
        $woo_single_product_nav_same_cat = (bool)cenos_get_option('woo_single_product_nav_same_cat');
        if ($prev){
            $result = get_previous_post($woo_single_product_nav_same_cat,'','product_cat');
        } else {
            $result = get_next_post($woo_single_product_nav_same_cat,'','product_cat');
        }
        if ($result){
            $result =  wc_get_product($result->ID);
        } else {
            $result = cenos_query_wc($prev,$woo_single_product_nav_same_cat);
        }
        if ($result){
            return $result;
        }
        return false;
    }
}

if (!function_exists('cenos_query_wc')) {
    function cenos_query_wc($prev = true, $in_same_term = true)
    {
        /*Query WooCommerce for either the first or last products.*/
        global $post;

        $args = array(
            'limit' => 2,
            'visibility' => 'catalog',
            'exclude' => array($post->ID),
            'orderby' => 'date',
        );

        if (!$prev) {
            $args['order'] = 'ASC';
        }

        if ($in_same_term) {
            $terms = get_the_terms($post->ID, 'product_cat');

            if (!empty($terms) && !is_wp_error($terms)) {
                $args['category'] = wp_list_pluck($terms, 'slug');
            }
        }

        $products = wc_get_products($args);

        // At least 2 results are required, otherwise previous/next will be the same.
        if (!empty($products) && count($products) >= 2) {
            return $products[0];
        }

        return false;
    }
}
if (!function_exists('cenos_woo_single_image_style')) {
    function cenos_woo_single_image_style() {
        global $woo_single_image_style;
        if (empty($woo_single_image_style)){
            $woo_single_layout = cenos_get_option('woo_single_layout');
            switch ($woo_single_layout) {
                case 'sticky':
                    $woo_single_image_style = cenos_get_option('woo_single_image_style_for_sticky');
                    break;
                case 'wide-gallery':
                    $woo_single_image_style = 'list';
                    $classes[] = 'wide-gallery';
                    break;
                default:
                    $woo_single_image_style = cenos_get_option('woo_single_image_style');
                    break;
            }
            $GLOBALS['woo_single_image_style'] = $woo_single_image_style;
        }
        return $woo_single_image_style;
    }
}

if (!function_exists('cenos_single_product_image_gallery_classes')){
    function cenos_single_product_image_gallery_classes($classes){
        $classes[] = 'gallery_style_'.cenos_woo_single_image_style();
        return $classes;
    }
}

if (!function_exists('cenos_product_carousel_options')) {
    function cenos_product_carousel_options( $options ) {
        $woo_single_image_style = cenos_get_option('woo_single_image_style');
        if ('slider' == $woo_single_image_style) {
            $options['animation']  = "slide";
            $options['directionNav'] = true;
            $options['controlNav'] = true;
            $options['prevText'] = cenos_get_svg_icon('arrow-triangle-left');
            $options['nextText'] = cenos_get_svg_icon('arrow-triangle-right');
        }
        if (cenos_on_device()){
            $options['controlNav'] = true;
        }
        return $options;
    }
}
if (!function_exists('cenos_single_product_flexslider_enabled')) {
    function cenos_single_product_flexslider_enabled($value) {
        $woo_single_layout = cenos_get_option('woo_single_layout');
        if ($woo_single_layout == 'sticky' || $woo_single_layout == 'wide-gallery'){
            return false;
        }
        $woo_single_image_style = cenos_get_option('woo_single_image_style');
        if ($woo_single_image_style == 'grid' || $woo_single_image_style == 'grid2'){
            $value = false;
        }
        return $value;
    }
}

if (!function_exists('cenos_single_product_gallery_image_size')) {
    function cenos_single_product_gallery_image_size($size){
        $woo_single_layout = cenos_get_option('woo_single_layout');
        if ($woo_single_layout == 'sticky' || $woo_single_layout == 'wide-gallery'){
            return 'woocommerce_single';
        }
        $woo_single_image_style = cenos_get_option('woo_single_image_style');
        if ($woo_single_image_style == 'grid' || $woo_single_image_style == 'grid2'){
            return 'woocommerce_single';
        }
        return $size;
    }
}

if (!function_exists('cenos_single_product_image_zoom')) {
    function cenos_single_product_image_zoom($value) {
        $woo_single_image_style = cenos_get_option('woo_single_image_style');
        if ($woo_single_image_style == 'grid' || $woo_single_image_style == 'grid2' || $woo_single_image_style == 'list'){
            return false;
        }
        $woo_single_image_zoom = cenos_get_option('woo_single_image_zoom');
        if (cenos_on_device()){
            $woo_single_image_zoom = cenos_get_option('woo_single_image_zoom_mobile');
        }
        return (bool)$woo_single_image_zoom;
    }
}

if (!function_exists('cenos_single_product_image_lightbox')) {
    function cenos_single_product_image_lightbox($value) {
        $woo_single_image_lightbox = cenos_get_option('woo_single_image_lightbox');
        return (bool) $woo_single_image_lightbox;
    }
}

if (!function_exists('cenos_single_product_summary_class')) {
    function cenos_single_product_summary_class($class_arg) {
        $summary_class = ['product-info','summary','entry-summary'];
        if (cenos_get_option('woo_single_layout') != 'wide-gallery') {
            $summary_class[] = 'col';
        }
        $woo_single_summary_align = cenos_get_option('woo_single_summary_align');
        $summary_class[] = 'text-'.$woo_single_summary_align;
        return array_merge($class_arg,$summary_class);
    }
}
if (!function_exists('cenos_products_loop_classes')) {
    function cenos_products_loop_classes($columns) {
        $sc = wc_get_loop_prop('is_fmtpl_widget', false);
        if ($sc || cenos_get_option('shop_page_layout') != 'carousel') {
            $classes = ['row'];
            if (cenos_on_device() && cenos_get_option('mobile_product_items')){
                $shop_list_style = 'grid';
                //$columns = $tablet_columns = $mobile_columns = 2;
                $tablet_columns = cenos_get_option('shop_columns_tablet');
                $mobile_columns = cenos_get_option('shop_columns_mobile');
                $product_item_style = cenos_get_option('mobile_product_items_style');
                $classes[] = 'product-item-'.$product_item_style;
            } else {
                $shop_list_style = cenos_get_option('shop_list_style');
                if ($sc){
                    $shop_list_style = 'grid';
                }
                if (in_array($shop_list_style,['list','list2'])){
                    $tablet_columns = 1;
                    $mobile_columns = 1;
                    if ($shop_list_style == 'list2'){
                        $columns = 2;
                    }
                } else {
                    $tablet_columns = cenos_get_option('shop_columns_tablet');
                    $mobile_columns = cenos_get_option('shop_columns_mobile');
                }
                if ($shop_list_style == 'grid'){
                    $product_item_style = cenos_get_option('product_item_style');
                    $product_item_style = wc_get_loop_prop('product_item_style',$product_item_style);
                    $classes[] = 'product-item-'.$product_item_style;
                }
            }
            $classes[] = 'products-'.$shop_list_style.'-style';
            $classes[] = 'columns-'.$columns;
            $classes[] = 'medium-columns-'.$tablet_columns;
            $classes[] = 'small-columns-'.$mobile_columns;
            return implode(' ',$classes);
        }
        return '';
    }
}

if (!function_exists('cenos_products_loop_classes_filter')) {
    function cenos_products_loop_classes_filter($class, $columns) {
        $class = cenos_products_loop_classes($columns);
        return $class;
    }
}

if (!function_exists('cenos_shop_products_per_page')) {
    function cenos_shop_products_per_page() {
        return cenos_get_option('shop_products_per_page');
    }
}

if (!function_exists('cenos_product_cat_class')) {
    function cenos_product_cat_class($classes) {
        if (($first = array_search('first', $classes)) !== false) {
            unset($classes[$first]);
        }
        if (($last = array_search('last', $classes)) !== false) {
            unset($classes[$last]);
        }
        $item_class = wc_get_loop_prop('product_item_class','');
        if (!empty($item_class)){
            $classes[] = $item_class;
        }
        $classes[] = 'cenos-product-item';
        return $classes;
    }
}

if (!function_exists('cenos_products_group_query')) {
    function cenos_products_group_query ($query) {
        if ( is_admin() || empty( $_GET['products_group'] ) || !(is_shop() || is_product_taxonomy() || is_product())  || ! $query->is_main_query() ) {
            return;
        }
        switch ( $_GET['products_group'] ) {
            case 'featured':
                $tax_query   = $query->get( 'tax_query' );
                $tax_query   = $tax_query ? $tax_query : WC()->query->get_tax_query();
                $tax_query[] = array(
                    'taxonomy' => 'product_visibility',
                    'field'    => 'name',
                    'terms'    => 'featured',
                    'operator' => 'IN',
                );
                $query->set( 'tax_query', $tax_query );
                break;
            case 'sale':
                $query->set( 'post__in', array_merge( array( 0 ), wc_get_product_ids_on_sale() ) );
                break;
            case 'new':
                $query->set( 'post__in', array_merge( array( 0 ), cenos_woocommerce_get_new_product_ids() ) );
                break;
            case 'best_sellers':
                $query->set( 'meta_key', 'total_sales' );
                $query->set( 'order', 'DESC' );
                $query->set( 'orderby', 'meta_value_num' );
                break;
        }
    }
}

if (!function_exists('cenos_woof_products_group_query')) {
    function cenos_woof_products_group_query($query) {
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'woof_draw_products') {
            $link = isset($_REQUEST['link'])? $_REQUEST['link'] : '';
            parse_str($link,$output);
            if (isset($output['products_group'])) {
                switch ( $output['products_group'] ) {
                    case 'featured':
                        $tax_query   = isset($query['tax_query']) ? $query['tax_query'] : [];
                        $tax_query[] = array(
                            'taxonomy' => 'product_visibility',
                            'field'    => 'name',
                            'terms'    => 'featured',
                            'operator' => 'IN',
                        );
                        $query['tax_query'] = $tax_query;
                        break;
                    case 'sale':
                        $sale_products = wc_get_product_ids_on_sale();
                        if (!empty($sale_products)) {
                            $post_in = isset($query['post__in'])? $query['post__in'] : [];
                            if (!empty($post_in)) {
                                $result_sale = array_intersect($post_in, $sale_products);
                            } else {
                                $result_sale = $sale_products;
                            }
                            $query['post__in'] = $result_sale;
                            $query['sale'] = $sale_products;
                        }
                        break;
                    case 'new':
                        $new_products = cenos_woocommerce_get_new_product_ids();
                        if (!empty($new_products)) {
                            $post_in = isset($query['post__in'])? $query['post__in'] : [];
                            if (!empty($post_in)) {
                                $result_new = array_intersect($post_in, $new_products);
                            } else {
                                $result_new = $new_products;
                            }
                            $query['post__in'] = $result_new;
                        }
                        break;
                    case 'best_sellers':
                        $query['meta_key'] = 'total_sales';
                        $query['order'] = 'DESC';
                        $query['orderby'] = 'meta_value_num';
                        break;
                }
            }
        }
        return $query;
    }
}
if (!function_exists('cenos_woof_posts_per_page')) {
    function cenos_woof_posts_per_page($default) {
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'woof_draw_products') {
            return cenos_get_option('shop_products_per_page');
        }
        return $default;
    }
}

/**
 * Get IDs of the products that are set as new ones.
 *
 * @return array
 */
if (!function_exists('cenos_woocommerce_get_new_product_ids')) {
    function cenos_woocommerce_get_new_product_ids() {
        // Load from cache.
        $product_ids = get_transient( 'cenos_woocommerce_products_new' );
        // Valid cache found.
        if ( false !== $product_ids ) {
            return $product_ids;
        }
        $product_ids = array();
        // Get products after selected days.
        $newness = intval( cenos_get_option( 'shop_badge_newness' ) );
        if ( $newness > 0 ) {
            $new_products = new WP_Query( array(
                'posts_per_page' => -1,
                'post_type'      => 'product',
                'fields'         => 'ids',
                'date_query'     => array(
                    'after' => date( 'Y-m-d', strtotime( '-' . $newness . ' days' ) ),
                ),
            ) );
            if ($new_products->post_count > 0) {
                $product_ids = array_merge( $product_ids, $new_products->posts );
            }
        }
        set_transient( 'cenos_woocommerce_products_new', $product_ids, DAY_IN_SECONDS );
        return $product_ids;
    }
}

if (!function_exists('cenos_single_product_thumbnails_columns')) {
    function cenos_single_product_thumbnails_columns($default = 5,$mode = 'desktop') {
        $woo_single_layout = cenos_get_option('woo_single_layout');
        $woo_single_image_style = cenos_get_option('woo_single_image_style'); //default || vertical
        $woo_single_image_width = cenos_get_option('woo_single_image_width'); //large || medium || small
        $layout_mode = 'col';
        if ($woo_single_layout == 'no-sidebar'){
            $layout_mode = 'full';
        }
        $columns_desktop_map = [
            'full_default_large' => 6,
            'full_default_medium' => 6,
            'full_default_small' => 5,
            'full_vertical_large' => 6,
            'full_vertical_medium' => 5,
            'full_vertical_small' => 4,
            'col_default_large' => 5,
            'col_default_medium' => 5,
            'col_default_small' => 4,
            'col_vertical_large' => 4,
            'col_vertical_medium' => 4,
            'col_vertical_small' => 4,
        ];
        $columns_tablet_map = [
            'full_default_large' => 5,
            'full_default_medium' => 5,
            'full_default_small' => 4,
            'full_vertical_large' => 5,
            'full_vertical_medium' => 4,
            'full_vertical_small' => 3,
            'col_default_large' => 4,
            'col_default_medium' => 4,
            'col_default_small' => 3,
            'col_vertical_large' => 3,
            'col_vertical_medium' => 3,
            'col_vertical_small' => 3,
        ];
        $key_columns = $layout_mode.'_'.$woo_single_image_style.'_'.$woo_single_image_width;
        if ($mode == 'desktop'){
            if ($woo_single_image_style == 'vertical'){
                $woo_single_thumb_item_desktop = cenos_get_option('woo_single_thumb_item_desktop');
                if ($woo_single_thumb_item_desktop){
                    return $woo_single_thumb_item_desktop;
                }
            }
            if (isset($columns_desktop_map[$key_columns])){
                return $columns_desktop_map[$key_columns];
            }
        } elseif ($mode == 'tablet') {
            if ($woo_single_image_style == 'vertical') {
                $woo_single_thumb_item_tablet = cenos_get_option('woo_single_thumb_item_tablet');
                if ($woo_single_thumb_item_tablet) {
                    return $woo_single_thumb_item_tablet;
                }
            }
            if (isset($columns_tablet_map[$key_columns])){
                return $columns_tablet_map[$key_columns];
            }
        }
        return $default;
    }
}
if (!function_exists('cenos_single_product_related_responsive')) {
    function cenos_single_product_related_responsive() {
        $woo_single_layout = cenos_get_option('woo_single_layout');
        if ($woo_single_layout == 'sidebar-right-full' || $woo_single_layout == 'sidebar-left-full'){
            return [
                'lg' => 3,
                'xxl' => 4,//>=1440
            ];
        }
        return [
            'lg' => 3,//>=992
            'xl' => 4,//>=1200
        ];
    }
}
if (!function_exists('cenos_product_thumbnails_swiper_responsive')) {
    function cenos_product_thumbnails_swiper_responsive() {
        $cenos_breakpoints = cenos_responsive_breakpoints();
        $data_responsive = [
            $cenos_breakpoints['lg'] => [
                'slidesPerView' => cenos_single_product_thumbnails_columns(4,'tablet'),
                'spaceBetween' => 10,
            ],
            $cenos_breakpoints['xl'] => [
                'slidesPerView' => cenos_single_product_thumbnails_columns(5,'desktop'),
                'spaceBetween' => 10,
            ]
        ];
        return $data_responsive;
    }
}
if (!function_exists('cenos_woosq_button_position')) {
    function cenos_woosq_button_position(){
        return '';
    }
}
if (!function_exists('cenos_attachment_image_attributes')) {
    function cenos_attachment_image_attributes($attr)
    {
        $attr['class'] .= ' fmfw-post-image';
        return $attr;
    }
}
if(!function_exists('cenos_live_search_products')) {
    function cenos_live_search_products() {
        global $woocommerce;

        $results = array();
        if (!$woocommerce || !isset($_REQUEST['s']) || trim($_REQUEST['s']) == '') {
            die(json_encode($results));
        }

        $data_store = WC_Data_Store::load('product');
        $post_id_in = $data_store->search_products(wc_clean($_REQUEST['s']), '', true, true);
        if (empty($post_id_in)) {
            die(json_encode($results));
        }

        $query_args = array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => (int)cenos_get_option('limit_results_search'),
            'no_found_rows' => true
        );

        $query_args['meta_query'] = array();
        $query_args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
        $query_args['meta_query'][] = $woocommerce->query->visibility_meta_query();

        $query_args['post__in'] = array_merge($post_id_in, array(0));
        $query_args['tax_query'] = array('relation' => 'AND');
        $product_visibility_terms = wc_get_product_visibility_term_ids();
        $arr_not_in = array($product_visibility_terms['exclude-from-catalog']);

        // Hide out of stock products.
        if ('yes' === get_option('woocommerce_hide_out_of_stock_items')) {
            $arr_not_in[] = $product_visibility_terms['outofstock'];
        }

        if (!empty($arr_not_in)) {
            $query_args['tax_query'][] = array(
                'taxonomy' => 'product_visibility',
                'field' => 'term_taxonomy_id',
                'terms' => $arr_not_in,
                'operator' => 'NOT IN',
            );
        }

        if (isset($_REQUEST['cat']) && !empty($_REQUEST['cat'])) {
            $query_args['tax_query'][] = array(
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => array($_REQUEST['category'])
            );
        }

        $search_query = new WP_Query( $query_args);
        if ($the_posts = $search_query->get_posts()) {
            foreach ($the_posts as $the_post) {
                $title = get_the_title($the_post->ID);
                if (has_post_thumbnail($the_post->ID)) {
                    $post_thumbnail_ID = get_post_thumbnail_id($the_post->ID);
                    $post_thumbnail_src = wp_get_attachment_image_src($post_thumbnail_ID, 'woocommerce_thumbnail');
                } else {
                    $size = wc_get_image_size('woocommerce_thumbnail');
                    $post_thumbnail_src = array(
                        wc_placeholder_img_src(),
                        esc_attr($size['width']),
                        esc_attr($size['height'])
                    );
                }

                if ($product = wc_get_product($the_post->ID)) {
                    $terms = wc_get_product_terms(
                        $the_post->ID,
                        'product_cat',
                        apply_filters(
                            'woocommerce_breadcrumb_product_terms_args',
                            array(
                                'orderby' => 'parent',
                                'order'   => 'DESC',
                            )
                        )
                    );
                    $cat_title = '';
                    if ( $terms ) {
                        $main_term = apply_filters('woocommerce_breadcrumb_main_term', $terms[0], $terms);
                        $cat_title = '<a class="product-item-cat-link" href="'.get_term_link( $main_term ).'" title="'.$main_term->name.'"><span class="product-item-cat-title">'.$main_term->name.'</span></a>';
                    }
                    $p_ratting = '';
                    if ( wc_review_ratings_enabled() ) {
                        $p_ratting = wc_get_rating_html( $product->get_average_rating() );
                    }
                    $results[] = array(
                        'title' => html_entity_decode($title, ENT_QUOTES, 'UTF-8'),
                        'tokens' => explode(' ', $title),
                        'url' => get_permalink($the_post->ID),
                        'image' => $post_thumbnail_src[0],
                        'price' => $product->get_price_html(),
                        'cat_title' => $cat_title,
                        'ratting' => $p_ratting
                    );
                }
            }
        }
        wp_reset_postdata();

        die(json_encode($results));
    }
}
if (!function_exists('cenos_is_mobile_single_product_layout')) {
    function cenos_is_mobile_single_product_layout() {
        global $cenos_is_mobile_single_product_layout;
        if (is_null($cenos_is_mobile_single_product_layout)){
            if (cenos_on_device() && cenos_get_option('mobile_single_product')){
                $cenos_is_mobile_single_product_layout = true;
            } else {
                $cenos_is_mobile_single_product_layout = false;
            }
            $GLOBALS['cenos_is_mobile_single_product_layout'] = $cenos_is_mobile_single_product_layout;
        }
        return $cenos_is_mobile_single_product_layout;
    }
}