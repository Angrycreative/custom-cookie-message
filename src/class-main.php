<?php
/**
 * Class Main
 *
 * @package CustomCookieMessage
 */

namespace CustomCookieMessage;

use CustomCookieMessage\Controller\Controller;
use CustomCookieMessage\Controller\RemoveCookie;
use CustomCookieMessage\Forms\AdminForm;

/**
 * Custom Cookie Message.
 *
 * @package CustomCookieMessage
 */
class Main {

	/**
	 * Current version.
	 *
	 * @var string
	 */
	protected $version = '2.4.10';

	/**
	 * Store singlenton CustomCookieMessage\Main.
	 *
	 * @var Main
	 */
	protected static $single;

	/**
	 * Main constructor.
	 */
	public function __construct() {

		spl_autoload_register( [ $this, 'autoload' ] );

		add_action( 'init', [ $this, 'init' ], 30 );

		register_activation_hook( CUSTOM_COOKIE_MESSAGE_FILE, [ __CLASS__, 'plugin_activation' ] );

	}

	/**
	 * Autoloader
	 *
	 * @since 2.0.0
	 *
	 * @param string $class_name Class name loader.
	 */
	public function autoload( $class_name ) {
		if ( ! preg_match( '/^CustomCookieMessage/', $class_name ) ) {
			return;
		}

		$class_normalize = str_replace( '\\', DIRECTORY_SEPARATOR, str_replace( '_', '-', $class_name ) );
		$class_normalize = preg_replace( '#/([a-zA-Z]*)$#', '/class-$1', $class_normalize );
		$class_normalize = explode( '/', $class_normalize );

		array_shift( $class_normalize );
		$class_file_name = array_pop( $class_normalize );

		$class_path = implode( '/', $class_normalize );

		$file_path = empty( $class_path ) ? strtolower( $class_file_name ) . '.php' : $class_path . '/' . strtolower( $class_file_name ) . '.php';

		if ( file_exists( CUSTOM_COOKIE_MESSAGE_DIR . '/src/' . $file_path ) ) {
			include CUSTOM_COOKIE_MESSAGE_DIR . '/src/' . $file_path;
		} else {
			wp_die( 'File does not exists ' . esc_url( CUSTOM_COOKIE_MESSAGE_DIR ) . '/src/' . esc_url( $file_path ) );
		}
	}

	/**
	 * Access to the single instance of the class.
	 *
	 * @return Main
	 */
	public static function single() {
		if ( empty( self::$single ) ) {
			self::$single = new self();
		}

		return self::$single;
	}

	/**
	 * Get the static version.
	 *
	 * @return string Version self::$version.
	 */
	public static function version() {
		return self::single()->get_version();
	}

	/**
	 * Get version.
	 *
	 * @return string Version CUSTOM_COOKIE_MESSAGE_VERSION.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Default trigger.
	 */
	public function init() {

		Controller::single();
		RemoveCookie::single();

		load_plugin_textdomain( 'custom-cookie-message', false, basename( dirname( __DIR__ ) ) . '/languages' );

		add_shortcode( 'ccm_preferences', [ '\CustomCookieMessage\Shortcode', 'ccm_shortcode_preferences' ] );

		self::pll_support();
		if ( is_admin() ) {
			AdminForm::single();

			add_action( 'upgrader_process_complete', [ __CLASS__, 'update' ] );

			add_action( 'admin_notices', [ __CLASS__, 'admin_notices' ] );
		}

		add_action( 'wp_footer', [ $this, 'display_frontend_notice' ] );

		if ( ! empty( $_COOKIE['custom_cookie_message'] ) ) {
			add_action( 'wp_enqueue_scripts', [ $this, 'ccm_handle_scripts' ], 99 );
		}
		$get_options = get_option( 'custom_cookie_message', [] );
		if ( ! isset( $get_options['general']['enable_mode_rewrite'] ) ) {
			add_filter(
				'rest_url',
				function( $url, $path, $blog_id, $scheme ) {
					$url = trailingslashit( get_home_url( $blog_id, '', $scheme ) );
					if ( 'index.php' !== substr( $url, 9 ) ) {
						$url .= 'index.php';
					}
					$path = '/' . ltrim( $path, '/' );
					$url  = add_query_arg( 'rest_route', $path, $url );
					return $url;
				},
				4,
				10
			);
		}
	}
	/**
	 * Add more support for Polylang strings translations.
	 */
	public static function pll_support() {
		if ( ! function_exists( 'pll_register_string' ) ) {
			return;
		}
		$get_options = get_option( 'custom_cookie_message', [] );
		if ( empty( $get_options ) ) {
			return;
		}
		foreach ( $get_options as $option_key => $option_value ) {
			if ( in_array( $option_key, array( 'content', 'cookie_granularity_settings' ), true ) ) {
				foreach ( $option_value as $sub_option_key => $sub_option_value ) {
					pll_register_string( $sub_option_key, $sub_option_value, 'custom_cookie_message' );
				}
			}
		}
	}

	/**
	 * Enable plugin cookie.
	 */
	public static function plugin_activation() {

		if ( empty( get_option( 'custom_cookie_message', [] ) ) ) {
			add_option( 'custom_cookie_message', self::ccm_set_default_options() );
			update_site_option( 'custom_cookie_message_version', self::version() );
		}

	}

	/**
	 * Were updates in options and code are written.
	 *
	 * @since 2.0.0
	 */
	public static function update() {

		$current_installed_version = str_replace( '.', '', get_site_option( 'custom_cookie_message_version', '1.6.4' ) );
		$current_version           = str_replace( '.', '', self::version() );

		$update_queue = [];
		while ( $current_version > $current_installed_version ) {
			++ $current_installed_version;
			if ( method_exists( '\CustomCookieMessage\Update', 'custom_cookie_message_' . $current_installed_version ) ) {
				$update_queue[] = [
					'\CustomCookieMessage\Update',
					'custom_cookie_message_' . $current_installed_version,
				];
			}
		}

		// Is empty, nani?
		if ( empty( $update_queue ) ) {
			return;
		}

		foreach ( $update_queue as $update_function ) {
			$update_function();
		}

		update_site_option( 'custom_cookie_message_version', self::version() );

	}

	/**
	 * Handle all admin notices.
	 */
	public static function admin_notices() {

		$current_installed_version = str_replace( '.', '', get_site_option( 'custom_cookie_message_version', '1.6.4' ) );
		$current_version           = str_replace( '.', '', self::version() );
		$output                    = '';

		if ( $current_installed_version < $current_version ) {
			$output .= '<div class="notice notice-info">';
			$output .= '<h2 class="notice-title">';
			$output .= esc_html( 'Custom Cookie Message', 'custom-cookie-message' );
			$output .= '</h2>';
			$output .= '<p>';
			$output .= esc_html__( 'An update is available.', 'custom-cookie-message' );
			$output .= ' <a href="#ccm-upgrade" class="custom-cookie-message-upgrade">' . esc_html__( 'Upgrade now', 'custom-cookie-message' ) . '</a>';
			$output .= '</p>';
			$output .= '</div>';
		}

		//echo $output; // WPCS: XSS ok.

	}

	/**
	 * Clean pattern list.
	 *
	 * @param array $pattern_array List cookie pattern to block.
	 *
	 * @return string
	 */
	protected function ccm_patter_list( $pattern_array ) {

		if ( ! is_array( $pattern_array ) ) {
			return '';
		}

		$pattern_array = array_filter(
			$pattern_array, function ( $value ) {
				return '' !== trim( $value );
			}
		);

		$pattern_array = array_map(
			function ( $pattern ) {
				return '(' . trim( $pattern ) . ')';
			}, $pattern_array
		);

		return implode( '|', $pattern_array );

	}

	/**
	 * Dequeue cookies when user change settings.
	 */
	public function ccm_handle_scripts() {
		global $wp_scripts;
		$options = get_option( 'custom_cookie_message' );

		$cookie_ccm = json_decode( stripslashes( $_COOKIE['custom_cookie_message'] ) );

		$functional_list  = explode( ',', $options['cookie_granularity_settings']['functional_list'] . ',' );
		$advertising_list = explode( ',', $options['cookie_granularity_settings']['advertising_list'] . ',' );

		$functional_list  = $this->ccm_patter_list( $functional_list );
		$advertising_list = $this->ccm_patter_list( $advertising_list );

		foreach ( $wp_scripts->queue as $handle ) {

			if ( 'false' === $cookie_ccm->functional && preg_match( "@{$functional_list}@", $handle ) ) {
				wp_dequeue_script( $handle );
			}
			if ( 'false' === $cookie_ccm->advertising && preg_match( "@{$advertising_list}@", $handle ) ) {
				wp_dequeue_script( $handle );
			}
		}
	}

	/**
	 * Template notice.
	 */
	public function display_frontend_notice() {
		if ( is_admin() ) {
			return;
		}

		$options = get_option( 'custom_cookie_message' );

		if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
			$lang = ICL_LANGUAGE_CODE;

		} elseif ( function_exists( 'pll_current_language' ) ) {
			$lang = pll_current_language();

		} elseif ( function_exists( 'get_bloginfo' ) ) {
			$lang = get_bloginfo( 'language' );
		} else {
			$lang = 'en';
		}

		wp_enqueue_style( 'custom-cookie-message-popup-styles', CUSTOM_COOKIE_MESSAGE_PLUGIN_URL . '/assets/css/custom-cookie-message-popup.css', [], $this->version, 'screen' );
		wp_add_inline_style( 'custom-cookie-message-popup-styles', $this->custom_css() );
		wp_enqueue_script( 'custom-cookie-message-popup', CUSTOM_COOKIE_MESSAGE_PLUGIN_URL . '/assets/js/custom-cookie-message-popup.js', [ 'jquery' ], $this->version, true );
		wp_localize_script(
			'custom-cookie-message-popup', 'customCookieMessageLocalize', [
				'options'             => get_option( 'custom_cookie_message' ),
				'rest_url_banner'     => rest_url( 'custom-cm/banner' ),
				'lang'                => $lang,
				'rest_url_preference' => rest_url( 'custom-cm/cookie-preference' ),
				'cookie_life_time'      => $options['general']['life_time']
			]
		);

	}

	/**
	 * Parse to rgba.
	 *
	 * @param string $colour .
	 * @param string $opacity .
	 */
	protected function parse_to_rgba( $colour, $opacity = 1 ) {
		list( $r, $g, $b ) = sscanf( $colour, '#%02x%02x%02x' );

		$opacity = $opacity / 100;
		$rgba    = sprintf( 'rgba(%s, %s, %s, %s)', $r, $g, $b, $opacity );

		return $rgba;
	}

	/**
	 * Create styles from the options
	 */
	protected function custom_css() {
		$options       = get_option( 'custom_cookie_message' );
		$styles        = $options['styles'];
		$button_styles = ! empty( $styles['button_styling'] ) ? $styles['button_styling'] : '';

		$banner_background = $this->parse_to_rgba( $styles['message_color_picker'], $styles['opacity_slider_amount'] );

		$modal_overlay = $this->parse_to_rgba( $styles['modal_overlay'], $styles['modal_overlay_opacity'] );

		$css  = '';
		$css .= '.custom-cookie-message-banner {';
		$css .= 'display: none;';
		$css .= sprintf( 'background-color: %s;', $banner_background );
		$css .= sprintf( 'padding-top: %spx;', $styles['message_height_slider_amount'] );
		$css .= sprintf( 'padding-bottom: %spx;', $styles['message_height_slider_amount'] );
		$css .= '}';

		$css .= '.custom-cookie-message-banner__text {';
		$css .= sprintf( 'color: %s;', $styles['text_color_picker'] );
		if ( ! empty( $styles['text_font'] ) ) {
			$css .= sprintf( 'font-family: %s;', $styles['text_font'] );
		}
		if ( ! empty( $styles['text_size'] ) ) {
			$css .= sprintf( 'font-size: %s;', $styles['text_size'] );
		}
		$css .= '}';

		$css .= '.custom-cookie-message-banner a {';
		$css .= sprintf( 'color: %s;', $styles['link_color_picker'] );
		$css .= '}';

		$css .= '.custom-cookie-message-banner__close svg {';
		$css .= sprintf( 'fill: %s;', $styles['close_color_picker'] );
		$css .= '}';

		$css .= '.custom-cookie-message-modal {';
		$css .= sprintf( 'background-color: %s;', $modal_overlay );
		$css .= '}';

		if ( isset( $button_styles ) ) :
			$css .= '.custom-cookie-message-banner__button,';
			$css .= '.custom-cookie-message-popup__button {';
			$css .= sprintf( 'background-color: %s;', $styles['button_color_picker'] );
			$css .= sprintf( 'color: %s;', $styles['button_text_color_picker'] );
			$css .= sprintf(
				'padding: %spx %spx;', $styles['button_height_slider_amount'],
				$styles['button_width_slider_amount']
			);
			$css .= $styles['textarea_btn_custom_styling'];
			$css .= '}';

			$css .= '.custom-cookie-message-banner__button:hover,';
			$css .= '.custom-cookie-message-popup__button:hover {';
			$css .= sprintf( 'background-color: %s;', $styles['button_hover_color_picker'] );
			$css .= sprintf( 'color: %s;', $styles['button_hover_text_color_picker'] );
			$css .= '}';
		endif;

		return $css;
	}

	/**
	 * Include template if we could locate it.
	 *
	 * @param string $template_name Template name.
	 */
	public static function get_template( $template_name = 'cookie-notice.php' ) {

		$located = static::locate_template( $template_name );

		if ( ! file_exists( $located ) ) {
			_doing_it_wrong( __FUNCTION__, sprintf( ' < code>%s </code > does not exist . ', esc_html( $located ) ), esc_html( self::version() ) );

			return;
		}

		include $located;
	}

	/**
	 * Helper to locate templates.
	 *
	 * @param string $template_name Template name.
	 *
	 * @return string
	 */
	public static function locate_template( $template_name ) {
		$template_path = CUSTOM_COOKIE_MESSAGE_PLUGIN_PATH . ' / ';

		$default_path = CUSTOM_COOKIE_MESSAGE_PLUGIN_PATH . '/templates';

		$template = locate_template(
			[
				trailingslashit( $template_path ) . $template_name,
				$template_name,
			]
		);

		if ( ! $template ) {
			$template = $default_path . '/' . $template_name;
		}

		return apply_filters( 'ccm_locate_template', $template, $template_name, $template_path );
	}

	/**
	 * TODO: Move this away from here.
	 */
	public function cookie_setcookie() {
		// TODO: Create option with life span. Also move it to REST API.
		setcookie( 'cookie-warning-message', 15, 30 * DAYS_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
		wp_send_json( 1 );
	}

	/**
	 * Default Options.
	 *
	 * @return mixed|void
	 */
	public static function ccm_set_default_options() {

		$defaults = [
			'general'                     => [
				'life_time'           => MONTH_IN_SECONDS,
				'location_options'    => 'top-fixed',
				'close_button'        => 'xbutton',
				'cookies_about_page'  => '#',
				'cookies_page_link'   => '#',
				'enable_mode_rewrite' => 1,
			],
			'content'                     => [
				'input_button_text'     => 'Change Settings',
				'save_settings_button'  => 'Save Settings',
				'or_text_settings'      => 'OR',
				'input_link_text'       => 'Read more',
				'textarea_warning_text' => 'This website uses cookies. By using our website you accept our use of cookies.',
				'shortcode_text'        => 'Cookie Preferences',
			],
			'styles'                      => [
				'message_color_picker'           => '#3E3E3B',
				'message_height_slider_amount'   => '20',
				'opacity_slider_amount'          => '100',
				'button_color_picker'            => '#F0F0F0',
				'button_hover_color_picker'      => '#E0E0E0',
				'button_text_color_picker'       => '#3E3E3B',
				'button_hover_text_color_picker' => '#EBEBEB',
				'button_height_slider_amount'    => '10',
				'button_width_slider_amount'     => '10',
				'button_custom_class'            => '',
				'textarea_btn_custom_styling'    => '',
				'text_color_picker'              => '#c0c0c0',
				'text_size'                      => '',
				'text_font'                      => '',
				'link_color_picker'              => '#CBC5C1',
				'close_color_picker'             => '#FFFFFF',
				'modal_overlay'                  => '#3d3d3d',
				'modal_overlay_opacity'          => '50',
				'button_custom_css'              => '#custom-cookie-message-preference {} #ccm-save-preference {}',
			],
			'cookie_granularity_settings' => [
				'headline'                    => 'Privacy Preferences',
				'required_cookies_message'    => 'Required Cookies Message',
				'functional_cookies_message'  => 'Functional Cookies Message',
				'advertising_cookies_message' => 'Advertising Cookies Message',
				'functional_list'             => '',
				'advertising_list'            => '',
			],
		];

		return apply_filters( 'custom_cookie_message_set_options', $defaults );
	}

}
