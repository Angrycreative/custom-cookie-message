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
	 * Option Group.
	 *
	 * @var string
	 */
	protected $option_group = 'custom_cookie_message_options';

	/**
	 * Custom Cookie Message options.
	 *
	 * @var array
	 */
	protected $options;

	/**
	 * AdminBase constructor.
	 */
	public function __construct() {
		$this->options = get_option( 'custom_cookie_message' );
	}
}
