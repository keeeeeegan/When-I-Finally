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
	var valid_to_save;
	var $selected_item = "";
	var sending_motivation;

	var selected_item_url;
	var selected_item_img_url;
	var selected_item_id;
	var selected_item_name;
	
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

		$(".motivation_milestone").keypress(function(e) {
			checkIfValid();
		});	

		valid_to_save = false;
		sending_motivation = false;
		
	}

	/**
	 * Creates and sends the request to the php file to retreive
	 * XML data from the Flickr api
	 *
	 * @param string search_term
	 *
	 */
	function getMotivations(twitter_name) {
		if (typeof twitter_name != 'undefined') {
			//console.log("retrieving those motivations for " + twitter_name);

			$.ajax({
				url: "db/getPublicMotivations.php",
				type: 'post',
				dataType: 'json',			
				data: "motivations=get&twitter_user=" + twitter_name,
				success: function(response) {
					$('.my_motivations').removeClass('loading');
					if (response == '404') {
						$('h3').html("Not found!");
						$('.my_motivations').append($("<p>That user doesn't exist here! Move along!</p>"));
					}
					else { 
						if (response.motivations.length == 0) {
							$('.my_motivations').append($('<p class="no_motivations">' + response.user_name + ' doesn\'t have any motivations yet!</p>'));
						}
						
						$('h3').html(response.user_name + "'s Motivations");
						
						for (i in response.motivations) {
							$('.my_motivations').append('<div class="motivation"><strong>When I finally ' + response.motivations[i].milestone + ', I will buy myself <a href="' + response.motivations[i].item_url + ' target="_blank">' + response.motivations[i].item_name + '</a></strong></div>');
						}
					}
				},
				error: function(response) {
					alert('Hey, something went wrong.\n' + response.responseText);
				}
			});	

		}
		else {

			$.ajax({
				url: "db/getMotivations.php",
				type: 'post',
				dataType: 'json',			
				data: "motivations=get",
				success: function(response) {
					$('.my_motivations').removeClass('loading');
					//console.log(response);
					if (response.length == 0) {
						$('.my_motivations').append($('<p class="no_motivations">You don\'t have any motivations! Start motivating!</p>'));
					}
					for (i in response) {
						$('.my_motivations').append('<div class="motivation"><strong>When I finally ' + response[i].milestone + ', I will buy myself <a href="' + response[i].item_url + ' target="_blank">' + response[i].item_name + '</a></strong><a class="delete" href="#" data-id="' + response[i].motivation_id + '">delete</a></div>');
					}
				},
				error: function(response) {
					alert('Hey, something went wrong.\n' + response.responseText);
				}
			});	

		}
	}


	/**
	 * Deletes a user's motivation
	 *
	 * @param string item_to_delete
	 * @param string motivation_id
	 *
	 */
	function deleteMotivation($item, motivation_id) {

		var sure = confirm("Woah, are you sure you want to delete this motivation!?");

		if (sure == true) {
			$.ajax({
				url: "db/deleteMotivation.php",
				type: 'post',
				dataType: 'json',			
				data: "motivation=delete&motivation_id=" + motivation_id,
				success: function(response) {
					//console.log(response);
					$item.fadeOut('fast');
				},
				error: function(response) {
					alert('Hey, something went wrong.\n' + response.responseText);
				}
			});	

		}
	}	

	/**
	 * Loads the saerch box
	 *
	 *
	 */
	function loadSearch() {
		//console.log("loading search box");

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
		// setup listeners for closing the window
		$('.close').live('click', function(e) {
			
			e.preventDefault();
			WhenIIWill.closeSearch();
		});				
	}	

	/**
	 * remove search box
	 *
	 */
	function closeSearch() {
		//console.log("closing those windows");

		$('.search_window').remove();
		$('.search_window_cover').remove();
	}


	/**
	 * Send request for a search
	 *
	 */
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


	/**
	 * Add content to the serach rsults box
	 *
	 */
	function populateSearchResultsBox(response) {
		//console.log("search_results");
		//console.log(response);
		$('.search_window .search_loading').hide();
		$('.search_results').html("");

		var items = response.Items.Item;

		for (i in items) {
			var item_name = items[i].ItemAttributes.Title;
			var item_url = items[i].DetailPageURL;
			var item_id = items[i].ASIN;
			var item_img_url = "img/no-product-img-amazon-logo.png";

			if (typeof items[i].SmallImage != 'undefined') {
				if (typeof items[i].SmallImage.URL != 'undefined') {
					item_img_url = items[i].SmallImage.URL;
				}
			}

			var $item = $('<div data-url="' + item_url + '" data-id="' + item_id + '"><img src="' + item_img_url + '" /><p>' + item_name + '</p></div>');
			
			$('.search_results').append($item);

			$item.click(function() {
				//useSelectedItem({"id":,"url":,"img_url":,"name": });
				useSelectedItem($(this));
			});
		}
		
	}

	function submitMotivation() {
		if (valid_to_save == true) {
			//console.log($(".motivation_milestone").val() + " " + $selected_item.attr("data-url") + " " + $selected_item.attr("data-id") + " " + $selected_item.children("p").html());
			//console.log($(".motivation_milestone").val() + " " + selected_item_url + " " + selected_item_id + " " + selected_item_name + " " + selected_item_img_url);

			$('.add_new_motivation_submit').addClass('disabled').addClass('loading');

			if (sending_motivation != true) {
				sending_motivation = true;

				$.ajax({
					url: "db/saveItem.php",
					type: 'post',
					dataType: 'text',			
					data: "milestone=" + $(".motivation_milestone").val() + "&item_url=" + selected_item_url + "&item_img_url=" + selected_item_img_url + "&item_id=" + selected_item_id + "&item_name=" + selected_item_name,
					success: function(response) {
						$('.description').slideUp('fast', function() {
							$('.description').html('<h3>Motivation Saved!</h3><p><a href="profile.php">Go to your motivations!</a></p>');
							$('.description').slideDown('fast');
						});			
					},
					error: function(response) {
						alert('Hey, something went wrong.\n' + response.responseText);
						sending_motivation = false;
					}
				});
			}
		}
	}

	function useSelectedItem($item) {
		$selected_item = $item;
		closeSearch();
		//console.log($selected_item);

		selected_item_url = $selected_item.attr("data-url");
		selected_item_id = $selected_item.attr("data-id");
		selected_item_img_url = $selected_item.children("img").attr("src");
		selected_item_name = $selected_item.children("p").html();

		$(".item_lookup").after($('<a href="'+ selected_item_url +'" target="_blank">'+ selected_item_name +'</a>'));
		$(".item_lookup").remove();

		checkIfValid();
	}

	function checkIfValid() {
		//console.log("checking");

		if ($selected_item != "" && $(".motivation_milestone").val() != "") {
			valid_to_save = true;

			$('.add_new_motivation_submit').removeClass('disabled');
			//console.log("yep");
		}
		else {
			valid_to_save = false;
			$('.add_new_motivation_submit').addClass('disabled');
			//console.log("nope");
		}

	}

	function repositionSearchBox() {
		//console.log("okay but I need to check first");
		if ($('.search_window').length > 0) {
			//console.log("do it");

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
		getMotivations: getMotivations,
		loadSearch: loadSearch,
		closeSearch: closeSearch,
		submitMotivation: submitMotivation,
		deleteMotivation: deleteMotivation
	};

}());

$(document).ready(function() {

	WhenIIWill.init();

	if ($('body').hasClass('profile')) {
		WhenIIWill.getMotivations();
	}

	if ($('body').hasClass('public_profile')) {
		WhenIIWill.getMotivations($('body').attr('id'));
	}

	$('.item_lookup').click( function(e) {
		e.preventDefault();

		WhenIIWill.loadSearch();
	});

	$('.add_new_motivation_submit').click( function(e) {
		e.preventDefault();

		WhenIIWill.submitMotivation();
	});

	$('.delete').live('click', function(e) {
		e.preventDefault();


		WhenIIWill.deleteMotivation($(this).parent(), $(this).attr('data-id'));
	});	

});
