<?php
$options = get_option( 'custom_cookie_message' );
?>
<div id="custom-cookie-message-banner" class="custom-cookie-message-banner custom-cookie-message-banner--<?php echo esc_attr( $options['general']['location_options'] ); ?>">
	<div class="cookie-block"></div>
	<div class="cookie-content">
		<div class="warning-text">
			<p <?php echo empty( $options['styles']['text_font'] ) ? esc_attr( '' ) : esc_attr( 'style="font-family: ' . $options['styles']['text_font'] ) . '"'; ?> ><?php esc_html_e( $options['content']['textarea_warning_text'], 'custom-cookie-message' ); ?>
				<a style="color: <?php echo esc_attr( $options['styles']['link_color_picker'] ); ?>" href=" <?php echo esc_url( $options['general']['cookies_page_link'] ); ?>"><?php esc_html_e( $options['content']['input_link_text'], 'custom-cookie-message' ); ?></a>
			</p>
			<a id="cookies-button-ok" class="cookies-button-ok <?php echo ! empty( $options['styling']['add_button_class'] ) ? esc_attr( $options['styles']['add_button_class'] ) : esc_attr( 'default-cookie-button-style' ); ?>">
				<?php esc_html_e( $options['content']['input_button_text'], 'custom-cookie-message' ); ?>
			</a>
		</div>
	</div>
</div>
<div id="custom-cookie-message-modal">

</div>
