jQuery( function ( $ ) {

  'use stric';

  let cookie = document.cookie.match( '(^|;) ?custom-cookie-message=([^;]*)(;|$)' );

  let customCookieMessage = {
    states: null, init: function () {
      if ( null === cookie ) {
        $( 'body' ).after( this.template );
      }

      $( 'body' )
        .on( 'click', '.ccmAccept', this.acceptTerms );

    },

    acceptTerms: function () {

    },

    template: function () {
      let html = '';
      let options = customCookieMessageLocalize.options;
      let blockMainClass = '';

      // blockMainClass = options.general;

      html += '<div class="custom-cookie-message">';
      html += '';
      html += '</div>';

      return html;
    },
  };

  customCookieMessage.init();

} );
