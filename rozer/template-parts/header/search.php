<?php
    $search_placeholder = rdt_get_option('header_search_placeholder', esc_html__( 'Enter your search key', 'rozer' ) );
    $search_categories = rdt_get_option('header_search_categories', true);
    $search_categories_depth = rdt_get_option('header_search_categories_depth', 1);
    $search_keywords = rdt_get_option('header_search_keywords', []);
?>
<div class="search-box">
	<form method="get" class="search-form searchbox" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
		<div class="input-wrapper">
			<?php
				if($search_categories) {
					echo rozer_get_categories_tree($search_categories_depth);
				}
			?>
			<?php if ( class_exists('WooCommerce') ) : ?>
				<input type="hidden" name="post_type" value="product" />
			<?php endif; ?>
			<input type="text" name="s" class="ajax_search search-field rozer_ajax_search" placeholder="<?php echo esc_attr($search_placeholder); ?>" autocomplete="off" />
			<span class="clear_search"><i class="icon-rt-close-outline"></i></span>
			<button type="submit" class="search-submit"><i class="icon-rt-magnifier" aria-hidden="true"></i></button>
		</div>
	</form>
	<div class="search_content">
		<?php if($search_keywords) : ?>
			<div class="search-keywords-list">
				<p><?php echo esc_html__('Popular searches :', 'rozer'); ?></p>
				<ul class="header-search-popular">
					<?php foreach($search_keywords as $search_keyword) : 
					if($search_categories) {
						$search_url = '?product_cat=&post_type=product';  
					}else{
						$search_url = '?post_type=product';  
					}
					$search_url .= '&s='.$search_keyword['keyword'];
					?>
					<li><a href="<?php echo esc_url($search_url); ?>"><?php echo esc_html($search_keyword['keyword']); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>
		<div class="search_result d-none"></div>
	</div>
</div>