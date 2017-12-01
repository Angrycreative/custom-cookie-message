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

	/**
	 * Setup Unit.
	 */
	public function setUp() {
		parent::setUp();

		\CustomCookieMessage\Main::single();
	}

	public function test_options() {

		$options = get_option( 'custom_cookie_message', [] );

		$this->assertNotEmpty( $options, 'We should have content' );
	}
}
