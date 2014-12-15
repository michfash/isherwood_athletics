<?php
require("include/db.connect.inc.php");
require("include/app.config.inc.php");
require_once(__ROOT__."library/products.class.php");
/************************************************
	Search Functionality
************************************************/

// Get Search
$search_string = preg_replace("/[^A-Za-z0-9]/", " ", $_POST['query']);
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
			
			//*********************************//
				$searchResult = '<div class="products_prv">';
                $searchResult .= '<div class="products_prv_img">';
                $searchResult .=     	'<a href="product.php?pid='.$result["productID"].'">';
							$imageURL = $result["productPicture"];
							if(isset($imageURL) && !empty($imageURL))
							{
                $searchResult .=             '<img src="'.__BASE_URL__.'images/products/'.$imageURL.'" width="150" height="200" alt="product image" />';
							}
							else
							{
                $searchResult .=             '<img src="'.__BASE_URL__.'images/no-image.jpg" width="150" height="200" alt="product image" />';
							}
                $searchResult .=         '</a>';
                $searchResult .=     '</div>';
                $searchResult .=     '<div class="products_prv_title"><a href="product.php?pid='.$result["productID"].'">'.$result["productName"].'></a></div>';
                $searchResult .=   	'<div class="products_prv_price">&pound;'.number_format($result["price"] +($result["price"] * 0.2),2).'</div>';
                $searchResult .= '</div> ';
			//********************************//
			echo ($searchResult);
		}
	}else{
		
		// Format No Results Output
		$searchResult = '<div class="emptyRecords">
			No Product(s) Found!
		</div>';

		// Output
		echo($searchResult);
	}
}


/************************************************
	Search Functionality ends here
************************************************/

?>