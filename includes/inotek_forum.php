<?php

function inotek_forum_post_type() {
	$labels = array(
		'name'                  => _x( 'Live Forum', 'Post Type General Name', 'text_domain' ),
		'singular_name'         => _x( 'Live Forum', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'             => __( 'Live Forums', 'text_domain' ),
		'name_admin_bar'        => __( 'Live Forum', 'text_domain' ),
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
		'label'               => __( 'Forum', 'text_domain' ),
		'description'         => __( 'Forum information page.', 'text_domain' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'thumbnail', 'excerpt', 'editor', 'page_properties' ),
		'taxonomies'          => array(),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-clock',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => false,
		'capability_type'     => 'post',
	);

	register_post_type( 'inotek_forum', $args );
}

function inotek_forum_meta_box() {
	remove_meta_box( 'postimagediv', 'inotek_forum', 'side' );
	add_meta_box( 'postimagediv', __( 'Featured Image' ), 'post_thumbnail_meta_box', 'inotek_forum', 'normal' );
	add_meta_box( 'forum-detail', 'Scale Up Forum Information', 'inotek_forum_callback', 'inotek_forum', 'normal' );
}

function inotek_forum_callback( $post ) {


	$speaker          = get_post_meta( $post->ID, '_forum_speaker', true );
	$speaker_title    = get_post_meta( $post->ID, '_forum_speaker_title', true );
	$forum_date       = get_post_meta( $post->ID, '_forum_date', true );
	$forum_start_time = get_post_meta( $post->ID, '_forum_time_start', true );
	$forum_end_time   = get_post_meta( $post->ID, '_forum_time_end', true );
	$live_event       = get_post_meta( $post->ID, '_forum_live', true );
	$url              = get_post_meta( $post->ID, '_forum_url', true );
	$embed_code       = get_post_meta( $post->ID, '_forum_embed', true );

	wp_nonce_field( 'inotek_forum_meta_box', 'inotek_forum_nonce' );
	?>
    <div>
        <label for="inotek-forum-speaker">Speaker</label>
        <input type="text" id="inotek-forum-speaker" name="inotek_forum_speaker" value="<?php echo $speaker; ?>">
    </div>
    <div>
        <label for="inotek-forum-speaker-title">Speaker Title</label>
        <input type="text" id="inotek-forum-speaker-title" name="inotek_forum_speaker_title"
               value="<?php echo $speaker_title; ?>">
    </div>
    <div>
        <label for="inotek-forum-date">Event Date</label>
        <input type="text" id="inotek-forum-date" name="inotek_forum_date" value="<?php echo $forum_date; ?>">
    </div>
    <div>
        <label for="inotek-forum-time">Event Time</label>
        <input type="text" id="inotek-forum-time-start" name="inotek_forum_time_start" placeholder="Start Time"
               value="<?php echo $forum_start_time; ?>">
        <input type="text" id="inotek-forum-time-end" name="inotek_forum_time_end" placeholder="End Time"
               value="<?php echo $forum_end_time; ?>">
    </div>
    <div>
        <label for="inotek-forum-live">Live Event</label>
        <select name="inotek_forum_live" id="inotek-forum-live">
            <option value="0" <?php if ( $live_event == 0 )
				echo 'selected' ?>>No
            </option>
            <option value="1" <?php if ( $live_event == 1 )
				echo 'selected' ?>>Yes
            </option>
        </select>
    </div>
    <div>
        <label for="inotek-forum-url">Youtube Stream</label>
        <input type="text" placeholder="Youtube link" id="inotek-forum-url" name="inotek_forum_url"
               value="<?php echo $url; ?>">
    </div>
    <div>
        <label for="inotek-forum-embed">Embed Code</label>
        <textarea name="inotek_forum_embed" id="inotek-forum-embed" cols="30"
                  rows="10"><?php echo $embed_code; ?></textarea>
    </div>
	<?php
}

function inotek_forum_save( $post_id ) {
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( ! wp_verify_nonce( $_POST['inotek_forum_nonce'], 'inotek_forum_meta_box' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! isset( $_POST['inotek_forum_speaker'] ) && ! isset( $_POST['inotek_forum_speaker_title'] )
	     && ! isset( $_POST['inotek_forum_date'] ) && ! isset( $_POST['inotek_forum_time_start'] )
	     && ! isset( $_POST['inotek_forum_time_end'] ) && ! isset( $_POST['inotek_forum_live'] ) ) {
		return;
	}

	$speaker          = sanitize_text_field( $_POST['inotek_forum_speaker'] );
	$speaker_title    = sanitize_text_field( $_POST['inotek_forum_speaker_title'] );
	$forum_date       = sanitize_text_field( $_POST['inotek_forum_date'] );
	$forum_start_time = sanitize_text_field( $_POST['inotek_forum_time_start'] );
	$forum_end_time   = sanitize_text_field( $_POST['inotek_forum_time_end'] );
	$live_event       = sanitize_text_field( $_POST['inotek_forum_live'] );
	$url              = sanitize_text_field( $_POST['inotek_forum_url'] );
	$embed_code       = $_POST['inotek_forum_embed'];

	update_post_meta( $post_id, '_forum_speaker', $speaker );
	update_post_meta( $post_id, '_forum_speaker_title', $speaker_title );
	update_post_meta( $post_id, '_forum_date', $forum_date );
	update_post_meta( $post_id, '_forum_time_start', $forum_start_time );
	update_post_meta( $post_id, '_forum_time_end', $forum_end_time );
	update_post_meta( $post_id, '_forum_live', $live_event );
	update_post_meta( $post_id, '_forum_url', $url );
	update_post_meta( $post_id, '_forum_embed', $embed_code );

}

function inotek_forum_live( $args ) {
	return 'Live';
}

add_shortcode( 'inotek_forum_live', 'inotek_forum_live' );
add_action( 'save_post_inotek_forum', 'inotek_forum_save' );


