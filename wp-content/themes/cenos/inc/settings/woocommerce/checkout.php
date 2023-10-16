<?php
//woocommerce_checkout



class Woo_CheckOut extends Settings {
    public function getPanels($panels)
    {
        return $panels;
    }

    public function getSections($sections)
    {

        return $sections;
    }

    public function getFields($fields)
    {
        $site_layout_fields = [

            'checkout_layout'                    => [
                'type'        => 'radio-buttonset',
                'label'       =>esc_html__( 'Checkout Layout', 'cenos' ),
                'section' => 'woocommerce_checkout',
                'default' => 'default',
                'choices' => [
                    'default' =>esc_html__( 'Two Column', 'cenos' ),
                ],
                'priority' => 1,
            ],
        ];
        return array_merge($fields,$site_layout_fields);
    }
}
new Woo_CheckOut();
