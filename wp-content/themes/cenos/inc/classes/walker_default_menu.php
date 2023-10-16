<?php

/**
 * Class Cenos_Walker_Default_Menu
 *
 * Walker class for menu
 */

if (!class_exists('Cenos_Walker_Default_Menu')){
    class Cenos_Walker_Default_Menu extends Walker_Nav_Menu {
        public function start_lvl( &$output, $depth = 0, $args = array() ) {
            $t = "\t";
            $n = "\n";
            $indent = str_repeat( $t, $depth );
            $classes = ['sub-menu'];
            $class_names = join( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );
            $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
            $output .= '<a href="#." class="toggle-submenu"><span class="toggle-icon"></span></a>';
            $output .= "{$n}{$indent}<ul$class_names>{$n}";
        }

        /**
         * Ends the list of after the elements are added.
         *
         * @since 1.0.0
         *
         * @see Walker::end_lvl()
         *
         * @param string   $output Used to append additional content (passed by reference).
         * @param int      $depth  Depth of menu item. Used for padding.
         * @param stdClass $args   An object of wp_nav_menu() arguments.
         */
        public function end_lvl( &$output, $depth = 0, $args = array() ) {
            $t = "\t";
            $n = "\n";
            $indent = str_repeat( $t, $depth );
            $output .= "$indent</ul>{$n}";
        }

        /**
         * Start the element output.
         * Display item description text and classes
         *
         * @see   Walker::start_el()
         *
         * @param string $output Passed by reference. Used to append additional content.
         * @param object $item   Menu item data object.
         * @param int    $depth  Depth of menu item. Used for padding.
         * @param array  $args   An array of arguments. @see wp_nav_menu()
         * @param int    $id     Current item ID.
         */
        public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
            $t = "\t";
            $indent = ( $depth ) ? str_repeat( $t, $depth ) : '';

            // Get mega data from post meta
            global $menu_item_mega;
            if (!isset($menu_item_mega[$item->ID])) {
                $default_mega_setting = [];
                if (function_exists('fmfw_get_default_setting_of_mega_menu')) {
                    $default_mega_setting = fmfw_get_default_setting_of_mega_menu();
                }
                // Get mega data from post meta
                $item_mega = cenos_get_post_meta( $item->ID, '_fm_menu_item_mega', true );
                $item_mega = cenos_parse_args( $item_mega, $default_mega_setting );
                $menu_item_mega[$item->ID] = $item_mega;
                $GLOBALS['menu_item_mega'] = $menu_item_mega;
            } else {
                $item_mega = $menu_item_mega[$item->ID];
            }

            $classes   = empty( $item->classes ) ? array() : (array) $item->classes;

            $classes[] = 'menu-item-' . $item->ID;
            $classes[] = 'menu-level-' . $depth;

            $args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );
            $classes = apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth );

            // Adding icon
            if (isset($item_mega['icon']) && $item_mega['icon'] ) {
                $classes[] = 'menu-item-has-icon';
            }

            $badge = '';
            if (isset($item_mega['badge_title']) && !empty($item_mega['badge_title']) ) {
                $classes[] = 'menu-item-has-badge';
                $label_color = isset( $item_mega['badge_color']) ? $item_mega['badge_color'] : '#ffffff';
                $label_background = isset( $item_mega['badge_bg_color'] ) ? $item_mega['badge_bg_color'] : cenos_get_option('main_color');
                $style  = 'color:'.$label_color.';';
                $style .=' background-color:'.$label_background.';';
                $badge = '<span style="'.$style.'" class="menu-item-badge"><span class="badge-title">'.$item_mega['badge_title'].'</span></span>';
            }

            $class_names = join( ' ', $classes );

            $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

            $item_id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );

            $item_id = $item_id ? ' id="' . esc_attr( $item_id ) . '"' : '';

            $output .= $indent . '<li' . $item_id . $class_names . '>';

            $atts           = array();
            $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
            $atts['target'] = ! empty( $item->target ) ? $item->target : '';
            $atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
            $atts['href']   = ! empty( $item->url ) ? $item->url : '';

            $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

            $attributes = '';
            foreach ( $atts as $attr => $value ) {
                if ( ! empty( $value ) ) {
                    $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                    $attributes .= ' ' . $attr . '="' . $value . '"';
                }
            }
            $link_open = '<a' . $attributes . '>';

            /** This filter is documented in wp-includes/post-template.php */
            $title = apply_filters( 'the_title', $item->title, $item->ID );
            $title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

            $icon = '';
            if (!empty($item_mega['icon']) && $item_mega['icon']['image']) {
                $icon = '<span class="menu-item-icon">'.cenos_media_content($item_mega['icon']['image'],$title).'</span>';
            }

            $link_close = '</a>';

            $item_output = $args->before;
            $item_output .= $link_open;
            $item_output .= $args->link_before . $icon . $title .$badge. $args->link_after;
            $item_output .= $link_close;
            $item_output .= $args->after;
            $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
        }
    }
}

