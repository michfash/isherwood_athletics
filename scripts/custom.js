/* JS File */

// Start Ready
$(document).ready(function() {  

	// Live Search
	// On Search Submit and Get Results
	function search() {
		var query_value = $('input#txtSearch').val();
		$('b#search-string').html(query_value);
		if(query_value !== 'Search Product...'){
			$.ajax({
				type: "POST",
				url: "search.php",
				data: { query: query_value },
				cache: false,
				success: function(html){
					$("ul#results").html(html);
				}
			});
		}return false;    
	}
	
	function searchResult() {
		var query_value = $('input#txtSearch').val();
		$('b#search-string').html(query_value);
		if(query_value !== 'Search Product...'){
			$.ajax({
				type: "POST",
				url: "searchResult.php",
				data: { query: query_value },
				cache: false,
				success: function(searchResult){
					$("div#search_result").html(searchResult);
				}
			});
		}return false; 
	}

	$("input#txtSearch").live("keyup", function(e) {
		// Set Timeout
		clearTimeout($.data(this, 'timer'));

		// Set Search String
		var search_string = $(this).val();

		// Do Search
		if (search_string == '') {
			$("ul#results").fadeOut();
			$('h4#results-text').fadeOut();
			$('div#main_body').fadeIn();
			$('div#search_result').fadeOut();
		}else{
			$("ul#results").fadeIn();
			$('h4#results-text').fadeIn();
			$(this).data('timer', setTimeout(search, 100));
			$(this).data('timer', setTimeout(searchResult, 100));
			$('div#main_body').fadeOut();
			$('div#search_result').fadeIn();
			$('div#disp_param').fadeOut();
		};
	});

});