<?php

Kirki::add_section( 'header_search', array(
    'priority'    => 1,
    'title'       => esc_html__( 'Element - Search', 'rozer' ),
    'panel'       => 'header',
) );
Kirki::add_field( 'option', [
	'type'     => 'text',
	'settings' => 'header_search_placeholder',
	'label'    => esc_html__( 'Placeholder text', 'rozer' ),
	'section'  => 'header_search',
	'default'  => esc_html__( 'Enter your search key', 'rozer' ),
	'transport'   => 'postMessage',
] );
Kirki::add_field( 'option', [
	'type'        => 'switch',
	'settings'    => 'header_search_categories',
	'label'       => esc_html__( 'Active categories', 'rozer' ),
	'section'     => 'header_search',
	'default'     => 'off',
	'choices'     => [
		'on'  => esc_html__( 'Yes', 'rozer' ),
		'off' => esc_html__( 'No', 'rozer' ),
	],
	'transport'   => 'postMessage',
] );
Kirki::add_field( 'option', [
	'type'        => 'number',
	'settings'    => 'header_search_categories_depth',
	'label'       => esc_html__( 'Categories depth', 'rozer' ),
	'section'     => 'header_search',
	'default'     => 1,
	'choices'     => [
		'min'  => 1,
		'max'  => 10,
		'step' => 1,
	],
	'active_callback'  => [
		[
			'setting'  => 'header_search_categories',
			'operator' => '===',
			'value'    => true,
		],
	],
	'transport'   => 'postMessage',
] );
Kirki::add_field( 'option', [
	'type'        => 'repeater',
	'label'       => esc_html__( 'Keyword list', 'rozer' ),
	'section'     => 'header_search',
	'priority'    => 10,
	'row_label' => [
		'type'  => 'field',
		'value' => esc_html__( 'Keyword', 'rozer' ),
		'field' => 'keyword',
	],
	'button_label' => esc_html__('Add keyword', 'rozer' ),
	'settings'     => 'header_search_keywords',
	'default'      => [],
	'fields' => [
		'keyword' => [
			'type'        => 'text',
			'label'       => esc_html__( 'Keyword', 'rozer' ),
			'default'     => '',
		],
	],
] );
Kirki::add_field( 'option', [
	'type'        => 'custom',
	'settings'    => 'header_search_custom',
	'section'     => 'header_search',
	'default'         => '<div class="customize-title-divider">' . __( 'Results search', 'rozer' ) . '</div>',
] );
Kirki::add_field( 'option', [
	'type'        => 'select',
	'settings'    => 'header_search_resource',
	'label'       => esc_html__( 'Search resource', 'rozer' ),
	'description' => esc_html__( 'Show search result from :', 'rozer' ),
	'section'     => 'header_search',
	'default'     => 'product-post',
	'multiple'    => 1,
	'choices'     => [
		'product-post' => esc_html__( 'Products & Posts', 'rozer' ),
		'product' => esc_html__( 'Products', 'rozer' ),
	],
] );
Kirki::add_field( 'option', [
	'type'        => 'slider',
	'settings'    => 'header_search_limit',
	'label'       => esc_html__( 'Number items show when searching', 'rozer' ),
	'section'     => 'header_search',
	'default'     => 10,
	'choices'     => [
		'min'  => 1,
		'max'  => 100,
		'step' => 1,
	],
] );
Kirki::add_field( 'option', [
	'type'        => 'toggle',
	'settings'    => 'header_search_price',
	'label'       => esc_html__( 'Show price for product results', 'rozer' ),
	'section'     => 'header_search',
	'default'     => '1',
] );