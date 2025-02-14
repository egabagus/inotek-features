<?php

function inotek_sme_post_type()
{
	$labels = array(
		'name'                  => _x('SME', 'Post Type General SME', 'text_domain'),
		'singular_name'         => _x('SME', 'Post Type Singular SME', 'text_domain'),
		'menu_name'             => __('SME', 'text_domain'),
		'name_admin_bar'        => __('SME', 'text_domain'),
		'archives'              => __('Category Archives', 'text_domain'),
		'attributes'            => __('Category Attributes', 'text_domain'),
		'parent_category_colon'     => __('Parent Category:', 'text_domain'),
		'all_categories'             => __('All Categories', 'text_domain'),
		'add_new_category'          => __('Add New Category', 'text_domain'),
		'add_new'               => __('Add New', 'text_domain'),
		'new_category'              => __('New Category', 'text_domain'),
		'edit_category'             => __('Edit Category', 'text_domain'),
		'update_category'           => __('Update Category', 'text_domain'),
		'view_category'             => __('View Category', 'text_domain'),
		'view_categories'            => __('View Categories', 'text_domain'),
		'search_categories'          => __('Search Category', 'text_domain'),
		'not_found'             => __('Not found', 'text_domain'),
		'not_found_in_trash'    => __('Not found in Trash', 'text_domain'),
		'featured_image'        => __('Featured Image', 'text_domain'),
		'set_featured_image'    => __('Set featured image', 'text_domain'),
		'remove_featured_image' => __('Remove featured image', 'text_domain'),
		'use_featured_image'    => __('Use as featured image', 'text_domain'),
		'insert_into_category'      => __('Insert into category', 'text_domain'),
		'uploaded_to_this_category' => __('Uploaded to this category', 'text_domain'),
		'categories_list'            => __('Categories list', 'text_domain'),
		'categories_list_navigation' => __('Categories list navigation', 'text_domain'),
		'filter_categories_list'     => __('Filter items list', 'text_domain'),
	);
	$args   = array(
		'label'               => __('SME', 'text_domain'),
		'description'         => __('SME information page.', 'text_domain'),
		'labels'              => $labels,
		'supports'            => array('title', 'excerpt', 'thumbnail'),
		'taxonomies'          => array('sme-categories'),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-admin-multisite',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => false,
		'capability_type'     => 'post',
	);
	register_post_type('inotek_sme', $args);
}

function inotek_sme_meta_box()
{
	remove_meta_box('postimagediv', 'inotek_sme', 'side');
	add_meta_box('postimagediv', __('Photo'), 'post_thumbnail_meta_box', 'inotek_sme', 'normal');
	add_meta_box('inotek_sme_box_id', 'SME', 'inotek_sme_callback', 'inotek_sme');
}

function inotek_sme_callback($post)
{

	wp_nonce_field('inotek_sme_meta_box', 'inotek_sme_nonce');

	$location = get_post_meta($post->ID, '_location', true);
	$website = get_post_meta($post->ID, '_website', true);
	$olshop = get_post_meta($post->ID, '_olshop', true);

?>
	<div>
		<label for="inotek-sme-loc">Location</label>
		<input type="text" name="inotek_sme_loc" id="inotek-sme-loc" value="<?php echo $location; ?>">
	</div>
	<div>
		<label for="inotek-sme-web">Website</label>
		<input type="text" name="inotek_sme_web" id="inotek-sme-web" value="<?php echo $website; ?>">
	</div>
	<div>
		<label for="inotek-sme-olshop">Online Shop</label>
		<input type="text" name="inotek_sme_olshop" id="inotek-sme-olshop" value="<?php echo $olshop; ?>">
	</div>
<?php
}

function inotek_sme_save($post_id)
{
	if (! current_user_can('edit_post', $post_id)) {
		return;
	}

	if (! isset($_POST['inotek_sme_nonce'])) {
		return;
	}

	if (! wp_verify_nonce($_POST['inotek_sme_nonce'], 'inotek_sme_meta_box')) {
		return;
	}

	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}

	if (! isset($_POST['inotek_sme_job']) && ! isset($_POST['inotek_sme_loc'])) {
		return;
	}

	$location = sanitize_text_field($_POST['inotek_sme_loc']);
	$website = sanitize_text_field($_POST['inotek_sme_web']);
	$olshop = sanitize_text_field($_POST['inotek_sme_olshop']);

	update_post_meta($post_id, '_location', $location);
	update_post_meta($post_id, '_website', $website);
	update_post_meta($post_id, '_olshop', $olshop);
}

function inotek_register_taxonomy_category()
{
	$labels = [
		'name'              => _x('Category', 'taxonomy general name'),
		'singular_name'     => _x('Category', 'taxonomy singular name'),
		'search_categories'      => __('Search Category'),
		'all_categories'         => __('All Category'),
		'parent_category'       => __('Parent Category'),
		'parent_category_colon' => __('Parent Category:'),
		'edit_category'         => __('Edit Category'),
		'update_category'       => __('Update Category'),
		'add_new_category'      => __('Add New Category'),
		'new_category_name'     => __('New Category Name'),
		'menu_name'         => __('Category'),
	];
	$args   = [
		'hierarchical'      => true, // make it hierarchical (like categories)
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => ['slug' => 'sme-categories'],
	];
	register_taxonomy('sme-categories', ['inotek_sme'], $args);
}

function inotek_sme_filter_column($columns)
{
	$columns['location'] = __('Location');
	$columns['website'] = __('Website');
	$columns['olshop'] = __('Online Shop');
	$columns['image']         = __('Image');

	return $columns;
}

function inotek_sme_column($column, $post_id)
{
	switch ($column) {
		case 'image':
			the_post_thumbnail(array(100));
			break;
		case 'location':
			echo get_post_meta($post_id, '_location', true);
			break;
		case 'website':
			echo get_post_meta($post_id, '_website', true);
			break;
		case 'olshop':
			echo get_post_meta($post_id, '_olshop', true);
			break;
	}
}

add_filter('manage_inotek_sme_posts_columns', 'inotek_sme_filter_column');
add_action('manage_inotek_sme_posts_custom_column', 'inotek_sme_column', 10, 2);
add_action('save_post_inotek_sme', 'inotek_sme_save');
add_action('init', 'inotek_register_taxonomy_category');
