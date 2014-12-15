<div class="pagination_store" style="display:none;">
	<a href="#" title="First Page">&lt;&lt;</a>
	<a href="#" title="Previous Page">&lt;</a>
	<a href="#" class="active">1</a>
	<a href="#">2</a>
	<a href="#">3</a>
	<a href="#">4</a>
	<a href="#">5</a>
	<a href="#">6</a>
	<a href="#">7</a>
	<a href="#">8</a>
	<a href="#">9</a>
	<a href="#">10 ...</a>
	<a href="#" title="Next Page">&gt;</a>
	<a href="#" title="Last Page">&gt;&gt;</a>
</div>

<?php 
$page = 1;
if(isset($_REQUEST['page'])) $page = $_REQUEST['page'];
if($max_page > 1)
{
	$nIcons = 17;
	
	$firstVisibleLink = max($curr_page - floor($nIcons/2),1);
	$lastVisibleLink = min($firstVisibleLink + $nIcons,$max_page);
	
	if($lastVisibleLink - $firstVisibleLink < $nIcons) $firstVisibleLink -= max(1,$nIcons-($lastVisibleLink - $firstVisibleLink));
	$firstVisibleLink = max($firstVisibleLink,1);
?> 

<div class="pagination" align="center" style="float:none; margin:20px 0px;">

	<a href="?page=<?php echo 1; ?><?php
										if(!empty($filterParam))
										{
											$txtSearch = $_REQUEST['txtSearch'];
										}
										if(!empty($_REQUEST['txtSearch']))
										{
											$txtSearch = $_REQUEST['txtSearch'];
										}
										echo (isset($_REQUEST['txtSearch']))?"&txtSearch=$txtSearch":"";
										echo(isset($_REQUEST['catID']))?'&catID='.$_REQUEST['catID']:'';
									?>">First
	</a>
	<a href="?page=<?php echo max($curr_page-1,1); ?><?php
														if(!empty($filterParam))
														{
															$txtSearch = $_REQUEST['txtSearch'];
														}
														if(!empty($_REQUEST['txtSearch']))
														{
															$txtSearch = $_REQUEST['txtSearch'];
														}
										echo (isset($_REQUEST['txtSearch']))?"&txtSearch=$txtSearch":"";
										echo(isset($_REQUEST['catID']))?'&catID='.$_REQUEST['catID']:'';
													  ?>">Prev
	</a>
	
	<?php
	for( $p = $firstVisibleLink; $p <= $lastVisibleLink; $p++ )
	{
	?>
	<a 
		href="?page=<?php echo $p; ?><?php
										if(!empty($filterParam))
										{
											$txtSearch = $_REQUEST['txtSearch'];
										}
										if(!empty($_REQUEST['txtSearch']))
										{
											$txtSearch = $_REQUEST['txtSearch'];
										}
										echo (isset($_REQUEST['txtSearch']))?"&txtSearch=$txtSearch":"";
										echo(isset($_REQUEST['catID']))?'&catID='.$_REQUEST['catID']:'';
									  ?>" 
		class="<?php echo ($p == $curr_page) ? "active" : "" ?>" 
		title="<?php echo "Page ".$p; ?>"
	>
	<?php 
		echo ($p == $firstVisibleLink && $firstVisibleLink > 1) ? "&hellip;" : "";
		echo $p;
		echo ($p == $lastVisibleLink && $lastVisibleLink < $max_page) ? "&hellip;" : ""; 
	?>
	</a> 
	<?php
	}//next page
	?>
	
	<a href="?page=<?php echo min($page+1,$max_page); ?><?php
															if(!empty($filterParam))
															{
																$txtSearch = $_REQUEST['txtSearch'];
															}
															if(!empty($_REQUEST['txtSearch']))
															{
																$txtSearch = $_REQUEST['txtSearch'];
															}
										echo (isset($_REQUEST['txtSearch']))?"&txtSearch=$txtSearch":"";
										echo(isset($_REQUEST['catID']))?'&catID='.$_REQUEST['catID']:'';
														 ?>">Next
	</a>
	<a href="?page=<?php echo $max_page; ?><?php
												if(!empty($filterParam))
												{
													$txtSearch = $_REQUEST['txtSearch'];
												}
												if(!empty($_REQUEST['txtSearch']))
												{
													$txtSearch = $_REQUEST['txtSearch'];
												}
										echo (isset($_REQUEST['txtSearch']))?"&txtSearch=$txtSearch":"";
										echo(isset($_REQUEST['catID']))?'&catID='.$_REQUEST['catID']:'';
											?>">Last
	</a>

</div>
<?php
}
?>