<?php
Kirki::add_panel( 'general', array(
    'priority'    => 48,
    'title'       => esc_html__( 'General', 'rozer' ),
) );
require_once dirname( __FILE__ ).'/preloader.php';
require_once dirname( __FILE__ ).'/layout.php';
require_once dirname( __FILE__ ).'/configurations.php';