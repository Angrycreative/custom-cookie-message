<?php
/**
 * Tests
 *
 * @package CustomCookieMessage
 */

/**
 * Class OptionsTests.
 */
class CustomCookiesMessageTests extends WP_UnitTestCase {

	/**
	 * Test update plugin from old version 1.6.* to 2.0.0
	 */
	public function test_update_plugin_from_165_200() {

		add_option( 'cookies_general_options', [
			'location_options'  => 'top-fixed',
			'cookies_page_link' => 'Updated link',
		] );

		add_option( 'cookies_content_options', [
			'input_button_text'     => esc_html__( 'I understand', 'custom-cookie-message' ),
			'input_link_text'       => esc_html__( 'Updated Read more', 'custom-cookie-message' ),
			'textarea_warning_text' => esc_html__( 'This website uses cookies . By using our website you accept our use of cookies . ', 'custom-cookie-message' ),
		] );

		add_option( 'cookies_styling_options', [
			'messages_color_picker'     => 'Updated Color Picker',
			'button_color_picker'       => '#EBECED',
			'button_hover_color_picker' => '#CBC5C1',
			'button_text_color_picker'  => '#3E3E3B',
			'text_color_picker'         => '#EBECED',
			'link_color_picker'         => '#CBC5C1',
		] );

		// Manually trigger eventho it should be triggered during the callback.
		\CustomCookieMessage\Main::update();

		$this->assertEmpty( get_option( 'cookies_general_options' ), 'General option was not empty.' );
		$this->assertEmpty( get_option( 'cookies_content_options' ), 'Content option was not empty.' );
		$this->assertEmpty( get_option( 'cookies_styling_options' ), 'Styling option was not empty.' );

		$options = get_option( 'custom_cookie_message' );

		$this->assertEquals( 'Updated link', $options['general']['cookies_page_link'], '' );
		$this->assertEquals( 'Updated Read more', $options['content']['input_link_text'] );
		$this->assertEquals( 'Updated Color Picker', $options['styles']['messages_color_picker'] );

		$this->assertEquals( \CustomCookieMessage\Main::version(), get_site_option( 'custom_cookie_message_version' ) );

	}

	/**
	 * Test Plugin Activation from scratch.
	 */
	public function test_installation_plugin() {
		// We've to manually run activation functions.
		\CustomCookieMessage\Main::plugin_activation();

		$options = get_option( 'custom_cookie_message', [] );

		$this->assertNotEmpty( $options, 'Installation could not be completed' );
	}

}
