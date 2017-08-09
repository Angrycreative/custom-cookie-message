<?php

namespace CustomCookieMessage\Forms;

class Admin {

	static protected $instance;

	public function __construct() {
		add_action( 'admin_menu', [ $this, 'cookies_menu' ] );
		add_action( 'admin_init', [ $this, 'cookies_initialize_general_options' ] );
		add_action( 'admin_init', [ $this, 'cookies_initialize_content_options' ] );
		add_action( 'admin_init', [ $this, 'cookies_initialize_styling_options' ] );

		add_action( 'admin_enqueue_scripts', [ $this, 'register_backend_plugin_styles' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'register_backend_plugin_scripts' ] );
	}

	/**
	 * Access to the single instance of the class.
	 *
	 * @since 2.0.0
	 *
	 * @return object
	 */
	static public function instance() {
		if ( empty( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function register_backend_plugin_styles() {
		wp_enqueue_style( 'jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css' );
		wp_register_style( 'cookie_style', CUSTOM_COOKIE_MESSAGE_PLUGIN_URL . '/css/cookies.css' );
		wp_enqueue_style( 'cookie_style' );
		wp_enqueue_style( 'wp-color-picker' );
	}

	public function register_backend_plugin_scripts() {
		wp_enqueue_script( 'variation-custom-cookie-script', CUSTOM_COOKIE_MESSAGE_PLUGIN_URL . '/js/ac-custom-cookie-message-backend.js', array(
			'jquery',
			'jquery-ui-slider',
			'wp-color-picker'
		) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_js' ) );
	}

	public function cookies_options_display() {

		$allow_edition = false;

		$current_roles = wp_get_current_user()->roles;

		if ( ! ! array_intersect( [ 'administrator', 'editor' ], $current_roles ) ) {
			$allow_edition = true;
		}

		?>
		<!-- Create a header in the default WordPress 'wrap' container -->
		<div class="wrap">

			<h2><?php _e( 'Cookies Theme Options', 'cookies-message' ); ?></h2>
			<!-- Give user feeback that a setting has been saved-->
			<?php //settings_errors();
			?>
			<!-- isset works as an "onclick" for the tabs to set active tab-->
			<?php $active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'general_options'; ?>

			<!-- Tabs -->
			<h2 class="nav-tab-wrapper">
				<a href="?page=cookies_options&tab=general_options"
				   class="nav-tab <?php echo $active_tab == 'general_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'General Options', 'cookies' ); ?></a>
				<a href="?page=cookies_options&tab=content_options"
				   class="nav-tab <?php echo $active_tab == 'content_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Content Options', 'cookies' ); ?></a>
				<?php if ( $allow_edition ): ?>
					<a href="?page=cookies_options&tab=styling_options"
					   class="nav-tab <?php echo $active_tab == 'styling_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Styling Options', 'cookies' ); ?></a>
				<?php endif; ?>
			</h2>

			<form method="post" action="options.php">

				<?php if ( $active_tab == 'general_options' ) {
					settings_fields( 'cookies_general_options' );
					do_settings_sections( 'cookies_general_options' );
				} elseif ( $active_tab == 'content_options' ) {
					settings_fields( 'cookies_content_options' );
					do_settings_sections( 'cookies_content_options' );
				} else {
					settings_fields( 'cookies_styling_options' );
					do_settings_sections( 'cookies_styling_options' );
				}

				submit_button(); ?>
			</form>
		</div>
		<?php
	}

	/**
	 * WP Menu.
	 */
	public function cookies_menu() {
		add_options_page(
			'Cookies',
			'Cookies',
			'administrator',
			'cookies_options',
			[
				$this,
				'cookies_options_display'
			]
		);
	}

	/**
	 * Define settings.
	 */
	public function cookies_initialize_general_options() {

		add_settings_section(
			'general_options_section',
			esc_html__( 'General Options', 'cookie-message' ),
			[
				$this,
				'cookies_general_options_callback'
			],
			'cookies_general_options'
		);

		add_settings_field(
			'Location Options',
			__( 'Select location of message:', 'cookie-message' ),
			[
				$this,
				'cookies_select_position_callback'
			],
			'cookies_general_options',
			'general_options_section'
		);

		add_settings_field(
			'cookies_page_link',
			__( 'Enter url to the page about cookies:', 'cookie-message' ),
			[ $this, 'cookies_page_link_callback' ],
			'cookies_general_options',
			'general_options_section'
		);

		register_setting(
			'cookies_general_options',
			'cookies_general_options',
			[ $this, 'cookies_validate_options' ]
		);
	}

	/**
	 *
	 */
	public function cookies_initialize_content_options() {

		add_settings_section(
			'content_options_section',
			__( 'Content Options', 'cookie-message' ),
			[ $this, 'cookies_content_options_callback' ],
			'cookies_content_options'
		);

		add_settings_field(
			'Textarea Warning Text',
			__( 'Enter warning text:', 'cookie-message' ),
			[ $this, 'cookies_textarea_warning_text_callback' ],
			'cookies_content_options',
			'content_options_section'
		);

		add_settings_field(
			'Input Link Text',
			__( 'Enter link text:', 'cookie-message' ),
			[ $this, 'cookies_input_link_text_callback' ],
			'cookies_content_options',
			'content_options_section'
		);

		add_settings_field(
			'Input Button Text',
			__( 'Enter button text:', 'cookie-message' ),
			[ $this, 'cookies_input_button_text_callback' ],
			'cookies_content_options',
			'content_options_section'
		);

		register_setting(
			'cookies_content_options',
			'cookies_content_options',
			[ $this, 'cookies_validate_options' ]
		);
	}

	public function cookies_initialize_styling_options() {

		add_settings_section(
			'styling_options_section',
			__( 'Styling Options', 'cookie-message' ),
			[ $this, 'cookies_styling_options_callback' ],
			'cookies_styling_options'
		);

		add_settings_field(
			'Background Color',
			__( 'Message container background', 'cookie-message' ),
			[ $this, 'cookies_message_color_picker_callback' ],
			'cookies_styling_options',
			'styling_options_section' );

		add_settings_field(
			'Message Size',
			__( 'Message container padding top and bottom', 'cookie-message' ),
			[ $this, 'cookies_message_height_slider_callback' ],
			'cookies_styling_options',
			'styling_options_section' );

		add_settings_field(
			'Opacity',
			__( 'Message container opacity', 'cookie-message' ),
			[ $this, 'cookies_opacity_slider_callback' ],
			'cookies_styling_options',
			'styling_options_section' );

		add_settings_field(
			'text_font',
			__( 'Text font', 'cookie-message' ),
			[ $this, 'cookies_text_font_callback' ],
			'cookies_styling_options',
			'styling_options_section' );

		add_settings_field(
			'Text Color',
			__( 'Text Color', 'cookie-message' ),
			[ $this, 'cookies_text_color_picker_callback' ],
			'cookies_styling_options',
			'styling_options_section' );

		add_settings_field(
			'Link Color',
			__( 'Link Color', 'cookie-message' ),
			[ $this, 'cookies_link_color_picker_callback' ],
			'cookies_styling_options',
			'styling_options_section' );

		add_settings_field(
			'add_button_class',
			__( 'Button classes', 'cookie-message' ),
			[ $this, 'cookies_add_button_class_callback' ],
			'cookies_styling_options',
			'styling_options_section' );

		add_settings_field(
			'Button Color',
			__( 'Button Color', 'cookie-message' ),
			[ $this, 'cookies_button_color_picker_callback' ],
			'cookies_styling_options',
			'styling_options_section' );

		add_settings_field(
			'Button Hover Color',
			__( 'Button Hover Color', 'cookie-message' ),
			[ $this, 'cookies_button_hover_color_picker_callback' ],
			'cookies_styling_options',
			'styling_options_section' );

		add_settings_field(
			'Button Text Color',
			__( 'Button Text Color', 'cookie-message' ),
			[ $this, 'cookies_button_text_color_picker_callback' ],
			'cookies_styling_options',
			'styling_options_section' );

		add_settings_field(
			'Button_height',
			__( 'Button Height', 'cookie-message' ),
			[ $this, 'cookies_button_height_slider_callback' ],
			'cookies_styling_options',
			'styling_options_section' );

		add_settings_field(
			'button_width',
			__( 'Button Width', 'cookie-message' ),
			[ $this, 'cookies_button_width_slider_callback' ],
			'cookies_styling_options',
			'styling_options_section' );

		register_setting(
			'cookies_styling_options',
			'cookies_styling_options',
			[ $this, 'cookies_validate_options' ]
		);
	}

	public function cookies_general_options_callback() {
		echo '<p>' . esc_html_e( 'Select where the cookie message should be displayed and enter the URL to the page about cookies.', 'cookie-message' ) . '</p>';
	}

	public function cookies_content_options_callback() {
		echo '<p>' . esc_html_e( 'Enter the content in the cookie message.', 'cookie-message' ) . '</p>';
	}

	public function cookies_styling_options_callback() {
		echo '<p>' . esc_html_e( 'Select the styling for the cookie message.', 'cookie-message' ) . '</p>';
	}

	public function cookies_select_position_callback() {
		// Get the options for which this setting is in
		$options = get_option( 'cookies_general_options' );

		$html = '<select id="location_options" name="cookies_general_options[location_options]">';
		$html .= '<option value="top-fixed"' . selected( $options['location_options'], 'top-fixed', false ) . '>' . __( 'Top as overlay', 'cookie-message' ) . '</option>';
		$html .= '<option value="top-static"' . selected( $options['location_options'], 'top-static', false ) . '>' . __( 'Top in content', 'cookie-message' ) . '</option>';
		$html .= '<option value="bottom-fixed"' . selected( $options['location_options'], 'bottom-fixed', false ) . '>' . __( 'Bottom as overlay', 'cookie-message' ) . '</option>';
		$html .= '</select>';

		echo $html;
	}

	public function cookies_page_link_callback() {
		$options = get_option( 'cookies_general_options' );
		//echo ($options['cookies_page_link']);

		echo '<input type="text" id="cookies_page_link" name="cookies_general_options[cookies_page_link]" value="' . $options['cookies_page_link'] . '" />';
	}

	public function cookies_input_button_text_callback() {
		$options = get_option( 'cookies_content_options' );

		echo '<input type="text" id="input_button_text" name="cookies_content_options[input_button_text]" value="' . $options['input_button_text'] . '" />';
	}

	public function cookies_input_link_text_callback() {
		$options = get_option( 'cookies_content_options' );

		echo '<input type="text" id="input_link_text" name="cookies_content_options[input_link_text]" value="' . $options['input_link_text'] . '" />';
	}

	public function cookies_textarea_warning_text_callback() {
		$options = get_option( 'cookies_content_options' );

		echo '<textarea id="textarea_warning_text" name="cookies_content_options[textarea_warning_text]" rows="5" cols="50">' . $options['textarea_warning_text'] . '</textarea>';
	}

	public function cookies_message_color_picker_callback() {
		$options = get_option( 'cookies_styling_options' );

		$val = ( isset( $options['message_color_picker'] ) ) ? $options['message_color_picker'] : '';
		echo '<input type="text" id="message_color_picker" name="cookies_styling_options[message_color_picker]" value="' . $val . '" class="cpa-color-picker" >';
	}

	public function cookies_button_color_picker_callback() {
		$options = get_option( 'cookies_styling_options' );

		$val = ( isset( $options['button_color_picker'] ) ) ? $options['button_color_picker'] : '';
		echo '<input type="text" id="button_color_picker" name="cookies_styling_options[button_color_picker]" value="' . $val . '" class="cpa-color-picker" >';
	}

	public function cookies_button_hover_color_picker_callback() {
		$options = get_option( 'cookies_styling_options' );

		$val = ( isset( $options['button_hover_color_picker'] ) ) ? $options['button_hover_color_picker'] : '';
		echo '<input type="text" id="button_hover_color_picker" name="cookies_styling_options[button_hover_color_picker]" value="' . $val . '" class="cpa-color-picker" >';
	}

	public function cookies_button_text_color_picker_callback() {
		$options = get_option( 'cookies_styling_options' );

		//$val = ( isset( $options['button_text_color_picker'] ) ) ? $options['button_text_color_picker'] : '';
		$val = $options['button_text_color_picker'];
		echo '<input type="text" id="button_text_color_picker" name="cookies_styling_options[button_text_color_picker]" value="' . $val . '" class="cpa-color-picker" >';
	}

	public function cookies_text_color_picker_callback() {
		$options = get_option( 'cookies_styling_options' );

		$val = ( isset( $options['text_color_picker'] ) ) ? $options['text_color_picker'] : '';
		echo '<input type="text" id="text_color_picker" name="cookies_styling_options[text_color_picker]" value="' . $val . '" class="cpa-color-picker" >';
	}

	public function cookies_link_color_picker_callback() {
		$options = get_option( 'cookies_styling_options' );

		$val = ( isset( $options['link_color_picker'] ) ) ? $options['link_color_picker'] : '';
		echo '<input type="text" id="link_color_picker" name="cookies_styling_options[link_color_picker]" value="' . $val . '" class="cpa-color-picker" >';
	}

	public function cookies_add_button_class_callback() {
		$options = get_option( 'cookies_styling_options' );

		$val = ( isset( $options['add_button_class'] ) ) ? $options['add_button_class'] : '';
		echo '<input type="text" id="add_button_class" name="cookies_styling_options[add_button_class]" value="' . $val . '" />';
		echo '<div><p>Replace the standard styling of the button by specifying your own class. If several classes, separate with space. Leave empty to keep the standard styling.</p></div>';
	}

	public function cookies_opacity_slider_callback() {
		$options = get_option( 'cookies_styling_options' );

		$val = ( isset( $options['opacity_slider_amount'] ) ) ? $options['opacity_slider_amount'] : '100';
		echo '<input type="text" id="opacity_slider_amount" name="cookies_styling_options[opacity_slider_amount]" value="' . $val . '" readonly style="border:0; color:#f6931f; font-weight:bold;">';
		echo '<div id="opacity_slider"></div>';

	}

	public function cookies_message_height_slider_callback() {
		$options = get_option( 'cookies_styling_options' );

		$val = ( isset( $options['message_height_slider_amount'] ) ) ? $options['message_height_slider_amount'] : '10';
		echo '<input type="text" id="message_height_slider_amount" name="cookies_styling_options[message_height_slider_amount]" value="' . $val . '" readonly style="border:0; color:#f6931f; font-weight:bold;">';
		echo '<div id="message_height_slider"></div>';
	}

	public function cookies_button_height_slider_callback() {
		$options = get_option( 'cookies_styling_options' );

		$val = ( isset( $options['button_height_slider_amount'] ) ) ? $options['button_height_slider_amount'] : '5';
		echo '<input type="text" id="button_height_slider_amount" name="cookies_styling_options[button_height_slider_amount]" value="' . $val . '" readonly style="border:0; color:#f6931f; font-weight:bold;">';
		echo '<div id="button_height_slider"></div>';
	}

	public function cookies_button_width_slider_callback() {
		$options = get_option( 'cookies_styling_options' );

		$val = ( isset( $options['button_width_slider_amount'] ) ) ? $options['button_width_slider_amount'] : '10';
		echo '<input type="text" id="button_width_slider_amount" name="cookies_styling_options[button_width_slider_amount]" value="' . $val . '" readonly style="border:0; color:#f6931f; font-weight:bold;">';
		echo '<div id="button_width_slider"></div>';
	}

	public function cookies_text_font_callback() {
		$options = get_option( 'cookies_styling_options' );

		$val = ( isset( $options['text_font'] ) ) ? $options['text_font'] : '';
		echo '<input type="text" id="text_font" name="cookies_styling_options[text_font]" value="' . $val . '" />';
		echo '<div><p>Replace your standard paragraph font-family. Leave empty for the standard font-family</p></div>';
	}

	function cookies_validate_options( $input ) {

		// Create our array for storing the validated options
		$output = array();

		// Loop through each of the incoming options
		foreach ( $input as $key => $value ) {

			// Check to see if the current option has a value. If so, process it.
			if ( isset( $input[ $key ] ) ) {

				// Strip all HTML and PHP tags and properly handle quoted strings
				$output[ $key ] = strip_tags( stripslashes( $input[ $key ] ) );

			} // end if

		} // end foreach

		// Return the array processing any additional functions filtered by this action
		return apply_filters( 'cookies_validate_styling_options', $output, $input );
	}

}
