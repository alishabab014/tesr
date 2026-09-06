<?php

Kirki::add_panel( 'woocommerce', array(
    'priority'    => 55,
    'title'       => esc_html__( 'Woocommece', 'rozer' ),
) );
require_once dirname( __FILE__ ).'/catalog-mode.php';
require_once dirname( __FILE__ ).'/catalog-product.php';
require_once dirname( __FILE__ ).'/single-product.php';
require_once dirname( __FILE__ ).'/variation-swatches.php';
