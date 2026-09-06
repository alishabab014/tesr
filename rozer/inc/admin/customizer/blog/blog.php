<?php
Kirki::add_panel( 'blog', array(
    'priority'    => 55,
    'title'       => esc_html__( 'Blog', 'rozer' ),
) );
require_once dirname( __FILE__ ).'/blog-archive.php';
require_once dirname( __FILE__ ).'/blog-single.php';