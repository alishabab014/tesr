<?php
/**
 * Mega menu custom walker for sub menu.
 */
class Rozer_Megamenu_Walkersub extends Walker_Nav_Menu {
	var $is_not_insert_first = true;
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$data_defaults = array(
			'1' => array(
				'disable_link'        => '0',
				'column_heading'	  => '0',
				'subtitle'            => '',
				'subtitle_background' => '',
				'custom_class'		  => '',
				'hide_desktop'        => '0',
				'hide_mobile'         => '0',
				'column_width'        => '3',
				'element_type'        => 'none',
				'html_data'           => '',
				'hide_label'          => '0',
			),
			'2' => array(
				'disable_link'        => '0',
				'column_heading'	  => '0',
				'subtitle'            => '',
				'subtitle_background' => '',
				'custom_class'		  => '',
				'hide_desktop'        => '0',
				'hide_mobile'         => '0',
			),
			'3' => array(
				'disable_link'        => '0',
				'column_heading'	  => '0',
				'subtitle'            => '',
				'subtitle_background' => '',
				'custom_class'		  => '',
				'hide_desktop'        => '0',
				'hide_mobile'         => '0',
			)
		);
		// Get data menu item
		$jsondata = Rozer_Megamenu::get_data();
		$data = isset( $jsondata[ $args->menu ][ $item->ID ] ) ? $jsondata[ $args->menu ][ $item->ID ] : null;
		if($data == null) $data = $data_defaults[$item->sub_level];
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		$class_names = $value = '';
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;
		if(isset($data['column_heading']) && $data['column_heading'] == 1 ) {
			$classes[] = 'column-heading';
		}
		if(!isset($data['element_type'])) $data['element_type'] = 'none';
		if(!isset($data['column_width'])) $data['column_width'] = 3;
		// Responsive
		$class_column ='';
		if(isset($data['hide_desktop']) && $data['hide_desktop']) {
			$class_column = ' d-block d-lg-none';
		}
		if(isset($data['hide_mobile']) && $data['hide_mobile'] ) {
			$class_column = ' hidden-sm';
		}
		if( $item->sub_level == 1) {	
			if($this->is_not_insert_first) {
				$output .= '<ul class="mega-nav col-sm-'.$data['column_width'].$class_column.'">';	
				$this->is_not_insert_first = false;	
			}else{
				$output .= '</ul>';
				$output .= '<ul class="mega-nav col-sm-'.$data['column_width'].$class_column.'">';
			}
		}
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = $class_names ? ' class="menu-item ' . esc_attr( $class_names ) . ' menu-item-level'.$item->sub_level.$class_column.'"' : '';
		$output .= $indent . '<li ' . $value . $class_names . '>';
		if ( $item->sub_level == 1 || $item->sub_level == 2 )
			$output .= '<div class="menu-item-inner">';
		// Set tag a
		$item_output = ( isset( $args->before ) ? $args->before : '' );
		$atts = array();
		$atts['title']  = ! empty( $item->title ) ? $item->title : '';
		$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
		$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
		$atts['href']   = ! empty( $item->url )        ? $item->url        : '';
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );
		$atts['class'] = isset( $atts['class'] ) ? $atts['class'] . ' menu-item-link' : 'menu-item-link';
		if( $this->has_children){
			$atts['class'] .= ' has-children';
		}
		if( $item->sub_level == 1 && $data['column_heading'] == 1){
			$atts['class'] .= ' column-heading';
		}
		if(isset($data['disable_link']) && $data['disable_link'] == 1 ) { 
			$atts['class'] .= ' link-disabled';
		}
		if(isset($data['hide_label']) && $data['hide_label'] == 1 ) { 
			$atts['class'] .= ' d-lg-none';
		}
		if($item->sub_level == 1 && $data['element_type'] == 'html' && $data['html_data'] != ''){
			$atts['class'] .= ' has-children';
		}
		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}
		$item_output .= '<a ' . $attributes . ' >';
		$item_output .= '<span class="menu_title">' . esc_attr( $item->title ) ;
		if(isset($data['subtitle'])) {
			$style = '';
			$style2 = '';
			if(isset($data['subtitle_background'])) $style = 'style="background: '. $data['subtitle_background'] .'"';
			if(isset($data['subtitle_background'])) $style2 = 'style="border-color: '. $data['subtitle_background'] .'"';
			$item_output .= '<span class="menu-label" '. $style .'>'. esc_attr( $data['subtitle'] ) .'<em '. $style2.'></em></span>';
		}
		$item_output .= '</span>';
		if($this->has_children || ($item->sub_level == 1 && $data['element_type'] == 'html' && $data['html_data'] != '')) {
			if($item->sub_level != 1) {
				$item_output .= '<i class="icon-rt-arrow-right mm-has-children"></i>';
			}
			$item_output .= '<span class="navbar-toggler collapsed"><i class="icon-rt-arrow-down" aria-hidden="true"></i></span>';
		}
		$item_output .= '</a>';
		// Insert element
		if ( $item->sub_level == 1 && isset( $data['element_type'] ) && $data['element_type'] ) {
			$element_content = NULL;
			if ( $data['element_type'] == 'html' ) {
				$element_content = $data['html_data'];
				$element_content = do_shortcode( shortcode_unautop( $element_content ) );
			}
			$item_output .= $element_content ? '<div class="sub-menu content-element ' . $data['element_type'] . '">' . $element_content . '</div>' : NULL;
		}
		$item_output .= ( isset( $args->after ) ? $args->after : '' );
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
	function end_el( &$output, $item, $depth = 0, $args = array() ) {
		if ( $item->sub_level == 1 || $item->sub_level == 2 ) $output .= '</div>';
		$output .= '</li>';
	}
}
