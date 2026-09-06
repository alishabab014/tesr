<?php
if ( ! defined( 'ABSPATH' ) ) exit;

global $product;
$product = wc_get_product( get_the_ID() );
if ( ! $product ) return;
?>
<div class="container">
	<div class="row custom-product-page">
		<div class="col-md-6 cpp-gallery">
			<div class="cpp-main-image">
				<?php echo $product->get_image( 'large' ); ?>
			</div>
			<div class="cpp-thumbs">
				<?php
				$attachment_ids = $product->get_gallery_image_ids();
				array_unshift( $attachment_ids, $product->get_image_id() );
				foreach ( $attachment_ids as $i => $id ) :
					echo wp_get_attachment_image( $id, 'thumbnail', false, array(
						'class' => 'cpp-thumb' . ( $i === 0 ? ' active' : '' ),
					) );
				endforeach;
				?>
			</div>
		</div>

		<div class="col-md-6">
			<div class="summary entry-summary cpp-summary">
				<div class="cpp-rating">
					<span class="cpp-stars">★★★★★</span>
					<a href="#reviews">4.9/5 Rated by 3k+ Happy Customers</a>
				</div>

				<h1 class="cpp-title"><?php the_title(); ?></h1>

				<p class="cpp-shipping-line">Ships Today, Fast Nationwide Shipping</p>

				<ul class="cpp-checklist">
					<?php
					// Pull bullets from the product's Short Description.
					// Write them as a bulleted list (<ul><li>...) in the
					// Short Description editor — works the same for every niche/product.
					$short_desc = $product->get_short_description();
					$benefits   = array();

					if ( $short_desc && strpos( $short_desc, '<li' ) !== false ) {
						preg_match_all( '/<li[^>]*>(.*?)<\/li>/is', $short_desc, $matches );
						if ( ! empty( $matches[1] ) ) {
							$benefits = array_map( 'wp_strip_all_tags', $matches[1] );
						}
					} elseif ( $short_desc ) {
						$plain    = wp_strip_all_tags( $short_desc );
						$benefits = array_filter( array_map( 'trim', explode( "\n", $plain ) ) );
					}

					foreach ( $benefits as $b ) :
						$b = trim( $b );
						if ( ! $b ) continue;
					?>
						<li><span class="cpp-check">✓</span><?php echo esc_html( $b ); ?></li>
					<?php endforeach; ?>
				</ul>

				<div class="cpp-price">
					<?php woocommerce_template_single_price(); // default WooCommerce price markup ?>
				</div>

				<?php woocommerce_template_single_add_to_cart(); ?>

				<?php woocommerce_upsell_display( 4, 4 ); // same upsell products, moved up next to Add to Cart ?>
			</div>
		</div>
	</div>

	<?php
	/**
	 * Hook: rozer_after_single_product_summary.
	 *
	 * @hooked woocommerce_output_product_data_tabs - 10 (Description tab)
	 */
	do_action( 'rozer_after_single_product_summary' );

	/**
	 * Hook: woocommerce_after_single_product_summary.
	 *
	 * @hooked woocommerce_upsell_display - 15
	 * @hooked woocommerce_output_related_products - 20
	 */
	do_action( 'woocommerce_after_single_product_summary' );
	?>
</div>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
.custom-product-page,.custom-product-page *{font-family:'Poppins',sans-serif}
.cpp-main-image img{width:100%;border:1px solid #e5e7eb;border-radius:8px}
.cpp-thumbs{display:flex;gap:8px;margin-top:8px}
.cpp-thumb{width:60px;height:60px;object-fit:cover;border:2px solid #e5e7eb;border-radius:6px;cursor:pointer}
.cpp-thumb.active{border-color:#1d3a6e}

.cpp-stars{color:#f5a623;letter-spacing:2px;margin-right:8px;font-size:15px}
.cpp-rating a{color:#1d3a6e;font-weight:600;text-decoration:underline;font-size:14px}
.cpp-title{font-size:28px;font-weight:800;color:#1d3a6e;margin:4px 0;text-align:left;background-color:transparent}
.cpp-shipping-line{color:#1a9c4a;font-weight:600;font-size:14px;margin:0 0 8px}

.cpp-checklist{list-style:none;padding:0;margin:8px 0}
.cpp-checklist li{display:flex;align-items:center;gap:8px;margin:4px 0;font-size:15px}
.cpp-check{background:#1a9c4a;color:#fff;border-radius:50%;width:18px;height:18px;display:flex;align-items:center;justify-content:center;font-size:11px;flex-shrink:0}

.cpp-price{margin:8px 0}
.cpp-price .price{font-size:28px;font-weight:800;color:#111}
.cpp-price .price ins{text-decoration:none;font-weight:800}
.cpp-price .price del{color:#999;font-weight:400;font-size:18px;margin-right:8px}

.single_add_to_cart_button{display:block;width:100%;padding:14px;border-radius:30px;font-size:16px;font-weight:700;text-align:center;border:none;cursor:pointer;margin-top:6px;background:#1d3a6e;color:#fff}
</style>

<script>
document.addEventListener('DOMContentLoaded',function(){
	document.querySelectorAll('.cpp-thumb').forEach(function(thumb){
		thumb.addEventListener('click', function(){
			document.querySelectorAll('.cpp-thumb').forEach(function(t){ t.classList.remove('active'); });
			thumb.classList.add('active');
			var mainImg = document.querySelector('.cpp-main-image img');
			if (mainImg) mainImg.src = thumb.src.replace('-150x150','');
		});
	});

	// Safety net: force-clear stuck WooCommerce loading overlay after add to cart
	jQuery(document.body).on('added_to_cart', function(){
		jQuery('.cpp-summary').removeClass('processing').unblock();
	});
	setTimeout(function(){
		jQuery('.cpp-summary.processing').removeClass('processing').unblock();
	}, 4000);
});
</script>