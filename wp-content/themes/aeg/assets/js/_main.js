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
		  updateSortValue();
		  $('.pagination_bar .next').text('»');
		  $('.pagination_bar .prev').text('«');
		});  
	}
  },
  lecture:{
	init: function(){
		
		$(document).ready(function() {
            initEducationPopup();
			updateEducationSortValue();
        });	
	}
  },
  videos:{
	init: function(){
		$(document).ready(function() {
            initEducationPopup();
			updateEducationSortValue();
        });	
	}
  },
  image:{
	init: function(){
		$(document).ready(function() {
            initEducationPopup();
			updateEducationSortValue();
        });	
	}
  },
  on_going_research:{
	init: function(){
		$(document).ready(function(){
			updateResearchSortValue();
		});
	}
  },
  past_research:{
	init: function(){
		$(document).ready(function(){
			updateResearchSortValue();
		});
	}
  },
  research_published:{
	init: function(){
		$(document).ready(function(){
			updateResearchSortValue();
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

function getUrlVars()
{
	var vars = [], hash;
	var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
	for(var i = 0; i < hashes.length; i++)
	{
		hash = hashes[i].split('=');
		vars.push(hash[0]);
		vars[hash[0]] = hash[1];
	}
	return vars;
}	

function updateSortValue(){
	var current_sort = '';
	var sort_str = '';
	
	current_sort = getUrlVars().sorting;	
	//console.log(current_sort);
	
	if(typeof(current_sort) !== 'undefined'){
		$("#sorting_control").val(current_sort);	
	}else{
		console.log($("#sorting_control option:first").val());
		$("#sorting_control").find('option:first').prop('selected', 'selected');
	}
	
	$('#sorting_control').change(function(){
		updateSortStr();
	});
	
	function updateSortStr(){
		sort_str = '';	
		sort_str = $('#sorting_control').val();
		var url = full_url + '?sorting=' + sort_str;
		window.location.href = url;
	}	
}

function updateEducationSortValue(){
	var sort_str = {};
	var current_sort = '';
	current_sort = getUrlVars().sorting;
	
	if(typeof(current_sort) !== 'undefined'){
		switch (current_sort) {
			case 'title_asc':
				$('.title_sort').find('i').removeClass('fa-caret-down').addClass('fa-caret-up');
				break;
			case 'title_desc':
				$('.title_sort').find('i').removeClass('fa-caret-up').addClass('fa-caret-down');
				break;
			case 'speaker_asc':
				$('.speaker_sort').find('i').removeClass('fa-caret-down').addClass('fa-caret-up');
				break;
			case 'speaker_desc':
				$('.speaker_sort').find('i').removeClass('fa-caret-up').addClass('fa-caret-down');
				break;
			case 'date_asc':
				$('.date_sort').find('i').removeClass('fa-caret-down').addClass('fa-caret-up');
				break;
			case 'date_desc':
				$('.date_sort').find('i').removeClass('fa-caret-up').addClass('fa-caret-down');
				break;
		}	
	}
	
	
	function updateSortStr(current_sort){
		// 0: title
		// 1: speaker
		// 2: date
		sort_str = [full_url + '?sorting=title_asc', full_url + '?sorting=speaker_asc', full_url + '?sorting=date_asc'];
		
		var temp_str = '';
		
		switch (current_sort) {
			case 'title_asc':
				temp_str = full_url + '?sorting=title_desc';
				sort_str[0] = temp_str;
				break;
			case 'title_desc':
				temp_str = full_url + '?sorting=title_asc';
				sort_str[0] = temp_str;
				break;
			case 'speaker_asc':
				temp_str = full_url + '?sorting=speaker_desc';
				sort_str[1] = temp_str;
				break;
			case 'speaker_desc':
				temp_str = full_url + '?sorting=speaker_asc';
				sort_str[1] = temp_str;
				break;
			case 'date_asc':
				temp_str = full_url + '?sorting=date_desc';
				sort_str[2] = temp_str;
				break;
			case 'date_desc':
				temp_str = full_url + '?sorting=date_asc';
				sort_str[2] = temp_str;
				break;
		}	
		
		$('.title_sort').attr('href', sort_str[0]);
		$('.speaker_sort').attr('href', sort_str[1]);
		$('.date_sort').attr('href', sort_str[2]);
	}	
	updateSortStr(current_sort);
}

function updateResearchSortValue(){
	var sort_str = {};
	var current_sort = '';
	current_sort = getUrlVars().sorting;
	
	if(typeof(current_sort) !== 'undefined'){
		switch (current_sort) {
			case 'title_asc':
				$('.title_sort').find('i').removeClass('fa-caret-down').addClass('fa-caret-up');
				break;
			case 'title_desc':
				$('.title_sort').find('i').removeClass('fa-caret-up').addClass('fa-caret-down');
				break;
			case 'speaker_asc':
				$('.speaker_sort').find('i').removeClass('fa-caret-down').addClass('fa-caret-up');
				break;
			case 'speaker_desc':
				$('.speaker_sort').find('i').removeClass('fa-caret-up').addClass('fa-caret-down');
				break;
		}	
	}
	
	
	function updateSortStr(current_sort){
		// 0: title
		// 1: researcher
		sort_str = [full_url + '?sorting=title_asc', full_url + '?sorting=speaker_asc'];
		
		var temp_str = '';
		
		switch (current_sort) {
			case 'title_asc':
				temp_str = full_url + '?sorting=title_desc';
				sort_str[0] = temp_str;
				break;
			case 'title_desc':
				temp_str = full_url + '?sorting=title_asc';
				sort_str[0] = temp_str;
				break;
			case 'speaker_asc':
				temp_str = full_url + '?sorting=speaker_desc';
				sort_str[1] = temp_str;
				break;
			case 'speaker_desc':
				temp_str = full_url + '?sorting=speaker_asc';
				sort_str[1] = temp_str;
				break;
		}	
		
		$('.title_sort').attr('href', sort_str[0]);
		$('.speaker_sort').attr('href', sort_str[1]);
	}	
	updateSortStr(current_sort);
}


})(jQuery); // Fully reference jQuery after this point.
