(function ($) {
    "use strict";

    /*========== LOADING PAGE ==========*/
    $(window).on('load', function () {
        $("#loading").fadeOut(500);
    });

    
    /*Document is Raedy */
    $(document).ready(function () {

        /*========== REVOLUTION SLIDER ==========*/

        if ($("#classic_slider").length > 0) {
            var tpj = jQuery;
            tpj.noConflict();
            var revapi6;
            tpj(document).ready(function () {
                if (tpj("#classic_slider").revolution == undefined) {
                    revslider_showDoubleJqueryError("#classic_slider");
                } else {
                    revapi6 = tpj("#classic_slider").show().revolution({
                        sliderType: "standard",
                        jsFileLocation: "js/",
                        sliderLayout: "auto",
                        dottedOverlay: "none",
                        delay: 4000,
                        navigation: {
                            keyboardNavigation: "on",
                            keyboard_direction: "horizontal",
                            mouseScrollNavigation: "off",
                            mouseScrollReverse: "default",
                            onHoverStop: "on",
                            touch: {
                                touchenabled: "on",
                                swipe_threshold: 75,
                                swipe_min_touches: 50,
                                swipe_direction: "horizontal",
                                drag_block_vertical: false
                            }
//                            arrows: {
//                                style: "hermes",
//                                enable: true,
//                                hide_onmobile: true,
//                                hide_under: 600,
//                                hide_onleave: true,
//                                tmp: '<div class="tp-arr-allwrapper"><div class="tp-arr-imgholder"></div>',
//                                left: {
//                                    h_align: "left",
//                                    v_align: "center",
//                                    h_offset: 0,
//                                    v_offset: 0
//                                },
//                                right: {
//                                    h_align: "right",
//                                    v_align: "center",
//                                    h_offset: 0,
//                                    v_offset: 0
//                                }
//                            }

                        },
                        responsiveLevels: [1200, 992, 768, 480],
                        visibilityLevels: [1200, 992, 768, 480],
                        gridwidth: [1200, 992, 768, 480],
                        gridheight: [750, 750, 700, 700],
                        lazyType: "none",
                        parallax: {
                            type: "scroll",
                            origo: "slidercenter",
                            speed: 2000,
                            levels: [5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55, 60, 65, 70, 75, 55]
                        },
                        shadow: 0,
                        spinner: "off",
                        stopLoop: "on",
                        stopAfterLoops: -1,
                        stopAtSlide: -1,
                        shuffle: "off",
                        autoHeight: "off",
                        hideThumbsOnMobile: "off",
                        hideSliderAtLimit: 0,
                        hideCaptionAtLimit: 0,
                        hideAllCaptionAtLilmit: 0,
                        debugMode: false,
                        fallbacks: {
                            simplifyAll: "off",
                            nextSlideOnWindowFocus: "off",
                            disableFocusListener: false
                        }
                    });
                }
            });
        };
        
        var amountScrolled = 500;
        var back_to_top = $('#back_to_top');
        $(window).on('scroll', function () {
            if ($(window).scrollTop() > amountScrolled) {
                back_to_top.addClass('active');
            } else {
                back_to_top.removeClass('active');
            }
        });
        back_to_top.on('click', function () {
            $('html, body').animate({
                scrollTop: 0
            }, 500);
            return false;
        });

    });
})(jQuery);