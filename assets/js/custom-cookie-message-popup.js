jQuery( function ( $ ) {

  'use stric';

  let cookie = document.cookie.match( '(^|;) ?custom-cookie-message=([^;]*)(;|$)' );

  let customCookieMessage = {
    states: null, init: function () {
      if ( null === cookie ) {
        this.showCookieNotice();

      }

      $( 'body' )
        .on( 'click', '#custom-cookie-message .ccmAccept', this.acceptTerms );

    },

    acceptTerms: function () {

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
