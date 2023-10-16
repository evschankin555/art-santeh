<?php


include_once FAMI_FRAMEWORK_PLUGIN_DIR.'/includes/megamenu/mega_menu_walker_edit.php';
/**
 * Class Mega_Menu_Edit
 *
 * Main class for adding mega setting modal
 */
class Fm_Mega_Menu {
    /**
     * Modal screen of mega menu settings
     *
     * @var array
     */
    public $modals = array();

    /**
     * Class constructor.
     */
    public function __construct() {
        $this->modals = apply_filters( 'fmfw_mega_menu_modals', array(
            'menus',
            'title',
            'mega',
            'background',
            'icon',
            'content',
            'design',
            'badges',
            'settings',
        ) );
        add_filter( 'wp_edit_nav_menu_walker', array( $this, 'edit_walker' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );
        add_action( 'admin_footer-nav-menus.php', array( $this, 'modal' ) );
        add_action( 'admin_footer-nav-menus.php', array( $this, 'templates' ) );
        add_action( 'wp_ajax_fmfw_save_menu_item_data', array( $this, 'save_menu_item_data' ) );
        //add_action( 'wp_ajax_fmfw_get_form_settings', array( $this, 'get_form_settings' ) );
        add_filter( 'fmfw_default_setting_of_mega_menu', array( $this, 'default_setting_of_mega_menu' ) );

    }

    public function default_setting_of_mega_menu($default = array()){
        $setting = array(
            'mega'         => false,
            'icon'         => array(
                'image'      => ''
            ),
            'hide_text'    => false,
            'disable_link' => false,
            'post_content' => '',
            'content'      => '',
            'width'        => '',
            'menu_content_id' => 0,
            'menu_bg'         => 0,
            'label_background' =>'',
            'label_text' =>'',
            'label_color' =>'',
            'border'       => array(
                'left' => 0,
            ),
            'background'   => array(
                'image'      => '',
                'color'      => '',
                'attachment' => 'scroll',
                'size'       => '',
                'repeat'     => 'no-repeat',
                'position'   => array(
                    'x'      => 'left',
                    'y'      => 'top',
                    'custom' => array(
                        'x' => '',
                        'y' => '',
                    ),
                ),
            ),
            'badge_title'  => '',
            'badge_color'  => '',
            'badge_bg_color' => ''
        );
        return array_merge($default,$setting);
    }

    /**
     * Change walker class for editing nav menu
     *
     * @return string
     */
    public function edit_walker() {
        return 'Mega_Menu_Walker_Edit';
    }


    /**
     * Load scripts on Menus page only
     *
     * @param string $hook
     */
    public function scripts( $hook_suffix ) {
        if ( ( $hook_suffix === 'post-new.php' || $hook_suffix === 'post.php' ) ) {
            if ( $GLOBALS['post']->post_type === 'familab_menu' ) {
                wp_enqueue_style( 'fmfw-mega-memu-item', FAMI_FRAMEWORK_PLUGIN_URL . 'assets/css/menu-item.css' );
                wp_enqueue_script( 'fmfw-mega-memu-item', FAMI_FRAMEWORK_PLUGIN_URL. 'assets/js/menu-item.js', array( 'jquery' ), '1.0' );
            }
        }
        if ( $hook_suffix == 'nav-menus.php' ) {
            wp_enqueue_media();
            wp_enqueue_script( 'fami-font-awesome', FAMI_FRAMEWORK_PLUGIN_URL . 'assets/3rd-party/font-awesome/all.min.js' ,[],'5.11.2',false);
            wp_enqueue_style( 'fmfw-nav-menu', FAMI_FRAMEWORK_PLUGIN_URL  . 'assets/css/mega-menu.css' );
            wp_enqueue_script( 'fmfw-nav-menu', FAMI_FRAMEWORK_PLUGIN_URL  . 'assets/js/mega-menu.js', array( 'jquery','jquery-ui-resizable', 'wp-util', 'wp-color-picker' ), '1.0' );
            wp_localize_script( 'fmfw_mega_menu_item', 'fmmModals', $this->modals);
        }
    }

    /**
     * Ajax function to save menu item data
     */
    public function save_menu_item_data() {
        $post_data = stripslashes_deep( $_POST['data'] );
        parse_str( $post_data, $data );
        $updated = $data;
//        // Save menu item data
        foreach ( $data['menu-item-mega'] as $id => $meta ) {
            $old_meta = get_post_meta( $id, '_fm_menu_item_mega', true );
            $old_meta = wp_parse_args( $old_meta, $this->default_setting_of_mega_menu());
            $meta     = wp_parse_args( $meta, $old_meta );

            $updated['menu-item-mega'][ $id ] = $meta;
            update_post_meta( $id, '_fm_menu_item_mega', $meta );
        }
        wp_send_json_success( $updated );
    }

    /**
     * Ajax function to general setting modal
     */

    public function get_form_settings() {
        $response       = array(
            'html'    => '',
            'message' => '',
            'success' => 'no',
        );
        $item_id        = isset( $_POST['item_id'] ) ? $_POST['item_id'] : '';
        $item_iframe    = isset( $_POST['iframe'] ) ? $_POST['iframe'] : '';
        $menu_object    = wp_get_nav_menu_object( $item_id );
        $title          = $menu_object->name;

        $settings       = get_post_meta( $item_id, '_fm_menu_item_mega', true );
        $settings       = wp_parse_args( ( array )$settings, $this->default_setting_of_mega_menu() );
        $menu_icon_type = isset( $settings['menu_icon_type'] ) ? $settings['menu_icon_type'] : 'font-icon';
        $menu_magemenu  = isset( $settings['mega'] ) ? $settings['mega'] : 0;
        ob_start();
        ?>
        <div id="fmfw_mega_menu_item_settings-popup-content-<?php echo esc_attr( $item_id ); ?>"
             class="fmfw_mega_menu_item_settings-popup-content">
            <div class="head">
                <span class="menu-title"><?php echo esc_html( $title ); ?></span>
                <div class="control">
                    <a class="fmfw_mega_menu-save-settings button button-primary"
                       data-item_id="<?php echo esc_attr( $item_id ); ?>"
                       href="#"><?php esc_html_e( 'Save All', 'fami-framework' ); ?></a>
                </div>
            </div>
            <div class="tabs-settings">
                <ul>
                    <li class="active">
                        <a href=".fmfw_mega_menu-tab-settings">
                            <span class="icon dashicons dashicons-admin-generic"></span>
                            <?php esc_html_e( 'Settings', 'fami-framework' ); ?>
                        </a>
                    </li>
                    <li>
                        <a href=".fmfw_mega_menu-tab-icons">
                            <span class="icon dashicons dashicons-image-filter"></span>
                            <?php esc_html_e( 'Icons', 'fami-framework' ); ?>
                        </a>
                    </li>
                    <li class="fmfw_mega_menu-setting-for-depth-0">
                        <a class="link-open-menu-buider" href=".fmfw_mega_menu-tab-builder">
                            <span class="icon dashicons dashicons-welcome-widgets-menus"></span>
                            <?php esc_html_e( 'Content', 'fami-framework' ); ?>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="tab-container">

                <div class="fmfw_mega_menu-tab-content active fmfw_mega_menu-tab-settings">
                    <div class="vc_col-xs-12 vc_column wpb_el_type_checkbox">
                        <div class="wpb_element_label"><?php esc_html_e( 'Top Level Item Settings', 'fami-framework' ); ?></div>

                        <div class="edit_form_line fmfw_mega_menu-setting-for-depth-0">
                            <div class="heading">
                                <span class="title"><?php esc_html_e( 'Enable Mega', 'fami-framework' ); ?></span>
                            </div>
                            <div class="value">
                                <label class="switch">
                                    <input data-item_id="<?php echo esc_attr( $item_id ); ?>" value="1"
                                           class="wpb_vc_param_value wpb-textinput enable_mega"
                                           name="enable_mega" <?php checked( $settings['mega'] ); ?>
                                           type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                        <div class="edit_form_line">
                            <div class="heading">
                                <span class="title"><?php esc_html_e( 'Hide title', 'fami-framework' ); ?></span>
                                <span class="description"><?php esc_html_e( 'Whether to display item without text or not.', 'fami-framework' ); ?></span>
                            </div>
                            <div class="value">
                                <label class="switch">
                                    <input value="1" class="wpb_vc_param_value wpb-textinput"
                                           name="hide_title" <?php checked( $settings['hide_text'] ); ?>
                                           type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                        <div class="edit_form_line">
                            <div class="heading">
                                <span class="title"><?php esc_html_e( 'Disable link', 'fami-framework' ); ?></span>
                                <span class="description"><?php esc_html_e( 'Whether to disable item hyperlink or not.', 'fami-framework' ); ?></span>
                            </div>
                            <div class="value">
                                <label class="switch">
                                    <input value="1" class="wpb_vc_param_value wpb-textinput"
                                           name="disable_link" <?php checked( $settings['disable_link'] ); ?>
                                           type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                        <div class="edit_form_line">
                            <div class="heading">
                                <span class="title"><?php esc_html_e( 'Label text', 'fami-framework' ); ?></span>
                                <span class="description"><?php esc_html_e( 'Display label or not.', 'fami-framework' ); ?></span>
                            </div>
                            <div class="value">
                                <input value="<?php echo esc_attr( $settings['label_text'] ) ?>"
                                       class="wpb_vc_param_value wpb-textinput el_class textfield" name="label_text"
                                       type="text">
                            </div>
                        </div>
                        <div class="edit_form_line">
                            <div class="heading">
                                <span class="title"><?php esc_html_e( 'Label Color', 'fami-framework' ); ?></span>
                            </div>
                            <div class="value">
                                <script type="text/javascript">
                                    jQuery(document).ready(function ($) {
                                        $('.menu-setting-color').wpColorPicker();
                                    });
                                </script>

                                <input value="<?php echo esc_attr( $settings['label_color'] ) ?>"
                                       class="wpb_vc_param_value wpb-textinput el_class textfield menu-setting-color" name="label_color"
                                       type="text">
                            </div>
                        </div>
                        <div class="edit_form_line">
                            <div class="heading">
                                <span class="title"><?php esc_html_e( 'Label background', 'fami-framework' ); ?></span>
                            </div>
                            <div class="value">
                                <input value="<?php echo esc_attr( $settings['label_background'] );?>"
                                       class="wpb_vc_param_value wpb-textinput el_class textfield menu-setting-color" name="label_background"
                                       type="text">
                            </div>
                        </div>
                        <div class="wpb_element_label"><?php esc_html_e( 'Sub Menu Item Settings', 'fami-framework' ); ?></div>
                        <div class="edit_form_line submenu-item-with fmfw_mega_menu-setting-for-depth-0">
                            <div class="heading">
                                <span class="title"><?php esc_html_e( 'Sub menu item width (px only)', 'fami-framework' ); ?></span>
                            </div>
                            <div class="value">
                                <input value="<?php echo esc_attr( $settings['width'] ) ?>"
                                       class="wpb_vc_param_value wpb-textinput el_class textfield" name="menu_width"
                                       type="number">
                            </div>
                        </div>
                        <div class="edit_form_line submenu-item-bg fmfw_mega_menu-setting-for-depth-0">
                            <div class="heading">
                                <span class="title"><?php esc_html_e( 'Menu Background', 'fami-framework' ); ?></span>
                            </div>
                            <div class="value">
                                <input type="hidden" value="<?php echo esc_attr( $settings['menu_bg'] ); ?>"
                                       class="regular-text process_custom_images" id="process_custom_images"
                                       name="menu_bg">
                                <button class="wpb_vc_param_value wpb-textinput el_class textfield set_custom_images button"><?php esc_html_e( 'Select a image' ,'fami-framework') ?></button>
                                <div class="image-preview">
                                    <?php if ( isset( $settings['menu_bg'] ) && $settings['menu_bg'] > 0 ):
                                        $image = wp_get_attachment_image_src( $settings['menu_bg'], 'full' );
                                        if ( $image && is_array( $image ) && isset( $image[0] ) && $image[0] != '' ) {
                                            ?>
                                            <img src="<?php echo esc_url( $image[0] ); ?>" alt="">
                                            <a class="remove-menu-bg" href="#"><span
                                                        class="fip-fa dashicons dashicons-no-alt"></span></a>
                                            <?php
                                        }
                                        ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="edit_form_line submenu-item-bg fmfw_mega_menu-setting-for-depth-0">
                            <div class="heading">
                                <span class="title"><?php esc_html_e( 'Background Position', 'fami-framework' ); ?></span>
                            </div>
                            <div class="value">
                                <select name="bg_position" class="wpb_vc_param_value">
                                    <option value="center" <?php echo $settings['bg_position'] == 'center' ? 'selected' : ''; ?>>
                                        <?php esc_html_e( 'Center', 'fami-framework' ); ?>
                                    </option>
                                    <option value="left" <?php echo $settings['bg_position'] == 'left' ? 'selected' : ''; ?>>
                                        <?php esc_html_e( 'Left', 'fami-framework' ); ?>
                                    </option>
                                    <option value="right" <?php echo $settings['bg_position'] == 'right' ? 'selected' : ''; ?>>
                                        <?php esc_html_e( 'Right', 'fami-framework' ); ?>
                                    </option>
                                    <option value="top" <?php echo $settings['bg_position'] == 'top' ? 'selected' : ''; ?>>
                                        <?php esc_html_e( 'Top', 'fami-framework' ); ?>
                                    </option>
                                    <option value="bottom" <?php echo $settings['bg_position'] == 'bottom' ? 'selected' : ''; ?>>
                                        <?php esc_html_e( 'Bottom', 'fami-framework' ); ?>
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <?php do_action( 'familab_menu_tabs_settings', $item_id, $settings ); ?>
                </div>

                <div class="fmfw_mega_menu-tab-content fmfw_mega_menu-tab-builder fmfw_mega_menu-setting-for-depth-0">
                    <?php if ( $menu_magemenu != 0 ) : ?>
                        <iframe src="<?php echo esc_url( $item_iframe ); ?>">
                            <?php echo esc_html__( 'Waiting for content ...', 'fami-framework' ); ?>
                        </iframe>
                    <?php else: ?>
                        <div class="notice-mega" style="text-align: center; padding: 50px 20px;"><?php esc_html_e( ' Click on "Enable Mega Builder" in  Settings tab before buiding content.', 'fami-framework' ); ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
        $response['html']    = ob_get_clean();
        $response['success'] = 'yes';
        wp_send_json( $response );
    }


    /**
     * Prints HTML of modal on footer
     */
    public function modal() {
        ?>
        <div id="fmm-settings" tabindex="0" class="fmm-settings">
            <div class="fmm-modal media-modal wp-core-ui">
                <button type="button" class="button-link media-modal-close fmm-modal-close">
                    <span class="media-modal-icon"><span class="screen-reader-text"><?php esc_html_e( 'Close', 'fami-framework' ) ?></span></span>
                </button>
                <div class="media-modal-content">
                    <div class="fmm-frame-menu media-frame-menu">
                        <div class="fmm-menu media-menu"></div>
                    </div>
                    <div class="fmm-frame-title media-frame-title"></div>
                    <div class="fmm-frame-content media-frame-content">
                        <div class="fmm-content"></div>
                    </div>
                    <div class="fmm-frame-toolbar media-frame-toolbar">
                        <div class="fmm-toolbar media-toolbar">
                            <div class="fmm-toolbar-primary media-toolbar-primary search-form">
                                <button type="button" class="button fmm-button fmm-button-save media-button button-primary button-large"><?php esc_html_e( 'Save Changes', 'fami-framework' ) ?></button>
                                <button type="button" class="button fmm-button fmm-button-cancel media-button button-secondary button-large"><?php esc_html_e( 'Cancel', 'fami-framework' ) ?></button>
                                <span class="spinner"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="media-modal-backdrop fmm-modal-backdrop"></div>
        </div>
        <?php
    }

    /**
     * Prints underscore template on footer
     */
    public function templates() {
        foreach ( $this->modals as $template ) {
            $file = plugin_dir_path(__FILE__). 'tmpl/' . $template . '.php';
            $file = apply_filters( 'fmfw_mega_menu_modal_template_file', $file, $template );

            if ( ! file_exists( $file ) ) {
                continue;
            }
            ?>
            <script type="text/html" id="tmpl-fmfw-<?php echo esc_attr( $template ) ?>">
                <?php include( $file ); ?>
            </script>
            <?php
        }
    }


}
new Fm_Mega_Menu();
