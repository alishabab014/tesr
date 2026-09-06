<?php

Kirki::add_section( 'header_cart', array(
    'priority'    => 1,
    'title'       => esc_html__( 'Element - Cart', 'rozer' ),
    'panel'       => 'header',
) );
Kirki::add_field( 'option', [
	'type'        => 'radio-image',
	'settings'    => 'header_elements_cart_icon',
	'label'       => esc_html__( 'Select cart icon', 'rozer' ),
	'section'     => 'header_cart',
	'default'     => '1',
	'choices'     => [
		'cart-outline'   => get_template_directory_uri() . '/assets/images/customizer/cart-1.jpg',	
		'bag-outline' => get_template_directory_uri() . '/assets/images/customizer/cart-2.jpg',	
		'handbag'  => get_template_directory_uri() . '/assets/images/customizer/cart-3.jpg',	
		'shopping-basket-solid'  => get_template_directory_uri() . '/assets/images/customizer/cart-4.jpg',
		'bag'  => get_template_directory_uri() . '/assets/images/customizer/cart-5.jpg',
	],
	'transport' => 'postMessage'
] );
Kirki::add_field( 'option', [
	'type'        => 'radio-buttonset',
	'settings'    => 'header_elements_cart_minicart',
	'label'       => esc_html__( 'Mini cart', 'rozer' ),
	'section'     => 'header_cart',
	'default'     => 'dropdown',
	'choices'     => [
		'none'    => esc_html__( 'None', 'rozer' ),
		'dropdown'   => esc_html__( 'Dropdown', 'rozer' ),
		'off-canvas' => esc_html__( 'Off-canvas sidebar', 'rozer' ),
	],
	'transport'   => 'postMessage',
] );