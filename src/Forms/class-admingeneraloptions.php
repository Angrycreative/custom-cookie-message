<?php
/**
 * AdminGeneralOptions
 *
 * @package CustomCookieMessage\Forms
 */

namespace CustomCookieMessage\Forms;

/**
 * Class AdminGeneralOptions
 *
 * @package CustomCookieMessage
 */
class AdminGeneralOptions extends AdminBase {

	use AdminTrait;

	/**
	 * Singlenton.
	 *
	 * @var AdminGeneralOptions
	 */
	static protected $single;

	/**
	 * Settings Sections.
	 *
	 * @var string
	 */
	protected $section_page = 'general_options';

	/**
	 * AdminGeneralOptions constructor.
	 */
	public function __construct() {
		parent::__construct();
		add_action( 'admin_init', [ $this, 'cookies_initialize_general_options' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enhanced_select_field' ] );
	}

	/**
	 * Access to the single instance of the class.
	 *
	 * @since 2.0.0
	 *
	 * @return AdminGeneralOptions
	 */
	public static function single() {
		if ( empty( self::$single ) ) {
			self::$single = new self();
		}

		return self::$single;
	}

	/**
	 * Add select2 scripts
	 */
	public function enhanced_select_field() {
		wp_register_style( 'select2css', '//cdnjs.cloudflare.com/ajax/libs/select2/3.4.8/select2.css', false, '1.0', 'all' );
		wp_register_script( 'select2js', '//cdnjs.cloudflare.com/ajax/libs/select2/3.4.8/select2.js', array( 'jquery' ), '1.0', true );
		wp_enqueue_style( 'select2css' );
		wp_enqueue_script( 'select2js' );
		$inline_js = 'jQuery(function($){ $("select").select2(); });';
		wp_add_inline_script( 'select2js', $inline_js );
	}
	/**
	 * Define settings.
	 */
	public function cookies_initialize_general_options() {

		add_settings_section( 'general', esc_html__( 'General Options', 'custom-cookie-message' ), [ $this, 'cookies_general_options_callback' ], $this->section_page );

		add_settings_field( 'life_time', esc_html__( 'Life Time Cookie:', 'custom-cookie-message' ), [ $this, 'cookies_life_time_callback' ], $this->section_page, 'general' );

		add_settings_field( 'location_options', esc_html__( 'Select location of message:', 'custom-cookie-message' ), [ $this, 'cookies_select_position_callback' ], $this->section_page, 'general' );

		add_settings_field( 'button_options', esc_html__( 'Close button type:', 'custom-cookie-message' ), [ $this, 'cookies_close_button_callback' ], $this->section_page, 'general' );

		add_settings_field( 'cookies_about_page', esc_html__( 'About cookies page:', 'custom-cookie-message' ), [ $this, 'cookies_about_page_callback' ], $this->section_page, 'general' );

	}

	/**
	 * Description Page
	 */
	public function cookies_general_options_callback() {
		echo '<p>' . esc_html_e( 'Select where the cookie message should be displayed and enter the URL to the page about cookies.', 'custom-cookie-message' ) . '</p>';
	}

	/**
	 * Life Time Cookie.
	 */
	public function cookies_life_time_callback() {
		$val = isset( $this->options['general']['life_time'] ) ? $this->options['general']['life_time'] : '0';
		echo '<input type="text" id="life_time_slider_amount" name="custom_cookie_message[general][life_time]" value="' . $val . '" readonly class="hidden regular-text ltr">'; // WPCS: XSS ok.
		echo '<span class="life_time_message"></span><div id="life_time_slider" class="slider"></div>';
	}

	/**
	 * Location message field.
	 */
	public function cookies_select_position_callback() {

		$html  = '<select id="location_options" name="custom_cookie_message[general][location_options]">';
		$html .= '<option value="top-fixed"' . selected( $this->options['general']['location_options'], 'top-fixed', false ) . '>' . __( 'Top as overlay', 'cookie-message' ) . '</option>';
		$html .= '<option value="bottom-fixed"' . selected( $this->options['general']['location_options'], 'bottom-fixed', false ) . '>' . __( 'Bottom as overlay', 'cookie-message' ) . '</option>';
		$html .= '</select>';

		echo $html; // WPCS: XSS ok.
	}

	/**
	 * Link page field.
	 */
	public function cookies_page_link_callback() {
		echo '<input type="text" id="cookies_page_link" name="custom_cookie_message[general][cookies_page_link]" value="' . $this->options['general']['cookies_page_link'] . '" placeholder="' . esc_html__( 'Paste URL or type to search', 'custom-cookie-message' ) . '" class="form-input-tip ui-autocomplete-input regular-text ltr" role="combobox" aria-autocomplete="list" aria-expanded="false" />'; // WPCS: XSS ok.
	}

}
