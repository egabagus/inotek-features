<?php

function inotek_innovation_post_type() {
	$labels = array(
		'name'                  => _x( 'Innovations', 'Post Type General Name', 'text_domain' ),
		'singular_name'         => _x( 'Innovation', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'             => __( 'Innovations', 'text_domain' ),
		'name_admin_bar'        => __( 'Innovation', 'text_domain' ),
		'archives'              => __( 'Item Archives', 'text_domain' ),
		'attributes'            => __( 'Item Attributes', 'text_domain' ),
		'parent_item_colon'     => __( 'Parent Item:', 'text_domain' ),
		'all_items'             => __( 'All Items', 'text_domain' ),
		'add_new_item'          => __( 'Add New Item', 'text_domain' ),
		'add_new'               => __( 'Add New', 'text_domain' ),
		'new_item'              => __( 'New Item', 'text_domain' ),
		'edit_item'             => __( 'Edit Item', 'text_domain' ),
		'update_item'           => __( 'Update Item', 'text_domain' ),
		'view_item'             => __( 'View Item', 'text_domain' ),
		'view_items'            => __( 'View Items', 'text_domain' ),
		'search_items'          => __( 'Search Item', 'text_domain' ),
		'not_found'             => __( 'Not found', 'text_domain' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
		'featured_image'        => __( 'Featured Image', 'text_domain' ),
		'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
		'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
		'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
		'insert_into_item'      => __( 'Insert into item', 'text_domain' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'text_domain' ),
		'items_list'            => __( 'Items list', 'text_domain' ),
		'items_list_navigation' => __( 'Items list navigation', 'text_domain' ),
		'filter_items_list'     => __( 'Filter items list', 'text_domain' ),
	);
	$args   = array(
		'label'               => __( 'Innovation', 'text_domain' ),
		'description'         => __( 'Innovation information page.', 'text_domain' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
		'taxonomies'          => array(),
		'hierarchical'        => false,
		'public'              => true,
		'show_in_rest'        => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-thumbs-up',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => true,
		'publicly_queryable'  => false,
		'capability_type'     => 'post',
	);
	register_post_type( 'inotek_innovation', $args );
}

function inotek_taxonomy_portfolio() {
	$labels = [
		'name'              => _x( 'Portfolio', 'taxonomy general name' ),
		'singular_name'     => _x( 'Portfolio', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Portfolio' ),
		'all_items'         => __( 'All Portfolio' ),
		'parent_item'       => __( 'Parent Portfolio' ),
		'parent_item_colon' => __( 'Parent Portfolio:' ),
		'edit_item'         => __( 'Edit Portfolio' ),
		'update_item'       => __( 'Update Portfolio' ),
		'add_new_item'      => __( 'Add New Portfolio' ),
		'new_item_name'     => __( 'New Portfolio Name' ),
		'menu_name'         => __( 'Portfolio' ),
	];
	$args   = [
		'hierarchical'      => true, // make it hierarchical (like categories)
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => [ 'slug' => 'portfolio' ],
	];
	register_taxonomy( 'portfolio', [ 'inotek_innovation' ], $args );
}

function inotek_innovation_meta_box() {
	add_meta_box( 'inotek_innovation_box_id', 'Innovation', 'inotek_innovation_callback', 'inotek_innovation' );
}

function inotek_innovation_callback( $post ) {

}

add_action( 'init', 'inotek_taxonomy_portfolio' );
