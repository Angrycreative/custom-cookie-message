<?php
/**
 * AdminCookieSettings
 *
 * @package CustomCookieMessage\Forms
 */

namespace CustomCookieMessage\Forms;

/**
 * Cookie List Form.
 *
 * @package CustomCookieMessage\Forms
 */
class AdminCookieSettings extends AdminBase {

	use AdminTrait;

	/**
	 * Class AdminCookieOptions
	 *
	 * @var AdminCookieSettings
	 */
	static protected $single;

	/**
	 * Settings Sections.
	 *
	 * @var string
	 */
	protected $section_page = 'cookie_settings';

	/**
	 * CookieList constructor.
	 */
	public function __construct() {
		parent::__construct();
		add_action( 'admin_init', [ $this, 'cookies_initialize_cookie_options' ] );
	}

	/**
	 * Access to the single instance of the class.
	 *
	 * @since 2.0.0
	 *
	 * @return AdminCookieSettings
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
	public function cookies_initialize_cookie_options() {

		add_settings_section( 'cookie_settings_section', esc_html__( 'Cookie List Options', 'custom-cookie-message' ), [ $this, 'cookie_list_options_callback' ], $this->section_page );

		add_settings_field( 'cookie_list', esc_html__( 'Cookie we found:', 'custom-cookie-message' ), [ $this, 'cookie_message_height_slider_callback' ], $this->section_page, 'cookie_settings_section' );
	}

	/**
	 * List options.
	 */
	public function cookie_list_options_callback() {
		echo '<p>' . esc_html_e( 'Label and select priority.', 'cookie-message' ) . '</p>';
	}

	/**
	 * Options.
	 */
	public function cookie_message_height_slider_callback() {

		$options_priority = [
			esc_html__( 'Necesary Cookies', 'custom-cookie-message' ),
			esc_html__( 'Performance Cookies', 'custom-cookie-message' ),
			esc_html__( 'Commercial Cookies', 'custom-cookie-message' ),
		];

		setrawcookie( 'fr', '', 0, '/', '.facebook.com', true, true );

		// TODO: Map $options.

		$output = '<div class="cookie_list_wrapper">';
		$output .= '</div>';

		echo $output;
	}

}
