<?php

Kirki::add_section( 'single_product', array(
    'title'       => esc_html__( 'Product page', 'rozer' ),
    'panel'       => 'woocommerce',
) );
Kirki::add_field( 'option', [
	'type'        => 'radio-image',
	'settings'    => 'single_product_layout',
	'label'       => esc_html__( 'Select product page layout', 'rozer' ),
	'section'     => 'single_product',
	'default'     => 'simple',
	'choices'     => [
		'simple' => get_template_directory_uri() . '/assets/images/customizer/single-product1.jpg',
		'fulltop' => get_template_directory_uri() . '/assets/images/customizer/single-product2.jpg',
		'fullleft' => get_template_directory_uri() . '/assets/images/customizer/single-product3.jpg',
		'vertical' => get_template_directory_uri() . '/assets/images/customizer/single-product4.jpg',
		'grid' => get_template_directory_uri() . '/assets/images/customizer/single-product5.jpg',
	],
] );
Kirki::add_field( 'option', [
	'type'        => 'radio-buttonset',
	'settings'    => 'single_product_image',
	'label'       => esc_html__( 'Image block width', 'rozer' ),
	'section'     => 'single_product',
	'default'     => 'default',
	'choices'     => [
		'smaller' => esc_html__( 'Smaller', 'rozer' ),
		'default' => esc_html__( 'Default', 'rozer' ),
		'larger'  => esc_html__( 'Larger', 'rozer' ),
	],
	'active_callback'  => [
		[
			'setting'  => 'single_product_layout',
			'operator' => '!==',
			'value'    => 'fulltop',
		],
	]
] );
Kirki::add_field( 'option', [
	'type'        => 'custom',
	'settings'    => 'single_product_sub',
	'section'     => 'single_product',
	'default'         => '<div class="sub-divider">' . __( 'Options', 'rozer' ) . '</div>',
] );
Kirki::add_field( 'option', [
	'type'        => 'toggle',
	'settings'    => 'zoom_active',
	'label'       => esc_html__( 'Active zoom', 'rozer' ),
	'section'     => 'single_product',
	'default'     => '1',
] );
Kirki::add_field( 'option', [
	'type'        => 'select',
	'settings'    => 'single_product_tab',
	'label'       => esc_html__( 'Tab design', 'rozer' ),
	'section'     => 'single_product',
	'default'     => 'default',
	'multiple'    => 1,
	'choices'     => [
		'default' => esc_html__( 'Default', 'rozer' ),
		'horizontal' => esc_html__( 'Horizontal', 'rozer' ),
		'vertical' => esc_html__( 'Vertical', 'rozer' ),
		'accordion' => esc_html__( 'Accordion', 'rozer' ),
	],
] );
Kirki::add_field( 'option', [
	'type'     => 'text',
	'settings' => 'single_product_tab_title',
	'label'    => esc_html__( 'Additional tab title', 'rozer' ),
	'section'  => 'single_product',
] );
Kirki::add_field( 'option', [
	'type'     => 'textarea',
	'settings' => 'single_product_tab_content',
	'label'    => esc_html__( 'Additional tab content', 'rozer' ),
	'section'  => 'single_product',
	'description'  => esc_html__( 'Allow using HTML, shortcode', 'rozer' ),
] );
Kirki::add_field( 'option', [
	'type'        => 'custom',
	'settings'    => 'custom_single_product_upsell_title',
	'section'     => 'single_product',
	'default'         => '<div class="customize-title-divider">' . __( 'Upsell products block', 'rozer' ) . '</div>',
] );
Kirki::add_field( 'option', [
	'type'        => 'switch',
	'settings'    => 'single_product_upsell',
	'label'       => esc_html__( 'Status', 'rozer' ),
	'section'     => 'single_product',
	'default'     => '1',
	'choices'     => [
		'on'  => esc_html__( 'Enable', 'rozer' ),
		'off' => esc_html__( 'Disable', 'rozer' ),
	],
] );
Kirki::add_field( 'option', [
	'type'     => 'text',
	'settings' => 'single_product_upsell_title',
	'label'    => esc_html__( 'Title', 'rozer' ),
	'section'  => 'single_product',
	'default'  => esc_html__( 'Upsell products', 'rozer' ),
] );
Kirki::add_field( 'option', [
	'type'        => 'slider',
	'settings'    => 'single_product_upsell_item',
	'label'       => esc_html__( 'Product on screen', 'rozer' ),
	'section'     => 'single_product',
	'default'     => 4,
	'choices'     => [
		'min'  => 3,
		'max'  => 6,
		'step' => 1,
	],
] );
Kirki::add_field( 'option', [
	'type'        => 'custom',
	'settings'    => 'custom_single_product_related_title',
	'section'     => 'single_product',
	'default'         => '<div class="customize-title-divider">' . __( 'Related products block', 'rozer' ) . '</div>',
] );
Kirki::add_field( 'option', [
	'type'        => 'switch',
	'settings'    => 'single_product_related',
	'label'       => esc_html__( 'Status', 'rozer' ),
	'section'     => 'single_product',
	'default'     => '1',
	'choices'     => [
		'on'  => esc_html__( 'Enable', 'rozer' ),
		'off' => esc_html__( 'Disable', 'rozer' ),
	],
] );
Kirki::add_field( 'option', [
	'type'     => 'text',
	'settings' => 'single_product_related_title',
	'label'    => esc_html__( 'Title', 'rozer' ),
	'section'  => 'single_product',
	'default'  => esc_html__( 'Related products', 'rozer' ),
] );
Kirki::add_field( 'option', [
	'type'        => 'slider',
	'settings'    => 'single_product_related_item',
	'label'       => esc_html__( 'Product on screen', 'rozer' ),
	'section'     => 'single_product',
	'default'     => 4,
	'choices'     => [
		'min'  => 3,
		'max'  => 6,
		'step' => 1,
	],
] );