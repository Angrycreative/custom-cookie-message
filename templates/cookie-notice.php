<?php
/**
 * Template cookie notice.
 *
 * @package CustomCookieMessage.
 */

$options = get_option( 'custom_cookie_message' );

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
			<p><?php esc_html_e( $options['content']['textarea_warning_text'], 'custom-cookie-message' ); ?>
				<a style="<?php echo esc_attr( $style_link ); ?>" href="<?php echo esc_url( $options['general']['cookies_page_link'] ); ?>" title="<?php esc_html_e( $options['content']['input_link_text'], 'custom-cookie-message' ); ?>"><?php esc_html_e( $options['content']['input_link_text'], 'custom-cookie-message' ); ?></a>
			</p>
			<a id="custom-cookie-message-preference" class="button <?php echo esc_attr( $style_button_class ); ?>">
				<?php esc_html_e( $options['content']['input_button_text'], 'custom-cookie-message' ); ?>
			</a>
		</div>
	</div>
</div>
<div id="custom-cookie-message-modal"></div>
