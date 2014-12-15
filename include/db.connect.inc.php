<?php
	$host = "localhost";
	$database = "isherwood_athletics";
	$username = "root";
	$password ="";

	$db_link = mysql_connect($host,$username,$password);

	//select database
	$db_selected = mysql_select_db($database, $db_link);
	/* close connection */
	#mysql_close($db_link);
?>