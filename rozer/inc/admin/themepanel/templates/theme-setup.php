<?php
	$array_imports = array(
		'home1' => array(
			'page_name' => 'Home 01',
			'demo_url' => 'http://demo2.roadthemes.com/rozer/',
		),
		'home2' => array(
			'page_name' => 'Home 02', 
			'demo_url' => 'http://demo2.roadthemes.com/rozer/home-page-02/',
		),
		'home3' => array(
			'page_name' => 'Home 03',
			'demo_url' => 'http://demo2.roadthemes.com/rozer/home-page-03/', 
		),
		'home4' => array(

			'page_name' => 'Home 04', 
			'demo_url' => 'http://demo2.roadthemes.com/rozer/home-page-04/',
		),
		
	);
?>
<section class="rdt-themesetup-panel">
	<div class="rdt-themesetup-wrapper">
		<div class="rdt-themesetup-header">
			<img src="<?php echo ROZER_THEME_URI; ?>/inc/admin/themepanel/images/rozer.png" alt="rozer" />
			<p>Welcome to import demo page.	 Here you can select the demo which you want to use </p>
			<p>Click import to start import demo</p>
		</div>
		<div class="rdt-themesetup-content rdt-demo-list">
			<?php
				foreach($array_imports as $key => $val) : ?>
					<div class="rdt-item">
						<div class="rdt-item__image">
							<img src="<?php echo ROZER_THEME_URI; ?>/inc/admin/themepanel/images/<?php echo esc_attr($key); ?>.png" alt="<?php echo esc_attr($val['page_name']); ?>" />
							<h3><?php echo esc_attr($val['page_name']); ?></h3>
						</div>
						<div class="rdt-item__buttons">
							<a href="<?php echo esc_url($val['demo_url']); ?>" class="rdt-btn" target="_blank">Preview</a>
							<button class="button-primary button button-large rdt-start-import" data-demo="<?php echo esc_attr($key); ?>" data-demo-name="<?php echo esc_attr($val['page_name']); ?>">Import</button>
						</div>
					</div>
				<?php endforeach;
			?>
		</div>
	</div>
	<div class="rdt-popup-import"></div>
	<div class="rdt-popup-overlay"></div>
</section>