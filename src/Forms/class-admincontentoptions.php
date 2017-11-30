<?php

namespace CustomCookieMessage\Forms;

class AdminContentOptions extends AdminBase {

	static protected $instance;

	/**
	 * CookieList constructor.
	 */
	public function __construct() {
		add_action( 'admin_init', [ $this, 'cookies_initialize_content_options' ] );
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
		settings_fields( 'cookies_content_options' );
		do_settings_sections( 'cookies_content_options' );
	}


	/**
	 *
	 */
	public function cookies_initialize_content_options() {

		add_settings_section(
			'content_options_section',
			__( 'Content Options', 'cookie-message' ),
			[ $this, 'cookies_content_options_callback' ],
			'cookies_content_options'
		);

		add_settings_field(
			'Textarea Warning Text',
			__( 'Enter warning text:', 'cookie-message' ),
			[ $this, 'cookies_textarea_warning_text_callback' ],
			'cookies_content_options',
			'content_options_section'
		);

		add_settings_field(
			'Input Link Text',
			__( 'Enter link text:', 'cookie-message' ),
			[ $this, 'cookies_input_link_text_callback' ],
			'cookies_content_options',
			'content_options_section'
		);

		add_settings_field(
			'Input Button Text',
			__( 'Enter button text:', 'cookie-message' ),
			[ $this, 'cookies_input_button_text_callback' ],
			'cookies_content_options',
			'content_options_section'
		);

		register_setting(
			'cookies_content_options',
			'cookies_content_options',
			[ $this, 'cookies_validate_options' ]
		);
	}

	public function cookies_content_options_callback() {
		echo '<p>' . esc_html_e( 'Enter the content in the cookie message.', 'cookie-message' ) . '</p>';
	}

	public function cookies_textarea_warning_text_callback() {
		$options = get_option( 'cookies_content_options' );

		echo '<textarea id="textarea_warning_text" name="cookies_content_options[textarea_warning_text]" rows="5" cols="50">' . $options['textarea_warning_text'] . '</textarea>';
	}

	public function cookies_input_link_text_callback() {
		$options = get_option( 'cookies_content_options' );

		echo '<input type="text" id="input_link_text" name="cookies_content_options[input_link_text]" value="' . $options['input_link_text'] . '" />';
	}

	public function cookies_input_button_text_callback() {
		$options = get_option( 'cookies_content_options' );

		echo '<input type="text" id="input_button_text" name="cookies_content_options[input_button_text]" value="' . $options['input_button_text'] . '" />';
	}

}
