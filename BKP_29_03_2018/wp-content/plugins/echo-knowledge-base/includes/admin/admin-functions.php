<?php

/*** GENERIC NON-KB functions  ***?

/**
 * When page is added/updated, check if it contains KB main page shortcode. If it does,
 * add the page to KB config.
 *
 * @param int $post_id The ID of the post being saved.
 * @param object $post The post object.
 * @param bool $update Whether this is an existing post being updated or not.
 */
function epkb_save_any_page( $post_id, $post, $update ) {

	// ignore autosave/revision which is not article submission; same with ajax and bulk edit
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || wp_is_post_autosave( $post_id ) || ( defined( 'DOING_AJAX') && DOING_AJAX ) || isset( $_REQUEST['bulk_edit'] ) ) {
		return;
	}

	// return if this page does not have KB shortocde
	$kb_id = EPKB_KB_Handler::get_kb_id_from_kb_main_shortcode( $post );
	if ( empty( $kb_id ) ) {
		return;
	}

	// core handles only default KB
	if ( $kb_id != EPKB_KB_Config_DB::DEFAULT_KB_ID && ! defined( 'E' . 'MKB_PLUGIN_NAME' ) ) {
		return;
	}

	// update KB kb_config if needed
	$kb_config = epkb_get_instance()->kb_config_obj->get_kb_config( $kb_id );
	if ( is_wp_error( $kb_config ) ) {
		return;
	}

	$kb_main_pages = isset( $kb_config['kb_main_pages'] ) ? $kb_config['kb_main_pages'] : array();

	// don't update if it is stored already
	if ( in_array($post_id, array_keys($kb_main_pages)) && $kb_main_pages[$post_id] == $post->post_title ) {
		return;
	}

	// remove revisions
	if ( $post->post_status == 'inherit' && isset($kb_main_pages[$post_id]) ) {
		unset($kb_main_pages[$post_id]);
	} else {
		$kb_main_pages[$post_id] = $post->post_title;
	}

	$kb_config['kb_main_pages'] = $kb_main_pages;

	// sanitize and save configuration in the database. see EPKB_Settings_DB class
	$result = epkb_get_instance()->kb_config_obj->update_kb_configuration( $kb_id, $kb_config );
	if ( is_wp_error( $result ) ) {
		return;
	}
}
add_action( 'save_post', 'epkb_save_any_page', 10, 3 );
