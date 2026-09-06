<?php
	global $product; 
	if ( !isset( $product ) ) {
		return;
	}
	$image_class = '';
	$product_label = get_post_meta($product->get_id() , 'product_label');
	$show_second_image = rdt_get_option('catalog_product_hover', true);
	$show_quickview = rdt_get_option('catalog_product_quickview', true);
	$show_category = rdt_get_option('catalog_product_category', true);
	$show_rating = rdt_get_option('catalog_product_rating', true);
	$show_countdown = rdt_get_option('catalog_product_countdown', true);
	$short_description = $product->get_short_description();
?>
<div class="product-inner product-list">
	<div class="product-image">
		<?php if($product_label) { ?>
			<span class="product-label"><?php echo esc_attr($product_label[0]); ?></span>
		<?php } ?>
		<a href="<?php echo esc_url( get_permalink() ); ?>" title="<?php the_title_attribute(); ?>">
			<?php
				if ( has_post_thumbnail( $product->get_id() ) ) {   
					echo  get_the_post_thumbnail( $product->get_id(), 'shop_catalog', array( 'class' => $image_class ) );
				} else {
					echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="Placeholder" />', wc_placeholder_img_src() ), $product->get_id() );
				}
				if($show_second_image){
					echo rozer_product_thumbnail_hover($product);
				}
			?>
		</a>
		<?php if($show_quickview): ?>
		<div class="product-quickview">
			<?php echo rozer_product_quickview(); ?>
		</div>
		<?php endif; ?>
	</div>
	<div class="product-content">
		<?php if($show_category): ?>
		<div class="product-category">
			<?php echo get_top_category_name(); ?>
		</div>
		<?php endif; ?>
		<div class="product-title">
			<h5><a href="<?php the_permalink();?>"><?php the_title();?></a></h5>
		</div>
		<?php if($show_rating): ?>
			<div class="product-rating">
				<?php do_action( 'woocommerce_after_shop_loop_item_rating' ); ?>
			</div>
		<?php endif; ?>
		<div class="product-short-description">
			<?php echo esc_attr($short_description); // WPCS: XSS ok. ?>
		</div>
		<?php if($show_countdown) {
			rozer_product_onsale_countdown();
		} ?>
	</div>
	<div class="box-list">
		<div class="product_available">
			<?php echo esc_html__('Available:', 'rozer') ?>
			<span><?php echo esc_attr($product->get_stock_status()); ?></span>
		</div>
		<?php if(ROZER_SHOW_PRICE): ?>
			<div class="product-price">
				<?php do_action( 'woocommerce_after_shop_loop_item_title' ); ?>
			</div>
		<?php endif; ?>
		<div class="action-links">
			<ul>
				<?php if(!ROZER_CATALOG_MODE): ?>
				<li class="product-cart">
					<?php woocommerce_template_loop_add_to_cart(); ?>
				</li>
				<?php endif; ?>
				<?php if ( class_exists( 'YITH_WCWL' ) ) : ?>
					<li class="add-to-wishlist"> 
						<?php echo preg_replace("/<img[^>]+\>/i", " ", do_shortcode('[yith_wcwl_add_to_wishlist]')); ?>
					</li>
				<?php endif; ?>
				<?php if( class_exists( 'YITH_Woocompare' ) ) : ?>
					<?php rozer_product_compare(); ?>
				<?php endif; ?>
			</ul>
		</div>
	</div>
</div>