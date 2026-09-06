<?php

add_action( 'cmb2_admin_init', 'rozer_register_page_metabox' );
function rozer_register_page_metabox() {
	$cmb_demo = new_cmb2_box( array(
		'id'            => 'rozer_page_metabox',
		'title'         => esc_html__( 'Page Options' , 'rozer' ),
		'object_types'  => array( 'page' ), // Post type
	) );
	$cmb_demo->add_field( array(
	    'name'             => esc_html__( 'Select header for this page' , 'rozer' ),
	    'desc'             => esc_html__( 'Default: get header from themeoption' , 'rozer' ),
	    'id'               => 'page_custom_header',
	    'type'             => 'select',
	    'default'          => 'default',
	    'options'          => array(
	        'default'     => __( 'Default', 'rozer' ),
	        '1'           => __( 'Header 1', 'rozer' ),
	        '2'           => __( 'Header 2', 'rozer' ),
	        '3'           => __( 'Header 3', 'rozer' ),
	        '4'           => __( 'Header 4', 'rozer' ),
	    ),
	) );
	$cmb_demo->add_field( array(
	    'name' => esc_html__( 'Disable page title' , 'rozer' ),
	    'desc' => esc_html__( 'Disable page title for this page' , 'rozer' ),
	    'id'   => 'page_custom_title',
	    'type' => 'checkbox',
	) );
	$cmb_demo->add_field( array(
	    'name' => esc_html__( 'Disable breadcrumb' , 'rozer' ),
	    'desc' => esc_html__( 'Disable breadcrumb for this page' , 'rozer' ),
	    'id'   => 'page_custom_breadcrumb',
	    'type' => 'checkbox',
	) );
	$cmb_demo->add_field( array(
	    'name'    => esc_html__( 'Image for page title' , 'rozer' ),
	    'desc'    => esc_html__( 'Upload an image.' , 'rozer' ),
	    'id'      => 'page_custom_title_image',
	    'type'    => 'file',
	    // Optional:
	    'options' => array(
	        'url' => false, // Hide the text input for the url
	    ),
	    'text'    => array(
	        'add_upload_file_text' => 'Add File' // Change upload button text. Default: "Add or Upload File"
	    ),
	    // query_args are passed to wp.media's library query.
	    'query_args' => array(
	        'type' => array(
	            'image/gif',
	            'image/jpeg',
	            'image/png',
	        ),
	    ),
	    'preview_size' => 'large', // Image size to use when previewing in the admin.
	) );
}
add_action( 'cmb2_admin_init', 'rozer_register_post_metabox' );
function rozer_register_post_metabox() {
	$cmb_demo = new_cmb2_box( array(
		'id'            => 'rt_post_metabox',
		'title'         => esc_html__( 'Post Options', 'rozer' ),
		'object_types'  => array( 'post' ), // Post type
	) );
	$cmb_demo->add_field( array(
	    'name'             => esc_html__( 'Select header for this page', 'rozer' ),
	    'desc'             => esc_html__( 'Default: get header from themeoption', 'rozer' ),
	    'id'               => 'page_custom_header',
	    'type'             => 'select',
	    'default'          => 'default',
	    'options'          => array(
	        'default'     => __( 'Default', 'rozer' ),
	        '1'           => __( 'Header 1', 'rozer' ),
	        '2'           => __( 'Header 2', 'rozer' ),
	        '3'           => __( 'Header 3', 'rozer' ),
	    ),
	) );
	$cmb_demo->add_field( array(
	    'name' => esc_html__( 'Hide the post title', 'rozer' ),
	    'desc' => esc_html__( 'The post title will be hidden in single post page.', 'rozer' ),
	    'id'   => 'post_hide_title',
	    'type' => 'checkbox',
	) );
	$cmb_demo->add_field( array(
	    'name' => esc_html__( 'Hide the featured image', 'rozer' ),
	    'desc' => esc_html__( 'The post featured image will be hidden in single post page.', 'rozer' ),
	    'id'   => 'post_hide_featured_image',
	    'type' => 'checkbox',
	) );
	$cmb_demo->add_field( array(
	    'name'    => esc_html__( 'Image for page title', 'rozer' ),
	    'desc'    => esc_html__( 'Upload an image.', 'rozer' ),
	    'id'      => 'page_custom_title_image',
	    'type'    => 'file',
	    // Optional:
	    'options' => array(
	        'url' => false, // Hide the text input for the url
	    ),
	    'text'    => array(
	        'add_upload_file_text' => esc_html__( 'Add File', 'rozer' ) // Change upload button text. Default: "Add or Upload File"
	    ),
	    // query_args are passed to wp.media's library query.
	    'query_args' => array(
	        'type' => array(
	            'image/gif',
	            'image/jpeg',
	            'image/png',
	        ),
	    ),
	    'preview_size' => 'large', // Image size to use when previewing in the admin.
	) );
}