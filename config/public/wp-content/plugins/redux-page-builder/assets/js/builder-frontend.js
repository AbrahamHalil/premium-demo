(function($){
    "use strict";

    $.builder = $.builder || {};

    $(document).ready(function(){
		//active fancybox 
		$.builder.fancybox();

		//active behavior and animation for gallery
		$.builder.gallery();

		//sorting functionality portfolio
		$.builder.mixitup();

		//image overlay
		$.builder.overlay();

		//animate on scroll
		$.builder.animate();
		$(window).bind("scroll", function(event) {
			$.builder.animate();
	    });

    });


    $.builder.overlay = function (){
		$(" a.fancybox img").after('<div class="overlay"><span class="overlay-inner"></span></div>');
	}


	$.builder.fancybox = function (){
		$("a.fancybox").fancybox({
			'transitionIn'	:	'elastic',
			'transitionOut'	:	'elastic',
			'padding'		: 0,
			'titleShow'     : true,
			'titlePosition'  : 'over',
		});
	}

	$.builder.gallery = function (){
		return $('.gallery').each(function(){
			var gallery = $(this),a =  gallery.find('.gallery-thumb .thumbnail') , images = gallery.find('img'), big_prev = gallery.find('.gallery-big');

			a.on('click', function(){

				var imgurl = $(this).attr("href");

				if( $(this).is('._blank') && imgurl != '' ){
					window.open(imgurl, '_blank');
				}else if( $(this).is('._self') && imgurl != '' )
					window.open(imgurl, '_self');

				return false;
			});


		});
	}

	$.builder.animate = function (){
		$('.animate_when_visible:in-viewport').not('.animated').each(function(i){
					
           	var element = $(this);
            // TODO wait for images
            //if(!$$.get(0).complete) return;
            element.addClass('animated');
            setTimeout(function(){
                //var a = element.data('animation');
                element.addClass('start_animation').trigger('start_animation');
                element.removeClass('animate_when_visible');
            }, (i * 250));
			
        });
	}

	$.builder.mixitup = function(){
    	$('.grid-sort-container').mixitup({
    		easing:  'windup',
    		effects: ['fade','scale'],
    		targetDisplayGrid : 'block',
    	});
	}

	/*
	 * Viewport - jQuery selectors for finding elements in viewport
	 *
	 * Copyright (c) 2008-2009 Mika Tuupola
	 *
	 * Licensed under the MIT license:
	 *   http://www.opensource.org/licenses/mit-license.php
	 *
	 * Project home:
	 *  http://www.appelsiini.net/projects/viewport
	 *
	 */
	$.belowthefold = function(element, settings) {
        var fold = $(window).height() + $(window).scrollTop();
        return fold <= $(element).offset().top - settings.threshold;
    };

    $.abovethetop = function(element, settings) {
        var top = $(window).scrollTop();
        return top >= $(element).offset().top + $(element).height() - settings.threshold;
    };
    
    $.rightofscreen = function(element, settings) {
        var fold = $(window).width() + $(window).scrollLeft();
        return fold <= $(element).offset().left - settings.threshold;
    };
    
    $.leftofscreen = function(element, settings) {
        var left = $(window).scrollLeft();
        return left >= $(element).offset().left + $(element).width() - settings.threshold;
    };
    
    $.inviewport = function(element, settings) {
        return !$.rightofscreen(element, settings) && !$.leftofscreen(element, settings) && !$.belowthefold(element, settings) && !$.abovethetop(element, settings);
    };

    $.extend($.expr[':'], {
        "below-the-fold": function(a, i, m) {
            return $.belowthefold(a, {threshold : 0});
        },
        "above-the-top": function(a, i, m) {
            return $.abovethetop(a, {threshold : 0});
        },
        "left-of-screen": function(a, i, m) {
            return $.leftofscreen(a, {threshold : 0});
        },
        "right-of-screen": function(a, i, m) {
            return $.rightofscreen(a, {threshold : 0});
        },
        "in-viewport": function(a, i, m) {
            return $.inviewport(a, {threshold : 0});
        }
    });
}(jQuery));