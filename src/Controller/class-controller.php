<?php
/**
 * Controller
 *
 * @package CustomCookieMessage
 */

namespace CustomCookieMessage\Controller;

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
		add_action( 'rest_api_init', [ $this, 'custom_cookie_message_routes' ] );
		add_action( 'plugins_loaded', [ $this, 'plugins_loaded' ] );
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
	 * Plugin loading.
	 */
	public function plugins_loaded() {
		$this->user = wp_get_current_user();
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

}
