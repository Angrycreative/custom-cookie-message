<?php
/**
 * AdminGeneralOptions
 *
 * @package CustomCookieMessage\Forms
 */

namespace CustomCookieMessage\Forms;

/**
 * Class AdminContentOptions
 *
 * @package CustomCookieMessage\Forms
 */
class AdminContentOptions extends AdminBase {

	use AdminTrait;

	/**
	 * Singlenton.
	 *
	 * @var AdminContentOptions
	 */
	static protected $single;

	/**
	 * Settings Sections.
	 *
	 * @var string
	 */
	protected $section_page = 'content_options';

	/**
	 * CookieList constructor.
	 */
	public function __construct() {
		parent::__construct();
		add_action( 'admin_init', [ $this, 'cookies_initialize_content_options' ] );
	}

	/**
	 * Access to the single instance of the class.
	 *
	 * @since 2.0.0
	 *
	 * @return AdminContentOptions
	 */
	public static function single() {
		if ( empty( self::$single ) ) {
			self::$single = new self();
		}

		return self::$single;
	}

	/**
	 * Settings
	 */
	public function cookies_initialize_content_options() {

		add_settings_section( 'content', esc_html__( 'Content Options', 'custom-cookie-message' ), [
			$this,
			'cookies_content_options_callback'
		], $this->section_page );

		add_settings_field( 'textarea_warning_text', esc_html__( 'Notification Text:', 'custom-cookie-message' ), [
			$this,
			'cookies_textarea_warning_text_callback'
		], $this->section_page, 'content' );

		add_settings_field( 'input_link_text', esc_html__( 'More Info Text:', 'custom-cookie-message' ), [
			$this,
			'cookies_input_link_text_callback'
		], $this->section_page, 'content' );

		add_settings_field( 'or_text_settings', esc_html__( 'Here you can change the word "OR"', 'custom-cookie-message' ), [
			$this,
			'cookies_or_text_settings_callback'
		], $this->section_page, 'content' );


		add_settings_field( 'input_button_text', esc_html__( 'Enter button text:', 'custom-cookie-message' ), [
			$this,
			'cookies_input_button_text_callback'
		], $this->section_page, 'content' );

		add_settings_field( 'accept_button_text', esc_html__( 'Enter Accept button text:', 'custom-cookie-message' ), [
			$this,
			'cookies_accept_button_text_callback'
		], $this->section_page, 'content' );

		add_settings_field( 'save_settings_button', esc_html__( 'Save Settings button text:', 'custom-cookie-message' ), [
			$this,
			'cookies_save_settings_button_callback'
		], $this->section_page, 'content' );

		add_settings_field( 'shortcode_text', esc_html__( 'Shortcode Text:', 'custom-cookie-message' ), [
			$this,
			'cookies_shortcode_text_callback'
		], $this->section_page, 'content' );

		add_settings_field( 'cookies_shortcode', esc_html__( 'Shortcode:', 'custom-cookie-message' ), [
			$this,
			'cookies_shortcode_callback'
		], $this->section_page, 'content' );

	}

	/**
	 * Description.
	 */
	public function cookies_content_options_callback() {
		echo '<p>' . esc_html_e( 'Update the content displayed to the user. ', 'cookie-message' ) . '</p><br>';
	}

	/**
	 * Warning message.
	 */
	public function cookies_textarea_warning_text_callback() {
		echo '<textarea id="textarea_warning_text" name="custom_cookie_message[content][textarea_warning_text]" rows="5" cols="50">' . $this->options['content']['textarea_warning_text'] . '</textarea>'; // WPCS: XSS ok.
	}

	/**
	 * Link Text.
	 */
	public function cookies_input_link_text_callback() {
		echo '<input type="text" id="input_link_text" name="custom_cookie_message[content][input_link_text]" value="' . $this->options['content']['input_link_text'] . '" class="regular-text ltr" />'; // WPCS: XSS ok.
	}

	/**
	 * OR Text.
	 */
	public function cookies_or_text_settings_callback() {
		echo '<input type="text" id="or_text_settings" name="custom_cookie_message[content][or_text_settings]" value="' . $this->options['content']['or_text_settings'] . '" class="regular-text ltr" />'; // WPCS: XSS ok.
	}

	/**
	 * Button text.
	 */
	public function cookies_input_button_text_callback() {
		echo '<input type="text" id="input_button_text" name="custom_cookie_message[content][input_button_text]" value="' . $this->options['content']['input_button_text'] . '" class="regular-text ltr" />'; // WPCS: XSS ok.
	}

	/**
	 * Accept Button text.
	 */
	public function cookies_accept_button_text_callback() {
		echo '<input type="text" id="accept_button_text" name="custom_cookie_message[content][accept_button_text]" value="' . $this->options['content']['accept_button_text'] . '" class="regular-text ltr" />
		<p class="description">' . esc_html__( 'The default text to dismiss the notification.', 'custom-cookie-message' ) . '</p>'; // WPCS: XSS ok.
	}

	/**
	 * Button text.
	 */
	public function cookies_save_settings_button_callback() {
		echo '<input type="text" id="input_button_text" name="custom_cookie_message[content][save_settings_button]" value="' . $this->options['content']['save_settings_button'] . '" class="regular-text ltr" />'; // WPCS: XSS ok.
	}

	/**
	 * Shortcode
	 */
	public function cookies_shortcode_text_callback() {
		echo '<input name="custom_cookie_message[content][shortcode_text]" value="' . $this->options['content']['shortcode_text'] . '" class="regular-text ltr" />'; // WPCS: XSS ok.
	}

	/**
	 * Shortcode
	 */
	public function cookies_shortcode_callback() {
		echo '<div class="cc-admin-shortcode">';
		echo '<div>';
		echo '<input type="text" id="cc-admin-shortcode-link" class="regular-text cc-shortcode" onfocus="this.select();" readonly="readonly" value="[ccm_preferences]" />';
		echo '<p class="description">' . esc_html__( 'Show a LINK for your customers to change their cookie settings', 'custom-cookie-message' ) . '</p>';
		echo '</div>';
		echo '<div>';
		echo '<input type="text" id="cc-admin-shortcode-button" class="regular-text cc-shortcode" onfocus="this.select();" readonly="readonly" value=\'[ccm_preferences style="button"]\' />';
		echo '<p class="description">' . esc_html__( 'Show a BUTTON for your customers to change their cookie settings', 'custom-cookie-message' ) . '</p>';
		echo '</div>';
		echo '</div>';
	}

}
