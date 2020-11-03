<?php
/**
 * Plugin Name: inanh86.com - ResetAPI Wordpress
 * Plugin URI: https://inanh86.com/ResetAPI
 * Description: Tùy Chỉnh là cổng Reset API Wordpress
 * Version: 0.1
 * Author: inanh86.com
 * Author URI: https://inanh86.com/ResetAPI
 * Text Domain: inanh86-resetAPI
 */
defined( 'ABSPATH' ) || exit;

// Define POS_PLUGIN_FILE.
if ( ! defined( 'API_PLUGIN_FILE' ) ) {
	define( 'API_PLUGIN_FILE', __FILE__ );
}
if ( !defined( "API_PLUGIN_DIR_PATH" ) ) {
	define( "API_PLUGIN_DIR_PATH", plugins_url('' , __FILE__) );
}
// Include the main WooCommerce class.
if ( ! class_exists( 'WpResetAPI', false ) ) {
	include_once dirname( API_PLUGIN_FILE ). '/src/class-ResetAPI.php';
}
function WpResetAPI() {
	return inanh86\WpResetAPI::instance();
}
// Global for backwards compatibility.
$GLOBALS['WpResetAPI'] = WpResetAPI();