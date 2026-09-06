<?php 

Kirki::add_panel( 'styles', array(
    'priority'    => 49,
    'title'       => esc_html__( 'Styles', 'rozer' ),
) );
require_once dirname( __FILE__ ).'/styles-color.php';
require_once dirname( __FILE__ ).'/styles-typography.php';
