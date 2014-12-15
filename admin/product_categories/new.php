<?php
$pageID = '5.2';
//load DB and App Config
require_once("../../include/db.connect.inc.php");
require_once("../../include/app.config.inc.php");
?>
<?php
require_once(__ROOT__."library/users.class.php");

require_once(__ROOT__."library/categories.class.php");
require_once(__ROOT__."library/image.class.php");

session_start();
//authenticate user
require_once(__ROOT__."include/auth.check.inc.php");

//check if user is admin
require_once(__ROOT__."include/admin.check.inc.php");

#load object
$category = new Categories();

#colour creation starts here
if(isset($_REQUEST['btnSave']))
{
	/*Image upload begins here*/
	#load image object
	$image = new Image();
	
	/* SET DEFAULT VALUES FOR ARGUMENTS TO BE PASSED BY REFERENCE */
	$documentPaths = array();
	$destFolder = __ROOT__."images/categories";
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
	$catDesc = $_POST['txtCatDesc'];
		
	#set object property
	$category->setProperty('catDescription',$catDesc);
	$category->setProperty('categoryPicture',$imageURL);
	
	#save to database
	$result = $category->saveCategory();
	
	if(isset($result) && !empty($result))
	{
		#save successful and redirect to index page
		$msg = "Category added successfully!";
		$msg_type = "success";
		header("location:".__BASE_URL__."admin/product_categories/index.php?notifMsg=".base64_encode($msg)."&notifType=".base64_encode($msg_type));
	}
	else
	{
		#save was NOT successful and redirect to index page
		$msg = "An error occured while trying to add Category!";
		$msg_type = "warning";
		header("location:".__BASE_URL__."admin/product_categories/index.php?notifMsg=".base64_encode($msg)."&notifType=".base64_encode($msg_type));
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
											
				//size description must be provided
				obj = document.getElementById("txtCatDesc");
				if(obj.value == "")
				{
					if (errorMsg != "") errorMsg = errorMsg + "<br />";
					errorMsg = errorMsg + "<span style='color:#f00'>Category description can NOT be empty!</span>";
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
                    Add New Product Category
                </h1>
                <p>&nbsp;</p>
                <div style="margin:0px">
                    <div style="padding:10px;">
                    <form id="frmAdd" name="frmAdd" method="post" action=""
                    enctype="multipart/form-data" onsubmit="return validateUpdate();">
                        <strong>Category Description</strong>
                        <input name="txtCatDesc" type="text" id="txtCatDesc" 
                        value="<?php echo $category->getProperty('catDescription') ;?>" /><span style="color:#F00">*</span><br />
                        <label style="font-size:12px">Upload Picture:</label>
                        <input type="file" id="filePicture" name="filePicture[]" />
                        <span style="font-size:12px; margin-left:-60px">(Max picture size is 3MB)</span><br />
                        <input name="btnSave" type="submit" id="btnSave" value="Save" />
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
