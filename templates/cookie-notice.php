<?php
/**
 * Template cookie notice.
 *
 * @package CustomCookieMessage.
 */

include_once ABSPATH . 'wp-admin/includes/plugin.php';

$options = get_option( 'custom_cookie_message' );

/**
 * To support polylang translations.
 *
 * @var esc_html_e()
 */
$esc_html = 'esc_html_e';

if ( is_plugin_active( 'polylang/polylang.php' ) || is_plugin_active( 'polylang-pro/polylang.php' ) ) {
	$esc_html = 'pll_e';
}

list( $r, $g, $b ) = sscanf( $options['styles']['message_color_picker'], '#%02x%02x%02x' );
$background_opacity  = $options['styles']['opacity_slider_amount'] / 100;
$style_notice_banner = "background-color: rgba({$r}, {$g}, {$b}, {$background_opacity});";
$style_notice_banner .= ' padding: ' . $options['styles']['message_height_slider_amount'] . 'px 0;';

$style_message = 'color: ' . $options['styles']['text_color_picker'] . ';';
$style_message .= empty( $options['styles']['text_font'] ) ? '' : 'font-family: ' . $options['styles']['text_font'] . ';';

$style_link = 'color: ' . $options['styles']['link_color_picker'] . ';';

$style_button_class = $options['styles']['add_button_class'];

list( $r, $g, $b ) = sscanf( $options['styles']['modal_bg'], '#%02x%02x%02x' );
$modal_background_opacity = $options['styles']['modal_bg_opacity'] / 100;
$modal_style              = "background-color: rgba({$r}, {$g}, {$b}, {$modal_background_opacity});";

?>
<div id="custom-cookie-message-banner" class="custom-cookie-message-banner custom-cookie-message-banner--<?php echo esc_attr( $options['general']['location_options'] ); ?>" style="<?php echo esc_attr( $style_notice_banner ); ?>">
	<div class="custom-cookie-message-banner__content">
		<div class="warning-text" style="<?php echo esc_attr( $style_message ); ?>">
			<p><?php $esc_html( $options['content']['textarea_warning_text'], 'custom-cookie-message' ); ?>
				<a style="<?php echo esc_attr( $style_link ); ?>" href="<?php echo esc_url( $options['general']['cookies_page_link'] ); ?>" title="<?php $esc_html( $options['content']['input_link_text'], 'custom-cookie-message' ); ?>"><?php $esc_html( $options['content']['input_link_text'], 'custom-cookie-message' ); ?></a>
				<a id="custom-cookie-message-preference" class="btn btn-default <?php echo esc_attr( $style_button_class ); ?>">
					<?php $esc_html( $options['content']['input_button_text'], 'custom-cookie-message' ); ?>
				</a>
			</p>
		</div>
	</div>
</div>
<div id="custom-cookie-message-modal" class="custom-cookie-message-modal custom-cookie-message-modal--off" style="<?php echo esc_attr( $modal_style ); ?>">
	<div class="custom-cookie-message-modal__box">
		<div class="custom-cookie-message-modal__close"><?php esc_html_e( 'Close', 'custom-cookie-message' ); ?></div>
		<h2 class="custom-cookie-message-modal__title"><?php $esc_html( $options['cookie_granularity_settings']['headline'], 'custom-cookie-message' ); ?></h2>
		<div class="custom-cookie-message-modal__tabs">
			<ul class="custom-cookie-message-modal__list">
				<li class="custom-cookie-message-modal__item custom-cookie-message-modal__item--required_message custom-cookie-message-modal__item--active"><?php esc_html_e( 'Required', 'custom-cookie-message' ); ?></li>
				<li class="custom-cookie-message-modal__item custom-cookie-message-modal__item--functional_message"><?php esc_html_e( 'Functional', 'custom-cookie-message' ); ?></li>
				<li class="custom-cookie-message-modal__item custom-cookie-message-modal__item--advertising_message"><?php esc_html_e( 'Advertising', 'custom-cookie-message' ); ?></li>
			</ul>
		</div>
		<div class="custom-cookie-message-modal__content">
			<div class="custom-cookie-message-modal__required_message"><?php echo wpautop( $options['cookie_granularity_settings']['required_cookies_message'] ); // WPCS: XSS ok. ?></div>
			<div class="custom-cookie-message-modal__functional_message hide"><?php echo wpautop( $options['cookie_granularity_settings']['functional_cookies_message'] ); // WPCS: XSS ok. ?></div>
			<div class="custom-cookie-message-modal__advertising_message hide"><?php echo wpautop( $options['cookie_granularity_settings']['advertising_cookies_message'] ); // WPCS: XSS ok. ?></div>
		</div>
		<div class="custom-cookie-message-modal__actions">
			<a id="custom-cookie-message-preference" class="btn btn-default"><?php $esc_html( $options['content']['input_button_text'], 'custom-cookie-message' ); ?></a>
		</div>
	</div>
</div>
