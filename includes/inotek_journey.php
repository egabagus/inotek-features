<?php

function inotek_journey_post_type()
{
	$labels = array(
		'name'                  => _x('Journeys', 'Post Type General Name', 'text_domain'),
		'singular_name'         => _x('Journey', 'Post Type Singular Name', 'text_domain'),
		'menu_name'             => __('Our Journeys', 'text_domain'),
		'name_admin_bar'        => __('Our Journey', 'text_domain'),
		'archives'              => __('Item Archives', 'text_domain'),
		'attributes'            => __('Item Attributes', 'text_domain'),
		'parent_item_colon'     => __('Parent Item:', 'text_domain'),
		'all_items'             => __('All Items', 'text_domain'),
		'add_new_item'          => __('Add New Journey', 'text_domain'),
		'add_new'               => __('Add New', 'text_domain'),
		'new_item'              => __('New Item', 'text_domain'),
		'edit_item'             => __('Edit Item', 'text_domain'),
		'update_item'           => __('Update Item', 'text_domain'),
		'view_item'             => __('View Item', 'text_domain'),
		'view_items'            => __('View Items', 'text_domain'),
		'search_items'          => __('Search Item', 'text_domain'),
		'not_found'             => __('Not found', 'text_domain'),
		'not_found_in_trash'    => __('Not found in Trash', 'text_domain'),
		'featured_image'        => __('Featured Image', 'text_domain'),
		'set_featured_image'    => __('Set featured image', 'text_domain'),
		'remove_featured_image' => __('Remove featured image', 'text_domain'),
		'use_featured_image'    => __('Use as featured image', 'text_domain'),
		'insert_into_item'      => __('Insert into item', 'text_domain'),
		'uploaded_to_this_item' => __('Uploaded to this item', 'text_domain'),
		'items_list'            => __('Items list', 'text_domain'),
		'items_list_navigation' => __('Items list navigation', 'text_domain'),
		'filter_items_list'     => __('Filter items list', 'text_domain'),
	);
	$args   = array(
		'label'               => __('Our Journey', 'text_domain'),
		'description'         => __('Journey information page.', 'text_domain'),
		'labels'              => $labels,
		'supports'            => array('title', 'editor', 'revisions'),
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
	register_post_type('inotek_journey', $args);
}

function inotek_journey_meta_box()
{
	remove_meta_box('postimagediv', 'inotek_journey', 'side');
	add_meta_box('inotek_journey_box_id', 'Display Order', 'inotek_journey_callback', 'inotek_journey');
}

function inotek_journey_callback($post)
{
	wp_nonce_field('inotek_journey_meta_box', 'inotek_journey_nonce');

	$order     = get_post_meta($post->ID, '_order', true);
?>
	<div>
		<label for="inotek-journey-order">Display Order</label>
		<input type="number" name="inotek_journey_order" id="inotek-journey-order"
			value="<?php echo $order; ?>">
	</div>
<?php
}

function inotek_journey_save($post_id)
{
	if (! current_user_can('edit_post', $post_id)) {
		return;
	}

	if (! isset($_POST['inotek_journey_nonce'])) {
		return;
	}

	if (! wp_verify_nonce($_POST['inotek_journey_nonce'], 'inotek_journey_meta_box')) {
		return;
	}

	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}

	if (! isset($_POST['inotek_journey_name'])) {
		return;
	}

	$order     = sanitize_text_field($_POST['inotek_journey_order']);

	update_post_meta($post_id, '_order', $order);
}

add_action('save_post_inotek_journey', 'inotek_journey_save');
