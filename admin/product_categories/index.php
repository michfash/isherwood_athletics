<?php
$pageID = '6';
//load DB and App Config
require_once("../../include/db.connect.inc.php");
require_once("../../include/app.config.inc.php");
?>
<?php
require_once(__ROOT__."library/users.class.php");

require_once(__ROOT__."library/categories.class.php");

session_start();
//authenticate user
require_once(__ROOT__."include/auth.check.inc.php");

//check if user is admin
require_once(__ROOT__."include/admin.check.inc.php");

$category = new Categories();

#delete colour
if(isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'delete')
{
	$catID = $_REQUEST['catID'];
	$result = $category->setStatus($catID,9);
	if($result)
	{
		#deleted
		$feedback = "Category delete successful!";
		header("location:?notifMsg=".base64_encode($feedback)."&notifType=".base64_encode("success"));
	}
	else
	{
		#error occurred
		$feedback = "An error occurred while trying to delete Category!";
		header("location:?notifMsg=".base64_encode($feedback)."&notifType=".base64_encode("error"));
	}
}

#set filter and status parameters
$filterParam = array();
$statusParam = array(0,1);

/* get and set the filter criteria*/
if(isset($_REQUEST['txtSearch']) )
{
	$filterParam['catDescription'] = $_REQUEST['txtSearch'];
}

if(isset($_REQUEST['txtSearch'])  )
{
	$filterParam['`catDescription`'] = $_REQUEST['txtSearch'];
}

// how many pages we have when using paging?
$totalNumberOfRecords = Categories::countCatRows($filterParam,$statusParam);

require_once(__ROOT__."include/pagination_offset_max.inc.php");

$allCategories = array();
$allCategories = Categories::loadAllCat_filter($filterParam,$statusParam,$offset,__ROWS_PER_PAGE__);


#close connection to database because they say is best practice
include_once(__ROOT__."include/db.close.inc.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Welcome to Isherwood Athletics</title>
        <link href="<?php echo __BASE_URL__ ;?>css/style.css" rel="stylesheet" type="text/css" />
    </head>
    
    <body>
            
        <div id="container">
        
            <!-- header starts here -->
                <?php include_once(__ROOT__."include/header.inc.php") ;?>
            <!-- header ends here -->
            
            <div id="mainbody">
            <!-- sidebar starts here -->
            	<?php include_once(__ROOT__.'include/sidebar.inc.php') ;?>
            <!-- sidebar ends here -->
                        
            <div id="maincontent">
            
            <!-- notification starts here -->
            	<?php include_once(__ROOT__.'include/notification.inc.php') ;?>
            <!-- notification ends here -->	
			
            <p id="formstatus" style="font-size:12px;margin-bottom:15px">
                <?php
                
                    if(@$feedback != "")
                    {
                    	echo $feedback;
                    }
                
                ?>				  
            </p>
            <h1>
                Manage Product Categories
            </h1>
            <p>&nbsp;</p>
            <div align="left">
                Displaying Page <?php echo $curr_page ?> of <?php echo $max_page?>
            </div>
            <div style="margin-bottom:15px; height: 30px; clear:both; margin-top:10px">
                <?php 
                    $criteria = "showing ".__ROWS_PER_PAGE__." product categories";
                
                    if (@$_REQUEST['txtSearch'] != "" && @$_REQUEST['btnFilter'] == "Filter")
                    $criteria = "showing result for - ".$_REQUEST['txtSearch'];	
					
                    if (@$_REQUEST['txtSearch'] != "")
                    $criteria = "showing result for - ".$_REQUEST['txtSearch'];	
                ?>
                <div style="vertical-align:bottom; line-height:30px; float:left;font-size:14px"> 
                    <em id="txtStatus"><?php echo $criteria; ?></em>
                </div>
                <div style="clear:both;float:right;font-size:18px;margin-top:-25px">
                    <a href="<?php echo __BASE_URL__ ;?>admin/product_categories/new.php">Add New</a>
                </div>
                <div style="float:right; vertical-align:middle">
                <form id="frmCategoryFilter" name="frmCategoryFilter" method="post" action="">
                    <input name="txtSearch" type="text" id="txtSearch" value="" />
                    <input name="btnFilter" type="submit" id="btnFilter" value="Filter" />
                    <input name="btnClear" type="button" id="btnClear" value="Clear Filter" onclick="location.href='index.php'" />
                </form>
                </div>
            </div>
            <table width="100%" height="155">
            	<thead style="background-color:#969; color:#FFF; text-align:center">
                	<tr>
                    	<td width="266">Category Description</td>
                    	<td width="160">Category Status</td>
                    	<td width="141">Action</td>
                    </tr>
                </thead>
                
			<?php
                if(count($allCategories)>0)
                {
					foreach($allCategories as $allCategory)
					{
            ?>
               <tbody style="text-align:center">
               <tr>
                   <td>
                     <a href="<?php echo __BASE_URL__ ;?>admin/product_categories/edit.php?catID=<?php echo $allCategory['catID'] ; ?>"><?php echo $allCategory['catDescription'] ; ?></a>
                    </td> 
                   <td>
				   		<?php 
							foreach($category->getProperty('statusTitle') as $key=>$value)
							{
								echo ($allCategory['catStatus'] == $key) ?$value : "";
							}
						?>
                   </td>
                   <td>
                   	<a href="<?php echo __BASE_URL__ ;?>admin/product_categories/edit.php?catID=<?php echo $allCategory['catID'] ; ?>">Edit</a>
                    <a onclick="return confirm('Are you sure you want to delete <?php echo $allCategory['catDescription'] ; ?>?')" href="?mode=delete&catID=<?php echo $allCategory['catID'] ; ?>"> | Delete</a>
                    </td>
                </tr>
                </tbody>
			<?php
            	}//end foreach
            ?>    
            <tfoot>
            <tr> 
                <td>
                    <div style="clear:both">&nbsp;</div>
                    <!-- Begin pagination -->
                    <?php require_once(__ROOT__."include/display_pagination.inc.php"); ?>
                    <!-- End pagination -->
                 </td>
             </tr>
             
                    <?php
                            }
                            else //count = 0
                            {
                     ?>		
               <tr>
               	<td>
                    <div class="emptyRecords">
                        No Category(ies) Found!
                    </div>
                    
                    <?php 
                        }//end if count > 0
                    ?>
                    </td>
                </tr>
                </tfoot>
             </table>
             </div>
            
            <!-- Footer starts here -->
            	<?php include_once(__ROOT__."include/footer.inc.php") ; ?>
            <!-- Footer ends here -->
          </div>
       </div>
                
    </body>
    
</html>
