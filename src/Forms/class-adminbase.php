<?php
/**
 * AdminBase
 *
 * @package CustomCookieMessage\Forms
 */

namespace CustomCookieMessage\Forms;

/**
 * Class AdminGeneralOptions
 *
 * @package CustomCookieMessage
 */
class AdminBase {

	/**
	 * Custom Cookie Message options.
	 *
	 * @var array
	 */
	protected $options;

	public function __construct() {
		$this->options = get_option( 'custom_cookie_message' );
	}
}