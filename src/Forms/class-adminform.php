<?php
/**
 * AdminGeneralOptions
 *
 * @package CustomCookieMessage
 */

namespace CustomCookieMessage\Forms;

/**
 * Class AdminBase
 *
 * @package CustomCookieMessage\Forms
 */
class AdminForm extends AdminBase {

	use AdminTrait;

	/**
	 * Singlenton.
	 *
	 * @var AdminForm
	 */
	static protected $single;

	/**
	 * General Options
	 *
	 * @var AdminGenralOptions
	 */
	private $general_options;

	/**
	 * Content Options
	 *
	 * @var AdminContentOptions
	 */
	private $content_options;

	/**
	 * Styling Options
	 *
	 * @var AdminStylingOptions
	 */
	private $styling_options;

	/**
	 * Cookie Settings
	 *
	 * @var AdminCookieListOptions
	 */
	private $cookie_settings;

	/**
	 * AdminBase constructor.
	 */
	public function __construct() {
		parent::__construct();
		add_action( 'admin_menu', [ $this, 'cookies_menu' ] );

		$this->general_options = AdminGeneralOptions::single();
		$this->content_options = AdminContentOptions::single();
		$this->styling_options = AdminStylingOptions::single();
		$this->cookie_settings = AdminCookieSettings::single();

		register_setting( 'custom_cookie_message_group', 'custom_cookie_message', [ $this, 'ccm_validate_options' ] );

		add_action( 'admin_enqueue_scripts', [ $this, 'ccm_admin_enqueue_scripts' ] );

	}

	/**
	 * Access to the single instance of the class.
	 *
	 * @since 2.0.0
	 *
	 * @return object
	 */
	public static function single() {
		if ( empty( self::$single ) ) {
			self::$single = new self();
		}

		return self::$single;
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
		// wp_enqueue_style( 'jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css' );
		wp_register_style( 'cookie_style', CUSTOM_COOKIE_MESSAGE_PLUGIN_URL . '/assets/css/cookies.css' );
		wp_enqueue_style( 'cookie_style' );
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'custom-cookie-message-backend', CUSTOM_COOKIE_MESSAGE_PLUGIN_URL . '/assets/js/ac-custom-cookie-message-backend.js', [
			'jquery',
			'jquery-ui-slider',
			'wp-color-picker',
		], CUSTOM_COOKIE_MESSAGE_VERSION );
	}

	/**
	 * Markup output.
	 */
	public function cookies_options_display() {

		$allow_edition = false;

		$current_roles = wp_get_current_user()->roles;

		if ( array_intersect( [ 'administrator', 'editor' ], $current_roles ) ) {
			$allow_edition = true;
		}
		?>

		<div class="wrap">

			<h2><?php esc_html_e( 'Cookies Theme Options', 'custom-cookie-message' ); ?></h2>

			<?php $active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'general_options'; ?>

			<h2 class="nav-tab-wrapper">
				<a href="?page=cookies_options&tab=general_options" class="nav-tab <?php echo 'general_options' === $active_tab ? esc_attr( 'nav-tab-active' ) : ''; ?>"><?php esc_html_e( 'General Options', 'custom-cookie-message' ); ?></a> <a href="?page=cookies_options&tab=content_options" class="nav-tab <?php echo 'content_options' === $active_tab ? esc_attr( 'nav-tab-active' ) : ''; ?>"><?php esc_html_e( 'Content Options', 'custom-cookie-message' ); ?></a>
				<?php if ( $allow_edition ) : ?>
					<a href="?page=cookies_options&tab=styling_options" class="nav-tab <?php echo 'styling_options' === $active_tab ? esc_attr( 'nav-tab-active' ) : ''; ?>"><?php esc_html_e( 'Styling Options', 'custom-cookie-message' ); ?></a>                    <a href="?page=cookies_options&tab=cookie_settings" class="nav-tab <?php echo 'cookie_settings' === $active_tab ? esc_attr( 'nav-tab-active' ) : ''; ?>"><?php esc_html_e( 'Cookie Settings', 'custom-cookie-message' ); ?></a>
				<?php endif; ?>
			</h2>

			<form method="post" action="options.php">
				<?php

				$this->{$active_tab}->get_section();

				submit_button();
				?>
			</form>
		</div>
		<?php
	}

}
