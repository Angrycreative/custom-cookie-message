<?php

namespace CustomCookieMessage\Forms;

/**
 * Cookie List Form.
 *
 * @package CustomCookieMessage\Forms
 */
class AdminCookieListOptions extends AdminBase {

	static protected $instance;

	/**
	 * CookieList constructor.
	 */
	public function __construct() {
		add_action( 'admin_init', [ $this, 'cookies_initialize_list_options' ] );
		$this->settings_save();
	}

	/**
	 * Access to the single instance of the class.
	 *
	 * @since 2.0.0
	 *
	 * @return object
	 */
	static public function instance() {
		if ( empty( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Callback section.
	 */
	public function getSection() {
		settings_fields( 'cookie_list_options' );
		do_settings_sections( 'cookie_list_options' );
	}

	public function cookies_initialize_list_options() {

		add_settings_section(
			'cookie_list_section',
			__( 'Cookie List Options', 'cookie-message' ),
			[ $this, 'cookie_list_options_callback' ],
			'cookie_list_options'
		);

		add_settings_field(
			'cookie_list',
			__( 'Cookie we found:', 'cookie-message' ),
			[ $this, 'cookie_message_height_slider_callback' ],
			'cookie_list_options',
			'cookie_list_section'
		);

		register_setting(
			'cookie_list_options',
			'cookie_list_options',
			[ $this, 'cookies_validate_options' ]
		);
	}

	public function cookie_list_options_callback() {
		echo '<p>' . esc_html_e( 'Label and select priority.', 'cookie-message' ) . '</p>';
	}

	public function cookie_message_height_slider_callback() {
		$options = get_option( 'cookie_list' );

		$options_priority = [
			__( 'Necesary Cookies', 'cookie-message' ),
			__( 'Performance Cookies', 'cookie-message' ),
			__( 'Commercial Cookies', 'cookie-message' ),
		];

		// TODO: Map $options.

		$output = '<div class="cookie_list_wrapper">';
		$output .= '</div>';

		echo $output;
	}

	private function settings_save() {
		if ( empty( $_POST['permalink_structure'] ) ) {
			return;
		}

		$cookie_list = get_option( 'cookie_list', [] );

		// TODO: Map $cookie_list.


		update_option( 'cookie_list', $cookie_list );
	}

}
