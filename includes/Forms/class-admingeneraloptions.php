<?php

namespace CustomCookieMessage\Forms;

class AdminGenralOptions {

	static protected $instance;

	/**
	 * CookieList constructor.
	 */
	public function __construct() {
		add_action( 'admin_init', [ $this, 'cookies_initialize_general_options' ] );
	}

	/**
	 * Access to the single instance of the class.
	 *
	 * @since 2.0.0
	 *
	 * @return AdminStylingOptions
	 */
	static public function instance() {
		if ( empty( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function getSection() {
		settings_fields( 'cookies_general_options' );
		do_settings_sections( 'cookies_general_options' );
	}

}
