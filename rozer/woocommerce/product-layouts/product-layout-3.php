<?php
	global $product; 
	if ( !isset( $product ) ) {
		return;
	}
	$image_class = '';
	$show_second_image = rdt_get_option('catalog_product_hover', true);
	$show_quickview = rdt_get_option('catalog_product_quickview', true);
	$show_category = rdt_get_option('catalog_product_category', true);
	$show_rating = rdt_get_option('catalog_product_rating', true);
	$show_countdown = rdt_get_option('catalog_product_countdown', true);
?>
<div class="product-inner product-grid">
	<div class="product-image">
		<div class="product-labels">
			<?php do_action('rozer_product_labels'); ?>
		</div>
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
		<div class="action-links">
			<ul>
				<?php if ( class_exists( 'YITH_WCWL' ) ) : ?>
					<li class="add-to-wishlist"> 
						<?php echo preg_replace("/<img[^>]+\>/i", " ", do_shortcode('[yith_wcwl_add_to_wishlist]')); ?>
					</li>
				<?php endif; ?>
				<?php if( class_exists( 'YITH_Woocompare' ) ) : ?>
					<?php rozer_product_compare(); ?>
				<?php endif; ?>
				<?php if($show_quickview): ?>
				<li class="button-quickview">
					<?php echo rozer_product_quickview(); ?>
				</li>
				<?php endif; ?>
			</ul>
		</div>
		<?php if($show_countdown) {
			rozer_product_onsale_countdown();
		} ?>
	</div>
	<div class="product-content">
		<?php if($show_category): ?>
			<div class="product-category">
				<?php echo get_top_category_name(); ?>
			</div>
		<?php endif; ?>
		<div class="product-title">
			<h6><a href="<?php the_permalink();?>"><?php the_title();?></a></h6>
		</div>
		<?php if($show_rating): ?>
			<div class="product-rating">
				<?php do_action( 'woocommerce_after_shop_loop_item_rating' ); ?>
			</div>
		<?php endif; ?>
		<?php if(ROZER_SHOW_PRICE): ?>
			<div class="product-price">
				<?php do_action( 'woocommerce_after_shop_loop_item_title' ); ?>
			</div>
		<?php endif; ?>
		<?php if(!ROZER_CATALOG_MODE): ?>
			<div class="product-cart">
				<?php woocommerce_template_loop_add_to_cart(); ?>
			</div>
		<?php endif; ?>
	</div>
</div>