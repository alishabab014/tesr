<?php

Kirki::add_panel( 'header', array(
    'priority'    => 50,
    'title'       => esc_html__( 'Header', 'rozer' ),
) );
require_once dirname( __FILE__ ).'/header-layout.php';
require_once dirname( __FILE__ ).'/header-styles.php';
require_once dirname( __FILE__ ).'/header-mobile.php';
require_once dirname( __FILE__ ).'/header-topbar.php';
require_once dirname( __FILE__ ).'/header-logo.php';
require_once dirname( __FILE__ ).'/header-search.php';
require_once dirname( __FILE__ ).'/header-cart.php';
require_once dirname( __FILE__ ).'/header-menu.php';
require_once dirname( __FILE__ ).'/header-account.php';
require_once dirname( __FILE__ ).'/header-contact.php';
require_once dirname( __FILE__ ).'/header-html.php';
require_once dirname( __FILE__ ).'/header-promo.php';
function rozer_refresh_header_partials( WP_Customize_Manager $wp_customize ) {
	if ( ! isset( $wp_customize->selective_refresh ) ) {
	      return;
	}
	$wp_customize->selective_refresh->add_partial( 'header-cart', array(
	    'selector' => (rdt_get_option('header_elements_cart_minicart') == 'dropdown') ? '.cart-block' : '#_desktop_cart_',
	    'settings' => array('header_elements_cart_icon','header_elements_cart_minicart'),
	    'container_inclusive' => true,
	    'render_callback' => 'rozer_header_cart',
	) );
	$wp_customize->selective_refresh->add_partial( 'header-contact', array(	
	    'selector' => '.header-contact',		
	    'settings' => array('he_contact_image','he_contact_phone','he_contact_text'),	
	    'container_inclusive' => true,
	    'render_callback' => 'rozer_header_contact',	
	) );
	$wp_customize->selective_refresh->add_partial( 'header-account', array(	
	    'selector' => '.header-account-block',		
	    'settings' => array('he_account_design','he_account_popup'),
	    'container_inclusive' => true,	
	    'render_callback' => 'rozer_header_account',	
	) );
	$wp_customize->selective_refresh->add_partial( 'header-promo', array(	
	    'selector' => '.promo-block',	
	    'settings' => array('header_promo_active','header_promo_type','header_promo_image','header_promo_link','header_promo_text','header_promo_close' ),		
	    'container_inclusive' => true,
	    'render_callback' => 'rozer_promo_block',	
	) );
	$wp_customize->selective_refresh->add_partial( 'header-search', array(	
	    'selector' => '.search-simple',	
	    'settings' => array('header_search_categories','header_search_categories_depth','header_search_keywords'),	
	    'container_inclusive' => true,
	    'render_callback' => 'rozer_header_search',	
	) );
	$wp_customize->selective_refresh->add_partial( 'header-search-icon', array(	
	    'selector' => '.search-sidebar',	
	    'settings' => array('header_search_categories','header_search_categories_depth','header_search_keywords'),
	    'container_inclusive' => true,	
	    'render_callback' => 'rozer_header_search_icon',	
	) );
	$wp_customize->selective_refresh->add_partial( 'header-html1', array(
	    'selector' => '.header-html1',
	    'settings' => array('header_html1_content'),
	    'container_inclusive' => true,
	    'render_callback' => 'rozer_custom_html1',
	) );
	$wp_customize->selective_refresh->add_partial( 'header-html2', array(
	    'selector' => '.header-html2',
	    'settings' => array('header_html2_content'),
	    'container_inclusive' => true,
	    'render_callback' => 'rozer_custom_html2',
	) );
	$wp_customize->selective_refresh->add_partial( 'header-hmenu', array(
	    'selector' => '.primary-menu-wrapper',
	    'settings' => array('hmenu_item_align'),
	    'container_inclusive' => true,
	    'render_callback' => 'rozer_main_menu',
	) );
	$wp_customize->selective_refresh->add_partial( 'header-vmenu', array(
	    'selector' => '.vertical-menu-wrapper',
	    'settings' => array('vmenu_action'),
	    'container_inclusive' => true,
	    'render_callback' => 'rozer_vertical_menu',
	) );
	$wp_customize->selective_refresh->add_partial( 'header-topbar', array(
	    'selector' => '.header-topbar',
	    'settings' => array('header_topbar_text'),
	    'container_inclusive' => true,
	    'render_callback' => 'rozer_header_topbar',
	) );
}
add_action( 'customize_register', 'rozer_refresh_header_partials' );
