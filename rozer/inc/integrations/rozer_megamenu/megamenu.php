<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // disable direct access
}
if ( ! class_exists( 'Rozer_Megamenu' ) ) :
require_once(dirname( __FILE__ ) . '/walker.php');
require_once(dirname( __FILE__ ) . '/walkersub.php');
final class Rozer_Megamenu {
    public static function init() {
        $plugin = new self();
    }
    /**
     * Constructor
     *
     * @since 1.0
     */
    public function __construct() {
        if ( is_admin() ) {
			// Save data megamenu
			add_action( 'wp_ajax_rt_save_megamenu', array( __CLASS__, 'ajax_save_megamenu' ) );
			add_action( 'wp_ajax_rt_save_options', array($this, 'save_menu_meta_options') );
			global $pagenow;
			if ( $pagenow == 'nav-menus.php' ) {
				add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts'), 11 );
				add_action( 'admin_footer', array( __CLASS__, 'megamenu_modal' ) );
			}
        } else {
			add_filter( 'wp_nav_menu_args', array( $this, 'modify_nav_menu_args' ), 9999 );
		}
    }
    /**
     * Add custom actions to allow enqueuing scripts on specific pages
     *
     * @since 1.8.3
     */
    public function admin_enqueue_scripts( $hook ) {
        wp_enqueue_style( 'rt-mega-menu', get_template_directory_uri() . '/assets/css/admin/admin-megamenu.css' );
		wp_enqueue_media();
		wp_enqueue_style( 'wp-color-picker' ); 
		// Enqueue jQuery UI.
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_script( 'rt-mega-menu', get_template_directory_uri() . '/assets/js/admin/megamenu-admin.js', array(), false, true );
		// Embed inline script.
		wp_localize_script( 'rt-mega-menu', 'rt_megamenu', self::localize_script() );
		// Embed data for all menus.
		wp_localize_script( 'rt-mega-menu', 'rt_data_megamenu', self::get_menu_data() );
		wp_localize_script( 'rt-mega-menu', 'rtmegamenu_data_default', self::gen_data_default() );
    }
	/**
	 * Embed data for all menus.
	 *
	 * @return  array
	 */
	public static function get_menu_data() {
		// Get current menu.
		if ( isset( $_GET['menu'] ) && (int) $_GET['menu'] && is_nav_menu( $_GET['menu'] ) ) {
			$menu = ( int ) $_GET['menu'];
		} else {
			$menu = ( int ) get_user_option( 'nav_menu_recently_edited' );
		}
		// Get menu data.
		$data = get_option( 'rt_data_megamenu', '' );
		$data = is_string( $data ) ? json_decode( $data, true ) : $data;
		if ( $data && isset( $data[$menu] ) && $data[$menu] ) {
			// Set data product
			foreach( $data[$menu] as $key => $val ) {
				if( ! ( isset( $val['element_type'] ) && isset( $val['element_data'] ) && $val['element_data'] ) ) continue;
				$list_id = explode( ',', $val['element_data'] );
				$list_id = array_reverse( $list_id );
				if( $list_id ) {
					if( $val['element_type'] == 'element-products' ) {
						foreach( $list_id as $key_item => $val_item ) {
							$val_item = (int) $val_item;
							$product  = wc_get_product( $val_item );
							if( $val_item > 0 && $product ) {
								if( $product->post->post_status == 'publish' ) {
									$data[$menu][$key]['element_data_product'][] = array(
										'id' 	=> $val_item,
										'title' => $product->get_title(),
										'image' => $product->get_image( array( 50, 50) ),
										'price' => $product->get_price_html(),
									);
								}
							} else {
								// Delete product data
								unset( $list_id[$key_item] );
							}
						}
					} else if ( $val['element_type'] == 'element-categories' )  {
						foreach( $list_id as $key_item => $val_item ) {
							$val_item   = (int) $val_item;
							$categories = get_term( $val_item, 'product_cat', ARRAY_A );
							if( $val_item > 0 && $categories ) {
								$image = self::get_image_term_product_category( $val_item, array( 100, 100 ) );
								$data[$menu][$key]['element_data_categories'][] = array(
									'id' 	=> $val_item,
									'name'  => $categories['name'],
									'count' => $categories['count'],
									'image' => $image
								);
							} else {
								// Delete product data
								unset( $list_id[$key_item] );
							}
						}
					}
				}
				$data[$menu][$key]['element_data'] = implode( ',', $list_id );
			}
			return $data[$menu];
		}
		return array();
	}
	/**
	 * Data menu item settings default.
	 *
	 * @return  array
	 */
	public static function gen_data_default() {
		$data = array(
			'lvl_1' => array(
				'use_icon'            => '0',
				'subtitle'            => '',
				'subtitle_background' => '',
				'custom_class'		  => '',
				'submenu_type'		  => 'mega',
				'width_type'		  => 'fullwidth',
				'width'               => '1000',
				'hide_desktop'        => '0',
				'hide_mobile'         => '0',
			),
			'lvl_2' => array(
				'disable_link'        => '0',
				'column_heading'	  => '0',
				'subtitle'            => '',
				'subtitle_background' => '',
				'custom_class'		  => '',
				'hide_desktop'        => '0',
				'hide_mobile'         => '0',
				'column_width'        => '3',
				'element_type'        => 'none',
				'html_data'           => '',
				'hide_label'          => '0',
			),
			'lvl_3' => array(
				'disable_link'        => '0',
				'column_heading'	  => '0',
				'subtitle'            => '',
				'subtitle_background' => '',
				'custom_class'		  => '',
				'hide_desktop'        => '0',
				'hide_mobile'         => '0',
			)
		);
		return $data;
	}
	public static function localize_script() {
		// Get current menu.
		if ( isset( $_GET['menu'] ) && (int) $_GET['menu'] && is_nav_menu( $_GET['menu'] ) ) {
			$menu = ( int ) $_GET['menu'];
		} else {
			$menu = ( int ) get_user_option( 'nav_menu_recently_edited' );
			if( ! is_nav_menu( $menu ) ) {
				$menu = 0;
			}
		}
		return array(
			'ajaxurl'   => admin_url( 'admin-ajax.php' ),
			'adminroot' => admin_url(),
			'rooturl'   => admin_url( 'index.php' ),
			'_nonce'    => wp_create_nonce( 'rt_megamenu_nonce_check' ),
			'menu_id' 	=> $menu
		);
	}
	/**
	 * Save mega menu data by ajax.
	 *
	 * @return  json
	 */
	public static function ajax_save_megamenu() {
		// Check nonce
		if ( ! isset( $_POST['_nonce'] ) || ! wp_verify_nonce( $_POST['_nonce'], 'rt_megamenu_nonce_check' ) ) {
			exit( json_encode( array( 'status' => 'false', 'message' => __( 'The nonce check wrong.', 'rozer' ) ) ) );
		}
		// Check is menu
		if ( ! ( isset( $_POST['menu_id'] ) && ( $_POST['menu_id'] == 0 || is_nav_menu( $_POST['menu_id'] ) ) ) ) {
			exit( json_encode( array( 'status' => 'false', 'message' => __( 'Menu ID is empty.', 'rozer' ) ) ) );
		}
		// Get current data.
		$cur_data = get_option( 'rt_data_megamenu', '' );
		$cur_data = is_string( $cur_data ) ? json_decode( $cur_data , true ) : $cur_data;
		$data_post = isset( $_POST['data'] ) ? wp_unslash ( $_POST['data'] ) : NULL;
		$data_menu_update = array();
		if( $data_post ) {
			if ( isset( $_POST['data_last_update'] ) && $_POST['data_last_update'] == 'ok' ) {
				foreach ( $data_post as $key => $val ) {
					$data_menu_update[$key] = $val;
				}
			} else {
				array_pop( $data_post );
				$list_id_updated = array();
				foreach ( $data_post as $key => $val ) {
					$data_menu_update[$key] = $val;
					$list_id_updated[] = $key;
				}
				exit( json_encode( array( 'status' => 'updating', 'list_id_updated' => $list_id_updated ) ) );
			};
		}
		if ( $data_menu_update ) {
			$cur_data[ $_POST['menu_id'] ] = $data_menu_update;
		} else {
			unset( $cur_data[ $_POST['menu_id'] ] );
		}
		//remove data menu if it was deleted
		$menu_ids = array();
		$menus = wp_get_nav_menus();
		foreach($menus as $menu){
			$menu_ids[] = $menu->term_id;
		}
		foreach($cur_data as $id => $data){
			if(!in_array($id, $menu_ids)) {
				unset($cur_data[$id]);
			}
		}
		update_option( 'rt_data_megamenu', $cur_data );
		exit( json_encode( array( 'status' => 'true' ) ) ) ;
	}
	public static function megamenu_modal() {
		?>
		<?php	echo '<scr' . 'ipt type="text/html" id="rt-modal-html">'; ?>
			<div class="rt-modal">
				<div class="rt-theme-overlay"></div>
				<div class="rt-dialog"></div>
			</div>
		<?php echo '</scr' . 'ipt>'; ?>
		<?php	echo '<scr' . 'ipt type="text/html" id="rt-template">'; ?>
			<div class="dialog-title"><span class="title"><% print( title_modal ); %></span><span class="close dashicons dashicons-no-alt"></span></div>
			<div class="rt-wrapper" data-id="<% print( id ); %>">				
					<div class="rtmenu-heading"><h3><?php esc_html_e( 'Item config', 'rozer' ); ?></h3></div>
					<% if ( level == 0 ) { %>
					<div class="wrapper-row mega-on-off <% if( ! has_children ) print( "dis-enable" ); %>">
						<div class="rt-icon-fields">
							<div class="col col-50">
								<label class="check-style">
									<input <% if( data_item.use_icon == "1" ) print("checked=\'checked\'"); %> class="chb-of use-icon" type="checkbox" />
									<span class="label"><?php esc_html_e( 'Use icon', 'rozer' ); ?></span>
								</label>
								<span class="m-description"><?php esc_html_e( 'The icons display above menu item.', 'rozer' ); ?></span>
							</div>
							<div class="col col-50 icon-form" <% if(data_item.use_icon == "0") print( "style=\'display:none;\'" ); %>>
								<button class="select-icon">Set icon</button>
								<button class="remove-icon <% if( data_item.icon ) { %> button-visible <% }else{ %> button-invisible <% } %>">Remove</button> 
								<span class="m-icon-display"><image src="<%= data_item.icon %>" alt="mene-icon"/></span>
								<span class="m-description"><?php esc_html_e( 'Recommnend using 32x32 pixels icon', 'rozer' ); ?></span>
							</div>
						</div>
					</div>
					<% } %>
					<div class="wrapper-row">
						<% if( level != 0 ) { %>
						<div class="col col-50">
							<label class="check-style">				
								<input <% if( data_item.disable_link == 1 ) print("checked=\'checked\'"); %> class="chb-of disable-link" type="checkbox" />
								<span class="label"><?php esc_html_e( 'Disable link', 'rozer' ); ?></span>
							</label>
						</div>
						<div class="clear-fix"></div>
						<div class="col col-50">
							<label class="check-style">
								<input <% if( data_item.column_heading == 1 ) print("checked=\'checked\'"); %> class="chb-of column-heading" type="checkbox" />
								<span class="label"><?php esc_html_e( 'Column Heading', 'rozer' ); ?></span>
							</label>
						</div>
						<% } %>
						<div class="rtmenu-subtitle-fields clear-fix">
							<div class="col col-50">
								<label><?php esc_html_e( 'Subtitle', 'rozer' ); ?></label>
								<div class="menu-class-box">
									<input type="text" value="<%= data_item.subtitle %>" class="subtitle" />
								</div>
								<span class="m-description"><?php esc_html_e( 'Add a subtitle for this item', 'rozer' ); ?></span>
							</div>
							<div class="col col-50">
								<label><?php esc_html_e( 'Subtitle background', 'rozer' ); ?></label>
								<input type="text" value="<%= data_item.subtitle_background %>" class="color-field"></input>
							</div>
						</div>
						<div class="clear-fix"></div>
						<div class="col col-40">
							<label><?php esc_html_e( 'Custom Class', 'rozer' ); ?></label>
							<div class="menu-class-box">
								<input type="text" value="<%= data_item.custom_class %>" class="custom-class" />
							</div>
							<span class="m-description"><?php esc_html_e( 'Add a specific class for custom CSS', 'rozer' ); ?></span>
						</div>
						<% if ( level == 0 ) { %>
						<div class="hr"></div>
						<div class="rtmenu-heading"><h3><?php esc_html_e( 'Submenu config', 'rozer' ); ?></h3></div>
						<div class="col col-50">
							<label><?php esc_html_e( 'Submenu Type', 'rozer' ); ?></label>
							<select class="submenu-type">
								<option <% if( data_item.submenu_type == "mega" ) print("selected=\'selected\'"); %> value="mega"><?php esc_html_e( 'Mega menu', 'rozer' ); ?></option>
								<option <% if( data_item.submenu_type == "flyout" ) print("selected=\'selected\'"); %> value="flyout"><?php esc_html_e( 'Flyout menu', 'rozer' ); ?></option>
							</select>
						</div>
						<div class="mega-options" <% if( data_item.submenu_type != "mega" ) print( "style=\'display:none;\'" ); %>>
							<div class="wrapper-row mega-option">
								<div class="col col-50">
									<label><?php esc_html_e( 'SubMenu Width Type', 'rozer' ); ?></label>
									<select class="width-type">
										<option <% if( data_item.width_type == "fullwidth" ) print("selected=\'selected\'"); %> value="fullwidth"><?php esc_html_e( 'Full Width', 'rozer' ); ?></option>
										<option <% if( data_item.width_type == "fixed" ) print("selected=\'selected\'"); %> value="fixed"><?php esc_html_e( 'Fixed', 'rozer' ); ?></option>
									</select>
								</label>
								</div>
								<div class="col col-50 width-box" <% if( data_item.width_type != "fixed" ) print( "style=\'display:none;\'" ); %>>
									<label><?php esc_html_e( 'SubMenu Width', 'rozer' ); ?></label>
									<div class="number-width-box">
										<input type="number" value="<%= data_item.width %>" class="number-width" />
										<span class="value-width">px</span>
									</div>
								</div>
							</div>
						</div>
						<% } %>
						<% if ( level == 1 ) { %>
						<div class="wrapper-row mega-option">
							<div class="col col-50">
								<label><?php esc_html_e( 'Column width', 'rozer' ); ?></label>
								<select class="column-width">
									<option <% if( data_item.column_width == 1 ) print("selected=\'selected\'"); %> value="1">1/12</option>
									<option <% if( data_item.column_width == 2 ) print("selected=\'selected\'"); %> value="2">2/12</option>
									<option <% if( data_item.column_width == 3 ) print("selected=\'selected\'"); %> value="3">3/12</option>
									<option <% if( data_item.column_width == 4 ) print("selected=\'selected\'"); %> value="4">4/12</option>
									<option <% if( data_item.column_width == 5 ) print("selected=\'selected\'"); %> value="5">5/12</option>
									<option <% if( data_item.column_width == 6 ) print("selected=\'selected\'"); %> value="6">6/12</option>
									<option <% if( data_item.column_width == 7 ) print("selected=\'selected\'"); %> value="7">7/12</option>
									<option <% if( data_item.column_width == 8 ) print("selected=\'selected\'"); %> value="8">8/12</option>
									<option <% if( data_item.column_width == 9 ) print("selected=\'selected\'"); %> value="9">9/12</option>
									<option <% if( data_item.column_width == 10 ) print("selected=\'selected\'"); %> value="10">10/12</option>
									<option <% if( data_item.column_width == 11 ) print("selected=\'selected\'"); %> value="11">11/12</option>
									<option <% if( data_item.column_width == 12 ) print("selected=\'selected\'"); %> value="12">12/12</option>
								</select>
							</div>
							<div class="col col-50">
								<label><?php esc_html_e( 'Content Element', 'rozer' ); ?></label>
								<select class="element-type">
									<option <% if( data_item.element_type == "" ) print("selected=\'selected\'"); %> value=""><?php esc_html_e( 'None', 'rozer' ); ?></option>
									<option <% if( data_item.element_type == "html" ) print("selected=\'selected\'"); %> value="html"><?php esc_html_e( 'Html', 'rozer' ); ?></option>
								</select>
							</div>
						</div>
						<div class="hide-label-control">
							<label class="check-style">				
								<input <% if( data_item.hide_label == 1 ) print("checked=\'checked\'"); %> class="chb-of hide-label" type="checkbox" />
								<span class="label"><?php esc_html_e( 'Hide Navigation Label', 'rozer' ); ?></span>
							</label>
						</div>
						<div class="element-content">
						</div>
						<% } %>
						<div class="hr"></div>
							<div class="rtmenu-heading"><h3><?php esc_html_e( 'Responsive', 'rozer' ); ?></h3></div>
							<div class="rtmenu-responsive">
								<div class="col col-50">
									<label class="check-style">				
										<input <% if( data_item.hide_desktop == 1 ) print("checked=\'checked\'"); %> class="chb-of hide-desktop" type="checkbox" />
										<span class="label"><?php esc_html_e( 'Hide item on desktop', 'rozer' ); ?></span>
									</label>
								</div>
								<div class="col col-50">
									<label class="check-style">				
										<input <% if( data_item.hide_mobile == 1 ) print("checked=\'checked\'"); %> class="chb-of hide-mobile" type="checkbox" />
										<span class="label"><?php esc_html_e( 'Hide item on mobile', 'rozer' ); ?></span>
									</label>
								</div>
								<span class="m-description"><?php esc_html_e( 'The children items will be hidden too', 'rozer' ); ?></span>
							</div>
					</div>
				<p class="note" style="clear:both; padding: 20px 0 0;"><?php esc_html_e( 'Note: These configurations only apply for Horizontal and Vertical menu.', 'rozer' ); ?></p>	
			</div>
			<div class="bottom-bar"><button><?php esc_html_e( 'Done', 'rozer' ); ?></button></div>
		<?php echo '</scr' . 'ipt>'; ?>
		<?php echo '<scr' . 'ipt type="text/html" id="rt-html-element">'; ?>
			<div class="rt-html-element">
				<div class="editor-wrapper">
					<?php
						echo wp_editor( '_WR_CONTENT_', 'rt-editor', array(
								'editor_class'  => 'rt-editor',
								'editor_height' => 200,
								'tinymce'       => array(
									'setup' => "function( editor ) {
										editor.on('change', function(e) {
											var content    = editor.getContent();
											var input_hide = jQuery( editor.targetElm ).closest( '.editor-wrapper' ).find( '.rt-editor-hidden' );
											input_hide.val( content ).trigger('change');
										} );
									}"
								),
							)
						);
					 ?>
					 <input type="hidden" class="rt-editor-hidden" value="">
				</div>
				<span class="m-description"><?php esc_html_e( 'Add specific HTML content for this item.', 'rozer' ); ?></span>
			</div>
		<?php echo '</scr' . 'ipt>'; ?>
	<?php }
	/**
     * Use the Mega Menu walker to output the menu
     * Resets all parameters used in the wp_nav_menu call
     * Wraps the menu in mega-menu IDs and classes
     *
     * @since 1.0
     * @param $args array
     * @return array
     */
    public function modify_nav_menu_args( $args ) {
        if ( ( isset( $args['menu'] ) && $args['menu'] ) || ( isset( $args['theme_location'] ) && $args['theme_location'] ) ) {
			if ( isset( $args['menu']->term_id ) ) {
				$id_menu = $args['menu']->term_id;
			} elseif ( $args['menu'] ) {
				$id_menu = $args['menu'];
			} elseif ( $args['theme_location'] ) {
                // Get location menu current
				$locations = get_nav_menu_locations();
				$id_menu   = $locations[$args['theme_location']];
			}
			if ( isset( $id_menu ) && is_nav_menu( $id_menu ) ) {
				$megamenu_options = get_option( 'rtmegamenu_options' );
				$_options = is_string( $megamenu_options ) ? json_decode( $megamenu_options, true ) : $megamenu_options;
				// Define default arguments.
				$defaults = array(
					'items_wrap' => '<ul class="%2$s">%3$s</ul>',
				);
				return array_merge( $args, $defaults );
			} else {
				return $args;
			}
		} else {
			// Define default arguments.
			$defaults = array(
				'echo' => false,
			);
			return array_merge( $args, $defaults );
		}
    }
	/**
	 * Plug into WordPress's front-end.
	 *
	 * @return  void
	 */
	public static function get_data() {
		$data = get_option( 'rt_data_megamenu', '' );
		$data = is_string( $data ) ? json_decode( $data, true ) : $data;
		return $data;
	}
}
add_action( 'init', array( 'Rozer_Megamenu', 'init' ), 10 );
endif;
