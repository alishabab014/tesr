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

				<?php
				$bmsm_unit_price = (float) $product->get_price();
				if ( $bmsm_unit_price > 0 ) :
					$bmsm_tiers = array(
						array( 'qty' => 1, 'discount' => 0,    'badge' => '' ),
						array( 'qty' => 2, 'discount' => 0.10, 'badge' => 'Popular &middot; 10% OFF' ),
						array( 'qty' => 3, 'discount' => 0.15, 'badge' => 'Best Value &middot; 15% OFF' ),
					);
					$bmsm_currency = get_woocommerce_currency_symbol();
				?>
				<div class="bmsm-wrap" data-unit-price="<?php echo esc_attr( $bmsm_unit_price ); ?>" data-currency="<?php echo esc_attr( $bmsm_currency ); ?>">
					<div class="bmsm-heading"><?php esc_html_e( 'Buy More, Save More', 'rozer' ); ?></div>
					<div class="bmsm-tiers">
						<?php foreach ( $bmsm_tiers as $tier ) : ?>
							<label class="bmsm-tier<?php echo 1 === $tier['qty'] ? ' active' : ''; ?>" data-qty="<?php echo esc_attr( $tier['qty'] ); ?>" data-discount="<?php echo esc_attr( $tier['discount'] ); ?>">
								<?php if ( $tier['badge'] ) : ?>
									<span class="bmsm-badge"><?php echo wp_kses_post( $tier['badge'] ); ?></span>
								<?php endif; ?>
								<input type="radio" name="bmsm_tier" value="<?php echo esc_attr( $tier['qty'] ); ?>" <?php checked( 1, $tier['qty'] ); ?>>
								<span class="bmsm-tier-qty"><?php echo esc_html( $tier['qty'] ); ?></span>
							</label>
						<?php endforeach; ?>
					</div>
					<div class="bmsm-total"><?php esc_html_e( 'Total:', 'rozer' ); ?> <span class="bmsm-total-amount"><?php echo wp_kses_post( wc_price( $bmsm_unit_price ) ); ?></span></div>
				</div>
				<?php endif; ?>

				<?php woocommerce_template_single_add_to_cart(); ?>
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

.bmsm-wrap{margin:16px 0}
.bmsm-heading{font-weight:700;font-size:15px;color:#111;margin-bottom:8px}
.bmsm-tiers{display:flex;gap:10px}
.bmsm-tier{position:relative;flex:1;border:2px solid #e5e7eb;border-radius:10px;padding:14px 8px 10px;text-align:center;cursor:pointer;transition:border-color .2s ease,background .2s ease}
.bmsm-tier:hover{border-color:#1d3a6e}
.bmsm-tier.active{border-color:#1d3a6e;background:#f3f6fc}
.bmsm-tier input{position:absolute;opacity:0;width:0;height:0}
.bmsm-tier-qty{display:block;font-weight:700;font-size:15px;color:#111}
.bmsm-badge{position:absolute;top:-11px;left:50%;transform:translateX(-50%);white-space:nowrap;background:#1d3a6e;color:#fff;font-size:10px;font-weight:700;padding:3px 8px;border-radius:999px}
.bmsm-total{margin-top:10px;font-size:14px;color:#333}
.bmsm-total-amount{font-weight:800;color:#1d3a6e}
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

	// Buy More, Save More tier selector
	document.querySelectorAll('.bmsm-wrap').forEach(function(wrap){
		var unitPrice = parseFloat(wrap.getAttribute('data-unit-price')) || 0;
		var currency = wrap.getAttribute('data-currency') || '';
		var totalEl = wrap.querySelector('.bmsm-total-amount');
		var qtyInput = wrap.closest('.cpp-summary').querySelector('.quantity input.qty');

		function formatPrice(amount){
			return currency + amount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
		}

		wrap.querySelectorAll('.bmsm-tier').forEach(function(tier){
			tier.addEventListener('click', function(){
				wrap.querySelectorAll('.bmsm-tier').forEach(function(t){ t.classList.remove('active'); });
				tier.classList.add('active');
				var radio = tier.querySelector('input[type="radio"]');
				if (radio) radio.checked = true;

				var qty = parseInt(tier.getAttribute('data-qty'), 10) || 1;
				var discount = parseFloat(tier.getAttribute('data-discount')) || 0;

				if (qtyInput) {
					qtyInput.value = qty;
					qtyInput.dispatchEvent(new Event('change', { bubbles: true }));
				}
				if (totalEl) {
					totalEl.textContent = formatPrice(unitPrice * qty * (1 - discount));
				}
			});
		});
	});
});
</script>