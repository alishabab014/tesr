<?php
/**
 * Mega menu custom walker.
 */
class Rozer_Megamenu_Walker extends Walker_Nav_Menu {
	private $style   = '';
	private $is_mega = false;
	/**
	 * Starts the list before the elements are added.
	 *
	 * @see Walker::start_lvl()
	 * @since 3.0.0
	 * @param string  $output Passed by reference. Used to append additional content.
	 * @param int     $depth  Depth of menu item. Used for padding.
	 * @param array   $args   An array of arguments. @see wp_nav_menu()
	 */
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		if ( $depth == 0 ) {
			if ( $this->is_mega ) {
				$output .= '';
			} else {
				$output .= '<div class="dropdown-menu flyout-submenu"><div class="mega-dropdown-inner"><ul class="sub-menu" ' . $this->style . '>';
			}
		} else if ( $this->is_mega ) {
			$output .= '';
		} else {
			$output .= '<ul class="sub-menu">';
		}
	}
	/**
	 * Ends the list of after the elements are added.
	 *
	 * @see Walker::end_lvl()
	 * @since 3.0.0
	 * @param string  $output Passed by reference. Used to append additional content.
	 * @param int     $depth  Depth of menu item. Used for padding.
	 * @param array   $args   An array of arguments. @see wp_nav_menu()
	 */
	function end_lvl( &$output, $depth = 0, $args = array() ) {
		if ( $depth == 0 ) {
			if ( $this->is_mega ) {
				$output .= '';
			} else {
				$output .= '</ul></div></div>';
			}
		} else if ( $this->is_mega ) {
			$output .= '';
		} else {
			$output .= '</ul>';
		}
	}
	/**
	 * Starting build menu element
	 *
	 * @param string  $output       Passed by reference. Used to append additional content.
	 * @param object  $item         Menu item data object.
	 * @param int     $depth        Depth of menu item. Used for padding.
	 * @param int     $current_page Menu item ID.
	 * @param object  $args
	 */
	function start_el( &$output, $item, $depth = 0, $args = array(), $current_object_id = 0 ) {
		$menu_id = isset( $args->menu->term_id ) ? $args->menu->term_id : $args->menu;
		$data = Rozer_Megamenu::get_data();
		$data = isset( $data[ $menu_id ][ $item->ID ] ) ? $data[ $menu_id ][ $item->ID ] : array();
		$data['level'] = $depth;
		//Start menu item link
		$atts = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
		$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
		$atts['href']   = ! empty( $item->url )        ? $item->url        : '';
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );
		if( isset( $args->is_mobile ) && $args->is_mobile ) {
			$atts['class'] = isset( $atts['class'] ) ? $atts['class'] . ' menu-item-link' : 'menu-item-link';
			$attributes = '';
			foreach ( $atts as $attr => $value ) {
				if ( ! empty( $value ) ) {
					$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
					$attributes .= ' ' . $attr . '="' . $value . '"';
				}
			}
			$item_output =
				'<div class="item-link-outer"><a ' . $attributes . '><span class="menu_title">' . esc_html( $item->title ) . '</span></a>' . ( $this->has_children ? '<i class="has-children-mobile icon-rt-arrow-down"></i>' : '' ) . '</div>';
		} else {
			$atts['class'] = isset( $atts['class'] ) ? $atts['class'] . ' menu-item-link ' : 'menu-item-link ';
			$attributes = '';
			foreach ( $atts as $attr => $value ) {
				if ( ! empty( $value ) ) {
					$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
					$attributes .= ' ' . $attr . '="' . $value . '"';
				}
			}
			$item_output = '';
			$item_output .= '<a ' . $attributes . ' >';
			if(isset($data['use_icon']) && $data['use_icon']) {
				if(isset($data['icon']) && $data['icon']){
					$item_output .= '<img src="'. $data['icon'] .'" alt="' . esc_attr( $item->title ) . '" />';
				}
			}
			$item_output .= '<span class="menu_title">' . esc_attr( $item->title ) ;
			if(isset($data['subtitle']) && $data['subtitle']) {
				$style = '';
				$style2 = '';
				if(isset($data['subtitle_background'])) $style = 'style="background: '. $data['subtitle_background'] .'"';
				if(isset($data['subtitle_background'])) $style2 = 'style="border-color: '. $data['subtitle_background'] .'"';
				$item_output .= '<span class="menu-label" '. $style .'>'. esc_attr( $data['subtitle'] ) .'<em '. $style2.'></em></span>';
			}
			$item_output .= '</span>';
			if ( $this->has_children ) {
				$item_output .= '<i class="icon-rt-arrow-right mm-has-children"></i>';
				$item_output .= '<span class="navbar-toggler collapsed"><i class="icon-rt-arrow-down" aria-hidden="true"></i></span>';
			}
			$item_output .= '</a>';
		}
		//Start submenu
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		if(isset( $data['custom_class'] )) $classes[] = $data['custom_class'];
		$style_inline = array();
		if ( $depth == 0 ) {
			$id_menu = isset( $args->menu->term_id ) ? $args->menu->term_id : $args->menu;
			$count_menu_items = count( self::get_menu_items( $id_menu, $item->ID, 1 ) );
			if(!isset($data['submenu_type'])) $data['submenu_type'] = 'flyout';
			if ( $data['submenu_type'] == 'mega' && $count_menu_items ) {
				$this->is_mega = true;
				$classes[] = 'mega-menu';
				$data_width = '';
				if(!isset($data['width_type'])) $data['width_type'] = 'fullwidth';
				if ( $data['width_type'] == 'fixed' && (int) $data['width'] ) {
					$data_width = 'data-width="'.(int) $data['width'].'"';
					$style_width = 'style="width:'.$data['width'].'px;"';
				} else {
					$data_width = 'data-width="full"';
					$classes[] = 'mega-full';
					$style_width = '';
				}
				$item_output .= '<div ' . $data_width . '  class="dropdown-menu mega-dropdown-menu '. ( ($data['width_type'] == 'fullwidth' ) ? esc_attr( $data['width_type'] )  : 'submenu-constant-width' ).'" '.$style_width.'><div class="container"><div class="mega-dropdown-inner row">';
				$submenu_items_elment = self::submenu( $id_menu, $item->ID );
				if ( $submenu_items_elment ) {
					$item_output .= $submenu_items_elment . '</ul>';
				}
				$item_output .= '</div></div></div>';
			} else {
				$classes[] = 'flyout-menu';
				$this->is_mega = false;
			}
		} else {
			if(isset($data['column_heading']) &&  $data['column_heading'] == 1 ) {
				$classes[] = 'column-heading';
			}
		}
		// Menu item
		$classes[] = 'menu-item-lv' . absint( $depth );
		// Responsive
		if(isset($data['hide_desktop']) && $data['hide_desktop']) {
			$classes[] = ' d-block d-lg-none';
		}
		if(isset($data['hide_mobile']) && $data['hide_mobile']) {
			$classes[] = ' hidden-sm';
		}
		// Generate class
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
		if ( $depth != 0 && $this->is_mega ) {
			$output .= '';
			$item_output = '';
		} else {
 			$output .= '<li ' . $class_names . '>';
 		}
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
	/**
	 * Ends the list of after the elements are added.
	 *
	 * @see Walker::end_lvl()
	 * @since 3.0.0
	 * @param string  $output Passed by reference. Used to append additional content.
	 * @param int     $depth  Depth of menu item. Used for padding.
	 * @param array   $args   An array of arguments. @see wp_nav_menu()
	 */
	function end_el( &$output, $item, $depth = 0, $args = array() ) {
		if ( $depth != 0 && $this->is_mega ) {
			$output .= '';
		} else {
 			$output .= '</li>';
 		}
	}
	/**
	 * Get menu items.
	 *
	 * @param   mixed    $menu
	 * @param   integer  $parent_id
	 * @param   integer  $depth
	 *
	 * @return  array
	 */
	public static function get_menu_items( $menu, $parent_id = 0, $depth = 1 ) {
		// Get all nav menu items.
		$menu_items = wp_get_nav_menu_items( $menu );
		$extracted_items = array();
		if ( $menu_items ) {
			$parents_set = array();
			foreach ( $menu_items as $item ) {
				if ( ! $parent_id ) {
					if ( $depth == 1 ) {
						// Get only the 1st level items.
						if ( ! $item->menu_item_parent ) {
							array_push( $extracted_items, $item );
						}
					}
				} else {
					// Get all sub menu items.
					if ( $item->menu_item_parent == $parent_id || in_array( $item->menu_item_parent, $parents_set ) ) {
						if ( $item->menu_item_parent == $parent_id ) {
							$parents_set[0] = $parent_id;
						}
						// Push current item id to parents list
						// used for calculating menuitem level
						// and get children menu items without recursiving.
						$sub_level = array_search( $item->menu_item_parent, $parents_set );
						$parents_set[ $sub_level + 1 ] = $item->ID;
						// Set level for current menu item.
						$item->sub_level = $sub_level + 1;
						// Place current item in the list.
						if ( $sub_level < $depth ) {
							array_push( $extracted_items, $item );
						}
					}
				}
			}
		}
		return $extracted_items;
	}
	public static function submenu( $menu_type, $menu_id ) {
		// Get all menu items.
		$menu_items = self::get_menu_items( $menu_type, $menu_id, 99 );
		// Prepare nav menu arguments.
		$args = array(
			'menu'        => $menu_type,
			'container'   => false,
			'menu_class'  => 'menu',
			'echo'        => true,
			'items_wrap'  => '<ul class="%2$s">%3$s</ul>',
			'count_items' => count( $menu_items ),
		);
		// Get mega menu data.
		$data = Rozer_Megamenu::get_data();
		if(isset($data[ $menu_type ][ $menu_id ])) $data = $data[ $menu_type ][ $menu_id ];
		$submenu_items_elment = self::submenu_child( $menu_items, 0, ( object ) $args );
		return $submenu_items_elment;
	}
	/**
	 * Process sub menu items.
	 *
	 * @return  mixed
	 */
	public static function submenu_child() {
		$args   = func_get_args();
		$walker = new Rozer_Megamenu_Walkersub;
		return call_user_func_array( array( &$walker, 'walk' ), $args );
	}
}