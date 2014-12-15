<?php
$pageID = '5.1';
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

if(isset($_REQUEST['sID']))
{
	$sizeID = $_REQUEST['sID'];
}
else
{
	#redirect to index
	$feedback = "We are sorry! An unexpected error occured.";
	header("location:".__BASE_URL__."admin/sizes/index.php?notifMsg=".base64_encode($feedback)."&notifType=".base64_encode("warning"));
}

#load object
$sizeDetails = new Sizes();

#load size by sizeID
$size = $sizeDetails->load($sizeID);

#colour edit starts here
if(isset($_REQUEST['btnEdit']))
{
	#get form data
	$sizeDesc = $_POST['txtSizeDesc'];
		
	#set object property
	$sizeDetails->setProperty('sizeDescription',$sizeDesc);
	
	#update table
	$result = $sizeDetails->update();
	
	if(isset($result) && !empty($result))
	{
		#update successful and redirect to index page
		$msg = "Size edit was Successful!";
		$msg_type = "success";
		header("location:".__BASE_URL__."admin/sizes/index.php?notifMsg=".base64_encode($msg)."&notifType=".base64_encode($msg_type));
	}
	else
	{
		#update was NOT successful and redirect to index page
		$msg = "Size edit was NOT Successful!";
		$msg_type = "warning";
		header("location:".__BASE_URL__."admin/sizes/index.php?notifMsg=".base64_encode($msg)."&notifType=".base64_encode($msg_type));
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
											
				//colour description must be provided
				obj = document.getElementById("txtSizeDesc");
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
                    <a href="<?php echo __BASE_URL__ ;?>admin/sizes/new.php">Add New</a>
                </div>
                <div style="margin:0px">
                    <div style="padding:10px;">
                    <form id="frmEdit" name="frmEdit" method="post" action=""
                    enctype="multipart/form-data" onsubmit="return validateUpdate();">
                    <table width="486" style="font-size:12px">
                        <tr>
                            <td width="153"><strong>Size Description</strong></td>
                            <td width="321">
                            	<input name="txtSizeDesc" type="text" id="txtSizerDesc" 
                                value="<?php echo $sizeDetails->getProperty('sizeDescription') ;?>" /><span style="color:#F00">*</span>
                            </td>
                        </tr>
                        <tr>
                        	<td>&nbsp;</td>
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
