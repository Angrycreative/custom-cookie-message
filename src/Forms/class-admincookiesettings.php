<?php

namespace CustomCookieMessage\Forms;

/**
 * Cookie List Form.
 *
 * @package CustomCookieMessage\Forms
 */
class AdminCookieSettings {

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
	protected $settings_sections = 'cookie_options';

	/**
	 * CookieList constructor.
	 */
	public function __construct() {
		add_action( 'admin_init', [ $this, 'cookies_initialize_cookie_options' ] );
		$this->settings_save();
	}

	/**
	 * Access to the single instance of the class.
	 *
	 * @since 2.0.0
	 *
	 * @return object
	 */
	public static function single() {
		if ( empty( self::$single ) ) {
			self::$single = new self();
		}

		return self::$single;
	}

	public function cookies_initialize_cookie_options() {

		add_settings_section( 'cookie_list_section', __( 'Cookie List Options', 'cookie-message' ), [ $this, 'cookie_list_options_callback' ], $this->settings_sections );

		add_settings_field( 'cookie_list', __( 'Cookie we found:', 'cookie-message' ), [ $this, 'cookie_message_height_slider_callback' ], $this->settings_sections, 'cookie_list_section' );

		register_setting( 'custom_cookie_message', 'custom_cookie_message', [ $this, 'ccm_validate_options' ] );
	}

	public function cookie_list_options_callback() {
		echo '<p>' . esc_html_e( 'Label and select priority.', 'cookie-message' ) . '</p>';
	}

	public function cookie_message_height_slider_callback() {
		$cookie_list = get_option( 'cookie_list' );

		$options_priority = [
			__( 'Necesary Cookies', 'cookie-message' ),
			__( 'Performance Cookies', 'cookie-message' ),
			__( 'Commercial Cookies', 'cookie-message' ),
		];

		// TODO: Map $options.

		var_dump( $cookie_list );

		$output = '<div class="cookie_list_wrapper">';
		$output .= '</div>';

		echo $output;
	}

	private function settings_save() {
		if ( empty( $_POST['cookie_list'] ) ) {
			return;
		}

		$cookie_list = get_option( 'cookie_list', [] );

		// TODO: Map $cookie_list.

		update_option( 'cookie_list', $cookie_list );
	}

}