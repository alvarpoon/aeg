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
    }
  },
  // Home page
  home: {
    init: function() {
      // JavaScript to be fired on the home page
	  $(document).ready(function(e) {
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
		});  
	  }
	  
	  function initDetailPopup(){
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
			closeEffect	: 'none',
			onClosed:function(){
                //$(window).trigger('fancyboxClosed');
				console.log('fancyboxCLosed');
				$('.committee_popup_content').remove();
            }
		});
		
		$(window).on('fancyboxClosed', function(){
			
			
		});
		
		$(".btn-detail").each(function(){
			$(this).click(function(){ // start execute when option is changed
				// set the currency rate
				$('.committee_popup_content').remove();
				var post_ID = $(this).attr('data-code');
				/********************************************************************************/
				// Using jQuery Ajax Method to query the price and stock of the option combination
				$.post( '../../wp-content/themes/aeg/templates/committee_popup.php', {postID: post_ID})
				.done(function( data ) {
					$('#committee_popup').append(data);
				});
				/********************************************************************************/
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
		  /*var text = $('.pagination_bar').html();
		  text = text.replace('Previous ', '');
		  text = text.replace('Next ', '');
		  $('.pagination').html(text);	*/
		  $('.pagination_bar .next').text('»');
		  $('.pagination_bar .prev').text('«');
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

})(jQuery); // Fully reference jQuery after this point.
