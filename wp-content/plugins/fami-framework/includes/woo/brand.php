<?php
if( !class_exists('Fmfw_Product_Brand')){
    class Fmfw_Product_Brand{
        private static $_instance;
        /**
         * Taxonomy slug
         *
         * @var string
         * @since 1.0.0
         */
        public static $brands_taxonomy = 'product-brand';
    
        /**
         * Rewrite for brands
         *
         * @var string
         * @since 1.2.0
         */
        public static $brands_rewrite = 'product-brands';
        
        
        protected static $initialized = false;
        /**
         * Initialize pluggable functions.
         *
         * @return  void
         */

        public static function instance() {
            if ( ! isset( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        public function __construct() {
            // register brand taxonomy
            add_action( 'init', [$this, 'registertaxonomy'] );
            add_action( 'after_switch_theme', 'flush_rewrite_rules' );
    
            add_action( 'init', [$this, 'init_brand_taxonomy_fields'], 15 );
            // enqueue needed scripts
            add_action( 'admin_enqueue_scripts', [$this, 'scripts']);
    
            add_action( 'created_term', [$this, 'save_brand_taxonomy_fields'], 10, 3 );
            add_action( 'edit_term', [$this, 'save_brand_taxonomy_fields'], 10, 3 );
    
            // add taxonomy columns
            add_action( 'init', [$this, 'init_brand_taxonomy_columns'], 15 );

            add_action('woocommerce_single_product_summary',[$this,'display_product_brand_list'],14);
            add_action('woocommerce_after_single_product_summary',[$this ,'related_brand'],50);
        }
    
        /**
         * Register taxonomy for brands
         *
         * @return void
         * @since 1.0.0
         */
        public function registertaxonomy(){
            $disable_fmfw_brand = apply_filters('fmfw_disable_product_brand', false);
            if (!current_theme_supports('fmfw-product-brand') || $disable_fmfw_brand){
                return;
            }
            self::$brands_taxonomy = apply_filters( 'fmfw_taxonomy_slug', self::$brands_taxonomy );
        
            $taxonomy_labels = array(
                'name' => apply_filters( 'fmfw_taxonomy_label_name', esc_html__( 'Brands', 'fami-framework' ) ),
                'singular_name'     => esc_html__( 'Brand', 'fami-framework' ),
                'all_items'         => esc_html__( 'All Brands', 'fami-framework' ),
                'edit_item'         => esc_html__( 'Edit Brand', 'fami-framework' ),
                'view_item'         => esc_html__( 'View Brand', 'fami-framework' ),
                'update_item'       => esc_html__( 'Update Brand', 'fami-framework' ),
                'add_new_item'      => esc_html__( 'Add New Brand', 'fami-framework' ),
                'new_item_name'     => esc_html__( 'New Brand Name', 'fami-framework' ),
                'parent_item'       => esc_html__( 'Parent Brand', 'fami-framework' ),
                'parent_item_colon' => esc_html__( 'Parent Brand:', 'fami-framework' ),
                'search_items'      => esc_html__( 'Search Brands', 'fami-framework' ),
                'separate_items_with_commas' => esc_html__( 'Separate brands with commas', 'fami-framework' ),
                'not_found'         => esc_html__( 'No Brands Found', 'fami-framework' )
            );
        
            $taxonomy_args = array(
                'label' => apply_filters( 'fmfw_taxonomy_label', esc_html__( 'Brands', 'fami-framework' ) ),
                'labels' => apply_filters( 'fmfw_taxonomy_labels', $taxonomy_labels ),
                'public' => true,
                'show_admin_column' => true,
                'hierarchical' => true,
                'capabilities' => apply_filters( 'fmfw_taxonomy_capabilities', array(
                        'manage_terms' => 'manage_product_terms',
                        'edit_terms'   => 'edit_product_terms',
                        'delete_terms' => 'delete_product_terms',
                        'assign_terms' => 'assign_product_terms',
                    )
                ),
                'update_count_callback' => '_wc_term_recount',
            );
        
            $object_type = apply_filters( 'fmfw_taxonomy_object_type', 'product' );
            $fc ='register'.'_taxonomy';
    
            $fc( self::$brands_taxonomy, $object_type, $taxonomy_args );
        
            if( is_array( $object_type ) && ! empty( $object_type ) ){
                foreach( $object_type as $type ){
                    register_taxonomy_for_object_type( self::$brands_taxonomy, $type );
                    
                }
            }
            else{
                register_taxonomy_for_object_type( self::$brands_taxonomy, $object_type );
            }
        }
        
        public function scripts(){
            $disable_fmfw_brand = apply_filters('fmfw_disable_product_brand', false);
            if (!current_theme_supports('fmfw-product-brand') || $disable_fmfw_brand){
                return;
            }
            $screen = get_current_screen();
            if( $screen->id == 'edit-' . self::$brands_taxonomy ){
                wp_enqueue_media();
                wp_enqueue_script( 'fmfw-brand-admin', FAMI_FRAMEWORK_PLUGIN_URL. '/assets/js/admin/brand_admin.js' , array( 'jquery' ), '1.0.0', true );
        
                wp_localize_script( 'fmfw-brand-admin', 'fmfw_brand', array(
                    'labels' => array(
                        'upload_file_frame_title' => esc_html__( 'Choose an image', 'fami-framework' ),
                        'upload_file_frame_button' => esc_html__( 'Use image', 'fami-framework' )
                    ),
                    'wc_placeholder_img_src' => wc_placeholder_img_src()
                ) );
            }
        }
        
        public function init_brand_taxonomy_fields(){
            $disable_fmfw_brand = apply_filters('fmfw_disable_product_brand', false);
            if (!current_theme_supports('fmfw-product-brand') || $disable_fmfw_brand){
                return;
            }
            add_action( self::$brands_taxonomy . '_add_form_fields', [$this, 'add_brand_taxonomy_fields'], 15, 1 );
            add_action( self::$brands_taxonomy . '_edit_form_fields', [$this, 'edit_brand_taxonomy_fields'], 15, 1 );
        }
    
        /**
         * Prints custom term fields on "Add Brand" page
         *
         * @param $term string Current taxonomy id
         *
         * @return void
         * @since 1.0.0
         */
        public function add_brand_taxonomy_fields( $term ) {
            $disable_fmfw_brand = apply_filters('fmfw_disable_product_brand', false);
            if (!current_theme_supports('fmfw-product-brand') || $disable_fmfw_brand){
                return;
            }
            ?>
            <div class="form-field">
                <label><?php esc_html_e( 'Logo', 'fami-framework' ); ?></label>
                <div id="product_brand_thumbnail" style="float:left;margin-right:10px;"><img src="<?php echo esc_url(wc_placeholder_img_src()); ?>" width="60px" height="60px" /></div>
                <div style="line-height:60px;">
                    <input type="hidden" id="product_brand_thumbnail_id" class="fmfw_upload_image_id" name="product_brand_thumbnail_id" />
                    <button id="product_brand_thumbnail_upload" type="button" class="fmfw_upload_image_button button"><?php esc_html_e( 'Upload/Add image', 'fami-framework' ); ?></button>
                    <button id="product_brand_thumbnail_remove" type="button" class="fmfw_remove_image_button button"><?php esc_html_e( 'Remove image', 'fami-framework' ); ?></button>
                </div>
                <div class="clear"></div>
            </div>
            <?php
        }
    
        /**
         * Prints custom term fields on "Edit Brand" page
         *
         * @param $term string Current taxonomy id
         *
         * @return void
         * @since 1.0.0
         */
        public function edit_brand_taxonomy_fields( $term ) {
            $disable_fmfw_brand = apply_filters('fmfw_disable_product_brand', false);
            if (!current_theme_supports('fmfw-product-brand') || $disable_fmfw_brand){
                return;
            }
            $thumbnail_id = absint( get_term_meta( $term->term_id, 'thumbnail_id', true ) );
            $image = $thumbnail_id ? wp_get_attachment_thumb_url( $thumbnail_id ) : wc_placeholder_img_src();
            ?>
            <tr class="form-field">
                <td><?php esc_html_e( 'Logo', 'fami-framework' ); ?></td>
                <td>
                    <div id="product_brand_thumbnail" style="float:left;margin-right:10px;"><img src="<?php echo esc_url($image); ?>" width="60px" height="60px" /></div>
                    <div style="line-height:60px;">
                        <input type="hidden" id="product_brand_thumbnail_id" class="fmfw_upload_image_id" name="product_brand_thumbnail_id" value="<?php echo esc_attr($thumbnail_id);?>" />
                        <button id="product_brand_thumbnail_upload" type="button" class="fmfw_upload_image_button button"><?php esc_html_e( 'Upload/Add image', 'fami-framework' ); ?></button>
                        <button id="product_brand_thumbnail_remove" type="button" class="fmfw_remove_image_button button"><?php esc_html_e( 'Remove image', 'fami-framework' ); ?></button>
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
        public function save_brand_taxonomy_fields( $term_id, $tt_id = '', $taxonomy = '' ) {
            $disable_fmfw_brand = apply_filters('fmfw_disable_product_brand', false);
            if (!current_theme_supports('fmfw-product-brand') || $disable_fmfw_brand){
                return;
            }
            if ( isset( $_POST['product_brand_thumbnail_id'] ) && self::$brands_taxonomy === $taxonomy ) {
                update_term_meta( $term_id, 'thumbnail_id', absint( $_POST['product_brand_thumbnail_id'] ) );
            }
        }
    
        /**
         * Add custom columns to brand taxonomy table
         *
         * @return void
         * @since 1.0.0
         */
        public function init_brand_taxonomy_columns() {
            $disable_fmfw_brand = apply_filters('fmfw_disable_product_brand', false);
            if (!current_theme_supports('fmfw-product-brand') || $disable_fmfw_brand){
                return;
            }
            add_filter( 'manage_edit-' . self::$brands_taxonomy . '_columns', [$this, 'brand_taxonomy_columns'], 15 );
            add_filter( 'manage_' . self::$brands_taxonomy . '_custom_column', [$this, 'brand_taxonomy_column'], 15, 3 );
        }
    
        /**
         * Register custom columns for "Add Brand" taxonomy view
         *
         * @param $columns mixed Old columns
         *
         * @return mixed Filtered array of columns
         * @since 1.0.0
         */
        public function brand_taxonomy_columns( $columns ) {
            $new_columns          = array();
            if( isset( $columns['cb'] ) ) {
                $new_columns['cb'] = $columns['cb'];
                unset( $columns['cb'] );
            }
        
            $new_columns['thumb'] = esc_html__( 'Logo', 'fami-framework' );
        
            return array_merge( $new_columns, $columns );
        }
    
        /**
         * Prints custom columns for "Add Brand" taxonomy view
         *
         * @param $columns mixed Array of columns to print
         * @param $column string Id of current column
         * @param $id int id of term being printed
         *
         * @return string Output for the columns
         */
        public function brand_taxonomy_column( $columns, $column, $id ) {
        
            if ( 'thumb' == $column ) {
            
                $thumbnail_id = get_term_meta( $id, 'thumbnail_id', true );
            
                if ( $thumbnail_id ) {
                    $image = wp_get_attachment_thumb_url( $thumbnail_id );
                } else {
                    $image = wc_placeholder_img_src();
                }
            
                $image = str_replace( ' ', '%20', $image );
            
                $columns = '<img src="' . esc_url( $image ) . '" alt="' . esc_html__( 'Logo', 'fami-framework' ) . '" class="wp-post-image" height="48" width="48" />';
            
            }
        
            return $columns;
        }
        public function display_product_brand_list( ){
            $disable_fmfw_brand = apply_filters('fmfw_disable_product_brand', false);
            if (!current_theme_supports('fmfw-product-brand') || $disable_fmfw_brand){
                return;
            }
            global  $product;
    
            $terms = get_the_terms( $product->get_id(), 'product-brand' );
            if( is_wp_error( $terms )  || empty($terms)){
                return '';
            }
            ?>
            <div class="product-brands">
                <label><?php esc_html_e('Brand:','fami-framework');?></label>
                <ul class="list">
                    <?php
                        foreach ( $terms as $term ) {
                            $link = get_term_link( $term, 'product-brand' );
                            ?>
                            <li>
                                <a href="<?php echo esc_url($link)?>">
                                    <?php
                                        $thumbnail_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
                                        $image = $thumbnail_id ? wp_get_attachment_thumb_url( $thumbnail_id ) : wc_placeholder_img_src();
                                    ?>
                                    <img src="<?php echo esc_url($image); ?>" width="60px" height="60px" />
                                </a>
                            </li>
                            <?php
                        }
                    ?>
                </ul>
            </div>
            <?php
        }
        
        public function related_brand(){
            $disable_fmfw_brand = apply_filters('fmfw_disable_product_brand', false);
            if (!current_theme_supports('fmfw-product-brand') || $disable_fmfw_brand){
                return;
            }
            global  $product;
            $terms = get_the_terms( $product->get_id(), 'product-brand' );
            
            if( is_wp_error( $terms )  || empty($terms)){
                return '';
            }
            $taxonomy ='product-brand';
            $term_ids = wp_get_post_terms( get_the_id(), $taxonomy, array('fields' => 'ids') );
            
            $args = array(
                'post_type'             => 'product',
                'post_status'           => 'publish',
                'ignore_sticky_posts'   => 1,
                'posts_per_page'        => 10, // Limit: two products
                'post__not_in'          => array( $product->get_id() ), // Excluding current product
                'tax_query'             => array(
                        array(
                            'taxonomy'      => $taxonomy,
                            'field'         => 'term_id', // can be 'term_id', 'slug' or 'name'
                            'terms'         => $term_ids,
                        )
                )
            );
            $query =new WP_Query($args);
            if ( $query->have_posts()) {
                $product_html = apply_filters('fmfw-brand-related-products-html', '', $query);
                if (empty($product_html)) {
                    wc_setup_loop(
                        array(
                            'columns' => 4,
                            'columns_tablet' => 3,
                            'columns_mobile' => 2,
                        )
                    );
                    $product_html = sprintf('<h2>%s</h2>',__('Brand Related Products','fami-framework'));
                    $product_html .= '<ul class="products">';
                    ob_start();
                    while ($query->have_posts()) {
                        $query->the_post();
                        wc_get_template_part('content', 'product');
                    }
                    $product_html .= ob_get_clean() . '</ul>';
                    wp_reset_postdata();
                }
                echo $product_html;
            }
        }
    }
}

Fmfw_Product_Brand::instance();