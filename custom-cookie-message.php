<?php

/**
 * Plugin Name: Custom Cookie Message
 * Plugin URI: https://angrycreative.se/
 * Description: A customizable cookie message.
 * Version: 2.0.0
 * Author: Johan Sylvan, angrycreative
 * Author URI: https://angrycreative.se/
 * Domain: cookie-message
 **/

use CustomCookieMessage\Main;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CUSTOM_COOKIE_MESSAGE_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'CUSTOM_COOKIE_MESSAGE_PLUGIN_URL', plugins_url( '', __FILE__ ) . '/' );

define( 'CUSTOM_COOKIE_MESSAGE_FILE', __FILE__ );
define( 'CUSTOM_COOKIE_MESSAGE_BASENAME', plugin_basename( CUSTOM_COOKIE_MESSAGE_FILE ) );
define( 'CUSTOM_COOKIE_MESSAGE_DIR', dirname( CUSTOM_COOKIE_MESSAGE_FILE ) );

define( 'CUSTOM_COOKIE_MESSAGE_PLUGIN_SLUG', 'custom-cookie-message-list' );

include_once CUSTOM_COOKIE_MESSAGE_DIR . '/includes/class-main.php';

new Main();
