/**
 * Author: Johan Sylvan
 * Date: 2016-10-10.
 */

jQuery(document).ready(function($) {

    setTimeout(function() {
        $( '#custom-cookie-message-container' ).slideToggle( 400, function() {
            $( this ).find( '.form-control' ).focus();
        } );
    },10);

    $('#cookies-button-ok').click(function(){
        //var ajaxURL = window.location.protocol + "//" + window.location.host + '/wp-admin/admin-ajax.php';
        $.ajax({
            //url : ajaxURL,
            url : MyAjax.ajaxurl,
            type: 'GET',
            data: {
                action: 'setcookie',
            },
        })
        .done(function( data ) {
            $('#custom-cookie-message-container').hide();
        })
        .fail(function() {
        })
        .always(function() {
        });
    });

    // Hide Header on on scroll down
    var didScroll;
    var lastScrollTop = 0;
    var lastScrollBot = 0;
    var delta = 5;
    var navbarHeight = $('#custom-cookie-message-container').outerHeight();

    $(window).scroll(function(event){
        didScroll = true;
    });

    setInterval(function() {
        if (didScroll) {
            hasScrolled();
            didScroll = false;
        }
    }, 250);

    function hasScrolled() {
        var st = $(this).scrollTop();
        var sb = $(this).scrollTop() + $(window).height();

        // Make sure they scroll more than delta
        if(Math.abs(lastScrollTop - st) <= delta)
            return;

        // If they scrolled down and are past the navbar, add class .nav-up.
        // This is necessary so you never see what is "behind" the navbar.
        if (st > lastScrollTop && st > navbarHeight){
            // Scroll Down
            $('#custom-cookie-message-container').removeClass('container-cookies-visible').addClass('container-cookies-up');
        } else {
            // Scroll Up
            if(st + $(window).height() < $(document).height()) {
                $('#custom-cookie-message-container').removeClass('container-cookies-up').addClass('container-cookies-visible');
            }
        }
        lastScrollTop = st;

        if(Math.abs(lastScrollBot - sb) <= delta)
            return;

        if (sb > lastScrollBot && sb > navbarHeight){
            // Scroll Down
            $('#custom-cookie-message-container').removeClass('container-cookies-down').addClass('container-cookies-visible2');
        } else {
            // Scroll Up
            if(st + $(window).height() < $(document).height()) {
                $('#custom-cookie-message-container').removeClass('container-cookies-visible2').addClass('container-cookies-down');
            }
        }
        lastScrollBot = sb;
    }
});
