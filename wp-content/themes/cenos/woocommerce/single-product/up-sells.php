<?php
/**
 * Single Product Up-Sells
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/up-sells.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $upsells ) : ?>
	<section class="up-sells upsells products">
		<h2><?php esc_html_e( 'You may also like&hellip;', 'cenos' ); ?></h2>
        <?php
        $cenos_breakpoints = cenos_responsive_breakpoints();
        $related_responsive = cenos_single_product_related_responsive();
        $data_space = [
            'lg' => 20,
            'xl' => 30,
            'xxl' => 30
        ];
        $data_responsive = [];
        if (!empty($related_responsive)){
            foreach ($related_responsive as $key => $col) {
                if (isset($cenos_breakpoints[$key])) {
                    $data_responsive[$cenos_breakpoints[$key]] = [
                        'slidesPerView' => $col,
                        'spaceBetween' => isset($data_space[$key])? $data_space[$key]:15,
                    ];
                }
            }
        }
        $sw_data = [
            'slidesPerView' => 2,
            'spaceBetween' => 15,
            'breakpoints' => $data_responsive,
        ];
        $list_class = ['products','swiper-wrapper'];
        if (cenos_on_device() && cenos_get_option('mobile_product_items')){
            $shop_list_style = 'grid';
            $item_style = cenos_get_option('mobile_product_items_style');
        } else {
            $shop_list_style = cenos_get_option('shop_list_style');
            if ($shop_list_style != 'grid'){
                $shop_list_style = 'grid';
                $item_style = 'style-1';
                wc_set_loop_prop('product_item_style','style-1');
                wc_set_loop_prop('shop_list_style','grid');
                wc_set_loop_prop('item_hover_image',cenos_get_option('default'));
            } else {
                $item_style = cenos_get_option('product_item_style');
                wc_set_loop_prop('item_hover_image',cenos_get_option('product_item_hover_image'));
            }
        }
        $list_class[] = 'products-'.$shop_list_style.'-style';
        $list_class[] = 'product-item-'.$item_style;
        wc_set_loop_prop('item_hover_image',cenos_get_option('product_item_hover_image'));
        ?>
        <div class="woocommerce cenos-carousel swiper-container"  data-pagination="page" data-swiper="<?php cenos_esc_data(htmlspecialchars(json_encode($sw_data)));?>">
            <ul class="<?php echo implode(' ',$list_class);?>">
                <?php foreach ( $upsells as $upsell ) : ?>
                    <?php
                        $post_object = get_post( $upsell->get_id() );

                        setup_postdata( $GLOBALS['post'] =& $post_object );

                        wc_get_template_part( 'content', 'product' ); ?>

                <?php endforeach;
                wc_reset_loop();
                ?>
            </ul>
        </div>
	</section>

<?php endif;
wp_reset_postdata();
