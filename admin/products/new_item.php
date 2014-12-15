<?php
$pageID = '7.4';
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

if(isset($_REQUEST['productID']))
{
	$productID = $_REQUEST['productID'];
}
else
{
	#redirect to index
	$feedback = "We are sorry! An unexpected error occured.";
	header("location:".__BASE_URL__."admin/products/index.php?notifMsg=".base64_encode($feedback)."&notifType=".base64_encode("warning"));
}

#load object
$productDetails = new Products();

#load product by productID
$product = $productDetails->load($productID);

#load all sizes
$allSizes = array();
$allSizes = Sizes::loadAll_filter($filterParam="",array('1'),$start="",$end="");

#load all colours
$allColours = array();
$allColours = Colours::loadAll_filter($filterParam="",array('1'),$start="",$end="");


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

#item save starts here
if(isset($_REQUEST['btnSave']))
{
	#get form data
	$sizeID = $_POST['mnuSize'];
	$colourID = $_POST['mnuColour'];
	$stock = $_POST['txtStock'];
	$price = $_POST['txtPrice'];
		
	#set object property
	$productDetails->setProperty('sizeID',$sizeID);
	$productDetails->setProperty('colourID',$colourID);
	$productDetails->setProperty('stock',$stock);
	$productDetails->setProperty('price',$price);
	
	#update table
	$result = $productDetails->saveVariation($productID);
	
	if(isset($result) && !empty($result))
	{
		#save successful and redirect to index page
		$msg = "Item added Successful!";
		$msg_type = "success";
		header("location:".__BASE_URL__."admin/products/index.php?notifMsg=".base64_encode($msg)."&notifType=".base64_encode($msg_type));
	}
	else
	{
		#save was NOT successful and redirect to index page
		$msg = "Item was NOT added!";
		$msg_type = "warning";
		header("location:".__BASE_URL__."admin/products/index.php?notifMsg=".base64_encode($msg)."&notifType=".base64_encode($msg_type));
	}
	
}

#close connection to database because they say is best practice
include_once(__ROOT__."include/db.close.inc.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Welcome to Isherwood Athletics</title>
        <link href="<?php echo __BASE_URL__ ;?>css/style.css" rel="stylesheet" type="text/css" />
        <!-- Validates compulsory fields in form -->
        <script language="javascript" type="text/javascript">
		
			function validateUpdate()
			{
				
				var obj = null;
				var errorMsg = "";
				var error = false;
											
				//size cannot be empty
				obj = document.getElementById("mnuSize");
				if(obj.value == "")
				{
					if (errorMsg != "") errorMsg = errorMsg + "<br />";
					errorMsg = errorMsg + "<span style='color:#f00'>Please select a size!</span>";
					error = true; 
				}	
											
				//colour cannot be empty
				obj = document.getElementById("mnuColour");
				if(obj.value == "")
				{
					if (errorMsg != "") errorMsg = errorMsg + "<br />";
					errorMsg = errorMsg + "<span style='color:#f00'>Please select a colour!</span>";
					error = true; 
				}	
											
				//stock cannot be empty
				obj = document.getElementById("txtStock");
				if(obj.value == "")
				{
					if (errorMsg != "") errorMsg = errorMsg + "<br />";
					errorMsg = errorMsg + "<span style='color:#f00'>Stock can NOT be empty!</span>";
					error = true; 
				}	
										
				//price cannot be empty
				obj = document.getElementById("txtPrice");
				if(obj.value == "")
				{
					if (errorMsg != "") errorMsg = errorMsg + "<br />";
					errorMsg = errorMsg + "<span style='color:#f00'>Price can NOT be empty!</span>";
					error = true; 
				}	
				
				//get form status			
				if(error == true)
				{
					var obj = document.getElementById("formstatus");
					obj.innerHTML = errorMsg;
					obj.style.display = "";
					
					var obj = document.getElementById("btnSave");
					obj.disabled = false;
					
					return false;
				}
				return true;
			}
					
        </script>
		<!-- Validates compulsory fields in form ends here -->
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
                    New Product Item
                </h1>
                <p>&nbsp;</p>
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
                <div style="margin:0px">
                    <div style="padding:10px;">
                    <form id="frmSave" name="frmSave" method="post" action=""
                    enctype="multipart/form-data" onsubmit="return validateUpdate();">
                    <table width="486" style="font-size:12px">
                        <tr>
                            <td width="153"><strong>Size</strong></td>
                            <td width="321">
                            	<select name="mnuSize" id="mnuSize">
                                <option value="">Select Size</option>
                                <?php
									foreach($allSizes as $key=>$value)
									{
								?>
                                	<option value="<?php echo $value['sizeID'] ;?>"><?php echo $value['sizeDescription'] ;?></option>
                                 <?php
									}
								 ?>
                                </select>
                                <span style="color:#F00">*</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="153"><strong>Colour</strong></td>
                            <td width="321">
                            	<select name="mnuColour" id="mnuColour">
                                <option value="">Select Colour</option>
                                	<?php
									foreach($allColours as $key=>$value)
									{
								?>
                                	<option value="<?php echo $value['colourID'] ;?>"><?php echo $value['colourDescription'] ;?></option>
                                 <?php
									}
								 ?>
                                </select>
                                <span style="color:#F00">*</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="153"><strong>Stock</strong></td>
                            <td width="321">
                            	<input name="txtStock" type="text" id="txtStock" 
                                value="" size="6" /><span style="color:#F00">*</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="153"><strong>Price</strong></td>
                            <td width="321">
                            	<input name="txtPrice" type="text" id="txtPrice" 
                                value="" size="6" /><span style="color:#F00">*</span>
                            </td>
                        </tr>
                        <tr>
                        	<td>&nbsp;</td>
                        	<td><input name="btnSave" type="submit" id="btnSave" value="Save" /></td>
                        </tr>
                    </table>
                    </form>
                    </div>
                 </div>
           </div>                     
          </div>
            
            <!-- Footer starts here -->
            	<?php include_once(__ROOT__."include/footer.inc.php") ; ?>
            <!-- Footer ends here -->
          </div>
                
    </body>
    
</html>
