<?php
$pageID = '6';
//load DB and App Config
require_once("../../include/db.connect.inc.php");
require_once("../../include/app.config.inc.php");
?>
<?php
require_once(__ROOT__."library/users.class.php");

require_once(__ROOT__."library/sizes.class.php");

session_start();
//authenticate user
require_once(__ROOT__."include/auth.check.inc.php");

//check if user is admin
require_once(__ROOT__."include/admin.check.inc.php");

$size = new Sizes();

#delete colour
if(isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'delete')
{
	$sizeID = $_REQUEST['sID'];
	$result = $size->setStatus($sizeID,9);
	if($result)
	{
		#deleted
		$feedback = "Size delete successful!";
		header("location:?notifMsg=".base64_encode($feedback)."&notifType=".base64_encode("success"));
	}
	else
	{
		#error occurred
		$feedback = "An error occurred while trying to delete size!";
		header("location:?notifMsg=".base64_encode($feedback)."&notifType=".base64_encode("error"));
	}
}

#set filter and status parameters
$filterParam = array();
$statusParam = array(0,1);

/* get and set the filter criteria*/
if(isset($_REQUEST['txtSearch']) )
{
	$filterParam['sizeDescription'] = $_REQUEST['txtSearch'];
}

if(isset($_REQUEST['txtSearch'])  )
{
	$filterParam['`sizeDescription`'] = $_REQUEST['txtSearch'];
}

// how many pages we have when using paging?
$totalNumberOfRecords = Sizes::countRows($filterParam,$statusParam);

require_once(__ROOT__."include/pagination_offset_max.inc.php");

$allSizes = array();
$allSizes = Sizes::loadAll_filter($filterParam,$statusParam,$offset,__ROWS_PER_PAGE__);


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
                Manage Sizes - List of Sizes
            </h1>
            <p>&nbsp;</p>
            <div align="left">
                Displaying Page <?php echo $curr_page ?> of <?php echo $max_page?>
            </div>
            <div style="margin-bottom:15px; height: 30px; clear:both; margin-top:10px">
                <?php 
                    $criteria = "showing ".__ROWS_PER_PAGE__." sizes";
                
                    if (@$_REQUEST['txtSearch'] != "" && @$_REQUEST['btnFilter'] == "Filter")
                    $criteria = "showing result for - ".$_REQUEST['txtSearch'];	
					
                    if (@$_REQUEST['txtSearch'] != "")
                    $criteria = "showing result for - ".$_REQUEST['txtSearch'];	
                ?>
                <div style="vertical-align:bottom; line-height:30px; float:left;font-size:14px"> 
                    <em id="txtStatus"><?php echo $criteria; ?></em>
                </div>
                <div style="clear:both;float:right;font-size:18px;margin-top:-25px">
                    <a href="<?php echo __BASE_URL__ ;?>admin/sizes/new.php">Add New</a>
                </div>
                <div style="float:right; vertical-align:middle">
                <form id="frmSizeFilter" name="frmSizeFilter" method="post" action="">
                    <input name="txtSearch" type="text" id="txtSearch" value="" />
                    <input name="btnFilter" type="submit" id="btnFilter" value="Filter" />
                    <input name="btnClear" type="button" id="btnClear" value="Clear Filter" onclick="location.href='index.php'" />
                </form>
                </div>
            </div>
            <table width="100%" height="155">
            	<thead style="background-color:#969; color:#FFF; text-align:center">
                	<tr>
                    	<td width="266">Size Description</td>
                    	<td width="160">Size Status</td>
                    	<td width="141">Action</td>
                    </tr>
                </thead>
                
			<?php
                if(count($allSizes)>0)
                {
					foreach($allSizes as $allSize)
					{
            ?>
               <tbody style="text-align:center">
               <tr>
                   <td>
                     <a href="<?php echo __BASE_URL__ ;?>admin/sizes/edit.php?cID=<?php echo $allSize['sizeID'] ; ?>"><?php echo $allSize['sizeDescription'] ; ?></a>
                    </td> 
                   <td>
				   		<?php 
							foreach($size->getProperty('statusTitle') as $key=>$value)
							{
								echo ($allSize['sizeStatus'] == $key) ?$value : "";
							}
						?>
                   </td>
                   <td>
                   	<a href="<?php echo __BASE_URL__ ;?>admin/sizes/edit.php?sID=<?php echo $allSize['sizeID'] ; ?>">Edit</a>
                    <a onclick="return confirm('Are you sure you want to delete <?php echo $allSize['sizeDescription'] ; ?>?')" href="?mode=delete&sID=<?php echo $allSize['sizeID'] ; ?>"> | Delete</a>
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
                        No Size(s) Found!
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
