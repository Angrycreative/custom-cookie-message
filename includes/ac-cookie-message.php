<?php
/**
 * Author: Johan Sylvan
 * Date: 2016-09-27
 * Time: 08:53
 */

if ( ! class_exists( 'AC_Custom_Cookie_Message' ) ) {

	class AC_Custom_Cookie_Message {

		public $options;

		function __construct() {

			add_action( 'init', array( $this, 'init' ), 30 );
			add_action( 'init', array( $this, 'cookie_start' ) );
		}

		function init() {

			add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_styles' ), 100 );
			add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_scripts' ) );

			add_action( 'admin_enqueue_scripts', array( $this, 'register_backend_plugin_styles' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'register_backend_plugin_scripts' ) );

			add_action( 'wp_ajax_nopriv_setcookie', array( $this, 'cookie_setcookie' ) );
			add_action( 'wp_ajax_setcookie', array( $this, 'cookie_setcookie' ) );

			if ( is_admin() ) {
				$this->admin_init();
			}

			//$location = get_option('cookies_general_options');

			add_action( 'wp_footer', array( $this, 'display_frontend_notice' ) );

		}

		function admin_init() {

			add_action( 'admin_menu', array( $this, 'cookies_menu' ) );

			add_action( 'admin_init', array( $this, 'cookies_initialize_general_options' ) );
			add_action( 'admin_init', array( $this, 'cookies_initialize_content_options' ) );
			add_action( 'admin_init', array( $this, 'cookies_initialize_styling_options' ) );
		}

		public static function plugin_activation() {
			if ( false == get_option( 'cookies_general_options' ) ) {
				add_option( 'cookies_general_options', static::cookies_default_general_options() );
			}
			if ( false == get_option( 'cookies_content_options' ) ) {
				add_option( 'cookies_content_options', static::cookies_default_content_options() );
			}
			if ( false == get_option( 'cookies_styling_options' ) ) {
				add_option( 'cookies_styling_options', static::cookies_default_styling_options() );
			}

		}

		public function cookie_start() {

			load_plugin_textdomain( 'cookie-message' );
		}

		//Register and enqueue style sheet.
		public function register_plugin_styles() {
			wp_register_style( 'cookie_style', CUSTOM_COOKIE_MESSAGE_PLUGIN_URL . '/css/cookies.css' );

			wp_enqueue_style( 'cookie_style' );

		}


		public function register_plugin_scripts() {

			// embed the javascript file that makes the AJAX request
			wp_enqueue_script( 'my-ajax-request', CUSTOM_COOKIE_MESSAGE_PLUGIN_URL . 'js/ac-custom-cookie-message-frontend.js', array( 'jquery' ) );

			// declare the URL to the file that handles the AJAX request (wp-admin/admin-ajax.php)
			wp_localize_script( 'my-ajax-request', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
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

		function cookies_menu() {
			add_options_page(
				'Cookies',                              // The title to be displayed in the browser window for this page.
				'Cookies',                              // The text to be displayed for this menu item
				'administrator',                        // Which type of users can see this menu item
				'cookies_options',                      // The unique ID - that is, the slug - for this menu item
				array(
					$this,
					'cookies_options_display'
				) // The name of the function to call when rendering this menu's page
			);
		}

		function display_frontend_notice() {
			AC_Custom_Cookie_Message::get_template( 'cookie-notice.php' );
		}

		static function get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
			if ( ! empty( $args ) && is_array( $args ) ) {
				extract( $args );
			}

			$located = static::locate_template( $template_name, $template_path, $default_path );

			if ( ! file_exists( $located ) ) {
				_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $located ), '2.1' );

				return;
			}

			include( $located );
		}


		static function locate_template( $template_name, $template_path = '', $default_path = '' ) {
			if ( ! $template_path ) {
				$template_path = CUSTOM_COOKIE_MESSAGE_PLUGIN_PATH . '/';
			}

			if ( ! $default_path ) {
				$default_path = CUSTOM_COOKIE_MESSAGE_PLUGIN_PATH . '/views/';
			}

			// Look within passed path within the theme - this is priority.
			$template = locate_template(
				array(
					trailingslashit( $template_path ) . $template_name,
					$template_name
				)
			);

			// Get default template/
			if ( ! $template ) {
				$template = $default_path . $template_name;
			}

			// Return what we found.
			return $template;
		}

		public function cookie_setcookie() {
			setcookie( 'cookie-warning-message', 15, 30 * DAYS_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
			wp_send_json( 1 );
		}


		function cookies_options_display() {

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
		/* ------------------------------------------------------------------------ *
		 * Setting Registration
		 * ------------------------------------------------------------------------ */

		//Provides default values for the General Options.
		static function cookies_default_general_options() {

			$defaults = array(
				'location_options'  => 'top-fixed',
				'cookies_page_link' => ''
			);

			return apply_filters( 'cookies_default_general_options', $defaults );
		}

		//Provides default values for the Content Options.
		static function cookies_default_content_options() {

			$defaults = array(
				'input_button_text'     => 'I understand',
				'input_link_text'       => 'Read more',
				'textarea_warning_text' => 'This website uses cookies. By using our website you accept our use of cookies.'
			);

			return apply_filters( 'cookies_default_content_options', $defaults );
		}

		//Provides default values for the Styling Options.
		static function cookies_default_styling_options() {

			$defaults = array(
				'message_color_picker'      => '#3E3E3B',
				'button_color_picker'       => '#EBECED',
				'button_hover_color_picker' => '#CBC5C1',
				'button_text_color_picker'  => '#3E3E3B',
				'text_color_picker'         => '#EBECED',
				'link_color_picker'         => '#CBC5C1'
			);

			return apply_filters( 'cookies_default_styling_options', $defaults );
		}

		function cookies_initialize_general_options() {

			// First, we register a section.
			add_settings_section(
				'general_options_section',                          // ID used to identify this section and with which to register options
				esc_html__( 'General Options', 'cookie-message' ),    // Title to be displayed on the administration page
				array(
					$this,
					'cookies_general_options_callback'
				),    // Callback used to render the description of the section
				'cookies_general_options'                           // Page on which to add this section of options
			);

			// Next, we'll introduce the fields for toggling the visibility of content elements.
			add_settings_field(
				'Location Options',                                 // ID used to identify the field throughout the theme
				__( 'Select location of message:', 'cookie-message' ),       // The label to the left of the option interface element
				array(
					$this,
					'cookies_select_position_callback'
				),    // The name of the function responsible for rendering the option interface
				'cookies_general_options',                    // The page on which this option will be displayed
				'general_options_section'                           // The name of the section to which this field belongs
			);

			add_settings_field(
				'cookies_page_link',
				__( 'Enter url to the page about cookies:', 'cookie-message' ),
				array( $this, 'cookies_page_link_callback' ),
				'cookies_general_options',
				'general_options_section'
			);

			// Finally, we register the fields with WordPress
			register_setting(
				'cookies_general_options',
				'cookies_general_options',
				array( $this, 'cookies_validate_options' )
			);
		}

		function cookies_initialize_content_options() {

			add_settings_section(
				'content_options_section',
				__( 'Content Options', 'cookie-message' ),
				array( $this, 'cookies_content_options_callback' ),
				'cookies_content_options'
			);

			add_settings_field(
				'Textarea Warning Text',
				__( 'Enter warning text:', 'cookie-message' ),
				array( $this, 'cookies_textarea_warning_text_callback' ),
				'cookies_content_options',
				'content_options_section'
			);

			add_settings_field(
				'Input Link Text',
				__( 'Enter link text:', 'cookie-message' ),
				array( $this, 'cookies_input_link_text_callback' ),
				'cookies_content_options',
				'content_options_section'
			);

			add_settings_field(
				'Input Button Text',
				__( 'Enter button text:', 'cookie-message' ),
				array( $this, 'cookies_input_button_text_callback' ),
				'cookies_content_options',
				'content_options_section'
			);

			register_setting(
				'cookies_content_options',
				'cookies_content_options',
				array( $this, 'cookies_validate_options' )
			//array($this,'cookies_sanitize_options')
			);
		}

		function cookies_initialize_styling_options() {

			add_settings_section(
				'styling_options_section',
				__( 'Styling Options', 'cookie-message' ),
				array( $this, 'cookies_styling_options_callback' ),
				'cookies_styling_options'
			);

			add_settings_field(
				'Background Color',
				__( 'Message container background', 'cookie-message' ),
				array( $this, 'cookies_message_color_picker_callback' ),
				'cookies_styling_options',
				'styling_options_section' );

			add_settings_field(
				'Message Size',
				__( 'Message container padding top and bottom', 'cookie-message' ),
				array( $this, 'cookies_message_height_slider_callback' ),
				'cookies_styling_options',
				'styling_options_section' );

			add_settings_field(
				'Opacity',
				__( 'Message container opacity', 'cookie-message' ),
				array( $this, 'cookies_opacity_slider_callback' ),
				'cookies_styling_options',
				'styling_options_section' );

			add_settings_field(
				'text_font',
				__( 'Text font', 'cookie-message' ),
				array( $this, 'cookies_text_font_callback' ),
				'cookies_styling_options',
				'styling_options_section' );

			add_settings_field(
				'Text Color',
				__( 'Text Color', 'cookie-message' ),
				array( $this, 'cookies_text_color_picker_callback' ),
				'cookies_styling_options',
				'styling_options_section' );

			add_settings_field(
				'Link Color',
				__( 'Link Color', 'cookie-message' ),
				array( $this, 'cookies_link_color_picker_callback' ),
				'cookies_styling_options',
				'styling_options_section' );

			add_settings_field(
				'add_button_class',
				__( 'Button classes', 'cookie-message' ),
				array( $this, 'cookies_add_button_class_callback' ),
				'cookies_styling_options',
				'styling_options_section' );

			add_settings_field(
				'Button Color',
				__( 'Button Color', 'cookie-message' ),
				array( $this, 'cookies_button_color_picker_callback' ),
				'cookies_styling_options',
				'styling_options_section' );

			add_settings_field(
				'Button Hover Color',
				__( 'Button Hover Color', 'cookie-message' ),
				array( $this, 'cookies_button_hover_color_picker_callback' ),
				'cookies_styling_options',
				'styling_options_section' );

			add_settings_field(
				'Button Text Color',
				__( 'Button Text Color', 'cookie-message' ),
				array( $this, 'cookies_button_text_color_picker_callback' ),
				'cookies_styling_options',
				'styling_options_section' );

			add_settings_field(
				'Button_height',
				__( 'Button Height', 'cookie-message' ),
				array( $this, 'cookies_button_height_slider_callback' ),
				'cookies_styling_options',
				'styling_options_section' );

			add_settings_field(
				'button_width',
				__( 'Button Width', 'cookie-message' ),
				array( $this, 'cookies_button_width_slider_callback' ),
				'cookies_styling_options',
				'styling_options_section' );

			register_setting(
				'cookies_styling_options',
				'cookies_styling_options',
				array( $this, 'cookies_validate_options' )
			);
		}

		/*
		------------------------------------------------------------------------
		Callbacks
		------------------------------------------------------------------------
		*/
		function cookies_general_options_callback() {
			echo '<p>' . esc_html_e( 'Select where the cookie message should be displayed and enter the URL to the page about cookies.', 'cookie-message' ) . '</p>';
		}

		function cookies_content_options_callback() {
			echo '<p>' . esc_html_e( 'Enter the content in the cookie message.', 'cookie-message' ) . '</p>';
		}

		function cookies_styling_options_callback() {
			echo '<p>' . esc_html_e( 'Select the styling for the cookie message.', 'cookie-message' ) . '</p>';
		}

		function cookies_select_position_callback() {
			// Get the options for which this setting is in
			$options = get_option( 'cookies_general_options' );

			$html = '<select id="location_options" name="cookies_general_options[location_options]">';
			$html .= '<option value="top-fixed"' . selected( $options['location_options'], 'top-fixed', false ) . '>' . __( 'Top as overlay', 'cookie-message' ) . '</option>';
			$html .= '<option value="top-static"' . selected( $options['location_options'], 'top-static', false ) . '>' . __( 'Top in content', 'cookie-message' ) . '</option>';
			$html .= '<option value="bottom-fixed"' . selected( $options['location_options'], 'bottom-fixed', false ) . '>' . __( 'Bottom as overlay', 'cookie-message' ) . '</option>';
			$html .= '</select>';

			echo $html;
		}

		function cookies_page_link_callback() {
			$options = get_option( 'cookies_general_options' );
			//echo ($options['cookies_page_link']);

			echo '<input type="text" id="cookies_page_link" name="cookies_general_options[cookies_page_link]" value="' . $options['cookies_page_link'] . '" />';
		}

		function cookies_input_button_text_callback() {
			$options = get_option( 'cookies_content_options' );

			echo '<input type="text" id="input_button_text" name="cookies_content_options[input_button_text]" value="' . $options['input_button_text'] . '" />';
		}

		function cookies_input_link_text_callback() {
			$options = get_option( 'cookies_content_options' );

			echo '<input type="text" id="input_link_text" name="cookies_content_options[input_link_text]" value="' . $options['input_link_text'] . '" />';
		}

		function cookies_textarea_warning_text_callback() {
			$options = get_option( 'cookies_content_options' );

			echo '<textarea id="textarea_warning_text" name="cookies_content_options[textarea_warning_text]" rows="5" cols="50">' . $options['textarea_warning_text'] . '</textarea>';
		}

		function cookies_message_color_picker_callback() {
			$options = get_option( 'cookies_styling_options' );

			$val = ( isset( $options['message_color_picker'] ) ) ? $options['message_color_picker'] : '';
			echo '<input type="text" id="message_color_picker" name="cookies_styling_options[message_color_picker]" value="' . $val . '" class="cpa-color-picker" >';
		}

		function cookies_button_color_picker_callback() {
			$options = get_option( 'cookies_styling_options' );

			$val = ( isset( $options['button_color_picker'] ) ) ? $options['button_color_picker'] : '';
			echo '<input type="text" id="button_color_picker" name="cookies_styling_options[button_color_picker]" value="' . $val . '" class="cpa-color-picker" >';
		}

		function cookies_button_hover_color_picker_callback() {
			$options = get_option( 'cookies_styling_options' );

			$val = ( isset( $options['button_hover_color_picker'] ) ) ? $options['button_hover_color_picker'] : '';
			echo '<input type="text" id="button_hover_color_picker" name="cookies_styling_options[button_hover_color_picker]" value="' . $val . '" class="cpa-color-picker" >';
		}

		function cookies_button_text_color_picker_callback() {
			$options = get_option( 'cookies_styling_options' );

			//$val = ( isset( $options['button_text_color_picker'] ) ) ? $options['button_text_color_picker'] : '';
			$val = $options['button_text_color_picker'];
			echo '<input type="text" id="button_text_color_picker" name="cookies_styling_options[button_text_color_picker]" value="' . $val . '" class="cpa-color-picker" >';
		}

		function cookies_text_color_picker_callback() {
			$options = get_option( 'cookies_styling_options' );

			$val = ( isset( $options['text_color_picker'] ) ) ? $options['text_color_picker'] : '';
			echo '<input type="text" id="text_color_picker" name="cookies_styling_options[text_color_picker]" value="' . $val . '" class="cpa-color-picker" >';
		}

		function cookies_link_color_picker_callback() {
			$options = get_option( 'cookies_styling_options' );

			$val = ( isset( $options['link_color_picker'] ) ) ? $options['link_color_picker'] : '';
			echo '<input type="text" id="link_color_picker" name="cookies_styling_options[link_color_picker]" value="' . $val . '" class="cpa-color-picker" >';
		}

		function cookies_add_button_class_callback() {
			$options = get_option( 'cookies_styling_options' );

			$val = ( isset( $options['add_button_class'] ) ) ? $options['add_button_class'] : '';
			echo '<input type="text" id="add_button_class" name="cookies_styling_options[add_button_class]" value="' . $val . '" />';
			echo '<div><p>Replace the standard styling of the button by specifying your own class. If several classes, separate with space. Leave empty to keep the standard styling.</p></div>';
		}

		function cookies_opacity_slider_callback() {
			$options = get_option( 'cookies_styling_options' );

			$val = ( isset( $options['opacity_slider_amount'] ) ) ? $options['opacity_slider_amount'] : '100';
			echo '<input type="text" id="opacity_slider_amount" name="cookies_styling_options[opacity_slider_amount]" value="' . $val . '" readonly style="border:0; color:#f6931f; font-weight:bold;">';
			echo '<div id="opacity_slider"></div>';

		}

		function cookies_message_height_slider_callback() {
			$options = get_option( 'cookies_styling_options' );

			$val = ( isset( $options['message_height_slider_amount'] ) ) ? $options['message_height_slider_amount'] : '10';
			echo '<input type="text" id="message_height_slider_amount" name="cookies_styling_options[message_height_slider_amount]" value="' . $val . '" readonly style="border:0; color:#f6931f; font-weight:bold;">';
			echo '<div id="message_height_slider"></div>';
		}

		function cookies_button_height_slider_callback() {
			$options = get_option( 'cookies_styling_options' );

			$val = ( isset( $options['button_height_slider_amount'] ) ) ? $options['button_height_slider_amount'] : '5';
			echo '<input type="text" id="button_height_slider_amount" name="cookies_styling_options[button_height_slider_amount]" value="' . $val . '" readonly style="border:0; color:#f6931f; font-weight:bold;">';
			echo '<div id="button_height_slider"></div>';
		}

		function cookies_button_width_slider_callback() {
			$options = get_option( 'cookies_styling_options' );

			$val = ( isset( $options['button_width_slider_amount'] ) ) ? $options['button_width_slider_amount'] : '10';
			echo '<input type="text" id="button_width_slider_amount" name="cookies_styling_options[button_width_slider_amount]" value="' . $val . '" readonly style="border:0; color:#f6931f; font-weight:bold;">';
			echo '<div id="button_width_slider"></div>';
		}

		function cookies_text_font_callback() {
			$options = get_option( 'cookies_styling_options' );

			$val = ( isset( $options['text_font'] ) ) ? $options['text_font'] : '';
			echo '<input type="text" id="text_font" name="cookies_styling_options[text_font]" value="' . $val . '" />';
			echo '<div><p>Replace your standard paragraph font-family. Leave empty for the standard font-family</p></div>';
		}

		// Iterates through every part of the input string and sanitizes it
		function cookies_sanitize_options( $input ) {

			// Define the array for the updated options
			$output = array();
			// Loop through each of the options sanitizing the data
			foreach ( $input as $key => $val ) {

				if ( isset ( $input[ $key ] ) ) {
					$output[ $key ] = esc_url_raw( strip_tags( stripslashes( $input[ $key ] ) ) );
				} // end if

			} // end foreach

			// Return the new collection
			return apply_filters( 'cookies_sanitize_options', $output, $input );
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

}
