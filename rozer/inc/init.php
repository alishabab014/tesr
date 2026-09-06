<?php
//Integrations
require_once ROZER_THEME_DIR . '/inc/admin/themepanel/vendor/merlin/autoload.php';
include_once ROZER_THEME_DIR . '/inc/admin/themepanel/themepanel.php';
require_once ROZER_THEME_DIR . '/inc/integrations/rozer_megamenu/megamenu.php';
// Backoffice
require_once ROZER_THEME_DIR . '/inc/admin/customizer/customizer.php';
require_once ROZER_THEME_DIR . '/inc/admin/dashboard/dashboard.php';
//Helpers
require_once ROZER_THEME_DIR . '/inc/helpers/theme-configs.php';
include_once ROZER_THEME_DIR . '/inc/helpers/ajax-search.php';
include_once ROZER_THEME_DIR . '/inc/helpers/conditionals.php';
include_once ROZER_THEME_DIR . '/inc/helpers/global.php';
include_once ROZER_THEME_DIR . '/inc/helpers/woocommerce.php';
//Frontend
include_once ROZER_THEME_DIR . '/inc/frontend/header.php';
include_once ROZER_THEME_DIR . '/inc/frontend/global.php';
include_once ROZER_THEME_DIR . '/inc/frontend/css-generator.php';
include_once ROZER_THEME_DIR . '/inc/frontend/footer.php';
include_once ROZER_THEME_DIR . '/inc/frontend/posts.php';
if ( is_woocommerce_activated() ) {
include_once ROZER_THEME_DIR . '/inc/frontend/woocommerce/wc-global.php';
include_once ROZER_THEME_DIR . '/inc/frontend/woocommerce/wc-single-product.php';
include_once ROZER_THEME_DIR . '/inc/frontend/woocommerce/wc-catalog-product.php';
include_once ROZER_THEME_DIR . '/inc/frontend/woocommerce/swatches-variant.php';
include_once ROZER_THEME_DIR . '/inc/frontend/woocommerce/variant-gallery.php';
};
if( ! function_exists( 'rozer_enqueue_styles' ) ) {
    function rozer_enqueue_styles() {
    	wp_enqueue_style( 'rozer-style', get_stylesheet_uri(), array(), ROZER_VERSION );
		wp_style_add_data( 'rozer-style', 'rtl', 'replace' );
        wp_enqueue_style( 'bootstrap', ROZER_THEME_URI . '/assets/css/bootstrap-rt.css', array(), '4.0.0');
        wp_enqueue_style( 'slick', ROZER_THEME_URI . '/assets/css/slick.css', array(), '1.5.9' );
        wp_enqueue_style( 'mgf', ROZER_THEME_URI . '/assets/css/magnific-popup.css', array(), '1.1.0' );
        wp_enqueue_style( 'rozer-theme', ROZER_THEME_URI . '/assets/css/theme.css', array(), ROZER_VERSION);
		wp_enqueue_style( 'rt-icons', ROZER_THEME_URI . '/assets/css/roadthemes-icon.css', array(), ROZER_VERSION );
    }
    add_action( 'wp_enqueue_scripts', 'rozer_enqueue_styles', 10 );
}
if( ! function_exists( 'rozer_enqueue_scripts' ) ) {
    function rozer_enqueue_scripts() {
        // Load required scripts.
        wp_enqueue_script( 'slick', ROZER_THEME_URI . '/assets/js/vendor/slick.min.js' , array(), '1.5.9', true);
        wp_enqueue_script( 'jq-countdown', ROZER_THEME_URI . '/assets/js/vendor/jquery.countdown.min.js' , array(), '2.2.0', true);
        wp_enqueue_script( 'mgf', ROZER_THEME_URI . '/assets/js/vendor/jquery.magnific-popup.min.js', array(), '1.1.0', true);
		if(rdt_get_option('lazyload_active', 1)){
			wp_enqueue_script( 'lazysizes', ROZER_THEME_URI . '/assets/js/vendor/lazysizes.js' , array(), '4.0.0', true);
		}
        wp_enqueue_script( 'rozer-theme', ROZER_THEME_URI . '/assets/js/theme.js' , array( 'jquery','imagesloaded' ), ROZER_VERSION, true);
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
		if ( is_singular( 'product' ) ) {
	        wp_enqueue_script( 'zoom' );
	        wp_enqueue_script( 'photoswipe' );
	        wp_enqueue_script( 'photoswipe-ui-default' );
	    }
		wp_enqueue_script( 'wc-add-to-cart-variation' );
        wp_localize_script( 'rozer-theme', 'rozerVars', array( 
        	'ajax_url'       => admin_url('admin-ajax.php'), 
        	'time_out'       => 1000,
        	'cartConfig'     => rdt_get_option('header_elements_cart_minicart' ,'off-canvas'),
        	'productLayout'  => rdt_get_option('single_product_layout' ,'simple'),
        	'load_more'      => esc_html__( 'Load more', 'rozer' ),
            'loading'        => esc_html__( 'Loading...', 'rozer' ),
            'no_more_item'   => esc_html__( 'All items loaded', 'rozer' ),
            'text_day'       => esc_html__( 'day', 'rozer' ),
            'text_day_plu'   => esc_html__( 'days', 'rozer' ),
            'text_hour'      => esc_html__( 'hour', 'rozer' ),
            'text_hour_plu'  => esc_html__( 'hours', 'rozer' ),
            'text_min'       => esc_html__( 'min', 'rozer' ),
            'text_min_plu'   => esc_html__( 'mins', 'rozer' ),
            'text_sec'       => esc_html__( 'sec', 'rozer' ),
            'text_sec_plu'   => esc_html__( 'secs', 'rozer' ),
            'required_message' => __('Please fill all required fields.','rozer'), 
            'valid_email' => __('Please provide a valid email address.','rozer'), 
        	)
    	);
    }    
}
add_action( 'wp_enqueue_scripts', 'rozer_enqueue_scripts', 100 );
function rozer_admin_scripts() {
	wp_enqueue_script( 'rozer-admin-scripts', ROZER_THEME_URI . '/assets/js/admin/admin.js', array(), array(), true );
}
add_action('admin_init','rozer_admin_scripts', 100);
/**
 * Register navigation menus uses wp_nav_menu in five places.
 */
function rozer_menus() {
	$locations = array(
		'primary'  => __( 'Primary Menu', 'rozer' ),
		// Start demo
		'secondary'  => __( 'Secondary Menu', 'rozer' ),
		// End demo
		'topbar'   => __( 'Top bar Menu', 'rozer' ),
		'vertical' => __( 'Vertical Menu', 'rozer' ),
		'footer'   => __( 'Footer Menu (Use in bottom footer)', 'rozer' ),
	);
	register_nav_menus( $locations );
}
add_action( 'init', 'rozer_menus' );
/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function rozer_widget_areas_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Blog Sidebar', 'rozer' ),
			'id'            => 'sidebar-blog',
			'description'   => esc_html__( 'Add widgets here.', 'rozer' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h5 class="widget-title"><span>',
			'after_title'   => '</span></h5>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Shop Sidebar', 'rozer' ),
			'id'            => 'sidebar-shop',
			'description'   => esc_html__( 'Always show filters from Shop Filter.', 'rozer' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h5 class="widget-title"><span>',
			'after_title'   => '</span></h5>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Shop filter', 'rozer' ),
			'id'            => 'shop-filter',
			'description'   => esc_html__( 'Widget area shows filters in sidebar or above products', 'rozer' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h5 class="widget-title"><span>',
			'after_title'   => '</span></h5>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer column 1', 'rozer' ),
			'id'            => 'footer-column-1',
			'description'   => esc_html__( 'Footer column 1', 'rozer' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h6 class="widget-title">',
			'after_title'   => '</h6>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer column 2', 'rozer' ),
			'id'            => 'footer-column-2',
			'description'   => esc_html__( 'Footer column 2', 'rozer' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h6 class="widget-title">',
			'after_title'   => '</h6>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer column 3', 'rozer' ),
			'id'            => 'footer-column-3',
			'description'   => esc_html__( 'Footer column 3', 'rozer' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h6 class="widget-title">',
			'after_title'   => '</h6>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer column 4', 'rozer' ),
			'id'            => 'footer-column-4',
			'description'   => esc_html__( 'Footer column 4', 'rozer' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h6 class="widget-title">',
			'after_title'   => '</h6>',
		)
	);
}
add_action( 'widgets_init', 'rozer_widget_areas_init' );
/**
 * Load custom control for elementor.
 */
// NeedToCheck : check elementor used
add_action( 'elementor/controls/controls_registered', 'init_controls');
function init_controls() {
  // Include Control files
  require_once( ROZER_THEME_DIR . '/inc/elementor/custom-controls/rozer-choose.php' );
  // Register control
  \Elementor\Plugin::$instance->controls_manager->register_control( 'rozer-choose', new Rozer_Choose());
}
// Disable emoji scripts
if ( ! is_admin() ) {
	remove_action('wp_head', 'print_emoji_detection_script', 7);
	remove_action('wp_print_styles', 'print_emoji_styles');
}