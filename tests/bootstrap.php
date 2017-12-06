<?php

/**
 * Class CCM_Unit_Bootstrap.
 *
 * @since 2.0.0
 */
class CCM_Unit_Bootstrap {

	/**
	 * WordPress Tests Directory.
	 *
	 * @var string
	 */
	public $tests_dir;

	/**
	 * WordPress Core Directory.
	 *
	 * @var string
	 */
	public $core_dir;

	/**
	 * Plugin Dir
	 *
	 * @var string
	 */
	public $plugin_dir;

	/**
	 * Singlenton
	 *
	 * @var CCM_Unit_Bootstrap
	 */
	protected static $instance;

	/**
	 * CCM_Unit_Bootstrap constructor.
	 */
	public function __construct() {

		$this->tests_dir = ! empty( getenv( 'WP_TESTS_DIR' ) ) ? getenv( 'WP_TESTS_DIR' ) : rtrim( sys_get_temp_dir(), '/\\' ) . '/wordpress-tests-lib';

		if ( ! file_exists( $this->tests_dir . '/includes/functions.php' ) ) {
			echo "Could not find $this->tests_dir/includes/functions.php, have you run bin/install.sh ?"; // WPCS: XSS ok.
			exit( 1 );
		}

		// Give access to tests_add_filter() function.
		require_once $this->tests_dir . '/includes/functions.php';

		$this->plugin_dir = dirname( __DIR__ );

		tests_add_filter( 'muplugins_loaded', [ $this, 'load' ] );

		// Start up the WP testing environment.
		require_once $this->tests_dir . '/includes/bootstrap.php';

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
	public static function instance() {
		if ( empty( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

}

CCM_Unit_Bootstrap::instance();
