<?php
if (!class_exists('Fmfw_Custom_fonts')){
    class Fmfw_Custom_fonts{
        private static $_instance;
        public static function instance() {
            if ( ! isset( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }
        protected static function default_args( $fonts ) {
            return wp_parse_args(
                $fonts,
                array(
                    'font_woff_2'  => '',
                    'font_woff'    => '',
                    'font_ttf'     => '',
                    'font_svg'     => '',
                    'font_eot'     => '',
                    'font_otf'     => '',
                    'font-display' => 'swap',
                )
            );
        }
        public function __construct() {
            add_action('init', [$this, 'init_custom_fonts']);
            add_action( 'admin_menu', [ $this, 'register_admin_menu' ], 30 );
            add_action( 'admin_head', array( $this, 'fmfw_custom_fonts_head' ) );
            add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
            add_filter( 'upload_mimes', array( $this, 'add_fonts_to_allowed_mimes' ) );
            add_filter( 'wp_check_filetype_and_ext', array( $this, 'update_mime_types' ), 10, 3 );
            add_filter( 'manage_edit-fmfw_custom_fonts_columns', array( $this, 'manage_columns' ) );
            add_action( 'edited_fmfw_custom_fonts', array( $this, 'save_metadata' ) );
            add_action( 'create_fmfw_custom_fonts', array( $this, 'save_metadata' ) );
            add_action( 'delete_term', array( $this, 'delete_custom_fonts_fallback' ), 10, 5 );
            add_action( 'wp_head', array( $this, 'add_style' ) );
            if ( is_admin() ) {
                add_action( 'enqueue_block_assets', array( $this, 'add_style' ) );
            }
            add_filter( 'elementor/fonts/groups', array( $this, 'elementor_group' ) );
            add_filter( 'elementor/fonts/additional_fonts', array( $this, 'add_elementor_fonts' ) );
            add_filter( 'kirki/fonts/standard_fonts', array( $this, 'add_kirki_custom_fonts' ), 20 );
        }
        public function fmfw_custom_fonts_head() {
            if ( get_current_screen()->id != 'edit-fmfw_custom_fonts') {
                return;
            }
            ?><script>jQuery( document ).ready( function( $ ) {
                    var $wrapper = $( '#addtag, #edittag' );
                    $wrapper.find( 'tr.form-field.term-name-wrap p, div.form-field.term-name-wrap > p' ).text( '<?php esc_html_e( 'The name of the font as it appears in the customizer options.', 'fami-framework' ); ?>' );
                } );</script>
            <?php
        }
        public function enqueue_admin_scripts() {
            if ( get_current_screen()->id != 'edit-fmfw_custom_fonts') {
                return;
            }
            wp_enqueue_style('fmfw-custom-fonts-css', FAMI_FRAMEWORK_PLUGIN_URL . '/assets/css/admin_custom_fonts.css', array(), FMFW_VERSION);
            wp_enqueue_media();
            wp_enqueue_script('fmfw-custom-fonts-js', FAMI_FRAMEWORK_PLUGIN_URL . '/assets/js/admin_custom_fonts.js', array(), FMFW_VERSION, true);
        }
        public function init_custom_fonts()
        {
            $labels = array(
                'name'              => __( 'Custom Fonts', 'fami-framework' ),
                'singular_name'     => __( 'Font', 'fami-framework' ),
                'menu_name'         => _x( 'Custom Fonts', 'Admin menu name', 'fami-framework' ),
                'search_items'      => __( 'Search Fonts', 'fami-framework' ),
                'all_items'         => __( 'All Fonts', 'fami-framework' ),
                'parent_item'       => __( 'Parent Font', 'fami-framework' ),
                'parent_item_colon' => __( 'Parent Font:', 'fami-framework' ),
                'edit_item'         => __( 'Edit Font', 'fami-framework' ),
                'update_item'       => __( 'Update Font', 'fami-framework' ),
                'add_new_item'      => __( 'Add New Font', 'fami-framework' ),
                'new_item_name'     => __( 'New Font Name', 'fami-framework' ),
                'not_found'         => __( 'No fonts found', 'fami-framework' ),
            );

            $args = array(
                'hierarchical'      => false,
                'labels'            => $labels,
                'public'            => false,
                'show_in_nav_menus' => false,
                'show_ui'           => true,
                'capabilities'      => ['edit_theme_options'],
                'query_var'         => false,
                'rewrite'           => false,
            );

            register_taxonomy(
                'fmfw_custom_fonts',
                array(),
                $args
            );
            add_action( 'fmfw_custom_fonts_add_form_fields', array( $this, 'add_fmfw_custom_fonts_taxonomy_fields' ), 15 );
            add_action( 'fmfw_custom_fonts_edit_form_fields', array( $this, 'edit_fmfw_custom_fonts_taxonomy_fields' ), 15 );
        }
        public function register_admin_menu() {
            add_submenu_page(
                'fami-dashboard',
                __( 'Custom Fonts', 'fami-framework' ),
                __( 'Custom Fonts', 'fami-framework' ),
                'edit_theme_options',
                'edit-tags.php?taxonomy=fmfw_custom_fonts'
            );
        }

        public function add_fonts_to_allowed_mimes( $mimes ) {
            $mimes['woff']  = 'application/x-font-woff';
            $mimes['woff2'] = 'application/x-font-woff2';
            $mimes['ttf']   = 'application/x-font-ttf';
            $mimes['svg']   = 'image/svg+xml';
            $mimes['eot']   = 'application/vnd.ms-fontobject';
            $mimes['otf']   = 'font/otf';

            return $mimes;
        }
        public function update_mime_types( $defaults, $file, $filename ) {
            if ( 'ttf' === pathinfo( $filename, PATHINFO_EXTENSION ) ) {
                $defaults['type'] = 'application/x-font-ttf';
                $defaults['ext']  = 'ttf';
            }

            if ( 'otf' === pathinfo( $filename, PATHINFO_EXTENSION ) ) {
                $defaults['type'] = 'application/x-font-otf';
                $defaults['ext']  = 'otf';
            }

            return $defaults;
        }
        public function manage_columns( $columns ) {

            $screen = get_current_screen();
            // If current screen is add new custom fonts screen.
            if ( isset( $screen->base ) && 'edit-tags' == $screen->base ) {

                $old_columns = $columns;
                $columns     = array(
                    'cb'   => $old_columns['cb'],
                    'name' => $old_columns['name'],
                );

            }
            return $columns;
        }
        public function save_metadata( $term_id ) {
            if ( isset( $_POST[ 'fmfw_custom_fonts' ] ) ) {// phpcs:ignore WordPress.Security.NonceVerification.Missing
                $value = array_map( 'esc_attr', $_POST[ 'fmfw_custom_fonts'] ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
                $this->update_font_links( $value, $term_id );
            }
        }

        public function add_fmfw_custom_fonts_taxonomy_fields() {
            $this->font_file_new_field( 'font_woff_2', __( 'Font .woff2', 'fami-framework' ), __( 'Upload the font\'s woff2 file or enter the URL.', 'fami-framework' ) );
            $this->font_file_new_field( 'font_woff', __( 'Font .woff', 'fami-framework' ), __( 'Upload the font\'s woff file or enter the URL.', 'fami-framework' ) );
            $this->font_file_new_field( 'font_ttf', __( 'Font .ttf', 'fami-framework' ), __( 'Upload the font\'s ttf file or enter the URL.', 'fami-framework' ) );
            $this->font_file_new_field( 'font_eot', __( 'Font .eot', 'fami-framework' ), __( 'Upload the font\'s eot file or enter the URL.', 'fami-framework' ) );
            $this->font_file_new_field( 'font_svg', __( 'Font .svg', 'fami-framework' ), __( 'Upload the font\'s svg file or enter the URL.', 'fami-framework' ) );
            $this->font_file_new_field( 'font_otf', __( 'Font .otf', 'fami-framework' ), __( 'Upload the font\'s otf file or enter the URL.', 'fami-framework' ) );

            $this->select_new_field(
                'font-display',
                __( 'Font Display', 'fami-framework' ),
                __( 'Select font-display property for this font', 'fami-framework' ),
                array(
                    'auto'     => 'auto',
                    'block'    => 'block',
                    'swap'     => 'swap',
                    'fallback' => 'fallback',
                    'optional' => 'optional',
                )
            );
        }

        protected function font_file_new_field( $id, $title, $description, $value = '' ) {
            ?>
            <div class="fmfw-custom-fonts-file-wrap form-field term-<?php echo esc_attr( $id ); ?>-wrap" >

                <label for="font-<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $title ); ?></label>
                <input type="text" id="font-<?php echo esc_attr( $id ); ?>" class="fmfw-custom-fonts-link <?php echo esc_attr( $id ); ?>" name="fmfw_custom_fonts[<?php echo esc_attr( $id ); ?>]" value="<?php echo esc_attr( $value ); ?>" />
                <a href="#" class="fmfw-custom-fonts-upload button" data-upload-type="<?php echo esc_attr( $id ); ?>"><?php esc_html_e( 'Upload', 'fami-framework' ); ?></a>
                <p><?php echo esc_html( $description ); ?></p>
            </div>
            <?php
        }
        protected function select_new_field( $id, $title, $description, $select_fields ) {
            ?>
            <div class="fmfw-custom-fonts-file-wrap form-field term-<?php echo esc_attr( $id ); ?>-wrap" >
                <label for="font-<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $title ); ?></label>
                <select type="select" id="font-<?php echo esc_attr( $id ); ?>" class="fmfw-custom-font-select-field <?php echo esc_attr( $id ); ?>" name="fmfw_custom_fonts[<?php echo esc_attr( $id ); ?>]" />
                <?php
                foreach ( $select_fields as $key => $value ) {
                    ?>
                    <option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $value ); ?></option>;
                <?php } ?>
                </select>
                <p><?php echo esc_html( $description ); ?></p>
            </div>
            <?php
        }

        public function edit_fmfw_custom_fonts_taxonomy_fields($term){
            $data = $this->get_font_links( $term->term_id );
            $this->font_file_edit_field( 'font_woff_2', __( 'Font .woff2', 'fami-framework' ), $data['font_woff_2'], __( 'Upload the font\'s woff2 file or enter the URL.', 'fami-framework' ) );
            $this->font_file_edit_field( 'font_woff', __( 'Font .woff', 'fami-framework' ), $data['font_woff'], __( 'Upload the font\'s woff file or enter the URL.', 'fami-framework' ) );
            $this->font_file_edit_field( 'font_ttf', __( 'Font .ttf', 'fami-framework' ), $data['font_ttf'], __( 'Upload the font\'s ttf file or enter the URL.', 'fami-framework' ) );
            $this->font_file_edit_field( 'font_eot', __( 'Font .eot', 'fami-framework' ), $data['font_eot'], __( 'Upload the font\'s eot file or enter the URL.', 'fami-framework' ) );
            $this->font_file_edit_field( 'font_svg', __( 'Font .svg', 'fami-framework' ), $data['font_svg'], __( 'Upload the font\'s svg file or enter the URL.', 'fami-framework' ) );
            $this->font_file_edit_field( 'font_otf', __( 'Font .otf', 'fami-framework' ), $data['font_otf'], __( 'Upload the font\'s otf file or enter the URL.', 'fami-framework' ) );

            $this->select_edit_field(
                'font-display',
                __( 'Font Display', 'fami-framework' ),
                $data['font-display'],
                __( 'Select font-display property for this font', 'fami-framework' ),
                array(
                    'auto'     => 'Auto',
                    'block'    => 'Block',
                    'swap'     => 'Swap',
                    'fallback' => 'Fallback',
                    'optional' => 'Optional',
                )
            );
        }
        private function select_edit_field( $id, $title, $saved_val = '', $description, $select_fields ) {
            ?>
            <tr class="fmfw-custom-fonts-file-wrap form-field term-<?php echo esc_attr( $id ); ?>-wrap ">
                <th scope="row">
                    <label for="metadata-<?php echo esc_attr( $id ); ?>">
                        <?php echo esc_html( $title ); ?>
                    </label>
                </th>
                <td>
                    <select type="select" id="font-<?php echo esc_attr( $id ); ?>" class="fmfw-custom-font-select-field <?php echo esc_attr( $id ); ?>" name="fmfw_custom_fonts[<?php echo esc_attr( $id ); ?>]" />
                    <?php
                    foreach ( $select_fields as $key => $value ) {
                        ?>
                        <option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $saved_val ); ?>><?php echo esc_html( $value ); ?></option>;
                    <?php } ?>
                    </select>
                    <p><?php echo esc_html( $description ); ?></p>
                </td>
            </tr>
            <?php
        }
        protected function font_file_edit_field( $id, $title, $value = '', $description ) {
            ?>
            <tr class="fmfw-custom-fonts-file-wrap form-field term-<?php echo esc_attr( $id ); ?>-wrap ">
                <th scope="row">
                    <label for="metadata-<?php echo esc_attr( $id ); ?>">
                        <?php echo esc_html( $title ); ?>
                    </label>
                </th>
                <td>
                    <input id="metadata-<?php echo esc_attr( $id ); ?>" type="text" class="fmfw-custom-fonts-link <?php echo esc_attr( $id ); ?>" name="fmfw_custom_fonts[<?php echo esc_attr( $id ); ?>]" value="<?php echo esc_attr( $value ); ?>" />
                    <a href="#" class="fmfw-custom-fonts-upload button" data-upload-type="<?php echo esc_attr( $id ); ?>"><?php esc_html_e( 'Upload', 'fami-framework' ); ?></a>
                    <p><?php echo esc_html( $description ); ?></p>
                </td>
            </tr>
            <?php
        }
        public function get_font_links( $term_id ) {
            $links = get_option( "taxonomy_fmfw_custom_fonts_{$term_id}", array() );
            return $this->default_args( $links );
        }
        public function update_font_links( $posted, $term_id ) {

            $links = $this->get_font_links( $term_id );
            foreach ( array_keys( $links ) as $key ) {
                if ( isset( $posted[ $key ] ) ) {
                    $links[ $key ] = $posted[ $key ];
                } else {
                    $links[ $key ] = '';
                }
            }
            update_option( "taxonomy_fmfw_custom_fonts_{$term_id}", $links );
        }
        public function get_fonts() {
            global $fmfw_fonts;
            if ( empty( $fmfw_fonts ) ) {
                $fmfw_fonts = array();

                $terms = get_terms(
                    'fmfw_custom_fonts',
                    array(
                        'hide_empty' => false,
                    )
                );

                if ( ! empty( $terms ) ) {
                    foreach ( $terms as $term ) {
                        $fmfw_fonts[ $term->name ] = $this->get_font_links( $term->term_id );
                    }
                    $GLOBALS['fmfw_fonts'] = $fmfw_fonts;
                }
            }
            return $fmfw_fonts;
        }
        public function add_style() {
            $fonts = $this->get_fonts();
            $font_css = '';
            if ( ! empty( $fonts ) ) {
                foreach ( $fonts  as $load_font_name => $load_font ) {
                    $font_css .= $this->render_font_css( $load_font_name );
                }
                ?>
                <style type="text/css">
                    <?php echo wp_strip_all_tags( $font_css ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </style>
                <?php
            }
        }
        public function get_links_by_name( $name ) {
            $fonts = $this->get_fonts();
            $font_links = array();
            if ( ! empty( $fonts ) ) {
                foreach ( $fonts as $font_name => $font_link ) {
                    if ( $font_name == $name ) {
                        $font_links[ $font_name ] = $font_link;
                    }
                }
            }
            return $font_links;

        }
        private function render_font_css( $font ) {
            $fonts = $this->get_links_by_name( $font );
            foreach ( $fonts as $font => $links ) :
                $css  = '@font-face { font-family:' . esc_attr( $font ) . ';';
                $css .= 'src:';
                $arr  = array();
                if ( $links['font_woff_2'] ) {
                    $arr[] = 'url(' . esc_url( $links['font_woff_2'] ) . ") format('woff2')";
                }
                if ( $links['font_woff'] ) {
                    $arr[] = 'url(' . esc_url( $links['font_woff'] ) . ") format('woff')";
                }
                if ( $links['font_ttf'] ) {
                    $arr[] = 'url(' . esc_url( $links['font_ttf'] ) . ") format('truetype')";
                }
                if ( $links['font_otf'] ) {
                    $arr[] = 'url(' . esc_url( $links['font_otf'] ) . ") format('opentype')";
                }
                if ( $links['font_svg'] ) {
                    $arr[] = 'url(' . esc_url( $links['font_svg'] ) . '#' . esc_attr( strtolower( str_replace( ' ', '_', $font ) ) ) . ") format('svg')";
                }
                $css .= join( ', ', $arr );
                $css .= ';';
                $css .= 'font-display: ' . esc_attr( $links['font-display'] ) . ';';
                $css .= '}';
            endforeach;

            return $css;
        }
        public function elementor_group( $font_groups ) {
            $new_group[ 'fmfw_custom_fonts' ] = __( 'Custom', 'custom-fonts' );
            $font_groups                   = $new_group + $font_groups;

            return $font_groups;
        }
        public function add_elementor_fonts( $fonts ) {

            $all_fonts = $this->get_fonts();

            if ( ! empty( $all_fonts ) ) {
                foreach ( $all_fonts as $font_family_name => $fonts_url ) {
                    $fonts[ $font_family_name ] = 'fmfw_custom_fonts';
                }
            }

            return $fonts;
        }
        public function add_kirki_custom_fonts( $standard_fonts ){

            $my_custom_fonts = array();
            $all_fonts = $this->get_fonts();
            if ( ! empty( $all_fonts ) ) {
                foreach ( $all_fonts as $font_family_name => $fonts_url ) {
                    $my_custom_fonts[ $font_family_name ] = array(
                        'label' => $font_family_name,
                        'variants' => array('regular'),
                        'stack' => $font_family_name.', sans-serif',
                    );
                }
            }
            return array_merge_recursive( $my_custom_fonts, $standard_fonts );
        }
        public function delete_custom_fonts_fallback( $term, $tt_id, $taxonomy, $deleted_term, $object_ids ) {
            $mods = get_theme_mods();
            $mods_change = false;
            if (!empty($mods)) {
                foreach ($mods as $opion_key => $setting){
                    if (isset($setting['font-family']) && $setting['font-family'] == $deleted_term->name){
                        $mods[$opion_key]['font-family'] = 'inherit';
                        $mods_change = true;
                    }
                }
            }
            if ($mods_change){
                $theme = get_option( 'stylesheet' );
                update_option( "theme_mods_$theme", $mods );
                fmfw_remove_old_customize_file();
            }
        }
    }
}
Fmfw_Custom_fonts::instance();
