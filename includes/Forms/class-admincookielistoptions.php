<?php

namespace CustomCookieMessage\Forms;

/**
 * Cookie List Form.
 *
 * @package CustomCookieMessage\Forms
 */
class AdminCookieListOptions {

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
			__( 'Styling Options', 'cookie-message' ),
			[ $this, 'cookies_list_options_callback' ],
			'cookie_list_options'
		);

		add_settings_field(
			'Message Size',
			__( 'Message container padding top and bottom', 'cookie-message' ),
			[ $this, 'cookies_message_height_slider_callback' ],
			'cookie_list_options',
			'cookie_list_section' );
	}

	public function cookies_message_height_slider_callback() {
		echo '<input type="text name="something" class="something" >';
	}

	public function cookies_list_options_callback() {
		echo '<p>' . esc_html_e( 'Select the styling for the cookie message.', 'cookie-message' ) . '</p>';
	}


}
