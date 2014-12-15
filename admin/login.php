<?php
$pageID = '4.2';
//load DB and App Config
require_once("../include/db.connect.inc.php");
require_once("../include/app.config.inc.php");

session_start();
require_once(__ROOT__."library/users.class.php");

#get returnURL
if(isset($_REQUEST['returnURL']) && !empty($_REQUEST['returnURL']))
{
	$uri = htmlspecialchars($_REQUEST['returnURL']);
	$isCart = strpos($uri,'total=');
	$getTotal = substr($uri,$isCart);
	if(!empty($isCart))
	{
		$total = str_replace('=','',substr($getTotal,strpos($getTotal,'=')));
		$position = strpos($uri,'?');
		$url = substr($uri,1,$position-1);
		$returnURL = str_replace('isherwood_athletics/','',$url);
		$returnURL = $returnURL.'?total='.$total;
	}
	else
	{
		$url = substr($uri,1);
		$returnURL = str_replace('isherwood_athletics/','',$url);
	}
	
}

#redirect to index page if userHandle isset and user is admin
if(isset($_SESSION['isherwood_athletics']['userHandle']))
{
	$userHandle = unserialize($_SESSION['isherwood_athletics']['userHandle']);
	if($userHandle->getProperty('role') == 0)
	{
		#redirect to index
		$feedback = "Sorry! But you are already logged in";
		header("location:".__BASE_URL__."index.php?notifMsg=".base64_encode($feedback)."&notifType=".base64_encode("warning"));
	}
}

if(isset($_REQUEST['btnLogin']))
{
	$email = $_REQUEST['txtEmail'];
	$password = $_REQUEST['txtPassword'];
	#processLogin
	$user = new Users();
	$user->checkAuth($email,$password);
	
	if($user->getProperty("isAuthenticated") && $user->getProperty("role") == 0)
	{
		#authentication passed	and user is admin
		$_SESSION['isherwood_athletics']['userHandle'] = serialize($user);
		
		$msg = "Login Successful!";
		$msg_type = "success";
		if(isset($_REQUEST['returnURL']) && $_REQUEST['returnURL'] !='')
		{
			if(isset($_REQUEST['total'])) $total=$_REQUEST['total'];
			if(!empty($total) && $total !='')
			{
				$returnURL = $_REQUEST['returnURL'];
				$returnURL .= "&notifMsg=".base64_encode($msg)."&notifType=".base64_encode($msg_type);
			}
			else
			{
				$returnURL = $_REQUEST['returnURL'];
				$returnURL .= "?notifMsg=".base64_encode($msg)."&notifType=".base64_encode($msg_type);
			}
		}
		else
		{
			$returnURL .= "?notifMsg=".base64_encode($msg)."&notifType=".base64_encode($msg_type);
		}
		
		header("location:".__BASE_URL__.$returnURL);
	}
	elseif($user->getProperty("role") != 0)
	{
		#authentication failed
		$msg = "Sorry! You do not have admin rights.";
		$msg_type = "warning";
		
		header("location:".__BASE_URL__."?notifMsg=".base64_encode($msg)."&notifType=".base64_encode($msg_type));
	}
	else
	{
		#authentication failed
		$msg = "Authentication Failed! Please try again";
		$msg_type = "error";
		
		header("location:".__BASE_URL__."admin/login.php?notifMsg=".base64_encode($msg)."&notifType=".base64_encode($msg_type));
	}//end if isAuthenticated
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
								
				//email must be selected
				obj = document.getElementById("txtEmail");
				if(obj.value == "")
				{
					if (errorMsg != "") errorMsg = errorMsg + "<br />";
					errorMsg = errorMsg + "<span style='color:#f00'>Please enter your email address!</span>";
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
					
					var obj = document.getElementById("btnLogin");
					obj.disabled = false;
					
					return false;
				}
				return true;
			}
					
        </script>
		<!-- Validates compulsory fields in form ends here -->
        
        <!-- Email and password validator starts here  -->
		<script>
        function checkMail(frmLogin,txtEmail){
           var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
           var address = document.forms[frmLogin].elements[txtEmail].value;
           if(reg.test(address) == false) {
              alert('Invalid Email Address (you must supply a valid Email)');
              document.getElementById('txtEmail').value="";
              return false;
           }
        }
        function validateEmail(){
            checkMail('frmLogin','txtEmail');
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
                    Customer Login
                </h1>
                <p>&nbsp;</p>
                <div>Fill in the form below to login to your account</div>
                <p>&nbsp;</p>
                <div>
                    <form id="frmLogin" name="frmLogin" method="post" action="" 
                    enctype="multipart/form-data" onsubmit="return validateUpdate();">
                    <table>
                    	<tr>
                    		<td><label>Email: </label></td>
                       		<td><input name="txtEmail" type="text" id="txtEmail" value=""
                            onblur="javascript:return validateEmail();" /></td>
                        </tr>
                    	<tr>
                        	<td><label>Password: </label></td>
                        	<td><input name="txtPassword" type="password" id="txtPassword" value="" 
                            onblur="javascript:return validatePassword();" /></td>
                        </tr>
                        <tr>
                        	<td><input type="hidden" name="returnURL" value="<?php echo @$returnURL ;?>" /></td>
                        	<td><input name="btnLogin" type="submit" id="btnLogin" value="Login" /></td>
                        </tr>
                    </table>
                    </form>
                </div>
                <p>&nbsp;</p>
                <div>Do not have an account yet? <a href="register.php">Register Now</a></div>
           </div>                     
          </div>
            
            <!-- Footer starts here -->
            	<?php include_once(__ROOT__."include/footer.inc.php") ; ?>
            <!-- Footer ends here -->
          </div>
                
    </body>
    
</html>
