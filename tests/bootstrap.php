<?php

/**
 * Custom Cookie Message Unit Test Bootstrap.
 *
 * @since 2.0.0
 */
class CCM_Unit_Bootstrap {

	/**
	 * @var string directory where wordpress-tests-lib is installed.
	 */
	public $wp_tests_dir;

	/**
	 * @var string testing directory.
	 */
	public $tests_dir;

	/**
	 * @var string plugin directory.
	 */
	public $plugin_dir;

	/**
	 * @var CCM_Unit_Bootstrap instance.
	 */
	protected static $instance;

	/**
	 * CCM_Unit_Bootstrap constructor.
	 */
	public function __construct() {
		ini_set( 'display_errors', 'on' );
		error_reporting( E_ALL );

		// Ensure server variable is set for WP email functions.
		if ( ! isset( $_SERVER['SERVER_NAME'] ) ) {
			$_SERVER['SERVER_NAME'] = 'localhost';
		}

		$this->tests_dir    = dirname( __FILE__ );
		$this->plugin_dir   = dirname( $this->tests_dir );
		$this->wp_tests_dir = getenv( 'WP_TESTS_DIR' ) ? getenv( 'WP_TESTS_DIR' ) : '/tmp/wordpress-tests-lib';

		// load test function so tests_add_filter() is available
		require_once( $this->wp_tests_dir . '/includes/functions.php' );

		tests_add_filter( 'muplugins_loaded', [ $this, 'load' ] );
	}

	/**
	 * Load Custom Cookie Message.
	 */
	public function load() {
		require_once $this->plugin_dir . '/custom-cookie-message.php';
	}

	/**
	 * Access to the single instance of the class.
	 *
	 * @return CCM_Unit_Bootstrap
	 */
	static public function instance() {
		if ( empty( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

}

CCM_Unit_Bootstrap::instance();
