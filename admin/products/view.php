<?php
$pageID='7.2';
//load DB and App Config
require_once("../../include/db.connect.inc.php");
require_once("../../include/app.config.inc.php");
?>
<?php
#include classes
include(__ROOT__."library/products.class.php");
include(__ROOT__."library/sizes.class.php");
include(__ROOT__."library/colours.class.php");
require_once(__ROOT__."library/users.class.php");

session_start();
//authenticate user
require_once(__ROOT__."include/auth.check.inc.php");

//check if user is admin
require_once(__ROOT__."include/admin.check.inc.php");

#load object
$productDetails = new Products();

#set product id
if(isset($_REQUEST['productID']))
{
	$productID = $_REQUEST['productID'];
}

#delete item
if(isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'delete')
{
	$variationID = $_REQUEST['variationID'];
	$result = $productDetails->setVarStatus($variationID,9);
	if($result)
	{
		#deleted
		$feedback = "Item delete successful!";
		header("location:?productID=".$productID."&notifMsg=".base64_encode($feedback)."&notifType=".base64_encode("success"));
	}
	else
	{
		#error occurred
		$feedback = "An error occurred while trying to delete Item!";
		header("location:?productID=".$productID."&notifMsg=".base64_encode($feedback)."&notifType=".base64_encode("error"));
	}
}

#load product by productID
$product = $productDetails->load($productID);

$statusParam = array('1');
#load all sizes
$allSizes = array();
$allSizes = Sizes::loadAll_filter($filterParam="",$statusParam,$start="",$end="");

#load all colours
$allColours = array();
$allColours = Colours::loadAll_filter($filterParam="",$statusParam,$start="",$end="");

#assign product colour ids to an array
foreach($product as $key=>$value)
{
	$prodColours[]=$value['colourID']; 
}

#assign product size ids to an array
foreach($product as $key=>$value)
{
	$prodSizes[]=$value['sizeID']; 
}
#close connection to database because they say its best practice and we have to
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
                Manage Products - Details
            </h1>
            <p>&nbsp;</p>
			<?php
                if(!empty($product))
                {
            ?>
           		
                <div>
                    <div class="products_prv_img">
                    	<?php
							$imageURL = $product[0]['productPicture'];
							if( isset($imageURL) && !empty($imageURL) )
							{
						 ?>
					  <img src="<?php echo __BASE_URL__ ; ?>images/products/<?php echo $imageURL ;?>" width="140px" alt="product image" />
						<?php
							}
							else
							{
						?>
					  <img src="<?php echo __BASE_URL__ ; ?>images/no-image.jpg" width="140px" alt="product image" />
						<?php
							}
						?>
                    </div>
                </div> 
                
                <div style="padding:10px;">
                    <table width="486" style="font-size:12px">
                        <tr>
                            <td width="153">Product Name</td>
                            <td width="321">
                                <?php echo $product[0]['productName'] ; ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="153">Product Description</td>
                            <td width="321">
                                <?php echo $product[0]['productDescription'] ; ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="153">Product Category</td>
                            <td width="321">
                                <?php echo $product[0]['catDescription'] ; ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="153">Product Sub Category</td>
                            <td width="321">
                                <?php echo $product[0]['subCatDescription'] ; ?>
                            </td>
                        </tr>
                    </table>
              </div>
                                
                    <div style="margin:20px;font-size:12px">
<a href="<?php echo __BASE_URL__ ;?>admin/products/edit.php?productID=<?php echo $product[0]['productID'] ; ?>">
                        &raquo; Edit Product</a>
                    </div> 
               <div style="clear:both">&nbsp;</div>
                <div style="clear:both;float:right;font-size:18px;margin-top:-25px">
                    <a href="<?php echo __BASE_URL__ ;?>admin/products/new_item.php?productID=<?php echo $product[0]['productID'] ;?>">Add New Item</a>
                </div>
                    <table width="100%" height="155">
            	<thead style="background-color:#969; color:#FFF; text-align:center">
                	<tr>
                    	<td width="266">Size</td>
                    	<td width="266">Colour</td>
                    	<td width="160">Stock</td>
                    	<td width="160">Price</td>
                    	<td width="141">Action</td>
                    </tr>
                </thead>
                
			<?php
                if(count($product)>0)
                {
					foreach($product as $k=>$val)
					{
            ?>
               <tbody style="text-align:center">
               <tr>
                   <td>
				   	<?php 
						foreach($allSizes as $key=>$value)
						{
							echo ($value['sizeID'] == $val['sizeID']) ?$value['sizeDescription'] :'' ; 
						}
					?>
                   </td>
                   <td>
				   	<?php 
						foreach($allColours as $key=>$value)
						{
							echo ($value['colourID'] == $val['colourID']) ?$value['colourDescription'] :'' ; 
						}
					?>
				   </td> 
                   <td><?php echo $val['stock'] ; ?></td> 
                   <td><?php echo $val['price'] ; ?></td> 
                   <td>
                   	<a href="<?php echo __BASE_URL__ ;?>admin/products/edit_variation.php?variationID=<?php echo $val['variationID'] ; ?>">Edit</a>
                    <a onclick="return confirm('Are you sure you want to delete this item?')" href="?productID=<?php echo $productID ;?>&mode=delete&variationID=<?php echo $val['variationID'] ; ?>"> | Delete</a>
                    </td>
                </tr>
                </tbody>
			<?php
            	}//end foreach
            ?>    
            <tfoot>
            <tr> 
            	<td>&nbsp;</td>
             </tr>
             
                    <?php
                            }
                            else //count = 0
                            {
                     ?>		
               <tr>
               	<td>
                    <div class="emptyRecords">
                        No Product Item(s) Found!
                    </div>
                    
                    <?php 
                        }//end if count > 0
                    ?>
                    </td>
                </tr>
                </tfoot>
             </table>
            </div>
            <div style="clear:both">&nbsp;</div> 
                       
            <?php
                    }
                    else //product is empty
                    {
                ?>		
                
                <div class="emptyRecords">
                    No Product(s) Found!
              </div>
                
                <?php 
                    }//end if empty
                ?>
          </div>
            
            <!-- Footer starts here -->
            	<?php include_once(__ROOT__."include/footer.inc.php") ; ?>
            <!-- Footer ends here -->
            
          </div> 
       
    	</div>
        
    </body>
    
</html>
