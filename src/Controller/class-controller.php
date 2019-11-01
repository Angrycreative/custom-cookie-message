<?php
/**
 * Controller
 *
 * @package CustomCookieMessage
 */

namespace CustomCookieMessage\Controller;

use CustomCookieMessage\Main;
use CustomCookieMessage\Update;

/**
 * Class Controller
 *
 * @package CustomCookieMessage\Controller
 */
class Controller {

	/**
	 * WordPress User.
	 *
	 * @var \WP_User
	 */
	protected $user;

	/**
	 * Singleton
	 *
	 * @var Controller
	 */
	protected static $single;

	/**
	 * Controller constructor.
	 */
	public function __construct() {
		$this->user = wp_get_current_user();
		add_action( 'rest_api_init', array( $this, 'custom_cookie_message_routes' ) );
		add_filter( 'plugin_action_links', array( $this, 'ac_add_actions_plugin' ), 10, 5 );
	}

	/**
	 * Access to the single instance of the class.
	 *
	 * @return Controller
	 */
	public static function single() {
		if ( empty( self::$single ) ) {
			self::$single = new self();
		}

		return self::$single;
	}

	/**
	 * Controller routes.
	 */
	public function custom_cookie_message_routes() {
		$namespace_route = apply_filters( 'custom_cookie_message_route_register', 'custom-cm' );

		register_rest_route(
			$namespace_route, '/upgrade', [
				[
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => [ $this, 'upgrade' ],
					'permission_callback' => [ $this, 'upgrade_permissions' ],
				],
			]
		);
		register_rest_route(
			$namespace_route, '/cookie_list/(?P<category>.+)', [
				'methods'  => \WP_REST_Server::READABLE,
				'callback' => [ $this, 'readeable_cookie_list' ],
			]
		);
		register_rest_route(
			$namespace_route, '/post_link', [
				'methods'  => \WP_REST_Server::READABLE,
				'callback' => [ $this, 'readeable_post_link' ],
			]
		);
		register_rest_route(
			$namespace_route, '/banner', [
				'methods'  => \WP_REST_Server::READABLE,
				'callback' => [ $this, 'readeable_popup_banner' ],
			]
		);
	}

	/**
	 * User upgrade permissions.
	 *
	 * @param \WP_REST_Request $request WP Request.
	 *
	 * @return \WP_Error|bool
	 */
	public function upgrade_permissions( \WP_REST_Request $request ) {
		return $this->user->has_cap( 'update_plugins' ) ?: new \WP_Error(
			'ccm_upgrade_permissions', esc_html__( 'What it is? No, thanks.', 'custom-cookie-message' ), [
				'status' => 403,
			]
		);
	}

	/**
	 * Upgrade request.
	 *
	 * @param \WP_REST_Request $request WP Rest Request class.
	 *
	 * @return \WP_REST_Response
	 */
	public function upgrade( \WP_REST_Request $request ) {

		// WP_REST_Request has its own nonce, I just include a second one to confirm was an UI trigger.
		if ( wp_verify_nonce( $request->get_param( '_ccm_nonce' ), 'custom_cookie_message_upgrade' ) ) {
			return new \WP_REST_Response( esc_html__( 'Sorry, who are you?', 'custom-cookie-message' ), 400 );
		}

		Main::update();

		return new \WP_REST_Response();
	}

	/**
	 * Get popup Banner.
	 */
	public function readeable_popup_banner() {

		ob_start();
		Main::get_template();

		$template_content = ob_get_clean();

		if ( empty( $template_content ) ) {
			return new \WP_REST_Response( esc_html__( 'Please double check your template files.' ), 404 );
		}

		return new \WP_REST_Response(
			[
				'template' => $template_content,
			], 200
		);
	}

	/**
	 * Return post link list.
	 *
	 * @param \WP_REST_Request $request WordPress REST request.
	 *
	 * @return \WP_REST_Response
	 */
	public function readeable_post_link( \WP_REST_Request $request ) {

		if ( ! $request->get_param( 'q' ) ) {
			return new \WP_REST_Response( [], 404 );
		}

		$query = new \WP_Query(
			[
				's'              => trim( $request->get_param( 'q' ) ),
				'posts_per_page' => 5,
			]
		);

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$result[] = get_permalink();
			}
			wp_reset_postdata();
		} else {
			return new \WP_REST_Response( [], 404 );
		}

		return new \WP_REST_Response( $result, 200 );
	}

	/**
	 * Filter the default cookie list with search terms.
	 *
	 * @param \WP_REST_Request $request WordPress REST Request.
	 *
	 * @return array
	 */
	protected function filter_cookie_list( \WP_REST_Request $request ) {
		$cookie_list = [];
		$category    = sanitize_textarea_field( $request->get_param( 'category' ) );

		if ( ! $request->get_param( 'category' ) && ! is_file( CUSTOM_COOKIE_MESSAGE_DIR . '/assets/data/' . $category . '-cookie-list.php' ) ) {
			return $cookie_list;
		}

		$q               = sanitize_textarea_field( $request->get_param( 'q' ) );
		$raw_cookie_list = include CUSTOM_COOKIE_MESSAGE_DIR . '/assets/data/' . $category . '-cookie-list.php';
		$raw_cookie_list = apply_filters( 'ccm_' . $category . '_cookies_list', $raw_cookie_list );

		foreach ( $raw_cookie_list as $cookie ) {
			if ( preg_match( "@{$q}@", $cookie ) ) {
				$cookie_list[] = $cookie;
			}
		}

		return $cookie_list;

	}

	/**
	 * Fetch cookie list.
	 *
	 * @param \WP_REST_Request $request WordPress REST Request.
	 *
	 * @return \WP_REST_Response
	 */
	public function readeable_cookie_list( \WP_REST_Request $request ) {

		$result = $this->filter_cookie_list( $request );

		if ( ! empty( $result ) ) {
			return new \WP_REST_Response( $result, 200 );
		}

		return new \WP_REST_Response( [], 404 );
	}

	/**
	 * @param $actions
	 * @param $plugin_file
	 * Add settings and support links to the plugin
	 * @return array
	 */
	public function ac_add_actions_plugin( $actions, $plugin_file ) {
		if ( CUSTOM_COOKIE_MESSAGE_PLUGIN_BASENAME == $plugin_file ) {
			$settings  = array( 'settings' => '<a href="' . admin_url( 'options-general.php?page=custom_cookie_message_options' ) . '">' . __( 'Settings', 'custom-cookie-message' ) . '</a>' );
			$site_link = array( 'support' => '<a href="https://angrycreative.se" target="_blank">' . __( 'Support', 'custom-cookie-message' ) . '</a>' );

			$actions = array_merge( $settings, $actions );
			$actions = array_merge( $site_link, $actions );
		}

		return $actions;
	}

}
