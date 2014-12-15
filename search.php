<?php
require("include/db.connect.inc.php");
require("include/app.config.inc.php");
require_once(__ROOT__."library/products.class.php");
/************************************************
	Search Functionality
************************************************/

// Define Output HTML Formating
$html = '';
$html .= '<li class="result">';
$html .= '<a href="urlString">';
$html .= '<h3>nameString</h3>';
$html .= '<h5>descriptionString</h5>';
$html .= '<h5>(categoryString)</h5>';
$html .= '</a>';
$html .= '</li>';

// Get Search
$search_string = preg_replace("/[^A-Za-z0-9]/", " ", $_REQUEST['query']);
$search_string = mysql_real_escape_string($search_string);

// Check Length More Than One Character
if (strlen($search_string) >= 1 && $search_string !== ' ') {
	
	$filterParam = array();
	$statusParam = array(1);

	/* get and set the filter parameters*/
	if(isset($_REQUEST['query']) )
	{
		$filterParam['productName'] = $_REQUEST['query'];
		$filterParam['productDescription'] = $_REQUEST['query'];
		$filterParam['catDescription'] = $_REQUEST['query'];
	}
	// how many pages we have when using paging?
	$totalNumberOfRecords = Products::countRows($filterParam,$statusParam);
	
	require_once(__ROOT__."include/pagination_offset_max.inc.php");
	
	// Do Search
	$result_array = array();
	$result_array = Products::loadAll_filter($filterParam,$statusParam,$offset,__ROWS_PER_PAGE__);

	// Check If We Have Results
	if (isset($result_array) && count($result_array) > 0) {
		foreach ($result_array as $result) {

			// Format Output Strings And Hightlight Matches
			$display_name = preg_replace("/".$search_string."/i", "<b class='highlight'>".$search_string."</b>", $result['productName']);
			$display_description = preg_replace("/".$search_string."/i", "<b class='highlight'>".$search_string."</b>", $result['productDescription']);
			$display_category = preg_replace("/".$search_string."/i", "<b class='highlight'>".$search_string."</b>", $result['catDescription']);
			$display_url = __BASE_URL__.'product.php?pid='.urlencode($result['productID']);

			// Insert Name
			$output = str_replace('nameString', $display_name, $html);

			// Insert Description
			$output = str_replace('descriptionString', $display_description, $output);
			
			// Insert Category
			$output = str_replace('categoryString', $display_category, $output);

			// Insert URL
			$output = str_replace('urlString', $display_url, $output);

			// Output
			echo($output);
		}
	}else{
		
		// Format No Results Output
		$output = str_replace('urlString', 'javascript:void(0);', $html);
		$output = str_replace('nameString', '<b>No Results Found.</b>', $output);
		$output = str_replace('descriptionString', 'Sorry :-(', $output);
		$output = str_replace('(categoryString)', '', $output);

		// Output
		echo($output);
	}
}


/************************************************
	Search Functionality ends here
************************************************/

?>