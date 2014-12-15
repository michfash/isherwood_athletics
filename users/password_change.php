<?php
$pageID = "4.4";
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
if(isset($_POST['btnChange']))
{
	if(isset($_POST['txtNewPassword']) && !empty($_POST['txtNewPassword']))
	{
		#get form data
		$oldPassword = $_POST['txtOldPassword'];
		$newPassword = $_POST['txtNewPassword'];
		
		#validate old password
		$valPassword = md5($oldPassword);
		if(!empty($oldPassword) && $valPassword == $userDetails->getProperty('password'))
		{
			$updatedBy = $userDetails->getProperty('userID');
		
			#set object property
			$userDetails->setProperty('password',$newPassword);
			$userDetails->setProperty('updatedBy',$updatedBy);
			
			#update table
			$result = $userDetails->update();
			
			if($result)
			{
				#update successful and redirect to profile page
				$msg = "Password change was Successful!";
				$msg_type = "success";
				header("location:".__BASE_URL__."users/profile.php?uID=".$userID."&notifMsg=".base64_encode($msg)."&notifType=".base64_encode($msg_type));
			}
			else
			{
				#update was NOT successful and redirect to profile page
				$msg = "Password change was NOT Successful!";
				$msg_type = "warning";
				header("location:".__BASE_URL__."users/profile.php?uID=".$userID."&notifMsg=".base64_encode($msg)."&notifType=".base64_encode($msg_type));
			}
		}
		else
		{
			#old password invalid
			$feedback = '<span style="color:#F00">We are sorry! Old password is not the same as what we haave on record, please re-try.</span>';
		}
	}
	else
	{
		$feedback = '<span style="color:#F00">Please enter new password if you wish to change password!</span>';
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
														
				//password must be selected
				obj = document.getElementById("txtOldPassword");
				if(obj.value == "")
				{
					if (errorMsg != "") errorMsg = errorMsg + "<br />";
					errorMsg = errorMsg + "<span style='color:#f00'>Please supply a password at least 6 characters long !</span>";
					error = true; 
				}									
				//password must be selected
				obj = document.getElementById("txtNewPassword");
				if(obj.value == "")
				{
					if (errorMsg != "") errorMsg = errorMsg + "<br />";
					errorMsg = errorMsg + "<span style='color:#f00'>Please supply a password at least 6 characters long !</span>";
					error = true; 
				}
								
				//get form status			
				if(error == true)
				{
					var obj = document.getElementById("formstatus");
					obj.innerHTML = errorMsg;
					obj.style.display = "";
					
					var obj = document.getElementById("btnChange");
					obj.disabled = false;
					
					return false;
				}
				return true;
			}
					
        </script>
		<!-- Validates compulsory fields in form ends here -->
        
        <!-- Email and password validator starts here  -->
		<script>
        function checkMail(frmPasswordChange,txtEmail){
           var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
           var address = document.forms[frmPasswordChange].elements[txtEmail].value;
           if(reg.test(address) == false) {
              alert('Invalid Email Address (you must supply a valid Email)');
              document.getElementById('txtEmail').value="";
              return false;
           }
        }
        function validateEmail(){
            checkMail('frmPasswordChange','txtEmail');
        }
        function validateOldPassword(){
			var pass=document.getElementById('txtOldPassword').value;
			if(pass.length<6){
								alert('Password should be of at least 6 characters');
								document.getElementById('txtOldPassword').value="";
								return false;
        					}
        }
        function validateNewPassword(){
			var pass=document.getElementById('txtNewPassword').value;
			if(pass.length<6){
								alert('Password should be of at least 6 characters');
								document.getElementById('txtNewPassword').value="";
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
                    <form id="frmPasswordChange" name="frmPasswordChange" method="post" action=""
                    enctype="multipart/form-data" onsubmit="return validateUpdate();">
                    <table width="486" style="font-size:12px">
                        <tr>
                            <td><strong>Old Password</strong></td>
                            <td>
                                <input name="txtOldPassword" type="password" id="txtOldPassword" 
                                value="" onblur="javascript:return validateOldPassword();" />
                                <span style="color:#F00">*</span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>New Password</strong></td>
                            <td>
                                <input name="txtNewPassword" type="password" id="txtNewPassword" 
                                value="" onblur="javascript:return validateNewPassword();" />
                                <span style="color:#F00">*</span>
                            </td>
                        </tr>
                        <tr>
                        	<td>&nbsp;</td>
                        	<td><input name="btnChange" type="submit" id="btnChange" value="Change" /></td>
                        </tr>
                    </table>
                    </form>
                    </div>
                    <div style="clear:both;margin:20px;font-size:12px">
                        <a href="<?php echo __BASE_URL__ ;?>users/edit.php?uID=<?php echo $userDetails->getProperty('userID') ; ?>">
                        &raquo; Edit profile</a>
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
