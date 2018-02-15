jQuery( function ( $ ) {

  'use stric';

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
        .on( 'click', '#cmm-save-preference,.custom-cookie-message-banner__close', this.savePreferences );
    },

    cookiePreferences: function ( event ) {
      event.stopPropagation();

      customCookieMessage.showCookieNotice();

      setTimeout( function () {
        customCookieMessage.changeSettings();
        $( '#custom-cookie-message-banner' ).remove();
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
      } )
       .done( function () {
         $( '#custom-cookie-message-modal' ).remove();
         $( '#custom-cookie-message-banner' ).remove();
       } );
    },

    showCookieNotice: function () {
      $.ajax( {
        url: customCookieMessageLocalize.rest_url_banner,
        method: 'GET',
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function ( xhr ) {
          xhr.setRequestHeader( 'X-WP-Nonce', customCookieMessageLocalize.wp_rest_nonce );
        },
      } )
       .done( function ( response ) {
         if ( null !== response.template &&
              'bottom-fixed' === customCookieMessageLocalize.options.general.location_options ) {
           $( 'body' ).append( response.template ).fadeIn();
         }
         else if ( null !== response.template ) {
           $( 'body' ).prepend( response.template ).fadeIn();
         }
       } );
    },
  };

  customCookieMessage.init();

} );
