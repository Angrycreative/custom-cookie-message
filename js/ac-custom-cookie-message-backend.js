/**
 * Author: Johan Sylvan
 * Date: 2016-10-10.
 */

jQuery(document).ready(function($) {

    $(function() {

        // Add Color Picker to all inputs that have 'color-field' class
        $( '.cpa-color-picker' ).wpColorPicker();


        $( "#opacity_slider" ).slider({
            range: "min",
            value: 37,
            min: 1,
            max: 100,
            slide: function( event, ui ) {
                $( "#opacity_slider_amount" ).val( "$" + ui.value );
            }
        });
        $( "#opacity_slider_amount" ).val( "$" + $( "#opacity_slider" ).slider( "value" ) );

    });
});
