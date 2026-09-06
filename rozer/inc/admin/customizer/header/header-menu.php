<?php

Kirki::add_section( 'header_menu', array(
    'priority'    => 1,
    'title'       => esc_html__( 'Element - Menu', 'rozer' ),
    'panel'       => 'header',
) );
Kirki::add_field( 'option', [
	'type'        => 'custom',
	'settings'    => 'header_horizonal_menu',
	'section'     => 'header_menu',
	'default'         => '<div class="customize-title-divider">' . __( 'Horizontal menu', 'rozer' ) . '</div>',
] );
Kirki::add_field( 'option', [
	'type'        => 'color',
	'settings'    => 'hmenu_background',
	'label'       => esc_html__( 'Menu Background', 'rozer' ),
	'section'     => 'header_menu',
	'default'     => '#146cda',
	'choices'     => [
		'alpha' => true,
	],
	'transport'   => 'postMessage',
] );
Kirki::add_field( 'option', [
	'type'        => 'custom',
	'settings'    => 'hmenu_main_items',
	'section'     => 'header_menu',
	'default'         => '<div class="sub-divider">' . __( 'Main items', 'rozer' ) . '</div>',
] );
Kirki::add_field( 'option', [
	'type'        => 'radio-buttonset',
	'settings'    => 'hmenu_item_align',
	'label'       => esc_html__( 'Item align', 'rozer' ),
	'section'     => 'header_menu',
	'default'     => 'left',
	'choices'     => [
		'left'   => esc_html__( 'Left', 'rozer' ),
		'center' => esc_html__( 'Center', 'rozer' ),
		'right'  => esc_html__( 'Right', 'rozer' ),
	],
	'transport' => 'postMessage'
] );
Kirki::add_field( 'option', [
	'type'        => 'color',
	'settings'    => 'hmenu_item_color',
	'label'       => esc_html__( 'Color', 'rozer' ),
	'section'     => 'header_menu',
	'default'     => '#ffffff',
	'choices'     => [
		'alpha' => true,
	],
	'transport'   => 'postMessage',
] );
Kirki::add_field( 'option', [
	'type'        => 'color',
	'settings'    => 'hmenu_item_color_active',
	'label'       => esc_html__( 'Active Color', 'rozer' ),
	'section'     => 'header_menu',
	'default'     => '#aed2ff',
	'choices'     => [
		'alpha' => true,
	],
	'transport'   => 'postMessage',
] );
Kirki::add_field( 'option', [
	'type'        => 'color',
	'settings'    => 'hmenu_item_background_color',
	'label'       => esc_html__( 'Background Color', 'rozer' ),
	'section'     => 'header_menu',
	'default'     => 'rgba(255,255,255,0)',
	'choices'     => [
		'alpha' => true,
	],
	'transport'   => 'postMessage',
] );
Kirki::add_field( 'option', [
	'type'        => 'color',
	'settings'    => 'hmenu_item_background_color_active',
	'label'       => esc_html__( 'Active Background Color', 'rozer' ),
	'section'     => 'header_menu',
	'default'     => 'rgba(255,255,255,0)',
	'choices'     => [
		'alpha' => true,
	],
	'transport'   => 'postMessage',
] );
Kirki::add_field( 'option', [
	'type'        => 'slider',
	'settings'    => 'hmenu_item_font',
	'label'       => esc_html__( 'Font size', 'rozer' ),
	'section'     => 'header_menu',
	'default'     => 14,
	'choices'     => [
		'min'  => 0,
		'max'  => 50,
		'step' => 1,
	],
	'transport'  => 'postMessage',
] );
Kirki::add_field( 'option', [
	'type'        => 'slider',
	'settings'    => 'hmenu_item_space',
	'label'       => esc_html__( 'Space between items', 'rozer' ),
	'section'     => 'header_menu',
	'default'     => 30,
	'choices'     => [
		'min'  => 0,
		'max'  => 50,
		'step' => 1,
	],
	'transport'  => 'postMessage',
] );
Kirki::add_field( 'option', [
	'type'        => 'custom',
	'settings'    => 'hmenu_submenu',
	'section'     => 'header_menu',
	'default'         => '<div class="sub-divider">' . __( 'Submenu', 'rozer' ) . '</div>',
] );
Kirki::add_field( 'option', [
	'type'        => 'custom',
	'settings'    => 'header_vertical_menu',
	'section'     => 'header_menu',
	'default'         => '<div class="customize-title-divider">' . __( 'Vertical menu', 'rozer' ) . '</div>',
] );
Kirki::add_field( 'option', [
	'type'        => 'toggle',
	'settings'    => 'vertical_menu_active',
	'label'       => esc_html__( 'Active vertical menu', 'rozer' ),
	'section'     => 'header_menu',
	'default'     => '1',
] );
Kirki::add_field( 'option', [
	'type'        => 'custom',
	'settings'    => 'vmenu_title_section',
	'section'     => 'header_menu',
	'default'         => '<div class="sub-divider">' . __( 'The title', 'rozer' ) . '</div>',
	'active_callback' => [
		[
			'setting'  => 'vertical_menu_active',
			'operator' => '==',
			'value'    => true,
		]
	],
] );
Kirki::add_field( 'option', [
	'type'     => 'text',
	'settings' => 'vmenu_title',
	'label'    => esc_html__( 'Title', 'rozer' ),
	'section'  => 'header_menu',
	'default'  => esc_html__( 'Categories', 'rozer' ),
	'transport'  => 'postMessage',
	'active_callback' => [
		[
			'setting'  => 'vertical_menu_active',
			'operator' => '==',
			'value'    => true,
		]
	],
] );
Kirki::add_field( 'option', [
	'type'        => 'slider',
	'settings'    => 'vmenu_title_size',
	'label'       => esc_html__( 'Title size', 'rozer' ),
	'section'     => 'header_menu',
	'default'     => 14,
	'choices'     => [
		'min'  => 0,
		'max'  => 50,
		'step' => 1,
	],
	'transport'  => 'postMessage',
	'active_callback' => [
		[
			'setting'  => 'vertical_menu_active',
			'operator' => '==',
			'value'    => true,
		]
	],
] );
Kirki::add_field( 'option', [
	'type'        => 'slider',
	'settings'    => 'vmenu_title_width',
	'label'       => esc_html__( 'Title width', 'rozer' ),
	'section'     => 'header_menu',
	'default'     => 210,
	'choices'     => [
		'min'  => 0,
		'max'  => 500,
		'step' => 1,
	],
	'transport'  => 'postMessage',
	'active_callback' => [
		[
			'setting'  => 'vertical_menu_active',
			'operator' => '==',
			'value'    => true,
		]
	],
] );
Kirki::add_field( 'option', [
	'type'        => 'color',
	'settings'    => 'vmenu_title_bground',
	'label'       => esc_html__( 'Title background', 'rozer' ),
	'section'     => 'header_menu',
	'default'     => 'rgba(255,255,255,0)',
	'choices'     => [
		'alpha' => true,
	],
	'transport'   => 'postMessage',
	'active_callback' => [
		[
			'setting'  => 'vertical_menu_active',
			'operator' => '==',
			'value'    => true,
		]
	],
] );
Kirki::add_field( 'option', [
	'type'        => 'color',
	'settings'    => 'vmenu_title_color',
	'label'       => esc_html__( 'Title color', 'rozer' ),
	'section'     => 'header_menu',
	'default'     => '#ffffff',
	'choices'     => [
		'alpha' => true,
	],
	'transport'   => 'postMessage',
	'active_callback' => [
		[
			'setting'  => 'vertical_menu_active',
			'operator' => '==',
			'value'    => true,
		]
	],
] );
Kirki::add_field( 'option', [
	'type'        => 'custom',
	'settings'    => 'vmenu_items_section',
	'section'     => 'header_menu',
	'default'         => '<div class="sub-divider">' . __( 'Menu items', 'rozer' ) . '</div>',
	'active_callback' => [
		[
			'setting'  => 'vertical_menu_active',
			'operator' => '==',
			'value'    => true,
		]
	],
] );
Kirki::add_field( 'option', [
	'type'        => 'radio-buttonset',
	'settings'    => 'vmenu_action',
	'label'       => esc_html__( 'Show menu items by', 'rozer' ),
	'section'     => 'header_menu',
	'default'     => 'click',
	'choices'     => [
		'click'   => esc_html__( 'Click', 'rozer' ),
		'hover' => esc_html__( 'Hover', 'rozer' ),
	],
	'transport' => 'postMessage',
	'active_callback' => [
		[
			'setting'  => 'vertical_menu_active',
			'operator' => '==',
			'value'    => true,
		]
	],
] );
Kirki::add_field( 'option', [
	'type'        => 'slider',
	'settings'    => 'vmenu_items_width',
	'label'       => esc_html__( 'Items width', 'rozer' ),
	'section'     => 'header_menu',
	'default'     => 270,
	'choices'     => [
		'min'  => 0,
		'max'  => 500,
		'step' => 1,
	],
	'transport'  => 'postMessage',
	'active_callback' => [
		[
			'setting'  => 'vertical_menu_active',
			'operator' => '==',
			'value'    => true,
		]
	],
] );
