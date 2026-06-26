<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
* Register Custom Post Types
*/
add_action( 'init', 'sms_create_post_types' );
function sms_create_post_types() {

	register_post_type('projects',
		array(
			'labels' => array(
				'name'                  => __( 'Projects', 'sms' ),
				'singular_name'         => __( 'Project', 'sms' ),
				'menu_name'             => __( 'Projects', 'sms' ),
				'name_admin_bar'        => __( 'Project', 'sms' ),
				'add_new'               => __( 'Add New', 'sms' ),
				'add_new_item'          => __( 'Add New Project', 'sms' ),
				'new_item'              => __( 'New Project', 'sms' ),
				'edit_item'             => __( 'Edit Project', 'sms' ),
				'view_item'             => __( 'View Project', 'sms' ),
				'all_items'             => __( 'All Projects', 'sms' ),
				'search_items'          => __( 'Search Projects', 'sms' ),
				'parent_item_colon'     => __( 'Parent Projects:', 'sms' ),
				'not_found'             => __( 'No Projects found.', 'sms' ),
				'not_found_in_trash'    => __( 'No Projects found in Trash.', 'sms' ),
				'featured_image'        => __( 'Project Cover Image', 'sms' ),
				'set_featured_image'    => __( 'Set cover image', 'sms' ),
				'remove_featured_image' => __( 'Remove cover image', 'sms' ),
				'use_featured_image'    => __( 'Use as cover image', 'sms' ),
				'archives'              => __( 'Project archives', 'sms' ),
				'insert_into_item'      => __( 'Insert into Project', 'sms' ),
				'uploaded_to_this_item' => __( 'Uploaded to this Project', 'sms' ),
				'filter_items_list'     => __( 'Filter Projects list', 'sms' ),
				'items_list_navigation' => __( 'Projects list navigation', 'sms' ),
				'items_list'            => __( 'Projects list', 'sms' ),
			),
			'public' => true,
			'rewrite' => array(
				'slug' => 'project',
				'with_front' => false
			),
			// icon
			'menu_icon' => 'dashicons-list-view',
			'show_ui' => true,
			'show_in_rest' => true,
			'supports' => array('title', 'editor', 'excerpt', 'thumbnail'),
		)
	);

	register_post_type( 'teams',
		array(
			'labels' => array(
				'name'                  => __( 'Teams', 'sms' ),
				'singular_name'         => __( 'Team', 'sms' ),
				'menu_name'             => __( 'Teams', 'sms' ),
				'name_admin_bar'        => __( 'Team', 'sms' ),
				'add_new'               => __( 'Add New', 'sms' ),
				'add_new_item'          => __( 'Add New Team', 'sms' ),
				'new_item'              => __( 'New Team', 'sms' ),
				'edit_item'             => __( 'Edit Team', 'sms' ),
				'view_item'             => __( 'View Team', 'sms' ),
				'all_items'             => __( 'All Teams', 'sms' ),
				'search_items'          => __( 'Search Teams', 'sms' ),
				'parent_item_colon'     => __( 'Parent Teams:', 'sms' ),
				'not_found'             => __( 'No Teams found.', 'sms' ),
				'not_found_in_trash'    => __( 'No Teams found in Trash.', 'sms' ),
				'featured_image'        => __( 'Team Cover Image', 'sms' ),
				'set_featured_image'    => __( 'Set cover image', 'sms' ),
				'remove_featured_image' => __( 'Remove cover image', 'sms' ),
				'use_featured_image'    => __( 'Use as cover image', 'sms' ),
				'archives'              => __( 'Team archives', 'sms' ),
				'insert_into_item'      => __( 'Insert into Team', 'sms' ),
				'uploaded_to_this_item' => __( 'Uploaded to this Team', 'sms' ),
				'filter_items_list'     => __( 'Filter Teams list', 'sms' ),
				'items_list_navigation' => __( 'Teams list navigation', 'sms' ),
				'items_list'            => __( 'Teams list', 'sms' ),
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array(
				'slug'       => 'team',
				'with_front' => false
			),
			'menu_icon'     => 'dashicons-admin-users',
			'show_ui'       => true,
			'show_in_rest'  => true,
			'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
		)
	);
}