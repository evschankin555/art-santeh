<?php
/*
 * Plugin Name: Fami Template Kits
 * Plugin URI: https://familab.net/
 * Description: Fami Elementor Addons & Templates Library.
 * Author: Familab
 * Version: 1.0.9
 * Author URI: https://familab.net/
 * Text Domain: fami-templatekits
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // disable direct access
}

if ( ! defined( 'FAMI_TPL_URL' ) ) {
    define('FAMI_TPL_URL', plugin_dir_url(__FILE__));
}
if ( ! defined( 'FAMI_TPL_DIR' ) ) {
    define('FAMI_TPL_DIR', dirname(__FILE__));
}
if ( ! defined( 'FAMI_TPL_VER' ) ) {
    define('FAMI_TPL_VER', '1.0.9');
}
if ( ! defined( 'FAMI_TPL_PATH' ) ) {
    define('FAMI_TPL_PATH', plugin_basename( __FILE__ ));
}

require_once 'fmtpl-functions.php';
if (!class_exists('Fami_TemplateKits')){
    class Fami_TemplateKits
    {
        /**
         * Current theme template
         *
         * @var String
         */
        public $template;

        /**
         * Instance of Elemenntor Frontend class.
         *
         * @var \Elementor\Frontend()
         */
        private static $elementor_instance;

        private static $_instance = null;

        private $template_type;

        public static function instance()
        {
            if (!isset(self::$_instance)) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }

        public function __construct()
        {
            register_activation_hook(__FILE__,[$this, 'fmtpl_activate']);

            $this->load_textdomain();

            require_once FAMI_TPL_DIR . '/inc/target-rule/fmtpl-target-rules-fields.php';
            require_once FAMI_TPL_DIR . '/templates/fmtpl-default-compat.php';

            add_action('init', [$this, 'init_custom_post_type']);
            add_action( 'init', [$this,'fmtpl_flush_rewrite_rules_maybe'], 20 );
            add_action('add_meta_boxes', [$this, 'register_metabox']);
            add_action('save_post_fmtpl_blocks',[$this,'save_meta_data']);
            add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
            add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_scripts' ] );
            add_filter( 'manage_fmtpl_blocks_posts_columns', [ $this, 'set_custom_columns' ] );
            add_action( 'manage_fmtpl_blocks_posts_custom_column', [ $this, 'render_custom_columns' ], 10, 2 );
            add_filter( 'body_class', [ $this, 'body_class' ] );
            add_shortcode( 'fmtpl_block', [ $this, 'render_shortcode_template' ] );
            add_action('wp_loaded',[$this,'fmtpl_wp_load']);

            if (fmtpl_is_elementor_activated() && is_callable('Elementor\Plugin::instance')) {
                require_once FAMI_TPL_DIR.'/inc/elementor/init.php';
                $this->template = get_template();
                self::$elementor_instance = Elementor\Plugin::instance();
                add_filter('single_template', [$this, 'load_canvas_template']);
            }
            $this->template_type = [
                'custom' => __('Custom Block', 'fami-templatekits'),
                'type_header' => __('Header', 'fami-templatekits'),
                'type_footer' => __('Footer', 'fami-templatekits')
            ];
        }

        public function fmtpl_wp_load(){
            $languages = function_exists( 'icl_get_languages' ) ? icl_get_languages() : array();
            $languages = apply_filters( 'wpml_active_languages', $languages );
            $GLOBALS['fmtpl_languages'] =  $languages;
        }

        public function load_textdomain()
        {
            load_plugin_textdomain('fami-templatekits');
        }

        public function fmtpl_activate(){
            if ( ! get_option( 'fmtpl_flush_rewrite_rules_flag' ) ) {
                add_option( 'fmtpl_flush_rewrite_rules_flag', true );
            }
        }

        public function fmtpl_flush_rewrite_rules_maybe() {
            if ( get_option( 'fmtpl_flush_rewrite_rules_flag' ) ) {
                flush_rewrite_rules();
                delete_option( 'fmtpl_flush_rewrite_rules_flag' );
            }
        }

        public function init_custom_post_type()
        {
            $support = ['thumbnail', 'editor', 'title'];
            if (fmtpl_is_elementor_activated()) {
                $support = ['title', 'thumbnail', 'elementor'];
            }
            $labels = [
                'name' => __('Custom Blocks Template', 'fami-templatekits'),
                'singular_name' => __('Custom Block', 'fami-templatekits'),
                'menu_name' => __('Custom Blocks', 'fami-templatekits'),
                'add_new' => __('Add New Block', 'fami-templatekits'),
                'new_item' => __('New Custom Blocks Template', 'fami-templatekits'),
                'edit_item' => __('Edit Custom Blocks Template', 'fami-templatekits'),
                'view_item' => __('View Custom Blocks Template', 'fami-templatekits'),
                'all_items' => __('All Custom Blocks', 'fami-templatekits'),
                'search_items' => __('Search Custom Blocks Templates', 'fami-templatekits'),
                'parent_item_colon' => __('Parent Custom Blocks Templates:', 'fami-templatekits'),
                'not_found' => __('No Custom Blocks Templates found.', 'fami-templatekits'),
                'not_found_in_trash' => __('No Custom Blocks Templates found in Trash.', 'fami-templatekits'),
            ];

            register_post_type('fmtpl_blocks',
                array(
                    'labels' => $labels,
                    'public' => true,
                    'has_archive' => false,
                    'show_in_menu' => true,
                    'supports' => $support,
                    'show_in_nav_menus' => false,
                    'exclude_from_search' => true,
                    'rewrite' => false,
                    'show_ui' => true,
                    'query_var' => true,
                    'capability_type' => 'post',
                    'hierarchical' => false,
                    'menu_position' => null,
                    'menu_icon' => 'dashicons-editor-kitchensink',
                )
            );

        }

        public function body_class( $classes ) {
            if ( fmtpl_header_enabled() ) {
                $classes[] = 'fmtpl-header';
            }

            if ( fmtpl_footer_enabled() ) {
                $classes[] = 'fmtpl-footer';
            }

            $classes[] = 'fmtpl-template-' . $this->template;
            $classes[] = 'fmtpl-stylesheet-' . get_stylesheet();

            return $classes;
        }

        public function load_canvas_template($single_template)
        {
            global $post;

            if ('fmtpl_blocks' == $post->post_type) {
                $elementor_2_0_canvas = ELEMENTOR_PATH . '/modules/page-templates/templates/canvas.php';

                if (file_exists($elementor_2_0_canvas)) {
                    return $elementor_2_0_canvas;
                } else {
                    return ELEMENTOR_PATH . '/includes/page-templates/canvas.php';
                }
            }

            return $single_template;
        }

        public function enqueue_scripts() {
            if ( class_exists( '\Elementor\Plugin' ) ) {
                $elementor = \Elementor\Plugin::instance();
                $elementor->frontend->enqueue_styles();
            }
            if ( class_exists( '\ElementorPro\Plugin' ) ) {
                $elementor_pro = \ElementorPro\Plugin::instance();
                $elementor_pro->enqueue_styles();
            }
            if ( fmtpl_header_enabled() ) {
                if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
                    $css_file = new \Elementor\Core\Files\CSS\Post( fmtpl_get_header_id() );
                    $css_file->enqueue();
                } elseif ( class_exists( '\Elementor\Post_CSS_File' ) ) {
                    $css_file = new \Elementor\Post_CSS_File( fmtpl_get_header_id() );
                    $css_file->enqueue();
                }
            }

            if ( fmtpl_footer_enabled() ) {
                if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
                    $css_file = new \Elementor\Core\Files\CSS\Post( fmtpl_get_footer_id() );
                    $css_file->enqueue();
                } elseif ( class_exists( '\Elementor\Post_CSS_File' ) ) {
                    $css_file = new \Elementor\Post_CSS_File( fmtpl_get_footer_id() );
                    $css_file->enqueue();
                }
            }
            wp_enqueue_style('bootstrap',FAMI_TPL_URL . 'assets/vendors/bootstrap/css/bootstrap.min.css',[],'4.3.1');
            wp_enqueue_script('bootstrap',FAMI_TPL_URL . 'assets/vendors/bootstrap/js/bootstrap.min.js',['jquery'],'4.3.1',true);
        }

        public function enqueue_admin_scripts() {
            global $pagenow;
            $screen = get_current_screen();
            if ( ( 'fmtpl_blocks' == $screen->id && ( 'post.php' == $pagenow || 'post-new.php' == $pagenow ) ) || ( 'edit.php' == $pagenow && 'edit-fmtpl_blocks' == $screen->id ) ) {
                wp_enqueue_style( 'fmtpl-admin-style', FAMI_TPL_URL . '/assets/css/fmtpl-admin.css', [], FAMI_TPL_VER );
                wp_enqueue_script( 'fmtpl-admin-script', FAMI_TPL_URL . '/assets/js/fmtpl-admin.js', [], FAMI_TPL_VER );
            }
        }

        public function register_metabox()
        {
            add_meta_box(
                'fmtpl-meta-box',
                __('Custom Block Options', 'fami_templatekits'),
                [
                    $this,
                    'metabox_render',
                ],
                'fmtpl_blocks',
                'normal',
                'high'
            );
        }

        public function metabox_render($post)
        {
            $values = get_post_custom($post->ID);
            $template_type = isset($values['fmtpl_template_type']) ? esc_attr($values['fmtpl_template_type'][0]) : '';

            // We'll use this nonce field later on when saving.
            wp_nonce_field('fmtpl_meta_nounce', 'fmtpl_meta_nounce');
            ?>
            <div class="fmtpl-meta-option-wrap">
                <div class="fmtpl-field">
                    <div class="fmtpl-field-heading">
                        <label for="fmtpl_template_type"><?php _e('Type of Template', 'fami-templatekits'); ?></label>
                    </div>
                    <div class="fmtpl-field-content">
                        <select name="fmtpl_template_type" id="fmtpl_template_type">
                            <option value="custom" <?php selected($template_type, 'custom'); ?>><?php echo $this->template_type['custom']; ?></option>
                            <option value="type_header" <?php selected($template_type, 'type_header'); ?>><?php echo $this->template_type['type_header']; ?></option>
                            <option value="type_footer" <?php selected($template_type, 'type_footer'); ?>><?php echo $this->template_type['type_footer']; ?></option>
                        </select>
                    </div>
                </div>
                <div id="fmtpl_condition_group" <?php if (!in_array($template_type,['type_header','type_footer'])) echo('class="fmtpl-hidden"');?>>
                    <?php $this->display_rules_tab(); ?>
                </div>
            </div>
            <?php
        }

        public function display_rules_tab()
        {
            // Load Target Rule assets.
            Fmtpl_Target_Rules_Fields::get_instance()->admin_styles();
            $include_locations = get_post_meta(get_the_id(), 'fmtpl_target_include_locations', true);
            $exclude_locations = get_post_meta(get_the_id(), 'fmtpl_target_exclude_locations', true);
            $users = get_post_meta(get_the_id(), 'fmtpl_target_user_roles', true);
            ?>
            <div class="fmtpl-field">
                <div class="fmtpl-field-heading">
                    <label><?php esc_html_e('Display On', 'fami-templatekits'); ?></label>
                </div>
                <div class="fmtpl-field-content">
                    <?php
                    Fmtpl_Target_Rules_Fields::target_rule_settings_field(
                        'tmtpl-rules-location',
                        [
                            'title' => __('Display Rules', 'fami-templatekits'),
                            'value' => '[{"type":"basic-global","specific":null}]',
                            'tags' => 'site,enable,target,pages',
                            'rule_type' => 'display',
                            'add_rule_label' => __('Add Display Rule', 'fami-templatekits'),
                        ],
                        $include_locations
                    );
                    ?>
                </div>
            </div>
            <div class="fmtpl-field">
                <div class="fmtpl-field-heading">
                    <label><?php esc_html_e('Do Not Display On', 'fami-templatekits'); ?></label>
                </div>
                <div class="fmtpl-field-content">
                    <?php
                    Fmtpl_Target_Rules_Fields::target_rule_settings_field(
                        'tmtpl-rules-exclusion',
                        [
                            'title' => __('Exclude On', 'fami-templatekits'),
                            'value' => '[]',
                            'tags' => 'site,enable,target,pages',
                            'add_rule_label' => __('Add Exclusion Rule', 'fami-templatekits'),
                            'rule_type' => 'exclude',
                        ],
                        $exclude_locations
                    );
                    ?>
                </div>
            </div>
            <div class="fmtpl-field">
                <div class="fmtpl-field-heading">
                    <label><?php esc_html_e('User Roles', 'fami-templatekits'); ?></label>
                </div>
                <div class="fmtpl-field-content">
                    <?php
                    Fmtpl_Target_Rules_Fields::target_user_role_settings_field(
                        'tmtpl-rules-users',
                        [
                            'title' => __('Users', 'fami-templatekits'),
                            'value' => '[]',
                            'tags' => 'site,enable,target,pages',
                            'add_rule_label' => __('Add User Rule', 'fami-templatekits'),
                        ],
                        $users
                    );
                    ?>
                </div>
            </div>
            <?php
        }
        public function save_meta_data($post_id) {
            if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
                return;
            }

            // if our nonce isn't there, or we can't verify it, bail.
            if ( ! isset( $_POST['fmtpl_meta_nounce'] ) || ! wp_verify_nonce( $_POST['fmtpl_meta_nounce'], 'fmtpl_meta_nounce' ) ) {
                return;
            }
            // if our current user can't edit this post, bail.
            if ( ! current_user_can( 'edit_posts' ) ) {
                return;
            }

            $target_locations = Fmtpl_Target_Rules_Fields::get_format_rule_value( $_POST, 'tmtpl-rules-location' );
            $target_exclusion = Fmtpl_Target_Rules_Fields::get_format_rule_value( $_POST, 'tmtpl-rules-exclusion' );
            $target_users     = [];

            if ( isset( $_POST['tmtpl-rules-users'] ) ) {
                $target_users = array_map( 'sanitize_text_field', $_POST['tmtpl-rules-users'] );
            }

            update_post_meta( $post_id, 'fmtpl_target_include_locations', $target_locations );
            update_post_meta( $post_id, 'fmtpl_target_exclude_locations', $target_exclusion );
            update_post_meta( $post_id, 'fmtpl_target_user_roles', $target_users );

            if ( isset( $_POST['fmtpl_template_type'] ) ) {
                update_post_meta( $post_id, 'fmtpl_template_type', esc_attr( $_POST['fmtpl_template_type'] ) );
            } else {
                update_post_meta( $post_id, 'fmtpl_template_type', 'custom' );
            }
        }
        public function set_custom_columns( $columns ) {
            $date_column = $columns['date'];
            unset( $columns['date'] );
            $columns['type'] = __( 'Type', 'fami-templatekits' );
            $columns['shortcode'] = __( 'Shortcode', 'fami-templatekits' );
            $columns['display_rules'] = __( 'Display Rules', 'fami-templatekits' );
            $columns['date']      = $date_column;
            return $columns;
        }
        public function render_custom_columns( $column, $post_id ) {
            switch ( $column ) {
                case 'shortcode':
                    ob_start();
                    ?>
                    <span class="hfe-shortcode-col-wrap">
                        <input type="text" onfocus="this.select();" readonly="readonly" value="[fmtpl_block id='<?php echo esc_attr( $post_id ); ?>']" class="hfe-large-text code">
                    </span>
                    <?php
                    ob_get_contents();
                    break;
                case 'display_rules':
                    $locations = get_post_meta( $post_id, 'fmtpl_target_include_locations', true );
                    if ( ! empty( $locations ) ) {
                        echo '<div class="advanced-headers-location-wrap">';
                        echo '<strong>Display: </strong>';
                        $this->column_display_location_rules( $locations );
                        echo '</div>';
                    }

                    $locations = get_post_meta( $post_id, 'fmtpl_target_exclude_locations', true );
                    if ( ! empty( $locations ) ) {
                        echo '<div class="advanced-headers-exclusion-wrap">';
                        echo '<strong>Exclusion: </strong>';
                        $this->column_display_location_rules( $locations );
                        echo '</div>';
                    }

                    $users = get_post_meta( $post_id, 'fmtpl_target_user_roles', true );
                    if ( isset( $users ) && is_array( $users ) ) {
                        if ( isset( $users[0] ) && ! empty( $users[0] ) ) {
                            $user_label = [];
                            foreach ( $users as $user ) {
                                $user_label[] = Fmtpl_Target_Rules_Fields::get_user_by_key( $user );
                            }
                            echo '<div class="ast-advanced-headers-users-wrap">';
                            echo '<strong>Users: </strong>';
                            echo join( ', ', $user_label );
                            echo '</div>';
                        }
                    }
                    break;
                case 'type':
                    $type = get_post_meta( $post_id, 'fmtpl_template_type', true );
                    if ( ! empty( $type ) && isset($this->template_type[$type])) {
                        echo $this->template_type[$type];
                    }
                    break;
            }
        }
        public function column_display_location_rules( $locations ) {
            $location_label = [];
            $index          = array_search( 'specifics', $locations['rule'] );
            if ( false !== $index && ! empty( $index ) ) {
                unset( $locations['rule'][ $index ] );
            }
            if ( isset( $locations['rule'] ) && is_array( $locations['rule'] ) ) {
                foreach ( $locations['rule'] as $location ) {
                    $location_label[] = Fmtpl_Target_Rules_Fields::get_location_by_key( $location );
                }
            }
            if ( isset( $locations['specific'] ) && is_array( $locations['specific'] ) ) {
                foreach ( $locations['specific'] as $location ) {
                    $location_label[] = Fmtpl_Target_Rules_Fields::get_location_by_key( $location );
                }
            }

            echo join( ', ', $location_label );
        }
        public function render_shortcode_template( $atts ) {
            $atts = shortcode_atts(
                [
                    'id' => '',
                ],
                $atts,
                'fmtpl_block'
            );

            $id = ! empty( $atts['id'] ) ? $atts['id'] : '';

            if ( empty( $id ) ) {
                return '';
            }
            if (fmtpl_is_elementor_activated()){
                if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
                    $css_file = new \Elementor\Core\Files\CSS\Post( $id );
                } elseif ( class_exists( '\Elementor\Post_CSS_File' ) ) {
                    // Load elementor styles.
                    $css_file = new \Elementor\Post_CSS_File( $id );
                }
                $css_file->enqueue();

                return self::$elementor_instance->frontend->get_builder_content_for_display( $id );
            } else {
                return do_shortcode(get_post_field('post_content', $id));
            }

        }
        public static function get_settings( $setting = '', $default = '' ) {
            if ( 'type_header' == $setting || 'type_footer' == $setting || 'type_before_footer' == $setting ) {
                $templates = self::get_template_id( $setting );

                $template = ! is_array( $templates ) ? $templates : $templates[0];

                return $template;
            }
        }


        public static function get_template_id( $type ) {
            $option = [
                'location'  => 'fmtpl_target_include_locations',
                'exclusion' => 'fmtpl_target_exclude_locations',
                'users'     => 'fmtpl_target_user_roles',
            ];

            $fmtpl_templates = Fmtpl_Target_Rules_Fields::get_instance()->get_posts_by_conditions( 'fmtpl_blocks', $option );

            foreach ( $fmtpl_templates as $template ) {
                if ( get_post_meta( absint( $template['id'] ), 'fmtpl_template_type', true ) === $type ) {
                    return $template['id'];
                }
            }

            return '';
        }

        public static function get_header_content() {
            $header_id = fmtpl_get_header_id();
            if (fmtpl_is_elementor_activated() && self::is_built_with_elementor( $header_id )){
                echo self::$elementor_instance->frontend->get_builder_content_for_display(  $header_id );
            } else {
                echo do_shortcode(get_post_field('post_content', $header_id));
            }

        }


        public static function get_footer_content() {
            $footer_id = fmtpl_get_footer_id();
            if (fmtpl_is_elementor_activated() && self::is_built_with_elementor($footer_id)){
                echo self::$elementor_instance->frontend->get_builder_content_for_display(  $footer_id );
            } else {
                echo do_shortcode(get_post_field('post_content', $footer_id));
            }
        }
        public static function is_built_with_elementor($post_id) {
            if (version_compare( ELEMENTOR_VERSION, '3.2.0', '<' ) ) {
                return self::$elementor_instance->db->is_built_with_elementor($post_id);
            }
            return self::$elementor_instance->documents->get( $post_id )->is_built_with_elementor();
        }
    }
}
function fmtpl_initialize(){
    Fami_TemplateKits::instance();
}
add_action('plugins_loaded','fmtpl_initialize');

