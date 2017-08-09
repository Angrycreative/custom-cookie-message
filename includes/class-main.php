<?php
/**
 * Author: Johan Sylvan
 * Date: 2016-09-27
 * Time: 08:53
 */

namespace CustomCookieMessage;

use CustomCookieMessage\Forms\Admin;

/**
 * Custom Cookie Message.
 *
 * @package CustomCookieMessage
 */
class Main {

	public $options;

	public $version = '2.0.0';

	function __construct() {

		spl_autoload_register( [ $this, 'autoload' ] );

		add_action( 'init', [ $this, 'init' ], 30 );
		add_action( 'init', [ $this, 'cookie_start' ] );

		register_activation_hook( CUSTOM_COOKIE_MESSAGE_FILE, [ $this, 'plugin_activation' ] );
	}

	/**
	 * Autoloader
	 *
	 * @since 2.0.0
	 *
	 * @param string $class_name
	 *   Class name loader.
	 */
	public function autoload( $class_name ) {
		if ( false === strpos( $class_name, 'CustomCookieMessage' ) ) {
			return;
		}

		$class     = str_replace( '\\', DIRECTORY_SEPARATOR, str_replace( '_', '-', $class_name ) );
		$class     = preg_replace( '#/([a-zA-Z]*)$#', strtolower( '/class-$1' ), $class );
		$file_path = str_replace( 'CustomCookieMessage/', '', $class ) . '.php';

		if ( file_exists( CUSTOM_COOKIE_MESSAGE_DIR . '/includes/' . $file_path ) ) {
			include_once CUSTOM_COOKIE_MESSAGE_DIR . '/includes/' . $file_path;
		}
	}

	function init() {

		add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_styles' ), 100 );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_scripts' ) );

		add_action( 'wp_ajax_nopriv_setcookie', array( $this, 'cookie_setcookie' ) );
		add_action( 'wp_ajax_setcookie', array( $this, 'cookie_setcookie' ) );

		if ( is_admin() ) {
			Admin::instance();
		}

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
		wp_register_style( 'cookie_style', CUSTOM_COOKIE_MESSAGE_PLUGIN_URL . '/assets/css/cookies.css' );

		wp_enqueue_style( 'cookie_style' );

	}


	public function register_plugin_scripts() {

		// embed the javascript file that makes the AJAX request
		wp_enqueue_script( 'my-ajax-request', CUSTOM_COOKIE_MESSAGE_PLUGIN_URL . '/assets/js/ac-custom-cookie-message-frontend.js', array( 'jquery' ) );

		// declare the URL to the file that handles the AJAX request (wp-admin/admin-ajax.php)
		wp_localize_script( 'my-ajax-request', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	}

	function cookies_menu() {
		add_options_page(
			'Cookies',                              // The title to be displayed in the browser window for this page.
			'Cookies',                              // The text to be displayed for this menu item
			'administrator',                        // Which type of users can see this menu item
			'cookies_options',                      // The unique ID - that is, the slug - for this menu item
			array(
				$this,
				'cookies_options_display'
			) // The name of the function to call when rendering this menu's page
		);
	}

	function display_frontend_notice() {
		$this->get_template( 'cookie-notice.php' );
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


	function cookies_options_display() {

		$allow_edition = false;

		$current_roles = wp_get_current_user()->roles;

		if ( ! ! array_intersect( [ 'administrator', 'editor' ], $current_roles ) ) {
			$allow_edition = true;
		}

		?>
		<!-- Create a header in the default WordPress 'wrap' container -->
		<div class="wrap">

			<h2><?php _e( 'Cookies Theme Options', 'cookies-message' ); ?></h2>
			<!-- Give user feeback that a setting has been saved-->
			<?php //settings_errors();
			?>
			<!-- isset works as an "onclick" for the tabs to set active tab-->
			<?php $active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'general_options'; ?>

			<!-- Tabs -->
			<h2 class="nav-tab-wrapper">
				<a href="?page=cookies_options&tab=general_options"
				   class="nav-tab <?php echo $active_tab == 'general_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'General Options', 'cookies' ); ?></a>
				<a href="?page=cookies_options&tab=content_options"
				   class="nav-tab <?php echo $active_tab == 'content_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Content Options', 'cookies' ); ?></a>
				<?php if ( $allow_edition ): ?>
					<a href="?page=cookies_options&tab=styling_options"
					   class="nav-tab <?php echo $active_tab == 'styling_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Styling Options', 'cookies' ); ?></a>
				<?php endif; ?>
			</h2>

			<form method="post" action="options.php">

				<?php if ( $active_tab == 'general_options' ) {
					settings_fields( 'cookies_general_options' );
					do_settings_sections( 'cookies_general_options' );
				} elseif ( $active_tab == 'content_options' ) {
					settings_fields( 'cookies_content_options' );
					do_settings_sections( 'cookies_content_options' );
				} else {
					settings_fields( 'cookies_styling_options' );
					do_settings_sections( 'cookies_styling_options' );
				}

				submit_button(); ?>
			</form>
		</div>
		<?php
	}
	/* ------------------------------------------------------------------------ *
	 * Setting Registration
	 * ------------------------------------------------------------------------ */

	//Provides default values for the General Options.
	static function cookies_default_general_options() {

		$defaults = array(
			'location_options'  => 'top-fixed',
			'cookies_page_link' => ''
		);

		return apply_filters( 'cookies_default_general_options', $defaults );
	}

	//Provides default values for the Content Options.
	static function cookies_default_content_options() {

		$defaults = array(
			'input_button_text'     => 'I understand',
			'input_link_text'       => 'Read more',
			'textarea_warning_text' => 'This website uses cookies. By using our website you accept our use of cookies.'
		);

		return apply_filters( 'cookies_default_content_options', $defaults );
	}

	//Provides default values for the Styling Options.
	static function cookies_default_styling_options() {

		$defaults = array(
			'message_color_picker'      => '#3E3E3B',
			'button_color_picker'       => '#EBECED',
			'button_hover_color_picker' => '#CBC5C1',
			'button_text_color_picker'  => '#3E3E3B',
			'text_color_picker'         => '#EBECED',
			'link_color_picker'         => '#CBC5C1'
		);

		return apply_filters( 'cookies_default_styling_options', $defaults );
	}

}
