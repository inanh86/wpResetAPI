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

// Define API_PLUGIN_FILE.
if ( ! defined( 'API_PLUGIN_FILE' ) ) {
	define( 'API_PLUGIN_FILE', __FILE__ );
}
if ( !defined( "API_PLUGIN_DIR_PATH" ) ) {
	define( "API_PLUGIN_DIR_PATH", plugins_url('' , __FILE__) );
}
// tải các packages đc cài bằng composer
require __DIR__ . './vendor/autoload.php';

// Include the main WooCommerce class.
if ( ! class_exists( 'WpResetAPI', false ) ) {
	include_once dirname( API_PLUGIN_FILE ). '/src/class-ResetAPI.php';
}
function WpResetAPI() {
	return inanh86\Controller\WpResetAPI::instance();
}
// Global for backwards compatibility.
$GLOBALS['WpResetAPI'] = WpResetAPI();