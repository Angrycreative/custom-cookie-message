<?php
/**
 * AdminCookieSettings
 *
 * @package CustomCookieMessage\Forms
 */

namespace CustomCookieMessage\Forms;

/**
 * Cookie List Form.
 *
 * @package CustomCookieMessage\Forms
 */
class AdminCookieSettings extends AdminBase {

	use AdminTrait;

	/**
	 * Class AdminCookieOptions
	 *
	 * @var AdminCookieSettings
	 */
	static protected $single;

	/**
	 * Settings Sections.
	 *
	 * @var string
	 */
	protected $section_page = 'cookie_settings';

	/**
	 * CookieList constructor.
	 */
	public function __construct() {
		parent::__construct();
		add_action( 'admin_init', [ $this, 'cookies_initialize_cookie_options' ] );
	}

	/**
	 * Access to the single instance of the class.
	 *
	 * @since 2.0.0
	 *
	 * @return AdminCookieSettings
	 */
	public static function single() {
		if ( empty( self::$single ) ) {
			self::$single = new self();
		}

		return self::$single;
	}

	/**
	 * Define settings.
	 */
	public function cookies_initialize_cookie_options() {

		add_settings_section( 'cookie_settings_section', esc_html__( 'Cookie Granilarity Options', 'custom-cookie-message' ), [ $this, 'cookie_list_options_callback' ], $this->section_page );

		add_settings_field( 'required_cookies', esc_html__( 'Required Cookies Message:', 'custom-cookie-message' ), [ $this, 'cookie_required_callback' ], $this->section_page, 'cookie_settings_section' );

		add_settings_field( 'functional_cookies', esc_html__( 'Functional Cookies Message:', 'custom-cookie-message' ), [ $this, 'cookie_functional_callback' ], $this->section_page, 'cookie_settings_section' );

		add_settings_field( 'advertising_cookies', esc_html__( 'Advertising Cookies Message:', 'custom-cookie-message' ), [ $this, 'cookie_advertising_callback' ], $this->section_page, 'cookie_settings_section' );
	}

	/**
	 * List options.
	 */
	public function cookie_list_options_callback() {
		echo '<p>' . esc_html_e( 'Activate Cookies and granilarity.', 'cookie-message' ) . '</p>';
	}

	/**
	 * Required Cookies Message.
	 */
	public function cookie_required_callback() {
		wp_editor( $this->options['cookie_granularity_settings']['required_cookies_message'], 'required_cookies_message', [
			'teeny'         => true,
			'textarea_name' => 'custom_cookie_message[cookie_granularity_settings][required_cookies_message]',
		] );
	}

	/**
	 * Required Cookies Message.
	 */
	public function cookie_functional_callback() {
		wp_editor( $this->options['cookie_granularity_settings']['functional_cookies_message'], 'functional_cookies_message', [
			'teeny'         => true,
			'textarea_name' => 'custom_cookie_message[cookie_granularity_settings][functional_cookies_message]',
		] );
	}

	/**
	 * Required Cookies Message.
	 */
	public function cookie_advertising_callback() {
		wp_editor( $this->options['cookie_granularity_settings']['advertising_cookies_message'], 'advertising_cookies_message', [
			'teeny'         => true,
			'textarea_name' => 'custom_cookie_message[cookie_granularity_settings][advertising_cookies_message]',
		] );
	}

}
