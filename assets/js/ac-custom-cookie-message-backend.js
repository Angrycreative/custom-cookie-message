/**
 * Author: Johan Sylvan
 * Date: 2016-10-10.
 */

jQuery( function ( $ ) {

	'use stric';

	var cookies = [];

	/**
	 * Retrieve a list of cookies.
	 *
	 * @returns {Array|*}
	 */
	function getCookies() {
		var pairs = document.cookie.split( ";" );
		var cookies;

		cookies = pairs.map( function ( cookie ) {
			return cookie.split( '=' )[0];
		} );

		return cookies;
	}

	$.get( '/' )
	 .done( function ( data, status, xhr ) {
		 cookies = getCookies();
		 var html;

		 cookies.map( function ( cookie ) {
			 html = '<div class="cookie">';
			 html = html + 'Cookie machine name: ' + cookie;
			 html = html + '<label for="' + cookie + '-label">Label: '
			 html = html + '<input type="text" name="cookie_list[' + cookie + '][label]" id="' + cookie + '-label">';
			 html = html + '</label>';
			 html = html + '</div>';


			 $( '.cookie_list_wrapper' ).append( html ).fadeIn();
		 } );

		 console.log( cookies )
	 } );


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
