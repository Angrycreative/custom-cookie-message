<?php
/**
 * Class Main
 *
 * @package CustomCookieMessage
 */

namespace CustomCookieMessage;

use CustomCookieMessage\Controller\Controller;
use CustomCookieMessage\Forms\AdminForm;

/**
 * Custom Cookie Message.
 *
 * @package CustomCookieMessage
 */
class Main {

	/**
	 * Current version.
	 *
	 * @var string
	 */
	protected $version = '2.0.0';

	/**
	 * Store singlenton CustomCookieMessage\Main.
	 *
	 * @var Main
	 */
	protected static $single;

	/**
	 * Main constructor.
	 */
	public function __construct() {

		spl_autoload_register( [ $this, 'autoload' ] );

		add_action( 'init', [ $this, 'init' ], 30 );

		register_activation_hook( CUSTOM_COOKIE_MESSAGE_FILE, [ __CLASS__, 'plugin_activation' ] );

	}

	/**
	 * Autoloader
	 *
	 * @since 2.0.0
	 *
	 * @param string $class_name Class name loader.
	 */
	public function autoload( $class_name ) {
		if ( false === strpos( $class_name, 'CustomCookieMessage' ) ) {
			return;
		}

		$class     = str_replace( '\\', DIRECTORY_SEPARATOR, str_replace( '_', '-', $class_name ) );
		$class     = preg_replace( '#/([a-zA-Z]*)$#', strtolower( '/class-$1' ), $class );
		$file_path = str_replace( 'CustomCookieMessage/', '', $class ) . '.php';

		if ( file_exists( CUSTOM_COOKIE_MESSAGE_DIR . '/src/' . $file_path ) ) {
			include_once CUSTOM_COOKIE_MESSAGE_DIR . '/src/' . strtolower( $file_path );
		}
	}

	/**
	 * Access to the single instance of the class.
	 *
	 * @return Main
	 */
	public static function single() {
		if ( empty( self::$single ) ) {
			self::$single = new self();
		}

		return self::$single;
	}

	/**
	 * Get the static version.
	 *
	 * @return string Version self::$version.
	 */
	public static function version() {
		return self::single()->get_version();
	}

	/**
	 * Get version.
	 *
	 * @return string Version CUSTOM_COOKIE_MESSAGE_VERSION.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Default trigger.
	 */
	public function init() {

		Controller::single();

		load_plugin_textdomain( 'custom-cookie-message' );

		if ( is_admin() ) {
			AdminForm::single();

			add_action( 'upgrader_process_complete', [ __CLASS__, 'update' ] );

			add_action( 'admin_notices', [ __CLASS__, 'admin_notices' ] );
		}

		add_action( 'wp_footer', [ $this, 'display_frontend_notice' ] );

	}

	/**
	 * Enable plugin cookie.
	 */
	public static function plugin_activation() {

		if ( empty( get_option( 'custom_cookie_message', [] ) ) ) {
			add_option( 'custom_cookie_message', self::ccm_set_default_options() );
		}

	}

	/**
	 * Were updates in options and code are written.
	 *
	 * @since 2.0.0
	 */
	public static function update() {

		$current_installed_version = str_replace( '.', '', get_site_option( 'custom_cookie_message_version', '1.6.4' ) );
		$current_version           = str_replace( '.', '', self::version() );

		$update_queue = [];
		while ( $current_version > $current_installed_version ) {
			++ $current_installed_version;
			if ( method_exists( '\CustomCookieMessage\Update', 'custom_cookie_message_' . $current_installed_version ) ) {
				$update_queue[] = [ '\CustomCookieMessage\Update', 'custom_cookie_message_' . $current_installed_version ];
			}
		}

		// Is empty, who trigger it?
		if ( empty( $update_queue ) ) {
			return;
		}

		foreach ( $update_queue as $update_function ) {
			$update_function();
		}

	}

	/**
	 * Handle all admin notices.
	 */
	public static function admin_notices() {

		$current_installed_version = str_replace( '.', '', get_site_option( 'custom_cookie_message_version', '1.6.4' ) );
		$current_version           = str_replace( '.', '', self::version() );
		$output                    = '';

		if ( $current_installed_version < $current_version ) {
			$output .= '<div class="notice notice-info">';
			$output .= '<h2 class="notice-title">';
			$output .= esc_html( 'Custom Cookie Message' );
			$output .= '</h2>';
			$output .= '<p>';
			$output .= esc_html__( 'An update is available.', 'custom-cookie-message' );
			$output .= ' <a href="#ccm-upgrade" class="custom-cookie-message-upgrade">' . esc_html__( 'Upgrade now', 'custom-cookie-message' ) . '</a>';
			$output .= '</p>';
			$output .= '</div>';
		}

		echo $output; // WPCS: XSS ok.

	}

	/**
	 * Enqueue scripts needed for coockies.
	 */
	public function ccm_enqueue_scripts() {

		wp_register_style( 'cookie_style', CUSTOM_COOKIE_MESSAGE_PLUGIN_URL . '/assets/css/custom-cookie-message-popup.css' );

		wp_enqueue_style( 'cookie_style' );

		wp_enqueue_script( 'my-ajax-request', CUSTOM_COOKIE_MESSAGE_PLUGIN_URL . '/assets/js/ac-custom-cookie-message-frontend.js', [ 'jquery' ] );

		wp_localize_script( 'my-ajax-request', 'MyAjax', [
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
		] );

	}

	/**
	 * Template notice.
	 */
	public function display_frontend_notice() {

		wp_enqueue_style( 'custom-cookie-message-popup-styles', CUSTOM_COOKIE_MESSAGE_PLUGIN_URL . '/assets/css/custom-cookie-message-popup.css', [], $this->version, 'screen' );

		wp_enqueue_script( 'custom-cookie-message-popup', CUSTOM_COOKIE_MESSAGE_PLUGIN_URL . '/assets/js/custom-cookie-message-popup.js', [ 'jquery' ], $this->version, true );
		wp_localize_script( 'custom-cookie-message-popup', 'customCookieMessageLocalize', [
			'options' => get_option( 'custom_cookie_message' ),
		] );

	}

	/**
	 * Include template if we could locate it.
	 *
	 * @param string $template_name Template name.
	 * @param array  $args Args.
	 * @param string $template_path Path to it.
	 * @param string $default_path Default path.
	 */
	public static function get_template( $template_name, $args = [], $template_path = '', $default_path = '' ) {

		$located = static::locate_template( $template_name, $template_path, $default_path );

		if ( ! file_exists( $located ) ) {
			_doing_it_wrong( __FUNCTION__, sprintf( ' < code>%s </code > does not exist . ', esc_html( $located ) ), esc_html( self::version() ) );

			return;
		}

		include $located;
	}

	/**
	 * Helper to locate templates.
	 *
	 * @param string $template_name Template name.
	 * @param string $template_path Template Path.
	 * @param string $default_path Default path.
	 *
	 * @return string
	 */
	public static function locate_template( $template_name, $template_path = '', $default_path = '' ) {
		if ( ! $template_path ) {
			$template_path = CUSTOM_COOKIE_MESSAGE_PLUGIN_PATH . ' / ';
		}

		if ( ! $default_path ) {
			$default_path = CUSTOM_COOKIE_MESSAGE_PLUGIN_PATH . '/templates';
		}

		$template = locate_template( [
			trailingslashit( $template_path ) . $template_name,
			$template_name,
		] );

		if ( ! $template ) {
			$template = $default_path . $template_name;
		}

		return $template;
	}

	/**
	 * TODO: Move this away form here.
	 */
	public function cookie_setcookie() {
		// TODO: Create option with life span. Also move it to REST API.
		setcookie( 'cookie-warning-message', 15, 30 * DAYS_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
		wp_send_json( 1 );
	}

	/**
	 * Default Options.
	 *
	 * @return mixed|void
	 */
	public static function ccm_set_default_options() {

		$defaults = [
			'general' => [
				'location_options'  => 'top-fixed',
				'cookies_page_link' => '',
			],
			'content' => [
				'input_button_text'     => esc_html__( 'I understand', 'custom-cookie-message' ),
				'input_link_text'       => esc_html__( 'Read more', 'custom-cookie-message' ),
				'textarea_warning_text' => esc_html__( 'This website uses cookies . By using our website you accept our use of cookies . ', 'custom-cookie-message' ),
			],
			'styles'  => [
				'messages_color_picker'     => '#3E3E3B',
				'button_color_picker'       => '#EBECED',
				'button_hover_color_picker' => '#CBC5C1',
				'button_text_color_picker'  => '#3E3E3B',
				'text_color_picker'         => '#EBECED',
				'link_color_picker'         => '#CBC5C1',
			],
		];

		return apply_filters( 'custom_cookie_message_set_options', $defaults );
	}

}
