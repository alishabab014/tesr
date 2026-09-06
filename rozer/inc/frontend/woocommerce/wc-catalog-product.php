<?php

/* Prepare for product catalog */
remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
add_action( 'woocommerce_after_shop_loop_item_rating', 'woocommerce_template_loop_rating', 5 );
/**
 * Gets products per page count from theme settings.
 */
function rozer_product_per_page() {
	return rdt_get_option( 'catalog_product_per_page', 12 );
}
add_filter( 'loop_shop_per_page', 'rozer_product_per_page', 20 );
/**
 * rozer_before_shop_content hook
 */
add_action('rozer_before_shop_content', 'rozer_get_category_thumbnail', 10);
function rozer_get_category_thumbnail(){
	global $wp_query, $post;
	$category_thumbnail = rdt_get_option('catalog_product_category_thumb','hide');
	if(is_tax( 'product_cat' ) && $category_thumbnail == 'show'){
		$current_cat = $wp_query->queried_object;
		$thumbnail_id = get_term_meta( $current_cat->term_id, 'thumbnail_id', true );
		if($thumbnail_id) { ?>
			<div class="category-thumbnail">
				<?php $image = wp_get_attachment_url( $thumbnail_id ); ?>
				<img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($current_cat->name); ?>"/>
			</div>
		<?php }
	}
}
add_action('rozer_before_shop_content', 'rozer_get_category_desc', 15);
function rozer_get_category_desc(){
	global $wp_query, $post;
	$category_description = rdt_get_option('catalog_product_category_desc','hide');
	if ( is_tax( 'product_cat' ) && $category_description != 'hide') {
	$current_cat = $wp_query->queried_object;
	?>
		<?php if($category_description == 'full') : ?>
			<div class="category-description">
				<?php echo category_description( (int) $current_cat->term_id ); ?>
			</div>
		<?php else : ?>
			<div class="category-description expand-content">
			<?php echo category_description( (int) $current_cat->term_id ); ?>
			<div class="block-expand-overlay">
				<a class="block-expand btn-more"><?php echo esc_html__('Show more', 'rozer'); ?></a>
				<a class="block-expand btn-less"><?php echo esc_html__('Show less', 'rozer'); ?></a>
			</div>
		</div>
		<?php endif; ?>
	<?php }
}
add_action('rozer_before_shop_content', 'rozer_get_subcategories', 20);
function rozer_get_subcategories(){
	global $wp_query, $post;
	$current_cat   = false;
	$cc_show_subcategories = '';
	$cat_ancestors = array();
	if ( is_tax( 'product_cat' ) ) {
		$current_cat   = $wp_query->queried_object;
		$cat_ancestors = get_ancestors( $current_cat->term_id, 'product_cat' );
		$cc_show_subcategories = get_term_meta( $current_cat->term_id, 'woo_category_sub', 'default' );
	}
	$subcategories_status = rdt_get_option('catalog_product_subcategories', false);
	if( $cc_show_subcategories == 'no' || ( ($cc_show_subcategories == 'default'|| $cc_show_subcategories == '') && ! $subcategories_status) ){
		return;
	}
	$items_show = rdt_get_option('catalog_product_sub_items', 6);
	$slick_options = json_encode([
		'slidesToShow'   => (int)$items_show,
		'slidesToScroll' => (int)$items_show,
		'autoplay'       => false,
		'infinite'       => false,
		'speed'          => 500,
		'arrows'         => false,
		'dots'           => false, 
	]);
	$slick_responsive = json_encode([
		'items_laptop'            => 4,
        'items_landscape_tablet'  => 3,
        'items_portrait_tablet'   => 4,
        'items_landscape_mobile'  => 3,
        'items_portrait_mobile'   => 2,
        'items_small_mobile'      => 2,
	]);
	$list_args          = array(
		'show_count'   => true,
		'hierarchical' => false,
		'taxonomy'     => 'product_cat',
		'hide_empty'   => true,
	);
	$include = get_terms(
		'product_cat',
		array(
			'fields'       => 'ids',
			'parent'       => $current_cat ? $current_cat->term_id : 0,
			'hierarchical' => true,
			'hide_empty'   => false,
		)
	);
	if ( empty( $include ) ) {
		return;
	}
	$list_args['include']     = implode( ',', $include );
	include_once WC()->plugin_path() . '/includes/walkers/class-wc-product-cat-list-walker.php';
	$list_args['walker']                     = new Rozer_Walker_Category();
	$list_args['title_li']                   = '';
	$list_args['pad_counts']                 = 1;
	$list_args['show_option_none']           = '';
	$list_args['current_category']           = ( $current_cat ) ? $current_cat->term_id : '';
	$list_args['current_category_ancestors'] = $cat_ancestors;
	$list_args['max_depth']                  = 2;
	echo '<div class="subcategories-wrapper">';
	echo '<ul class="product-subcategories slick-slider-block column-desktop-'. (int)$items_show .' column-tablet-3 column-mobile-1" data-slick-options=\''.$slick_options.'\' data-slick-responsive=\''.$slick_responsive.'\'>';
	wp_list_categories( $list_args );
	echo '</ul>';
	echo '</div>';
}
function get_top_category_name(){
	global $product;
	$terms = wc_get_product_terms( $product->get_id(), 'product_cat', array( 'orderby' => 'parent', 'order' => 'DESC' ) );
	if ( empty( $terms ) ) {
		return '';
	}
	if ( $terms[0]->parent == 0 ) {
		$cat = $terms[0];
	}
	else {
		$ancestors = get_ancestors( $terms[0]->term_id, 'product_cat', 'taxonomy' );
		$cat_id    = end( $ancestors );
		$cat       = get_term( $cat_id, 'product_cat' );
	}
	$cat_url = get_term_link($cat->term_id, 'product_cat');
	return '<a href="'. $cat_url .'">'. $cat->name .'</a>';
}
if ( !function_exists('rozer_wc_quickview') ) {
	/**
	 * Customize product quick view.
	 */
	function rozer_wc_quickview() {
		// Get product from request.
		if ( isset( $_POST['product'] ) && (int) $_POST['product'] ) {
			global $post, $product, $woocommerce;
			$id      = ( int ) $_POST['product'];
			$post    = get_post( $id );
			$product = get_product( $id );
			if ( $product ) {
				// Get quickview template.
				include get_template_directory() . '/woocommerce/content-quickview-product.php';
			}
		}
		exit;
	}
	add_action( 'wp_ajax_rozer_quickview', 'rozer_wc_quickview' );
	add_action( 'wp_ajax_nopriv_rozer_quickview', 'rozer_wc_quickview' );
}
if ( !function_exists('rozer_product_quickview') ) {
	function rozer_product_quickview() {
		global $post;
		?>
			<div class="quickview hidden-xs"><a href="javascript:void(0)" class="button btn-quickview" data-product="<?php echo esc_attr( $post->ID ); ?>"><?php echo esc_html__('Quick View', 'rozer'); ?></a></div>
		<?php
	}
}
class Rozer_Walker_Category extends Walker_Category {
	public function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
		$subcategories_design = rdt_get_option('catalog_product_sub_design', 'design1');
		/** This filter is documented in wp-includes/category-template.php */
		$cat_name = apply_filters(
			'list_cats',
			esc_attr( $category->name ),
			$category
		);
		// Don't generate an element if the category name is empty.
		if ( ! $cat_name ) {
			return;
		}
		$link = '<div class="category-nav-link">';
		$link .= '<a href="' . esc_url( get_term_link( $category ) ) . '" ';
		$link .= '>';
		$icon_url = get_term_meta( $category->term_id, 'woo_category_image_nav', true );
		if( ! empty( $icon_url ) ) {
			$link .= '<img src="'  . esc_url( $icon_url ) . '" alt="' . esc_attr( $category->cat_name ) . '" class="category-icon" />';
		}else{
			$link .= '<img src="'  . esc_url( wc_placeholder_img_src() ) . '" alt="' . esc_attr( $category->cat_name ) . '" class="category-icon" />';
		}
		$link .= '<span class="category-summary">';
		if($subcategories_design == 'design1') {
			$link .= '<span class="category-name">' . $cat_name . '</span>';
			if ( ! empty( $args['show_count'] ) ) {
				$link .= '<span class="category-products-count"><span class="cat-count-number">' . number_format_i18n( $category->count ) . '</span> <span class="cat-count-label">' . _n( 'product', 'products', $category->count, 'rozer' ) . '</span></span>';
			}
		}else{
			$link .= '<span class="category-name">' . $cat_name.' ';
			if ( ! empty( $args['show_count'] ) ) {
				$link .= '(' . number_format_i18n( $category->count ) . ')';
			}	
			$link .= '</span>';	
		}
		$link .= '</span>';
		$link .= '</a>';
		$link .= '</div>';
		if ( 'list' == $args['style'] ) {
			$default_cat = get_option( 'default_product_cat' );
			$output .= "\t<li";
			$css_classes = array(
				'cat-item',
				'cat-item-' . $category->term_id,
				( $category->term_id == $default_cat ? 'wc-default-cat' : '')
			);
			if ( ! empty( $args['current_category'] ) ) {
				// 'current_category' can be an array, so we use `get_terms()`.
				$_current_terms = get_terms( $category->taxonomy, array(
					'include' => $args['current_category'],
					'hide_empty' => false,
				) );
				foreach ( $_current_terms as $_current_term ) {
					if ( $category->term_id == $_current_term->term_id ) {
						$css_classes[] = 'current-cat';
					} elseif ( $category->term_id == $_current_term->parent ) {
						$css_classes[] = 'current-cat-parent';
					}
					while ( $_current_term->parent ) {
						if ( $category->term_id == $_current_term->parent ) {
							$css_classes[] =  'current-cat-ancestor';
							break;
						}
						$_current_term = get_term( $_current_term->parent, $category->taxonomy );
					}
				}
			}
			/**
			 * Filter the list of CSS classes to include with each category in the list.
			 *
			 * @since 4.2.0
			 *
			 * @see wp_list_categories()
			 *
			 * @param array  $css_classes An array of CSS classes to be applied to each list item.
			 * @param object $category    Category data object.
			 * @param int    $depth       Depth of page, used for padding.
			 * @param array  $args        An array of wp_list_categories() arguments.
			 */
			$css_classes = implode( ' ', apply_filters( 'category_css_class', $css_classes, $category, $depth, $args ) );
			$output .=  ' class="' . $css_classes . '"';
			$output .= ">$link\n";
		} elseif ( isset( $args['separator'] ) ) {
			$output .= "\t$link" . $args['separator'] . "\n";
		} else {
			$output .= "\t$link<br />\n";
		}
	}
}
function rozer_shop_toolbar(){
	if(isset($_COOKIE['shop-display'])) {
		$shopview = $_COOKIE['shop-display'];
	}else {
		$shopview = 'grid';
	}
	?>
	<div class="shop-views view-mode">
		<button id="shop-display-grid" class="shop-display grid-icon <?php if($shopview == 'grid') echo 'active'; ?>" data-display="grid">
			Grid
		</button>
		<button id="shop-display-list" class="shop-display list-icon <?php if($shopview == 'list') echo 'active'; ?>" data-display="list">
			List
		</button>
	</div>
	<?php
}
add_action( 'rozer_shop_toolbar', 'rozer_shop_toolbar' );
remove_action('woocommerce_before_shop_loop' , 'woocommerce_result_count' , 20);
remove_action('woocommerce_before_shop_loop' , 'woocommerce_catalog_ordering', 30);
add_action('rozer_shop_toolbar' , 'woocommerce_result_count');
add_action('rozer_shop_toolbar' , 'woocommerce_catalog_ordering');
/*
 * Filters widget area
 */
function rozer_shop_filters(){
	$filter_position = rdt_get_option('catalog_product_filter_posistion', 'side');
	// Start demo
	if(isset($_GET['filter']) && $_GET['filter'] != '') {
		$filter_position = $_GET['filter'];
	}
	// End demo
	?>
	<div id="_desktop_filters_" class="filter-<?php echo esc_attr($filter_position); ?> <?php if ($filter_position == 'side') {  echo esc_attr('widget-area-side'); } ?>">
		<a href="#" class="side-close-icon <?php if($filter_position == 'top') : ?>d-lg-none<?php endif; ?>" title="Close"><i class="icon-rt-close-outline"></i></a>
		<div id="shop-filters" class="widget-area-side">
			<?php if ( is_active_sidebar( 'shop-filter' ) ) {
				dynamic_sidebar('shop-filter');
			} ?>
		</div>
	</div>
	<?php
}
add_action('rozer_shop_filters', 'rozer_shop_filters');
/* Product - second image */
if ( ! function_exists( 'rozer_product_thumbnail_hover' ) ) {
	function rozer_product_thumbnail_hover( $product , $size = '' ) {
		if(!$size) $size = 'shop_catalog';
		$product_gallery_thumbnail_ids = $product->get_gallery_image_ids();
		$product_thumbnail_alt_id = ( $product_gallery_thumbnail_ids ) ? reset( $product_gallery_thumbnail_ids ) : null; // Get first gallery image id
		if ( $product_thumbnail_alt_id ) {
			$product_thumbnail_alt_src = wp_get_attachment_image_src( $product_thumbnail_alt_id, $size );
			if ( $product_thumbnail_alt_src ) {
				return wp_get_attachment_image( $product_thumbnail_alt_id, $size, '', array('class'=>'product_thumbnail_hover') );				
			}
		}
		return '';
	}
}
function rozer_wl_shop_loop(){
	if(isset($_COOKIE['shop-display'])) {
		$shopview = $_COOKIE['shop-display'];
	}else {
		$shopview = 'grid';
	}
	wc_setup_loop(array(
		'column_desktop' => rdt_get_option('catalog_product_items_desktop', 4),
		'column_tablet' => rdt_get_option('catalog_product_items_tablet', 3),
		'column_phone' => rdt_get_option('catalog_product_items_phone', 2),
		'shopview' => $shopview,
		'is_slider' => false,
	));
}
add_action('woocommerce_shop_loop', 'rozer_wl_shop_loop'); 
function rozer_product_onsale_countdown(){
	global $product;
	if ( $product->is_type('variable') ) {
		return;
	}else{
		$sale_date_start = get_post_meta( $product->get_id(), '_sale_price_dates_from', true );
		$sale_date_end = get_post_meta( $product->get_id(), '_sale_price_dates_to', true );
		$curent_date = strtotime( date( 'Y-m-d H:i:s' ) );
	}
	if ( $sale_date_end < $curent_date || $curent_date < $sale_date_start ) return;
    
	echo '<div class="rozer-product-countdown block-countdown" data-end-date="' . esc_attr( date( 'Y-m-d H:i:s', $sale_date_end ) ) . '"></div>';
}
function rozer_product_stock($product){
	$stock_line     = '';
	$stock_quantity = $product->get_stock_quantity();
	$already_sold   = get_post_meta( $product->get_ID(), 'total_sales', true );
	if ( ! empty( $stock_quantity ) ) {
		$already_sold = empty( $already_sold ) ? 0 : $already_sold;
		$all_stock    = $stock_quantity + $already_sold;
		ob_start();
		$stock_line_inner = (( $already_sold * 100 ) / $all_stock );
		?>
        <div class="rt-product-stock">
            <span class="stock-out"><?php echo esc_html__( 'Sold:', 'rozer' ) . ' <span class="stock-count">' . $already_sold . '</span>'; ?></span>
            <span class="stock-in"><?php echo esc_html__( 'Available:', 'rozer' ) . ' <span class="stock-count">' . $stock_quantity . '</span>'; ?></span>
            <span class="stock-line"><span class="stock-line-inner"
                                           style="width: <?php echo esc_attr( $stock_line_inner ); ?>%"></span></span>
        </div>
		<?php $stock_line = ob_get_clean();
	}
	return $stock_line;
}
/*
 * Show subcategories thumbnail
 * Use custom image : woo_category_image_nav
*/
remove_action('woocommerce_before_subcategory_title', 'woocommerce_subcategory_thumbnail');
add_action('woocommerce_before_subcategory_title', 'rozer_subcategory_thumbnail', 10);
if ( ! function_exists( 'rozer_subcategory_thumbnail' ) ) {
	function rozer_subcategory_thumbnail( $category ) {
		$dimensions           = wc_get_image_size( 'shop_catalog' );
		$thumbnail_id         = get_term_meta( $category->term_id, 'woo_category_image_nav_id', true );
		if ( $thumbnail_id ) {
			$image        = wp_get_attachment_image_src( $thumbnail_id, 'shop_catalog' );
			$image        = $image[0];
			$image_srcset = function_exists( 'wp_get_attachment_image_srcset' ) ? wp_get_attachment_image_srcset( $thumbnail_id, 'shop_catalog' ) : false;
			$image_sizes  = function_exists( 'wp_get_attachment_image_sizes' ) ? wp_get_attachment_image_sizes( $thumbnail_id, 'shop_catalog' ) : false;
		} else {
			$image        = wc_placeholder_img_src();
			$image_srcset = false;
			$image_sizes  = false;
		}
		if ( $image ) {
			// Prevent esc_url from breaking spaces in urls for image embeds.
			// Ref: https://core.trac.wordpress.org/ticket/23605.
			$image = str_replace( ' ', '%20', $image );
			// Add responsive image markup if available.
			if ( $image_srcset && $image_sizes ) {
				echo '<img src="' . esc_url( $image ) . '" alt="' . esc_attr( $category->name ) . '" width="' . esc_attr( $dimensions['width'] ) . '" height="' . esc_attr( $dimensions['height'] ) . '" srcset="' . esc_attr( $image_srcset ) . '" sizes="' . esc_attr( $image_sizes ) . '" />';
			} else {
				echo '<img src="' . esc_url( $image ) . '" alt="' . esc_attr( $category->name ) . '" width="' . esc_attr( $dimensions['width'] ) . '" height="' . esc_attr( $dimensions['height'] ) . '" />';
			}
		}
	}
}
/*
 * Active filters
 */
add_action('rozer_active_filters', 'rozer_active_filters');
function rozer_active_filters(){
	if ( ! is_shop() && ! is_product_taxonomy() ) {
		return;
	}
	$_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes();
	$min_price          = isset( $_GET['min_price'] ) ? wc_clean( wp_unslash( $_GET['min_price'] ) ) : 0; // WPCS: input var ok, CSRF ok.
	$max_price          = isset( $_GET['max_price'] ) ? wc_clean( wp_unslash( $_GET['max_price'] ) ) : 0; // WPCS: input var ok, CSRF ok.
	$rating_filter      = isset( $_GET['rating_filter'] ) ? array_filter( array_map( 'absint', explode( ',', wp_unslash( $_GET['rating_filter'] ) ) ) ) : array(); // WPCS: sanitization ok, input var ok, CSRF ok.
	$base_link          = rozer_get_current_page_url();
	$reset_url = strtok( $base_link, '?' );
	if ( 0 < count( $_chosen_attributes ) || 0 < $min_price || 0 < $max_price || ! empty( $rating_filter ) ) {
		echo '<div class="woo-active-filters">';
	}else{
		echo '<div class="woo-active-filters hide">';
	}
	echo '<h5>'. esc_attr__('Active filters', 'rozer') .'</h5>';
	echo '<div class="actived_filters">';
	if ( 0 < count( $_chosen_attributes ) || 0 < $min_price || 0 < $max_price || ! empty( $rating_filter ) ) {
		echo '<ul>';
		// Attributes.
		if ( ! empty( $_chosen_attributes ) ) {
			foreach ( $_chosen_attributes as $taxonomy => $data ) {
				foreach ( $data['terms'] as $term_slug ) {
					$term = get_term_by( 'slug', $term_slug, $taxonomy );
					if ( ! $term ) {
						continue;
					}
					$filter_name    = 'filter_' . wc_attribute_taxonomy_slug( $taxonomy );
					$current_filter = isset( $_GET[ $filter_name ] ) ? explode( ',', wc_clean( wp_unslash( $_GET[ $filter_name ] ) ) ) : array(); // WPCS: input var ok, CSRF ok.
					$current_filter = array_map( 'sanitize_title', $current_filter );
					$new_filter     = array_diff( $current_filter, array( $term_slug ) );
					$link = remove_query_arg( array( 'add-to-cart', $filter_name ), $base_link );
					if ( count( $new_filter ) > 0 ) {
						$link = add_query_arg( $filter_name, implode( ',', $new_filter ), $link );
					}
					$filter_classes = array( 'chosen', 'chosen-' . sanitize_html_class( str_replace( 'pa_', '', $taxonomy ) ), 'chosen-' . sanitize_html_class( str_replace( 'pa_', '', $taxonomy ) . '-' . $term_slug ) );
					echo '<li class="' . esc_attr( implode( ' ', $filter_classes ) ) . '"><a rel="nofollow" aria-label="' . esc_attr__( 'Remove filter', 'rozer' ) . '" href="' . esc_url( $link ) . '">' . esc_html( $term->name ) . '</a></li>';
				}
			}
		}
		if ( $min_price ) {
			$link = remove_query_arg( 'min_price', $base_link );
			/* translators: %s: minimum price */
			echo '<li class="chosen"><a rel="nofollow" aria-label="' . esc_attr__( 'Remove filter', 'rozer' ) . '" href="' . esc_url( $link ) . '">' . sprintf( __( 'Min %s', 'rozer' ), wc_price( $min_price ) ) . '</a></li>'; // WPCS: XSS ok.
		}
		if ( $max_price ) {
			$link = remove_query_arg( 'max_price', $base_link );
			/* translators: %s: maximum price */
			echo '<li class="chosen"><a rel="nofollow" aria-label="' . esc_attr__( 'Remove filter', 'rozer' ) . '" href="' . esc_url( $link ) . '">' . sprintf( __( 'Max %s', 'rozer' ), wc_price( $max_price ) ) . '</a></li>'; // WPCS: XSS ok.
		}
		if ( ! empty( $rating_filter ) ) {
			foreach ( $rating_filter as $rating ) {
				$link_ratings = implode( ',', array_diff( $rating_filter, array( $rating ) ) );
				$link         = $link_ratings ? add_query_arg( 'rating_filter', $link_ratings ) : remove_query_arg( 'rating_filter', $base_link );
				/* translators: %s: rating */
				echo '<li class="chosen"><a rel="nofollow" aria-label="' . esc_attr__( 'Remove filter', 'rozer' ) . '" href="' . esc_url( $link ) . '">' . sprintf( esc_html__( 'Rated %s out of 5', 'rozer' ), esc_html( $rating ) ) . '</a></li>';
			}
		}
		echo '</ul>';
	}
	echo '</div>';
	echo '<a class="reset-filters button-hide" href="'. $reset_url .'">'. esc_attr__('Clear Filters', 'rozer') .'</a>';
	echo '</div>';
}