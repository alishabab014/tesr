<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require_once ROZER_THEME_DIR . '/inc/admin/themepanel/includes/class-tgm-plugin-activation.php';
class Rdt_Theme_Panel{
	protected $tgmpa;
	protected $theme;
	protected $slug;
	protected $base_path;
	protected $importer;
	protected $hooks;
	public $logger;
	function __construct() {
		$this->theme = wp_get_theme();
		$this->slug  = strtolower( preg_replace( '#[^a-zA-Z]#', '', $this->theme->template ) );
		$this->base_path = get_parent_theme_file_path();
		add_action( 'tgmpa_register', array($this,'roadtheme_register_required_plugins'));
		require_once ROZER_THEME_DIR . '/inc/admin/themepanel/includes/merlin/class-rdt-logger.php';
		$this->logger = Rdt_Logger::get_instance();
		$this->tgmpa = isset($GLOBALS['tgmpa']) ? $GLOBALS['tgmpa'] : TGM_Plugin_Activation::get_instance(); 
		add_action( 'admin_init', array( $this, 'required_classes' ) );
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'rdt_admin_panel_scripts' ) );
		add_filter( 'tgmpa_load', array( $this, 'load_tgmpa' ), 10, 1 );
		add_action( 'wp_ajax_rdt_child_theme', array($this, 'generate_child') );
		add_action( 'wp_ajax_rdt_ajax_import_popup', array($this, 'rdt_ajax_import_popup') );
		add_action( 'wp_ajax_rdt_ajax_content', array($this, 'rdt_ajax_content'), 10, 0 );
		add_action( 'wp_ajax_rdt_ajax_plugins', array($this, 'rdt_ajax_plugins'), 10, 0 );
		add_filter( 'pt-importer/new_ajax_request_response_data', array( $this, 'pt_importer_new_ajax_request_response_data' ) );
	}
	public function rdt_admin_panel_scripts() {
		wp_enqueue_script( 'rdt-panel', ROZER_THEME_URI . '/inc/admin/themepanel/assets/themepanel.js', array(), array(), true );
		wp_enqueue_style( 'rdt-panel', ROZER_THEME_URI . '/inc/admin/themepanel/assets/themepanel.css', array(), ROZER_VERSION );
	}
	/**
	 * Require necessary classes.
	 */
	function required_classes() {
		if ( ! class_exists( '\WP_Importer' ) ) {
			require ABSPATH . '/wp-admin/includes/class-wp-importer.php';
		}
		$this->importer = new ProteusThemes\WPContentImporter2\Importer( array( 'fetch_attachments' => true ), $this->logger );
		require_once ROZER_THEME_DIR . '/inc/admin/themepanel/includes/merlin/class-rdt-widget-importer.php';
		if ( ! class_exists( 'WP_Customize_Setting' ) ) {
			require_once ABSPATH . 'wp-includes/class-wp-customize-setting.php';
		}
		require_once ROZER_THEME_DIR . '/inc/admin/themepanel/includes/merlin/class-rdt-customizer-option.php';
		require_once ROZER_THEME_DIR . '/inc/admin/themepanel/includes/merlin/class-rdt-customizer-importer.php';
		require_once ROZER_THEME_DIR . '/inc/admin/themepanel/includes/merlin/class-rdt-hooks.php';
		$this->hooks = new Rdt_Hooks();
		if ( class_exists( 'EDD_Theme_Updater_Admin' ) ) {
			$this->updater = new EDD_Theme_Updater_Admin();
		}
	}
	public function add_admin_menu() {
		$page_name = 'rozer-theme';
	  	$addMenuPage = 'add_me' . 'nu_page';
	  	$addMenuPage( 
	        esc_html__( 'Rozer', 'rozer' ), 
	        esc_html__( 'Rozer', 'rozer' ), 
	        'edit_theme_options', 
	        $page_name, 
	        array( $this , 'rdt_introduction_page'),
	        ROZER_THEME_URI . '/assets/images/rt-logo.png', 
	        62 
	    );
	    add_submenu_page(
	        $page_name,
	        esc_html__( 'Theme customize', 'rozer' ),
	        esc_html__( 'Theme customize', 'rozer' ),
	        'edit_theme_options',
	        'customize.php',
	        '',
	        3
	    );
	    add_submenu_page(
	        $page_name,
	        esc_html__( 'Import demo', 'rozer' ),
	        esc_html__( 'Import demo', 'rozer' ),
	        'edit_theme_options',
	        'themesetup',
	        array( $this , 'rdt_theme_setup'),
	        1
	    ); 
	}
	public function rdt_introduction_page() {
		get_template_part( 'inc/admin/themepanel/templates/introduction-page' );
	}
	public function rdt_theme_setup() {
		get_template_part( 'inc/admin/themepanel/templates/theme-setup' );
	}
	public function rdt_ajax_import_popup(){
		$response = '';
        ob_start();
        get_template_part( 'inc/admin/themepanel/templates/popup-import' );
        $response = ob_get_clean();
        wp_send_json($response);
	}
	public function get_import_files_paths( $demo ) {
		$demo_path = trailingslashit( get_template_directory() ) .'inc/admin/themepanel/demo/'. $demo . '/';
		$import_files   = array(
			'pages' => '',
			'posts' => '',
			'products' => '',
			'media' => '',
			'widgets' => '',
			'options' => '',
		);
		// Pages
		if ( file_exists( $demo_path . 'pages.xml' ) ) {
			$import_files['pages'] = $demo_path . 'pages.xml';
		}
		// Posts
		if ( file_exists( $demo_path . 'posts.xml' ) ) {
			$import_files['posts'] = $demo_path . 'posts.xml';
		}
		// Products
		if ( file_exists( $demo_path . 'products.xml' ) ) {
			$import_files['products'] = $demo_path . 'products.xml';
		}
		// Media
		if ( file_exists( $demo_path . 'media.xml' ) ) {
			$import_files['media'] = $demo_path . 'media.xml';
		}
		// Get widgets file as well. If defined!
		if ( file_exists( $demo_path . 'widgets.wie' ) ) {
			$import_files['widgets'] = $demo_path . 'widgets.wie';
		}
		// Get customizer import file as well. If defined!	
		if ( file_exists( $demo_path . 'customizer.dat' ) ) {
			$import_files['options'] = $demo_path . 'customizer.dat';
		}
		return $import_files;
	}
	/**
	 * Generate the child theme via AJAX.
	 */
	public function generate_child() {
		// Text strings.
		$success = esc_html__( 'Success', 'rozer' );
		$already = esc_html__( 'Already have child theme', 'rozer' );
		$name = $this->theme . ' Child';
		$slug = sanitize_title( $name );
		$path = get_theme_root() . '/' . $slug;
		if ( ! file_exists( $path ) ) {
			WP_Filesystem();
			global $wp_filesystem;
			wp_mkdir_p( $path );
			$wp_filesystem->put_contents( $path . '/style.css', $this->generate_child_style_css( $this->theme->template, $this->theme->name, $this->theme->author, $this->theme->version ) );
			$wp_filesystem->put_contents( $path . '/functions.php', $this->generate_child_functions_php( $this->theme->template ) );
			$this->generate_child_screenshot( $path );
			$allowed_themes          = get_option( 'allowedthemes' );
			$allowed_themes[ $slug ] = true;
			update_option( 'allowedthemes', $allowed_themes );
		} else {
			if ( $this->theme->template !== $slug ) :
				update_option( 'rdt_' . $this->slug . '_child', $name );
				switch_theme( $slug );
			endif;
			wp_send_json(
				array(
					'done'    => 1,
					'message' => sprintf(
						esc_html( $success ), $slug
					),
				)
			);
		}
		if ( $this->theme->template !== $slug ) :
			update_option( 'rdt_' . $this->slug . '_child', $name );
			switch_theme( $slug );
		endif;
		wp_send_json(
			array(
				'done'    => 1,
				'message' => sprintf(
					esc_html( $already ), $name
				),
			)
		);
	}
	/**
	 * Content template for the child theme functions.php file.
	 *
	 * @link https://gist.github.com/richtabor/688327dd103b1aa826ebae47e99a0fbe
	 *
	 * @param string $slug Parent theme slug.
	 */
	public function generate_child_functions_php( $slug ) {
		$slug_no_hyphens = strtolower( preg_replace( '#[^a-zA-Z]#', '', $slug ) );
		$output = "
			<?php
			/**
			 * Theme functions and definitions.
			 * This child theme was generated by Rdt WP.
			 *
			 * @link https://developer.wordpress.org/themes/basics/theme-functions/
			 */
			/*
			 * If your child theme has more than one .css file (eg. ie.css, style.css, main.css) then
			 * you will have to make sure to maintain all of the parent theme dependencies.
			 *
			 * Make sure you're using the correct handle for loading the parent theme's styles.
			 * Failure to use the proper tag will result in a CSS file needlessly being loaded twice.
			 * This will usually not affect the site appearance, but it's inefficient and extends your page's loading time.
			 *
			 * @link https://codex.wordpress.org/Child_Themes
			 */
			function {$slug_no_hyphens}_child_enqueue_styles() {
			    wp_enqueue_style( '{$slug}-style' , get_template_directory_uri() . '/style.css' );
			    wp_enqueue_style( '{$slug}-child-style',
			        get_stylesheet_directory_uri() . '/style.css',
			        array( '{$slug}-style' ),
			        wp_get_theme()->get('Version')
			    );
			}
			add_action(  'wp_enqueue_scripts', '{$slug_no_hyphens}_child_enqueue_styles' );\n
		";
		// Let's remove the tabs so that it displays nicely.
		$output = trim( preg_replace( '/\t+/', '', $output ) );
		// Filterable return.
		return apply_filters( 'rdt_generate_child_functions_php', $output, $slug );
	}
	/**
	 * Content template for the child theme functions.php file.
	 *
	 * @link https://gist.github.com/richtabor/7d88d279706fc3093911e958fd1fd791
	 *
	 * @param string $slug    Parent theme slug.
	 * @param string $parent  Parent theme name.
	 * @param string $author  Parent theme author.
	 * @param string $version Parent theme version.
	 */
	public function generate_child_style_css( $slug, $parent, $author, $version ) {
		$output = "
			/**
			* Theme Name: {$parent} Child
			* Description: This is a child theme of {$parent}, generated by Rdt WP.
			* Author: {$author}
			* Template: {$slug}
			* Version: {$version}
			*/\n
		";
		// Let's remove the tabs so that it displays nicely.
		$output = trim( preg_replace( '/\t+/', '', $output ) );
		return apply_filters( 'rdt_generate_child_style_css', $output, $slug, $parent, $version );
	}
	/**
	 * Generate child theme screenshot file.
	 *
	 * @param string $path    Child theme path.
	 */
	public function generate_child_screenshot( $path ) {
		$screenshot = apply_filters( 'rdt_generate_child_screenshot', '' );
		if ( ! empty( $screenshot ) ) {
			// Get custom screenshot file extension
			if ( '.png' === substr( $screenshot, -4 ) ) {
				$screenshot_ext = 'png';
			} else {
				$screenshot_ext = 'jpg';
			}
		} else {
			if ( file_exists( $this->base_path . '/screenshot.png' ) ) {
				$screenshot     = $this->base_path . '/screenshot.png';
				$screenshot_ext = 'png';
			} elseif ( file_exists( $this->base_path . '/screenshot.jpg' ) ) {
				$screenshot     = $this->base_path . '/screenshot.jpg';
				$screenshot_ext = 'jpg';
			}
		}
		if ( ! empty( $screenshot ) && file_exists( $screenshot ) ) {
			$copied = copy( $screenshot, $path . '/screenshot.' . $screenshot_ext );
		} 
	}
	/**
	 * Do content's AJAX
	 *
	 * @internal    Used as a callback.
	 */
	function rdt_ajax_content() {
		static $content = null;
		$selected_demo = $_POST['selected_demo'];
		if ( null === $content ) {
			$content = $this->get_import_data( $selected_demo );
		}
		$json         = false;
		$this_content = $content[ $_POST['content'] ];
		if ( isset( $_POST['proceed'] ) ) {
			if ( is_callable( $this_content['install_callback'] ) ) {
				$logs = call_user_func( $this_content['install_callback'], $this_content['data'] );
				if ( $logs ) {
					$json = array(
						'done'    => 1,
						'message' => $this_content['success'],
						'debug'   => '',
						'errors'  => '',
					);
				}
			}
		} else {
			$json = array(
				'url'            => admin_url( 'admin-ajax.php' ),
				'action'         => 'rdt_ajax_content',
				'proceed'        => 'true',
				'content'        => $_POST['content'],
				'_wpnonce'       => wp_create_nonce( 'rdt_nonce' ),
				'selected_demo'  => $selected_demo,
				'message'        => $this_content['installing'],
				'errors'         => '',
			);
		}
		if ( $json ) {
			$json['hash'] = md5( serialize( $json ) );
			wp_send_json( $json );
		} else {
			wp_send_json(
				array(
					'error'   => 1,
					'message' => esc_html__( 'Error', 'rozer' ),
					'errors'  => '',
				)
			);
		}
	}
	protected function get_import_data( $selected_demo ) {
		$content = array();
		$import_files = $this->get_import_files_paths( $selected_demo );
		if ( ! empty( $import_files['pages'] ) ) {
			$content['pages'] = array(
				'title'            => esc_html__( 'Pages', 'rozer' ),
				'description'      => esc_html__( 'Demo content data.', 'rozer' ),
				'pending'          => esc_html__( 'Pending', 'rozer' ),
				'installing'       => esc_html__( 'Installing', 'rozer' ),
				'success'          => esc_html__( 'Success', 'rozer' ),
				'checked'          => 0,
				'install_callback' => array( $this->importer, 'import' ),
				'data'             => $import_files['pages'],
			);
		}
		if ( ! empty( $import_files['posts'] ) ) {
			$content['posts'] = array(
				'title'            => esc_html__( 'Posts', 'rozer' ),
				'description'      => esc_html__( 'Demo content data.', 'rozer' ),
				'pending'          => esc_html__( 'Pending', 'rozer' ),
				'installing'       => esc_html__( 'Installing', 'rozer' ),
				'success'          => esc_html__( 'Success', 'rozer' ),
				'checked'          => 0,
				'install_callback' => array( $this->importer, 'import' ),
				'data'             => $import_files['posts'],
			);
		}
		if ( ! empty( $import_files['products'] ) ) {
			$content['products'] = array(
				'title'            => esc_html__( 'Products', 'rozer' ),
				'description'      => esc_html__( 'Demo content data.', 'rozer' ),
				'pending'          => esc_html__( 'Pending', 'rozer' ),
				'installing'       => esc_html__( 'Installing', 'rozer' ),
				'success'          => esc_html__( 'Success', 'rozer' ),
				'checked'          => 0,
				'install_callback' => array( $this->importer, 'import' ),
				'data'             => $import_files['products'],
			);
		}
		if ( ! empty( $import_files['media'] ) ) {
			$content['media'] = array(
				'title'            => esc_html__( 'Media', 'rozer' ),
				'description'      => esc_html__( 'Demo content data.', 'rozer' ),
				'pending'          => esc_html__( 'Pending', 'rozer' ),
				'installing'       => esc_html__( 'Installing', 'rozer' ),
				'success'          => esc_html__( 'Success', 'rozer' ),
				'checked'          => 0,
				'install_callback' => array( $this->importer, 'import' ),
				'data'             => $import_files['media'],
			);
		}
		if ( ! empty( $import_files['widgets'] ) ) {
			$content['widgets'] = array(
				'title'            => esc_html__( 'Widgets', 'rozer' ),
				'description'      => esc_html__( 'Sample widgets data.', 'rozer' ),
				'pending'          => esc_html__( 'Pending', 'rozer' ),
				'installing'       => esc_html__( 'Installing', 'rozer' ),
				'success'          => esc_html__( 'Success', 'rozer' ),
				'install_callback' => array( 'Rdt_Widget_Importer', 'import' ),
				'checked'          => 0,
				'data'             => $import_files['widgets'],
			);
		}
		if ( ! empty( $import_files['options'] ) ) {
			$content['options'] = array(
				'title'            => esc_html__( 'Options', 'rozer' ),
				'description'      => esc_html__( 'Sample theme options data.', 'rozer' ),
				'pending'          => esc_html__( 'Pending', 'rozer' ),
				'installing'       => esc_html__( 'Installing', 'rozer' ),
				'success'          => esc_html__( 'Success', 'rozer' ),
				'install_callback' => array( 'Rdt_Customizer_Importer', 'import' ),
				'checked'          => 0,
				'data'             => $import_files['options'],
			);
		}
			$content['after_import'] = array(
				'title'            => esc_html__( 'After import setup', 'rozer' ),
				'description'      => esc_html__( 'After import setup.', 'rozer' ),
				'pending'          => esc_html__( 'Pending', 'rozer' ),
				'installing'       => esc_html__( 'Installing', 'rozer' ),
				'success'          => esc_html__( 'Success', 'rozer' ),
				'install_callback' => array( $this->hooks, $this->after_all_import_action($selected_demo), $this->import_elmentor_global() ),
				'checked'          => 0,
				'data'             => $selected_demo,
			);
		return $content;
	}
	/**
	 * Conditionally load TGMPA
	 *
	 * @param string $status User's manage capabilities.
	 */
	public function load_tgmpa( $status ) {
		return is_admin() || current_user_can( 'install_themes' );
	}
	function rdt_ajax_plugins ()  {
		$this->roadtheme_register_required_plugins();	
		$json = array();
		$tgmpa_url = $this->tgmpa->get_tgmpa_url();
		$plugins = $this->get_tgmpa_plugins();
		//echo"<pre>"; print_r($plugins ); echo "</pre>";
		$loading_url = get_template_directory_uri().'/road_importdata/images/loading.gif';
		$loading_url_success = get_template_directory_uri().'/road_importdata/images/true.png';
		foreach ( $plugins['activate'] as $slug => $plugin ) {
			if ( $_POST['plugin_slug'] === $slug ) {
				$json = array(
					'url'           => $tgmpa_url,
					'plugin'        => array( $slug ),
					'tgmpa-page'    => $this->tgmpa->menu,
					'plugin_status' => 'all',
					'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
					'action'        => 'tgmpa-bulk-activate',
					'action2'       => - 1,
					'message'       => esc_html__( ' Activating...', 'rozer' ),
					'loading_url' => $loading_url
				);
				break;
			}
		}
		foreach ( $plugins['update'] as $slug => $plugin ) {
			if ( $_POST['plugin_slug'] === $slug ) {
				$json = array(
					'url'           => $tgmpa_url,
					'plugin'        => array( $slug ),
					'tgmpa-page'    => $this->tgmpa->menu,
					'plugin_status' => 'all',
					'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
					'action'        => 'tgmpa-bulk-update',
					'action2'       => - 1,
					'message'       => esc_html__( ' Updating...', 'rozer' ),
					'loading_url' => $loading_url
				);
				break;
			}
		}
		foreach ( $plugins['install'] as $slug => $plugin ) {
			if ( $_POST['plugin_slug'] === $slug ) {
				$json = array(
					'url'           => $tgmpa_url,
					'plugin'        => array( $slug ),
					'tgmpa-page'    => $this->tgmpa->menu,
					'plugin_status' => 'all',
					'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
					'action'        => 'tgmpa-bulk-install',
					'action2'       => - 1,
					'message'       => esc_html__( ' Installing ...','rozer'),
					'loading_url' => $loading_url
				);
				break;
			}
		}
		if ( $json ) {
			$json['hash'] = md5( serialize( $json ) );
			wp_send_json( $json );
		} else {
			wp_send_json( array( 'done' => 1, 'message' => esc_html__( 'Completed', 'rozer' ),'loading_url' => $loading_url_success ) );
		}
		exit;
	}
	/**
	 * Change the new AJAX request response data.
	 *
	 * @param array $data The default data.
	 *
	 * @return array The updated data.
	 */
	public function pt_importer_new_ajax_request_response_data( $data ) {
		$data['url']      = admin_url( 'admin-ajax.php' );
		$data['message']  = esc_html__( 'Installing', 'rozer' );
		$data['proceed']  = 'true';
		$data['action']   = 'rdt_ajax_content';
		$data['content']  = 'media';
		$data['selected_demo']  = $_POST['selected_demo'];
		//$data['_wpnonce'] = wp_create_nonce( 'merlin_nonce' );
		$data['hash']     = md5( rand() ); // Has to be unique (check JS code catching this AJAX response).
		return $data;
	}
	function roadtheme_register_required_plugins() {		
		$plugins = array(
			array(
			'name'               => 'Rozer Core', // The plugin name.
			'slug'               => 'rozer-core', // The plugin slug (typically the folder name).
			'source'             => get_template_directory() . '/inc/plugins/rozer-core.zip', // The plugin source.
			'required'           => true, // If false, the plugin is only 'recommended' instead of required.
			'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.
			'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
			'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
			),
			array(
			'name'               => esc_html__('Kirki Customizer Framework', 'rozer'),
			'slug'               => 'kirki',
			'required'           => true,
			'force_activation'   => false,
			'force_deactivation' => false,
			),
			array(
			'name'               => esc_html__('WPForms Lite', 'rozer'),
			'slug'               => 'wpforms-lite',
			'required'           => true,
			'force_activation'   => false,
			'force_deactivation' => false,
			),
			array(
				'name'               => esc_html__('Elementor Website Builder', 'rozer'),
				'slug'               => 'elementor',
				'required'           => true,
				'force_activation'   => false,
				'force_deactivation' => false,
			),		
			array(
				'name'               => esc_html__('CMB2', 'rozer'),
				'slug'               => 'cmb2',
				'required'           => true,
				'force_activation'   => false,
				'force_deactivation' => false,
			),			
			array(
			'name'               => esc_html__('WooCommerce', 'rozer'),
			'slug'               => 'woocommerce',
			'required'           => true,
			'force_activation'   => false,
			'force_deactivation' => false,
			),
			array(
				'name'               => esc_html__('YITH WooCommerce Wishlist', 'rozer'),
				'slug'               => 'yith-woocommerce-wishlist',
				'required'           => false,
				'force_activation'   => false,
				'force_deactivation' => false,
			),
			array(
				'name'               => esc_html__('YITH WooCommerce Compare', 'rozer'),
				'slug'               => 'yith-woocommerce-compare',
				'required'           => false,
				'force_activation'   => false,
				'force_deactivation' => false,
			),
			array(
				'name'               => esc_html__('Mailchimp for WordPress', 'rozer'),
				'slug'               => 'mailchimp-for-wp',
				'required'           => false,
				'force_activation'   => false,
				'force_deactivation' => false,
			),
		);
		$config = array(
			'id'           => 'et-framework',
			'default_path' => '',                          // Default absolute path to pre-packaged plugins
			'parent_slug'  => 'themes.php',
			'menu'         => 'install-required-plugins',  // Menu slug
			'has_notices'  => true,                        // Show admin notices or not
			'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
			'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
			'is_automatic' => true,                   // Automatically activate plugins after installation or not.
			'message'      => '<div class="notice-warning notice"><p>Install the following required or recommended plugins to get complete functionality from your new theme.</p></div>',                      // Message to output right before the plugins table.
			'strings'      => array(
			'return'       => esc_html__( 'Return to Theme Plugins', 'rozer' )
			)
		);
		tgmpa($plugins, $config);
	}
	function get_tgmpa_plugins()
	{
		$plugins  = array(
			'all'      => array(), // Meaning: all plugins which still have open actions.
			'install'  => array(),
			'update'   => array(),
			'activate' => array(),
		);
		foreach ( $this->tgmpa->plugins as $slug => $plugin ) {
			if ( $this->tgmpa->have_plugin_active( $slug ) && false === $this->tgmpa->does_plugin_have_update( $slug ) ) {
				continue;
			} else {
				$plugins['all'][$slug] = $plugin;
				if ( !$this->tgmpa->is_plugin_installed( $slug ) ) {
					$plugins['install'][$slug] = $plugin;
				} else {
					if ( false !== $this->tgmpa->does_plugin_have_update( $slug ) ) {
						$plugins['update'][$slug] = $plugin;
					}
					if ( $this->tgmpa->can_plugin_activate( $slug ) ) {
						$plugins['activate'][$slug] = $plugin;
					}
				}
			}
		}
		return $plugins;
	}
	private function import_elmentor_global(){
		$remote_data = array(
			'elementor_scheme_color' => ["1"=>"#222222","2"=>"#888888","3"=>"#555555","4"=>"#C62828"],
			"elementor_scheme_color-picker" => ["1"=>"#000","2"=>"#222222","3"=>"#555555","4"=>"#888888","5"=>"#C62828","6"=>"#fff","7"=>"#E1E1E1"],
			'elementor_scheme_typography' => ["1"=>["font_family"=>"Lato","font_weight"=>"400"],"2"=>["font_family"=>"Lato","font_weight"=>"400"],"3"=>["font_family"=>"Lato","font_weight"=>"400"],"4"=>["font_family"=>"Lato","font_weight"=>"500"]],
		);
		if ($remote_data) {
		   foreach ($remote_data as $key => $value) {
			   update_option( $key, $value );
		   }
		}
		return true;
	}
	private function after_all_import_action( $selected_demo ) {	
		switch ($selected_demo) {
			case 'home1':
				$front_page_id = get_page_by_title( 'Home 01' );
				break;
			case 'home2':
				$front_page_id = get_page_by_title( 'Home 02' );
				break;
			case 'home3':
				$front_page_id = get_page_by_title( 'Home 03' );
				break;
			case 'home4':
				$front_page_id = get_page_by_title( 'Home 04' );
				break;
		}
		$blog_page_id  = get_page_by_title( 'Blog' );
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $front_page_id->ID );
		update_option( 'page_for_posts', $blog_page_id->ID );
		// Assign menus to their locations.
		$hoz_menu_term = get_term_by('slug', 'desktop-horizontal-menu', 'nav_menu');
		$ver_menu_term = get_term_by('slug', 'vertical-menu', 'nav_menu');
		set_theme_mod(
			'nav_menu_locations', array(
				'primary' => $hoz_menu_term->term_id,
				'vertical' => $ver_menu_term->term_id,
			)
		);
		//Find what is menu term ID
		$hoz_menu_term = get_term_by('slug', 'desktop-horizontal-menu', 'nav_menu');
		$hoz_menu_id = $hoz_menu_term->term_id;
		$ver_menu_term = get_term_by('slug', 'vertical-menu', 'nav_menu');
		$ver_menu_id = $ver_menu_term->term_id;
		$menu_config = array();
		$menu_config[$hoz_menu_id] = array (2495=>array ('use_icon'=>'0','submenu_type'=>'mega','width_type'=>'fullwidth','width'=>'1000','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'0',),2496=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','column_width'=>'2','element_type'=>'none','hide_label'=>'0','level'=>'1',),2497=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2498=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2499=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2500=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2501=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','column_width'=>'2','element_type'=>'none','hide_label'=>'0','level'=>'1',),2502=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2503=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2504=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2505=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2506=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','column_width'=>'2','element_type'=>'none','hide_label'=>'0','level'=>'1',),2507=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2508=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2509=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2515=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2516=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','column_width'=>'2','element_type'=>'none','hide_label'=>'0','level'=>'1',),2517=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2518=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2519=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2520=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2521=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','column_width'=>'2','element_type'=>'none','hide_label'=>'0','level'=>'1',),2522=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2523=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2524=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2525=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2526=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','column_width'=>'2','element_type'=>'none','hide_label'=>'0','level'=>'1',),2527=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2528=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2529=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2530=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2531=>array ('use_icon'=>'0','submenu_type'=>'mega','width_type'=>'fixed','width'=>'800','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'0',),2532=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','column_width'=>'3','element_type'=>'none','hide_label'=>'0','level'=>'1',),2533=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2534=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2535=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2536=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2537=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','column_width'=>'3','element_type'=>'none','hide_label'=>'0','level'=>'1',),2538=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2539=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2540=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2541=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2542=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','column_width'=>'3','element_type'=>'none','hide_label'=>'0','level'=>'1',),2543=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2544=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2545=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2546=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2547=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','column_width'=>'3','element_type'=>'none','hide_label'=>'0','level'=>'1',),2548=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2549=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2550=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2551=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2552=>array ('use_icon'=>'0','submenu_type'=>'flyout','width_type'=>'fullwidth','width'=>'1000','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'0',),2553=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','column_width'=>'3','element_type'=>'none','hide_label'=>'0','level'=>'1',),2554=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2555=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2556=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2557=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2558=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','column_width'=>'3','element_type'=>'none','hide_label'=>'0','level'=>'1',),2559=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2560=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2561=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2562=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2563=>array ('use_icon'=>'0','subtitle'=>'New','subtitle_background'=>'#81d742','submenu_type'=>'mega','width_type'=>'fullwidth','width'=>'1000','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'0',),2564=>array ('use_icon'=>'0','submenu_type'=>'mega','width_type'=>'fullwidth','width'=>'1000','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'0',),2565=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','column_width'=>'3','element_type'=>'none','hide_label'=>'0','level'=>'1',),);
		$menu_config[$ver_menu_id] = array (2460=>array ('use_icon'=>'0','submenu_type'=>'mega','width_type'=>'fixed','width'=>'500','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'0',),2461=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','column_width'=>'6','element_type'=>'none','hide_label'=>'0','level'=>'1',),2462=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2463=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2464=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2465=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2466=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','column_width'=>'6','element_type'=>'none','hide_label'=>'0','level'=>'1',),2467=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2468=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2469=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2470=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2471=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','column_width'=>'6','element_type'=>'none','hide_label'=>'0','level'=>'1',),2472=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2473=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2474=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2475=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2476=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','column_width'=>'6','element_type'=>'none','hide_label'=>'0','level'=>'1',),2477=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2478=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2479=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2480=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2481=>array ('use_icon'=>'0','submenu_type'=>'flyout','width_type'=>'fullwidth','width'=>'1000','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'0',),2482=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','column_width'=>'3','element_type'=>'none','hide_label'=>'0','level'=>'1',),2483=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2484=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2485=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2486=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2487=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','column_width'=>'3','element_type'=>'none','hide_label'=>'0','level'=>'1',),2488=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2489=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2490=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2491=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2492=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','column_width'=>'3','element_type'=>'none','hide_label'=>'0','level'=>'1',),2493=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2494=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2510=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2511=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'2',),2512=>array ('use_icon'=>'0','subtitle'=>'Hot','subtitle_background'=>'#dd3333','submenu_type'=>'mega','width_type'=>'fullwidth','width'=>'1000','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'0',),2513=>array ('use_icon'=>'0','submenu_type'=>'mega','width_type'=>'fullwidth','width'=>'1000','hide_desktop'=>'0','hide_mobile'=>'0','level'=>'0',),2514=>array ('disable_link'=>'0','column_heading'=>'0','hide_desktop'=>'0','hide_mobile'=>'0','column_width'=>'3','element_type'=>'none','hide_label'=>'0','level'=>'1',),);
		update_option('rt_data_megamenu', $menu_config);
		return true;
	}
};
new Rdt_Theme_Panel();
