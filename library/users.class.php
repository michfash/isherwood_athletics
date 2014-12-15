<?php

/*
	The user class
	collection of user objects, and methods to manage them
*/


class Users
{
	const STATUS_INACTIVE = 0;
	const STATUS_ACTIVE = 1;
	const STATUS_SUSPENDED = 2;
	const STATUS_DELETED = 9;
	
	private $statusTitle = array();
	
	private $isAuthenticated = false;
	private $safePassword;
	
	private $userID;
	private $firstName;
	private $lastName;
	private $email;
	private $phone;
	private $address;
	private $password;
	private $role;
	private $lastLoginDate;
	private $loginDate;
	private $dateRegistered;
	private $lastUpdated;
	private $updatedBy;	
	private $userStatus;
	
	function getProperty($propertyName)
	{
		return $this->$propertyName;
	}

	function setProperty($propertyName,$newValue)
	{
		$this->$propertyName = $newValue;
	}
	
	/* constructor */
	function Users
	(
		
	)
	{
		$this->statusTitle[self::STATUS_INACTIVE] = "Inactive";
		$this->statusTitle[self::STATUS_ACTIVE] = "Active";
		$this->statusTitle[self::STATUS_SUSPENDED] = "Suspended";
		$this->statusTitle[self::STATUS_DELETED] = "Deleted";
				
	}//End of constructor
	
	function save()
	{
		$this->dateRegistered = @strtotime("now");
		$this->lastLoginDate = @strtotime("now");
		
		#default status
		$this->userStatus = self::STATUS_ACTIVE;
		
		$query = 
		"
			INSERT INTO
				
				`users`
				
			SET
			
				`firstName` = '".mysql_real_escape_string($this->firstName)."',
				`lastName` = '".mysql_real_escape_string($this->lastName)."',
				`email` = '".mysql_real_escape_string($this->email)."',
				`phone` = '".mysql_real_escape_string($this->phone)."',
				`address` = '".mysql_real_escape_string($this->address)."',
				`password` = '".md5(mysql_real_escape_string($this->password))."',
				`role` = '".mysql_real_escape_string($this->role)."',
				`dateRegistered` = '".mysql_real_escape_string($this->dateRegistered)."',
				`lastLoginDate` = '".mysql_real_escape_string($this->lastLoginDate)."',
				`userStatus` = '".mysql_real_escape_string($this->userStatus)."'
		";
		
		#echo "<pre>".$query."</pre>";	
				
		$result = mysql_query($query);
		$userID = mysql_insert_id();
					
		return $this->userID = $userID;
	}
	
	function load($userID)
	{
	  $query = 
			"
				SELECT
					
					*
					
				FROM
					
					`users`
					
				WHERE
					
					`userID` = '".mysql_real_escape_string($userID)."'
					
			";
		
		$resultset = mysql_query($query);
		
		$result = mysql_fetch_assoc ($resultset);
	
		$this->userID = $result['userID'];
		
		$this->firstName = $result['firstName'];
		$this->lastName = $result['lastName'];
		$this->email = $result['email'];
		$this->phone = $result['phone'];
		$this->address = $result['address'];
		$this->password = $result['password'];
		$this->role = $result['role'];
		$this->lastLoginDate = $result['lastLoginDate'];
		$this->dateRegistered = $result['dateRegistered'];
		$this->lastUpdated = $result['lastUpdated'];
		$this->updatedBy = $result['updatedBy'];
		$this->userStatus = $result['userStatus'];
		
		return $result;
	}
	
	function update()
	{
		$this->lastUpdated = @strtotime("now");
		
		#default status
		$this->userStatus = self::STATUS_ACTIVE;
		
		$query = 
		"
			UPDATE
				
				`users`
				
			SET
							
				`firstName` = '".mysql_real_escape_string($this->firstName)."',
				`lastName` = '".mysql_real_escape_string($this->lastName)."',
				`email` = '".mysql_real_escape_string($this->email)."',
				`phone` = '".mysql_real_escape_string($this->phone)."',
				`address` = '".mysql_real_escape_string($this->address)."',
				`password` = '".md5(mysql_real_escape_string($this->password))."',
				
				`lastUpdated` = '".mysql_real_escape_string($this->lastUpdated)."',
				`updatedBy` = '".mysql_real_escape_string($this->updatedBy)."',
												
				`userStatus` = '".mysql_real_escape_string($this->userStatus)."'
				
			WHERE
			
				`userID` = '".mysql_real_escape_string($this->userID)."'
		";
		
		#echo "<pre>".$query."</pre>";	
				
		$result = mysql_query($query);
		
		return true;
	}
		
	function checkAuth($email = "",$plainPassword = "")
	{
		$this->email = $email;
		$this->safePassword = md5($plainPassword);
		$this->isAuthenticated = false;
	
		$checkSQL = 
		"
			SELECT 
				
				*
				
			FROM
			
				`users`
			
			WHERE 
				 
				`email` = '".mysql_real_escape_string($this->email)."' AND 
				`password` = '".mysql_real_escape_string($this->safePassword)."' AND 
				`userStatus` NOT IN ('0','2','9')
		";
		
		printf("<pre>%s</pre>",$checkSQL);
		
		$result = @mysql_query($checkSQL);
	
		if($result && @mysql_num_rows($result) == 1)
		{
			#authentication successful!
			$userDetails = @mysql_fetch_assoc($result);
			
			$this->userID = $userDetails['userID'];
			$this->firstName = $userDetails['firstName'];
			$this->lastName = $userDetails['lastName'];
			$this->email = $userDetails['email'];
			$this->phone = $userDetails['phone'];
			$this->address = $userDetails['address'];
			$this->password = $userDetails['password'];
			$this->role = $userDetails['role'];
			$this->lastLoginDate = $userDetails['lastLoginDate'];
			$this->dateRegistered = $userDetails['dateRegistered'];
			$this->lastUpdated = $userDetails['lastUpdated'];
			$this->updatedBy = $userDetails['updatedBy'];
			$this->userStatus = $userDetails['userStatus'];
						
			#set login date
			$this->loginDate = strtotime('now');
		}
		else
		{	
			#authentication failed
			$this->isAuthenticated = false;
		}//end if
		return $this->isAuthenticated = true;
	}
	
	function updateLastLogin($userID,$loginDate)
	{
		$query="UPDATE `users` SET `lastLoginDate`='".$loginDate."' WHERE `userID`='".$userID."'";
		#printf("<pre>%s</pre>",$query);
		$runQuery = mysql_query($query);
		
		return true;
	}
}

?>
