<?php
if($userHandle->getProperty('role') != 0)
{
	#redirect to index
	$feedback = "You do not have Admin rights.";
	header("location:".__BASE_URL__."?notifMsg=".base64_encode($feedback)."&notifType=".base64_encode("error"));
}

?>