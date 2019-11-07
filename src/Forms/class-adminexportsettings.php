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
class AdminExportSettings extends AdminBase {

	use AdminTrait;

	/**
	 * Singlenton.
	 *
	 * @var AdminExportSettings
	 */
	static protected $single;

	/**
	 * Settings Sections.
	 *
	 * @var string
	 */
	protected $section_page = 'export_settings';

	/**
	 * AdminExportSettings constructor.
	 */
	public function __construct() {
		parent::__construct();
		add_action( 'admin_init', [ $this, 'cookies_initialize_export_settings' ] );
	}

	/**
	 * Access to the single instance of the class.
	 *
	 * @since 2.0.0
	 *
	 * @return AdminExportSettings
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
	public function cookies_initialize_export_settings() {

		add_settings_section( 'export_settings', esc_html__( 'Export/Import Settings', 'custom-cookie-message' ), [ $this, 'cookies_export_settings_callback' ], $this->section_page );

		add_settings_field( 'export_value', esc_html__( ' ', 'custom-cookie-message' ), [ $this, 'cookies_export_value_callback' ], $this->section_page, 'export_settings' );

		add_settings_field( 'import_value', esc_html__( ' ', 'custom-cookie-message' ), [ $this, 'cookies_import_value_callback' ], $this->section_page, 'export_settings' );

	}

	/**
	 * Description.
	 */
	public function cookies_export_settings_callback() {
		echo '<p>' . esc_html_e( 'Here you can export and/or import your settings.', 'custom-cookie-message' ) . '</p>';
	}

	/**
	 * Export settings.
	 */
	public function cookies_export_value_callback() {
		echo '<h1>' . esc_html_e( 'Export settings', 'custom-cookie-message' ) . '</h1><textarea id="textarea_warning_text" name="custom_cookie_message[export]" rows="10" cols="150">' . serialize( $this->options ) . '</textarea>'; // WPCS: XSS ok.
	}


	/**
	 * Import settings.
	 */
	public function cookies_import_value_callback() {
		echo '<h1>' . esc_html_e( 'Import settings', 'custom-cookie-message' ) . '</h1><textarea id="textarea_warning_text" name="custom_cookie_message[import]" rows="10" cols="150"></textarea>'; // WPCS: XSS ok.
	}

}
