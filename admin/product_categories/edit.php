<?php
$pageID = '5.1';
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

if(isset($_REQUEST['catID']))
{
	$catID = $_REQUEST['catID'];
}
else
{
	#redirect to index
	$feedback = "We are sorry! An unexpected error occured.";
	header("location:".__BASE_URL__."admin/product_categories/index.php?notifMsg=".base64_encode($feedback)."&notifType=".base64_encode("warning"));
}

#load object
$catDetails = new Categories();

#load category by catID
$category = $catDetails->loadCat($catID);

#category edit starts here
if(isset($_REQUEST['btnEdit']))
{
	/*Image upload begins here*/
	#load image object
	$image = new Image();
	
	/* SET DEFAULT VALUES FOR ARGUMENTS TO BE PASSED BY REFERENCE */
	$documentPaths = array();
	$destFolder = __ROOT__."images/categories";
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
	$catDesc = $_POST['txtCatDesc'];
		
	#set object property
	$catDetails->setProperty('catDescription',$catDesc);
	$catDetails->setProperty('categoryPicture',$imageURL);
	
	#update table
	$result = $catDetails->update();
	
	if(isset($result) && !empty($result))
	{
		#update successful and redirect to index page
		$msg = "Category edit was Successful!";
		$msg_type = "success";
		header("location:".__BASE_URL__."admin/product_categories/index.php?notifMsg=".base64_encode($msg)."&notifType=".base64_encode($msg_type));
	}
	else
	{
		#update was NOT successful and redirect to index page
		$msg = "Category edit was NOT Successful!";
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
											
				//category description must be provided
				obj = document.getElementById("txtCatDesc");
				if(obj.value == "")
				{
					if (errorMsg != "") errorMsg = errorMsg + "<br />";
					errorMsg = errorMsg + "<span style='color:#f00'>Size description can NOT be empty!</span>";
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
                    Edit Size
                </h1>
                <p>&nbsp;</p>
                
                <div style="clear:both;float:right;font-size:18px;margin-top:-25px">
                    <a href="<?php echo __BASE_URL__ ;?>admin/product_categories/new.php">Add New</a>
                </div>
                <div style="margin:0px">
                    <div style="padding:10px;">
                    <form id="frmEdit" name="frmEdit" method="post" action=""
                    enctype="multipart/form-data" onsubmit="return validateUpdate();">
                          <label style="font-size:12px">Category Picture:</label>
                          <span style="font-size:12px;">(Max picture size is 3MB)</span>
                          <br />
                            <div style="float:left">
                             <?php
							 	$imageURL = $catDetails->getProperty('categoryPicture');
                                if( isset($imageURL) && !empty($imageURL) )
                                {
                             ?>
                            <img src="<?php echo __BASE_URL__ ; ?>images/categories/<?php echo $imageURL ;?>" width="100px">
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
                        <input type="hidden" id="txtPicURL" name="txtPicURL" value="<?php echo $catDetails->getProperty('categoryPicture') ;?>" />
                        <input type="file" id="filePicture" name="filePicture[]" onchange="document.getElementById('rdBtn_New').checked = true;" />
                        </div>
                        <div style="clear:both">&nbsp;</div>
                        <strong>Category Description</strong>
                        <input name="txtCatDesc" type="text" id="txtCatDesc" 
                        value="<?php echo $catDetails->getProperty('catDescription') ;?>" /><span style="color:#F00">*</span><br />
                        <input name="btnEdit" type="submit" id="btnEdit" value="Edit" />
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
