jQuery( function ( $ ) {

  'use stric';

  let cookie = document.cookie.match( '(^|;) ?custom-cookie-message=([^;]*)(;|$)' );

  let customCookieMessage = {
    states: null, init: function () {

      if ( null === cookie ) {
        this.showCookieNotice();
      }

      $( 'body' )
        .on( 'click', '#custom-cookie-message-preference', this.changeSettings );
    },

    changeSettings: function () {
      let modalBlock = $( '#custom-cookie-message-modal' );

      if ( modalBlock.hasClass( 'custom-cookie-message-modal--off' ) ) {
        modalBlock.removeClass( 'custom-cookie-message-modal--off' ).addClass( 'custom-cookie-message-modal--on' );
      }

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
