<?php
 
Kirki::add_section( 'styles_color', array(
    'title'       => esc_html__( 'Color', 'rozer' ),
    'panel'       => 'styles',
) );
Kirki::add_field( 'option', [
	'type'        => 'color',
	'settings'    => 'primary_color',
	'label'       => __( 'Primary color', 'rozer' ),
	'section'     => 'styles_color',
	'default'     => '#146cda',
] );
Kirki::add_field( 'option', [
	'type'        => 'color',
	'settings'    => 'link_color',
	'label'       => __( 'Links color', 'rozer' ),
	'section'     => 'styles_color',
	'default'     => '#47494a',
] );