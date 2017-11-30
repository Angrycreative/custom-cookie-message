<?php

namespace CustomCookieMessage\Forms;

class AdminGeneralOptions extends AdminBase {

	static protected $instance;

	/**
	 * CookieList constructor.
	 */
	public function __construct() {
		add_action( 'admin_init', [ $this, 'cookies_initialize_general_options' ] );
	}

	/**
	 * Access to the single instance of the class.
	 *
	 * @since 2.0.0
	 *
	 * @return AdminStylingOptions
	 */
	static public function instance() {
		if ( empty( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function getSection() {
		settings_fields( 'cookies_general_options' );
		do_settings_sections( 'cookies_general_options' );
	}


	/**
	 * Define settings.
	 */
	public function cookies_initialize_general_options() {

		add_settings_section(
			'general_options_section',
			esc_html__( 'General Options', 'cookie-message' ),
			[ $this, 'cookies_general_options_callback' ],
			'cookies_general_options'
		);

		add_settings_field(
			'location_options',
			__( 'Select location of message:', 'cookie-message' ),
			[ $this, 'cookies_select_position_callback' ],
			'cookies_general_options',
			'general_options_section'
		);

		add_settings_field(
			'cookies_page_link',
			__( 'Enter url to the page about cookies:', 'cookie-message' ),
			[ $this, 'cookies_page_link_callback' ],
			'cookies_general_options',
			'general_options_section'
		);

		register_setting(
			'cookies_general_options',
			'cookies_general_options',
			[ $this, 'cookies_validate_options' ]
		);
	}

	public function cookies_general_options_callback() {
		echo '<p>' . esc_html_e( 'Select where the cookie message should be displayed and enter the URL to the page about cookies.', 'cookie-message' ) . '</p>';
	}

	public function cookies_select_position_callback() {
		// Get the options for which this setting is in
		$options = get_option( 'cookies_general_options' );

		$html = '<select id="location_options" name="cookies_general_options[location_options]">';
		$html .= '<option value="top-fixed"' . selected( $options['location_options'], 'top-fixed', false ) . '>' . __( 'Top as overlay', 'cookie-message' ) . '</option>';
		$html .= '<option value="bottom-fixed"' . selected( $options['location_options'], 'bottom-fixed', false ) . '>' . __( 'Bottom as overlay', 'cookie-message' ) . '</option>';
		$html .= '</select>';

		echo $html;
	}

	public function cookies_page_link_callback() {
		$options = get_option( 'cookies_general_options' );
		//echo ($options['cookies_page_link']);

		echo '<input type="text" id="cookies_page_link" name="cookies_general_options[cookies_page_link]" value="' . $options['cookies_page_link'] . '" />';
	}

}
