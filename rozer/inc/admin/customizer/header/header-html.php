<?php

Kirki::add_section( 'header_html', array(
    'priority'    => 1,
    'title'       => esc_html__( 'Element - HTML', 'rozer' ),
    'panel'       => 'header',
) );
Kirki::add_field( 'option', [
	'type'     => 'textarea',
	'settings' => 'header_language_content',
	'label'    => esc_html__( 'Language dropdown content', 'rozer' ),
	'description'    => esc_html__( 'You can use HTML or shortcode', 'rozer' ),
	'section'  => 'header_html',
] );
Kirki::add_field( 'option', [
	'type'     => 'textarea',
	'settings' => 'header_currency_content',
	'label'    => esc_html__( 'Currency dropdown content', 'rozer' ),
	'description'    => esc_html__( 'You can use HTML or shortcode', 'rozer' ),
	'section'  => 'header_html',
] );
Kirki::add_field( 'option', [
	'type'     => 'textarea',
	'settings' => 'header_html1_content',
	'label'    => esc_html__( 'HTML block 1', 'rozer' ),
	'description'    => esc_html__( 'Leave empty if you dont use', 'rozer' ),
	'section'  => 'header_html',
	'transport'   => 'postMessage',
] );
Kirki::add_field( 'option', [
	'type'     => 'textarea',
	'settings' => 'header_html2_content',
	'label'    => esc_html__( 'HTML block 2', 'rozer' ),
	'description'    => esc_html__( 'Leave empty if you dont use', 'rozer' ),
	'section'  => 'header_html',
	'transport'   => 'postMessage',
] );