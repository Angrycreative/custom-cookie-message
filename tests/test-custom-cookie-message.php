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
	 * Test REST Server
	 *
	 * @var WP_REST_Server
	 */
	protected $server;

	/**
	 * Custom Cookie Message RESTfull resource.
	 *
	 * @var string
	 */
	protected $route = '/custom-cm';

	public function setUp() {
		@session_start();
		parent::setUp();

		/**
		 * WP REST Server.
		 *
		 * @var WP_REST_Server $wp_rest_server
		 */
		global $wp_rest_server;

		$wp_rest_server = new \WP_REST_Server();
		$this->server   = $wp_rest_server;

		do_action( 'rest_api_init' );

		wp_enqueue_script( 'google', 'http://www.example.com/google.js' );
		wp_enqueue_script( 'twitter', 'http://www.example.com/google.js' );
		wp_enqueue_script( 'hotjar', 'http://www.example.com/google.js' );

		wp_enqueue_script( 'doubleclick', 'http://www.example.com/google.js' );
		wp_enqueue_script( 'facebook', 'http://www.example.com/google.js' );

	}

	/**
	 * Test update plugin from old version 1.6.* to 2.0.0
	 */
	public function test_update_plugin_from_165_200() {

		add_option(
			'cookies_general_options', [
				'location_options'  => 'top-fixed',
				'cookies_page_link' => 'Updated link',
			]
		);

		add_option(
			'cookies_content_options', [
				'input_button_text'     => esc_html__( 'I understand', 'custom-cookie-message' ),
				'input_link_text'       => esc_html__( 'Updated Read more', 'custom-cookie-message' ),
				'textarea_warning_text' => esc_html__( 'This website uses cookies . By using our website you accept our use of cookies . ', 'custom-cookie-message' ),
			]
		);

		add_option(
			'cookies_styling_options', [
				'messages_color_picker'     => 'Updated Color Picker',
				'button_color_picker'       => '#EBECED',
				'button_hover_color_picker' => '#CBC5C1',
				'button_text_color_picker'  => '#3E3E3B',
				'text_color_picker'         => '#EBECED',
				'link_color_picker'         => '#CBC5C1',
			]
		);

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

	/**
	 * Test route is register.
	 */
	public function test_route_is_register() {
		$routes = $this->server->get_routes();
		$this->assertArrayHasKey( $this->route, $routes, 'Route is not register' );
	}

	/**
	 * Test if update process is reliable.
	 */
	public function test_rest_api_update() {
		$_POST = [
			'_ccm_nonce' => wp_create_nonce( 'custom_cookie_message_upgrade' ),
		];

		$user = wp_get_current_user();
		$user->remove_all_caps();
		$user->add_cap( 'update_plugins' );
		$user->set_role( 'administrator' );

		$request  = new WP_REST_Request( 'POST', $this->route . '/upgrade' );
		$response = $this->server->dispatch( $request );

		$this->assertEquals( 200, $response->get_status(), 'Upgrade went well.' );
		$this->assertEquals( \CustomCookieMessage\Main::version(), get_site_option( 'custom_cookie_message_version' ) );
	}

	/**
	 * Test get banner.
	 */
	public function test_rest_api_get_banner() {
		$request  = new WP_REST_Request( 'GET', $this->route . '/banner' );
		$response = $this->server->dispatch( $request );

		$this->assertEquals( 200, $response->get_status(), 'Upgrade went well.' );
		$this->assertNotEmpty( $response->get_data()['template'], 'Template was not included.' );
	}

	/**
	 * Test save cookies preferences.
	 */
	/*
	 Removed temporary waiting for a solution with runInSeparateProcess
	public function test_rest_api_post_preferences() {
		update_option(
			'custom_cookie_message', [
				'general' => [
					'life_time' => 0,
				],
			]
		);

		$request  = new WP_REST_Request( 'POST', $this->route . '/cookie-preference' );
		$response = $this->server->dispatch( $request );

		// We just can assert the response code.
		$this->assertEquals( 200, $response->get_status(), 'Cookie preferences were save.' );
	}*/

	public function test_dequeue_scripts() {
		global $wp_scripts;

		add_option(
			'custom_cookie_message', [
				'cookie_granularity_settings' => [
					'functional_list'  => 'hotjar, google, twitter',
					'advertising_list' => 'facebook, doubleclick',
				],
			]
		);

		$_COOKIE['custom_cookie_message'] = wp_json_encode(
			[
				'functional'  => 'true',
				'advertising' => 'false',
			], JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK
		);

		\CustomCookieMessage\Main::single()->ccm_handle_scripts();

		$this->assertNotContains( 'facebook', $wp_scripts->queue );
		$this->assertNotContains( 'doubleclick', $wp_scripts->queue );

		$this->assertContains( 'google', $wp_scripts->queue );
		$this->assertContains( 'hotjar', $wp_scripts->queue );
		$this->assertContains( 'twitter', $wp_scripts->queue );

	}

}
