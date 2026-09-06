<?php

if(!function_exists('rdt_get_option')) {
	function rdt_get_option($key , $default_value = ''){
		// Start demo
		if (is_page('home-page-02')){
			$options = rdt_demo_configs('home-page-02');
		}
		if (is_page('home-page-03')){
			$options = rdt_demo_configs('home-page-03');
		}
		if (is_page('home-page-04')){
			$options = rdt_demo_configs('home-page-04');
		}
		// End demo
		if( !empty($options) && array_key_exists($key , $options)) {
			$kirki_option = $options[$key];
		}else{
			$kirki_option = get_theme_mod($key, $default_value);
		}
		return $kirki_option;
	}
}
function rdt_demo_configs($demo){
	$configs = array();
	switch ($demo) {
		case 'home-page-02':
			$configs = array(
				
			);
			break;
		case 'home-page-03':
			$configs = array(
				
			);
			break;
		case 'home-page-04':
			$configs = array(
				
			);
			break;
		default:
			$configs = array();
			break;
	}
	return $configs;
}