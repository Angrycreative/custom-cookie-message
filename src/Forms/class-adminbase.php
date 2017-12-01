<?php

namespace CustomCookieMessage\Forms;

class AdminBase {

	/**
	 * Singlenton.
	 *
	 * @var AdminBase
	 */
	static protected $instance;

	/**
	 * @var AdminGenralOptions
	 */
	private $general_options;

	/**
	 * @var AdminContentOptions
	 */
	private $content_options;

	/**
	 * @var AdminStylingOptions
	 */
	private $styling_options;

	/**
	 * @var AdminCookieListOptions
	 */
	private $cookie_list;

	/**
	 * AdminBase constructor.
	 */
	public function __construct() {
		add_action( 'admin_menu', [ $this, 'cookies_menu' ] );

		$this->general_options = AdminGeneralOptions::instance();
		$this->content_options = AdminContentOptions::instance();
		$this->styling_options = AdminStylingOptions::instance();
		$this->cookie_list     = AdminCookieListOptions::instance();

		add_action( 'admin_enqueue_scripts', [ $this, 'ccm_admin_enqueue_scripts' ] );
	}

	/**
	 * Access to the single instance of the class.
	 *
	 * @since 2.0.0
	 *
	 * @return object
	 */
	public static function instance() {
		if ( empty( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * WP Settings Cookie Menu.
	 */
	public function cookies_menu() {
		add_options_page( 'Cookies', 'Cookies', 'administrator', 'cookies_options', [
			$this,
			'cookies_options_display',
		] );
	}

	/**
	 * Enqueue Scripts.
	 */
	public function ccm_admin_enqueue_scripts() {
		wp_enqueue_style( 'jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css' );
		wp_register_style( 'cookie_style', CUSTOM_COOKIE_MESSAGE_PLUGIN_URL . '/assets/css/cookies.css' );
		wp_enqueue_style( 'cookie_style' );
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'variation-custom-cookie-script', CUSTOM_COOKIE_MESSAGE_PLUGIN_URL . '/assets/js/ac-custom-cookie-message-backend.js', [
			'jquery',
			'jquery-ui-slider',
			'wp-color-picker',
		] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_js' ] );
	}

	/**
	 * Markup output.
	 */
	public function cookies_options_display() {

		$allow_edition = false;

		$current_roles = wp_get_current_user()->roles;

		if ( ! ! array_intersect( [ 'administrator', 'editor' ], $current_roles ) ) {
			$allow_edition = true;
		}
		?>
		<!-- Create a header in the default WordPress 'wrap' container -->
		<div class="wrap">

			<h2><?php _e( 'Cookies Theme Options', 'cookies-message' ); ?></h2>
			<!-- Give user feeback that a setting has been saved-->
			<?php //settings_errors();
			?>
			<!-- isset works as an "onclick" for the tabs to set active tab-->
			<?php $active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'general_options'; ?>

			<!-- Tabs -->
			<h2 class="nav-tab-wrapper">
				<a href="?page=cookies_options&tab=general_options" class="nav-tab <?php echo $active_tab == 'general_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'General Options', 'cookies' ); ?></a> <a href="?page=cookies_options&tab=content_options" class="nav-tab <?php echo $active_tab == 'content_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Content Options', 'cookies' ); ?></a>
				<?php if ( $allow_edition ): ?>
					<a href="?page=cookies_options&tab=styling_options" class="nav-tab <?php echo $active_tab == 'styling_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Styling Options', 'cookies' ); ?></a>                    <a href="?page=cookies_options&tab=cookie_list" class="nav-tab <?php echo $active_tab == 'cookie_list' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Cookie List', 'cookies' ); ?></a>
				<?php endif; ?>
			</h2>

			<form method="post" action="options.php">
				<?php

				$this->{$active_tab}->getSection();

				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * @param $input
	 *
	 * @return mixed|void
	 */
	public function cookies_validate_options( $input ) {

		// Create our array for storing the validated options
		$output = [];

		// Loop through each of the incoming options
		foreach ( $input as $key => $value ) {

			// Check to see if the current option has a value. If so, process it.
			if ( isset( $input[ $key ] ) ) {

				// Strip all HTML and PHP tags and properly handle quoted strings
				$output[ $key ] = strip_tags( stripslashes( $input[ $key ] ) );

			} // end if

		} // end foreach

		// Return the array processing any additional functions filtered by this action
		return apply_filters( 'cookies_validate_styling_options', $output, $input );
	}

}
