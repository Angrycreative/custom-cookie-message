<?php
/**
 * Cookie message template.
 *
 * @package CustomCookieMessage\Templates
 */

$options = get_option( 'custom_cookie_message' );
?>
<?php
if ( $options['general']['location_options'] === 'top-fixed' ) {
	?>
	<style>
		.container-cookies {
			position: fixed;
			top: 0;
			left: 0;
			right: 0;
			transition: top 0.2s ease-in-out;
		}

		.container-cookies-up {
			top: -100px !important;
		}
	</style>
<?php } ?>
<?php if ( $options['general']['location_options'] === 'top-static' ) { ?>
	<style>
		.container-cookies {
			position: absolute;
			top: 0;
			left: 0;
			right: 0;
		}
	</style>
<?php } ?>
<?php if ( $options['general']['location_options'] === 'bottom-fixed' ) { ?>
	<style>
		.container-cookies {
			position: fixed;
			bottom: 0;
			left: 0;
			right: 0;
			transition: bottom 0.2s ease-in-out;
		}

		.container-cookies-down {
			bottom: -100px !important;
		}
	</style>
<?php } ?>
<?php if ( isset( $_COOKIE['cookie-warning-message'] ) ) { ?>
	<style>
		.container-cookies {
			display: none !important;
		}
	</style>
<?php } ?>

<style>

	.cookie-block {
	<?php if (!empty($options['styling']['message_color_picker'])) { ?> background-color: <?php echo $options['styling']['message_color_picker'];?>;
	<?php } ?><?php if(!empty($options['styling']['opacity_slider_amount']) && absint($options['styling']['opacity_slider_amount']) > 0) { ?> opacity: <?php echo absint($options['styling']['opacity_slider_amount'])/100;?>;
	<?php } ?>
	}

	<?php if(!empty($options['styling']['message_height_slider_amount'])) { ?>
	#custom-cookie-message-container.container-cookies {
		padding: <?php echo $options['styling']['message_height_slider_amount'];?>px 0 <?php echo $options['styling']['message_height_slider_amount'];?>px 0;
	}

	<?php } ?>
	<?php if(!empty( $options['styling']['text_color_picker'] )){ ?>
	#custom-cookie-message-container.container-cookies p {
		color: <?php echo $options['styling']['text_color_picker'];?>;
	}

	<?php } ?>
	<?php if(!empty( $options['styling']['link_color_picker'] )){ ?>
	#custom-cookie-message-container.warning-text a:link {
		color: <?php echo $options['styling']['link_color_picker'];?> !important;
	}

	<?php } ?>
	<?php if(!empty( $options['styling']['link_color_picker'] )){ ?>
	#custom-cookie-message-container.warning-text a:visited {
		color: <?php echo $options['styling']['link_color_picker'];?>;
	}

	<?php } ?>
	#cookies-button-ok.cookies-button-ok {
	<?php if(!empty( $options['styling']['button_color_picker'] )){ ?> background-color: <?php echo $options['styling']['button_color_picker'];?>;
	<?php } ?><?php if(!empty( $options['styling']['button_text_color_picker'] )){ ?> color: <?php echo $options['styling']['button_text_color_picker'];?>;
	<?php } ?><?php if(!empty( $options['styling']['button_height_slider_amount'] )){ ?> padding-top: <?php echo $options['styling']['button_height_slider_amount'];?>px;
		padding-bottom: <?php echo $options['styling']['button_height_slider_amount'];?>px;
	<?php } ?><?php if(!empty( $options['styling']['button_width_slider_amount'] )){ ?> padding-left: <?php echo $options['styling']['button_width_slider_amount'];?>px;
		padding-right: <?php echo $options['styling']['button_width_slider_amount'];?>px;
	<?php } ?>
	}

	<?php if(!empty( $options['styling']['button_text_color_picker'] )){ ?>
	#cookies-button-ok.cookies-button-ok a:visited {
		color: <?php echo $options['styling']['button_text_color_picker'];?>;
	}

	<?php } ?>
	#cookies-button-ok.cookies-button-ok:hover {
	<?php if(!empty( $options['styling']['button_text_color_picker'] )){ ?> color: <?php echo $options['styling']['button_text_color_picker'];?>;
	<?php } ?><?php if(!empty( $options['styling']['button_hover_color_picker'] )){ ?> background-color: <?php echo $options['styling']['button_hover_color_picker'];?>;
	<?php } ?>
	}
</style>
<div id="custom-cookie-message-container" class="container-cookies <?php echo $options['general']['location_options']; ?>">
	<div class="cookie-block"></div>
	<div class="cookie-content">
		<div class="warning-text">
			<p <?php echo( empty( $options['styling']['text_font'] ) ? '' : 'style=”font-family: ' . $options['styling']['text_font'] . '“' ); ?> ><?php echo $options['conten']['textarea_warning_text']; ?>
				<a style="color: <?php echo $styling_options['link_color_picker']; ?>" href=" <?php echo $options['general']['cookies_page_link']; ?>"><?php _e( $options['conten']['input_link_text'], 'cookie-message' ); ?></a>
			</p>
			<a id="cookies-button-ok" class="cookies-button-ok <?php echo( ! empty( $options['styling']['add_button_class'] ) ? $options['styling']['add_button_class'] : 'default-cookie-button-style' ); ?>">
				<?php _e( $options['conten']['input_button_text'], 'cookie-message' ); ?>
			</a>
		</div>
	</div>
</div>
