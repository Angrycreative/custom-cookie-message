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
	    $( '#custom-cookie-message-modal' ).remove();
	    $( '#custom-cookie-message-banner' ).slideUp().remove();
	    $( 'body' ).animate({marginBottom: '0px', marginTop: '0px'});

      $.ajax( {
        url: customCookieMessageLocalize.rest_url_preference,
        method: 'POST',
        cache: false,
        data: {
          'functional': $( '#ccm-functional' ).prop( 'checked' ),
          'adsvertising': $( '#ccm-advertising' ).prop( 'checked' ),
        },
        beforeSend: function ( xhr ) {
          xhr.setRequestHeader( 'X-WP-Nonce', customCookieMessageLocalize.wp_rest_nonce );
        },
	      error: function ( error ) {
	        console.error( 'Custom cookie plugin: Could not save preferences' );
	        console.error( error );
	      }
      } )
    },

    showCookieNotice: function () {
      $.ajax( {
        url: customCookieMessageLocalize.rest_url_banner,
        method: 'GET',
        cache: false,
        contentType: false,
        processData: false,
        data: 'lang=' + customCookieMessageLocalize.lang,
        beforeSend: function ( xhr ) {
          xhr.setRequestHeader( 'X-WP-Nonce', customCookieMessageLocalize.wp_rest_nonce );
        },
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
								if ( typeof customCookieMessageLocalize.options.styles != 'undefined' ) {
									if ( typeof customCookieMessageLocalize.options.styles.banner_animation != 'undefined' && 'scroll' === customCookieMessageLocalize.options.styles.banner_animation ) {
                    $( '#custom-cookie-message-banner' ).slideDown();
                  } else if ( typeof customCookieMessageLocalize.options.styles.banner_animation != 'undefined' && 'fade' === customCookieMessageLocalize.options.styles.banner_animation ) {
                    $( '#custom-cookie-message-banner' ).fadeIn();
                  }
								} else {
									$( '#custom-cookie-message-banner' ).show();
								}

								/* Scroll content container */
								if ( typeof customCookieMessageLocalize.options.styles.scroll_body != 'undefined' && 'yes' === customCookieMessageLocalize.options.styles.scroll_body ) {
									if ( typeof customCookieMessageLocalize.options.general.location_options != 'undefined' && 'bottom-fixed' === customCookieMessageLocalize.options.general.location_options ) {
										$( 'body' ).animate({marginBottom: scroll_height});
									}
									else {
										$( 'body' ).animate({marginTop: scroll_height});
									}
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
