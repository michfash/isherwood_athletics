<?php

if(isset($_SESSION['isherwood_athletics']['userHandle']))
{
	$userHandle = unserialize($_SESSION['isherwood_athletics']['userHandle']);

	if($userHandle->getProperty("isAuthenticated") == false)
	{
		fnRedirect();
	}
	elseif($userHandle->getProperty("isAuthenticated") == true)
	{
		#do nothing
	}
}
else
{
	fnRedirect();
}



function fnRedirect()
{
	#authentication required
	$msg = "Authentication Required!";
	$msg_type = "warning";
	$returnURL = urlencode($_SERVER['REQUEST_URI']);
	
	header("location:".__BASE_URL__."users/login.php?returnURL=".$returnURL."&notifMsg=".base64_encode($msg)."&notifType=".base64_encode($msg_type));
}
?>