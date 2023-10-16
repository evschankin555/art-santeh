<?php
if( !class_exists('Fm_Product_Cat_Background')){
    class  Fm_Product_Cat_Background{
        private static $_instance;
        public static function instance() {
            if ( ! isset( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }
        public function __construct() {
            add_action( 'init', array( $this, 'init_category_taxonomy_fields' ), 15 );

            add_action( 'created_product_cat', array( $this, 'save_category_taxonomy_fields' ), 10, 2 );
            add_action( 'edit_product_cat', array( $this, 'save_category_taxonomy_fields' ), 10, 2 );

            // enqueue needed scripts
            add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );
        }
        public function scripts(){
            $screen = get_current_screen();
            if( $screen->id == 'edit-product_cat' ){
                wp_enqueue_media();
                wp_enqueue_script( 'fmfw-category-admin', FAMI_FRAMEWORK_PLUGIN_URL. '/assets/js/product-cat-admin.js', array( 'jquery' ), '1.0.0', true );
                wp_localize_script( 'fmfw-category-admin', 'fmfw_category', array(
                    'labels' => array(
                        'upload_file_frame_title' => esc_html__( 'Choose an image', 'fami-framework' ),
                        'upload_file_frame_button' => esc_html__( 'Use image', 'fami-framework' )
                    ),
                    'wc_placeholder_img_src' => wc_placeholder_img_src()
                ) );
            }
        }


        public function init_category_taxonomy_fields(){
            add_action( 'product_cat_add_form_fields', array( $this, 'add_product_cat_taxonomy_fields' ), 15 );
            add_action( 'product_cat_edit_form_fields', array( $this, 'edit_product_cat_taxonomy_fields' ), 15 );
        }
        public function add_product_cat_taxonomy_fields(){
            ?>
            <div class="form-field">
                <label><?php esc_html_e( 'Background', 'fami-framework' ); ?></label>
                <div id="fmfw_product_cat_thumbnail" style="float:left;margin-right:10px;"><img src="<?php echo esc_url(wc_placeholder_img_src()); ?>" width="60px" height="60px" /></div>
                <div style="line-height:60px;">
                    <input type="hidden" id="fmfw_product_cat_background" class="fmfw_product_cat_background" name="fmfw_product_cat_background" />
                    <button id="fmfw_product_cat_background_upload" type="button" class="fmfw_upload_image_button button"><?php esc_html_e( 'Upload/Add image', 'fami-framework' ); ?></button>
                    <button id="fmfw_product_cat_background_remove" type="button" class="fmfw_remove_image_button button"><?php esc_html_e( 'Remove image', 'fami-framework' ); ?></button>
                </div>
                <div class="clear"></div>
            </div>
            <?php
        }
        public function edit_product_cat_taxonomy_fields($term){
            $thumbnail_id = absint( get_term_meta( $term->term_id, 'fmfw_product_cat_background', true ) );
            $image = $thumbnail_id ? wp_get_attachment_thumb_url( $thumbnail_id ) : wc_placeholder_img_src();
            ?>
            <tr class="form-field">
                <th><?php esc_html_e( 'Background', 'fami-framework' ); ?></th>
                <td>
                    <div id="fmfw_product_cat_thumbnail" style="float:left;margin-right:10px;"><img src="<?php echo esc_url($image); ?>" width="60px" height="60px" /></div>
                    <div style="line-height:60px;">
                        <input type="hidden" id="fmfw_product_cat_background" class="fmfw_product_cat_background" name="fmfw_product_cat_background" value="<?php echo esc_attr($thumbnail_id);?>" />
                        <button id="fmfw_product_cat_background_upload" type="button" class="fmfw_upload_image_button button"><?php esc_html_e( 'Upload/Add image', 'fami-framework' ); ?></button>
                        <button id="fmfw_product_cat_background_remove" type="button" class="fmfw_remove_image_button button"><?php esc_html_e( 'Remove image', 'fami-framework' ); ?></button>
                    </div>
                    <div class="clear"></div>
                </td>
            </tr>
            <?php
        }

        /**
         * Save custom term fields
         *
         * @param $term_id int Currently saved term id
         * @param $tt_id int|string Term Taxonomy id
         * @param $taxonomy string Current taxonomy slug
         *
         * @return void
         * @since 1.0.0
         */
        public function save_category_taxonomy_fields( $term_id, $tt_id = '' ) {
            if ( isset( $_POST['fmfw_product_cat_background'] )) {
                update_term_meta( $term_id, 'fmfw_product_cat_background', absint( $_POST['fmfw_product_cat_background'] ) );
            }
        }
    }
}

Fm_Product_Cat_Background::instance();