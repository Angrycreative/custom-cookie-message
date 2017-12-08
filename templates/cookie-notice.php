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

?>
<div id="custom-cookie-message-banner" class="custom-cookie-message-banner custom-cookie-message-banner--<?php echo esc_attr( $options['general']['location_options'] ); ?>" style="<?php echo esc_attr( $style_notice_banner ); ?>">
	<div class="custom-cookie-message-banner__content">
		<div class="warning-text" style="<?php echo esc_attr( $style_message ); ?>">
			<p><?php $esc_html( $options['content']['textarea_warning_text'], 'custom-cookie-message' ); ?>
				<a style="<?php echo esc_attr( $style_link ); ?>" href="<?php echo esc_url( $options['general']['cookies_page_link'] ); ?>" title="<?php $esc_html( $options['content']['input_link_text'], 'custom-cookie-message' ); ?>"><?php $esc_html( $options['content']['input_link_text'], 'custom-cookie-message' ); ?></a> <a id="custom-cookie-message-preference" class="btn btn-default <?php echo esc_attr( $style_button_class ); ?>">
					<?php $esc_html( $options['content']['input_button_text'], 'custom-cookie-message' ); ?>
				</a>
			</p>
		</div>
	</div>
</div>
<div id="custom-cookie-message-modal">
	<div></div>
	<div>
		<div><?php echo apply_filters( 'the_content', $options['cookie_granularity_settings']['required_cookies_message'] ); // WPCS: XSS ok. ?></div>
		<div><?php echo apply_filters( 'the_content', $options['cookie_granularity_settings']['functional_cookies_message'] ); // WPCS: XSS ok. ?></div>
		<div><?php echo apply_filters( 'the_content', $options['cookie_granularity_settings']['advertising_cookies_message'] ); // WPCS: XSS ok. ?></div>
	</div>
</div>
