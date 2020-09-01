<?php
/**
 * Custom Cookie Message.
 *
 * @author            Angry Creative AB
 * @link              https://github.com/Angrycreative/custom-cookie-message
 * @since             2.0.0
 * @package           CustomCookieMessage
 *
 * @wordpress-plugin
 * Plugin Name:       Custom Cookie Message
 * Plugin URI:        https://github.com/Angrycreative/custom-cookie-message
 * Description:       A customizable cookie message, compatible with GDPR compliance.
 * Text Domain:       custom-cookie-message
 * Domain Path:       /languages
 * Version:           2.4.10
 * Tested up to:      5.5
 * Requires PHP:      5.6
 * Author:            Angry Creative AB
 * Contributors:      Johan Sylvan, kylegard, Luigi Guevara @killua99, Victor Camnerin, Elias Chalhoub, Hosam Alnajar
 * Author URI:        https://angrycreative.se/
 * License:           GPL-2.0+
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use CustomCookieMessage\Main;

define( 'CUSTOM_COOKIE_MESSAGE_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'CUSTOM_COOKIE_MESSAGE_PLUGIN_URL', plugins_url( '', __FILE__ ) . '/' );
define( 'CUSTOM_COOKIE_MESSAGE_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

define( 'CUSTOM_COOKIE_MESSAGE_FILE', __FILE__ );
define( 'CUSTOM_COOKIE_MESSAGE_BASENAME', plugin_basename( CUSTOM_COOKIE_MESSAGE_FILE ) );
define( 'CUSTOM_COOKIE_MESSAGE_DIR', dirname( CUSTOM_COOKIE_MESSAGE_FILE ) );

require_once CUSTOM_COOKIE_MESSAGE_DIR . '/src/class-main.php';

Main::single();
