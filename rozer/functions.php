<?php
/**
 * rozer functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package rozer 
 */
define( 'ROZER_VERSION', '1.0.0' );
define( 'ROZER_THEME_URI', get_template_directory_uri() );
define( 'ROZER_THEME_DIR', get_template_directory() );
define( 'ROZER_SCRIPTS', ROZER_THEME_DIR . '/js' );
define( 'ROZER_STYLES', ROZER_THEME_DIR . '/css' );
if ( ! function_exists( 'rozer_setup' ) ) :
	function rozer_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on rozer, use a find and replace
		 * to change 'rozer' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'rozer', ROZER_THEME_DIR . '/languages' );
		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );
		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );
		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );
		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);
		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'rozer_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);
		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );
		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'rozer_setup' );
function rozer_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'rozer_content_width', 750 );
}
add_action( 'after_setup_theme', 'rozer_content_width', 0 );
/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require ROZER_THEME_DIR . '/inc/jetpack.php';
}
add_filter( 'widget_text', 'do_shortcode' );
/**
 * ------------------------------------------------------------------------------------------------
 * Add theme support for WooCommerce
 * ------------------------------------------------------------------------------------------------
 */
add_theme_support( 'woocommerce' );
add_theme_support( 'wc-product-gallery-lightbox' );
require_once ROZER_THEME_DIR . '/inc/init.php';
define( 'ROZER_CATALOG_MODE', rdt_get_option('catalog_mode_active', false) );
if(ROZER_CATALOG_MODE && rdt_get_option('catalog_mode_price', true)) {
	define( 'ROZER_SHOW_PRICE', false );
}else{
	define( 'ROZER_SHOW_PRICE', true );
}
add_image_size( 'rozer_small_default', 150, 150, false );
/**
 * Get list menu
 */
function rozer_default_responsive($item){
	switch($item) {
		case(6):
			$responsive = array(
				'xl' => 6,
				'lg' => 5,
				'md' => 4,
				'sm' => 3,
				'xs' => 2,
				'xxs' => 1,
			);
			break;
		case(5):
			$responsive = array(
				'xl' => 5,
				'lg' => 5,
				'md' => 4,
				'sm' => 3,
				'xs' => 2,
				'xxs' => 1,
			);
			break;
		case(4):;
			$responsive = array(
				'xl' => 4,
				'lg' => 4,
				'md' => 3,
				'sm' => 3,
				'xs' => 2,
				'xxs' => 1,
			);
			break;
		case(3):
			$responsive = array(
				'xl' => 3,
				'lg' => 3,
				'md' => 3,
				'sm' => 2,
				'xs' => 2,
				'xxs' => 1,
			);
			break;
		case(2):
			$responsive = array(
				'xl' => 2,
				'lg' => 2,
				'md' => 2,
				'sm' => 2,
				'xs' => 2,
				'xxs' => 1,
			);
			break;
		case(1):
			$responsive = array(
				'xl' => 1,
				'lg' => 1,
				'md' => 1,
				'sm' => 1,
				'xs' => 1,
				'xxs' => 1,
			);
			break;
	}
	return $responsive;
}
function rozer_icon_elementor(){
	return array(
		'rt-icons' => [
			'name' => 'rt-icons',
			'label' => __( 'RT Icons', 'rozer' ),
			'url' => ROZER_THEME_URI . '/assets/css/roadthemes-icon.css', 
			'enqueue' => [], 
			'prefix' => 'icon-rt-',
			'displayPrefix' => '',
			'labelIcon' => 'fab fa-font-awesome-alt', //Icon for label
			'ver' => '1.0.0',
			'fetchJson' => ROZER_THEME_URI .'/assets/js/admin/elementor/rt-icons.js', 
			'native' => false,
		],
	);
}
add_filter('elementor/icons_manager/additional_tabs', 'rozer_icon_elementor', 100);
