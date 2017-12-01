<?php
/**
 * Trait Class.
 *
 * @package CustomCookieMessage
 */

namespace CustomCookieMessage\Forms;

/**
 * Trait AdminTrait
 *
 * @package CustomCookieMessage
 */
trait AdminTrait {

	/**
	 * Callback section.
	 */
	public function get_section() {
		settings_fields( $this->option_group );
		do_settings_sections( $this->section_page );
	}

	/**
	 * Validation options.
	 *
	 * @param array $input Input post.
	 *
	 * @return mixed|void
	 */
	public function ccm_validate_options( array $input ) {

		$output = [];

		foreach ( $input as $key => $value ) {

			if ( ! empty( $value ) && is_array( $value ) ) {
				$output[ $key ] = $this->ccm_validate_options( $value );
			}

			if ( ! empty( $value ) && ! is_array( $value ) ) {
				$output[ $key ] = sanitize_text_field( $value );
			}
		}

		$output = wp_parse_args( $output, $this->options );

		return apply_filters( 'ccm_validate_options', $output, $input );
	}

}
