<?php





class Scroll_Bar extends Settings
{

    public function getPanels($panels)
    {
        return $panels;
    }

    public function getSections($sections)
    {
        $sections_scroll = [
            'scroll_bar' => array(
                'priority' => 300,
                'title'    =>esc_html__( 'Scroll Bar', 'cenos' ),
            ),
        ];
        return array_merge($sections, $sections_scroll);
    }

    public function getFields($fields)
    {
        $scroll_field = [
            'scroll_to_top' => [
                'type'        => 'toggle',
                'label'       =>esc_html__( 'Scroll To Top', 'cenos' ),
                'section'     => 'scroll_bar',
                'default'     => false,
            ],
        ];
        if (cenos_is_woocommerce_activated()){
            $scroll_field['scroll_cart_btn'] = [
                'type'        => 'toggle',
                'label'       =>esc_html__( 'Scroll Cart', 'cenos' ),
                'section'     => 'scroll_bar',
                'default'     => false,
            ];
            if (defined('TINVWL_VERSION') || defined('TINVWL_FVERSION') || class_exists('YITH_WCWL')){
                $scroll_field['scroll_wishlist_btn'] = [
                    'type'        => 'toggle',
                    'label'       =>esc_html__( 'Scroll Wishlist', 'cenos' ),
                    'section'     => 'scroll_bar',
                    'default'     => false,
                ];
            }
        }
        return array_merge($fields,$scroll_field);
    }
}
new Scroll_Bar();
