<?php
/**
 * Shortcode
 *
 * @package CustomCookieMessage
 */

namespace CustomCookieMessage;

/**
 * Class Shortcode
 *
 * @package CustomCookieMessage
 */
class Shortcode {

	/**
	 * Shortcode Cookie Preferences.
	 *
	 * @param arry $atts Attributes shortcode.
	 *
	 * @return string
	 */
	public static function ccm_shortcode_preferences( $atts ) {
		$atts = shortcode_atts(
			[
				'style' => 'link',
			],
			$atts
		);

		$class  = '';
		$option = get_option( 'custom_cookie_message', [] );

		if ( 'button' === $atts['style'] ) {
			$class = apply_filters( 'ccm_shortcode_preferences_class', 'btn btn-default custom-cookie-message-banner__button' );
		}

		ob_start();
		echo '<a id="ccm_cookie_preferences" class="' . $class . '">' . esc_html( $option['content']['shortcode_text'] ) . '</a>'; // WPCS: XSS ok.

		return ob_get_clean();

	}
}
