<?php
//load DB and App Config
$pageID = "2.1";
require_once("include/db.connect.inc.php");
require_once("include/app.config.inc.php");
?>
<?php
require(__ROOT__."library/products.class.php");

/* get and set category ID */
if(isset($_REQUEST['catID'])  )
{
	$catID = $_REQUEST['catID'];
}

#set filter and status parameters
$filterParam = array();
$statusParam = array('1');

/* get and set the filter criteria*/
if(isset($_REQUEST['txtSearch']) )
{
	$filterParam['productName'] = $_REQUEST['txtSearch'];
	$filterParam['productDescription'] = $_REQUEST['txtSearch'];
}

if(isset($_REQUEST['txtSearch'])  )
{
	$filterParam['`productName`'] = $_REQUEST['txtSearch'];
	$filterParam['`productDescription`'] = $_REQUEST['txtSearch'];
}

// how many pages we have when using paging?
$totalNumberOfRecords = Products::countRows($filterParam,$statusParam);

require_once(__ROOT__."include/pagination_offset_max.inc.php");

$allProducts = array();
$allProducts = Products::loadAllProductPerCat($catID,$filterParam,$statusParam,$offset,__ROWS_PER_PAGE__);

#close connection to database because they say is best practice
include_once("include/db.close.inc.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Welcome to Isherwood Athletics</title>
        <link href="css/style.css" rel="stylesheet" type="text/css" />
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
                List of Products Per Category
            </h1>
            <p>&nbsp;</p>
			<?php
                if(count($allProducts)>0)
                {
            ?>
            <div align="left">
                Displaying Page <?php echo $curr_page ?> of <?php echo $max_page?>
            </div>
            <div style="margin-bottom:15px; height: 30px; clear:both; margin-top:10px">
                <?php 
                    $criteria = "showing ".__ROWS_PER_PAGE__." most recent products";
                
                    if (@$_REQUEST['txtSearch'] != "" && @$_REQUEST['btnFilter'] == "Filter")
                    $criteria = "showing result for - ".$_REQUEST['txtSearch'];	
					
                    if (@$_REQUEST['txtSearch'] != "")
                    $criteria = "showing result for - ".$_REQUEST['txtSearch'];	
                ?>
                <div style="vertical-align:bottom; line-height:30px; float:left;font-size:14px"> 
                    <em id="txtStatus"><?php echo $criteria; ?></em>
                </div>
                <div style="float:right; vertical-align:middle">
                <form id="frmProductFilter" name="frmProductFilter" method="post" action="">
                    <input name="txtSearch" type="text" id="txtSearch" value="" />
                    <input name="btnFilter" type="submit" id="btnFilter" value="Filter" />
                    <input name="btnClear" type="button" id="btnClear" value="Clear Filter" onclick="location.href='category.php?catID=<?php echo $_REQUEST['catID'] ; ?>'" />
                </form>
                </div>
            </div>
               <?php
                foreach($allProducts as $allProduct)
                {
               ?>
                <div class="products_prv">
                    <div class="products_prv_img">
                    	<a href="product.php?pid=<?php echo $allProduct['productID'] ; ?>">
							 <?php
                                $imageURL = $allProduct['productPicture'];
                                if(isset($imageURL) && !empty($imageURL))
                                {
                            ?>
                                <img src="<?php echo __BASE_URL__ ;?>images/products/<?php echo $imageURL ;?>" width="150" height="200" alt="product image" />
                            <?php
                                }
                                else
                                {
                            ?>
                                <img src="<?php echo __BASE_URL__ ;?>images/no-image.jpg" width="150" height="200" alt="product image" />
                            <?php
                                }
                            ?>
                        </a>
                    </div>
                    <div class="products_prv_title"><a href="product.php?pid=<?php echo $allProduct['productID'] ; ?>"><?php echo $allProduct['productName'] ; ?></a></div>
                  	<div class="products_prv_price">&pound;<?php echo number_format($allProduct['price'] +($allProduct['price'] * 0.2),2) ;?></div>
                </div> 
			<?php
            	}//end foreach
            ?>                      
            </div>
            <div style="clear:both">&nbsp;</div>
            <!-- Begin pagination -->
            <?php require_once(__ROOT__."include/display_pagination.inc.php"); ?>
            <!-- End pagination -->
            
            <?php
                    }
                    else //count = 0
                    {
                ?>		
                
                <div class="emptyRecords">
                    No Product(s) Found!
                </div>
                
                <?php 
                    }//end if count > 0
                ?>
             </div>
            
            <!-- Footer starts here -->
            	<?php include_once(__ROOT__."include/footer.inc.php") ; ?>
            <!-- Footer ends here -->
            
          </div> 
       
    	</div>
        
    </body>
    
</html>
