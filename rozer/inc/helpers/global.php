<?php
/**
 * ------------------------------------------------------------------------------------------------
 * Is shop on front page
 * ------------------------------------------------------------------------------------------------
 */
if ( ! function_exists( 'rozer_is_shop_on_front' ) ) {
	function rozer_is_shop_on_front() {
		return function_exists( 'wc_get_page_id' ) && 'page' === get_option( 'show_on_front' ) && wc_get_page_id( 'shop' ) == get_option( 'page_on_front' );
	}
}
/**
 * ------------------------------------------------------------------------------------------------
 * Get current page URL
 * ------------------------------------------------------------------------------------------------
 */
function rozer_get_current_page_url() {
	if ( rozer_is_shop_on_front() ) {
		$link = home_url();
	} elseif ( is_shop() ) {
		$link = get_permalink( wc_get_page_id( 'shop' ) );
	} elseif ( is_product_category() ) {
		$link = get_term_link( get_query_var( 'product_cat' ), 'product_cat' );
	} elseif ( is_product_tag() ) {
		$link = get_term_link( get_query_var( 'product_tag' ), 'product_tag' );
	} else {
		$queried_object = get_queried_object();
		$link           = get_term_link( $queried_object->slug, $queried_object->taxonomy );
	}
	// Min/Max.
	if ( isset( $_GET['min_price'] ) ) {
		$link = add_query_arg( 'min_price', wc_clean( wp_unslash( $_GET['min_price'] ) ), $link );
	}
	if ( isset( $_GET['max_price'] ) ) {
		$link = add_query_arg( 'max_price', wc_clean( wp_unslash( $_GET['max_price'] ) ), $link );
	}
	// Order by.
	if ( isset( $_GET['orderby'] ) ) {
		$link = add_query_arg( 'orderby', wc_clean( wp_unslash( $_GET['orderby'] ) ), $link );
	}
	/**
	 * Search Arg.
	 * To support quote characters, first they are decoded from &quot; entities, then URL encoded.
	 */
	if ( get_search_query() ) {
		$link = add_query_arg( 's', rawurlencode( wp_specialchars_decode( get_search_query() ) ), $link );
	}
	// Post Type Arg.
	if ( isset( $_GET['post_type'] ) ) {
		$link = add_query_arg( 'post_type', wc_clean( wp_unslash( $_GET['post_type'] ) ), $link );
		// Prevent post type and page id when pretty permalinks are disabled.
		if ( is_shop() ) {
			$link = remove_query_arg( 'page_id', $link );
		}
	}
	// Min Rating Arg.
	if ( isset( $_GET['rating_filter'] ) ) {
		$link = add_query_arg( 'rating_filter', wc_clean( wp_unslash( $_GET['rating_filter'] ) ), $link );
	}
	// All current filters.
	if ( $_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes() ) { // phpcs:ignore Squiz.PHP.DisallowMultipleAssignments.Found, WordPress.CodeAnalysis.AssignmentInCondition.Found
		foreach ( $_chosen_attributes as $name => $data ) {
			$filter_name = wc_attribute_taxonomy_slug( $name );
			if ( ! empty( $data['terms'] ) ) {
				$link = add_query_arg( 'filter_' . $filter_name, implode( ',', $data['terms'] ), $link );
			}
			if ( 'or' === $data['query_type'] ) {
				$link = add_query_arg( 'query_type_' . $filter_name, 'or', $link );
			}
		}
	}
	// Start demo
	if ( isset( $_GET['sidebar'] ) ) {
		$link = add_query_arg( 'sidebar', wc_clean( $_GET['sidebar'] ), $link );
	}
	if ( isset( $_GET['filter'] ) ) {
		$link = add_query_arg( 'filter', wc_clean( $_GET['filter'] ), $link );
	}
	// End demo
	return apply_filters( 'woocommerce_widget_get_current_page_url', $link );
}
if(rdt_get_option('lazyload_active', 1)){
	add_filter( 'the_content', 'rozer_generate_image_placeholders' , 9999 );
	add_filter( 'post_thumbnail_html', 'rozer_generate_image_placeholders' , 11 );
	add_filter( 'woocommerce_single_product_image_thumbnail_html', 'rozer_generate_image_placeholders' , 9999 );
	add_filter( 'woocommerce_product_get_image', 'rozer_generate_image_placeholders' , 9999 );
}
function rozer_generate_image_placeholders( $content ) {
    if ( is_admin() || is_feed() || is_customize_preview() )
      return $content;
    $matches = array();
    preg_match_all( '/<img[\s\r\n]+.*?>/is', $content, $matches );
    $search = array();
    $replace = array();
    foreach ( $matches[0] as $img_html ) {
        if ( ! preg_match( "/src=['\"]data:image/is", $img_html ) ) {
	      	$skip_images_regex = '/class=".*(lazyload|rev-slidebg).*"/';
	      	$skip_lazyjs_regex = '/class=".*(skip-lazy).*"/';
	    	if(!( preg_match( $skip_images_regex, $img_html ))) {
		      	preg_match_all( '/(height|width)=["\'](.*?)["\']/is', $img_html, $txmatches, PREG_PATTERN_ORDER );
			    $size = array( 100, 100 );
			    foreach ( $txmatches[1] as $key => $attr ) {
			      $value = intval( $txmatches[2][ $key ] );
			      if ( $attr === 'width' && $value != 0 ) $size[0] = $value;
			      if ( $attr === 'height' && $value != 0 ) $size[1] = $value;
			    }
		        if($size[0]/$size[1] == 1) {
		        	$placeholder = 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==';
		        }else{
			        $placeholder = 'data:image/svg+xml,'.rawurlencode(' <svg viewBox="0 0 ' . $size[0] . ' ' . $size[1] . '" xmlns="http://www.w3.org/2000/svg"></svg>');
		        }
		        $replaceImg = '';
		        if ( false === strpos( $img_html, 'data-src' ) ) {
		          $replaceImg = preg_replace( '/<img(.*?)src=/is', '<img$1src="' . $placeholder . '" data-src=', $img_html );
		        } else {
		          $replaceImg = preg_replace( '/<img(.*?)src=(["\'](.*?)["\'])/is', '<img$1src="' . $placeholder . '"', $img_html );
		        }
		        $replaceImg = preg_replace( '/<img(.*?)srcset=/is', '<img$1srcset="" data-srcset=', $replaceImg );
		        //Add class to image
		        if(!( preg_match( $skip_lazyjs_regex, $img_html ))) {
			        if ( preg_match( '/class=["\']/i', $replaceImg )) {
			          $replaceImg = preg_replace( '/class=(["\'])(.*?)["\']/is', 'class=$1 lazyload $2$1', $replaceImg );
			        } else {
			          $replaceImg = preg_replace( '/<img/is', '<img class="lazyload"', $replaceImg );
			        }
			    }
		        array_push( $search, $img_html );
		        array_push( $replace, $replaceImg );
		    }
      	}
    }
    $search = array_unique( $search );
    $replace = array_unique( $replace );
    $content = str_replace( $search, $replace, $content );
    return $content;
}
