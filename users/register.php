<?php
$pageID = '4.1';
//load DB and App Config
require_once("../include/db.connect.inc.php");
require_once("../include/app.config.inc.php");
?>
<?php
require_once(__ROOT__."library/users.class.php");

if(isset($_REQUEST['btnRegister']))
{
	#new user registration begins here
	$user = new Users();
	
	#Get form data
	$user->setProperty("firstName",$_POST['txtFirstName']);
	$user->setProperty("lastName",strtoupper($_POST['txtLastName']));
	$user->setProperty("phone",$_POST['txtPhone']);
	$user->setProperty("address",$_POST['txtAddress']);
	$user->setProperty("email",$_POST['txtEmail']);
	$user->setProperty("password",$_POST['txtPassword']);	
	
	#set user role
	$user->setProperty("role","1");
	
	#store details in database
	$result = $user->save();
	
	#test success
	if(!is_array($result))
	{
		//new user registration was successful and redirect to success page
		#load user by ID
		$email = $user->getProperty('email');
		$plainPassword = $user->getProperty('password');
		
		//echo 'email beni=>'.$email;
//		echo '<br> Password beni=>'.$plainPassword;
		$authUser = $user->checkAuth($email,$password);
//		if($authUser)
//		{
//			$authSecond = $user->getProperty("isAuthenticated");
//			echo '<br>passed auth';
//		}
//		else
//		{
//			echo '<br>Failed auth';
//		}
//		die;
		#$loadUser = $user->load($result);
		if($authUser)
		{
			#start a session for object
			session_start();
			$_SESSION['isherwood_athletics']['userHandle'] = serialize($user);
			#redirect to success page
			$feedback = "Your registration was successfull!";
			header("location:".__BASE_URL__."index.php?notifMsg=".base64_encode($feedback)."&notifType=".base64_encode("success"));
		}
		else
		{
			//Error Occured.		
			$feedback = "An unexpected error occured, please login!";
			$feedback .= "<em>".$result['errMsg']."</em>";
			header("location:".__BASE_URL__."users/login.php?notifMsg=".base64_encode($feedback)."&notifType=".base64_encode("error"));
		}
	}
	else
	{
		//Error Occured.		
		$feedback = "Your registration was NOT successful!";
		$feedback .= "<em>".$result['errMsg']."</em>";
		header("location:".__BASE_URL__."users/register.php?notifMsg=".base64_encode($feedback)."&notifType=".base64_encode("error"));
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
									
				//password must be selected
				obj = document.getElementById("txtPassword");
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
					
					var obj = document.getElementById("btnRegister");
					obj.disabled = false;
					
					return false;
				}
				return true;
			}
					
        </script>
		<!-- Validates compulsory fields in form ends here -->
        
        <!-- Email and password validator starts here  -->
		<script>
        function checkMail(frmRegister,txtEmail){
           var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
           var address = document.forms[frmRegister].elements[txtEmail].value;
           if(reg.test(address) == false) {
              alert('Invalid Email Address (you must supply a valid Email)');
              document.getElementById('txtEmail').value="";
              return false;
           }
        }
        function validateEmail(){
            checkMail('frmRegister','txtEmail');
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
                    Registration Form
                </h1>
                <p>&nbsp;</p>
                <div>Fill in the form below to register for your Isherwood Athletic account</div>
                <p>&nbsp;</p>
                <div>
                    <form id="frmRegister" name="frmRegister" method="post" action=""
                    enctype="multipart/form-data" onsubmit="return validateUpdate();">
                    <table>
                    	<tr>
                    		<td><label>First Name: </label></td>
                       		<td><input name="txtFirstName" type="text" id="txtFirstName" value="" /></td>
                            <td><span style="color:#F00">*</span></td>
                        </tr>
                    	<tr>
                    		<td><label>Last Name: </label></td>
                       		<td><input name="txtLastName" type="text" id="txtLastName" value="" /></td>
                            <td><span style="color:#F00">*</span></td>
                        </tr>
                    	<tr>
                    		<td><label>Phone: </label></td>
                       		<td><input name="txtPhone" type="text" id="txtPhone" value="" /></td>
                        </tr>
                    	<tr>
                    		<td><label>Address: </label></td>
                       		<td><input name="txtAddress" type="text" id="txtAddress" value="" /></td>
                            <td><span style="color:#F00">*</span></td>
                        </tr>
                    	<tr>
                    		<td><label>Email: </label></td>
                       		<td><input name="txtEmail" type="text" id="txtEmail" value=""
                            onblur="javascript:return validateEmail();" /></td>
                            <td><span style="color:#F00">*</span></td>
                        </tr>
                    	<tr>
                        	<td><label>Password: </label></td>
                        	<td><input name="txtPassword" type="password" id="txtPassword" value="" 
                            onblur="javascript:return validatePassword();" /></td>
                            <td><span style="color:#F00">*</span></td>
                        </tr>
                        <tr>
                        	<td>&nbsp;</td>
                        	<td><input name="btnRegister" type="submit" id="btnRegister" value="Register" /></td>
                        </tr>
                    </table>
                    </form>
                </div>
           </div>                     
          </div>
            
            <!-- Footer starts here -->
            	<?php include_once(__ROOT__."include/footer.inc.php") ; ?>
            <!-- Footer ends here -->
          </div>
                
    </body>
    
</html>
