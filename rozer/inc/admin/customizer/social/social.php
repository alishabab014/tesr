<?php

$social_list  = array (
	''   => '',
	'facebook'   => esc_html__( 'Facebook', 'rozer' ),
	'twitter'    => esc_html__( 'Twitter', 'rozer' ),
	'google'     => esc_html__( 'Google+', 'rozer' ),
	'instagram'  => esc_html__( 'Instagram', 'rozer' ),
	'pinterest'  => esc_html__( 'Pinterest', 'rozer' ),
	'whatsapp'   => esc_html__( 'Whatsapp', 'rozer' ),
	'rss'        => esc_html__( 'RSS', 'rozer' ),
	'tumblr'     => esc_html__( 'Tumblr', 'rozer' ),
	'youtube'    => esc_html__( 'Youtube', 'rozer' ),
	'vimeo'      => esc_html__( 'Vimeo', 'rozer' ),
	'behance'    => esc_html__( 'Behance', 'rozer' ),
	'dribbble'   => esc_html__( 'Dribbble', 'rozer' ),
	'flickr'     => esc_html__( 'Flickr', 'rozer' ),
	'github'     => esc_html__( 'GitHub', 'rozer' ),
	'skype'      => esc_html__( 'Skype', 'rozer' ),
	'snapchat'   => esc_html__( 'Snapchat', 'rozer' ),
	'wechat'     => esc_html__( 'WeChat', 'rozer' ),
	'weibo'      => esc_html__( 'Weibo', 'rozer' ),
	'foursquare' => esc_html__( 'Foursquare', 'rozer' ),
	'soundcloud' => esc_html__( 'Soundcloud', 'rozer' ),
	'vk'         => esc_html__( 'VK', 'rozer' ),
);
Kirki::add_section( 'social', array(
    'priority'    => 55,
    'title'       => esc_html__( 'Social', 'rozer' ),
) );
Kirki::add_field( 'option', [
	'type'        => 'custom',
	'settings'    => 'social_sharing_part',
	'section'     => 'social',
	'default'         => '<div class="customize-title-divider">' . __( 'Social sharing', 'rozer' ) . '</div>',
] );
Kirki::add_field( 'option', [
	'type'        => 'sortable',
	'settings'    => 'social_sharing',
	'label'       => esc_html__( 'Social sharing in Single page', 'rozer' ),
	'section'     => 'social',
	'default'     => [
		'facebook',
		'pinterest',
		'twitter'
	],
	'choices'     => [
		'facebook' => esc_html__( 'Facebook', 'rozer' ),
		'pinterest' => esc_html__( 'Pinterest', 'rozer' ),
		'twitter' => esc_html__( 'Twitter', 'rozer' ),
		'whatsapp' => esc_html__( 'Whatsapp', 'rozer' ),
		'email' => esc_html__( 'Email', 'rozer' ),
		'vk' => esc_html__( 'VK', 'rozer' ),
		'linkedin' => esc_html__( 'LinkedIn', 'rozer' ),
		'telegram' => esc_html__( 'Telegram', 'rozer' ),
	],
] );
Kirki::add_field( 'option', [
	'type'        => 'custom',
	'settings'    => 'social_list_part',
	'section'     => 'social',
	'default'         => '<div class="customize-title-divider">' . __( 'Social List', 'rozer' ) . '</div>',
] );
Kirki::add_field( 'option', [
	'type'        => 'repeater',
	'label'       => esc_html__( 'Social list', 'rozer' ),
	'section'     => 'social',
	'priority'    => 10,
	'row_label' => [
		'type'  => 'field',
		'value' => esc_attr__( 'Element', 'rozer' ),
		'field' => 'name',
	],
	'button_label' => esc_html__('Add new', 'rozer' ),
	'settings'     => 'social_list',
	'fields' => [
		'name' => [
			'type'        => 'select',
			'label'       => esc_html__( 'Social', 'rozer' ),
			'description' => esc_html__( 'Select a social network', 'rozer' ),
			'default'     => '',
			'choices'     => $social_list,
		],
		'url'  => [
			'type'        => 'text',
			'label'       => esc_html__( 'Social URL', 'rozer' ),
			'default'     => '',
		],
	]
] );