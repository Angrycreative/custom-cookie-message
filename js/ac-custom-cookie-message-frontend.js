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
    var scrollPosition = $(this).scrollTop();
    var delta = 50;
    var navbarHeight = $('#custom-cookie-message-container').outerHeight();
    var cookieContainer = $('#custom-cookie-message-container');
    var slideAnimation = 400;

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

    setTimeout(function() {
        $(window).scroll(debounce(function () {

            hasScrolled();

        }, 100));
    }, 10);


    if( cookieContainer.hasClass('top-static') ) {
        slideAnimation = 0;
    }

    if(storage) {
        var fallback = storage.getItem("ac-cookie-fallback");
        if(fallback !== "fallback") {
            $('#custom-cookie-message-container').slideDown(slideAnimation, function () {
                $(this).find('.form-control').focus();

                setBodyMargin();
            });
        }
    }
    else {
        $('#custom-cookie-message-container').slideDown(slideAnimation, function () {
            $(this).find('.form-control').focus();

            setBodyMargin();
        });
    }

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
            storage.setItem("ac-cookie-fallback", "fallback");
            $('#custom-cookie-message-container').hide();
            if( cookieContainer.hasClass('top-static') ) {
                $('body').css("margin-top", 0);
            }
        })
        .fail(function() {
        })
        .always(function() {
        });
    });

    $(window).on('resize', function() {

        setBodyMargin();
    });

    function setBodyMargin() {
        if( cookieContainer.hasClass('top-static') ) {
            $('body').css("margin-top", $('#custom-cookie-message-container').outerHeight());
        }
    }
   
    function hasScrolled() {
        var st = $(this).scrollTop();
        var sb = $(this).scrollTop() + $(window).height();

        // Make sure they scroll more than delta
        if(Math.abs(lastScrollTop - st) <= delta)
            return;
        if(scrollPosition + 50 <= st) {
            scrollPosition = st;
        }
        //console.log(navbarHeight );
        //console.log(st);
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

        //console.log(navbarHeight );
        //console.log(sb);
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