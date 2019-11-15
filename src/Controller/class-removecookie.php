<?php
/**
 * RemoveCookie
 *
 * @package CustomCookieMessage
 */

namespace CustomCookieMessage\Controller;

/**
 * Class RemoveCookie
 *
 * @package CustomCookieMessage\RemoveCookie
 */
class RemoveCookie {

	/**
	 * Singleton
	 *
	 * @var RemoveCookie
	 */
	protected static $single;

	/**
	 * Custom Cookie Message options.
	 *
	 * @var array
	 */
	protected $options;

	/**
	 * @var
	 */
	public $functional_list;

	/**
	 * @var
	 */
	public $advertising_list;

	/**
	 * @var
	 */
	public $cc_equeue_list_scripts;

	/**
	 * @var array
	 */
	public $all_cookies_to_be_removed;

	/**
	 * @var
	 */
	public $is_functional_cookes;

	/**
	 * @var
	 */
	public $is_advertising_cookies;

	/**
	 * RemoveCookie constructor.
	 */
	public function __construct() {
		$this->cc_get_client_cookie_settings_from_browser();

		$this->options          = get_option( 'custom_cookie_message' );
		$this->functional_list  = (
		$this->options['cookie_granularity_settings']['functional_list'] )
			?
			explode(
				',',
				$this->options['cookie_granularity_settings']['functional_list']
			)
			: '';
		$this->advertising_list = (
			$this->options['cookie_granularity_settings']['advertising_list'] )
			?
			explode(
				',',
				$this->options['cookie_granularity_settings']['advertising_list']
			)
			: '';

		if ( ! empty( $this->functional_list ) && ! empty( $this->advertising_list ) ) {
			$this->all_cookies_to_be_removed = array_merge( $this->functional_list, $this->advertising_list );
		}
		add_action( 'init', [ $this, 'cc_check_the_cookies_to_be_deleted' ], 100 );
	}

	/**
	 * Access to the single instance of the class.
	 *
	 * @return RemoveCookie
	 */
	public static function single() {
		if ( empty( self::$single ) ) {
			self::$single = new self();
		}

		return self::$single;
	}

	/**
	 * Check which cookies needed to delete.
	 */
	public function cc_check_the_cookies_to_be_deleted() {
		if ( ! $this->functional_list && ! $this->advertising_list ) {
			return;
		}
		if ( ! empty( $this->all_cookies_to_be_removed ) && empty( $this->is_functional_cookes ) && isset( $this->is_functional_cookes ) && empty( $this->is_advertising_cookies ) ) {
			$this->cc_remove_all_cookies( $this->all_cookies_to_be_removed );
		} elseif ( empty( $this->advertising_list ) && ! empty( $this->functional_list ) && isset( $this->is_functional_cookes ) && empty( $this->is_functional_cookes ) ) {
			$this->cc_remove_functional_cookies( $this->functional_list );
		} elseif ( ! empty( $this->advertising_list ) && empty( $this->functional_list ) && isset( $this->is_advertising_cookies ) && empty( $this->is_advertising_cookies ) ) {
			$this->cc_remove_advertising_cookies( $this->advertising_list );
		}
	}

	/**
	 * Get saved cookie
	 */
	public function cc_get_client_cookie_settings_from_browser() {
		if ( empty( $_COOKIE['custom_cookie_message'] ) ) {
			return;
		}
		$cookie_preferences = json_decode( stripslashes( $_COOKIE['custom_cookie_message'] ) );

		// JSON Cookie values are strings.
		$this->is_functional_cookes   = $cookie_preferences->functional;
		$this->is_advertising_cookies = $cookie_preferences->advertising;
	}

	/**
	 * @param $cookie_names_list
	 * Remove Functional Cookies
	 */
	public function cc_remove_functional_cookies( $cookie_names_list ) {
		$this->cc_remove_cookie( $cookie_names_list );
	}

	/**
	 * @param $cookie_names_list
	 * Remove Advertising Cookies
	 */
	public function cc_remove_advertising_cookies( $cookie_names_list ) {
		remove_action( 'wp_head', 'gtm4wp_wp_header_begin' );
		$this->cc_remove_cookie( $cookie_names_list );
	}

	/**
	 * @param $cookie_names_list
	 * Remove All Cookies
	 */
	public function cc_remove_all_cookies( $cookie_names_list ) {
		remove_action( 'wp_head', 'gtm4wp_wp_header_begin' );
		$this->cc_remove_cookie( $cookie_names_list );
	}

	/**
	 * @param $cookie_names_list
	 * Remove All cookies
	 */
	public function cc_remove_cookie( $cookie_names_list ) {
		$cookie       = $_COOKIE;
		$cookies_list = [];

		foreach ( $cookie as $cookie_key => $value_2 ) {
			array_push( $cookies_list, $cookie_key );
		}

		foreach ( $cookie_names_list as $item ) {
			if ( in_array( $item, $cookies_list, true ) ) {
				unset( $_COOKIE[ $item ] );
				setcookie( $item, '', time() - 3600, '/' ); // empty value and old timestamp
			}
		}
	}
}
