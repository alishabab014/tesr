/* global wp, jQuery */
/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */
( function( $ ) {
	// Site title and description.
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );
	// Layout
	wp.customize( 'site_width', function( value ) {
		value.bind( function( to ) {
			$( '.container' ).css( 'max-width', to + 'px' );
		} );
	} );
	wp.customize( 'boxed_width', function( value ) {
		value.bind( function( to ) {
			$( 'body.boxed' ).css( 'max-width', to + 'px' );
		} );
	} );
	
	//header
	wp.customize( 'header_main_background', function( value ) {
		value.bind( function( to ) {
			$( '.header-wrapper' ).css( 'background-color', to['background-color'] );
		} );
	} );
	wp.customize( 'header_main_background', function( value ) {
		value.bind( function( to ) {
			$( '.header-wrapper' ).css( 'background-color', to['background-color'] );
			$( '.header-wrapper' ).css( 'background-image', 'url('+ to['background-image'] + ')' );
			$( '.header-wrapper' ).css( 'background-repeat', to['background-repeat'] );
			$( '.header-wrapper' ).css( 'background-position', to['background-position'] );
			$( '.header-wrapper' ).css( 'background-size', to['background-size'] );
			$( '.header-wrapper' ).css( 'background-attachment', to['background-attachment'] );
		} );
	} );
	wp.customize( 'header_main_text', function( value ) {
		value.bind( function( to ) {
			$( '.main-header' ).addClass('text-' + to);
		} );
	} );
	wp.customize( 'header_main_padding', function( value ) {
		value.bind( function( to ) {
			$( '.main-header-content' ).css( 'padding', to + 'px 0' );
		} );
	} );
	//logo
	wp.customize( 'header_logo_maxwidth', function( value ) {
		value.bind( function( to ) {
			$( '.custom-logo-link' ).css( 'max-width', to + 'px' );
		} );
	} );
	wp.customize( 'header_logo_padding', function( value ) {
		value.bind( function( to ) {
			$( '.custom-logo-link' ).css( 'padding-top', to + 'px' ).css( 'padding-bottom', to + 'px' );
		} );
	} );
	//topbar
	wp.customize( 'header_topbar_font', function( value ) {
		value.bind( function( to ) {
			$( '.topbar-header, .topbar-header li a' ).css( 'font-size', to + 'px' );
		} );
	} );
	wp.customize( 'header_topbar_text', function( value ) {
		value.bind( function( to ) {
			$( '.topbar-header' ).addClass('text-' + to);
		} );
	} );
	wp.customize( 'header_topbar_background', function( value ) {
		value.bind( function( to ) {
			$( '.topbar-header' ).css( 'background-color', to );
		} );
	} );
	//Promo
	wp.customize( 'header_promo_bground', function( value ) {
		value.bind( function( to ) {
			$( '.promo-block' ).css( 'background', to );
		} );
	} );
	wp.customize( 'header_promo_height', function( value ) {
		value.bind( function( to ) {
			$( '.promo-block' ).css( 'height', to + 'px' );
		} );
	} );
	wp.customize( 'header_promo_color', function( value ) {
		value.bind( function( to ) {
			$( '.promo-block' ).css( 'color', to );
		} );
	} );
	
	// Search
	wp.customize( 'header_search_placeholder', function( value ) {
		value.bind( function( to ) {
			$( 'input.rozer_ajax_search' ).attr('placeholder', to );
		} );
	} );
	// Menu
	wp.customize( 'hmenu_background', function( value ) {
		value.bind( function( to ) {
			$( '.menu-background' ).css('background-color', to );
		} );
	} );
	wp.customize( 'hmenu_item_font', function( value ) {
		value.bind( function( to ) {
			$( '#_desktop_menu_ .primary-menu-wrapper ul.primary-menu > li > a' ).css('font-size', to + 'px' );
		} );
	} );
	wp.customize( 'hmenu_item_color', function( value ) {
		value.bind( function( to ) {
			$( '#_desktop_menu_ .primary-menu-wrapper ul.primary-menu > li > a' ).css('color', to );
		} );
	} );
	wp.customize( 'hmenu_item_color_active', function( value ) {
		value.bind( function( to ) {
			$( '#_desktop_menu_ .primary-menu-wrapper ul.primary-menu > li.current-menu-item > a' ).css('color', to );
			$( '#_desktop_menu_ .primary-menu-wrapper ul.primary-menu > li:hover > a' ).css('color', to );
		} );
	} );
	wp.customize( 'hmenu_item_background_color', function( value ) {
		value.bind( function( to ) {
			$( '#_desktop_menu_ .primary-menu-wrapper ul.primary-menu > li > a' ).css('background-color', to );
		} );
	} );
	wp.customize( 'hmenu_item_background_color_active', function( value ) {
		value.bind( function( to ) {
			$( '#_desktop_menu_ .primary-menu-wrapper ul.primary-menu > li.current-menu-item > a' ).css('background-color', to );
			$( '#_desktop_menu_ .primary-menu-wrapper ul.primary-menu > li:hover > a' ).css('background-color', to );
		} );
	} );
	wp.customize( 'hmenu_item_space', function( value ) {
		value.bind( function( to ) {
			$( '.main-menu ul.primary-menu > li' ).css('padding-left', to + 'px' );
			$( '.main-menu ul.primary-menu > li' ).css('padding-right', to + 'px' );
		} );
	} );
	//Vertical menu
	wp.customize( 'vmenu_title', function( value ) {
		value.bind( function( to ) {
			$( '.vmenu-title span' ).text( to );
		} );
	} );
	wp.customize( 'vmenu_title_size', function( value ) {
		value.bind( function( to ) {
			$( '.vmenu-title span' ).css('font-size', to + 'px' );
		} );
	} );
	wp.customize( 'vmenu_title_bground', function( value ) {
		value.bind( function( to ) {
			$( '.vmenu-title' ).css('background', to );
		} );
	} );
	wp.customize( 'vmenu_title_color', function( value ) {
		value.bind( function( to ) {
			$( '.vmenu-title' ).css('color', to );
		} );
	} );
	wp.customize( 'vmenu_title_width', function( value ) {
		value.bind( function( to ) {
			$( '.col-ver' ).css('width', to + 'px' );
		} );
	} );
	wp.customize( 'vmenu_items_width', function( value ) {
		value.bind( function( to ) {
			$( '.vermenu-wrapper ul.vertical-menu' ).css('width', to + 'px' );
		} );
	} );
	//page title background and color
	wp.customize( 'page_title_color', function( value ) {
		value.bind( function( to ) {
			$( 'h1.page-title' ).css( 'color', to );
		} );
	} );
	//Layout
	wp.customize('layout_mode', function ( value ) {
        value.bind( function ( to ) {
            $('body').removeClass('boxed fullwidth'); $('body').addClass( to );
        });
    });
     //Shop page
    wp.customize('shop_sidebar_width', function ( value ) {
        value.bind( function ( to ) {
           $('aside.sidebar').css('flex', '0 0 ' + to + '%');
           $('aside.sidebar').css('max-width', to + '%');
           $('.main-content').css('flex', '0 0 ' + (100-to) + '%');
           $('.main-content').css('max-width', (100-to) + '%');
        });
    });
	//Single Product
	wp.customize( 'swatches_color_size', function( value ) {
		value.bind( function( to ) {
			$( '.color-swatches span.swatch' ).css( 'width', to + 'px' );
			$( '.color-swatches span.swatch' ).css( 'height', to + 'px' );
		} );
	} );
	
	//Single Post
	wp.customize( 'blog_single_pdtitle', function( value ) {
		value.bind( function( to ) {
			$( '.single-post .title-background' ).css( 'padding', to + 'px 0' );
		} );
	} );
	//Sale
	wp.customize('catalog_product_sale_bground', function ( value ) {
        value.bind( function ( to ) {
           $('.sale-label').css('background', to);
           $('.label-d-trapezium:after').css('border-left-color', to);
           $('.label-d-trapezium:after').css('border-bottom-color', to);
        });
    });
    //Footer
    wp.customize( 'footer_main_background', function( value ) {
		value.bind( function( to ) {
			$( '.footer-main' ).css( 'background-color', to['background-color'] );
		} );
	} );
	wp.customize( 'footer_main_background', function( value ) {
		value.bind( function( to ) {
			$( '.footer-main' ).css( 'background-color', to['background-color'] );
			$( '.footer-main' ).css( 'background-image', 'url('+ to['background-image'] + ')' );
			$( '.footer-main' ).css( 'background-repeat', to['background-repeat'] );
			$( '.footer-main' ).css( 'background-position', to['background-position'] );
			$( '.footer-main' ).css( 'background-size', to['background-size'] );
			$( '.footer-main' ).css( 'background-attachment', to['background-attachment'] );
		} );
	} );
	wp.customize( 'footer_bottom_background', function( value ) {
		value.bind( function( to ) {
			$( '.footer-bottom' ).css( 'background-color', to );
		} );
	});
}( jQuery ) );