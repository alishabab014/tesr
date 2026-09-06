<?php

Kirki::add_section( 'page_title', array(
    'priority'    => 52,
    'title'       => esc_html__( 'Page title', 'rozer' ),
) );
Kirki::add_field( 'option', [
	'type'        => 'radio-image',
	'settings'    => 'page_title_design',
	'label'       => esc_html__( 'Page title design', 'rozer' ),
	'section'     => 'page_title',
	'default'     => '2',
	'choices'     => [
		'1'   => get_template_directory_uri() . '/assets/images/customizer/page-title-1.jpg',
		'2' => get_template_directory_uri() . '/assets/images/customizer/page-title-2.jpg',
	],
] );
Kirki::add_field( 'option', [
	'type'        => 'radio-buttonset',
	'settings'    => 'page_title_align',
	'label'       => esc_html__( 'Align', 'rozer' ),
	'section'     => 'page_title',
	'default'     => 'left',
	'choices'     => [
		'left'   => esc_html__( 'Left', 'rozer' ),
		'center' => esc_html__( 'Center', 'rozer' ),
	],
] );
Kirki::add_field( 'option', [
	'type'        => 'radio-buttonset',
	'settings'    => 'page_title_size',
	'label'       => esc_html__( 'Size', 'rozer' ),
	'section'     => 'page_title',
	'default'     => 'medium',
	'choices'     => [
		'small'   => esc_html__( 'Small', 'rozer' ),
		'medium' => esc_html__( 'Medium', 'rozer' ),
		'large'  => esc_html__( 'Large', 'rozer' ),
	],
] );
Kirki::add_field( 'option', [
	'type'        => 'radio-image',
	'settings'    => 'page_title_color',
	'label'       => esc_html__( 'Text color', 'rozer' ),
	'section'     => 'page_title',
	'default'     => 'dark',
	'choices'     => [
		'dark'   => get_template_directory_uri() . '/assets/images/customizer/text-dark.svg',
		'light' => get_template_directory_uri() . '/assets/images/customizer/text-light.svg',
	],
	'active_callback'  => [
		[
			'setting'  => 'page_title_design',
			'operator' => '===',
			'value'    => '1',
		],
	]
] );
Kirki::add_field( 'option', [
	'type'        => 'background',
	'settings'    => 'page_title_background',
	'label'       => esc_html__( 'Background', 'rozer' ),
	'section'     => 'page_title',
	'default'     => [
		'background-color'      => 'rgba(20,20,20,.8)',
		'background-image'      => '',
		'background-repeat'     => 'repeat',
		'background-position'   => 'center center',
		'background-size'       => 'cover',
		'background-attachment' => 'scroll',
	],
	'transport'   => 'auto',
	'output'      => [
		[
			'element' => '.page-title-section',
		],
	],
	'active_callback'  => [
		[
			'setting'  => 'page_title_design',
			'operator' => '===',
			'value'    => '1',
		],
	]
] );
