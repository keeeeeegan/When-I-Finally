/**
 * When I, I will...
 *
 * @version 	1.0
 * @author 		Keegan Berry
 * @since		04/017/12
 *
 */

if(!window.WhenIIWill)
{
	window.WhenIIWill = {};
}

WhenIIWill = (function() {

	var a_variable;
	
	/**
	 * Display and hide page elements as necessary
	 *
	 */
	function init() {
		$(window).scroll(function() {
			repositionSearchBox();
		});
		$(window).resize(function() {
			repositionSearchBox();
		});		

		
	}

	/**
	 * Creates and sends the request to the php file to retreive
	 * XML data from the Flickr api
	 *
	 * @param string search_term
	 *
	 */
	function getUserMotivations(user_id) {
		console.log("retrieving those motivations");

		$('.my_motivations').removeClass('loading');
		$('.my_motivations').append($('<p class="no_motivations">You don\'t have any motivations! Start motivating!</p>'));
	}

	/**
	 * Creates and sends the request to the php file to retreive
	 * XML data from the Flickr api
	 *
	 * @param string search_term
	 *
	 */
	function loadSearch() {
		console.log("loading search box");

		$('body').append($('<div class="search_window_cover"></div>'));
		$('body').append($('<div class="search_window">searching....</div>'));

		repositionSearchBox();

		// setup listeners for closing the window
		$('.search_window_cover').click( function(e) {
			e.preventDefault();
			WhenIIWill.closeSearch();
		});		
	}	

	function closeSearch() {
		console.log("closing those windows");

		$('.search_window').remove();
		$('.search_window_cover').remove();
	}

	function repositionSearchBox() {
		console.log("okay but I need to check first");
		if ($('.search_window').length > 0) {
			console.log("do it");

			var win_width = $(window).width();
			var win_height = $(window).height();
			var search_width = $('.search_window').width(); 
			var search_height = $('.search_window').height(); 

			var place_x = win_width/2 - search_width/2;
			var place_y = win_height/2 - search_height/2;

			$('.search_window').css({'top':place_y , 'left':place_x})
		}
	}

	return {
		init: init,
		getMotivations: getUserMotivations,
		loadSearch: loadSearch,
		closeSearch: closeSearch
	};

}());

$(document).ready(function() {

	WhenIIWill.init();

	if ($('body').hasClass('profile')) {
		WhenIIWill.getMotivations();
	}

	$('.item_lookup').click( function(e) {
		e.preventDefault();

		WhenIIWill.loadSearch();
	});

});
