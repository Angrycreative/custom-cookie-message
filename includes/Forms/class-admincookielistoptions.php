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

	public function getSection() {
		settings_fields( 'cookie_list_section' );
		do_settings_sections( 'cookie_list_options' );
	}

	public function cookies_initialize_list_options() {
		add_settings_section(
			'cookie_list_section',
			__( 'Cookie List Options', 'cookie-message' ),
			[ $this, 'cookies_list_options_callback' ],
			'cookie_list_options'
		);

		add_settings_field(
			'cookie-list',
			__( 'Cookie we found:', 'cookie-message' ),
			[ $this, 'cookies_message_height_slider_callback' ],
			'cookie_list_options',
			'cookie_list_section'
		);

		register_setting(
			'cookies_content_options',
			'cookies_content_options',
			[ $this, 'cookies_validate_options' ]
		);
	}

	public function cookies_message_height_slider_callback() {
		echo '<input type="text" name="something" class="something" >';
	}

	public function cookies_list_options_callback() {
		echo '<p>' . esc_html_e( 'Label and select priority.', 'cookie-message' ) . '</p>';
	}

}
