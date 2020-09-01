<?php
/**
 * Template cookie notice.
 *
 * @package CustomCookieMessage.
 */

require_once ABSPATH . 'wp-admin/includes/plugin.php';

$options = get_option( 'custom_cookie_message' );

/**
 * To support polylang translations.
 *
 * @var esc_html_e()
 */
$esc_html = 'esc_html_e';
$echo_str = '__';
if ( ( is_plugin_active( 'polylang/polylang.php' ) || is_plugin_active( 'polylang-pro/polylang.php' ) ) && function_exists( 'pll_e' ) ) {
	$esc_html = 'pll_e';
	$echo_str = 'pll__';
}

$functional_check  = 'checked';
$advertising_check = 'checked';

if ( ! empty( $_COOKIE['custom_cookie_message'] ) ) {
	$cookie_preferences = json_decode( stripslashes( $_COOKIE['custom_cookie_message'] ) );

	// JSON Cookie values are strings.
	$functional_check  = $cookie_preferences->functional ? $functional_check : "";
	$advertising_check = $cookie_preferences->advertising ? $advertising_check : "";
}

?>
<svg aria-hidden="true" style="position: absolute; width: 0; height: 0; overflow: hidden;" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
	<defs>
		<symbol id="icon-cancel-circle" viewBox="0 0 32 32">
			<title>cancel-circle</title>
			<path d="M16 0c-8.837 0-16 7.163-16 16s7.163 16 16 16 16-7.163 16-16-7.163-16-16-16zM16 29c-7.18 0-13-5.82-13-13s5.82-13 13-13 13 5.82 13 13-5.82 13-13 13z"></path>
			<path d="M21 8l-5 5-5-5-3 3 5 5-5 5 3 3 5-5 5 5 3-3-5-5 5-5z"></path>
		</symbol>
	</defs>
</svg>

<div id="custom-cookie-message-banner" class="custom-cookie-message-banner custom-cookie-message-banner--<?php echo esc_attr( $options['general']['location_options'] ); ?>">
	<div class="custom-cookie-message-banner__content">
		<p class="custom-cookie-message-banner__text">
			<?php $esc_html( $options['content']['textarea_warning_text'], 'custom-cookie-message' ); ?>
			<?php if ( $options['general']['cookies_page_link'] ) { ?>
				<a href="<?php echo esc_url( $options['general']['cookies_page_link'] ); ?>" title="<?php $esc_html( $options['content']['input_link_text'], 'custom-cookie-message' ); ?>"><?php $esc_html( $options['content']['input_link_text'], 'custom-cookie-message' ); ?></a>
			<?php } else { ?>
				<?php
				if ( $options['general']['cookies_about_page'] ) {
					if ( function_exists( 'icl_object_id' ) ) {
						$page_id = icl_object_id( $options['general']['cookies_about_page'], 'page', true );
					} else {
						$page_id = $options['general']['cookies_about_page'];
					}
					?>
					<a href="<?php echo get_permalink( $page_id );  // WPCS: XSS ok. ?>" title="<?php $esc_html( $options['content']['input_link_text'], 'custom-cookie-message' ); ?>"><?php $esc_html( $options['content']['input_link_text'], 'custom-cookie-message' ); ?></a>
				<?php
				}
}
			echo $echo_str( $options['content']['or_text_settings'], 'custom-cookie-message' ); // WPCS: XSS ok.
			?>
			<a id="custom-cookie-message-preference" href="#" title="<?php $esc_html( $options['content']['input_button_text'], 'custom-cookie-message' ); ?>"><?php $esc_html( $options['content']['input_button_text'], 'custom-cookie-message' ); ?></a>
		</p>
		<?php if ( 'textvalue' === $options['general']['close_button'] ) : ?>
		<button id="custom-cookie-message-accept_button_text" class="custom-cookie-message-banner__accept <?php echo isset( $options['styles']['button_styling'] ) ? 'custom-cookie-message-banner__button' : ''; ?> <?php echo esc_attr( $options['styles']['button_custom_class'] ); ?>">
			<?php $esc_html( $options['content']['accept_button_text'], 'custom-cookie-message' ); ?>
		</button>
		<?php endif; ?>
		<?php if ( 'xbutton' === $options['general']['close_button'] ) : ?>
		<div class="custom-cookie-message-banner__close <?php echo isset( $options['styles']['xclose_styling'] ) ? 'custom-cookie-message-banner__button' : ''; ?> <?php echo esc_attr( $options['styles']['button_custom_class'] ); ?>">
			<span><?php esc_html_e( 'Close', 'custom-cookie-message' ); ?></span>
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
				<path d="M16.24 14.83a1 1 0 0 1-1.41 1.41L12 13.41l-2.83 2.83a1 1 0 0 1-1.41-1.41L10.59 12 7.76 9.17a1 1 0 0 1 1.41-1.41L12 10.59l2.83-2.83a1 1 0 0 1 1.41 1.41L13.41 12l2.83 2.83z"/>
			</svg>
		</div>
	<?php endif; ?>
	</div>
</div>

<div id="custom-cookie-message-modal" class="custom-cookie-message-modal custom-cookie-message-modal--off">
	<div class="custom-cookie-message-modal__box">
		<div class="custom-cookie-message-modal__header">
			<div class="custom-cookie-message-modal__close">
				<?php esc_html_e( 'Close', 'custom-cookie-message' ); ?>
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
					<path d="M16.24 14.83a1 1 0 0 1-1.41 1.41L12 13.41l-2.83 2.83a1 1 0 0 1-1.41-1.41L10.59 12 7.76 9.17a1 1 0 0 1 1.41-1.41L12 10.59l2.83-2.83a1 1 0 0 1 1.41 1.41L13.41 12l2.83 2.83z"/>
				</svg>
			</div>
			<h2 class="custom-cookie-message-modal__title"><?php $esc_html( $options['cookie_granularity_settings']['headline'], 'custom-cookie-message' ); ?></h2>
		</div>
		<div class="custom-cookie-message-modal__body">
			<div class="custom-cookie-message-modal__tabs">
				<ul class="custom-cookie-message-modal__list">
					<li class="custom-cookie-message-modal__item custom-cookie-message-modal__item--required_message custom-cookie-message-modal__item--active"><?php esc_html_e( 'Required Cookies', 'custom-cookie-message' ); ?></li>
					<li class="custom-cookie-message-modal__item custom-cookie-message-modal__item--functional_message"><?php esc_html_e( 'Functional Cookies', 'custom-cookie-message' ); ?></li>
					<li class="custom-cookie-message-modal__item custom-cookie-message-modal__item--advertising_message"><?php esc_html_e( 'Advertising Cookies', 'custom-cookie-message' ); ?></li>
				</ul>
			</div>
			<div class="custom-cookie-message-modal__content">
				<div class="custom-cookie-message-modal__required_message">
					<?php echo wpautop( $echo_str( $options['cookie_granularity_settings']['required_cookies_message'], 'custom-cookie-message' ) ); // WPCS: XSS ok. ?>
				</div>
				<div class="custom-cookie-message-modal__functional_message hide ">
					<?php echo wpautop( $echo_str( $options['cookie_granularity_settings']['functional_cookies_message'], 'custom-cookie-message' ) ); // WPCS: XSS ok. ?>
					<label class="custom-cookie-message-modal__checkbox switch">
						<input type="checkbox" id="ccm-functional" <?php echo esc_attr( $functional_check ); ?>>
						<span data-cc-status-enable="<?php esc_html_e( 'Enabled', 'custom-cookie-message' ); ?>" data-cc-status-disable="<?php esc_html_e( 'Disabled', 'custom-cookie-message' ); ?>"></span>
					</label>
				</div>
				<div class="custom-cookie-message-modal__advertising_message hide">
					<?php echo wpautop( $echo_str( $options['cookie_granularity_settings']['advertising_cookies_message'], 'custom-cookie-message' ) ); // WPCS: XSS ok. ?>
					<label class="custom-cookie-message-modal__checkbox switch">
						<input type="checkbox" id="ccm-advertising" <?php echo esc_attr( $advertising_check ); ?>>
						<span data-cc-status-enable="<?php esc_html_e( 'Enabled', 'custom-cookie-message' ); ?>" data-cc-status-disable="<?php esc_html_e( 'Disabled', 'custom-cookie-message' ); ?>"></span>
					</label>
				</div>
			</div>
		</div>
		<div class="custom-cookie-message-modal__actions">
			<button id="ccm-save-preference" class="<?php echo ! empty( $options['styles']['button_styling'] ) ? 'custom-cookie-message-popup__button' : ''; ?> <?php echo esc_attr( $options['styles']['button_custom_class'] ); ?>">
				<?php $esc_html( $options['content']['save_settings_button'], 'custom-cookie-message' ); ?>
			</button>
		</div>
	</div>
</div>
