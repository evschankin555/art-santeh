<?php
if( !class_exists('Fmfw_Promo_Information')){
    class Fmfw_Promo_Information{
        private static $_instance;
        public static function instance() {
            if ( ! isset( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }
        /**
         * Initialize pluggable functions.
         *
         * @return  void
         */
        public function __construct() {

            add_filter( 'woocommerce_product_data_tabs', array($this,'product_edit_tabs') );
            add_filter( 'woocommerce_product_data_panels', array($this,'product_edit_tab_content') );
            add_action( 'woocommerce_process_product_meta_simple', array($this,'product_edit_tab_save')  );
            add_action( 'woocommerce_process_product_meta_variable', array($this,'product_edit_tab_save')  );
        }

        public function product_edit_tabs($tabs){
            $disable_fmfw_promo = apply_filters('fmfw_disable_product_promo_info', false);
            if (!current_theme_supports('fmfw-promo-info') || $disable_fmfw_promo){
                return $tabs;
            }
            $tabs['promo_information'] = array(
                'label'		=> esc_html__( 'Promo Information', 'fami-framework' ),
                'target'	=> 'promo_information',
            );
            return $tabs;
        }
        /**
         * Contents of the Urus Options options product tab.
         */
        public function product_edit_tab_content(){
            //
            $disable_fmfw_promo = apply_filters('fmfw_disable_product_promo_info', false);
            if (!current_theme_supports('fmfw-promo-info') || $disable_fmfw_promo){
                return '';
            }
            if (isset($_GET['post'])){
                $post_id = $_GET['post'];
            }else{
                global $post;
                $post_id = $post->ID;
            }
            $_promo_info = get_post_meta( $post_id, '_promo_info', true );
            $post_options = fmfw_get_block_post_options();
            ?>
            <div id='promo_information' class='panel woocommerce_options_panel'>
                <div class="options_group">
                    <h4><?php esc_html_e( 'Promo Content', 'fami-framework' ) ?></h4>
                    <?php
                    woocommerce_wp_select(
                        array(
                            'id'          => '_promo_info',
                            'value'       => $_promo_info,
                            'label'       => __( 'Promo Post', 'fami-framework' ),
                            'options'     => $post_options,
                        )
                    );
                    ?>
                </div>
            </div>
            <?php
        }

        public function product_edit_tab_save($post_id){
            $disable_fmfw_promo = apply_filters('fmfw_disable_product_promo_info', false);
            if (!current_theme_supports('fmfw-promo-info') || $disable_fmfw_promo){
                return;
            }
            if ( isset( $_POST[ '_promo_info' ] ) ) {
                update_post_meta( $post_id, '_promo_info', $_POST[ '_promo_info' ]);
            } else {
                delete_post_meta( $post_id, '_promo_info' );
            }
        }
    }
}
Fmfw_Promo_Information::instance();