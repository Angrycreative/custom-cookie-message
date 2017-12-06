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

	public function setUp() {
		parent::setUp();
	}

	public function test_update_plugin() {
		$old_general_options = [
			'location_options'  => 'top-fixed',
			'cookies_page_link' => '',
		];
	}

	/**
	 * Test Plugin Activation from scratch.
	 */
	public function test_installation_plugin() {
		// We've to manually run activation functions.
		\CustomCookieMessage\Main::single()->plugin_activation();

		$options = get_option( 'custom_cookie_message', [] );

		$this->assertNotEmpty( $options, 'We should have content' );
	}
}
