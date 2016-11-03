<?php

/**
 * Plugin Name: Custom Cookie Message
 * Plugin URI: https://angrycreative.se/
 * Description: A customizable cookie message.
 * Version: 1.0
 * Author: Johan Sylvan, angrycreative
 * Author URI: https://angrycreative.se/
 **/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define('CUSTOM_COOKIE_MESSAGE_PLUGIN_PATH', plugin_dir_path( __FILE__ ));
define('CUSTOM_COOKIE_MESSAGE_PLUGIN_URL', plugins_url('', __FILE__).'/');

define('CUSTOM_COOKIE_MESSAGE_PLUGIN_SLUG', 'custom-cookie-message-list');
//<script src="js/jquery-1.9.1.min.js" type="text/javascript">

require_once('includes/ac-cookie-message.php');

add_action( 'plugins_loaded', function() {


    $GLOBALS['CustomCookie'] = new AC_Custom_Cookie_Message(); 
    //$GLOBALS['WC_List_Color_Variation'] = new WC_List_Color_Variation();

});


register_activation_hook( __FILE__, array( 'AC_Custom_Cookie_Message', 'plugin_activation' ) );

