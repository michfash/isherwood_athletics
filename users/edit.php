<?php
$pageID = '4.3';
//load DB and App Config
require_once("../include/db.connect.inc.php");
require_once("../include/app.config.inc.php");
?>
<?php
require_once(__ROOT__."library/users.class.php");

if(isset($_REQUEST['uID']))
{
	$userID = $_REQUEST['uID'];
}
else
{
	#redirect to index
	$feedback = "We are sorry! An unexpected error occured.";
	header("location:".__BASE_URL__."index.php?notifMsg=".base64_encode($feedback)."&notifType=".base64_encode("warning"));
}

#load object
$userDetails = new Users();

#load user by userID
$user = $userDetails->load($userID);

#profile edit starts here
if(isset($_REQUEST['btnEdit']))
{
	#get form data
	$firstName = $_POST['txtFirstName'];
	$lastName = strtoupper($_POST['txtLastName']);
	$phone = $_POST['txtPhone'];
	$address = $_POST['txtAddress'];
	$email = $_POST['txtEmail'];
	
	$updatedBy = $userDetails->getProperty('userID');
	
	#set object property
	$userDetails->setProperty('firstName',$firstName);
	$userDetails->setProperty('lastName',$lastName);
	$userDetails->setProperty('phone',$phone);
	$userDetails->setProperty('address',$address);
	$userDetails->setProperty('email',$email);
	$userDetails->setProperty('updatedBy',$updatedBy);
	
	#update table
	$result = $userDetails->update();
	
	if(isset($result) && !empty($result))
	{
		#update successful and redirect to profile page
		$msg = "Profile edit was Successful!";
		$msg_type = "success";
		header("location:".__BASE_URL__."users/profile.php?uID=".$userID."&notifMsg=".base64_encode($msg)."&notifType=".base64_encode($msg_type));
	}
	else
	{
		#update was NOT successful and redirect to profile page
		$msg = "Profile edit was NOT Successful!";
		$msg_type = "warning";
		header("location:".__BASE_URL__."users/profile.php?uID=".$userID."&notifMsg=".base64_encode($msg)."&notifType=".base64_encode($msg_type));
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
											
				//firstname must be provided
				obj = document.getElementById("txtFirstName");
				if(obj.value == "")
				{
					if (errorMsg != "") errorMsg = errorMsg + "<br />";
					errorMsg = errorMsg + "<span style='color:#f00'>Please enter your First Name!</span>";
					error = true; 
				}	
				
				//lastname must be provided
				obj = document.getElementById("txtLastName");
				if(obj.value == "")
				{
					if (errorMsg != "") errorMsg = errorMsg + "<br />";
					errorMsg = errorMsg + "<span style='color:#f00'>Please enter your Last Name!</span>";
					error = true; 
				}
														
				//Address line value empty if not set
				obj = document.getElementById("txtAddress");
				if(obj.value == "")
				{
					if (errorMsg != "") errorMsg = errorMsg + "<br />";
					errorMsg = errorMsg + "<span style='color:#f00'>Please enter your address! This is used for item delivery.</span>";
					error = true; 
				}			
				
				//email must be selected
				obj = document.getElementById("txtEmail");
				if(obj.value == "")
				{
					if (errorMsg != "") errorMsg = errorMsg + "<br />";
					errorMsg = errorMsg + "<span style='color:#f00'>Please enter your email address! This is used for account login.</span>";
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
        
        <!-- Email and password validator starts here  -->
		<script>
        function checkMail(frmEdit,txtEmail){
           var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
           var address = document.forms[frmEdit].elements[txtEmail].value;
           if(reg.test(address) == false) {
              alert('Invalid Email Address (you must supply a valid Email)');
              document.getElementById('txtEmail').value="<?php echo $userDetails->getProperty('email') ;?>";
              return false;
           }
        }
        function validateEmail(){
            checkMail('frmEdit','txtEmail');
        }
        function validatePassword(){
			var pass=document.getElementById('txtPassword').value;
			if(pass.length<6){
								alert('Password should be of at least 6 characters');
								document.getElementById('txtPassword').value="";
								return false;
        					}
        }
        </script>
        <!-- Email and password validator ends here  -->
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
                    Edit Profile
                </h1>
                <p>&nbsp;</p>
                <div style="margin:0px">
                    <div style="padding:10px;">
                    <form id="frmEdit" name="frmEdit" method="post" action=""
                    enctype="multipart/form-data" onsubmit="return validateUpdate();">
                    <table width="486" style="font-size:12px">
                        <tr>
                            <td width="153"><strong>First Name</strong></td>
                            <td width="321">
                            	<input name="txtFirstName" type="text" id="txtFirstName" 
                                value="<?php echo $userDetails->getProperty('firstName') ;?>" /><span style="color:#F00">*</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="153"><strong>Last Name</strong></td>
                            <td width="321">
                               <input name="txtLastName" type="text" id="txtLastName" 
                               value="<?php echo $userDetails->getProperty('lastName') ;?>" /><span style="color:#F00">*</span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Phone</strong></td>
                            <td>
                                <input name="txtPhone" type="text" id="txtPhone" 
                                value="<?php echo $userDetails->getProperty('phone') ;?>" />
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Address</strong></td>
                            <td>
                                <input name="txtAddress" type="text" id="txtAddress" 
                                value="<?php echo $userDetails->getProperty('address') ;?>" /><span style="color:#F00">*</span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Email</strong></td>
                            <td>
                                <input name="txtEmail" type="text" id="txtEmail" 
                                value="<?php echo $userDetails->getProperty('email') ;?>" onblur="javascript:return validateEmail();" />
                                <span style="color:#F00">*</span>
                            </td>
                        </tr>
                        <tr>
                        	<td>&nbsp;</td>
                        	<td><input name="btnEdit" type="submit" id="btnEdit" value="Edit" /></td>
                        </tr>
                    </table>
                    </form>
                    </div>
                    <div style="clear:both;margin:20px;font-size:12px">
                        <a href="<?php echo __BASE_URL__ ;?>users/password_change.php?uID=<?php echo $userDetails->getProperty('userID') ; ?>">
                        &raquo; Change Password</a>
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
