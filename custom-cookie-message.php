<?php
/**
 * Custom Cookie Message.
 *
 * @author            Luigi Guevara @killua99 & Angry Creative AB
 * @link              https://git.synotio.se/ac-components/ac-private-files.git
 * @since             2.0.0
 * @package           CustomCookieMessage
 *
 * @wordpress-plugin
 * Plugin Name:       Custom Cookie Message
 * Plugin URI:        https://git.synotio.se/ac-components/ac-private-files.git
 * Description:       A customizable cookie message supported with GDPR.
 * Text Domain:       custom-cookie-message
 * Version:           2.0.0
 * Tested up to:      4.9
 * Author:            Luigi Guevara @killua99 & Angry Creative AB
 * Author URI:        https://angrycreative.se/
 * License:           GPL-2.0+
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.txt
 */

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

define( 'CUSTOM_COOKIE_MESSAGE_VERSION', '2.0.0' );

include_once CUSTOM_COOKIE_MESSAGE_DIR . '/src/class-main.php';

Main::single();
