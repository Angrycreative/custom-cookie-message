<?php
/**
 * Author: johansylvan
 * Date: 2016-10-05
 * Time: 14:36
 */

$general_options = get_option('cookies_general_options');
$content_options = get_option('cookies_content_options');
$styling_options = get_option('cookies_styling_options');
?>
<?php //echo $styling_options['opacity_amount']/100;?>
<?php if($general_options['location_options'] == 'top-fixed') { ?>
    <style>
        .container-cookies {
            position: fixed;
            top: 0;
            transition: top 0.2s ease-in-out;
        }
        .container-cookies-up {
            top: -100px !important;
        }
    </style>
<?php } ?>
<?php if($general_options['location_options'] == 'top-static') { ?>
    <style>
        .container-cookies {
            position: static;
        }
    </style>
<?php } ?>
<?php if($general_options['location_options'] == 'bottom-fixed') { ?>
    <style>
        .container-cookies {
            position: fixed;
            bottom: 0;
            transition: bottom 0.2s ease-in-out;
        }
        .container-cookies-down {
            bottom: -100px !important;
        }
    </style>
<?php } ?>
<?php if(isset($_COOKIE['cookie-warning-message'])) { ?>
    <style>
        .container-cookies {
            display: none !important;
        }
    </style>
<?php } ?>

<style>
    .container-cookies {
        background-color: <?php echo $styling_options['message_color_picker'];?>;
        color: <?php echo $styling_options['text_color_picker'];?>;
        //opacity: <?php echo $styling_options['opacity_amount']/100;?>;
    }

    .warning-text a:link{
        color: <?php echo $styling_options['link_color_picker'];?> !important;
    }
    .warning-text a:visited{
        color: <?php echo $styling_options['link_color_picker'];?> !important;
    }

    .cookies-button-ok {
        background-color: <?php echo $styling_options['button_color_picker'];?>;
        color: <?php echo $styling_options['button_text_color_picker'];?>;
    }

    .cookies-button-ok a:visited {
        color: <?php echo $styling_options['button_text_color_picker'];?>;
    }

    .cookies-button-ok:hover {
        color: <?php echo $styling_options['button_text_color_picker'];?>;
        background-color: <?php echo $styling_options['button_hover_color_picker'];?>;
    }
</style>

<div id="custom-cookie-message-container" class="container-cookies container-cookies-visible">
    <div class="cookie-container">
    <div class="warning-text"><p><?php echo $content_options['textarea_warning_text'] ; ?> <a href=" <?php echo $general_options['cookies_page_link']; ?>" ><?php _e( $content_options['input_link_text'], 'cookie-message'); ?></a></p>
        <a id="cookies-button-ok" class="cookies-button-ok">
            <?php _e( $content_options['input_button_text'], 'cookie-message' ); ?>
        </a>
    </div>
    </div>
</div>
