<?php
/**
 * Update file
 *
 * @package CustomCookieMessage
 */

namespace CustomCookieMessage;

/**
 * Class Update
 *
 * @package CustomCookieMessage
 */
class Update {

	/**
	 * Update verion 2.0.0 from 1.6.*
	 */
	public static function custom_cookie_message_200() {
		$general = get_option( 'cookies_general_options' );
		$content = get_option( 'cookies_content_options' );
		$styling = get_option( 'cookies_styling_options' );

		$options = get_option( 'custom_cookie_message' );

		if ( empty( $options ) ) {
			Main::plugin_activation();
			$options = get_option( 'custom_cookie_message' );
		}

		$options['general'] = wp_parse_args( $general, $options['general'] );
		$options['content'] = wp_parse_args( $content, $options['content'] );
		$options['styles']  = wp_parse_args( $styling, $options['styles'] );

		update_option( 'custom_cookie_message', $options );

		set_transient( 'cookies_general_options', $general, YEAR_IN_SECONDS );
		set_transient( 'cookies_content_options', $content, YEAR_IN_SECONDS );
		set_transient( 'cookies_styling_options', $styling, YEAR_IN_SECONDS );

		delete_option( 'cookies_general_options' );
		delete_option( 'cookies_content_options' );
		delete_option( 'cookies_styling_options' );

	}
}
