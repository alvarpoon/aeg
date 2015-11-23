/* ========================================================================
 * DOM-based Routing
 * Based on http://goo.gl/EUTi53 by Paul Irish
 *
 * Only fires on body classes that match. If a body class contains a dash,
 * replace the dash with an underscore when adding it to the object below.
 *
 * .noConflict()
 * The routing is enclosed within an anonymous function so that you can 
 * always reference jQuery with $, even when in .noConflict() mode.
 *
 * Google CDN, Latest jQuery
 * To use the default WordPress version of jQuery, go to lib/config.php and
 * remove or comment out: add_theme_support('jquery-cdn');
 * ======================================================================== */

(function($) {

// Use this variable to set up the common and page specific functions. If you 
// rename this variable, you will also need to rename the namespace below.
var Roots = {
  // All pages
  common: {
    init: function() {
      // JavaScript to be fired on all pages
      //var $ = jQuery.noConflict();

	  $(".various").fancybox({
			maxWidth	: 700,
			maxHeight	: 700,
			padding		: 30,
			fitToView	: false,
			width		: '70%',
			height		: '70%',
			autoSize	: false,
			closeClick	: false,
			openEffect	: 'none',
			closeEffect	: 'none'
		});		
    }
  },
  // Home page
  home: {
    init: function() {
      // JavaScript to be fired on the home page
	  $(document).ready(function(e) {
		$('.home-banner').slick({
			slidesToShow: 1,
			slidesToScroll:1,
			dots: true
		});
		  
      	$('.committees-home').slick({
			slidesToShow: 4,
			slidesToScroll:4,
			dots: false	
		});  
		
		$('.members-home').slick({
			slidesToShow: 4,
			slidesToScroll:4,
			dots: false	
		});  
		
		
      });
	  
    }
  },
  // About us page, note the change from about-us to about_us.
  about_us: {
    init: function() {
      // JavaScript to be fired on the about us page
    }
  },
  our_committees:{
	init: function() {
	  function initContentHeight(){
		$('.expandable-content-wrapper').each(function(){
			$(this).parent().attr('data-original-height',$(this).outerHeight());
			if(!$(this).parent().parent().find('.expandable-header').hasClass('open')){
				$(this).parent().css('height',0);
			}
		});  
	  }
	  
	  function initDetailPopup(){		
		$(".btn-detail").each(function(){
			$(this).click(function(){
				$('.committee_popup_content').remove();
				var post_ID = $(this).attr('data-code');
				$.post( '../../wp-content/themes/aeg/templates/committee_popup.php', {postID: post_ID})
				.done(function( data ) {
					$('#committee_popup').append(data);
				});
			});
		});  
	  }
	  
	  $(document).ready(function(){
		  initContentHeight();
		  initDetailPopup();
		  $( ".expandable-header").click(function() {
			if($(this).hasClass('open')){
			  	$(this).parent().find( ".expandable-content" ).animate({
					height:'0px'
				},500);
				$(this).removeClass('open');
			}else{
				$original_height = parseInt($(this).parent().find( ".expandable-content" ).attr('data-original-height'));
				$(this).parent().find( ".expandable-content" ).animate({
					height:$original_height
				},500);
				$(this).addClass('open');
			}
		  });  
		  
		  
	  });
	  $(window).resize(function(){
		initContentHeight();  
	  });
	}
  },
  our_members:{
	init: function(){
		$(document).ready(function() {
		  $('.pagination_bar .next').text('»');
		  $('.pagination_bar .prev').text('«');
		});  
	}
  },
  lecture:{
	init: function(){
		
		$(document).ready(function() {
            initEducationPopup();
        });	
	}
  },
  videos:{
	init: function(){
		$(document).ready(function() {
            initEducationPopup();
        });	
	}
  },
  image:{
	init: function(){
		$(document).ready(function() {
            initEducationPopup();
        });	
	}
  },
  up_coming_events:{
	init: function(){
		var masonryOptions = {
			itemSelector: '.event-item',
			columnWidth: '.event-sizer',
			gutter: '.gutter-sizer',
			percentPosition: true
		};
		$(document).ready(function() {
			if($('.event-grid').length > 0){
				$(window).load(function(){	
					var product_grid = $('.event-grid').masonry(masonryOptions);
				});	
			}
		});
	}
  }
};

// The routing fires all common scripts, followed by the page specific scripts.
// Add additional events for more control over timing e.g. a finalize event
var UTIL = {
  fire: function(func, funcname, args) {
    var namespace = Roots;
    funcname = (funcname === undefined) ? 'init' : funcname;
    if (func !== '' && namespace[func] && typeof namespace[func][funcname] === 'function') {
      namespace[func][funcname](args);
    }
  },
  loadEvents: function() {
    UTIL.fire('common');

    $.each(document.body.className.replace(/-/g, '_').split(/\s+/),function(i,classnm) {
      UTIL.fire(classnm);
    });
  }
};

$(document).ready(UTIL.loadEvents);

function initEducationPopup(){
  $(".btn_lecture_detail").each(function(){
		$(this).click(function(){
			$('.lecture_popup_content').remove();
			var post_ID = $(this).attr('data-code');
			$.post( '../../wp-content/themes/aeg/templates/lecture_popup.php', {postID: post_ID})
			.done(function( data ) {
				$('#lecture_content').append(data);
			});
		});
  });
  $(".btn_author_detail").each(function(){
		$(this).click(function(){
			$('.author_popup_content').remove();
			var post_ID = $(this).attr('data-code');
			$.post( '../../wp-content/themes/aeg/templates/author_popup.php', {postID: post_ID})
			.done(function( data ) {
				$('#author_content').append(data);
			});
		});
  });
}

})(jQuery); // Fully reference jQuery after this point.
