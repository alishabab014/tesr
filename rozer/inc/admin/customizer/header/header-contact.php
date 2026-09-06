<?php

Kirki::add_section( 'header_contact', array(
    'priority'    => 1,
    'title'       => esc_html__( 'Element - Contact', 'rozer' ),
    'panel'       => 'header',
) );
Kirki::add_field( 'option', [	
	'type'        => 'image',	
	'settings'    => 'he_contact_image',	
	'label'       => esc_html__( 'Image', 'rozer' ),	
	'description' => esc_html__( 'Recommend 35x35 size', 'rozer' ),	
	'section'     => 'header_contact',	
	'default'     => '',
	'transport' => 'postMessage'		
] );	
Kirki::add_field( 'option', [	
	'type'     => 'text',	
	'settings' => 'he_contact_phone',	
	'label'    => esc_html__( 'Phone number', 'rozer' ),	
	'section'  => 'header_contact',
	'transport' => 'postMessage'	
] );	
Kirki::add_field( 'option', [	
	'type'     => 'text',	
	'settings' => 'he_contact_text',	
	'label'    => esc_html__( 'Text', 'rozer' ),	
	'section'  => 'header_contact',	
	'transport' => 'postMessage'	
] );