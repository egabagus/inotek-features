<?php

function inotek_achievement_post_type() {
	$labels = array(
		'name'                  => _x( 'Achievements', 'Post Type General Name', 'text_domain' ),
		'singular_name'         => _x( 'Achievement', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'             => __( 'Achievements', 'text_domain' ),
		'name_admin_bar'        => __( 'Achievement', 'text_domain' ),
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
		'label'               => __( 'Achievement', 'text_domain' ),
		'description'         => __( 'Achievement information page.', 'text_domain' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'excerpt', 'revisions' ),
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
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => false,
		'capability_type'     => 'post',
	);
	register_post_type( 'inotek_achievement', $args );
}

function inotek_achievement_meta_box() {
	add_meta_box( 'inotek_achievement_box_id', 'Achievement', 'inotek_achievement_callback', 'inotek_achievement', 'advanced', 'high' );
}

function inotek_achievement_callback( $post ) {
	wp_nonce_field( 'inotek_achievement_meta_box', 'inotek_achievement_nonce' );

	$prefix    = get_post_meta( $post->ID, '_achievement_prefix', true );
	$value     = get_post_meta( $post->ID, '_achievement_value', true );
	$link      = get_post_meta( $post->ID, '_achievement_link', true );
	$delay     = get_post_meta( $post->ID, '_achievement_delay', true );
	$increment = get_post_meta( $post->ID, '_achievement_increment', true );
	$position  = get_post_meta( $post->ID, '_achievement_position', true );

	?>
    <div class="ifa-form-group">
        <label for="inotek-achievement-prefix">Prefix</label>
        <input type="text" id="inotek-achievement-prefix" name="inotek_achievement_prefix"
               value="<?php echo $prefix ?>">
    </div>
    <div class="ifa-form-group">
        <label for="inotek-achievement-value">Value</label>
        <input type="text" id="inotek-achievement-value" name="inotek_achievement_value" value="<?php echo $value ?>">
    </div>
    <div class="ifa-form-group">
        <label for="inotek-achievement-link">Link (Optional)</label>
        <input type="text" id="inotek-achievement-link" name="inotek_achievement_link" value="<?php echo $link ?>">
    </div>
    <div class="ifa-form-group">
        <label for="inotek-achievement-delay">FX delay</label>
        <input type="text" id="inotek-achievement-delay" name="inotek_achievement_delay" value="<?php echo $delay ?>">
    </div>
    <div class="ifa-form-group">
        <label for="inotek-achievement-increment">FX increment</label>
        <input type="text" id="inotek-achievement-increment" name="inotek_achievement_increment"
               value="<?php echo $increment ?>">
    </div>
    <div class="ifa-form-group">
        <label for="inotek-position-increment">Position</label>
        <input type="text" id="inotek-achievement-position" name="inotek_achievement_position"
               value="<?php echo $position ?>">
    </div>
	<?php
}

function inotek_achievement_save( $post_id ) {
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( ! isset( $_POST['inotek_achievement_nonce'] ) ) {
		return;
	}

	if ( ! wp_verify_nonce( $_POST['inotek_achievement_nonce'], 'inotek_achievement_meta_box' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! isset( $_POST['inotek_achievement_prefix'] ) &&
	     ! isset( $_POST['inotek_achievement_value'] ) &&
	     ! isset( $_POST['inotek_achievement_delay'] ) &&
	     ! isset( $_POST['inotek_achievement_increment'] ) ) {
		return;
	}

	$prefix    = sanitize_text_field( $_POST['inotek_achievement_prefix'] );
	$value     = sanitize_text_field( $_POST['inotek_achievement_value'] );
	$link      = sanitize_text_field( $_POST['inotek_achievement_link'] );
	$delay     = sanitize_text_field( $_POST['inotek_achievement_delay'] );
	$increment = sanitize_text_field( $_POST['inotek_achievement_increment'] );
	$position  = trim( sanitize_text_field( $_POST['inotek_achievement_position'] ) );

	if ( empty( $value ) || ! is_numeric( $value ) ) {
		$value = 0;
	}

	if ( empty( $position ) || ! is_numeric( $position ) ) {
		$position = 0;
	}

	update_post_meta( $post_id, '_achievement_prefix', $prefix );
	update_post_meta( $post_id, '_achievement_value', $value );
	update_post_meta( $post_id, '_achievement_link', $link );
	update_post_meta( $post_id, '_achievement_delay', $delay );
	update_post_meta( $post_id, '_achievement_increment', $increment );
	update_post_meta( $post_id, '_achievement_position', $position );
}

function inotek_achievement_filter_column( $columns ) {
	$columns['value']     = __( 'Value' );
	$columns['delay']     = __( 'FX Delay' );
	$columns['increment'] = __( 'FX Increment' );
	$columns['position']  = __( 'Position' );

	return $columns;
}

function inotek_achievement_column( $column, $post_id ) {
	switch ( $column ) {
		case 'value':
			echo get_post_meta( $post_id, '_achievement_prefix', true ) . ' ' . get_post_meta( $post_id, '_achievement_value', true );
			break;
		case 'delay':
			echo get_post_meta( $post_id, '_achievement_delay', true );
			break;
		case 'increment':
			echo get_post_meta( $post_id, '_achievement_increment', true );
			break;
		case 'position':
			echo get_post_meta( $post_id, '_achievement_position', true );
			break;
	}
}


add_action( 'save_post_inotek_achievement', 'inotek_achievement_save' );
add_filter( 'manage_inotek_achievement_posts_columns', 'inotek_achievement_filter_column' );
add_action( 'manage_inotek_achievement_posts_custom_column', 'inotek_achievement_column', 10, 2 );