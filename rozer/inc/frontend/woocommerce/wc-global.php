<?php

if( !is_woocommerce_activated() ){
	return;
}
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');
add_action( 'woocommerce_after_cart', 'woocommerce_cross_sell_display' );
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
add_action( 'rozer_woocommerce_breadcrumb', 'woocommerce_breadcrumb' );
/* Cart desktop */
function rozer_header_cart(){
	if(ROZER_CATALOG_MODE) return;
	$minicart_config = rdt_get_option('header_elements_cart_minicart','off-canvas');
	$cart_icon = rdt_get_option('header_elements_cart_icon','bag');
	$classes[] = '';
	if($minicart_config == 'dropdown'){
		$classes[] = 'minicart-dropdown';
	}elseif($minicart_config == 'off-canvas'){
		$classes[] = 'minicart-side';
	}else{
		$classes[] = 'minicart-none';
	}
	?>
	<?php if($minicart_config != 'dropdown') { ?>
	<div id="_desktop_cart_">
	<?php } ?>
        <div class="header-block cart-block cart-<?php echo esc_attr($minicart_config); ?>">
            <?php
        	global $woocommerce;
	    	?>
	        <div class="header-cart <?php echo esc_attr(implode(' ', $classes)); ?>">
	            <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="dropdown-toggle cart-contents">
	                <i class="icon-rt-<?php echo esc_attr($cart_icon); ?>" aria-hidden="true"></i>
	                <span class="cart-count"><?php echo esc_html($woocommerce->cart->cart_contents_count);?></span>
	            </a>
	            <?php if($minicart_config == 'dropdown') { ?>
					<div class="widget_shopping_cart_content"></div>
				<?php } ?>
				<?php if($minicart_config == 'off-canvas') { ?>
					<nav id="cart-side" class="">
						<div class="cart-side-navbar">
							<div class="cart-side-navbar-inner">
							<div class="popup-cart-title"><?php esc_html_e('Your Cart', 'rozer' ); ?> </div>
							<a href="#" class="side-close-icon" title="<?php esc_attr_e('Close', 'rozer'); ?>"><i class="icon-rt-close-outline"></i></a>
							</div>
						</div>
						<div class="cart-side-content">
							<div class="widget_shopping_cart_content"></div>
						</div>
					</nav>
				<?php } ?>
	        </div> 
        </div>
    <?php if($minicart_config != 'dropdown') { ?>
	</div>
	<?php }
}
/* Cart mobile */
function rozer_header_cart_mobile(){
	$catalog_mode  = false; // Need: get catalog mode config
	if($catalog_mode) return;
	$minicart_config = rdt_get_option('header_elements_cart_minicart','off-canvas');
	$cart_icon = rdt_get_option('header_elements_cart_icon');
	$classes[] = 'minicart-side';
	?>
	<?php if($minicart_config == 'dropdown') { ?>
        <div class="header-block cart-block cart-block-mobile">
            <?php
        	global $woocommerce;
	    	?>
	        <div class="header-cart <?php echo esc_attr(implode(' ', $classes)); ?>">
	            <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="dropdown-toggle cart-contents">
	                <i class="icon-rt-bag" aria-hidden="true"></i>
	                <span class="cart-count"><?php echo esc_html($woocommerce->cart->cart_contents_count);?></span>
	            </a>		       
				<nav id="cart-side" class="">
					<div class="cart-side-navbar">
						<div class="cart-side-navbar-inner">
						<div class="popup-cart-title"><?php esc_html_e('Your Cart', 'rozer' ); ?> </div>
						<a href="#" class="side-close-icon" title="<?php esc_attr_e('Close', 'rozer'); ?>"><i class="icon-rt-close-outline"></i></a>
						</div>
					</div>
					<div class="cart-side-content">
						<div class="widget_shopping_cart_content"></div>
					</div>
				</nav>
	        </div> 
        </div>
    <?php } else { ?>
    	<div id="_mobile_cart_"></div>
    <?php }
}
/*
 * Get items count 
 */
function rozer_minicart_items_count() {
	$cart_count =  WC()->cart->cart_contents_count;
	return '<span class="cart-count">' . $cart_count . '</span>';
}
/* 
 * Update minicart counter 
 */
function rozer_minicart_update($fragments) {
	$cart_count = rozer_minicart_items_count();
	$fragments['.cart-count'] = $cart_count;
	return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'rozer_minicart_update'); 
/*
 * Get sale label for product
 */
add_action('rozer_product_labels', 'rozer_get_product_sale_label', 10);
function rozer_get_product_sale_label(){
	global $product;
	$sale_date_start = get_post_meta( $product->get_id(), '_sale_price_dates_from', true );
	$sale_date_end = get_post_meta( $product->get_id(), '_sale_price_dates_to', true );
	$curent_date = strtotime( date( 'Y-m-d H:i:s' ) );
	if ( $sale_date_end < $curent_date || $curent_date < $sale_date_start ) return;
 	$sale_label_type = rdt_get_option('catalog_product_sale', 'text');
 	$sale_label_design = rdt_get_option('catalog_product_sale_design', 'circle');
 	$sale_label_bground = rdt_get_option('catalog_product_sale_bground', '');
 	$class = '';
	if($sale_label_design){
		$class .= ' label-d-'. $sale_label_design;
	}
 	?>
 	<span class="product-label sale-label<?php echo esc_attr($class); ?>">
   	<?php if($sale_label_type == 'text') : ?>
   		<span><?php echo esc_html__('Sale', 'rozer'); ?></span>
   	<?php else : ?>
   		<?php if( $product->is_type('variable')){
   			$prices = $product->get_variation_prices();
   			foreach( $prices['price'] as $key => $price ){
	            // Only on sale variations
	            if( $prices['regular_price'][$key] !== $price ){
	                // Calculate and set in the array the percentage for each variation on sale
	                $percentages[] = round(100 - ($prices['sale_price'][$key] / $prices['regular_price'][$key] * 100));
	            }
	        }
	        $percent = max($percentages);
   		}else{
   			$percent = (($product->get_regular_price() - $product->get_sale_price())/$product->get_regular_price() )*100;
   		} ?>
   		<span> -<?php echo round($percent, 0); ?>% </span>
   	<?php endif; ?>
	</span>
	<?php
}
/*
 * Get specific label for product
 */
add_action('rozer_product_labels', 'rozer_get_product_specific_label', 5);
function rozer_get_product_specific_label(){
	global $product;
	$label_text = get_post_meta($product->get_id() , 'product_label');
	$label_design = get_post_meta($product->get_id() , 'product_label_design' , 'circle');
	$label_position = get_post_meta($product->get_id() , 'product_label_position', 'left');
	$label_bground = get_post_meta($product->get_id() , 'product_label_bground');
	$label_image = get_post_meta($product->get_id() , 'product_label_image');
	if(!empty($label_image)) {
		$class = '';
		if(!empty($label_position)){
			$class .= ' label-p-'. $label_position;
		}
		if(!empty($label_text)) {
			$class .= ' label-has-hover';
		}
		?>
		<span class="product-label specific-label-image <?php echo esc_attr($class); ?>">
		<img src="<?php echo esc_url($label_image[0]); ?>'" alt="label"/>
		<?php if(!empty($label_text)) { ?>
			<span><?php echo esc_attr($label_text[0]); ?></span>
		<?php } ?>
		</span>
	<?php }else{
		if(!$label_text) return;
		$class = '';
		if(isset($label_design) && $label_design) {
			$class .= 'label-d-'. $label_design;
		}
		if(!empty($label_position)){
			$class .= ' label-p-'. $label_position;
		}
		$style = '';
		if(!empty($label_bground)) {
			$style = 'style=background:'. $label_bground[0].';';
		}
		?>
		<span class="product-label specific-label <?php echo esc_attr($class); ?>" <?php echo esc_attr($style); ?>><?php echo esc_attr($label_text[0]); ?></span>
		<?php 
	}
}
/*
 * Compare button
 */
function rozer_product_compare() {
	echo '<li class="add-to-compare">';
        global $product;
        $product_id = $product->get_id();
        // return if product doesn't exist
        if ( empty( $product_id ) || apply_filters( 'yith_woocompare_remove_compare_link_by_cat', false, $product_id ) )
            return;
        $is_button = ! isset( $button_or_link ) || ! $button_or_link ? get_option( 'yith_woocompare_is_button' ) : $button_or_link;
        if ( ! isset( $button_text ) || $button_text == 'default' ) {
            $button_text = get_option( 'yith_woocompare_button_text', esc_html__( 'Compare', 'rozer' ) );
        }
        printf( '<a href="%s" class="%s" data-product_id="%d" rel="nofollow">%s</a>',  rozer_add_product_url( $product_id ), 'compare' . ( $is_button == 'button' ? ' button' : '' ), $product_id, $button_text );
	echo '</li>';
}
function rozer_add_product_url( $product_id ) {
    $action_add = 'yith-woocompare-add-product';
    $url_args = array(
        'action' => 'asd',
        'id' => $product_id
    );
    return apply_filters( 'yith_woocompare_add_product_url', esc_url_raw( add_query_arg( $url_args ) ), $action_add );
}
/*
 * Popup login form
 */
function rozer_account_login_popup(){
	if ( !is_user_logged_in() && !is_checkout() && !is_account_page() ) {
    ?>
    <div id="login-form-popup" class="lightbox-content">
		<div class="form-content">
			<a href="#" class="side-close-icon" title="Close"><i class="icon-rt-close-outline"></i></a>
			<?php
			$show_register_form = ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ); ?>
			<div class="header-account-content panel-wrap">
				<header>
					<?php the_custom_logo(); ?>
				</header>
				<?php
				if ( $show_register_form ) : ?>
					<div class="rt-tabs-wrapper">
					<ul class="tabs rt-tabs">
						<li class="rt-tab active">
							<a href="#popup-form-login"><?php esc_html_e( 'Login', 'rozer' ); ?></a>
						</li>
						<li class="rt-tab">
							<a href="#popup-form-register"><?php esc_html_e( 'Register', 'rozer' ); ?></a>
						</li>
					</ul>
				<?php endif; ?>
				<form id="popup-form-login" class="woocommerce-form woocommerce-form-login login <?php if ( $show_register_form ) {
					echo 'rt-tab-panel opened';
				} ?>" data-tab-name="login" autocomplete="off" method="post"
					  action="<?php echo get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ?>">
					<div class="login_msg fail" style="display:none"></div>	
					<?php do_action( 'woocommerce_login_form_start' ); ?>
					<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
						<label for="username"><?php esc_html_e( 'Username or email address', 'rozer' ); ?>
							&nbsp;<span class="required">*</span></label>
						<input type="text" class="woocommerce-Input woocommerce-Input--text input-text"
							   name="username" id="username"
							   value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>"/><?php // @codingStandardsIgnoreLine ?>
					</p>
					<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
						<label for="password"><?php esc_html_e( 'Password', 'rozer' ); ?>&nbsp;<span
									class="required">*</span></label>
						<input class="woocommerce-Input woocommerce-Input--text input-text" type="password"
							   name="password" id="password" autocomplete="current-password"/>
					</p>
					<?php do_action( 'woocommerce_login_form' ); ?>
					<div class="box-password">
						<p>
							<label class="woocommerce-form__label woocommerce-form__label-for-checkbox inline">
								<input class="woocommerce-form__input woocommerce-form__input-checkbox"
									   name="rememberme" type="checkbox" id="rememberme" value="forever"/>
								<span><?php esc_html_e( 'Remember me', 'rozer' ); ?></span>
							</label>
						</p>
						<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"
						   class="lost-password"><?php esc_html_e( 'Lost password ?', 'rozer' ); ?></a>
					</div>
					<p class="login-submit">
						<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
						<button type="submit" class="woocommerce-Button button" name="login"
								value="<?php esc_attr_e( 'Log in', 'rozer' ); ?>"><?php esc_html_e( 'Log in', 'rozer' ); ?></button>
					</p>
					<p class="login_msg success" style="display:none;"></p>
					<?php do_action( 'woocommerce_login_form_end' ); ?>
					<input type="hidden" name="action" value="ajaxlogin">
				</form>
				<?php if ( $show_register_form ) : ?>
					<form id="popup-form-register" method="post" autocomplete="off"
						  class="woocommerce-form woocommerce-form-register rt-tab-panel register"
						  data-tab-name="register" <?php do_action( 'woocommerce_register_form_tag' ); ?>
						  action="<?php echo get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ?>">
						<div class="register_msg fail" style="display:none"></div>	
						<?php do_action( 'woocommerce_register_form_start' ); ?>
						<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>
							<p class="woocommerce-form-row woocommerce-form-row--wide form-row-wide">
								<label for="reg_username"><?php esc_html_e( 'Username', 'rozer' ); ?>
									&nbsp;<span class="required">*</span></label>
								<input type="text" class="woocommerce-Input woocommerce-Input--text input-text"
									   name="username" id="reg_username" autocomplete="username"
									   value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>"/><?php // @codingStandardsIgnoreLine ?>
							</p>
						<?php endif; ?>
						<p class="woocommerce-form-row woocommerce-form-row--wide form-row-wide">
							<label for="reg_email"><?php esc_html_e( 'Email address', 'rozer' ); ?>
								&nbsp;<span class="required">*</span></label>
							<input type="email" class="woocommerce-Input woocommerce-Input--text input-text"
								   name="email" id="reg_email" autocomplete="email"
								   value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>"/><?php // @codingStandardsIgnoreLine ?>
						</p>
						<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>
							<p class="woocommerce-form-row woocommerce-form-row--wide form-row-wide">
								<label for="reg_password"><?php esc_html_e( 'Password', 'rozer' ); ?>
									&nbsp;<span class="required">*</span></label>
								<input type="password"
									   class="woocommerce-Input woocommerce-Input--text input-text"
									   name="password" id="reg_password" autocomplete="new-password"/>
							</p>
						<?php else : ?>
							<p><?php esc_html_e( 'A password will be sent to your email address.', 'rozer' ); ?></p>
						<?php endif; ?>
						<?php do_action( 'woocommerce_register_form' ); ?>
						<p class="woocommerce-FormRow">
							<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
							<button type="submit" class="woocommerce-Button button" name="register"
									value="<?php esc_attr_e( 'Register', 'rozer' ); ?>"><?php esc_html_e( 'Register', 'rozer' ); ?></button>
						</p>
						<?php do_action( 'woocommerce_register_form_end' ); ?>
						<p class="register_msg success" style="display:none;"></p>
						<input type="hidden" name="action" value="ajaxregister">
					</form>
					</div>
					<?php
				endif; ?>
			</div>
		</div>
    </div>
    <?php 
    }
}
add_action('wp_footer', 'rozer_account_login_popup', 10);
/*
 * Checkout steps in page title
 */
if( ! function_exists( 'rozer_checkout_process' ) ) {
	function rozer_checkout_process() {
		?>
            <div class="rozer-checkout-process-wrap">
                <ul>
                	<li class="checkout-process-step step-cart <?php echo (is_cart()) ? 'step-active' : 'step-inactive'; ?>">
                		<a href="<?php echo esc_url( wc_get_cart_url() ); ?>">
                			<span><?php esc_html_e('Shopping cart', 'rozer'); ?></span>
                		</a>
                	</li>
                	<li class="checkout-process-step step-checkout <?php echo (is_checkout() && ! is_order_received_page()) ? 'step-active' : 'step-inactive'; ?>">
                		<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>">
                			<span><?php esc_html_e('Checkout', 'rozer'); ?></span>
                		</a>
                	</li>
                	<li class="checkout-process-step step-complete <?php echo (is_order_received_page()) ? 'step-active' : 'step-inactive'; ?>">
                		<span><?php esc_html_e('Order complete', 'rozer'); ?></span>
                	</li>
                </ul>
            </div>
		<?php
	}
}
/*
 * Minus , plus button for quantity
 */
add_action('woocommerce_after_quantity_input_field', 'rozer_plus_quantity_button');
function rozer_plus_quantity_button(){
	echo '<input class="plus" type="button" value="+">';
}
add_action('woocommerce_before_quantity_input_field', 'rozer_minus_quantity_button');
function rozer_minus_quantity_button(){
	echo '<input class="minus" type="button" value="-">';
}