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

	var search_term;
	var search_searching;
	
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
		$('body').append($('<div class="search_window"></div>'));

		$('.search_window').load('templates/search_window.tmpl', function() {
			$('.search_window .search_loading').hide();
			$('.search_window .search_box').focus();
			$('.search_window .search_box').keypress(function(e) {
				if (e.which == 13) {
					performSearch();
				}
			});		

		});


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

	function performSearch() {
		//show the search box so you don't think nothing's happening
		$('.search_window .search_loading').show();
				
		search_term = $('.search_window .search_box').val();

		if (search_searching != true) {
			search_searching = true;

			$.ajax({
				url: "amazon/searchFor.php",
				type: 'post',
				dataType: 'json',			
				data: "search_term=" + search_term,
				success: function(response) {
					search_searching = false;
					populateSearchResultsBox(response);
				},
				error: function(response) {
					alert('Hey, something went wrong.\n' + response.responseText);
				}
			});
		}
	}

	function populateSearchResultsBox(response) {
		console.log("search_results");
		console.log(response);
		$('.search_window .search_loading').hide();
		$('.search_results').html("");

		var items = response.Items.Item;

		for (i in items) {
			var item_name = items[i].ItemAttributes.Title;
			var item_url = items[i].DetailPageURL;
			var item_id = items[i].ASIN;
			var item_img_url = items[i].SmallImage.URL;
			
			var $item = $('<div data-url="' + item_url + '" data-id="' + item_id + '"><img src="' + item_img_url + '" /><p>' + item_name + '</p></div>');
			
			$('.search_results').append($item);

			$item.click(function() {
				//useSelectedItem({"id":,"url":,"img_url":,"name": });
				useSelectedItem($(this));
			});
		}
		
	}

	function useSelectedItem($item) {
		closeSearch();
		console.log($item);

		$(".item_lookup").after($('<a href="'+ $item.attr("data-url") +'" target="_blank">'+ $item.children("p").html() +'</a>'));
		$(".item_lookup").remove();
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
	
	$('.item_lookup').click( function(e) {
		e.preventDefault();

		WhenIIWill.loadSearch();
	});

});
