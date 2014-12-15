<?php
$pageID = '7.4';
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

#load object
$productDetails = new Products();

$allCategories = array();
$allCategories = Categories::loadAllCat_filter($filterParam="",array('1'),$start="",$end="");

$allSubCategories = array();
$allSubCategories = Categories::loadAllSubCat_filter($filterParam="",array('1'),$start="",$end="");

#product creation starts here
if(isset($_REQUEST['btnSave']))
{
	/*Image upload begins here*/
	#load image object
	$image = new Image();
	
	/* SET DEFAULT VALUES FOR ARGUMENTS TO BE PASSED BY REFERENCE */
	$documentPaths = array();
	$destFolder = __ROOT__."images/products";
	$uploadMsg = "";
	$errStr = "";
	
	$imgRes = $image->uploadImage("filePicture",$documentPaths,$destFolder,$uploadMsg,$errStr);
	if($imgRes)
	{
		$imageURL = $image->getProperty('documentPaths');
	}
	else
	{
		$feedback = "An error occurred while uploading picture!";
	}
		
	
	#get form data
	$productName = $_POST['txtProductName'];
	$productDescription = $_POST['txtProductDesc'];
	$productCat = $_POST['mnuCatID'];
	$productSubCat = $_POST['mnuSubCatID'];
		
	#set object property
	$productDetails->setProperty('productName',$productName);
	$productDetails->setProperty('productDescription',$productDescription);
	$productDetails->setProperty('catID',$productCat);
	$productDetails->setProperty('subCatID',$productSubCat);
	$productDetails->setProperty('productPicture',$imageURL);
		
	#save to database
	$result = $productDetails->saveProduct();
	
	if(isset($result) && !empty($result))
	{
		#save successful and redirect to index page
		$msg = "Product added successfully!";
		$msg_type = "success";
		header("location:".__BASE_URL__."admin/products/index.php?notifMsg=".base64_encode($msg)."&notifType=".base64_encode($msg_type));
	}
	else
	{
		#save was NOT successful and redirect to index page
		$msg = "An error occured while trying to add Product!";
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
                    Add New Product
                </h1>
                <p>&nbsp;</p>
                <div style="margin:0px">
                    <div style="padding:10px;">
                    <form id="frmAdd" name="frmAdd" method="post" action=""
                    enctype="multipart/form-data" onsubmit="return validateUpdate();">
                        <table>
                        <tr>
                        <td>Product Name</td>
                        <td><input name="txtProductName" type="text" id="txtProductName" 
                        value="" /><span style="color:#F00">*</span></td>
                        </tr>
                        <tr>
                       	<td>Product Description</td>
                        <td><textarea name="txtProductDesc" id="txtProductDesc"></textarea></td>
                        </tr>
                        <tr>
                        <td>Product Category</td>
                        <td>
                        <select name="mnuCatID" id="mnuCatID">
                        <option value="">Select Category</option>
                        <?php
							foreach($allCategories as $key=>$value)
							{
						?>
                        	<option value="<?php echo $value['catID'] ;?>"><?php echo $value['catDescription'] ;?></option>
						<?php
							}
                        ?>
                        </select><span style="color:#F00">*</span></td>
                        </tr>
                        <tr>
                        <td>Product Sub Category</td>
                        <td>
                        <select name="mnuSubCatID" id="mnuSubCatID">
                        <option value="">Select Sub Category</option>
                        <?php
							foreach($allSubCategories as $key=>$value)
							{
						?>
                        	<option value="<?php echo $value['subCatID'] ;?>"><?php echo $value['subCatDescription'] ;?></option>
						<?php
							}
                        ?>
                        </select><span style="color:#F00">*</span>
                        </td>
                        </tr>
                        <tr><td>Upload Picture:</td>
                        <td><input type="file" id="filePicture" name="filePicture[]" /><span style="font-size:12px; margin-left:-60px">(Max picture size is 3MB)</span></td>
                        </tr>
                        <tr><td>&nbsp;</td>
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
