<?php	

function rozer_customizer_print_styles_inline() {
	echo '<style id="rt-customizer-inline-styles">';
		echo rozer_css_generator();
	echo '</style>';
}
add_action( 'wp_head', 'rozer_customizer_print_styles_inline' );
function rozer_css_generator() {	
	ob_start();
	$default_primaryFont = array(
		'font-family'    => 'Open Sans',
		'variant'        => 'regular',
		'font-size'      => '1.4rem',
		'line-height'    => '1.7',
		'color'          => '#555555',
	);
	$primaryFont = rdt_get_option('primary_font', $default_primaryFont); 
	?>
	.custom-logo-link,.custom-logo-mobile {
		max-width: <?php echo esc_attr(rdt_get_option('header_logo_maxwidth', 183)); ?>px;	
	}	
	:root{	
		--textcolor: <?php echo esc_attr($primaryFont['color']); ?>;	
		--mainfont: <?php echo esc_attr($primaryFont['font-family']); ?>	
	}
	body{	
		font-family: <?php echo esc_attr($primaryFont['font-family']); ?>;	
		font-size : <?php echo esc_attr($primaryFont['font-size']); ?>;	
		line-height : <?php echo esc_attr($primaryFont['line-height']); ?>;	
		color : <?php echo esc_attr($primaryFont['color']); ?>;	
		<?php rozer_font_variant($primaryFont['variant']);	?>;	
	}	
	<?php 
		$default_secondaryFont = array(
			'font-family'    => 'Open Sans',
			'variant'        => '700',
			'font-size'      => '1.4rem',
			'line-height'    => '1.5',
			'text-transform' => 'none',
			'color'          => '#1d1d1d',
		);	
		$secondaryFontActive = rdt_get_option('secondary_font_active', '1'); 	
		$secondaryFont = rdt_get_option('secondary_font', $default_secondaryFont); 	
		$secondaryFontUse = rdt_get_option('secondary_font_use', array('title', 'testimonial')); 	
		if($secondaryFontActive == '1' && count($secondaryFontUse) > 0 ) {	
			foreach($secondaryFontUse as $sec) {	
				if($sec == 'title') : ?>	
					:root{	
						--headingcolor: <?php echo esc_attr($secondaryFont['color']); ?>;	
						--headingfont: <?php echo esc_attr($secondaryFont['font-family']); ?>;	
					}	
					h1,h2,h3,h4,h5,h6{	
						font-family: <?php echo esc_attr($secondaryFont['font-family']); ?>;		
						line-height : <?php echo esc_attr($secondaryFont['line-height']); ?>;	
						color : <?php echo esc_attr($secondaryFont['color']); ?>;	
						<?php rozer_font_variant($secondaryFont['variant']);?>;
						text-transform: <?php echo esc_attr($secondaryFont['text-transform']); ?>;		
					}	
				<?php endif;	
				if($sec == 'testimonial') : ?>	
					.testimonial{	
						font-family: <?php echo esc_attr($secondaryFont['font-family']); ?>;	
						font-size : <?php echo esc_attr($secondaryFont['font-size']); ?>;	
						line-height : <?php echo esc_attr($secondaryFont['line-height']); ?>;	
						color : <?php echo esc_attr($secondaryFont['color']); ?>;	
						<?php rozer_font_variant($secondaryFont['variant']);?>;
						text-transform: <?php echo esc_attr($secondaryFont['text-transform']); ?>;	
					}	
				<?php endif;	
			}	
		}	
	?>	
	:root{	
		--primarycolor: <?php echo esc_attr(rdt_get_option('primary_color', '#146cda')); ?>;	
		--linkcolor: <?php echo esc_attr(rdt_get_option('link_color', '#47494a')); ?>;	
	}	
	@media(min-width: 1200px){	
		.container{	
			max-width: <?php echo esc_attr(rdt_get_option('site_width', 1470)); ?>px;	
		}	
	}	
	@media(min-width: 1200px){	
		body.boxed{	
			max-width: <?php echo esc_attr(rdt_get_option('boxed_width', 1600)); ?>px;
			margin-left: auto;
			margin-right: auto;
		}	
	}	
	<?php 
		echo rozer_background_config('body', rdt_get_option('layout_background',[]));
	?>		
	.main-header-content{	
		padding: <?php echo (rdt_get_option('header_main_padding', 24)); ?>px 0;		
	}		
	.promo-block{	
		background: <?php echo esc_attr(rdt_get_option('header_promo_bground', '#f1f1f1')); ?> ;
		<?php if(rdt_get_option('header_promo_type', 'text') == 'text'): ?>	
		height: <?php echo esc_attr(rdt_get_option('header_promo_height', 40)); ?>px ; 	
		<?php endif; ?>		
		color: <?php echo esc_attr(rdt_get_option('header_promo_color', '#444444')); ?> ; 	
	}			
	.topbar-header{	
		background-color: <?php echo esc_attr(rdt_get_option('header_topbar_background', '#ffffff')); ?> ;	
		font-size: <?php echo esc_attr(rdt_get_option('header_topbar_font' , 12)); ?>px;	
	}
	<?php 
		$hmenu_background = rdt_get_option('hmenu_background', '#ffffff');	
		$hmenu_item_color = rdt_get_option('hmenu_item_color', '#1d1d1d');	
		$hmenu_item_color_active = rdt_get_option('hmenu_item_color_active', '#146cda');	
		$hmenu_item_background_color = rdt_get_option('hmenu_item_background_color', 'rgba(255,255,255,0)');	
		$hmenu_item_background_color_active = rdt_get_option('hmenu_item_background_color_active', 'rgba(255,255,255,0)');	
		$hmenu_item_font = rdt_get_option('hmenu_item_font', 14);	
		$hmenu_item_space = rdt_get_option('hmenu_item_space', 15);	
	?>	
	:root{	
		--menubackground: <?php echo esc_attr($hmenu_background); ?>;	
		--menucolor: <?php echo esc_attr($hmenu_item_color); ?>;	
		--menu_active_color: <?php echo esc_attr($hmenu_item_color_active); ?>;	
	}
	.menu-background{
		background-color: <?php echo esc_attr($hmenu_background); ?>;
	}
	#_desktop_menu_ .primary-menu-wrapper ul.primary-menu > li{	
		padding:0 <?php echo esc_attr($hmenu_item_space); ?>px;	
	}	
	#_desktop_menu_ .primary-menu-wrapper ul.primary-menu > li > a{	
		font-size: <?php echo esc_attr($hmenu_item_font); ?>px;	
		color: <?php echo esc_attr($hmenu_item_color); ?>;	
		background-color: <?php echo esc_attr($hmenu_item_background_color); ?>;	
	}	
	#_desktop_menu_ .primary-menu-wrapper ul.primary-menu > li.current-menu-item > a,	
	#_desktop_menu_ .primary-menu-wrapper ul.primary-menu > li:hover > a{	
		color: <?php echo esc_attr($hmenu_item_color_active); ?>;	
		background-color: <?php echo esc_attr($hmenu_item_background_color_active); ?>;	
	}	
	/* Vertical menu */
	<?php 
		$vmenu_title_size = rdt_get_option('vmenu_title_size', 14);	
		$vmenu_title_width = rdt_get_option('vmenu_title_width',210);	
		$vmenu_title_bground = rdt_get_option('vmenu_title_bground', 'rgba(255,255,255,0)');	
		$vmenu_title_color = rdt_get_option('vmenu_title_color', '#ffffff');	
		$vmenu_items_width = rdt_get_option('vmenu_items_width', 270);	
	?>		
	.vertical-menu-wrapper .vmenu-title{	
		background: <?php echo esc_attr($vmenu_title_bground); ?>;		
		color: <?php echo esc_attr($vmenu_title_color); ?>;	
		width: <?php echo esc_attr($vmenu_title_width); ?>px;	
	}	
	.vertical-menu-wrapper .vmenu-title span{	
		font-size: <?php echo esc_attr($vmenu_title_size); ?>px;	
	}		
	#_desktop_vmenu_ .vermenu-wrapper ul.vertical-menu{	
		width: <?php echo esc_attr($vmenu_items_width); ?>px;	
	}	
	/* Sale label */
	.sale-label{	
		background: <?php echo esc_attr(rdt_get_option('catalog_product_sale_bground','#dd3333')); ?>;	
	}
	.label-d-trapezium:after{
		border-left-color: <?php echo esc_attr(rdt_get_option('catalog_product_sale_bground','#dd3333')); ?>;
		border-bottom-color: <?php echo esc_attr(rdt_get_option('catalog_product_sale_bground','#dd3333')); ?>;
	}	
	.color-swatches span.swatch{	
		width: <?php echo esc_attr(rdt_get_option('swatches_color_size', 20)); ?>px;		
		height: <?php echo esc_attr(rdt_get_option('swatches_color_size', 20)); ?>px;		
	}	
	.single-post .title-background{	
		padding: <?php echo esc_attr(rdt_get_option('blog_single_pdtitle',30)); ?>px 0;		
	}	
	.footer-bottom{ 	
		background: <?php echo esc_attr(rdt_get_option('footer_bottom_background','#146cda')); ?>;	
	}
	<?php 
		echo rozer_background_config('body.boxed #page', rdt_get_option('layout_boxed_background',[]));
	?>	
	<?php 
		echo rozer_background_config('.header-wrapper', rdt_get_option('header_main_background',[]));
	?>	
	<?php $header_background = rdt_get_option('header_main_background',['background-color' => '#ffffff',]); ?>
	:root{	
		--headerbackground: <?php echo esc_attr($header_background['background-color']); ?>;	
	}	
	<?php
		echo rozer_background_config('.page-title-section', rdt_get_option('page_title_background',[]));
	?>		
	<?php		
		echo rozer_background_config('.site-footer', rdt_get_option('footer_main_background',[]));
	?>		
	<?php	
	$css_output = ob_get_contents();
	if (ob_get_contents()) ob_end_clean();
	$css_output = rozer_clean_css($css_output);
	return $css_output;
}
function rozer_background_config($target, $bg){
	if( empty($bg) ) return;
	$output = '';
	$output .= $target.'{';
	if($bg['background-color']) {
		$output .= 'background-color:'. $bg['background-color'] .';';
	}
	if(isset($bg['background-image']) && $bg['background-image']) {
		$output .= 'background-image: url("'. $bg['background-image'] .'");';
	}
	if(isset($bg['background-repeat']) && $bg['background-repeat']) {
		$output .= 'background-repeat:'. $bg['background-repeat'] .';';
	}
	if(isset($bg['background-position']) && $bg['background-position']) {
		$output .= 'background-position:'. $bg['background-position'] .';';
	}
	if(isset($bg['background-size']) && $bg['background-size']) {
		$output .= 'background-size:'. $bg['background-size'] .';';
	}
	if(isset($bg['background-attachment']) && $bg['background-attachment']) {
		$output .= 'background-attachment:'. $bg['background-attachment'] .';';
	}
	$output .= '}';
	return $output;
}
function rozer_font_variant($variant){
	switch ($variant) {	
		  case "100":	
			echo "font-weight: 100;";	
			break;	
		  case "100italic":	
			echo "font-weight: 100;font-style: italic";	
			break;	
		  case "300":	
			echo "font-weight: 300;";	
			break;	
		  case "300italic":	
			echo "font-weight: 300;font-style: italic";	
			break;	
			case "500":	
			echo "font-weight: 500;";	
			break;	
		  case "500italic":	
			echo "font-weight: 500;font-style: italic";	
			break;	
		  case "600":	
			echo "font-weight: 600;";	
			break;	
		  case "600italic":	
			echo "font-weight: 600;font-style: italic";	
			break;	
		  case "700":	
			echo "font-weight: 700;";	
			break;	
		  case "700italic":	
			echo "font-weight: 700;font-style: italic";	
			break;	
		  case "800":	
			echo "font-weight: 800;";	
			break;	
		  case "800italic":	
			echo "font-weight: 800;font-style: italic";	
			break;	
		  case "900":	
			echo "font-weight: 900;";	
			break;	
		  case "900italic":	
			echo "font-weight: 900;font-style: italic";	
			break;	
		  case "italic":	
			echo "font-style: italic;font-weight: 400;";	
			break;	
		  default:	
			echo "font-weight: 400;";	
		}
}
function rozer_clean_css($css) {
	// Remove comments
	$css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
	// Remove space after colons
	$css = str_replace(': ', ':', $css);
	// Remove whitespace
	$css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css);
	return $css;
}