/**
 * Author: Johan Sylvan
 * Date: 2016-10-10.
 */

//Debounce from David Walsh https://davidwalsh.name/javascript-debounce-function
function debounce(func, wait, immediate) {
    var timeout;
    return function() {
        var context = this, args = arguments;
        var later = function() {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        var callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
};


jQuery(document).ready(function($) {
    // Hide Header on on scroll 
    var didScroll;
    var lastScrollTop = 0;
    var lastScrollBot = 0;
    var delta = 5;
    var navbarHeight = $('#custom-cookie-message-container').outerHeight();

    var storage = (function() {
        var uid = new Date;
        var storage;
        var result;
        try {
            (storage = window.localStorage).setItem(uid, uid);
            result = storage.getItem(uid) == uid;
            storage.removeItem(uid);
            return result && storage;
        } catch (exception) {}
    }());

    $(window).on('scroll', debounce( function () {
        hasScrolled();
    }, 250));

    setTimeout(function() {
        if(storage) {
            var fallback = storage.getItem("ac-cookie-fallback");
            if(fallback !== "fallback") {

                $('#custom-cookie-message-container').slideToggle(400, function () {
                    $(this).find('.form-control').focus();
                });
            }

        } else {
            $('#custom-cookie-message-container').slideToggle(400, function () {
                $(this).find('.form-control').focus();
            });
        }
    },10);

    $('#cookies-button-ok').click(function(e){
        e.preventDefault();
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
            storage.setItem("ac-cookie-fallback", "fallback")
            $('#custom-cookie-message-container').hide();
        })
        .fail(function() {
        })
        .always(function() {
        });
    });
   
    function hasScrolled() {
        var st = $(this).scrollTop();
        var sb = $(this).scrollTop() + $(window).height();

        // Make sure they scroll more than delta
        if(Math.abs(lastScrollTop - st) <= delta)
            return;

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