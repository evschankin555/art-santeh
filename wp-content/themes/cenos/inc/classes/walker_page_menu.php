<?php

/**
 * Class Cenos_Walker_Default_Menu
 *
 * Walker class for menu
 */

if (!class_exists('Cenos_Walker_Page_Menu')){
    class Cenos_Walker_Page_Menu extends Walker_Page {
        public function start_lvl( &$output, $depth = 0, $args = array() ) {
            if ( isset( $args['item_spacing'] ) && 'preserve' === $args['item_spacing'] ) {
                $t = "\t";
                $n = "\n";
            } else {
                $t = '';
                $n = '';
            }
            $output .= '<a href="#." class="toggle-submenu"><span class="toggle-icon"></span></a>';
            $indent  = str_repeat( $t, $depth );
            $output .= "{$n}{$indent}<ul class='children'>{$n}";
        }
    }
}

