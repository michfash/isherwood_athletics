<?php
$pageID = '5.2';
//load DB and App Config
require_once("../../include/db.connect.inc.php");
require_once("../../include/app.config.inc.php");
?>
<?php
require_once(__ROOT__."library/users.class.php");

require_once(__ROOT__."library/colours.class.php");

session_start();
//authenticate user
require_once(__ROOT__."include/auth.check.inc.php");

//check if user is admin
require_once(__ROOT__."include/admin.check.inc.php");

#load object
$colour = new Colours();

#colour creation starts here
if(isset($_REQUEST['btnSave']))
{
	#get form data
	$colourDesc = $_POST['txtColourDesc'];
		
	#set object property
	$colour->setProperty('colourDescription',$colourDesc);
	
	#save to database
	$result = $colour->saveColour();
	
	if(isset($result) && !empty($result))
	{
		#save successful and redirect to index page
		$msg = "Colour added successfully!";
		$msg_type = "success";
		header("location:".__BASE_URL__."admin/colours/index.php?notifMsg=".base64_encode($msg)."&notifType=".base64_encode($msg_type));
	}
	else
	{
		#save was NOT successful and redirect to index page
		$msg = "An error occured while trying to add colour!";
		$msg_type = "warning";
		header("location:".__BASE_URL__."admin/colours/index.php?notifMsg=".base64_encode($msg)."&notifType=".base64_encode($msg_type));
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
				obj = document.getElementById("txtColourDesc");
				if(obj.value == "")
				{
					if (errorMsg != "") errorMsg = errorMsg + "<br />";
					errorMsg = errorMsg + "<span style='color:#f00'>Colour description can NOT be empty!</span>";
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
                    Add New Colour
                </h1>
                <p>&nbsp;</p>
                <div style="margin:0px">
                    <div style="padding:10px;">
                    <form id="frmAdd" name="frmAdd" method="post" action=""
                    enctype="multipart/form-data" onsubmit="return validateUpdate();">
                    <table width="486" style="font-size:12px">
                        <tr>
                            <td width="153"><strong>Colour Description</strong></td>
                            <td width="321">
                            	<input name="txtColourDesc" type="text" id="txtColourDesc" 
                                value="<?php echo $colour->getProperty('colourDescription') ;?>" /><span style="color:#F00">*</span>
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
