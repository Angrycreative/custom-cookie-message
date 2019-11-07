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

		add_settings_field( 'message_color_picker', esc_html__( 'Message container background', 'custom-cookie-message' ), [ $this, 'cookies_message_color_picker_callback' ], $this->section_page, 'styling' );

		add_settings_field( 'message_height_slider_amount', esc_html__( 'Message container padding top and bottom', 'custom-cookie-message' ), [ $this, 'cookies_message_height_slider_callback' ], $this->section_page, 'styling' );

		add_settings_field( 'opacity_slider_amount', esc_html__( 'Message container opacity', 'custom-cookie-message' ), [ $this, 'cookies_opacity_slider_callback' ], $this->section_page, 'styling' );


		add_settings_field( 'cookie_banner_animation', esc_html__( 'Banner Animation', 'custom-cookie-message' ), [ $this, 'cookies_banner_animation_callback' ], $this->section_page, 'styling' );
		add_settings_field( 'text_font', esc_html__( 'Text font family', 'custom-cookie-message' ), [ $this, 'cookies_text_font_callback' ], $this->section_page, 'styling' );

		add_settings_field( 'text_size', esc_html__( 'Text size', 'custom-cookie-message' ), [ $this, 'cookies_text_size_callback' ], $this->section_page, 'styling' );

		add_settings_field( 'text_color_picker', esc_html__( 'Text Color', 'custom-cookie-message' ), [ $this, 'cookies_text_color_picker_callback' ], $this->section_page, 'styling' );

		add_settings_field( 'link_color_picker', esc_html__( 'Link Color', 'custom-cookie-message' ), [ $this, 'cookies_link_color_picker_callback' ], $this->section_page, 'styling' );

		add_settings_field( 'Close_color_picker', esc_html__( 'Close icon color', 'custom-cookie-message' ), [ $this, 'cookies_close_color_picker_callback' ], $this->section_page, 'styling' );

		add_settings_section( 'modal', esc_html__( 'Modal Options', 'custom-cookie-message' ), [ $this, 'cookies_modal_options_callback' ], $this->section_page );

		add_settings_field( 'modal_overlay', esc_html__( 'Modal Overlay', 'custom-cookie-message' ), [ $this, 'cookies_modal_overlay_callback' ], $this->section_page, 'modal' );

		add_settings_field( 'modal_overlay_opacity', esc_html__( 'Modal Overlay Opacity', 'custom-cookie-message' ), [ $this, 'cookies_modal_overlay_opacity_callback' ], $this->section_page, 'modal' );

		add_settings_section( 'button', esc_html__( 'Button Options', 'custom-cookie-message' ), [ $this, 'cookies_button_options_callback' ], $this->section_page );

		add_settings_field( 'button_custom_class', esc_html__( 'Button Custom Class', 'custom-cookie-message' ), [ $this, 'cookies_button_custom_class_callback' ], $this->section_page, 'button' );

		add_settings_field( 'button_styling', esc_html__( 'Button Styling', 'custom-cookie-message' ), [ $this, 'cookies_button_styling_callback' ], $this->section_page, 'button' );

		add_settings_field( 'button_color_picker', esc_html__( 'Button Color', 'custom-cookie-message' ), [ $this, 'cookies_button_color_picker_callback' ], $this->section_page, 'button' );

		add_settings_field( 'button_hover_color_picker', esc_html__( 'Button Hover Color', 'custom-cookie-message' ), [ $this, 'cookies_button_hover_color_picker_callback' ], $this->section_page, 'button' );

		add_settings_field( 'button_text_color_picker', esc_html__( 'Button Text Color', 'custom-cookie-message' ), [ $this, 'cookies_button_text_color_picker_callback' ], $this->section_page, 'button' );

		add_settings_field( 'button_hover_text_color_picker', esc_html__( 'Button Hover Text Color', 'custom-cookie-message' ), [ $this, 'cookies_button_hover_text_color_picker_callback' ], $this->section_page, 'button' );

		add_settings_field( 'button_height_slider_amount', esc_html__( 'Button Top and Bottom Padding', 'custom-cookie-message' ), [ $this, 'cookies_button_height_slider_callback' ], $this->section_page, 'button' );

		add_settings_field( 'button_width_slider_amount', esc_html__( 'Button Left and Right Padding', 'custom-cookie-message' ), [ $this, 'cookies_button_width_slider_callback' ], $this->section_page, 'button' );
		add_settings_field( 'xbutton_styling', esc_html__( '[X] Button Styling', 'custom-cookie-message' ), [ $this, 'same_styles_to_close_button_callback' ], $this->section_page, 'button' );
		add_settings_field( 'button_custom_css', esc_html__( 'Custom styles for buttons', 'custom-cookie-message' ), [ $this, 'cookies_btn_custom_styling_callback' ], $this->section_page, 'button' );
	}

	/**
	 * Description Styles Options.
	 */
	public function cookies_styling_options_callback() {
		echo '<p>' . esc_html_e( 'Select the styling for the cookie message.', 'custom-cookie-message' ) . '</p>';
	}

	/**
	 * Background color Picker field.
	 */
	public function cookies_message_color_picker_callback() {
		$val = isset( $this->options['styles']['message_color_picker'] ) ? $this->options['styles']['message_color_picker'] : '#3d3d3d';
		echo '<input type="text" id="message_color_picker" name="custom_cookie_message[styles][message_color_picker]" value="' . $val . '" class="cpa-color-picker" >
		<p class="description"> ' . __( 'The background color for the notification', 'custom-cookie-message' ) . '</p>'; // WPCS: XSS ok.
	}

	/**
	 * Container Padding.
	 */
	public function cookies_message_height_slider_callback() {
		$val = isset( $this->options['styles']['message_height_slider_amount'] ) ? $this->options['styles']['message_height_slider_amount'] : '10';
		echo '<input type="text" id="message_height_slider_amount" name="custom_cookie_message[styles][message_height_slider_amount]" value="' . $val . '" readonly class="hidden">'; // WPCS: XSS ok.
		echo '<div id="message_height_slider" class="slider"><div id="message_height_custom_handle" class="ui-slider-handle ui-slider-handle-custom"></div></div>';
	}

	/**
	 * Opacity.
	 */
	public function cookies_opacity_slider_callback() {
		$val = isset( $this->options['styles']['opacity_slider_amount'] ) ? $this->options['styles']['opacity_slider_amount'] : '100';
		echo '<input type="text" id="opacity_slider_amount" name="custom_cookie_message[styles][opacity_slider_amount]" value="' . $val . '" readonly class="hidden">'; // WPCS: XSS ok.
		echo '<div id="opacity_slider" class="slider"><div id="opacity_slider_handle" class="ui-slider-handle ui-slider-handle-custom"></div></div>';
	}

	/**
	 * Text font family.
	 */
	public function cookies_text_font_callback() {
		$val = isset( $this->options['styles']['text_font'] ) ? $this->options['styles']['text_font'] : '';
		echo '<input type="text" id="text_font" name="custom_cookie_message[styles][text_font]" value="' . $val . '" class="regular-text ltr" />'; // WPCS: XSS ok.
		echo '<div><p>Replace your standard paragraph font-family. Leave empty for the standard font-family</p></div>';
	}

	/**
	 * Text size family.
	 */
	public function cookies_text_size_callback() {
		$val = isset( $this->options['styles']['text_size'] ) ? $this->options['styles']['text_size'] : '';
		echo '<input type="text" id="text_size" name="custom_cookie_message[styles][text_size]" value="' . $val . '" class="regular-text ltr" />'; // WPCS: XSS ok.
		echo '<div><p>Size of the text in the banner and modal</p></div>';
	}

	/**
	 * Color Text.
	 */
	public function cookies_text_color_picker_callback() {
		$val = isset( $this->options['styles']['text_color_picker'] ) ? $this->options['styles']['text_color_picker'] : '';
		echo '<input type="text" id="text_color_picker" name="custom_cookie_message[styles][text_color_picker]" value="' . $val . '" class="cpa-color-picker" >'; // WPCS: XSS ok.
	}

	/**
	 * Color link.
	 */
	public function cookies_link_color_picker_callback() {
		$val = isset( $this->options['styles']['link_color_picker'] ) ? $this->options['styles']['link_color_picker'] : '';
		echo '<input type="text" id="link_color_picker" name="custom_cookie_message[styles][link_color_picker]" value="' . $val . '" class="cpa-color-picker" >'; // WPCS: XSS ok.
	}

	/**
	 * Color close.
	 */
	public function cookies_close_color_picker_callback() {
		$val = isset( $this->options['styles']['close_color_picker'] ) ? $this->options['styles']['close_color_picker'] : '';
		echo '<input type="text" id="close_color_picker" name="custom_cookie_message[styles][close_color_picker]" value="' . $val . '" class="cpa-color-picker" >'; // WPCS: XSS ok.
	}

	/**
	 * Description Styles Options.
	 */
	public function cookies_modal_options_callback() {
		echo '<p>' . esc_html_e( 'Select the styling for the modal overlay.', 'custom-cookie-message' ) . '</p>';
	}

	/**
	 * Modal background.
	 */
	public function cookies_modal_overlay_callback() {
		$val = isset( $this->options['styles']['modal_overlay'] ) ? $this->options['styles']['modal_overlay'] : '#3d3d3d';
		echo '<input type="text" id="modal_overlay" name="custom_cookie_message[styles][modal_overlay]" value="' . $val . '" class="cpa-color-picker" >'; // WPCS: XSS ok.
	}

	/**
	 * Modal opacity.
	 */
	public function cookies_modal_overlay_opacity_callback() {
		$val = isset( $this->options['styles']['modal_overlay_opacity'] ) ? $this->options['styles']['modal_overlay_opacity'] : '50';
		echo '<input type="text" id="modal_overlay_opacity_amount" name="custom_cookie_message[styles][modal_overlay_opacity]" value="' . $val . '" readonly class="hidden">'; // WPCS: XSS ok.
		echo '<div id="modal_overlay_opacity_slider" class="slider"><div id="modal_overlay_opacity_slider_handle" class="ui-slider-handle ui-slider-handle-custom"></div></div>';
	}

	/**
	 * Description Styles Options.
	 */
	public function cookies_button_options_callback() {
		echo '<p>' . esc_html_e( 'Select the styling for the buttons.', 'custom-cookie-message' ) . '</p>';
	}

	/**
	 * Button Class field.
	 */
	public function cookies_button_custom_class_callback() {
		$val = isset( $this->options['styles']['button_custom_class'] ) ? $this->options['styles']['button_custom_class'] : '';
		echo '<input type="text" id="button_custom_class" name="custom_cookie_message[styles][button_custom_class]" value="' . $val . '" class="regular-text ltr" >'; // WPCS: XSS ok.
		echo '<div><p>If you wish to add more then one class seperate them with a blank space</p></div>';
	}

	/**
	 * Button Css checkbox
	 */
	public function cookies_button_styling_callback() {
		$checked = isset( $this->options['styles']['button_styling'] ) ? 'checked="checked"' : '';
		echo '<input type="checkbox" id="button_styling" name="custom_cookie_message[styles][button_styling]" value="yes"' . $checked . ' class="checkbox" >'; // WPCS: XSS ok.
		echo '<label for="button_styling">Yes, use the Custom Cookie Message button styling</label>';
		echo '<div><br><p>If this option is set it\'s likely that any theme button styling used on the page will be overwritten</p></div>';
	}

	/**
	 * Apply same styles to close button
	 */
	public function same_styles_to_close_button_callback() {
		$checked = isset( $this->options['styles']['xclose_styling'] ) ? 'checked="checked"' : '';
		echo '<input type="checkbox" id="xclose_styling" name="custom_cookie_message[styles][xclose_styling]" value="yes"' . $checked . ' class="checkbox" >'; // WPCS: XSS ok.
		echo '<label for="xclose_styling">Yes, use the same styles to [X] close button.</label>';
	}

	/**
	 * Button color.
	 */
	public function cookies_button_color_picker_callback() {
		$val = isset( $this->options['styles']['button_color_picker'] ) ? $this->options['styles']['button_color_picker'] : '';
		echo '<input type="text" id="button_color_picker" name="custom_cookie_message[styles][button_color_picker]" value="' . $val . '" class="cpa-color-picker" >'; // WPCS: XSS ok.
	}

	/**
	 * Button hover color.
	 */
	public function cookies_button_hover_color_picker_callback() {
		$val = isset( $this->options['styles']['button_hover_color_picker'] ) ? $this->options['styles']['button_hover_color_picker'] : '';
		echo '<input type="text" id="button_hover_color_picker" name="custom_cookie_message[styles][button_hover_color_picker]" value="' . $val . '" class="cpa-color-picker" >'; // WPCS: XSS ok.
	}

	/**
	 * Button text color.
	 */
	public function cookies_button_text_color_picker_callback() {
		$val = $this->options['styles']['button_text_color_picker'];
		echo '<input type="text" id="button_text_color_picker" name="custom_cookie_message[styles][button_text_color_picker]" value="' . $val . '" class="cpa-color-picker" >'; // WPCS: XSS ok.
	}

	/**
	 * Button hover text color.
	 */
	public function cookies_button_hover_text_color_picker_callback() {
		$val = $this->options['styles']['button_hover_text_color_picker'];
		echo '<input type="text" id="button_hover_text_color_picker" name="custom_cookie_message[styles][button_hover_text_color_picker]" value="' . $val . '" class="cpa-color-picker" >'; // WPCS: XSS ok.
	}

	/**
	 * Button height.
	 */
	public function cookies_button_height_slider_callback() {
		$val = isset( $this->options['styles']['button_height_slider_amount'] ) ? $this->options['styles']['button_height_slider_amount'] : '5';
		echo '<input type="text" id="button_height_slider_amount" name="custom_cookie_message[styles][button_height_slider_amount]" value="' . $val . '" readonly class="hidden">'; // WPCS: XSS ok.
		echo '<div id="button_height_slider" class="slider"><div id="button_height_handle" class="ui-slider-handle ui-slider-handle-custom"></div></div>';
	}

	/**
	 * Button width.
	 */
	public function cookies_button_width_slider_callback() {
		$val = isset( $this->options['styles']['button_width_slider_amount'] ) ? $this->options['styles']['button_width_slider_amount'] : '10';
		echo '<input type="text" id="button_width_slider_amount" name="custom_cookie_message[styles][button_width_slider_amount]" value="' . $val . '" readonly class="hidden">'; // WPCS: XSS ok.
		echo '<div id="button_width_slider" class="slider"><div id="button_width_handle" class="ui-slider-handle ui-slider-handle-custom"></div></div>';
	}

	/**
	 * Banner Animation.
	 */
	public function cookies_banner_animation_callback() {

		$html  = '<select id="banner_animation" name="custom_cookie_message[styles][banner_animation]">';
		$html .= '<option value="none"' . selected( $this->options['styles']['banner_animation'], 'none', false ) . '>' . __( 'None', 'custom-cookie-message' ) . '</option>';
		$html .= '<option value="scroll"' . selected( $this->options['styles']['banner_animation'], 'scroll', false ) . '>' . __( 'Scroll up/down', 'custom-cookie-message' ) . '</option>';
		$html .= '<option value="fade"' . selected( $this->options['styles']['banner_animation'], 'fade', false ) . '>' . __( 'Fade', 'custom-cookie-message' ) . '</option>';
		$html .= '</select>
		<p class="description">' . __( 'Select the animation about how the message should appear: None - Scroll up/down - Fade','custom-cookie-message' ) . '</p>';

		echo $html; // WPCS: XSS ok.
	}

	/**
	 * Btn custom styling.
	 */
	public function cookies_btn_custom_styling_callback() {
		echo '<textarea id="textarea_btn_custom_styling" name="custom_cookie_message[styles][textarea_btn_custom_styling]" rows="5" cols="50">' . $this->options['styles']['textarea_btn_custom_styling'] . '</textarea>'; // WPCS: XSS ok.
	}
}
