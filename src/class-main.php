<?php
/**
 * Class Main
 *
 * @package CustomCookieMessage
 */

namespace CustomCookieMessage;

use CustomCookieMessage\Forms\AdminForm;

/**
 * Custom Cookie Message.
 *
 * @package CustomCookieMessage
 */
class Main {

	/**
	 * Options.
	 *
	 * @var string
	 */
	protected $options;

	/**
	 * Version plugin.
	 *
	 * @var string
	 */
	protected $version = '2.0.0';

	/**
	 * Class singlenton.
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

		register_activation_hook( CUSTOM_COOKIE_MESSAGE_FILE, [ $this, 'plugin_activation' ] );
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
			include_once CUSTOM_COOKIE_MESSAGE_DIR . '/src/' . $file_path;
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
	 * @return string Version number.
	 */
	public static function version() {
		$self = new self();

		return $self->version();
	}

	/**
	 * Get version.
	 *
	 * @return string Version.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Default trigger.
	 */
	public function init() {

		add_action( 'wp_enqueue_scripts', [ $this, 'ccm_enqueue_scripts' ], 100 );

		add_action( 'wp_ajax_nopriv_setcookie', [ $this, 'cookie_setcookie' ] );
		add_action( 'wp_ajax_setcookie', [ $this, 'cookie_setcookie' ] );

		load_plugin_textdomain( 'cookie-message' );

		if ( is_admin() ) {
			AdminForm::single();
		}

		add_action( 'wp_footer', [ $this, 'display_frontend_notice' ] );

	}

	/**
	 * Enable plugin cookie.
	 */
	public static function plugin_activation() {

		if ( false === get_option( 'custom_cookie_message' ) ) {
			add_option( 'custom_cookie_message', self::ccm_set_default_options() );
		}

	}

	/**
	 * Enqueue scripts needed for coockies.
	 */
	public function ccm_enqueue_scripts() {

		wp_register_style( 'cookie_style', CUSTOM_COOKIE_MESSAGE_PLUGIN_URL . '/assets/css/cookies.css' );

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
		$this->get_template( 'cookie-notice.php' );
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
			_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', esc_html( $located ) ), esc_html( self::version() ) );

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
			$template_path = CUSTOM_COOKIE_MESSAGE_PLUGIN_PATH . '/';
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
				'input_button_text'     => 'I understand',
				'input_link_text'       => 'Read more',
				'textarea_warning_text' => 'This website uses cookies. By using our website you accept our use of cookies.',
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
