<?php
/**
 * AdminGeneralOptions
 *
 * @package CustomCookieMessage
 */

namespace CustomCookieMessage\Forms;

use CustomCookieMessage\Main;

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
	 * Export / Import Settings
	 *
	 * @var AdminExportSettings
	 */
	private $export_import;
	/**
	 * AdminBase constructor.
	 */
	public function __construct() {
		parent::__construct();
		add_action( 'admin_menu', [ $this, 'cookies_menu' ] );
		register_setting( 'custom_cookie_message_options', 'custom_cookie_message', [ $this, 'ccm_validate_options' ] );
		global $pagenow;
		if ( ( 'options-general.php' === $pagenow ) && ( 'custom_cookie_message_options' === $_GET['page'] ) ) {
			$this->general_options = AdminGeneralOptions::single();
			$this->content_options = AdminContentOptions::single();
			$this->styling_options = AdminStylingOptions::single();
			$this->cookie_settings = AdminCookieSettings::single();
			$this->export_import   = AdminExportSettings::single();


			add_action( 'admin_enqueue_scripts', [ $this, 'ccm_admin_enqueue_scripts' ] );
		}
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
		add_options_page(
			esc_html( 'Custom Cookie Message', 'custom-cookie-message' ), 'Custom Cookie Message', 'administrator', 'custom_cookie_message_options', [
				$this,
				'cookies_options_display',
			]
		);
	}

	/**
	 * Enqueue Scripts.
	 */
	public function ccm_admin_enqueue_scripts() {
		wp_enqueue_style( 'jquery-style', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css' );
		wp_enqueue_style( 'custom-cookie-message-admin-style', CUSTOM_COOKIE_MESSAGE_PLUGIN_URL . '/assets/css/custom-cookie-message-admin-style.css' );
		wp_enqueue_style( 'wp-color-picker' );
		wp_register_script( 'ccm-suggest', CUSTOM_COOKIE_MESSAGE_PLUGIN_URL . '/assets/js/ccm-suggest.js', [], Main::version() );
		wp_enqueue_script(
			'custom-cookie-message-admin-style',
			CUSTOM_COOKIE_MESSAGE_PLUGIN_URL . '/assets/js/custom-cookie-message-backend.js',
			[
				'jquery',
				'jquery-ui-slider',
				'jquery-ui-autocomplete',
				'wp-color-picker',
				'ccm-suggest',
			],
			Main::version()
		);
		wp_localize_script(
			'custom-cookie-message-admin-style', 'customCookieMessageAdminLocalize', [
				'rest_url'           => rest_url( 'custom-cm/upgrade' ),
				'rest_post_link'     => rest_url( 'custom-cm/post_link' ),
				'rest_cookie_list'   => rest_url( 'custom-cm/cookie_list' ),
				'ccm_nonce'          => wp_create_nonce( 'custom_cookie_message_upgrade' ),
				'wp_rest_nonce'      => wp_create_nonce( 'wp_rest' ),
				'life_time'          => [
					'week_seconds'  => WEEK_IN_SECONDS,
					'month_seconds' => MONTH_IN_SECONDS,
					'year_seconds'  => YEAR_IN_SECONDS,
				],
				'life_time_messages' => [
					'no_life_time'       => esc_html__( 'Until session expires (closing browser)', 'custom-cookie-message' ),
					'week_life_time'     => esc_html__( 'Weekly', 'custom-cookie-message' ),
					'month_life_time'    => esc_html__( 'Montly', 'custom-cookie-message' ),
					'year_life_time'     => esc_html__( 'Yearly', 'custom-cookie-message' ),
					'end_less_life_time' => esc_html__( 'Until end of times', 'custom-cookie-message' ),
				],
			]
		);
	}

	/**
	 * Markup output.
	 */
	public function cookies_options_display() {

		$allow_edition = false;

		$page_title = get_admin_page_title();

		if ( current_user_can( 'manage_options' ) ) {
			$allow_edition = true;
		}
		?>

		<div class="wrap">

			<h2><?php echo $page_title; // WPCS: XSS ok. ?></h2>

			<?php $active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'general_options'; // WPCS: csrf ok. ?>

			<h2 class="nav-tab-wrapper">
				<a href="?page=custom_cookie_message_options&tab=general_options" class="nav-tab <?php echo 'general_options' === $active_tab ? esc_attr( 'nav-tab-active' ) : ''; ?>"><?php esc_html_e( 'General Options', 'custom-cookie-message' ); ?></a> <a href="?page=custom_cookie_message_options&tab=content_options" class="nav-tab <?php echo 'content_options' === $active_tab ? esc_attr( 'nav-tab-active' ) : ''; ?>"><?php esc_html_e( 'Content Options', 'custom-cookie-message' ); ?></a>
				<?php if ( $allow_edition ) : ?>
					<a href="?page=custom_cookie_message_options&tab=styling_options" class="nav-tab <?php echo 'styling_options' === $active_tab ? esc_attr( 'nav-tab-active' ) : ''; ?>"><?php esc_html_e( 'Styling Options', 'custom-cookie-message' ); ?></a>                    <a href="?page=custom_cookie_message_options&tab=cookie_settings" class="nav-tab <?php echo 'cookie_settings' === $active_tab ? esc_attr( 'nav-tab-active' ) : ''; ?>"><?php esc_html_e( 'Cookie Settings', 'custom-cookie-message' ); ?></a>
				<?php endif; ?>
				<a href="?page=custom_cookie_message_options&tab=export_import" class="nav-tab <?php echo 'export_import' === $active_tab ? esc_attr( 'nav-tab-active' ) : ''; ?>"><?php esc_html_e( 'Export/Import', 'custom-cookie-message' ); ?></a>
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
