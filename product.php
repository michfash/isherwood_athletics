<?php
$pageID='';
//load DB and App Config
require_once("include/db.connect.inc.php");
require_once("include/app.config.inc.php");
?>
<?php
session_start();
#include classes
include(__ROOT__."library/products.class.php");
include(__ROOT__."library/sizes.class.php");
include(__ROOT__."library/colours.class.php");
require_once(__ROOT__."library/users.class.php");
require_once(__ROOT__."library/carts.class.php");

if(isset($_SESSION['isherwood_athletics']['userHandle']))
{
	$userHandle = unserialize($_SESSION['isherwood_athletics']['userHandle']);
	#set userID
	$userID = $userHandle->getProperty("userID") ;
}


#load object
$productDetails = new Products();
$cart = new Carts();

/*
	TO-DO
	*********************************************
	1.	Check stock level 
	2.	Update stock level 
	3.	Add to cart
	4.	Direct to cart page
	*********************************************
*/
if(isset($_REQUEST['btnCart']))
{
	#get form value
	$productID = $_REQUEST['pid'];
	$sizeID = $_REQUEST['mnuSize'];
	$colourID = $_REQUEST['mnuColor'];
	$qty = $_REQUEST['qty'];
	#load product by productID
	$product = $productDetails->getItemProperty($productID,$sizeID,$colourID);
	$variationID = $productDetails->getProperty('variationID');
	$stock = $productDetails->getProperty('stock');
	$price = $productDetails->getProperty('price');
	
	#check quantity against stock level
	if($stock != 0 && $stock >= $qty)
	{
		#stock is not zero and greater than or equal to quantity selected
		#update stock level
		$newStockValue = $stock - $qty;
		$productDetails->setProperty('stock',$newStockValue);
		$updateStockLevel = $productDetails->updateStockLevel($variationID);
		if($updateStockLevel)
		{
			#add to cart and direct to cart page
			header("location:".__BASE_URL__."users/cart.php?variationID=".$variationID."&qty=".$qty);
		}
		else
		{
			#update stock level return false, it failed in short
			$feedback = '<span style="color:#f00">An unexpected error occured while processing your request, Please try again!</span>';
		}
	}
	else
	{
		#quantity requested is greater than stock level
		$feedback = '<span style="color:#f00">We are sorry! Our stock level for this item is '.$stock.', please reduce quantity.</span>';
	}
	#echo $stock; die;
	#print_r($product);die;
}

#set product id
if(isset($_REQUEST['pid']))
{
	$productID = $_REQUEST['pid'];
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
#print_r($prodSizes);die;
#close connection to database because they say its best practice and we have to
include_once("include/db.close.inc.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Welcome to Isherwood Athletics</title>
        <link href="css/style.css" rel="stylesheet" type="text/css" />
         <script language="javascript" type="text/javascript">
		
			function validateUpdate()
			{
				
				var obj = null;
				var errorMsg = "";
				var error = false;
											
				//quantity must be provided
				obj = document.getElementById("qty");
				if(obj.value == "")
				{
					if (errorMsg != "") errorMsg = errorMsg + "<br />";
					errorMsg = errorMsg + "<span style='color:#f00'>Please specify quantity!</span>";
					error = true; 
				}	
				
				//get form status			
				if(error == true)
				{
					var obj = document.getElementById("formstatus");
					obj.innerHTML = errorMsg;
					obj.style.display = "";
					
					var obj = document.getElementById("btnCart");
					obj.disabled = false;
					
					return false;
				}
				return true;
			}
					
        </script>
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
                Product Details
            </h1>
            <p>&nbsp;</p>
			<?php
                if(!empty($product))
                {
            ?>
           		
                <div class="products_prv">
                    <div class="products_prv_img">
                    	<?php
							$imageURL = $product[0]['productPicture'];
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
                    </div>
                    <div class="products_prv_title"><?php echo $product[0]['productName'] ; ?></div>
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
                        <!--- get the input needed from user and include in the form-->
                        <form id="frmAddCart" name="frmAddCart" method="post" action="" onsubmit="return validateUpdate();">
                        <tr>
                            <td width="153">Product Size(s)</td>
                            <td width="321">
                                <select  name="mnuSize" id="mnuSize">
                                <?php
									foreach($allSizes as $key=>$value)
									{
										if(in_array($value['sizeID'],$prodSizes))
										{
								?>
                                	<option value="<?php echo $value['sizeID']; ?>">
                                    <?php echo $value['sizeDescription']; ?>
                                    </option>
								<?php		
										}//end if
									}//end foreach allColours
								?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td width="153">Product Colour(s)</td>
                            <td width="321">
                                <select  name="mnuColor" id="mnuColor">
                                <?php
									foreach($allColours as $key=>$value)
									{
										if(in_array($value['colourID'],$prodColours))
										{
								?>
                                	<option value="<?php echo $value['colourID']; ?>">
                                    <?php echo $value['colourDescription']; ?>
                                    </option>
								<?php
										}//end if
									}//end foreach allColours
								?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Price</td>
                            <td>
                                <?php echo '&pound;'.number_format($product[0]['price'] +($product[0]['price'] * 0.2),2) ; ?> (VAT inclusive)
                            </td>
                        </tr>
                        <tr>
                            <td>
                                   	<label>Qty:</label><input type="text" name="qty" id="qty" size="3" />
                                    <input type="hidden" name="pid" id="pid" value="<?php echo $productID; ?>" />
                                    <input name="btnCart" type="submit" id="btnCart" value="Add to cart" />
                            </td>
                        </tr>
                        </form>
                    </table>
                    </div>
                                 
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
