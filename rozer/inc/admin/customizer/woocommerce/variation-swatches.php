<?php

//Get all attributes
$atts = array();
$attribute_taxonomies = wc_get_attribute_taxonomies();
foreach($attribute_taxonomies as $attribute){
	$atts[$attribute->attribute_name] = $attribute->attribute_label;
}
Kirki::add_section( 'variant_swatches', array(
    'title'       => esc_html__( 'Variant swatches', 'rozer' ),
    'panel'       => 'woocommerce',
) );
Kirki::add_field( 'option', [
	'type'        => 'custom',
	'settings'    => 'variant_swatches_general_custom',
	'section'     => 'variant_swatches',
	'default'         => '<div class="customize-title-divider">' . __( 'General', 'rozer' ) . '</div>',
] );
Kirki::add_field( 'option', [
	'type'        => 'select',
	'settings'    => 'swatches_main_attr',
	'label'       => esc_html__( 'Main attribute', 'rozer' ),
	'description' => esc_html__( 'Attribute show in product catalog', 'rozer' ),
	'section'     => 'variant_swatches',
	'default'     => '',
	'placeholder' => esc_html__( 'Select an attribute...', 'rozer' ),
	'multiple'    => 1,
	'choices'     => $atts,
] );
Kirki::add_field( 'option', [
	'type'        => 'toggle',
	'settings'    => 'swatches_attr_image',
	'label'       => esc_html__( 'Replace main attribute by image', 'rozer' ),
	'description' => esc_html__( 'Use variant image instead of attribute. Only available when main attribute type is color/texture', 'rozer' ),
	'section'     => 'variant_swatches',
	'default'     => '1',
] );
Kirki::add_field( 'option', [
	'type'        => 'custom',
	'settings'    => 'variant_swatches_shop_custom',
	'section'     => 'variant_swatches',
	'default'         => '<div class="customize-title-divider">' . __( 'Catalog product', 'rozer' ) . '</div>',
] );
Kirki::add_field( 'option', [
	'type'        => 'toggle',
	'settings'    => 'swatches_attr_active',
	'label'       => esc_html__( 'Active image swatches', 'rozer' ),
	'description' => esc_html__( 'Change image for each product variantion', 'rozer' ),
	'section'     => 'variant_swatches',
	'default'     => '1',
] );
Kirki::add_field( 'option', [
	'type'        => 'radio-buttonset',
	'settings'    => 'swatches_attr_action',
	'label'       => esc_html__( 'Action on attribute', 'rozer' ),
	'description' => esc_html__( 'Action on attribute for image swatches change', 'rozer' ),
	'section'     => 'variant_swatches',
	'default'     => 'hover',
	'choices'     => [
		'hover'   => esc_html__( 'Hover', 'rozer' ),
		'click' => esc_html__( 'Click', 'rozer' ),
	],
	'active_callback' => [
		[
			'setting'  => 'swatches_attr_active',
			'operator' => '==',
			'value'    => true,
		]
	],
] );
Kirki::add_field( 'option', [
	'type'        => 'custom',
	'settings'    => 'variant_swatches_single_custom',
	'section'     => 'variant_swatches',
	'default'         => '<div class="customize-title-divider">' . __( 'Product page', 'rozer' ) . '</div>',
] );
Kirki::add_field( 'option', [
	'type'        => 'custom',
	'settings'    => 'variant_swatches_color_custom',
	'section'     => 'variant_swatches',
	'default'         => '<div class="sub-divider">' . __( 'Color or texture type', 'rozer' ) . '</div>',
] );
Kirki::add_field( 'option', [
	'type'        => 'radio-buttonset',
	'settings'    => 'swatches_color_design',
	'label'       => esc_html__( 'Color/image attribute display', 'rozer' ),
	'section'     => 'variant_swatches',
	'default'     => 'circle',
	'choices'     => [
		'circle'   => esc_html__( 'Circle', 'rozer' ),
		'square' => esc_html__( 'Square', 'rozer' ),
	],
] );
Kirki::add_field( 'option', [
	'type'        => 'slider',
	'settings'    => 'swatches_color_size',
	'label'       => esc_html__( 'Color/image attribute size', 'rozer' ),
	'section'     => 'variant_swatches',
	'default'     => 20,
	'choices'     => [
		'min'  => 0,
		'max'  => 100,
		'step' => 1,
	],
	'transport'   => 'postMessage',
] );
Kirki::add_field( 'option', [
	'type'        => 'custom',
	'settings'    => 'variant_swatches_label_custom',
	'section'     => 'variant_swatches',
	'default'         => '<div class="sub-divider">' . __( 'Label type', 'rozer' ) . '</div>',
] );
Kirki::add_field( 'option', [
	'type'        => 'radio-image',
	'settings'    => 'swatches_label_design',
	'label'       => esc_html__( 'Label attribute display', 'rozer' ),
	'section'     => 'variant_swatches',
	'default'     => '1',
	'choices'     => [
		'1' => get_template_directory_uri() . '/assets/images/customizer/swatches-label1.jpg',
		'2' => get_template_directory_uri() . '/assets/images/customizer/swatches-label2.jpg',
	],
] );
