<?php

$topbar_content = array(
	'none' => esc_html__( '-----', 'rozer' ),
	'account' => esc_html__( 'Account', 'rozer' ),
	'topbar-menu' => esc_html__( 'Topbar menu', 'rozer' ),
	'social' => esc_html__( 'Social list', 'rozer' ),
	'language' => esc_html__( 'Language switcher', 'rozer' ),
	'currency' => esc_html__( 'Currency switcher', 'rozer' ),
	'html1' => esc_html__( 'HTML 1', 'rozer' ),
	'html2' => esc_html__( 'HTML 2', 'rozer' ),
);
Kirki::add_section( 'header_topbar', array(
    'priority'    => 1,
    'title'       => esc_html__( 'Header Topbar', 'rozer' ),
    'panel'       => 'header',
) );
Kirki::add_field( 'option', [
	'type'        => 'custom',
	'settings'    => 'header_topbar_custom1',
	'section'     => 'header_topbar',
	'default'         => '<div class="customize-title-divider">' . __( 'General', 'rozer' ) . '</div>',
] );
Kirki::add_field( 'option', [
	'type'        => 'switch',
	'settings'    => 'header_topbar_active',
	'label'       => esc_html__( 'Active topbar', 'rozer' ),
	'section'     => 'header_topbar',
	'default'     => '1',
	'priority'    => 10,
	'choices'     => [
		'on'  => esc_html__( 'Enable', 'rozer' ),
		'off' => esc_html__( 'Disable', 'rozer' ),
	],
] );
Kirki::add_field( 'option', [
	'type'        => 'color',
	'settings'    => 'header_topbar_background',
	'label'       => esc_html__( 'Background', 'rozer' ),
	'section'     => 'header_topbar',
	'default'     => '#0088CC',
	'choices'     => [
		'alpha' => true,
	],
	'transport'   => 'postMessage',
] );
Kirki::add_field( 'option', [
	'type'        => 'radio-image',
	'settings'    => 'header_topbar_text',
	'label'       => esc_html__( 'Text color', 'rozer' ),
	'section'     => 'header_topbar',
	'default'     => 'dark',
	'choices'     => [
		'dark'   => get_template_directory_uri() . '/assets/images/customizer/text-dark.svg',
		'light' => get_template_directory_uri() . '/assets/images/customizer/text-light.svg',
	],
] );
Kirki::add_field( 'option', [
	'type'        => 'slider',
	'settings'    => 'header_topbar_font',
	'label'       => esc_html__( 'Text font size (px)', 'rozer' ),
	'section'     => 'header_topbar',
	'default'     => 12,
	'choices'     => [
		'min'  => 0,
		'max'  => 30,
		'step' => 1,
	],
	'transport'   => 'postMessage',
] );
Kirki::add_field( 'option', [
	'type'        => 'custom',
	'settings'    => 'header_topbar_custom2',
	'section'     => 'header_topbar',
	'default'         => '<div class="customize-title-divider">' . __( 'Layout', 'rozer' ) . '</div>',
] );
Kirki::add_field( 'option', [
	'type'        => 'repeater',
	'label'       => esc_html__( 'Left position', 'rozer' ),
	'section'     => 'header_topbar',
	'row_label' => [
		'type'  => 'field',
		'value' => esc_html__( 'Block: ', 'rozer' ),
		'field' => 'block',
	],
	'button_label' => esc_html__('Add a block', 'rozer' ),
	'settings'     => 'topbar_left',
	'default'      => [
	],
	'fields' => [
		'block' => [
			'type'        => 'select',
			'label'       => esc_html__( 'Select block', 'rozer' ),
			'default'     => '',
			'choices'     => $topbar_content,
		],
	]
] );
Kirki::add_field( 'option', [
	'type'        => 'repeater',
	'label'       => esc_html__( 'Center position', 'rozer' ),
	'section'     => 'header_topbar',
	'row_label' => [
		'type'  => 'field',
		'value' => esc_html__( 'Block: ', 'rozer' ),
		'field' => 'block',
	],
	'button_label' => esc_html__('Add a block', 'rozer' ),
	'settings'     => 'topbar_center',
	'default'      => [
	],
	'fields' => [
		'block' => [
			'type'        => 'select',
			'label'       => esc_html__( 'Select block', 'rozer' ),
			'default'     => '',
			'choices'     => $topbar_content,
		],
	]
] );
Kirki::add_field( 'option', [
	'type'        => 'repeater',
	'label'       => esc_html__( 'Right position', 'rozer' ),
	'section'     => 'header_topbar',
	'row_label' => [
		'type'  => 'field',
		'value' => esc_html__( 'Block: ', 'rozer' ),
		'field' => 'block',
	],
	'button_label' => esc_html__('Add a block', 'rozer' ),
	'settings'     => 'topbar_right',
	'default'      => [
	],
	'fields' => [
		'block' => [
			'type'        => 'select',
			'label'       => esc_html__( 'Select block', 'rozer' ),
			'default'     => '',
			'choices'     => $topbar_content,
		],
	]
] );