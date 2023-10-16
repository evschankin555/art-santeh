<?php

/**
 * Class Cenos_Walker_Mega_Menu
 *
 * Walker class for mega menu
 */
class Cenos_Walker_Mega_Menu extends Walker_Nav_Menu {
	/**
	 * Tells child items know it is in a mega menu or not
	 *
	 * @var boolean
	 */
	protected $enable_mega = false;

	/**
	 * Store menu item mega data
	 *
	 * @var array
	 */
	protected $mega_data = array();

	/**
	 * Custom CSS for menu items
	 *
	 * @var string
	 */
	protected $css = '';

	/**
	 * Starts the list before the elements are added.
	 *
	 * @see   Walker::start_lvl()
	 *
	 * @since 1.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   An array of arguments. @see wp_nav_menu()
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
        $t = "\t";
        $n = "\n";
		$indent = str_repeat( $t, $depth );

        $classes = ['sub-menu'];
		if ( ! $depth && $this->enable_mega ) {
			$style = ' '.$this->get_mega_inline_css();
            if (in_array( $this->mega_data['width'], array( 'container', 'container-fluid' ) )){
                $classes[] = 'mega-menu-wrap';
            } else {
                $classes[] = 'mega-menu-container';
            }
		} else {
		    $style = '';
		}
        $class_names = join( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );
        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
        if ( ! $depth && $this->enable_mega && in_array( $this->mega_data['width'], array( 'container', 'container-fluid' ) ) ) {
            $mega_container_class[] = $this->mega_data['width'];
            $mega_container_class[] = 'mega-menu-container';
            $attrs   = ' class="' . esc_attr( join( ' ', $mega_container_class ) ) . '"';
            $output .= "{$n}{$indent}<div$class_names>{$n}";
            $output .= "{$n}{$indent}<div$attrs $style>{$n}";
            $output .= "{$n}{$indent}<ul class='mega-menu-content'>{$n}";
        } else {
            $output .= "{$n}{$indent}<ul$class_names $style>{$n}";
        }
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
        if ( ! $depth && $this->enable_mega  && in_array( $this->mega_data['width'], array( 'container', 'container-fluid' ) )) {
            $output .= "$indent</ul></div></div>{$n}";
        } else {
            $output .= "$indent</ul>{$n}";
        }
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
		/**
		 * Filters the arguments for a single nav menu item.
		 *
		 * @since 4.4.0
		 *
		 * @param array  $args  An array of arguments.
		 * @param object $item  Menu item data object.
		 * @param int    $depth Depth of menu item. Used for padding.
		 */
		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

		// Adding icon
		if (isset($item_mega['icon']) && $item_mega['icon'] ) {
			$classes[] = 'menu-item-has-icon';
		}
		// Adding classes for mega menu
		if (isset($item_mega['mega']) && $item_mega['mega'] && ! $depth ) {
			$classes[] = 'menu-item-mega ';
			if (!in_array('menu-item-has-children',$classes)){
				$classes[] = 'menu-item-has-children';
			}
			if ( $item_mega['background']['image'] ) {
				$classes[] = 'menu-item-has-background';
			}
			if ($item_mega['width'] == 'custom'){
                $classes[] = 'menu-item-custom-width';
            }
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
        $mega_post_content = '';
		// Add classes for columns
		if ( 1 == $depth && $this->enable_mega ) {
			$classes[] = 'mega-sub-menu ' . $this->get_css_column( $item_mega['width'] );

			if ( $item_mega['disable_link'] ) {
				$classes[] = 'link-disabled';
			}

			if ( $item_mega['hide_text'] ) {
				$classes[] = 'menu-item-title-hidden';
			}

			if ( $item_mega['border']['left'] ) {
				$classes[] = 'has-border-left';
			}
		}

		// Check if this is top level and is mega menu
		if (isset($item_mega['mega']) && ! $depth ) {
			$this->enable_mega = $item_mega['mega'];
			$this->mega_data = $item_mega;
		}

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$item_id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );

		$item_id = $item_id ? ' id="' . esc_attr( $item_id ) . '"' : '';

		$output .= $indent . '<li' . $item_id . $class_names  . '>';

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

		// Check if link is disable
        $parent_field = $this->db_fields['parent'];
		if ( $this->enable_mega && $depth == 1 && $item_mega['disable_link'] ) {
			$link_open = '<span data-parent="'.$item->$parent_field.'">';
		} else {
			$link_open = '<a' . $attributes . ' data-parent="'.$item->$parent_field.'">';
		}
        /** This filter is documented in wp-includes/post-template.php */
        $title = apply_filters( 'the_title', $item->title, $item->ID );
        /**
         * Filters a menu item's title.
         *
         * @since 4.4.0
         *
         * @param string $title The menu item's title.
         * @param object $item  The current menu item.
         * @param array  $args  An array of wp_nav_menu() arguments.
         * @param int    $depth Depth of menu item. Used for padding.
         */
        $title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

        $icon = '';
        if (isset($item_mega['icon'],$item_mega['icon']['image']) && $item_mega['icon']['image'] ) {
            $icon = '<span class="menu-item-icon">'.cenos_media_content($item_mega['icon']['image'],$title).'</span>';
        }

		// Check if link is disable
		if ( $this->enable_mega && $depth == 1 && $item_mega['disable_link'] ) {
			$link_close = '</span>';
		} else {
			$link_close = '</a>';
		}

		$item_output = $args->before;
		$item_output .= $link_open;
		$item_output .= $args->link_before . $icon . $title .$badge. $args->link_after;
		$item_output .= $link_close;
		$item_output .= $args->after;

		if ( 1 <= $depth && ! empty( $item_mega['content'] ) ) {
			$item_output .= '<div class="menu-item-content">' . do_shortcode( $item_mega['content'] ) . '</div>';
		}

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}


    /**
     * Traverse elements to create list from elements.
     *
     * Display one element if the element doesn't have any children otherwise,
     * display the element and its children. Will only traverse up to the max
     * depth and no ignore elements under that depth. It is possible to set the
     * max depth to include all depths, see walk() method.
     *
     * This method should not be called directly, use the walk() method instead.
     *
     * @since 2.5.0
     *
     * @param object $element           Data object.
     * @param array  $children_elements List of elements to continue traversing (passed by reference).
     * @param int    $max_depth         Max depth to traverse.
     * @param int    $depth             Depth of current element.
     * @param array  $args              An array of arguments.
     * @param string $output            Used to append additional content (passed by reference).
     */
    public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
        if ( ! $element ) {
            return;
        }

        global $elementor_instance;
        $id_field = $this->db_fields['id'];
        $id       = $element->$id_field;
        $parent_field = $this->db_fields['parent'];
        global $menu_item_mega, $skip_items;
        if (!isset($menu_item_mega[$id])) {
            $default_mega_setting = [];
            if (function_exists('fmfw_get_default_setting_of_mega_menu')) {
                $default_mega_setting = fmfw_get_default_setting_of_mega_menu();
            }

            // Get mega data from post meta
            $item_mega = cenos_get_post_meta( $id, '_fm_menu_item_mega', true );
            $item_mega = cenos_parse_args( $item_mega, $default_mega_setting );
            $menu_item_mega[$id] = $item_mega;
            $GLOBALS['menu_item_mega'] = $menu_item_mega;
        } else {
            $item_mega = $menu_item_mega[$id];
        }

        // Display this element.
        $this->has_children = ! empty( $children_elements[ $id ] );
        if ( isset( $args[0] ) && is_array( $args[0] ) ) {
            $args[0]['has_children'] = $this->has_children; // Back-compat.
        }
        if (!isset($skip_items[$element->$parent_field])){
            $this->start_el( $output, $element, $depth, ...array_values( $args ) );
            // Descend only when the depth is right and there are childrens for this element.
            if ( ( 0 == $max_depth || $max_depth > $depth + 1 ) && (isset( $children_elements[ $id ] ) || (isset($item_mega['post_content']) && $item_mega['post_content'])) ) {
                if ($depth == 0 && isset($item_mega['post_content']) && $item_mega['post_content']) {
                    $this->start_lvl( $output, $depth, ...array_values( $args ) );
                    if( $elementor_instance && cenos_is_built_with_elementor($item_mega['post_content'])){
                        $in_css = cenos_get_option('header_quick_menu')? true : false;
                        $output .= '<li class="mega-content-post">';
                        $output .= $elementor_instance->frontend->get_builder_content_for_display($item_mega['post_content'], $in_css);
                        $output .= '</li>';
                    } else {
                        $output .= '<li class="mega-content-post">';
                        $output .= do_shortcode(get_post_field('post_content', $item_mega['post_content']));
                        $output .= '</li>';
                    }
                    $this->end_lvl( $output, $depth, ...array_values( $args ) );
                    $skip_items[$id] = true;
                    if (isset($children_elements[ $id ])){
                    foreach ( $children_elements[ $id ] as $child ) {
                        $skip_items[$child->$id_field] = true;
                        }
                    }
                    $GLOBALS['skip_items'] = $skip_items;
                } else {
                    foreach ( $children_elements[ $id ] as $child ) {

                        if ( ! isset( $newlevel ) ) {
                            $newlevel = true;
                            // Start the child delimiter.
                            $this->start_lvl( $output, $depth, ...array_values( $args ) );
                        }
                        $this->display_element( $child, $children_elements, $max_depth, $depth + 1, $args, $output );
                    }
                }


                unset( $children_elements[ $id ] );
            }

            if ( isset( $newlevel ) && $newlevel ) {
                // End the child delimiter.
                $this->end_lvl( $output, $depth, ...array_values( $args ) );
            }

            // End this element.
            $this->end_el( $output, $element, $depth, ...array_values( $args ) );
        }
    }

	/**
	 * Get CSS column class name
	 *
	 * @param string $width
	 *
	 * @return string
	 */


	private function get_css_column( $width = '25.00%' ) {
		$columns = array(
			3  => '25.00%',
			4  => '33.33%',
			6  => '50.00%',
			8  => '66.66%',
			9  => '75.00%',
			12 => '100.00%',
		);

		$column = array_search( $width, $columns );
		$column = false === $column ? 3 : $column;

		return 'col-md-' . $column;
	}



	/**
	 * Get inline css for mega menu container
	 *
	 * @return string
	 */
	private function get_mega_inline_css() {
		if ( ! $this->enable_mega ) {
			return '';
		}

		$props = array();

		if ( $this->mega_data['custom_width'] ) {
			$props['width'] = $this->mega_data['custom_width'];
		}
        if ( ! empty( $this->mega_data['background'] ) ) {
            if ($this->mega_data['background']['color']) {
                $props['background-color'] = $this->mega_data['background']['color'];
            }

            if ($this->mega_data['background']['image']) {
                $props['background-image'] = 'url(' . $this->mega_data['background']['image'] . ')';
                $props['background-attachment'] = $this->mega_data['background']['attachment'];
                $props['background-repeat'] = $this->mega_data['background']['repeat'];

                if ($this->mega_data['background']['size']) {
                    $props['background-size'] = $this->mega_data['background']['size'];
                }

                if ($this->mega_data['background']['position']['x'] == 'custom') {
                    $position_x = $this->mega_data['background']['position']['custom']['x'];
                } else {
                    $position_x = $this->mega_data['background']['position']['x'];
                }

                if ($this->mega_data['background']['position']['y'] == 'custom') {
                    $position_y = $this->mega_data['background']['position']['custom']['y'];
                } else {
                    $position_y = $this->mega_data['background']['position']['y'];
                }

                $props['background-position'] = $position_x . ' ' . $position_y;
            }
        }

		if ( empty( $props ) ) {
			return '';
		}

		$style = '';
		foreach ( $props as $prop => $value ) {
			$style .= $prop . ':' . esc_attr( $value ) . ';';
		}

		return 'style="' . $style . '"';
	}
	/**
	 * Get mega container tag attributes
	 *
	 * @return string
	 */
	protected function get_mega_container_attrs() {
		if ( ! $this->in_mega ) {
			return '';
		}

		$class = array( 'mega-menu-container' );

		if ( 'custom' == $this->mega_data['width'] ) {
			$class[] = 'container-custom';
			$attrs   = ' class="' . esc_attr( join( ' ', $class ) ) . '"';
			$attrs   .= ' style="width: ' . esc_attr( $this->mega_data['custom_width'] ) . '"';
		} elseif ( in_array( $this->mega_data['width'], array( 'container', 'container-fluid' ) ) ) {
			$class[] = $this->mega_data['width'];
			$attrs   = ' class="' . esc_attr( join( ' ', $class ) ) . '"';
		} else {
			$class[] = 'container';
			$attrs   = ' class="' . esc_attr( join( ' ', $class ) ) . '"';
		}

		return $attrs;
	}
}

