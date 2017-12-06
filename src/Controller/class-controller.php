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
		add_action( 'rest_api_init', [ $this, 'custom_cookie_message_routes' ] );
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

		register_rest_route( $namespace_route, '/upgrade', [
			[
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => [ $this, 'upgrade' ],
				'permission_callback' => [ $this, 'upgrade_permissions' ],
			],
		] );
	}

	/**
	 * User upgrade permissions.
	 *
	 * @param \WP_REST_Request $request WP Request.
	 *
	 * @return \WP_Error|bool
	 */
	public function upgrade_permissions( \WP_REST_Request $request ) {
		return $this->user->has_cap( 'update_plugins' ) ?: new \WP_Error( 'ccm_upgrade_permissions', esc_html__( 'What it is? No, thanks.', 'custom-cookie-message' ), [
			'status' => 403,
		] );
	}

	/**
	 * Upgrade request.
	 *
	 * @param \WP_REST_Request $request WP Rest Request class.
	 *
	 * @return \WP_REST_Response
	 */
	public function upgrade( \WP_REST_Request $request ) {

		if ( wp_verify_nonce( $request->get_param( '_ccm_nonce' ), 'custom_cookie_message_upgrade' ) ) {
			return new \WP_REST_Response( esc_html__( 'Sorry, who are you?', 'custom-cookie-message' ), 400 );
		}

		Main::update();

		return new \WP_REST_Response();
	}

}
