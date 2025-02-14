<?php

function inotek_partner_post_type()
{
	$labels = array(
		'name'                  => _x('Partners', 'Post Type General Name', 'text_domain'),
		'singular_name'         => _x('Partner', 'Post Type Singular Name', 'text_domain'),
		'menu_name'             => __('Partners', 'text_domain'),
		'name_admin_bar'        => __('Partner', 'text_domain'),
		'archives'              => __('Item Archives', 'text_domain'),
		'attributes'            => __('Item Attributes', 'text_domain'),
		'parent_item_colon'     => __('Parent Item:', 'text_domain'),
		'all_items'             => __('All Items', 'text_domain'),
		'add_new_item'          => __('Add New Item', 'text_domain'),
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
		'label'               => __('Partner', 'text_domain'),
		'description'         => __('Partner information page.', 'text_domain'),
		'labels'              => $labels,
		'supports'            => array('title', 'excerpt', 'thumbnail', 'page_properties'),
		'taxonomies'          => array(),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-admin-links',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => false,
		'capability_type'     => 'post',
	);
	register_post_type('inotek_partner', $args);
}

function inotek_partner_meta_box()
{
	remove_meta_box('postimagediv', 'inotek_partner', 'side');
	add_meta_box('postimagediv', __('Logo Image'), 'post_thumbnail_meta_box', 'inotek_partner', 'normal');
	add_meta_box('partner-detail', 'Partner Order', 'inotek_partner_callback', 'inotek_partner', 'advanced', 'high');
}

function inotek_partner_callback($post)
{

	wp_nonce_field('inotek_partner_meta_box', 'inotek_partner_nonce');

	$order_value = get_post_meta($post->ID, '_inotek_partner_order', true);
	$partner_url = get_post_meta($post->ID, '_inotek_partner_url', true);
?>
	<div>
		<label for="display-order">Display Order</label>
		<input type="text" name="inotek_partner_order" id="display-order" placeholder="Display Order"
			value="<?php echo $order_value; ?>">
	</div>
	<div>
		<label for="inotek-partner-link">Partner Link</label>
		<input type="text" name="inotek_partner_url" id="Inotek Partner URL" placeholder="Website link"
			value="<?php echo $partner_url; ?>">
	</div>
<?php
}

function inotek_partner_save($post_id)
{

	if (! current_user_can('edit_post', $post_id)) {
		return;
	}

	if (! isset($_POST['inotek_partner_nonce'])) {
		return;
	}

	if (! wp_verify_nonce($_POST['inotek_partner_nonce'], 'inotek_partner_meta_box')) {
		return;
	}

	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}

	if (! isset($_POST['inotek_partner_order'])) {
		return;
	}

	$order_value = sanitize_text_field($_POST['inotek_partner_order']);
	$partner_url = sanitize_text_field($_POST['inotek_partner_url']);

	update_post_meta($post_id, '_inotek_partner_order', $order_value);
	update_post_meta($post_id, '_inotek_partner_url', $partner_url);
}

add_action('save_post_inotek_partner', 'inotek_partner_save');

function inotek_partner_custom_columns($columns)
{
	$columns['inotek_partner_url']   = __('Url');
	$columns['inotek_partner_order'] = __('Display Order', 'your_text_domain');
	$columns['inotek_partner_image'] = __('Logo');

	return $columns;
}

function inotek_partner_column($column, $post_id)
{
	switch ($column) {
		case 'inotek_partner_url':
			echo get_post_meta($post_id, '_inotek_partner_url', true);
			break;
		case 'inotek_partner_order':
			echo get_post_meta($post_id, '_inotek_partner_order', true);
			break;
		case 'inotek_partner_image':
			the_post_thumbnail(array(100));
			break;
	}
}

add_action('manage_inotek_partner_posts_columns', 'inotek_partner_custom_columns');
add_action('manage_inotek_partner_posts_custom_column', 'inotek_partner_column', 13, 2);
