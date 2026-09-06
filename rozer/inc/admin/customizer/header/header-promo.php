<?php

Kirki::add_section( 'header_promo', array(
    'priority'    => 1,
    'title'       => esc_html__( 'Element - Promo text', 'rozer' ),
    'panel'       => 'header',
) );
Kirki::add_field( 'option', [
	'type'        => 'switch',
	'settings'    => 'header_promo_active',
	'label'       => esc_html__( 'Active promo block', 'rozer' ),
	'section'     => 'header_promo',
	'default'     => 'on',
	'choices'     => [
		'on'  => esc_html__( 'Yes', 'rozer' ),
		'off' => esc_html__( 'No', 'rozer' ),
	],
	'transport'   => 'postMessage',
] );
Kirki::add_field( 'option', [
	'type'        => 'radio-buttonset',
	'settings'    => 'header_promo_type',
	'label'       => esc_html__( 'Select type', 'rozer' ),
	'section'     => 'header_promo',
	'default'     => 'text',
	'choices'     => [
		'text'    => esc_html__( 'Text', 'rozer' ),
		'image'   => esc_html__( 'Image', 'rozer' ),
	],
	'transport'   => 'postMessage',
] );
Kirki::add_field( 'option', [
	'type'        => 'image',
	'settings'    => 'header_promo_image',
	'label'       => esc_html__( 'Upload your image', 'rozer' ),
	'section'     => 'header_promo',
	'default'     => '',
	'active_callback' => [
		[
			'setting'  => 'header_promo_type',
			'operator' => '==',
			'value'    => 'image',
		]
	],
	'transport'   => 'postMessage',
] );
Kirki::add_field( 'option', [
	'type'     => 'text',
	'settings' => 'header_promo_link',
	'label'    => esc_html__( 'Link', 'rozer' ),
	'section'  => 'header_promo',
	'active_callback' => [
		[
			'setting'  => 'header_promo_type',
			'operator' => '==',
			'value'    => 'image',
		]
	],
	'transport'   => 'postMessage',
] );
Kirki::add_field( 'option', [
	'type'     => 'textarea',
	'settings' => 'header_promo_text',
	'label'    => esc_html__( 'Add your text', 'rozer' ),
	'description'    => esc_html__( 'Allow using HTML or shortcode', 'rozer' ),
	'section'  => 'header_promo',
	'active_callback' => [
		[
			'setting'  => 'header_promo_type',
			'operator' => '==',
			'value'    => 'text',
		]
	],
	'transport'   => 'postMessage',
] );
Kirki::add_field( 'option', [
	'type'        => 'switch',
	'settings'    => 'header_promo_close',
	'label'       => esc_html__( 'Show close button', 'rozer' ),
	'section'     => 'header_promo',
	'default'     => 'on',
	'choices'     => [
		'on'  => esc_html__( 'Yes', 'rozer' ),
		'off' => esc_html__( 'No', 'rozer' ),
	],
	'transport'   => 'postMessage',
] );
Kirki::add_field( 'option', [
	'type'        => 'custom',
	'settings'    => 'header_promo_style',
	'section'     => 'header_promo',
	'default'         => '<div class="sub-divider">' . __( 'Promo Style', 'rozer' ) . '</div>',
] );
Kirki::add_field( 'option', [
	'type'        => 'slider',
	'settings'    => 'header_promo_height',
	'label'       => esc_html__( 'Height', 'rozer' ),
	'section'     => 'header_promo',
	'default'     => 50,
	'choices'     => [
		'min'  => 0,
		'max'  => 200,
		'step' => 1,
	],
	'transport'   => 'postMessage',
	'active_callback' => [
		[
			'setting'  => 'header_promo_type',
			'operator' => '==',
			'value'    => 'text',
		]
	],
] );
Kirki::add_field( 'option', [
	'type'        => 'color',
	'settings'    => 'header_promo_color',
	'label'       => esc_html__( 'Color', 'rozer' ),
	'section'     => 'header_promo',
	'default'     => '#222222',
	'choices'     => [
		'alpha' => true,
	],
	'active_callback' => [
		[
			'setting'  => 'header_promo_type',
			'operator' => '==',
			'value'    => 'text',
		]
	],
	'transport'   => 'postMessage',
] );
Kirki::add_field( 'option', [
	'type'        => 'color',
	'settings'    => 'header_promo_bground',
	'label'       => esc_html__( 'Background', 'rozer' ),
	'section'     => 'header_promo',
	'default'     => 'rgba(255,255,255,0)',
	'choices'     => [
		'alpha' => true,
	],
	'transport'   => 'postMessage',
] );