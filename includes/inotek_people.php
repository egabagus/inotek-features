<?php

function inotek_people_post_type() {
	$labels = array(
		'name'                  => _x( 'People', 'Post Type General Name', 'text_domain' ),
		'singular_name'         => _x( 'People', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'             => __( 'People', 'text_domain' ),
		'name_admin_bar'        => __( 'People', 'text_domain' ),
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
		'label'               => __( 'People', 'text_domain' ),
		'description'         => __( 'People information page.', 'text_domain' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'excerpt', 'thumbnail' ),
		'taxonomies'          => array( 'team' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-groups',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => false,
		'capability_type'     => 'post',
	);
	register_post_type( 'inotek_people', $args );
}

function inotek_people_meta_box() {
	remove_meta_box( 'postimagediv', 'inotek_people', 'side' );
	add_meta_box( 'postimagediv', __( 'Person Photo' ), 'post_thumbnail_meta_box', 'inotek_people', 'normal' );
	add_meta_box( 'inotek_people_box_id', 'People', 'inotek_people_callback', 'inotek_people' );
}

function inotek_people_callback( $post ) {

	wp_nonce_field( 'inotek_people_meta_box', 'inotek_people_nonce' );

	$organisation_name = get_post_meta( $post->ID, '_organisation_name', true );
	$job_description   = get_post_meta( $post->ID, '_job_description', true );
	$description       = get_post_meta( $post->ID, '_description', true );
	$display_order     = get_post_meta( $post->ID, '_display_order', true );

	?>
    <div>
        <label for="inotek-people-org">Line 2</label>
        <input type="text" name="inotek_people_org" id="inotek-people-org" value="<?php echo $organisation_name; ?>">
    </div>
    <div>
        <label for="inotek-people-job">Line 3</label>
        <input type="text" name="inotek_people_job" id="inotek-people-job" value="<?php echo $job_description; ?>">
    </div>
    <div>
        <label for="inotek-people-desc">Description - optional</label>
        <input type="text" name="inotek_people_desc" id="inotek-people-desc" value="<?php echo $description; ?>">
    </div>
    <div>
        <label for="inotek-people-order">Display Order</label>
        <input type="text" name="inotek_people_order" id="inotek-people-order" value="<?php echo $display_order; ?>">
    </div>
	<?php
}

function inotek_people_save( $post_id ) {
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( ! isset( $_POST['inotek_people_nonce'] ) ) {
		return;
	}

	if ( ! wp_verify_nonce( $_POST['inotek_people_nonce'], 'inotek_people_meta_box' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! isset( $_POST['inotek_people_job'] ) && ! isset( $_POST['inotek_people_org'] ) ) {
		return;
	}

	$organisation_name = sanitize_text_field( $_POST['inotek_people_org'] );
	$job_description   = sanitize_text_field( $_POST['inotek_people_job'] );
	$description       = sanitize_text_field( $_POST['inotek_people_desc'] );
	$display_order     = trim( sanitize_text_field( $_POST['inotek_people_order'] ) );

	if ( empty( $display_order ) || ! is_numeric( $display_order ) ) {
		$display_order = 0;
	}

	update_post_meta( $post_id, '_organisation_name', $organisation_name );
	update_post_meta( $post_id, '_job_description', $job_description );
	update_post_meta( $post_id, '_description', $description );
	update_post_meta( $post_id, '_display_order', $display_order );
}

function inotek_register_taxonomy_team() {
	$labels = [
		'name'              => _x( 'Team', 'taxonomy general name' ),
		'singular_name'     => _x( 'Team', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Team' ),
		'all_items'         => __( 'All Team' ),
		'parent_item'       => __( 'Parent Team' ),
		'parent_item_colon' => __( 'Parent Team:' ),
		'edit_item'         => __( 'Edit Team' ),
		'update_item'       => __( 'Update Team' ),
		'add_new_item'      => __( 'Add New Team' ),
		'new_item_name'     => __( 'New Team Name' ),
		'menu_name'         => __( 'Team' ),
	];
	$args   = [
		'hierarchical'      => true, // make it hierarchical (like categories)
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => [ 'slug' => 'team' ],
	];
	register_taxonomy( 'team', [ 'inotek_people' ], $args );
}

function inotek_people_filter_column( $columns ) {
	$columns['display_order'] = __( 'Order' );
	$columns['image']         = __( 'Image' );

	return $columns;
}

function inotek_people_column( $column, $post_id ) {
	switch ( $column ) {
		case 'image':
			the_post_thumbnail( array( 100 ) );
			break;
		case 'display_order':
			echo get_post_meta( $post_id, '_display_order', true );
			break;
	}
}

add_filter( 'manage_inotek_people_posts_columns', 'inotek_people_filter_column' );
add_action( 'manage_inotek_people_posts_custom_column', 'inotek_people_column', 10, 2 );
add_action( 'save_post_inotek_people', 'inotek_people_save' );
add_action( 'init', 'inotek_register_taxonomy_team' );
