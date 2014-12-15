<?php

/*
	The cart class
	manipulating the cart object
*/

	
class Carts
{
	#define status constants
	const STATUS_INACTIVE = 0;
	const STATUS_ACTIVE = 1;
	const STATUS_DELETED = 9;
	
	#define class/object properties
	private $statusTitle = array();
	
	private $cartID;	
	private $userID;	
	private $cartStatus;
	private $dateCreated;
	
	private $cartItemID;
	private $variationID;
	private $cartItemQty;
	private $cartItemPrice;	
	private $cartItemStatus;	
	
	
	function getProperty($propertyName)
	{
		return $this->$propertyName;
	}

	function setProperty($propertyName,$newValue)
	{
		$this->$propertyName = $newValue;
	}
	
	/* constructor */
	function Carts
	(
		
	)
	{
		$this->statusTitle[self::STATUS_INACTIVE] = "Inactive";
		$this->statusTitle[self::STATUS_ACTIVE] = "Active";
		$this->statusTitle[self::STATUS_DELETED] = "Deleted";
				
	}//End of constructor
	
	function saveCart()
	{
		$this->dateCreated = strtotime('now');
		#default status
		$this->cartStatus = self::STATUS_ACTIVE;
		
		$query = 
		"
			INSERT INTO
				
				`cart`
				
			SET
			
				`userID` = '".mysql_real_escape_string($this->userID)."',
				`cartStatus` = '".mysql_real_escape_string($this->cartStatus)."',
				`dateCreated` = '".mysql_real_escape_string($this->dateCreated)."'
		";		
		#echo "<pre>".$query."</pre>";	
				
		$result = mysql_query($query);
		$cartID = mysql_insert_id();
		
		if(!$result)
		{
			return array("errNo"=>mysql_errno(),"errMsg"=>mysql_error());
		}//end if

		//insert into cart item
		//********call saveCartItem method*******
		$success = $this->saveCartItem($cartID);
		if(!$success) echo "Error saving the cart item";
			
		return $this->cartID = $cartID;
	}
	
	function saveCartItem($cartID)
	{		
		#default status
		$this->cartItemStatus = self::STATUS_ACTIVE;
		
		$query = 
		"
			INSERT INTO
				
				`cartitem`
				
			SET
			
				`cartID` = '".mysql_real_escape_string($cartID)."',
				`variationID` = '".mysql_real_escape_string($this->variationID)."',
				`cartItemQty` = '".mysql_real_escape_string($this->cartItemQty)."',
				`cartItemPrice` = '".mysql_real_escape_string($this->cartItemPrice)."',
				`cartItemStatus` = '".mysql_real_escape_string($this->cartItemStatus)."'
		";		
		#echo "<pre>".$query."</pre>";	
				
		$result = mysql_query($query);
		$cartItemID = mysql_insert_id();
			
		return $this->cartItemID = $cartItemID;
	}
			
	function load($cartID)
	{
	  $query = 
			"
				SELECT * 
				FROM  `cart` a
				JOIN  `cartitem` b ON a.`cartID` = b.`cartID` 
				WHERE 						
					a.`cartID` = '".mysql_real_escape_string($cartID)."'
			";
		
		#echo "<code>".$query."</code>";
		$resultset = mysql_query($query);
		
		$allRows = array();
		
		while($row = mysql_fetch_assoc($resultset))
		{			
			$allRows[] = $row;
		}

		return $allRows;
	}

}

?>
