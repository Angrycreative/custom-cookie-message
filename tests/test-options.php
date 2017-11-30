<?php
/**
 * Tests
 *
 * @package CustomCookieMessage
 */

/**
 * Class OptionsTests.
 */
class OptionsTests extends WP_UnitTestCase {

	public function test_options() {
		\CustomCookieMessage\Main::single();

		$options = get_option( 'custom_cookies_message', [] );

		$this->assertNotEmpty( $options );
	}
}
