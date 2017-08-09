/**
 * Author: Johan Sylvan
 * Date: 2016-10-10.
 */

jQuery( function ( $ ) {

	function getCookies () {
		var pairs = document.cookie.split(";");


	}

	// Add Color Picker to all inputs that have 'color-field' class
	$( '.cpa-color-picker' ).wpColorPicker();

	$( "#opacity_slider" ).slider( {
		range: "min",
		value: parseInt( $( "#opacity_slider_amount" ).val() ),
		min: 50,
		max: 100,
		slide: function ( event, ui ) {
			$( "#opacity_slider_amount" ).val( ui.value + "%" );
		}
	} );

	$( "#message_height_slider" ).slider( {
		range: "min",
		value: parseInt( $( "#message_height_slider_amount" ).val() ),
		min: 5,
		max: 40,
		slide: function ( event, ui ) {
			$( "#message_height_slider_amount" ).val( ui.value );
		}
	} );

	$( "#button_height_slider" ).slider( {
		range: "min",
		value: parseInt( $( "#button_height_slider_amount" ).val() ),
		min: 5,
		max: 40,
		slide: function ( event, ui ) {
			$( "#button_height_slider_amount" ).val( ui.value );
		}
	} );

	$( "#button_width_slider" ).slider( {
		range: "min",
		value: parseInt( $( "#button_width_slider_amount" ).val() ),
		min: 5,
		max: 40,
		slide: function ( event, ui ) {
			$( "#button_width_slider_amount" ).val( ui.value );
		}
	} );
} );
