var $ = jQuery.noConflict();

$(document).ready(function($) {
    "use strict";

//  Bootstrap Pills

    $('#work-pills a').click(function (e) {
        e.preventDefault();
        $(this).tab('show')
    });
	

    $('.slide').each(function () {
        var imgSrc = $(this).children('.slider-bg').attr('src');
        $(this).css({'background':'url("' + imgSrc + '") 50% 0%', 'background-size':'cover'});
		$(this).children('.slider-bg').remove();
    });
	
	
// Simple parallax
	if (!AnaglyphGlobal.is_mobile){
		var s = skrollr.init({
		    forceHeight: false,
		    render: function(data) {
		        //Debugging - Log the current scroll position.
		        //console.log(data.curTop);
		    }
		});	
	} 

//  Contact Form with validation
	$('#contactform.footer-form .form-actions input#cff-submit.btn').click(function(){
        $("#contactform").validate({
			rules: {
				form_captcha: {
					required:true,
					minlength:6,
					remote: {
						url: AnaglyphGlobal.ajaxurl,
						type: "post",
						data: {action: 'verify_captcha' }
					}
				}
			},
			
            submitHandler: function() {
				var data = { action: 'send_contact_mail',  formData : $("#contactform").serialize() };
				$.post(AnaglyphGlobal.ajaxurl, data,  function(response) {
                     $('#form-status').html(response);
                });
                return false;
            }
        });
    });
	
	if ($('#captcha_img').length > 0) {
		$('#captcha_img').on('click', function() {
			var data = { action: 'reload_captcha_image'};
			$.post(AnaglyphGlobal.ajaxurl, data,  function(src_img) {
				$('#captcha_img').attr('src', src_img);	
            });
				
			return false;
		});
	}


//  Slider high on small screens
    if ($('#slider').length > 0) {
		if (document.documentElement.clientWidth < 768) {
			$('#slider').css('height', $(window).height());
		} else {
			$('#slider').css('height', 900);
		}			
	}	
    
	
//  FlexSlider
    $('.flexslider').flexslider({
        slideshowSpeed: AnaglyphGlobal.sliderParam.slideshowSpeed,
        animationSpeed: AnaglyphGlobal.sliderParam.animationSpeed,
        directionNav: AnaglyphGlobal.sliderParam.directionNav,
        controlNav: AnaglyphGlobal.sliderParam.controlNav,
		after: function(){
            $('.slide-wrapper').removeClass('animated ' + AnaglyphGlobal.sliderParam.animationeffectout + ' is-visible');
            $('.slide-wrapper').addClass('animated '    + AnaglyphGlobal.sliderParam.animationeffectin  + ' is-visible');
        },
        before: function(){
            $('.slide-wrapper').removeClass('animated ' + AnaglyphGlobal.sliderParam.animationeffectin  + ' is-visible');
            $('.slide-wrapper').addClass('animated '    + AnaglyphGlobal.sliderParam.animationeffectout + ' is-visible');
        }

    });

//  Center slide title
	
	$('.flexslider').each(function () {
        var slideHeight = $(this).height();
        var contentHeight = $(this).children('.slide-content').height();
        var padTop = (slideHeight / 2) - (contentHeight / 2);
        $('.slide-content').css('padding-top', padTop);
    });
	
	
     $(window).scroll(function () {
        if ((AnaglyphGlobal.headerFixedVartiation == '1') && AnaglyphGlobal.slider_on) {
			if ($(window).scrollTop() > 1) {
				$('.navigation').addClass('header-solid');
			} else {
				$('.navigation').removeClass('header-solid');
			}
		}
		
        var scrollAmount = $(window).scrollTop();
			scrollAmount = Math.round(scrollAmount);
			
			if (AnaglyphGlobal.sliderParam.sliderParallaxOn) {
				if(AnaglyphGlobal.headerFixedVartiation == '3') {
					if (scrollAmount > 0 && scrollAmount > $('.navigation-wrapper').outerHeight()) {
						$('.home #slider .slides').css('margin-top', parseInt((scrollAmount/2) - ($('.navigation-wrapper').outerHeight()/2)) + 'px');
					} else {
						$('.home #slider .slides').css('margin-top', 0);
					}
				} else {
					$('.home #slider .slides').css('margin-top', scrollAmount/2 + 'px');
				}
			}

		if (AnaglyphGlobal.headerFixedVartiation != 3) {
			if ($('#wpadminbar').length > 0) {
				if ($('#wpadminbar').css('position') == 'absolute') {
					if (scrollAmount > 1) {
						$('.navigation-wrapper').css({'top' : 0});
					} else {
						$('.navigation-wrapper').css({'top' : 'initial'});
					}
				} else {
					$('.navigation-wrapper').css({'top' : 'initial'});
				}
			}
		}		
   });

//  Scroll Reveal

    if (document.documentElement.clientWidth > 768 && !(AnaglyphGlobal.is_mobile && AnaglyphGlobal.disable_animation_mobile)) {
		window.scrollReveal = new scrollReveal();
    }
	
	
	if ($(window).width() < 768) {
		$('.nav a').on('click', function(){
			$(".navbar-toggle").click();
		});
	}	

//  Placeholder

    $('input, textarea').placeholder();

//  Vanilla Box
    if ($('.video').length > 0) {
        $('a.video').vanillabox({
            animation: 'default',
            preferredWidth: 1100,
            type: 'iframe'
        });
    }
	
	/*Cneter menu. if logo have big height*/
	if ($('.navbar-header a.navbar-brand img').length > 0) {
		var vLogoH = parseInt($('.navbar-header a.navbar-brand').outerHeight());
		var vPadd  = parseInt($('.navbar-brand.nav.logo').css('padding-top')) + parseInt($('.navbar-brand.nav.logo').css('padding-bottom'));
		
			if (vLogoH > 18) {
				vLogoH = (vLogoH / 2) - vPadd;
				if ($(window).width() > 768) {
					$('.navigation header#top.navbar .container nav.collapse').css({'top':vLogoH});
				} else {
					$('.navigation header#top.navbar .container nav.collapse').css({'top':0});
				}				
				
				$('.navbar-toggle').css({'top':vLogoH+5});
			}	
	}
	
	// Focus styles for menus.
		$( '.primary-navigation' ).find( 'a' ).on( 'focus blur', function() {
			$( this ).parents('li').toggleClass( 'focus' );
		} );
	
	
	if ($('.primary-sidebar.widget-area .widget select').length > 0) {
		$('.primary-sidebar.widget-area .widget select').each(function() {
			$(this).selectize();
		});
		
	}
	
	
	/*WooCommerce*/
	if ($('.woocommerce-ordering .orderby').length > 0) {
		$('.woocommerce-ordering .orderby').selectize();
	}
	
	if ($('.shipping-calculator-form #calc_shipping_country').length > 0) {
		$('.shipping-calculator-form #calc_shipping_country').selectize();
	}
	
	$( 'ul.products li.anaglyph-woo-has-gallery a:first-child' ).hover( function() {
		$( this ).children( '.wp-post-image' ).removeClass( 'is-animated-true' ).addClass( 'is-animated-false' );
		$( this ).children( '.anaglyph-second-image' ).removeClass( 'is-animated-false' ).addClass( 'is-animated-true' );
	}, function() {
		$( this ).children( '.wp-post-image' ).removeClass( 'is-animated-false' ).addClass( 'is-animated-true' );
		$( this ).children( '.anaglyph-second-image' ).removeClass( 'is-animated-true' ).addClass( 'is-animated-false' );
	});
	
	
		
//  Smooth Navigation Scrolling
	var vNav = $('.navigation-wrapper').outerHeight();
	$('body').data('offset', vNav + vNav/6);
	 
    $('.navigation .nav a[href^="#"], a[href^="#"].roll').on('click',function (e) {
		var vOffset = $('.navigation-wrapper').outerHeight();
		var target = this.hash, $target = $(target);
		
		if (target) {			
			$(window).stop(true).scrollTo( $target, 2000, { easing: "swing" , offset:  -vOffset + vOffset/6, 'axis':'y' } );	
			e.preventDefault();
		}			
		return false;
	 });
	
	
				
	if (AnaglyphGlobal.headerFixedVartiation == '2' ||
		AnaglyphGlobal.headerFixedVartiation == '3' ||
		((AnaglyphGlobal.headerFixedVartiation == '1') && !AnaglyphGlobal.slider_on)
	) {
		$('.navigation').addClass('header-solid');
		
		if ($('body').hasClass('sub-page')) {
			if (AnaglyphGlobal.headerFixedVartiation != '3') {
				$('#main.site-main').css({'padding-top': $('.navigation-wrapper').outerHeight()});
			}
		}
	}
	
});

$(window).resize(function () {
	if ($('.navbar-header a.navbar-brand img').length > 0) {
		var vLogoH = parseInt($('.navbar-header a.navbar-brand').outerHeight());
		var vPadd  = parseInt($('.navbar-brand.nav.logo').css('padding-top')) + parseInt($('.navbar-brand.nav.logo').css('padding-bottom'));
		
			if (vLogoH > 18) {
				vLogoH = (vLogoH / 2) - vPadd;
				if ($(window).width() > 768) {
					$('.navigation header#top.navbar .container nav.collapse').css({'top':vLogoH});
				} else {
					$('.navigation header#top.navbar .container nav.collapse').css({'top':0});
				}				
				$('.navbar-toggle').css({'top':vLogoH+5});
			}	
	}
	
	if ($('#slider').length > 0) {
		if (document.documentElement.clientWidth < 768) {
			$('#slider').css('height', $(window).height());
		} else {
			$('#slider').css('height', 900);
		}			
	
		
		$('.flexslider').each(function () {
			var slideHeight = $(this).height();
			var contentHeight = $(this).children('.slide-content').height();
			var padTop = (slideHeight / 2) - (contentHeight / 2);
			$('.slide-content').css('padding-top', padTop);
		});
	
	}	
	
	
});

$(window).load(function () {
    
	if( window.location.hash ) {				
		  setTimeout ( function () {		
			  var vOffset = $('.navigation-wrapper').height();
				  $.scrollTo( $(window.location.hash) , 10 , { easing: "swing" , offset: -vOffset, "axis":"y" } );																		
		  }, 200 );								
	}
	
	
	if ($('.navbar-header a.navbar-brand img').length > 0) {
		var vLogoH = parseInt($('.navbar-header a.navbar-brand').outerHeight());
		var vPadd  = parseInt($('.navbar-brand.nav.logo').css('padding-top')) + parseInt($('.navbar-brand.nav.logo').css('padding-bottom'));
		
			if (vLogoH > 18) {
				vLogoH = (vLogoH / 2) - vPadd;
				if ($(window).width() > 768) {
					$('.navigation header#top.navbar .container nav.collapse').css({'top':vLogoH});
				} else {
					$('.navigation header#top.navbar .container nav.collapse').css({'top':0});
				}				
				
				$('.navbar-toggle').css({'top':vLogoH+5});
			}	
	}
   
});