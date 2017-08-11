<?php

namespace CustomCookieMessage\Forms;

class StylingOptions {

	static protected $instance;

	/**
	 * CookieList constructor.
	 */
	public function __construct() {
		add_action( 'admin_init', [ $this, 'cookies_initialize_styling_options' ] );
	}

	/**
	 * Access to the single instance of the class.
	 *
	 * @since 2.0.0
	 *
	 * @return StylingOptions
	 */
	static public function instance() {
		if ( empty( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function getSection() {
		settings_fields( 'styling_options_section' );
		do_settings_sections( 'cookies_styling_options' );
	}

	public function cookies_initialize_styling_options() {

		add_settings_section(
			'styling_options_section',
			__( 'Styling Options', 'cookie-message' ),
			[ $this, 'cookies_styling_options_callback' ],
			'cookies_styling_options'
		);

		add_settings_field(
			'Background Color',
			__( 'Message container background', 'cookie-message' ),
			[ $this, 'cookies_message_color_picker_callback' ],
			'cookies_styling_options',
			'styling_options_section' );

		add_settings_field(
			'Message Size',
			__( 'Message container padding top and bottom', 'cookie-message' ),
			[ $this, 'cookies_message_height_slider_callback' ],
			'cookies_styling_options',
			'styling_options_section' );

		add_settings_field(
			'Opacity',
			__( 'Message container opacity', 'cookie-message' ),
			[ $this, 'cookies_opacity_slider_callback' ],
			'cookies_styling_options',
			'styling_options_section' );

		add_settings_field(
			'text_font',
			__( 'Text font', 'cookie-message' ),
			[ $this, 'cookies_text_font_callback' ],
			'cookies_styling_options',
			'styling_options_section' );

		add_settings_field(
			'Text Color',
			__( 'Text Color', 'cookie-message' ),
			[ $this, 'cookies_text_color_picker_callback' ],
			'cookies_styling_options',
			'styling_options_section' );

		add_settings_field(
			'Link Color',
			__( 'Link Color', 'cookie-message' ),
			[ $this, 'cookies_link_color_picker_callback' ],
			'cookies_styling_options',
			'styling_options_section' );

		add_settings_field(
			'add_button_class',
			__( 'Button classes', 'cookie-message' ),
			[ $this, 'cookies_add_button_class_callback' ],
			'cookies_styling_options',
			'styling_options_section' );

		add_settings_field(
			'Button Color',
			__( 'Button Color', 'cookie-message' ),
			[ $this, 'cookies_button_color_picker_callback' ],
			'cookies_styling_options',
			'styling_options_section' );

		add_settings_field(
			'Button Hover Color',
			__( 'Button Hover Color', 'cookie-message' ),
			[ $this, 'cookies_button_hover_color_picker_callback' ],
			'cookies_styling_options',
			'styling_options_section' );

		add_settings_field(
			'Button Text Color',
			__( 'Button Text Color', 'cookie-message' ),
			[ $this, 'cookies_button_text_color_picker_callback' ],
			'cookies_styling_options',
			'styling_options_section' );

		add_settings_field(
			'Button_height',
			__( 'Button Height', 'cookie-message' ),
			[ $this, 'cookies_button_height_slider_callback' ],
			'cookies_styling_options',
			'styling_options_section' );

		add_settings_field(
			'button_width',
			__( 'Button Width', 'cookie-message' ),
			[ $this, 'cookies_button_width_slider_callback' ],
			'cookies_styling_options',
			'styling_options_section' );

		register_setting(
			'cookies_styling_options',
			'cookies_styling_options',
			[ Admin::instance(), 'cookies_validate_options' ]
		);
	}

	public function cookies_message_color_picker_callback() {
		$options = get_option( 'cookies_styling_options' );

		$val = ( isset( $options['message_color_picker'] ) ) ? $options['message_color_picker'] : '';
		echo '<input type="text" id="message_color_picker" name="cookies_styling_options[message_color_picker]" value="' . $val . '" class="cpa-color-picker" >';
	}

	public function cookies_button_color_picker_callback() {
		$options = get_option( 'cookies_styling_options' );

		$val = ( isset( $options['button_color_picker'] ) ) ? $options['button_color_picker'] : '';
		echo '<input type="text" id="button_color_picker" name="cookies_styling_options[button_color_picker]" value="' . $val . '" class="cpa-color-picker" >';
	}

	public function cookies_button_hover_color_picker_callback() {
		$options = get_option( 'cookies_styling_options' );

		$val = ( isset( $options['button_hover_color_picker'] ) ) ? $options['button_hover_color_picker'] : '';
		echo '<input type="text" id="button_hover_color_picker" name="cookies_styling_options[button_hover_color_picker]" value="' . $val . '" class="cpa-color-picker" >';
	}

	public function cookies_button_text_color_picker_callback() {
		$options = get_option( 'cookies_styling_options' );

		//$val = ( isset( $options['button_text_color_picker'] ) ) ? $options['button_text_color_picker'] : '';
		$val = $options['button_text_color_picker'];
		echo '<input type="text" id="button_text_color_picker" name="cookies_styling_options[button_text_color_picker]" value="' . $val . '" class="cpa-color-picker" >';
	}

	public function cookies_text_color_picker_callback() {
		$options = get_option( 'cookies_styling_options' );

		$val = ( isset( $options['text_color_picker'] ) ) ? $options['text_color_picker'] : '';
		echo '<input type="text" id="text_color_picker" name="cookies_styling_options[text_color_picker]" value="' . $val . '" class="cpa-color-picker" >';
	}

	public function cookies_link_color_picker_callback() {
		$options = get_option( 'cookies_styling_options' );

		$val = ( isset( $options['link_color_picker'] ) ) ? $options['link_color_picker'] : '';
		echo '<input type="text" id="link_color_picker" name="cookies_styling_options[link_color_picker]" value="' . $val . '" class="cpa-color-picker" >';
	}

	public function cookies_text_font_callback() {
		$options = get_option( 'cookies_styling_options' );

		$val = ( isset( $options['text_font'] ) ) ? $options['text_font'] : '';
		echo '<input type="text" id="text_font" name="cookies_styling_options[text_font]" value="' . $val . '" />';
		echo '<div><p>Replace your standard paragraph font-family. Leave empty for the standard font-family</p></div>';
	}

	public function cookies_add_button_class_callback() {
		$options = get_option( 'cookies_styling_options' );

		$val = ( isset( $options['add_button_class'] ) ) ? $options['add_button_class'] : '';
		echo '<input type="text" id="add_button_class" name="cookies_styling_options[add_button_class]" value="' . $val . '" />';
		echo '<div><p>Replace the standard styling of the button by specifying your own class. If several classes, separate with space. Leave empty to keep the standard styling.</p></div>';
	}

	public function cookies_opacity_slider_callback() {
		$options = get_option( 'cookies_styling_options' );

		$val = ( isset( $options['opacity_slider_amount'] ) ) ? $options['opacity_slider_amount'] : '100';
		echo '<input type="text" id="opacity_slider_amount" name="cookies_styling_options[opacity_slider_amount]" value="' . $val . '" readonly style="border:0; color:#f6931f; font-weight:bold;">';
		echo '<div id="opacity_slider"></div>';

	}

	public function cookies_message_height_slider_callback() {
		$options = get_option( 'cookies_styling_options' );

		$val = ( isset( $options['message_height_slider_amount'] ) ) ? $options['message_height_slider_amount'] : '10';
		echo '<input type="text" id="message_height_slider_amount" name="cookies_styling_options[message_height_slider_amount]" value="' . $val . '" readonly style="border:0; color:#f6931f; font-weight:bold;">';
		echo '<div id="message_height_slider"></div>';
	}

	public function cookies_button_height_slider_callback() {
		$options = get_option( 'cookies_styling_options' );

		$val = ( isset( $options['button_height_slider_amount'] ) ) ? $options['button_height_slider_amount'] : '5';
		echo '<input type="text" id="button_height_slider_amount" name="cookies_styling_options[button_height_slider_amount]" value="' . $val . '" readonly style="border:0; color:#f6931f; font-weight:bold;">';
		echo '<div id="button_height_slider"></div>';
	}

	public function cookies_button_width_slider_callback() {
		$options = get_option( 'cookies_styling_options' );

		$val = ( isset( $options['button_width_slider_amount'] ) ) ? $options['button_width_slider_amount'] : '10';
		echo '<input type="text" id="button_width_slider_amount" name="cookies_styling_options[button_width_slider_amount]" value="' . $val . '" readonly style="border:0; color:#f6931f; font-weight:bold;">';
		echo '<div id="button_width_slider"></div>';
	}

	public function cookies_styling_options_callback() {
		echo '<p>' . esc_html_e( 'Select the styling for the cookie message.', 'cookie-message' ) . '</p>';
	}

}
