<?php
$pageID = '7.1';
//load DB and App Config
require_once("../../include/db.connect.inc.php");
require_once("../../include/app.config.inc.php");
?>
<?php
require_once(__ROOT__."library/users.class.php");

require_once(__ROOT__."library/products.class.php");
require_once(__ROOT__."library/categories.class.php");
require_once(__ROOT__."library/image.class.php");

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

$allCategories = array();
$allCategories = Categories::loadAllCat_filter($filterParam="",array('1'),$start="",$end="");

$allSubCategories = array();
$allSubCategories = Categories::loadAllSubCat_filter($filterParam="",array('1'),$start="",$end="");

#product edit starts here
if(isset($_REQUEST['btnEdit']))
{
	/*Image upload begins here*/
	#load image object
	$image = new Image();
	
	/* SET DEFAULT VALUES FOR ARGUMENTS TO BE PASSED BY REFERENCE */
	$documentPaths = array();
	$destFolder = __ROOT__."images/products";
	$uploadMsg = "";
	$errStr = "";
			
	if($_POST['rdBtn_update_choice'] == "new")
	{
		$image->uploadImage("filePicture",$documentPaths,$destFolder,$uploadMsg,$errStr);
		
		$imageURL = $image->getProperty('documentPaths');
	}
	elseif($_POST['rdBtn_update_choice'] == "keep")
	{
		$imageURL = $_POST['txtPicURL'];
	}	
	
	#get form data
	$productName = $_POST['txtProductName'];
	$productDescription = $_POST['txtProductDesc'];
	$productCat = $_POST['mnuCatID'];
	$productSubCat = $_POST['mnuSubCatID'];
		
	#set object property
	$productDetails->setProperty('productID',$productID);
	$productDetails->setProperty('productName',$productName);
	$productDetails->setProperty('productDescription',$productDescription);
	$productDetails->setProperty('catID',$productCat);
	$productDetails->setProperty('subCatID',$productSubCat);
	$productDetails->setProperty('productPicture',$imageURL);
	
	#update table
	$result = $productDetails->update();
	
	if(isset($result) && !empty($result))
	{
		#update successful and redirect to index page
		$msg = "Product edit was Successful!";
		$msg_type = "success";
		header("location:".__BASE_URL__."admin/products/index.php?notifMsg=".base64_encode($msg)."&notifType=".base64_encode($msg_type));
	}
	else
	{
		#update was NOT successful and redirect to index page
		$msg = "Product edit was NOT Successful!";
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
											
				//product name must be specified
				obj = document.getElementById("txtProductName");
				if(obj.value == "")
				{
					if (errorMsg != "") errorMsg = errorMsg + "<br />";
					errorMsg = errorMsg + "<span style='color:#f00'>Product name must be entered!</span>";
					error = true; 
				}	
											
				//category must be specified
				obj = document.getElementById("mnuCatID");
				if(obj.value == "")
				{
					if (errorMsg != "") errorMsg = errorMsg + "<br />";
					errorMsg = errorMsg + "<span style='color:#f00'>Category must be specified!</span>";
					error = true; 
				}
											
				//sub category must be specified
				obj = document.getElementById("mnuSubCatID");
				if(obj.value == "")
				{
					if (errorMsg != "") errorMsg = errorMsg + "<br />";
					errorMsg = errorMsg + "<span style='color:#f00'>Sub Category must be specified!</span>";
					error = true; 
				}	
				
				//get form status			
				if(error == true)
				{
					var obj = document.getElementById("formstatus");
					obj.innerHTML = errorMsg;
					obj.style.display = "";
					
					var obj = document.getElementById("btnEdit");
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
                    Edit Product
                </h1>
                <p>&nbsp;</p>
                
                <div style="clear:both;float:right;font-size:18px;margin-top:-25px">
                    <a href="<?php echo __BASE_URL__ ;?>admin/products/new.php">Add New Product</a>
                </div>
                <div style="margin:0px">
                    <div style="padding:10px;">
                    <form id="frmEdit" name="frmEdit" method="post" action=""
                    enctype="multipart/form-data" onsubmit="return validateUpdate();">
                          <label style="font-size:12px">Product Picture:</label>
                          <span style="font-size:12px;">(Max picture size is 3MB)</span>
                          <br />
                            <div style="float:left">
                             <?php
							 	$imageURL = $product[0]['productPicture'];
                                if( isset($imageURL) && !empty($imageURL) )
                                {
                             ?>
                            <img src="<?php echo __BASE_URL__ ; ?>images/products/<?php echo $imageURL ;?>" width="100px">
                            <?php
                                }
                                else
                                {
                            ?>
                            <img src="<?php echo __BASE_URL__ ; ?>images/no-image.jpg" width="100px">
                            <?php
                                }
                            ?>
                            </div>
                        <div style="width:300px; margin-left:20px; float:left;font-size:12px">
                        <label for="rdBtn_Keep">Keep Existing</label>
                        <input name="rdBtn_update_choice" type="radio" id="rdBtn_Keep" value="keep" checked="checked" />
                        <br />
                        <label for="rdBtn_New">Upload New Image</label>
                        <input name="rdBtn_update_choice" type="radio" id="rdBtn_New" value="new" />
                        <br />
                        <input type="hidden" id="txtPicURL" name="txtPicURL" value="<?php echo $product[0]['productPicture'] ;?>" />
                        <input type="file" id="filePicture" name="filePicture[]" onchange="document.getElementById('rdBtn_New').checked = true;" />
                        </div>
                        <div style="clear:both">&nbsp;</div>
                        <table>
                        <tr>
                        <td><strong>Product Name</strong></td>
                        <td><input name="txtProductName" type="text" id="txtProductName" 
                        value="<?php echo $product[0]['productName'] ;?>" /><span style="color:#F00">*</span></td>
                        </tr>
                        <tr>
                       	<td><strong>Product Description</strong></td>
                        <td><textarea name="txtProductDesc" id="txtProductDesc"><?php echo $product[0]['productDescription'] ;?></textarea></td>
                        </tr>
                        <tr>
                        <td><strong>Product Category</strong></td>
                        <td>
                        <select name="mnuCatID" id="mnuCatID">
                        <option value="">Select Category</option>
                        <?php
							foreach($allCategories as $key=>$value)
							{
						?>
                        	<option <?php echo ($value['catID'] == $product[0]['catID'] ) ?'selected' :'' ;?> value="<?php echo $value['catID'] ;?>"><?php echo $value['catDescription'] ;?></option>
						<?php
							}
                        ?>
                        </select><span style="color:#F00">*</span></td>
                        </tr>
                        <tr>
                        <td><strong>Product Sub Category</strong></td>
                        <td>
                        <select name="mnuSubCatID" id="mnuSubCatID">
                        <option value="">Select Sub Category</option>
                        <?php
							foreach($allSubCategories as $key=>$value)
							{
						?>
                        	<option <?php echo ($value['subCatID'] == $product[0]['subCatID'] ) ?'selected' :'' ;?> value="<?php echo $value['subCatID'] ;?>"><?php echo $value['subCatDescription'] ;?></option>
						<?php
							}
                        ?>
                        </select><span style="color:#F00">*</span>
                        </td>
                        </tr>
                        <tr><td>&nbsp;</td>
                        <td><input name="btnEdit" type="submit" id="btnEdit" value="Edit" /></td>
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
