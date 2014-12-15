<?php
$pageID = '4';
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

#close connection to database because they say is best practice
include_once(__ROOT__."include/db.close.inc.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Welcome to Isherwood Athletics</title>
        <link href="<?php echo __BASE_URL__ ;?>css/style.css" rel="stylesheet" type="text/css" />
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
                    My Profile
                </h1>
                <p>&nbsp;</p>
                <div style="margin:0px">
                    <div style="padding:10px;">
                    <table width="486" style="font-size:12px">
                        <tr>
                            <td width="153"><strong>First Name</strong></td>
                            <td width="321">
                               <?php echo $userDetails->getProperty('firstName') ;?>
                            </td>
                        </tr>
                        <tr>
                            <td width="153"><strong>Last Name</strong></td>
                            <td width="321">
                               <?php echo $userDetails->getProperty('lastName') ;?>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Phone</strong></td>
                            <td>
                                <?php echo $userDetails->getProperty('phone') ;?>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Address</strong></td>
                            <td>
                                <?php echo $userDetails->getProperty('address') ;?>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Email</strong></td>
                            <td>
                                <?php echo $userDetails->getProperty('email') ;?>
                            </td>
                        </tr>
                        <?php
							if($userDetails->getProperty('role') == '0')
							{
						?>
                        <tr>
                            <td><strong>Role</strong></td>
                            <td>
                                Store Administrator
                            </td>
                        </tr>
                        <?php
							}
						?>
                        <tr>
                            <td><strong>Last Login Date</strong></td>
                            <td>
                                <?php echo ($userDetails->getProperty('lastLoginDate') !='')? date("D d M Y g:i:s A",$userDetails->getProperty('lastLoginDate')) : date("D d M Y g:i:s A",$userDetails->getProperty('dateRegistered'));?>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Date Registered</strong></td>
                            <td>
                                <?php echo date("D d M Y g:i:s A",$userDetails->getProperty('dateRegistered')) ;?>
                            </td>
                        </tr>
                    </table>
                    </div>
                    <div style="clear:both;margin:20px;font-size:12px">
                        <a href="<?php echo __BASE_URL__ ;?>users/edit.php?uID=<?php echo $userDetails->getProperty('userID') ; ?>">
                        &raquo; Edit profile</a>
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
