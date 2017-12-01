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

		$options = get_option( 'custom_cookie_message', [] );

		$this->assertNotEmpty( $options, 'We should have content' );
	}
}
