<?php
if( !class_exists('Fmfw_Product_360degree')){
    class Fmfw_Product_360degree{
        private static $_instance;
        public static function instance() {
            if ( ! isset( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        public function __construct() {
            add_filter( 'woocommerce_product_data_tabs', array($this,'product_edit_tabs') );
            add_filter( 'woocommerce_product_data_panels', array($this,'product_edit_tab_content') );
            add_action( 'woocommerce_process_product_meta_simple', array($this,'product_edit_tab_save')  );
            add_action( 'woocommerce_process_product_meta_variable', array($this,'product_edit_tab_save')  );
            // enqueue needed scripts
            add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
        }
    
        public function admin_scripts(){
            $disable_360 = apply_filters('fmfw_disable_product_360degree', true);
            if (!current_theme_supports('fmfw-product-360degree')  || $disable_360){
                return;
            }
            $screen = get_current_screen();
            if ( $screen->base != 'post' && $screen->post_type != 'product') {
                return;
            }
            wp_enqueue_media();
            wp_enqueue_style( 'product-gallery-360', FAMI_FRAMEWORK_PLUGIN_URL . '/assets/css/product-360-admin.css',[],FMFW_VERSION);
            wp_enqueue_script( 'product-gallery-360', FAMI_FRAMEWORK_PLUGIN_URL . '/assets/js/product-gallery-360.js', array( 'jquery' ), FMFW_VERSION, true );
            wp_localize_script( 'product-gallery-360', 'product_gallery_360', array(
                'labels' => array(
                    'upload_file_frame_title' => esc_html__( 'Choose an images', 'fami-framework' ),
                    'upload_file_frame_button' => esc_html__( 'Use images', 'fami-framework' )
                ),
                'wc_placeholder_img_src' => wc_placeholder_img_src()
            ) );
        }

        public function product_edit_tabs($tabs){
            $disable_360 = apply_filters('fmfw_disable_product_360degree', false);
            if (!current_theme_supports('fmfw-product-360degree') || $disable_360){
                return $tabs;
            }
            $tabs['gallery_360degree'] = array(
                'label'		=> esc_html__( 'Gallery 360', 'fami-framework' ),
                'target'	=> 'gallery_360degree',
            );
            return $tabs;
        }
        /**
         * Contents of the fami-framework Options options product tab.
         */
        public function product_edit_tab_content(){
            $disable_360 = apply_filters('fmfw_disable_product_360degree', false);
            if (!current_theme_supports('fmfw-product-360degree') || $disable_360){
                return '';
            }
            global $post;
            $post_id = $post->ID;
            $gallery_images = get_post_meta( $post_id, '_gallery_360degree', true );
            ?>
            <div id='gallery_360degree' class='panel woocommerce_options_panel'>
                <div class="options_group">
                    <h4><?php esc_html_e( 'Image Gallery', 'fami-framework' ) ?></h4>
                    <ul id="product-gallery-images-<?php echo esc_attr($post_id)?>" class="product-gallery-images">
                        <?php if( !empty($gallery_images) && is_array($gallery_images)):?>
                            <?php foreach ( $gallery_images as $attachment):?>
                                <li class="image">
                                    <?php echo wp_get_attachment_image($attachment,'thumbnail');?>
                                    <input type="hidden" name="_gallery_360degree[<?php echo esc_attr($post_id); ?>][]" value="<?php echo esc_attr($attachment); ?>">
                                    <a href="#" class="delete remove-product-gallery-image"><span class="dashicons dashicons-dismiss"></span></a>
                                </li>
                            <?php endforeach;?>
                        <?php endif;?>
                    </ul>
                    <div>
                        <button data-id="<?php echo esc_attr($post_id);?>" id="product-gallery-upload-<?php echo esc_attr($post_id);?>"  type="button" class="product-gallery-upload button"><?php esc_html_e( 'Upload/Add images', 'fami-framework' ); ?></button>
                    </div>
                </div>
            </div>
            <?php
        }
    
        public function product_edit_tab_save($post_id){
            $disable_360 = apply_filters('fmfw_disable_product_360degree', false);
            if (!current_theme_supports('fmfw-product-360degree') || $disable_360){
                return;
            }
            if ( isset( $_POST[ '_gallery_360degree' ] ) ) {
                if ( isset( $_POST[ '_gallery_360degree' ][ $post_id ] ) ) {
                    update_post_meta( $post_id, '_gallery_360degree', $_POST[ '_gallery_360degree' ][ $post_id ] );
                } else {
                    delete_post_meta( $post_id, '_gallery_360degree' );
                }
            } else {
                delete_post_meta( $post_id, '_gallery_360degree' );
            }
        }
    }
}
Fmfw_Product_360degree::instance();