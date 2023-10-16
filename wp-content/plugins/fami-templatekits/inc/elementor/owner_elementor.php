<?php

use Elementor\Controls_Manager;
use Elementor\Element_Base;
use Elementor\Plugin;

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class Fmtpl_Owner_Elementor {
    /**
     * Add Action hook
     */
    public function __construct() {
        add_action('elementor/element/after_section_end', [__CLASS__, 'add_controls_section'], 10, 3);
    }

    /**
     * Replace Pro Custom CSS Control
     */
    public static function add_controls_section($element, $section_id, $args) {
        if ($element->get_controls('content_width')){
            $page_template = basename(get_page_template());
            if ($page_template == 'fullpage.php') {
                $element->update_responsive_control('content_width',[
                    'selectors' => [
                        '{{WRAPPER}} > .elementor-container' => 'max-width: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} > .fp-tableCell > .elementor-container' => 'max-width: {{SIZE}}{{UNIT}};',
                    ],
                ]);
            }
        }
    }
}
new Fmtpl_Owner_Elementor();