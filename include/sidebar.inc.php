<div id="sidebar">

    <span class="title"> Categories </span>
    <?php
		//load DB and App Config
		include(__ROOT__."include/db.connect.inc.php");
		#include categories class if not not on categories page
		if($pageID !='2') include_once(__ROOT__.'library/categories.class.php');
		
		@session_start();
		require_once(__ROOT__."library/users.class.php");
	
		if(isset($_SESSION['isherwood_athletics']['userHandle']))
		{
			$userHandle = unserialize($_SESSION['isherwood_athletics']['userHandle']);
		}
		
		#load object  
		$statusParam = array('1');
		$allCat = array();
		$allCat = Categories::loadAllCat_filter($filterParam="",$statusParam,0,__ROWS_PER_PAGE__);
		
		#close connection to database because they say is best practice
		include_once(__ROOT__."include/db.close.inc.php");
		
		foreach($allCat as $categories)
		{
	?>
   		 <span class="link"> <a href="<?php echo __BASE_URL__ ;?>category.php?catID=<?php echo $categories['catID'] ;?>"><?php echo $categories['catDescription'] ;?> </a></span>
    <?php
    	} 
	?>
    <?php
		if(!isset($userHandle))
		{
	?>
    <span class="title"><a href="<?php echo __BASE_URL__ ;?>users/login.php"> Customer Login </a></span>
    <?php
		}
		if(!isset($userHandle) || (isset($userHandle) && $userHandle->getProperty('role') != 0))
		{
	?>
    <span class="title"><a href="<?php echo __BASE_URL__ ;?>admin/login.php" target="_blank"> Admin Login </a></span>
    <?php
		}
		elseif($userHandle->getProperty('role') == 0)//else if user is admin, make admin panel visible
		{
	?>
    <span class="title"> Admin Panel </span>
    <span class="link"> <a href="<?php echo __BASE_URL__ ;?>admin/products/">Manage Products</a></span>
    <span class="link"> <a href="<?php echo __BASE_URL__ ;?>admin/product_categories/">Manage Products Categories</a></span>
    <span class="link"> <a href="<?php echo __BASE_URL__ ;?>admin/orders/">Manage Orders</a></span>
    <span class="link"> <a href="<?php echo __BASE_URL__ ;?>admin/sizes/">Manage Sizes</a></span>
    <span class="link"> <a href="<?php echo __BASE_URL__ ;?>admin/colours/">Manage Colours</a></span>
    <?php
		}
	?>
    
</div>