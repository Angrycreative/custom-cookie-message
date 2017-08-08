<?php

/**
 * Plugin Name: Custom Cookie Message
 * Plugin URI: https://angrycreative.se/
 * Description: A customizable cookie message.
 * Version: 1.6.3
 * Author: Johan Sylvan, angrycreative
 * Author URI: https://angrycreative.se/
 * Domain: cookie-message
 **/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CUSTOM_COOKIE_MESSAGE_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'CUSTOM_COOKIE_MESSAGE_PLUGIN_URL', plugins_url( '', __FILE__ ) . '/' );

define( 'CUSTOM_COOKIE_MESSAGE_PLUGIN_SLUG', 'custom-cookie-message-list' );

require_once( 'includes/ac-cookie-message.php' );

add_action( 'plugins_loaded', function () {
	$GLOBALS['CustomCookie'] = new AC_Custom_Cookie_Message();
} );

register_activation_hook( __FILE__, array( 'AC_Custom_Cookie_Message', 'plugin_activation' ) );
