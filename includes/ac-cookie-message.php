<?php
/**
 * Author: Johan Sylvan
 * Date: 2016-09-27
 * Time: 08:53
 */

if ( ! class_exists( 'AC_Custom_Cookie_Message' ) ) {

	class AC_Custom_Cookie_Message {

		public $options;

		function __construct() {

			add_action( 'init', array( $this, 'init' ), 30 );
			add_action( 'init', array( $this, 'cookie_start' ) );
		}

		function init() {

			add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_styles' ), 100 );
			add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_scripts' ) );

			add_action( 'admin_enqueue_scripts', array( $this, 'register_backend_plugin_styles' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'register_backend_plugin_scripts' ) );

			add_action( 'wp_ajax_nopriv_setcookie', array( $this, 'cookie_setcookie' ) );
			add_action( 'wp_ajax_setcookie', array( $this, 'cookie_setcookie' ) );



			//$location = get_option('cookies_general_options');

			add_action( 'wp_footer', array( $this, 'display_frontend_notice' ) );

		}



		public static function plugin_activation() {
			if ( false == get_option( 'cookies_general_options' ) ) {
				add_option( 'cookies_general_options', static::cookies_default_general_options() );
			}
			if ( false == get_option( 'cookies_content_options' ) ) {
				add_option( 'cookies_content_options', static::cookies_default_content_options() );
			}
			if ( false == get_option( 'cookies_styling_options' ) ) {
				add_option( 'cookies_styling_options', static::cookies_default_styling_options() );
			}

		}

		public function cookie_start() {

			load_plugin_textdomain( 'cookie-message' );
		}

		//Register and enqueue style sheet.
		public function register_plugin_styles() {
			wp_register_style( 'cookie_style', CUSTOM_COOKIE_MESSAGE_PLUGIN_URL . '/css/cookies.css' );

			wp_enqueue_style( 'cookie_style' );

		}


		public function register_plugin_scripts() {

			// embed the javascript file that makes the AJAX request
			wp_enqueue_script( 'my-ajax-request', CUSTOM_COOKIE_MESSAGE_PLUGIN_URL . 'js/ac-custom-cookie-message-frontend.js', array( 'jquery' ) );

			// declare the URL to the file that handles the AJAX request (wp-admin/admin-ajax.php)
			wp_localize_script( 'my-ajax-request', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		}

		public function register_backend_plugin_styles() {
			wp_enqueue_style( 'jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css' );
			wp_register_style( 'cookie_style', CUSTOM_COOKIE_MESSAGE_PLUGIN_URL . '/css/cookies.css' );
			wp_enqueue_style( 'cookie_style' );
			wp_enqueue_style( 'wp-color-picker' );

		}

		public function register_backend_plugin_scripts() {
			wp_enqueue_script( 'variation-custom-cookie-script', CUSTOM_COOKIE_MESSAGE_PLUGIN_URL . '/js/ac-custom-cookie-message-backend.js', array(
				'jquery',
				'jquery-ui-slider',
				'wp-color-picker'
			) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_js' ) );

		}



		function display_frontend_notice() {
			AC_Custom_Cookie_Message::get_template( 'cookie-notice.php' );
		}

		static function get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
			if ( ! empty( $args ) && is_array( $args ) ) {
				extract( $args );
			}

			$located = static::locate_template( $template_name, $template_path, $default_path );

			if ( ! file_exists( $located ) ) {
				_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $located ), '2.1' );

				return;
			}

			include( $located );
		}


		static function locate_template( $template_name, $template_path = '', $default_path = '' ) {
			if ( ! $template_path ) {
				$template_path = CUSTOM_COOKIE_MESSAGE_PLUGIN_PATH . '/';
			}

			if ( ! $default_path ) {
				$default_path = CUSTOM_COOKIE_MESSAGE_PLUGIN_PATH . '/views/';
			}

			// Look within passed path within the theme - this is priority.
			$template = locate_template(
				array(
					trailingslashit( $template_path ) . $template_name,
					$template_name
				)
			);

			// Get default template/
			if ( ! $template ) {
				$template = $default_path . $template_name;
			}

			// Return what we found.
			return $template;
		}

		public function cookie_setcookie() {
			setcookie( 'cookie-warning-message', 15, 30 * DAYS_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
			wp_send_json( 1 );
		}










	}

}
