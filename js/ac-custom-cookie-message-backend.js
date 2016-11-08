/**
 * Author: Johan Sylvan
 * Date: 2016-10-10.
 */

jQuery(document).ready(function($) {

    $(function() {

        // Add Color Picker to all inputs that have 'color-field' class
        $( '.cpa-color-picker' ).wpColorPicker();

        /*$( "#opacity_slider" ).slider({
            range: "min",
            value: parseInt($("#opacity_slider_amount").val()),
            min: 50,
            max: 100,
            slide: function( event, ui ) {
                $( "#opacity_slider_amount" ).val( ui.value + "%");
            }
        });

        $( "#message_size_slider" ).slider({
            range: "min",
            //value:  parseInt(message_slider_value),
            value: parseInt($("#message_size_slider_amount").val()),
            min: 50,
            max: 100,
            slide: function( event, ui ) {
                $( "#message_size_slider_amount" ).val( ui.value );
            }
        });

        $( "#button_size_slider" ).slider({
            range: "min",
            value: parseInt($("#button_size_slider_amount").val()),
            //value: 78,
            min: 50,
            max: 100,
            slide: function( event, ui ) {
                $( "#button_size_slider_amount" ).val( ui.value );
            }
        });*/
    });
});
