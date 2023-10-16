<?php
if( !class_exists('Fmfw_Product_Video')) {
    class Fmfw_Product_Video
    {
        private static $_instance;
        public static function instance() {
            if ( ! isset( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }
        private static $provider_match_masks = [
            'youtube' => '/^.*(?:youtu\.be\/|youtube(?:-nocookie)?\.com\/(?:(?:watch)?\?(?:.*&)?vi?=|(?:embed|v|vi|user)\/))([^\?&\"\'>]+)/',
            'vimeo' => '/^.*vimeo\.com\/(?:[a-z]*\/)*([‌​0-9]{6,11})[?]?.*/',
            'dailymotion' => '/^.*dailymotion.com\/(?:video|hub)\/([^_]+)[^#]*(#video=([^_&]+))?/',
        ];
        private static $embed_patterns = [
            'youtube' => 'https://www.youtube{NO_COOKIE}.com/embed/{VIDEO_ID}?feature=oembed',
            'vimeo' => 'https://player.vimeo.com/video/{VIDEO_ID}#t={TIME}',
            'dailymotion' => 'https://dailymotion.com/embed/video/{VIDEO_ID}',
        ];
        public function __construct() {
           add_filter( 'woocommerce_product_data_tabs', array($this,'product_edit_tabs') );
            add_filter( 'woocommerce_product_data_panels', array($this,'product_edit_tab_content') );
            add_action( 'woocommerce_process_product_meta_simple', array($this,'product_edit_tab_save') );
            add_action( 'woocommerce_process_product_meta_variable', array($this,'product_edit_tab_save')  );
            // enqueue needed scripts
            add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );
            /*add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );*/
        }
        /**
         * Get video properties.
         *
         * Retrieve the video properties for a given video URL.
         *
         * @since 1.5.0
         * @access public
         * @static
         *
         * @param string $video_url Video URL.
         *
         * @return null|array The video properties, or null.
         */
        public static function get_video_properties( $video_url ) {
            foreach ( self::$provider_match_masks as $provider => $match_mask ) {
                preg_match( $match_mask, $video_url, $matches );
                if ( $matches ) {
                    return [
                        'provider' => $provider,
                        'video_id' => $matches[1],
                    ];
                }
            }
            return null;
        }
        public static function get_embed_url( $video_url, array $embed_url_params = [], array $options = [] ) {
            $video_properties = self::get_video_properties( $video_url );
            if ( ! $video_properties ) {
                return null;
            }
            $embed_pattern = self::$embed_patterns[ $video_properties['provider'] ];
            $replacements = [
                '{VIDEO_ID}' => $video_properties['video_id'],
            ];
            if ( 'youtube' === $video_properties['provider'] ) {
                $replacements['{NO_COOKIE}'] = ! empty( $options['privacy'] ) ? '-nocookie' : '';
            } elseif ( 'vimeo' === $video_properties['provider'] ) {
                $time_text = '';

                $replacements['{TIME}'] = $time_text;
            }
            $embed_pattern = str_replace( array_keys( $replacements ), $replacements, $embed_pattern );
            return add_query_arg( $embed_url_params, $embed_pattern );
        }


        public function scripts(){
            $disable_video = apply_filters('fmfw_disable_product_video', false);
            if (!current_theme_supports('fmfw-product-video') || $disable_video){
                return;
            }
            $screen = get_current_screen();
            if ( $screen->base != 'post' && $screen->post_type != 'product') {
                return;
            }
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

        public function product_edit_tabs($tabs){
            $disable_video = apply_filters('fmfw_disable_product_video', false);
            if (!current_theme_supports('fmfw-product-video') || $disable_video){
                return $tabs;
            }
            $tabs['gallery_video'] = array(
                'label'		=> esc_html__( 'Feature Video', 'urus' ),
                'target'	=> '_feature_video',
            );
            return $tabs;
        }
        public function product_edit_tab_content(){
            $disable_video = apply_filters('fmfw_disable_product_video', false);
            if (!current_theme_supports('fmfw-product-video') || $disable_video){
                return '';
            }
            global $post;
            $post_id = $post->ID;
            $_feature_video_url = get_post_meta( $post_id, '_feature_video_url', true );
            $_feature_video_thumb = get_post_meta( $post_id, '_feature_video_thumb', true );
            $image_url = $_feature_video_thumb ? wp_get_attachment_thumb_url( $_feature_video_thumb ) : wc_placeholder_img_src();
            ?>
            <div id='_feature_video' class='panel woocommerce_options_panel'>
                <div class="options_group">
                    <?php
                    woocommerce_wp_text_input( array(
                        'id'				=> '_feature_video_url',
                        'label'				=> esc_html__( 'Video URL', 'urus' ),
                        'desc_tip'			=> 'true',
                        'description'		=> esc_html__( 'Enter the url video.', 'urus' ),
                        'type' 				=> 'text',
                        'custom_attributes'	=> array(
                            'placeholder'	=> 'http://',
                        ),
                        'value' => empty($_feature_video_url)? '': $_feature_video_url,
                    ) );
                    ?>
                </div>
                <div class="options_group">
                    <p class="form-field _feature_video_thumb_field">
                        <label><?php esc_html_e( 'Background', 'fami-framework' ); ?></label>
                        <span id="fmfw_product_video_thumbnail" style="float:left;margin-right:10px;"><img src="<?php echo esc_url($image_url); ?>" width="60px" height="60px" /></span>
                        <span style="line-height:60px;">
                            <input type="hidden" id="fmfw_feature_video_thumb" class="fmfw_feature_video_thumb" name="fmfw_feature_video_thumb" value="<?php echo $_feature_video_thumb;?>" />
                            <button id="fmfw_feature_video_thumb_upload" type="button" class="fmfw_upload_image_button button"><?php esc_html_e( 'Upload/Add image', 'fami-framework' ); ?></button>
                            <button id="fmfw_feature_video_thumb_remove" type="button" class="fmfw_remove_image_button button"<?php echo ($_feature_video_thumb? '':' style="display:none"');?>><?php esc_html_e( 'Remove image', 'fami-framework' ); ?></button>
                        </span>
                        <span class="clear"></span>
                    </p>
                </div>
            </div>
            <?php
        }

        public function product_edit_tab_save($post_id){
            $disable_video = apply_filters('fmfw_disable_product_video', false);
            if (!current_theme_supports('fmfw-product-video') || $disable_video){
                return;
            }
            if (isset($_POST['_feature_video_url'])){
                $_feature_video_url =  $_POST['_feature_video_url'];
                update_post_meta($post_id,'_feature_video_url',$_feature_video_url);
            }
            if (isset($_POST['fmfw_feature_video_thumb'])) {
                $_feature_video_thumb =  absint( $_POST['fmfw_feature_video_thumb']);
                update_post_meta($post_id,'_feature_video_thumb',$_feature_video_thumb);
            }

        }
    }
}
Fmfw_Product_Video::instance();