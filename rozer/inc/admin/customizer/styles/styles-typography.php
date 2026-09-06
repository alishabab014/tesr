<?php 

Kirki::add_section( 'typography', array(
    'title'       => esc_html__( 'Typography', 'rozer' ),
    'panel'       => 'styles',
) );
Kirki::add_field( 'option', [
	'type'        => 'typography',
	'settings'    => 'primary_font',
	'label'       => esc_html__( 'Primary font', 'rozer' ),
	'section'     => 'typography',
	'default'     => [
		'font-family'    => 'Open Sans',
		'font-size'      => '1.4rem',
		'line-height'    => '1.5',
		'color'          => '#555555',
	],
	'transport'   => 'auto',
	'output'      => [
		[
			'element' => 'body',
		],
	],
] );
Kirki::add_field( 'option', [
	'type'        => 'toggle',
	'settings'    => 'secondary_font_active',
	'label'       => esc_html__( 'Active secondary font', 'rozer' ),
	'section'     => 'typography',
	'default'     => '1',
] );
Kirki::add_field( 'option', [
	'type'        => 'typography',
	'settings'    => 'secondary_font',
	'label'       => esc_html__( 'Secondary font', 'rozer' ),
	'section'     => 'typography',
	'default'     => [
		'font-family'    => 'Open Sans',
		'variant'        => '700',
		'font-size'      => '1.4rem',
		'line-height'    => '1.5',
		'text-transform' => 'none',
		'color'          => '#1d1d1d',
	],
	'active_callback' => [
		[
			'setting'  => 'secondary_font_active',
			'operator' => '==',
			'value'    => '1',
		]
	],
] );
Kirki::add_field( 'option', [
	'type'        => 'multicheck',
	'settings'    => 'secondary_font_use',
	'label'       => esc_html__( 'Use sencondary font for: ', 'rozer' ),
	'section'     => 'typography',
	'default'     => array('title'),
	'choices'     => [
		'title'   => esc_html__( 'Heading title', 'rozer' ),
		'testimonial' => esc_html__( 'Testimonial', 'rozer' ),
	],
] );