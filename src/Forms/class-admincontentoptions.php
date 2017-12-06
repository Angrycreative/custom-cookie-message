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
	 * Option Group.
	 *
	 * @var string
	 */
	protected $option_group = 'custom_cookie_message';

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

		add_settings_section( 'content', esc_html__( 'Content Options', 'custom-cookie-message' ), [ $this, 'cookies_content_options_callback' ], $this->section_page );

		add_settings_field( 'Textarea Warning Text', esc_html__( 'Enter warning text:', 'custom-cookie-message' ), [ $this, 'cookies_textarea_warning_text_callback' ], $this->section_page, 'content' );

		add_settings_field( 'Input Link Text', esc_html__( 'Enter link text:', 'custom-cookie-message' ), [ $this, 'cookies_input_link_text_callback' ], $this->section_page, 'content' );

		add_settings_field( 'Input Button Text', esc_html__( 'Enter button text:', 'custom-cookie-message' ), [ $this, 'cookies_input_button_text_callback' ], $this->section_page, 'content' );

	}

	/**
	 * Description.
	 */
	public function cookies_content_options_callback() {
		echo '<p>' . esc_html_e( 'Enter the content in the cookie message.', 'cookie-message' ) . '</p>';
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
		echo '<input type="text" id="input_link_text" name="custom_cookie_message[content][input_link_text]" value="' . $this->options['content']['input_link_text'] . '" />'; // WPCS: XSS ok.
	}

	/**
	 * Button text.
	 */
	public function cookies_input_button_text_callback() {
		echo '<input type="text" id="input_button_text" name="custom_cookie_message[content][input_button_text]" value="' . $this->options['content']['input_button_text'] . '" />'; // WPCS: XSS ok.
	}

}
