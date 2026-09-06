<?php

if(!class_exists('Kirki')) return;
//load customizer files
require_once get_template_directory() . '/inc/admin/customizer/title_tagline/title_tagline.php';
require_once get_template_directory() . '/inc/admin/customizer/header/header.php';
require_once get_template_directory() . '/inc/admin/customizer/general/general.php';
require_once get_template_directory() . '/inc/admin/customizer/styles/styles.php';
require_once get_template_directory() . '/inc/admin/customizer/page-title/page-title.php';
require_once get_template_directory() . '/inc/admin/customizer/blog/blog.php';
require_once get_template_directory() . '/inc/admin/customizer/social/social.php';
require_once get_template_directory() . '/inc/admin/customizer/footer/footer.php';
require_once get_template_directory() . '/inc/admin/customizer/404page.php';
if(class_exists( 'woocommerce' )){
	require_once get_template_directory() . '/inc/admin/customizer/woocommerce/woocommerce.php';
}
// Add custom CSS file
function rozer_enqueue_customizer_stylesheet() {
    wp_enqueue_style( 'rozer-customizer-admin', get_template_directory_uri() . '/assets/css/admin/admin-customizer.css', array(), '1.0.0' );
    wp_enqueue_style( 'rt-icons', get_template_directory_uri() . '/assets/css/roadthemes-icon.css', array(), '1.0.0' );
}
add_action( 'customize_controls_print_styles', 'rozer_enqueue_customizer_stylesheet' );
/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function rozer_customize_preview_js() {
	wp_enqueue_script( 'rozer-customizer', get_template_directory_uri() . '/assets/js/admin/customizer/customizer.js', array( 'customize-preview' ), '', true );
	wp_enqueue_script( 'rozer-preview', get_template_directory_uri() . '/assets/js/admin/customizer/preview.js', array( 'customize-preview' ), '', true );
	wp_enqueue_style( 'rozer-preview', get_template_directory_uri() . '/assets/css/admin/admin-preview.css', array(), true );
}
add_action( 'customize_preview_init', 'rozer_customize_preview_js' );
// Remove unuse section
function my_customize_register() {     
	global $wp_customize;
	$wp_customize->remove_section( 'header_image' );  
	$wp_customize->remove_section( 'background_image' ); 
	$wp_customize->remove_section( 'colors' ); 
	$wp_customize->remove_control( 'woocommerce_catalog_rows' ); 
	$wp_customize->remove_control( 'woocommerce_catalog_columns' ); 
	$wp_customize->remove_control( 'woocommerce_category_archive_display' ); 
} 
add_action( 'customize_register', 'my_customize_register', 11 );
