<?php
/**
 * Plugin Name: Gutenberg block for Gravity Forms
 * Plugin URI: https://github.com/wpjsio/gutenberg-block-for-gravity-forms
 * Description: Adds simple Gravity Forms selector in Gutenberg
 * Version: 1.0.0
 * License: GPL2+
 * Author: Luke Kowalski
 * Author URI: https://wpjs.io
 * License: GPL2
 * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: gf-gutenberg
 * Domain Path: /languages
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'Silence is golden.' );
}

add_action( 'plugins_loaded', 'gbgf_gravityforms_gutenberg_block_init' );

/**
 * Initialize our Gravity Forms block
 */
function gbgf_gravityforms_gutenberg_block_init() {
	if ( ! gbgf_all_required_plugins_installed() ) {
		add_action( 'admin_notices', 'gbgf_required_plugins_notice' );
		return;
	}

	if ( gbgf_is_gravityforms_webapi_enabled() ) {
		require_once dirname(__FILE__) . '/classes/class-gravityforms-gutenberg-block.php';
		GBGF_GravityForms_Gutenberg_Block::get_instance();
	} else {
		add_action( 'admin_notices', 'gbgf_disabled_webapi_notice' );
	}
}

/**
 * Check if Gravity Forms and Gutenberg plugins are installed
 */
function gbgf_all_required_plugins_installed() {
	include_once ABSPATH . 'wp-admin/includes/plugin.php';

	$is_gutenberg_active    = function_exists( 'register_block_type' );
    $is_gravityforms_active = is_plugin_active( 'gravityforms/gravityforms.php' );
    return $is_gutenberg_active && $is_gravityforms_active;
}

/**
 * Check if Gravity Forms Web API is enabled
 */
function gbgf_is_gravityforms_webapi_enabled() {
	$gravityforms_webapi = get_option( 'gravityformsaddon_gravityformswebapi_settings' );
	return intval( $gravityforms_webapi['enabled'] ) === 1;
}

/**
 * Create notification about missing plugins
 */
function gbgf_required_plugins_notice() {
	echo '<div class="error"><p>';
	echo __(' "Gutenberg block for Gravity Forms" requires following plugins to be installed: <ul> <li><a href="https://wordpress.org/plugins/gutenberg/">Gutenberg</a> (or WordPress version >= 5.0)</li><li><a href="https://www.gravityforms.com/">Graviy Forms</a></li></ul> Please make sure that you have all dependencies installed.', 'gf-gutenberg' );
	echo '</p></div>';
}

/**
 * Create notification about disabled Gravity Forms Web API
 */
function gbgf_disabled_webapi_notice() {
	echo '<div class="error"><p>';
	echo __(' "Gutenberg block for Gravity Forms" plugin requires Gravity Forms Web API to be enabled, please go to <a href="'. esc_url( get_admin_url( null, 'admin.php?page=gf_settings&subview=gravityformswebapi' ) ) .'">Forms > Settings > Web API</a> to adjust your settings.', 'gf-gutenberg' );
	echo '</p></div>';
}
