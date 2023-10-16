<?php

require_once (dirname(__FILE__).'/featured-box.php');
require_once (dirname(__FILE__).'/newsletter.php');
require_once (dirname(__FILE__).'/recent_post.php');
function cenos_widget_list() {
    $widgets = array(
        'no_required' => [
            'Cenos_Featured_Box_Widget',
            'Cenos_Recent_Posts_Widget'
        ],
        'MC4WP_Form_Manager' => [
            'Cenos_Newsletter_Widget'
        ]
    );
    return $widgets;
}
add_filter('fmfw_widgets','cenos_widget_list');