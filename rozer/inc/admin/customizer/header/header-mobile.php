<?php

global $wpdb;
$sql = "SELECT post_title, post_name
  FROM $wpdb->posts a
  WHERE a.post_type = 'page'
  AND a.post_status = 'publish'";
$pages = $wpdb->get_results($sql , 'ARRAY_A');
$page_list = array();
$page_list[''] = esc_html__( 'None', 'rozer' );
foreach($pages as $el){
	$page_name = $el['post_name'];
	if($page_name)
	$page_list[$page_name] = $el['post_title'];
}
$default_list = array(
	'page_link'     => esc_html__( 'Page', 'rozer' ),
	'wishlist' => esc_html__( 'Wishlist', 'rozer' ),
	'custom_link'   => esc_html__( 'Custom link', 'rozer' ),
);
$icon_list=array(
	'rt-home-outline' => 'rt-home-outline',
	'rt-bag-outline' => 'rt-bag-outline',
	'rt-cart-outline' => 'cart-outline',
	'rt-bag' => 'bag',
	'rt-handbag' => 'handbag',
	'rt-shopping-cart' => 'shopping-cart',
	'rt-bag2' => 'bag2',
	'rt-shopping-cart-solid' => 'shopping-cart-solid',
	'rt-basket-outline' => 'basket-outline',
	'rt-shopping-basket-solid' => 'shopping-basket-solid',
	'rt-heart' => 'heart',
	'rt-heart2' => 'heart2',
	'rt-heart-solid' => 'heart-solid',
	'rt-heart-outline' => 'heart-outline',
	'rt-shuffle' => 'shuffle',
	'rt-refresh' => 'refresh',
	'rt-ios-shuffle-strong' => 'ios-shuffle-strong',
	'rt-repeat-outline' => 'repeat-outline',
	'rt-sync-alt-solid' => 'sync-alt-solid',
	'rt-star2' => 'star2',
	'rt-star-solid' => 'star-solid',
	'rt-call-outline' => 'call-outline',
	'rt-call-sharp' => 'call-sharp',
	'rt-headphones' => 'headphones',
	'rt-headphones-mic' => 'headphones-mic',
	'rt-headphones2' => 'headphones2',
	'rt-phone-volume-solid' => 'phone-volume-solid',
	'rt-phone-call' => 'phone-call',
	'rt-headset-outline' => 'headset-outline',
	'rt-shipping-fast-solid' => 'shipping-fast-solid',
	'rt-truck-solid' => 'truck-solid',
	'rt-rocket' => 'rocket',
	'rt-rocket-outline' => 'rocket-outline',
	'rt-location-pin' => 'location-pin',
	'rt-map-marked-alt-solid' => 'map-marked-alt-solid',
	'rt-location-outline' => 'location-outline',
	'rt-mail-outline' => 'mail-outline',
	'rt-mail-open-outline' => 'mail-open-outline',
	'rt-globe-solid' => 'globe-solid',
	'rt-expand-outline' => 'expand-outline',
	'rt-checkmark' => 'checkmark',
	'rt-credit-card-solid' => 'credit-card-solid',
	'rt-cash-outline' => 'cash-outline',
	'rt-gift-outline' => 'gift-outline',
	'rt-gift-solid' => 'gift-solid',
	'rt-gifts-solid' => 'gifts-solid',
	'rt-ribbon-outline' => 'ribbon-outline',
	'rt-call-center-24-7' => 'call-center-24-7',
	'rt-headphone-24-7' => 'headphone-24-7',
	'rt-credit-card-secure1' => 'credit-card-secure1',
);
Kirki::add_section( 'header_mobile', array(
    'priority'    => 1,
    'title'       => esc_html__( 'Header Mobile', 'rozer' ),
    'panel'       => 'header',
) );
Kirki::add_field( 'option', [
	'type'        => 'image',
	'settings'    => 'custom_logo_mobile',
	'label'       => esc_html__( 'Mobile logo', 'kirki' ),
	'description' => esc_html__( 'Different logo in mobile.', 'kirki' ),
	'section'     => 'header_mobile',
	'default'     => '',
	'choices'     => [
		'save_as' => 'id',
	],
] );
Kirki::add_field( 'option', [
	'type'        => 'radio-image',
	'settings'    => 'header_mobile_layout',
	'label'       => esc_html__( 'Select header mobile layout', 'rozer' ),
	'section'     => 'header_mobile',
	'default'     => '1',
	'choices'     => [
		'1'   => get_template_directory_uri() . '/assets/images/customizer/header-mobile-1.jpg',
		'2' => get_template_directory_uri() . '/assets/images/customizer/header-mobile-2.jpg',
	],
] );
Kirki::add_field( 'option', [
	'type'        => 'custom',
	'settings'    => 'quick_links_custom',
	'section'     => 'header_mobile',
	'default'         => '<div class="customize-title-divider">' . __( 'Mobile quick links', 'rozer' ) . '</div>',
] );
Kirki::add_field( 'option', [
	'type'        => 'toggle',
	'settings'    => 'quick_links_active',
	'label'       => esc_html__( 'Active mobile quick links', 'rozer' ),
	'section'     => 'header_mobile',
	'default'     => '0',
] );
Kirki::add_field( 'option', [
	'type'        => 'repeater',
	'label'       => esc_html__( 'Social list', 'rozer' ),
	'section'     => 'header_mobile',
	'row_label' => [
		'type'  => 'field',
		'value' => esc_attr__( 'Element', 'rozer' ),
		'field' => 'type_link',
	],
	'button_label' => esc_html__('Add new', 'rozer' ),
	'settings'     => 'quick_links',
	'fields' => [
		'type_link' => [
			'type'        => 'select',
			'label'       => esc_html__( 'Type', 'rozer' ),
			'description' => esc_html__( 'Select type of link.', 'rozer' ),
			'default'     => 'page_link',
			'choices'     => $default_list,
		],
		'page_link' => [
			'type'        => 'select',
			'label'       => esc_html__( 'Page link', 'rozer' ),
			'default'     => '',
			'choices'     => $page_list,
			'active_callback' => array(
                array(
                    'setting'  => 'type_link',
                    'operator' => '==',
                    'value'    => 'page_link',
                ),
            )
		],
		'custom_title'  => [
			'type'        => 'text',
			'label'       => esc_html__( 'Custom title', 'rozer' ),
			'default'     => '',
			'active_callback' => array(
                array(
                    'setting'  => 'type_link',
                    'operator' => '==',
                    'value'    => 'custom_link',
                ),
            )
		],
		'custom_url'  => [
			'type'        => 'text',
			'label'       => esc_html__( 'Custom URL', 'rozer' ),
			'default'     => '',
			'active_callback' => array(
                array(
                    'setting'  => 'type_link',
                    'operator' => '==',
                    'value'    => 'custom_link',
                ),
            )
		],
		'icon' => [
			'type'        => 'select',
			'label'       => esc_html__( 'Icon font', 'rozer' ),
			'default'     => 'rt-home-outline',
			'choices'     => $icon_list,
			'active_callback' => array(
                array(
                    'setting'  => 'type_link',
                    'operator' => '!=',
                    'value'    => 'cart',
                ),
                 array(
                    'setting'  => 'type_link',
                    'operator' => '!=',
                    'value'    => 'wishlist',
                ),
            )
		],
		'image' => [
			'type'        => 'image',
			'label'       => esc_html__( 'Icon image', 'rozer' ),
			'default'     => '',
			'active_callback' => array(
                array(
                    'setting'  => 'type_link',
                    'operator' => '!=',
                    'value'    => 'cart',
                ),
                 array(
                    'setting'  => 'type_link',
                    'operator' => '!=',
                    'value'    => 'wishlist',
                ),
            )
		]
	]
] );
