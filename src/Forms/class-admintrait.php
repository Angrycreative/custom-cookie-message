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

		if ( ! empty( $input['import'] ) ) {
			$input = unserialize( $input['import'] );
		}
		if ( empty( $input['cookie_granularity_settings'] ) ) {
			array_walk_recursive(
				$input, function ( &$item, $key ) {
					$item = sanitize_textarea_field( $item );
				}
			);
		}

		$output = wp_parse_args( $input, $this->options );

		return apply_filters( 'ccm_validate_options', $output );
	}

}
