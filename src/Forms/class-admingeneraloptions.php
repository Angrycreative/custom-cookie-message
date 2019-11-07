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

		add_settings_field( 'enable_mode_rewrite', esc_html__( 'Enable Mode Rewrite', 'custom-cookie-message' ), [ $this, 'enable_mode_rewrite_callback' ], $this->section_page, 'general' );

		add_settings_field( 'life_time', esc_html__( 'Cookie Expiry:', 'custom-cookie-message' ), [ $this, 'cookies_life_time_callback' ], $this->section_page, 'general' );

		add_settings_field( 'location_options', esc_html__( 'Select location of message:', 'custom-cookie-message' ), [ $this, 'cookies_select_position_callback' ], $this->section_page, 'general' );

		add_settings_field( 'button_options', esc_html__( 'Close button type:', 'custom-cookie-message' ), [ $this, 'cookies_close_button_callback' ], $this->section_page, 'general' );

		add_settings_field( 'cookies_about_page', esc_html__( 'Choose cookies page:', 'custom-cookie-message' ), [ $this, 'cookies_about_page_callback' ], $this->section_page, 'general' );

		add_settings_field( 'cookies_page_link', esc_html__( 'About cookies Link:', 'custom-cookie-message' ), [ $this, 'cookies_page_link_callback' ], $this->section_page, 'general' );

	}

	/**
	 * Description Page
	 */
	public function cookies_general_options_callback() {
		echo '<p>' . esc_html_e( 'Select where the cookie message should be displayed and enter the URL to the page about cookies.', 'custom-cookie-message' ) . '</p><br>';
	}

	/**
	 * Life Time Cookie.
	 */
	public function cookies_life_time_callback() {
		$val = isset( $this->options['general']['life_time'] ) ? $this->options['general']['life_time'] : '0';
		echo '<input type="text" id="life_time_slider_amount" name="custom_cookie_message[general][life_time]" value="' . $val . '" readonly class="hidden regular-text ltr">'; // WPCS: XSS ok.
		echo '<span class="life_time_message"></span><div id="life_time_slider" class="slider"></div>
		<p class="description"> ' . __( 'The period that the cookie is set for', 'custom-cookie-message' ) . '</p>';
	}

	/**
	 * Location message field.
	 */
	public function cookies_select_position_callback() {

		$html  = '<select id="location_options" class="regular-text" name="custom_cookie_message[general][location_options]">';
		$html .= '<option value="top-fixed"' . selected( $this->options['general']['location_options'], 'top-fixed', false ) . '>' . __( 'Top as overlay', 'custom-cookie-message' ) . '</option>';
		$html .= '<option value="bottom-fixed"' . selected( $this->options['general']['location_options'], 'bottom-fixed', false ) . '>' . __( 'Bottom as overlay', 'custom-cookie-message' ) . '</option>';
		$html .= '</select>
		<p class="description"> ' . __( 'Where the notification should appear', 'custom-cookie-message' ) . ' </p>';

		echo $html; // WPCS: XSS ok.
	}

	/**
	 * Close Button Type.
	 */
	public function cookies_close_button_callback() {

		$html  = '<select id="close_button" class="regular-text" name="custom_cookie_message[general][close_button]">';
		$html .= '<option value="xbutton"' . selected( $this->options['general']['close_button'], 'xbutton', false ) . '>' . __( 'Use X Close', 'custom-cookie-message' ) . '</option>';
		$html .= '<option value="textvalue"' . selected( $this->options['general']['close_button'], 'textvalue', false ) . '>' . __( 'accept button', 'custom-cookie-message' ) . '</option>';
		$html .= '</select>
		<p class="description">'. __( 'Select accept button or X icon instead (for client to be able to remove the cookie message)', 'custom-cookie-message' ) .'</p>';

		echo $html; // WPCS: XSS ok.
	}

	/**
	 * About cookies page field.
	 */
	public function cookies_about_page_callback() {
		$html = '<select id="cookies_about_page" class="regular-text" name="custom_cookie_message[general][cookies_about_page]">';
		if ( $pages = get_pages() ) {
			foreach ( $pages as $page ) {
				$html .= '<option value="' . $page->ID . '" ' . selected( $page->ID, $this->options['general']['cookies_about_page'], false ) . '>' . $page->post_title . '</option>';
			}
		}
		$html .= '</select>
		<p class="description"> ' . __( 'The page containing further information about your cookie policy','custom-cookie-message' ) . '</p>';
		echo $html; // WPCS: XSS ok.
	}

	/**
	 * Link page field.
	 */
	public function cookies_page_link_callback() {
		echo '<input type="text" id="cookies_page_link" name="custom_cookie_message[general][cookies_page_link]" value="' . $this->options['general']['cookies_page_link'] . '" placeholder="' . esc_html__( 'Paste URL or type to search', 'custom-cookie-message' ) . '" class="form-input-tip ui-autocomplete-input regular-text ltr" role="combobox" aria-autocomplete="list" aria-expanded="false" />
		<p class="description"> ' . __( 'This link will override the cookie page link above', 'custom-cookie-message' ) . '</p>'; // WPCS: XSS ok.
	}

	/**
	 * Scroll content
	 */
	public function enable_mode_rewrite_callback() {
		$checked = isset( $this->options['general']['enable_mode_rewrite'] ) ? 'checked="checked"' : '';
		echo '<input type="checkbox" id="enable_mode_rewrite" name="custom_cookie_message[general][enable_mode_rewrite]" value="yes"' . $checked . ' class="checkbox" >'; // WPCS: XSS ok.
		echo '<label for="enable_mode_rewrite">'. esc_html__( 'Select this if you have wordpress multisite network.', 'custom-cookie-message' ) . '</label>';
	}
}
