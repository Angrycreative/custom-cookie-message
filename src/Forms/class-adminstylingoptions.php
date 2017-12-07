<?php
/**
 * AdminStylingOptions
 *
 * @package CustomCookieMessage\Forms
 */

namespace CustomCookieMessage\Forms;

/**
 * Class AdminStylingOptions
 *
 * @package CustomCookieMessage\Forms
 */
class AdminStylingOptions extends AdminBase {

	use AdminTrait;

	/**
	 * Singlenton.
	 *
	 * @var AdminStylingOptions
	 */
	static protected $single;

	/**
	 * Settings Sections.
	 *
	 * @var string
	 */
	protected $section_page = 'styling_options';

	/**
	 * CookieList constructor.
	 */
	public function __construct() {
		parent::__construct();
		add_action( 'admin_init', [ $this, 'cookies_initialize_styling_options' ] );
	}

	/**
	 * Access to the single instance of the class.
	 *
	 * @since 2.0.0
	 *
	 * @return AdminStylingOptions
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
	public function cookies_initialize_styling_options() {

		add_settings_section( 'styling', esc_html__( 'Styling Options', 'custom-cookie-message' ), [ $this, 'cookies_styling_options_callback' ], $this->section_page );

		add_settings_field( 'cookies_styling_options', esc_html__( 'Message container background', 'custom-cookie-message' ), [ $this, 'cookies_message_color_picker_callback' ], $this->section_page, 'styling' );

		add_settings_field( 'Message Size', esc_html__( 'Message container padding top and bottom', 'custom-cookie-message' ), [ $this, 'cookies_message_height_slider_callback' ], $this->section_page, 'styling' );

		add_settings_field( 'Opacity', esc_html__( 'Message container opacity', 'custom-cookie-message' ), [ $this, 'cookies_opacity_slider_callback' ], $this->section_page, 'styling' );

		add_settings_field( 'text_font', esc_html__( 'Text font', 'custom-cookie-message' ), [ $this, 'cookies_text_font_callback' ], $this->section_page, 'styling' );

		add_settings_field( 'Text Color', esc_html__( 'Text Color', 'custom-cookie-message' ), [ $this, 'cookies_text_color_picker_callback' ], $this->section_page, 'styling' );

		add_settings_field( 'Link Color', esc_html__( 'Link Color', 'custom-cookie-message' ), [ $this, 'cookies_link_color_picker_callback' ], $this->section_page, 'styling' );

		add_settings_field( 'add_button_class', esc_html__( 'Button classes', 'custom-cookie-message' ), [ $this, 'cookies_add_button_class_callback' ], $this->section_page, 'styling' );

		add_settings_field( 'Button Color', esc_html__( 'Button Color', 'custom-cookie-message' ), [ $this, 'cookies_button_color_picker_callback' ], $this->section_page, 'styling' );

		add_settings_field( 'Button Hover Color', esc_html__( 'Button Hover Color', 'custom-cookie-message' ), [ $this, 'cookies_button_hover_color_picker_callback' ], $this->section_page, 'styling' );

		add_settings_field( 'cookies_styling_options', esc_html__( 'Button Text Color', 'custom-cookie-message' ), [ $this, 'cookies_button_text_color_picker_callback' ], $this->section_page, 'styling' );

		add_settings_field( 'Button_height', esc_html__( 'Button Height', 'custom-cookie-message' ), [ $this, 'cookies_button_height_slider_callback' ], $this->section_page, 'styling' );

		add_settings_field( 'button_width_slider_amount', esc_html__( 'Button Width', 'custom-cookie-message' ), [ $this, 'cookies_button_width_slider_callback' ], $this->section_page, 'styling' );
	}

	/**
	 * Description Styles Options.
	 */
	public function cookies_styling_options_callback() {
		echo '<p>' . esc_html_e( 'Select the styling for the cookie message.', 'cookie-message' ) . '</p>';
	}

	/**
	 * Color Picker field.
	 */
	public function cookies_message_color_picker_callback() {
		$val = isset( $this->options['styling']['message_color_picker'] ) ? $this->options['styling']['message_color_picker'] : '';
		echo '<input type="text" id="message_color_picker" name="custom_cookie_message[styling][message_color_picker]" value="' . $val . '" class="cpa-color-picker" >';
	}

	public function cookies_button_color_picker_callback() {
		$val = isset( $this->options['styling']['button_color_picker'] ) ? $this->options['styling']['button_color_picker'] : '';
		echo '<input type="text" id="button_color_picker" name="custom_cookie_message[styling][button_color_picker]" value="' . $val . '" class="cpa-color-picker" >';
	}

	public function cookies_button_hover_color_picker_callback() {
		$val = isset( $this->options['styling']['button_hover_color_picker'] ) ? $this->options['styling']['button_hover_color_picker'] : '';
		echo '<input type="text" id="button_hover_color_picker" name="custom_cookie_message[styling][button_hover_color_picker]" value="' . $val . '" class="cpa-color-picker" >';
	}

	public function cookies_button_text_color_picker_callback() {
		$val = $this->options['styling']['button_text_color_picker'];
		echo '<input type="text" id="button_text_color_picker" name="custom_cookie_message[styling][button_text_color_picker]" value="' . $val . '" class="cpa-color-picker" >';
	}

	public function cookies_text_color_picker_callback() {
		$val = isset( $this->options['styling']['text_color_picker'] ) ? $this->options['styling']['text_color_picker'] : '';
		echo '<input type="text" id="text_color_picker" name="custom_cookie_message[styling][text_color_picker]" value="' . $val . '" class="cpa-color-picker" >';
	}

	public function cookies_link_color_picker_callback() {
		$val = isset( $this->options['styling']['link_color_picker'] ) ? $this->options['styling']['link_color_picker'] : '';
		echo '<input type="text" id="link_color_picker" name="custom_cookie_message[styling][link_color_picker]" value="' . $val . '" class="cpa-color-picker" >';
	}

	public function cookies_text_font_callback() {
		$val = isset( $this->options['styling']['text_font'] ) ? $this->options['styling']['text_font'] : '';
		echo '<input type="text" id="text_font" name="custom_cookie_message[styling][text_font]" value="' . $val . '" />';
		echo '<div><p>Replace your standard paragraph font-family. Leave empty for the standard font-family</p></div>';
	}

	public function cookies_add_button_class_callback() {
		$val = isset( $this->options['styling']['add_button_class'] ) ? $this->options['styling']['add_button_class'] : '';
		echo '<input type="text" id="add_button_class" name="custom_cookie_message[styling][add_button_class]" value="' . $val . '" />';
		echo '<div><p>Replace the standard styling of the button by specifying your own class. If several classes, separate with space. Leave empty to keep the standard styling.</p></div>';
	}

	public function cookies_opacity_slider_callback() {
		$val = isset( $this->options['styling']['opacity_slider_amount'] ) ? $this->options['styling']['opacity_slider_amount'] : '100';
		echo '<input type="text" id="opacity_slider_amount" name="custom_cookie_message[styling][opacity_slider_amount]" value="' . $val . '" readonly style="border:0; color:#f6931f; font-weight:bold;">';
		echo '<div id="opacity_slider"></div>';
	}

	public function cookies_message_height_slider_callback() {
		$val = isset( $this->options['styling']['message_height_slider_amount'] ) ? $this->options['styling']['message_height_slider_amount'] : '10';
		echo '<input type="text" id="message_height_slider_amount" name="custom_cookie_message[styling][message_height_slider_amount]" value="' . $val . '" readonly style="border:0; color:#f6931f; font-weight:bold;">';
		echo '<div id="message_height_slider"></div>';
	}

	public function cookies_button_height_slider_callback() {
		$val = isset( $this->options['styling']['button_height_slider_amount'] ) ? $this->options['styling']['button_height_slider_amount'] : '5';
		echo '<input type="text" id="button_height_slider_amount" name="custom_cookie_message[styling][button_height_slider_amount]" value="' . $val . '" readonly style="border:0; color:#f6931f; font-weight:bold;">';
		echo '<div id="button_height_slider"></div>';
	}

	public function cookies_button_width_slider_callback() {
		$val = isset( $this->options['styling']['button_width_slider_amount'] ) ? $this->options['styling']['button_width_slider_amount'] : '10';
		echo '<input type="text" id="button_width_slider_amount" name="custom_cookie_message[styling][button_width_slider_amount]" value="' . $val . '" readonly style="border:0; color:#f6931f; font-weight:bold;">';
		echo '<div id="button_width_slider"></div>';
	}

}
