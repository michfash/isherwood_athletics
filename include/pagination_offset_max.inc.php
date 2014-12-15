<?php

	// by default we show first page, else show the page specified. If a page higher than the max page is specified, max page is specified instead
	$curr_page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
	if(isset($_REQUEST['btnFilter']) && $_REQUEST['btnFilter'] == "Filter") $curr_page =1;	
	
	if ($curr_page < 1) $curr_page = 1;
	
	$max_page = ceil($totalNumberOfRecords / __ROWS_PER_PAGE__);
	if ($curr_page > $max_page) $curr_page = $max_page; //current page cannot be greater than max page
	
	// counting the offset
	#echo "offset: ".
	$offset = max($curr_page - 1,0) * __ROWS_PER_PAGE__;
		
?>