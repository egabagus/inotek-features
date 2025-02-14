<?php

function inotek_testimonial_post_type() {
	$labels = array(
		'name'                  => _x( 'Testimonials', 'Post Type General Name', 'text_domain' ),
		'singular_name'         => _x( 'Testimonial', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'             => __( 'Testimonials', 'text_domain' ),
		'name_admin_bar'        => __( 'Testimonial', 'text_domain' ),
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
		'label'               => __( 'Testimonial', 'text_domain' ),
		'description'         => __( 'Testimonial information page.', 'text_domain' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
		'taxonomies'          => array(),
		'hierarchical'        => false,
		'public'              => true,
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
	register_post_type( 'inotek_testimonial', $args );
}

function inotek_testimonial_meta_box() {
	remove_meta_box( 'postimagediv', 'inotek_testimonial', 'side' );
	add_meta_box( 'postimagediv', __( 'Featured Image' ), 'post_thumbnail_meta_box', 'inotek_testimonial', 'normal' );
	add_meta_box( 'inotek_testimonial_box_id', 'Testimonial', 'inotek_testimonial_callback', 'inotek_testimonial');
}

function inotek_testimonial_callback( $post ) {
	wp_nonce_field( 'inotek_testimonial_meta_box', 'inotek_testimonial_nonce' );

	$person_name     = get_post_meta( $post->ID, '_person_name', true );
	$highlight_order = get_post_meta( $post->ID, '_highlight_order', true );
	$highlight       = get_post_meta( $post->ID, '_highlight', true );
	?>
    <div>
        <label for="inotek-testimonial-name">Person Name</label>
        <input type="text" name="inotek_testimonial_name" id="inotek-testimonial-name"
               value="<?php echo $person_name; ?>">
    </div>
    <div>
        <label for="inotek-testimonial-highlight">Highlight</label>
        <input type="checkbox" name="inotek_testimonial_highlight" id="inotek-testimonial-highlight"
               value="<?php echo $highlight; ?>">
    </div>
    <div>
        <label for="">Highlight Order</label>
        <input type="text" name="inotek_testimonial_order" id="inotek-testimonial-order"
               value="<?php echo $highlight_order; ?>">
    </div>
	<?php
}

function inotek_testimonial_save( $post_id ) {
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( ! isset( $_POST['inotek_testimonial_nonce'] ) ) {
		return;
	}

	if ( ! wp_verify_nonce( $_POST['inotek_testimonial_nonce'], 'inotek_testimonial_meta_box' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! isset( $_POST['inotek_testimonial_name'] ) ) {
		return;
	}

	$person_name     = sanitize_text_field( $_POST['inotek_testimonial_name'] );
	$highlight_order = sanitize_text_field( $_POST['inotek_testimonial_order'] );
	$highlight       = isset( $_POST['inotek_testimonial_highlight'] );

	update_post_meta( $post_id, '_person_name', $person_name );
	update_post_meta( $post_id, '_highlight_order', $highlight_order );
	update_post_meta( $post_id, '_highlight', $highlight );
}

add_action( 'save_post_inotek_testimonial', 'inotek_testimonial_save' );