<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes;
use Elementor\Scheme_Typography;

class Fmtpl_Instagram extends Widget_Base {
    private $default_control;
    public function get_name() {
        return 'fmtpl-instagram';
    }
    public function get_title() {
        return __( 'Instagram Feed', 'fami-templatekits' );
    }
    public function get_icon() {
        return 'eicon-instagram-gallery fmtpl-widget-icon';
    }
    public function get_categories() {
        return [ 'fami-elements' ];
    }
    public function get_keywords() {
        return ['fami','fm', 'instagram','social','feed' ];
    }

    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );
        $this->add_style_depends('fmtpl-instagram');
    }

    private function define_widget_control() {
        $widget_control = [
            'layout' => [
                'label' => __( 'Layout', 'fami-templatekits' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => __( 'Default', 'fami-templatekits' ),
                ],
                'style_transfer' => true,
            ],
            'limit' => [
                'label' => __( 'Item Limit', 'fami-templatekits' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 6,
            ],
            'row_desktop_items' => [
                'label' => __( 'Item Limit On Desktop', 'fami-templatekits' ),
                'type' => Controls_Manager::NUMBER,
                'default' => '',
                'prefix_class' => 'fmtpl-desktop-items-limit-',
            ],
            'row_items' => [
                'label' => __( 'Items per row', 'fami-templatekits' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 6,
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-instagram .instagram-feed-content' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
                ],
            ],
            'items_gap' => [
                'label' => __( 'Items Gap', 'fami-templatekits' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],

                ],
                'default' => [],
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-instagram .instagram-feed-content' => 'grid-gap: {{SIZE}}{{UNIT}};',
                ],
            ],
            'show_by_hashtag' => [
                'label' => __( 'Show By Hashtag', 'fami-templatekits' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'fami-templatekits' ),
                'label_off' => __( 'Hide', 'fami-templatekits' ),
                'return_value' => 'yes',
                'default' => 'no',
            ],
            'hashtags' => [
                'label' => __( 'Hashtags', 'fami-templatekits' ),
                'description' => __('Insert hashtags without \'#\' separated by comma','fami-templatekits'),
                'type' => Controls_Manager::TEXT,
                'default' => __( '', 'fami-templatekits' ),
                'condition' => ['show_by_hashtag' => 'yes'],
            ],
            'title' => [
                'label' => __( 'Title', 'fami-templatekits' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' =>  __( 'This is the %highlight% title', 'fami-templatekits' ),
                'placeholder' => __( 'This is the %highlight% title', 'fami-templatekits' ),
                'label_block' => true,


            ],
            'highlight_title' =>
                [
                    'label' => __( 'Highlight Title', 'fami-templatekits' ),
                    'type' => Controls_Manager::TEXT,
                    'dynamic' => [
                        'active' => true,
                    ],
                    'default' => __( 'Highlight', 'fami-templatekits' ),
                    'placeholder' => __( 'Enter your Highlight title', 'fami-templatekits' ),
                    'label_block' => true,
                ],
            'description' => [
                'label' => __( 'Description', 'fami-templatekits' ),
                'type' => Controls_Manager::WYSIWYG,
                'default' => __( '', 'fami-templatekits' ),
                'placeholder' => __( 'Type your description here', 'fami-templatekits' ),
            ],
            'content_alignment' => [
                'label' => __( 'Alignment', 'fami-templatekits' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'fami-templatekits' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'fami-templatekits' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'fami-templatekits' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-instagram, {{WRAPPER}} .instagram-form-content input[type="email"]' => 'text-align: {{VALUE}}',
                ],
            ],
            'title_color' =>
                [
                    'label' => __( 'Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .instagram-title' => 'color: {{VALUE}}',
                    ],
                    'condition' => [
                        'title!' => '',
                    ],
                ],
            'title_typography' =>
                [
                    'name' => 'title_typography',
                    'scheme' => Schemes\Typography::TYPOGRAPHY_1,
                    'selector' => '{{WRAPPER}} .instagram-title',
                    'condition' => [
                        'title!' => '',
                    ],
                ],
            'title_padding' => [
                'label' => __( 'Padding', 'fami-templatekits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .instagram-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'title!' => '',
                ],
            ],
            'highlight_title_color' => [
                'label' => __( 'Color', 'fami-templatekits' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .fmtpl-instagram .instagram-title .highlight' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_1,
                ],
            ],
            'highlight_title_typography' => [
                'name' => 'highlight_title_typography',
                'selector' => '{{WRAPPER}} .fmtpl-instagram .instagram-title .highlight',
                'scheme' => Schemes\Typography::TYPOGRAPHY_1,
            ],
            'desc_color' =>
                [
                    'label' => __( 'Color', 'fami-templatekits' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .instagram-desc' => 'color: {{VALUE}}',
                    ],
                    'condition' => [
                        'description!' => '',
                    ],
                ],
            'desc_typography' =>
                [
                    'name' => 'desc_typography',
                    'scheme' => Schemes\Typography::TYPOGRAPHY_3,
                    'selector' => '{{WRAPPER}} .instagram-desc',
                    'condition' => [
                        'description!' => '',
                    ],
                ],
            'desc_padding' => [
                'label' => __( 'Padding', 'fami-templatekits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .instagram-desc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'description!' => '',
                ],
            ],
        ];
        $this->default_control = apply_filters('fmtpl-instagram-elementor-widget-control',$widget_control);
    }

    protected function _register_controls()
    {
        $this->define_widget_control();

        $this->start_controls_section('_section_title_instagram', ['label' => __('Title', 'fami-templatekits')]);

        if (isset($this->default_control['title'])) {
            $this->add_control(
                'title',
                $this->default_control['title']
            );
        }

        if (isset($this->default_control['highlight_title'])) {
            $this->add_control(
                'highlight_title',
                $this->default_control['highlight_title']
            );
        }
        if (isset($this->default_control['description'])) {
            $this->add_control(
                'description',
                $this->default_control['description']
            );
        }
        $this->end_controls_section();

        $this->start_controls_section('_section_instagram', ['label' => __('Settings', 'fami-templatekits')]);
        if (isset($this->default_control['layout'])) {
            $this->add_control(
                'layout',
                $this->default_control['layout']
            );
        }
        if (isset($this->default_control['show_by_hashtag'])) {
            $this->add_control(
                'show_by_hashtag',
                $this->default_control['show_by_hashtag']
            );
        }
        if (isset($this->default_control['hashtags'])) {
            $this->add_control(
                'hashtags',
                $this->default_control['hashtags']
            );
        }
        if (isset($this->default_control['limit'])) {
            $this->add_control(
                'limit',
                $this->default_control['limit']
            );
        }
        if (isset($this->default_control['row_desktop_items'])) {
            $this->add_control(
                'row_desktop_items',
                $this->default_control['row_desktop_items']
            );
        }
        if (isset($this->default_control['row_items'])) {
            $this->add_responsive_control(
                'row_items',
                $this->default_control['row_items']
            );
        }
        if (isset($this->default_control['items_gap'])) {
            $this->add_responsive_control(
                'items_gap',
                $this->default_control['items_gap']
            );
        }
        $this->end_controls_section();
        $this->start_controls_section(
            'instagram_content_style',
            [
                'label' => __( 'Layout', 'fami-templatekits' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        if (isset($this->default_control['content_alignment'])) {
            $this->add_control(
                'content_alignment',
                $this->default_control['content_alignment']
            );
        }
        $this->end_controls_section();
        $this->start_controls_section(
            'instagram_title_style',
            [
                'label' => __( 'Title', 'fami-templatekits' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'title!' => ''
                ]
            ]
        );
        if (isset($this->default_control['title_color'])) {
            $this->add_control(
                'title_color',
                $this->default_control['title_color']
            );
        }
        if (isset($this->default_control['title_typography'])) {
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                $this->default_control['title_typography']
            );
        }
        if (isset($this->default_control['title_padding'])) {
            $this->add_responsive_control(
                'title_padding',
                $this->default_control['title_padding']
            );
        }
        $this->end_controls_section();
        $this->start_controls_section(
            '_section_highlight_title_style',
            [
                'label' => __( 'HighLight Title', 'fami-templatekits' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'highlight_title!' => ''
                ]
            ]
        );
        if (isset($this->default_control['highlight_title_color'])) {
            $this->add_control(
                'highlight_title_color',
                $this->default_control['highlight_title_color']
            );
        }
        if (isset($this->default_control['highlight_title_typography'])) {
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                $this->default_control['highlight_title_typography']
            );
        }
        $this->end_controls_section();
        $this->start_controls_section(
            'instagram_desc_style',
            [
                'label' => __( 'Description', 'fami-templatekits' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'description!' => ''
                ]
            ]
        );
        if (isset($this->default_control['desc_color'])) {
            $this->add_control(
                'desc_color',
                $this->default_control['desc_color']
            );
        }
        if (isset($this->default_control['desc_typography'])) {
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                $this->default_control['desc_typography']
            );
        }
        if (isset($this->default_control['desc_padding'])) {
            $this->add_responsive_control(
                'desc_padding',
                $this->default_control['desc_padding']
            );
        }
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $enjoyinstagram = false;
        if (function_exists('EnjoyInstagram')){
            $enjoyinstagram = EnjoyInstagram();
        } elseif (function_exists('enjoyinstagram')){
            $enjoyinstagram = enjoyinstagram();
        }
        if (!$enjoyinstagram){
            return;
        }
        if ( empty( $enjoyinstagram->get_users())) {
            return '';
        }
        $limit = isset($settings['limit'])? $settings['limit'] : 8;
        //show_by_hashtag
        $hashtags = [];
        $type = '';
        if (isset($settings['show_by_hashtag'],$settings['hashtags']) && $settings['show_by_hashtag'] == 'yes' && !empty($settings['hashtags'])){
            $hashtags = array_map( 'trim', explode( ',', $settings['hashtags'] ) );
            $type = 'hashtag';
        }
        $result = $this->_get_shortcode_data($type, $limit, $hashtags);
        if ( empty( $result ) ) {
            return '';
        }
        $html = apply_filters('fmtpl-instagram-widget-html','',$settings, $result);
        if (!empty($html)):
            echo $html;
        else:?>
        <div class="fmtpl-elementor-widget fmtpl-instagram <?php echo($settings['layout']);?>">
            <?php
            if (!empty($settings['title'])) :
                $highlight_title = isset($settings['highlight_title'])? $settings['highlight_title']:'';
                $title = str_replace('%highlight%','<span class="highlight">'.$highlight_title.'</span>',$settings['title']);
                ?>
                <div class="instagram-title widget-title"><?php echo $title;?></div>
            <?php endif;?>
            <?php if (!empty($settings['description'])) :?>
                <div class="instagram-desc"><?php echo $settings['description'];?></div>
            <?php endif;?>
            <div class="instagram-feed-content">
                <?php
                foreach ( $result as $entry ) :
                    $url = $entry['images']['standard_resolution']['url'];
                    if ( $entry['type'] === 'video' ) {
                        $url .= '&swipeboxvideo=1';
                    }
                    ?>
                    <div class="fmins-item">
                        <div class="image-box">
                            <a title="<?php echo $entry['caption']['text'] ?>" class="swipebox_grid <?php echo $entry['type'] === 'video' ? 'ei-media-type-video' : 'ei-media-type-image' ?>"
                               href="<?php echo $url ?>">
                                <img alt="<?php echo $entry['caption']['text'] ?>"
                                     src="<?php echo $entry['images']['thumbnail']['url'] ?>">
                            </a>
                        </div>
                        <div class="img-overlay">
                            <a title="<?php echo $entry['caption']['text'] ?>" class="swipebox_grid <?php echo $entry['type'] === 'video' ? 'ei-media-type-video' : 'ei-media-type-image' ?>"
                               href="<?php echo $url ?>">
                                <?php echo $entry['caption']['text'];?>
                            </a>

                            <span class="ins-like"><?php echo $entry['likes']['count'];?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif;
    }
    protected function _get_shortcode_data( $type = '', $limit = 20, $hashtag = [] ) {

        ! $type && $type = get_option( 'enjoyinstagram_user_or_hashtag', 'user' );
        $enjoyinstagram = false;
        if (function_exists('EnjoyInstagram')){
            $enjoyinstagram = EnjoyInstagram();
        } elseif (function_exists('enjoyinstagram')){
            $enjoyinstagram = enjoyinstagram();
        }
        if (!$enjoyinstagram){
            return;
        }


        $ei_api = false;
        if (function_exists('EnjoyInstagram_Api_Connection')){
            $ei_api = EnjoyInstagram_Api_Connection();
        }elseif (function_exists('ei_api')) {
            $ei_api = ei_api();
        }
        if (!$ei_api){
            return;
        }
        $users = $enjoyinstagram->get_users();
        if ( empty( $users ) ) {
            return '';
        }
        $user     = array_values( $users )[0];
        //$hashtag  = array_map( 'trim', explode( ',', get_option( 'enjoyinstagram_hashtag' ) ) );
        $medias   = array();
        $data     = array();
        $user_business = $enjoyinstagram->has_business_user();
        $ei_db = false;
        if (function_exists('EnjoyInstagram_DB')){
            $ei_db = EnjoyInstagram_DB();
        }elseif (function_exists('ei_db')){
            $ei_db = ei_db();
        }
        if (!$ei_db){
            return $data;
        }
        if ( 'user' === $type ) {
            // get media
            $medias = $ei_db->get_shortcode_media_user(
                $user['username'],
                array(),
                false,
                $limit
            );
        } elseif ( 'hashtag' === $type ) {
            $medias = $ei_db->get_shortcode_media_hashtag(
                $hashtag,
                false,
                $limit
            );
        } elseif ( 'public_hashtag' === $type && ! empty( $hashtag ) && $user_business ) {
            foreach ( $hashtag as $single_hashtag ) {
                $tr_key = 'ei-hashtag-' . $single_hashtag;

                $temp_medias = get_transient( $tr_key );

                if ( empty( $temp_medias ) ) {
                    $temp_medias = $ei_api->search_business_hashtag(
                        $users[ $user_business ],
                        $single_hashtag
                    );

                    if ( ! empty( $temp_medias ) ) {
                        set_transient( $tr_key, $temp_medias, MINUTE_IN_SECONDS * 3 );
                    }
                }

                $medias = array_merge( $medias, $temp_medias );
            }
        }

        if ( count( $medias ) > 0 ) {

            foreach ( $medias as $media ) {
                $data[] = enjoyinstagram_format_media_for_shortcode(
                    $media,
                    isset( $this->users[ $user ] ) ? $this->users[ $user ] : array()
                );
            }
        }

        return $data;
    }
}
