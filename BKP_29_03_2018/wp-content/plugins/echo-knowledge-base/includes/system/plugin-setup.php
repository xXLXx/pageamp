<?php

/**
 * Activate the plugin
 *
 * @copyright   Copyright (C) 2018, Echo Plugins
*/

/**
 * Activate this plugin i.e. setup tables, data etc.
 * NOT invoked on plugin updates
 */
function epkb_activate_plugin() {

	$new_kb_main_page_title = __( 'Knowledge Base', 'echo-knowledge-base' );

	// true if the plugin was activated for the first time since installation
	$plugin_version = get_option( 'epkb_version' );
	if ( empty($plugin_version) ) {
		update_option( 'epkb_show_welcome_header', true );
		set_transient( '_epkb_plugin_installed', true, 3600 );
		EPKB_KB_Handler::add_new_knowledge_base( EPKB_KB_Config_DB::DEFAULT_KB_ID, $new_kb_main_page_title );  // ignore errors

	} else {
		EPKB_KB_Handler::update_existing_knowledge_base( EPKB_KB_Config_DB::DEFAULT_KB_ID, $new_kb_main_page_title );  // ignore errors
	}

	set_transient( '_epkb_plugin_activated', true, 3600 );

	// Clear permalinks
	update_option( 'epkb_flush_rewrite_rules', true );
	flush_rewrite_rules( false );
}
register_activation_hook( Echo_Knowledge_Base::$plugin_file, 'epkb_activate_plugin' );

/**
 * User deactivates this plugin so refresh the permalinks
 */
function epkb_deactivation() {

	// Clear the permalinks to remove our post type's rules
	flush_rewrite_rules( false );

}
register_deactivation_hook( Echo_Knowledge_Base::$plugin_file, 'epkb_deactivation' );
