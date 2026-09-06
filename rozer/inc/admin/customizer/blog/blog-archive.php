<?php
Kirki::add_section( 'blog_archive', array(
    'priority'    => 1,
    'title'       => esc_html__( 'Archive page', 'rozer' ),
    'panel'       => 'blog',
) );
Kirki::add_field( 'option', [
	'type'        => 'custom',
	'settings'    => 'blog_archive_layout_part',
	'section'     => 'blog_archive',
	'default'         => '<div class="customize-title-divider">' . __( 'Layout', 'rozer' ) . '</div>',
] );
Kirki::add_field( 'option', [
	'type'        => 'radio-image',
	'settings'    => 'blog_archive_layout',
	'label'       => esc_html__( 'Layout', 'rozer' ),
	'section'     => 'blog_archive',
	'default'     => 'right-sidebar',
	'choices'     => [
		'left-sidebar'   => get_template_directory_uri() . '/assets/images/customizer/layout-left-sidebar.png',
		'no-sidebar'  => get_template_directory_uri() . '/assets/images/customizer/layout-no-sidebar.png',
		'right-sidebar' => get_template_directory_uri() . '/assets/images/customizer/layout-right-sidebar.png',
	],
] );
Kirki::add_field( 'option', [
	'type'        => 'select',
	'settings'    => 'blog_archive_design',
	'label'       => esc_html__( 'Design', 'rozer' ),
	'section'     => 'blog_archive',
	'default'     => 'design-1',
	'multiple'    => 1,
	'choices'     => [
		'design-1' => esc_html__( 'Design 1', 'rozer' ),
		'design-2' => esc_html__( 'Design 2', 'rozer' ),
		'design-3' => esc_html__( 'Design 3', 'rozer' ),
	],
] );
Kirki::add_field( 'option', [
	'type'        => 'radio-buttonset',
	'settings'    => 'blog_archive_items',
	'label'       => esc_html__( 'Blog items columns', 'rozer' ),
	'section'     => 'blog_archive',
	'default'     => '1',
	'choices'     => [
		'1'       => 1,
		'2'       => 2,
		'3'       => 3,
		'4'       => 4,
	],
] );
Kirki::add_field( 'option', [
	'type'        => 'radio-buttonset',
	'settings'    => 'posts_navigation',
	'label'       => esc_html__( 'Navigation type', 'rozer' ),
	'section'     => 'blog_archive',
	'default'     => 'default',
	'choices'     => [
		'default'   => esc_html__( 'Default', 'rozer' ),
		'loadmore' => esc_html__( 'Load more', 'rozer' ),
		'infinite'  => esc_html__( 'Infinite scroll', 'rozer' ),
	],
] );
Kirki::add_field( 'option', [
	'type'        => 'custom',
	'settings'    => 'blog_archive_post_part',
	'section'     => 'blog_archive',
	'default'         => '<div class="customize-title-divider">' . __( 'Blog post options', 'rozer' ) . '</div>',
] );
Kirki::add_field( 'option', [
	'type'        => 'radio-buttonset',
	'settings'    => 'blog_archive_excerpt',
	'label'       => esc_html__( 'Posts excerpt', 'rozer' ),
	'section'     => 'blog_archive',
	'default'     => 'excerpt',
	'choices'     => [
		'full'   => esc_html__( 'Full content', 'rozer' ),
		'excerpt' => esc_html__( 'Excerpt', 'rozer' ),
	],
] );
Kirki::add_field( 'option', [
	'type'     => 'text',
	'settings' => 'blog_archive_excerpt_length',
	'label'    => esc_html__( 'Excerpt length ( By word )', 'rozer' ),
	'default'     => '20',
	'section'  => 'blog_archive',
] );
Kirki::add_field( 'option', [
	'type'     => 'text',
	'settings' => 'blog_archive_excerpt_suffix',
	'label'    => esc_html__( 'Excerpt More', 'rozer' ),
	'description'    => esc_html__( 'Default [...]', 'rozer' ),
	'section'  => 'blog_archive',
	'default'  => '[...]',
] );
