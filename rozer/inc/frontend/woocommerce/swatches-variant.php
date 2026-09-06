<?php

if( ! function_exists( 'rozer_get_attribute_type' ) ) {
	function rozer_get_attribute_type( $attr_name ) {
		$attr = get_tax_attribute($attr_name);
		return $attr->attribute_type;
	}
}
if( ! function_exists( 'rozer_swatches_variation_attribute_options' ) ) {
	function rozer_swatches_variation_attribute_options( $args = array() ) {
		echo '<div id="'.$args['attribute'].'">';
		echo rozer_get_swatches_attribute($args); 
		echo '</div>';
	}
}
/*
 * Swatches variation 
 */
add_action('rozer_swatches_shop','rozer_swatches_variation_shop');
function rozer_swatches_variation_shop(){
	global $product; 
	if(!$product->get_id() || $product->get_type() != 'variable') return false;
	// Get product attributes
	$attributes = $product->get_variation_attributes();
	$main_attr = 'pa_'.rdt_get_option('swatches_main_attr','');
	if(!isset($attributes[$main_attr]) && empty($attributes[$main_attr])) return false;
	$attribute_type = rozer_get_attribute_type($main_attr);	
	$swatches_image_status = rdt_get_option('swatches_attr_active', true);
	?>
	<div class="shop-swatches" data-action-behavior="<?php if($swatches_image_status) echo esc_attr(rdt_get_option('swatches_attr_action','click')); ?>">
	<?php
	if($attribute_type == 'color' || $attribute_type == 'label'){
		echo rozer_get_swatches_attribute_shop(
			array(
				'options'   => $attributes[$main_attr],
				'attribute' => $main_attr,
				'product'   => $product,
			)
		);
	}else{
		echo wc_dropdown_variation_attribute_options(
			array(
				'options'   => $attributes[$main_attr],
				'attribute' => $main_attr,
				'product'   => $product,
			)
		);
	}	
	?>
	</div>
	<?php
}
function rozer_get_swatches_attribute_shop($args){
	$options   = $args['options'];
	$product   = $args['product'];
	$attribute = $args['attribute'];
	$attr = get_tax_attribute($attribute);
	if ( empty( $options ) && ! empty( $product ) && ! empty( $attribute ) ) {
		$attributes = $product->get_variation_attributes();
		$options    = $attributes[$attribute];
	}
	$terms = wc_get_product_terms( $product->get_id(), $attribute, array( 'fields' => 'all' ) );
	$available_variations = $product->get_available_variations();
	$swatches = '';
	foreach ( $terms as $term ) {
		if ( in_array( $term->slug, $options ) ) {
			$attr_imgs = rozer_swatches_get_variation_images($term->slug, $available_variations, $product->get_id());
			$swatches .= rozer_swatch_html_shop('', $term, $attr->attribute_type, $args, $attr_imgs);
		}
	}
	return $swatches;
}
function rozer_swatches_get_variation_images($attr_val , $variants , $id_product){
	$variant_images = array();
	foreach($variants as $key => $variant) {
		if( in_array($attr_val,$variant['attributes']) ) {
			if( !empty($variant['variation_gallery_images'])) {
				$variant_images['first_img'] =  $variant['variation_gallery_images'][0]['thumb_src'];
				$variant_images['gthumb_img'] =  $variant['variation_gallery_images'][0]['gallery_thumbnail_src'];
				if(!empty($variant['variation_gallery_images'][1])) {
					$variant_images['second_img'] =  $variant['variation_gallery_images'][1]['thumb_src'];
				}else{
					$variant_images['second_img'] =  $variant['variation_gallery_images'][0]['thumb_src'];
				}
			}else{
				$variant_images['first_img'] =  wc_placeholder_img_src('woocommerce_thumbnail');
				$variant_images['gthumb_img'] =  wc_placeholder_img_src('woocommerce_thumbnail');
				$variant_images['second_img'] =  wc_placeholder_img_src('woocommerce_thumbnail');
			}
		}
	}
	return $variant_images;
}
function rozer_get_swatches_attribute($args){
	$options   = $args['options'];
	$product   = $args['product'];
	$attribute = $args['attribute'];
	$main_attr = 'pa_'.rdt_get_option('swatches_main_attr','');
	$attr = get_tax_attribute($attribute);
	if ( empty( $options ) && ! empty( $product ) && ! empty( $attribute ) ) {
		$attributes = $product->get_variation_attributes();
		$options    = $attributes[$attribute];
	}
	$terms = wc_get_product_terms( $product->get_id(), $attribute, array( 'fields' => 'all' ) );
	$main_attr = 'pa_'.rdt_get_option('swatches_main_attr','');
	$available_variations = $product->get_available_variations();
	$swatches = '';
	if($attribute == $main_attr) {
		foreach ( $terms as $term ) {
			if ( in_array( $term->slug, $options ) ) {
				$attr_img = rozer_swatches_get_variation_images($term->slug, $available_variations, $product->get_id());
				$swatches .= rozer_swatch_html('', $term, $attr->attribute_type, $args, $attr_img);
			}
		}
	}else{
		foreach ( $terms as $term ) {
			if ( in_array( $term->slug, $options ) ) {
				$swatches .= rozer_swatch_html('', $term, $attr->attribute_type, $args);
			}
		}
	}
	return $swatches;
}
/**
* Print HTML of a single swatch in Shop page
*
* @param $html
* @param $term
* @param $type
* @param $args
*
* @return string
*/
function rozer_swatch_html_shop( $html, $term, $type, $args , $image_src = array() ) {
	$selected = isset($args['selected']) && sanitize_title( $args['selected'] ) == $term->slug ? 'selected' : '';
	$name     = esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name ) );
	switch ( $type ) {
		case 'color':
			$color = get_term_meta( $term->term_id, 'term-color', true );
			$image = get_term_meta( $term->term_id, 'term-image', true );
			$product = $args['product'];
			$rt_replace_image = get_post_meta($product->get_id() , 'rtproduct_replace_image' , 'default');
			if($rt_replace_image == 'default'){
				$repacement = rdt_get_option('swatches_attr_image', true);
			}else if($rt_replace_image == 'yes'){
				$repacement = true;
			}else{
				$repacement = false;
			}
			if( !$repacement || empty($image_src) ) {
				if($image){
					$html = sprintf(
						'<span class="swatch swatch-color swatch-%s %s" style="background: url(%s) no-repeat;" title="%s" data-value="%s" data-first-image="%s" data-second-image="%s">%s</span>',
						esc_attr( $term->slug ),
						$selected,
						esc_url($image),
						esc_attr( $name ),
						esc_attr( $term->slug ),
						$image_src['first_img']? esc_url($image_src['first_img']) : '',
						$image_src['second_img']? esc_url($image_src['second_img']) : '',
						$name
					);
				}else{
					$html = sprintf(
						'<span class="swatch swatch-color swatch-%s %s" style="background-color:%s;" title="%s" data-value="%s" data-first-image="%s" data-second-image="%s">%s</span>',
						esc_attr( $term->slug ),
						$selected,
						$color,
						esc_attr( $name ),
						esc_attr( $term->slug ),
						$image_src['first_img']? esc_url($image_src['first_img']) : '',
						$image_src['second_img']? esc_url($image_src['second_img']) : '',
						$name
					);
				}
			}else{
				$html = sprintf(
					'<span class="swatch swatch-color swatch-image swatch-%s %s" style="background: url(%s) no-repeat center;background-size:cover" title="%s" data-value="%s" data-first-image="%s" data-second-image="%s">%s</span>',
					esc_attr( $term->slug ),
					$selected,
					esc_url($image_src['gthumb_img']),
					esc_attr( $name ),
					esc_attr( $term->slug ),
					$image_src['first_img']? esc_url($image_src['first_img']) : '',
					$image_src['second_img']? esc_url($image_src['second_img']) : '',
					$name
				);
			}
			break;
		case 'label':
			$label = get_term_meta( $term->term_id, 'term-label', true );
			$label = $label ? $label : $name;
			$html  = sprintf(
				'<span class="swatch swatch-label swatch-%s %s" title="%s">%s</span>',
				esc_attr( $term->slug ),
				$selected,
				esc_attr( $name ),
				esc_attr( $term->slug ),
				esc_html( $label )
			);
			break;
	}
	return $html;
}
/**
* Print HTML of a single swatch in detail page
*
* @param $html
* @param $term
* @param $type
* @param $args
*
* @return string
*/
function rozer_swatch_html( $html, $term, $type, $args , $image_src = array() ) {
	$selected = isset($args['selected']) && sanitize_title( $args['selected'] ) == $term->slug ? 'selected' : '';
	$name     = esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name ) );
	$swatches_color_style = rdt_get_option('swatches_color_design', 'circle');
	$swatches_label_style = rdt_get_option('swatches_label_design', '1');
	switch ( $type ) {
		case 'color':
			$color = get_term_meta( $term->term_id, 'term-color', true );
			$image = get_term_meta( $term->term_id, 'term-image', true );
			$product = $args['product'];
			$rt_replace_image = get_post_meta($product->get_id() , 'rtproduct_replace_image' , 'default');
			if($rt_replace_image == 'default'){
				$repacement = rdt_get_option('swatches_attr_image', true);
			}else if($rt_replace_image == 'yes'){
				$repacement = true;
			}else{
				$repacement = false;
			}
			if(!$repacement || empty($image_src)) {
				if($image){
					$html = sprintf(
						'<span class="swatch swatch-style-%s swatch-color swatch-%s %s" style="background: url(%s) no-repeat;" title="%s" data-value="%s">%s</span>',
						$swatches_color_style,
						esc_attr( $term->slug ),
						$selected,
						esc_url($image),
						esc_attr( $name ),
						esc_attr( $term->slug ),
						$name
					);
				}else{
					$html = sprintf(
						'<span class="swatch swatch-style-%s swatch-color swatch-%s %s" style="background-color:%s;" title="%s" data-value="%s">%s</span>',
						$swatches_color_style,
						esc_attr( $term->slug ),
						$selected,
						$color,
						esc_attr( $name ),
						esc_attr( $term->slug ),
						$name
					);
				}
			}else{
				$html = sprintf(
					'<span class="swatch swatch-image swatch-style-%s swatch-color swatch-%s %s" style="background: url(%s) no-repeat center;background-size:cover;" title="%s" data-value="%s">%s</span>',
					$swatches_color_style,
					esc_attr( $term->slug ),
					$selected,
					esc_url($image_src['first_img']),
					esc_attr( $name ),
					esc_attr( $term->slug ),
					$name
				);	
			}
			break;
		case 'label':
			$label = get_term_meta( $term->term_id, 'term-label', true );
			$label = $label ? $label : $name;
			$html  = sprintf(
				'<span class="swatch swatch-style-%s swatch-label swatch-%s %s" title="%s" data-value="%s">%s</span>',
				$swatches_label_style,
				esc_attr( $term->slug ),
				$selected,
				esc_attr( $name ),
				esc_attr( $term->slug ),
				esc_html( $label )
			);
			break;
	}
	return $html;
}
add_action('woocommerce_before_add_to_cart_form', 'rozer_get_default_gallery');
function rozer_get_default_gallery(){
	global $product;
	$outout = array();
	$main_image = $product->get_image_id();
	$thumbnail_images = $product->get_gallery_image_ids();
	array_unshift($thumbnail_images, $main_image);
	foreach($thumbnail_images as $id){
		$output[] = rozer_get_image_data($id);
	}
	$product_video_id = get_post_meta( get_the_ID(), 'product_video_upload_id');
	$product_video_upload = get_post_meta( get_the_ID(), 'product_video_upload', [] );
	$product_video_position = get_post_meta( get_the_ID(), 'product_video_position' , 'last' );
	if($product_video_id) {
		$v_output = array(
			'video_src' => $product_video_upload[0],
			'thumb_src' =>  '<div class="video-thumb"><i class="icon-rt-videocam-outline"></i></div>',
		);
		if($product_video_position == 'first') {
			array_unshift($output, $v_output);
		}else if($product_video_position == 'second'){
			$nv_output = array(
				'0' => array(
				'video_src' => $product_video_upload[0],
				'thumb_src' => '<div class="video-thumb"><i class="icon-rt-videocam-outline"></i></div>'
				)
			);
			rozer_array_insert( $output, 1, $nv_output );
		}else{
			array_push($output , $v_output);
		}
	}
	//echo '<pre>'; print_r($output); echo '</pre>'; die('x_x');
	return $output;
}
function rozer_get_image_data($attachment_id){
	$full_size_image   = wp_get_attachment_image_src( $attachment_id, 'full' );
	$thumbnail         = wp_get_attachment_image_src( $attachment_id, 'woocommerce_gallery_thumbnail' );
	$output = array(
		'image_detail' => array(
			'full_src' => $full_size_image[0],
			'full_w'   => $full_size_image[1],
			'full_h'   => $full_size_image[2],
		),
		'thumb_detail' => array(
			'thumb_src'=> $thumbnail[0],
			'thumb_w'  => $thumbnail[1],
			'thumb_h'  => $thumbnail[2],
		),
	);
	return $output;
}
/**
 * @param array      $array
 * @param int|string $position
 * @param mixed      $insert
 */
function rozer_array_insert(&$array, $position, $insert)
{
    if (is_int($position)) {
        array_splice($array, $position, 0, $insert);
    } else {
        $pos   = array_search($position, array_keys($array));
        $array = array_merge(
            array_slice($array, 0, $pos),
            $insert,
            array_slice($array, $pos)
        );
    }
}
