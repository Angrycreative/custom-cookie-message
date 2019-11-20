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

		add_settings_section(
			'cookie_settings_section',
			esc_html__( 'Cookie Settings', 'custom-cookie-message' ),
			[
				$this,
				'cookie_list_options_callback',
			],
			$this->section_page
		);

		add_settings_field(
			'opt_in_opt_out',
			esc_html__( 'Opt-in by default', 'custom-cookie-message' ),
			[
				$this,
				'opt_in_opt_out_callback',
			],
			$this->section_page,
			'cookie_settings_section'
		);


		add_settings_field(
			'headline',
			esc_html__( 'Head Line Message:', 'custom-cookie-message' ),
			[
				$this,
				'cookie_headline_callback',
			],
			$this->section_page,
			'cookie_settings_section'
		);

		add_settings_field(
			'required_cookies',
			esc_html__( 'Required Cookies Message:', 'custom-cookie-message' ),
			[
				$this,
				'cookie_required_callback',
			],
			$this->section_page,
			'cookie_settings_section'
		);

		add_settings_field(
			'functional_cookies',
			esc_html__( 'Functional Cookies Message:', 'custom-cookie-message' ),
			[
				$this,
				'cookie_functional_callback',
			],
			$this->section_page,
			'cookie_settings_section'
		);

		add_settings_field(
			'advertising_cookies',
			esc_html__( 'Advertising Cookies Message:', 'custom-cookie-message' ),
			[
				$this,
				'cookie_advertising_callback',
			],
			$this->section_page,
			'cookie_settings_section'
		);
	}

	/**
	 * List options.
	 */
	public function cookie_list_options_callback() {
		echo '<p>' . esc_html_e( 'Update the content displayed to the user when the user click Change "Settings button" ', 'custom-cookie-message' ) . '</p>';
	}

	/**
	 *
	 */
	public function opt_in_opt_out_callback() {
		$checked = isset( $this->options['cookie_granularity_settings']['opt_in_opt_out'] ) ? 'checked="checked"' : '';

		$lable = ! empty( $checked ) ? esc_html_e( 'Enabled', 'custom-cookie-message' ) : esc_html_e( 'Disabled', 'custom-cookie-message' );
		echo '<label class="opt-in_opt-out__checkbox switch">' . esc_attr( $lable ) . '<input type="checkbox" id="opt-in_opt-out" ' . esc_attr( $checked ) . ' name="custom_cookie_message[cookie_granularity_settings][opt_in_opt_out]">
						<span></span>
					</label>
					<p class="description">' . esc_html__( 'If this is enabled, non-functional cookies will not be saved until the user agrees to it. This may cause the site to break, if you rely on cookies that are used for advertising/marketing/tracking etc.', 'custom-cookie-message' ) . '</p>';
	}

	/**
	 * Required Cookies Message.
	 */
	public function cookie_headline_callback() {
		echo '<input type="text" id="cookies_page_link" name="custom_cookie_message[cookie_granularity_settings][headline]" value="' . $this->options['cookie_granularity_settings']['headline'] . '" class="regular-text ltr" />'; // WPCS: XSS ok.
	}

	/**
	 * Required Cookies Message.
	 */
	public function cookie_required_callback() {
		$html  = '<br><label>';
		$html .= esc_html__( 'These cookies are required to enable core site functionality, we can not disable anything here.', 'custom-cookie-message' );
		$html .= '</label>';

		wp_editor(
			$this->options['cookie_granularity_settings']['required_cookies_message'],
			'required_cookies_message',
			[
				'teeny'         => true,
				'textarea_name' => 'custom_cookie_message[cookie_granularity_settings][required_cookies_message]',
			]
		);
		echo $html; // WPCS: XSS ok.
	}

	/**
	 * Required Cookies Message.
	 */
	public function cookie_functional_callback() {
		$html  = '<br><label>';
		$html .= esc_html__( 'These cookies allow us to analyze site usage so we can measure and improve performance. Example, hotjar', 'custom-cookie-message' ) . '<br>';
		$html .= '<input id="functional_cookies_ban" placeholder="hotjar, analytics" name="custom_cookie_message[cookie_granularity_settings][functional_list]" value="' . $this->options['cookie_granularity_settings']['functional_list'] . '" class="large-text ltr">';
		$html .= '</label>';

		wp_editor(
			$this->options['cookie_granularity_settings']['functional_cookies_message'],
			'functional_cookies_message',
			[
				'teeny'         => true,
				'textarea_name' => 'custom_cookie_message[cookie_granularity_settings][functional_cookies_message]',
			]
		);
		echo $html; // WPCS: XSS ok.
	}

	/**
	 * Required Cookies Message.
	 */
	public function cookie_advertising_callback() {
		$html  = '<br><label>';
		$html .= esc_html__( 'These cookies are used by advertising companies to serve ads that are relevant to your interests. Example, Doubleclick', 'custom-cookie-message' ) . '<br>';
		$html .= '<input id="advertising_cookies_ban" placeholder="doubleclick, adsense" name="custom_cookie_message[cookie_granularity_settings][advertising_list]" value="' . $this->options['cookie_granularity_settings']['advertising_list'] . '" class="large-text ltr">';
		$html .= '</label>';
		$html .= '<p class="description"> ' . __( 'We have these cookies in our default list: _ga,_gid,_hjIncludedInSample,_hjid,1P_JAR,APISID,CONSENT,HSID,NID,SAPISID,SEARCH_SAMESITE,SID,SIDCC,SSID,UULE', 'custom-cookie-message' ) . '</p>';

		wp_editor(
			$this->options['cookie_granularity_settings']['advertising_cookies_message'],
			'advertising_cookies_message',
			[
				'teeny'         => true,
				'textarea_name' => 'custom_cookie_message[cookie_granularity_settings][advertising_cookies_message]',
			]
		);
		echo $html; // WPCS: XSS ok.
	}

}
