import '../sass/style.scss';

jQuery( function ( $ ) {

	let cookie = document.cookie.match( '(^|;) ?custom_cookie_message=([^;]*)(;|$)' );

	let customCookieMessage = {
		states: null, init: function () {

			if ( null === cookie ) {
				this.showCookieNotice();
			}

			$( 'body' )
				.on( 'click', '#custom-cookie-message-preference', this.changeSettings )
				.on( 'click', '.custom-cookie-message-modal__close', this.killModal )
				.on( 'click', '#custom-cookie-message-modal', this.actionModal )
				.on( 'click', '.custom-cookie-message-modal__item', this.actionTab )
				.on( 'click', '#ccm_cookie_preferences', this.cookiePreferences )
				.on( 'click', '#ccm-save-preference,.custom-cookie-message-banner__close,.custom-cookie-message-banner__accept', this.savePreferences );
		},

		cookiePreferences: function ( event ) {
			event.stopPropagation();

			customCookieMessage.showCookieNotice();

			setTimeout( function () {
				customCookieMessage.changeSettings();
				$( '#custom-cookie-message-banner' ).slideUp().remove();
				$( 'body' ).animate({marginBottom: '0px', marginTop: '0px'});
			}, 350 );

		},

		changeSettings: function () {
			let modalBlock = $( '#custom-cookie-message-modal' );
			if ( modalBlock.hasClass( 'custom-cookie-message-modal--off' ) ) {
				modalBlock.removeClass( 'custom-cookie-message-modal--off' ).addClass( 'custom-cookie-message-modal--on' );
				$( '#custom-cookie-message-banner' ).addClass( 'hide' );
			}

		},

		killModal: function () {
			let modal = $( '#custom-cookie-message-modal' );
			if ( modal.hasClass( 'custom-cookie-message-modal--on' ) ) {
				modal.removeClass( 'custom-cookie-message-modal--on' ).addClass( 'custom-cookie-message-modal--off' );
				$( '#custom-cookie-message-banner' ).removeClass( 'hide' );
			}
		},

		actionModal: function ( event ) {
			if ( event.target !== event.currentTarget ) {
				return;
			}
			customCookieMessage.killModal();
		},

		actionTab: function () {
			let self = $( this );
			let classList = self.attr( 'class' ).split( /\s+/ );
			let re = /--(.*)_message$/g;

			for ( let i = 0; i < classList.length; i++ ) {
				if ( classList[ i ].match( re ) ) {
					let contentBox = re.exec( classList[ i ] );
					let baseTabClass = '.custom-cookie-message-modal__item--' + contentBox[ 1 ] + '_message';
					let baseMessageClass = '.custom-cookie-message-modal__' + contentBox[ 1 ] + '_message';

					if ( !$( baseTabClass ).hasClass( 'custom-cookie-message-modal__item--active' ) ) {
						$( '.custom-cookie-message-modal__item.custom-cookie-message-modal__item--active' ).removeClass( 'custom-cookie-message-modal__item--active' );
						$( baseTabClass ).addClass( 'custom-cookie-message-modal__item--active' );

						$( '.custom-cookie-message-modal__content div' ).not( '.hide' ).addClass( 'hide' );
						$( baseMessageClass ).removeClass( 'hide' );
					}
				}
			}
		},

		savePreferences: function () {
			let ccmFunctionalStatus = $( '#ccm-functional' ).prop( 'checked' );
			let ccmAdvertisingStatus = $( '#ccm-advertising' ).prop( 'checked' );

			let data = {
				'functional': ccmFunctionalStatus,
				'advertising': ccmAdvertisingStatus,
			};
			data = JSON.stringify( data );

			let cookieLifeTime = parseInt( customCookieMessageLocalize.cookie_life_time );

			let numberOfDays = cookieLifeTime / 60 / 60 / 24;

			let d = new Date();
			d.setTime( d.getTime() + (
				numberOfDays * 24 * 60 * 60 * 1000
			) );
			let expires = "expires=" + d.toUTCString();
			document.cookie = 'custom_cookie_message' + "=" + data + ";" + expires + ";path=/";

			$( '#custom-cookie-message-modal' ).remove();
			$( '#custom-cookie-message-banner' ).slideUp().remove();
		},

		showCookieNotice: function () {
			$.ajax( {
				url: customCookieMessageLocalize.rest_url_banner,
				method: 'GET',
				cache: true,
				contentType: false,
				processData: false,
				data: 'lang=' + customCookieMessageLocalize.lang,
			} )
			 .done( function ( response ) {
				 if ( null !== response.template && '' !== customCookieMessageLocalize.options ) {
					 if ( 'bottom-fixed' === customCookieMessageLocalize.options.general.location_options ) {
						 $( 'body' ).append( response.template );
					 }
					 else {
						 $( 'body' ).prepend( response.template );
					 }
					 /* Get height of the banner before showing it */
					 var get_height = $( '#custom-cookie-message-banner' ).clone().attr("id", false).css({display:"block", position:"absolute"});
					 $( 'body' ).append(get_height);
					 var scroll_height = get_height.outerHeight();

					 get_height.remove();

					 /* banner animation */
					 if ( 'scroll' === customCookieMessageLocalize.options.styles.banner_animation ) {
						 $( '#custom-cookie-message-banner' ).slideDown();
					 } else
					 if ( 'fade' === customCookieMessageLocalize.options.styles.banner_animation ) {
						 $( '#custom-cookie-message-banner' ).fadeIn();
					 }
					 else {
						 $( '#custom-cookie-message-banner' ).show();
					 }
				 }
				 else {
					 console.warn( 'Custom Cookie Message options are not set' );
				 }
			 } );
		},
	};


	customCookieMessage.init();

} );
