<?php

/*
	The order class
	manipulating the order object
*/

	
class Orders
{
	#define status constants
	const STATUS_PROCESSING = 'p';
	const STATUS_SHIPPED = 's';
	const STATUS_DELIVERED = 'd';
	const STATUS_RETURNED = 'r';
	
	#define class/object properties
	private $statusTitle = array();
	
	private $orderID;	
	private $userID;
	private $cc;	
	private $total;	
	private $paymentDate;
	private $shippingDate;
	private $deliveryDate;	
	private $returnDate;
	private $orderStatus;
	
	private $orderDetailID;	
	private $variationID;
	private $quantity;
	private $amount;

	
	function getProperty($propertyName)
	{
		return $this->$propertyName;
	}

	function setProperty($propertyName,$newValue)
	{
		$this->$propertyName = $newValue;
	}
	
	/* constructor */
	function Orders
	(
		
	)
	{
		$this->statusTitle[self::STATUS_DELIVERED] = "Delivered";
		$this->statusTitle[self::STATUS_PROCESSING] = "Processing";
		$this->statusTitle[self::STATUS_RETURNED] = "Returned";
		$this->statusTitle[self::STATUS_SHIPPED] = "Shipped";
				
	}//End of constructor
	
	function saveOrder()
	{
		$this->paymentDate = strtotime('now');
		#default status
		$this->orderStatus = self::STATUS_PROCESSING;
		
		$query = 
		"
			INSERT INTO
				
				`orders`
				
			SET
			
				`userID` = '".mysql_real_escape_string($this->userID)."',
				`cc` = '".mysql_real_escape_string($this->cc)."',
				`total` = '".mysql_real_escape_string($this->total)."',
				`paymentDate` = '".mysql_real_escape_string($this->paymentDate)."',
				`orderStatus` = '".mysql_real_escape_string($this->orderStatus)."'
		";		
		#echo "<pre>".$query."</pre>";	
				
		$result = mysql_query($query);
		$orderID = mysql_insert_id();
					
		return $this->orderID = $orderID;
	}
	
	function saveOrderDetails($orderID)
	{		
		$query = 
		"
			INSERT INTO
				
				`orderdetails`
				
			SET
			
				`variationID` = '".mysql_real_escape_string($this->variationID)."',
				`quantity` = '".mysql_real_escape_string($this->quantity)."',
				`amount` = '".mysql_real_escape_string($this->amount)."',
				`orderID` = '".mysql_real_escape_string($orderID)."'
		";		
		#echo "<pre>".$query."</pre>";	
				
		$result = mysql_query($query);
		$orderDetailID = mysql_insert_id();
						
		return $this->orderDetailID = $orderDetailID;
	}
	
	function loadAll_filter($filterParam = array(),$statusParam = array(),$start = 0,$end = __ROWS_PER_PAGE__)
	{
		$statusFilter = "";
		
		if(!empty($statusParam))
		{
			$statusFilter = "a.`orderStatus` IN ('".implode("','", $statusParam)."') AND ";
		}
		
		$filter = "";
		
		if(!empty($filterParam))
		{
			
			foreach($filterParam as $fieldExpr => $value)
			{
				$filter .= ($filter == "") ? "( " : " OR ";
				$filter .= $fieldExpr ." LIKE '%".mysql_real_escape_string($value)."%' ";			
			}
			
			$filter .= " ) AND ";
		}
		
		$limit = "";
		if(!empty($start) || !empty($end)) $limit="LIMIT $start, $end";
		
		$query = 
		"			
				SELECT
					*,
					COUNT(b.`orderID`) AS 'NumberOfOrders'	
				FROM				
					`orders` a
				JOIN
					`orderdetails` b ON a.`orderID`=b.`orderID` 
				JOIN
					`product_variations` c ON b.`variationID`=c.`variationID`
				JOIN
					`products` d ON c.`productID`=d.`productID`
				WHERE
					
			".$filter . $statusFilter . "
				 1 = 1 
				 
				GROUP BY b.`orderID` 
								
				ORDER BY
				
					a.`orderID` DESC
				
		 ".$limit."
			";
			
		#echo"<pre>".$query."</pre>";
		
		$resultset = mysql_query($query);
		
		$allRows = array();
		
		while($row = mysql_fetch_assoc($resultset))
		{			
			$allRows[] = $row;
		}

		return $allRows;
	}			
			
	function loadAllByUser($filterParam = array(),$statusParam = array(),$start = 0,$end = __ROWS_PER_PAGE__)
	{
		$statusFilter = "";
		
		if(!empty($statusParam))
		{
			$statusFilter = "a.`orderStatus` IN ('".implode("','", $statusParam)."') AND ";
		}
		
		$filter = "";
		
		if(!empty($filterParam))
		{
			
			foreach($filterParam as $fieldExpr => $value)
			{
				$filter .= ($filter == "") ? "( " : " OR ";
				$filter .= $fieldExpr ." LIKE '%".mysql_real_escape_string($value)."%' ";			
			}
			
			$filter .= " ) AND ";
		}
		
		$limit = "";
		if(!empty($start) || !empty($end)) $limit="LIMIT $start, $end";
		
		$query = 
		"			
				SELECT
					*,
					COUNT(b.`orderID`) AS 'NumberOfOrders'	
				FROM				
					`orders` a
				JOIN
					`orderdetails` b ON a.`orderID`=b.`orderID` 
				JOIN
					`product_variations` c ON b.`variationID`=c.`variationID`
				JOIN
					`products` d ON c.`productID`=d.`productID`
				WHERE
				a.`userID` = ".mysql_real_escape_string($this->userID)."  AND
					
			".$filter . $statusFilter . "
				 1 = 1 
				 
				GROUP BY b.`orderID` 
								
				ORDER BY
				
					a.`orderID` DESC
				
		 ".$limit."
			";
			
		#echo"<pre>".$query."</pre>";
		
		$resultset = mysql_query($query);
		
		$allRows = array();
		
		while($row = mysql_fetch_assoc($resultset))
		{			
			$allRows[] = $row;
		}

		return $allRows;
	}			
			
	function loadOrderByUser($orderID)
	{
	  $query = 
			"
				SELECT * 
				FROM  `orders` a
				JOIN
					`orderdetails` b ON a.`orderID`=b.`orderID` 
				JOIN
					`product_variations` c ON b.`variationID`=c.`variationID`
				JOIN
					`products` d ON c.`productID`=d.`productID`
				JOIN
					`users` e ON a.`userID`=e.`userID`
				WHERE 						
					a.`orderID` = '".mysql_real_escape_string($orderID)."' AND
					a.`userID` = '".mysql_real_escape_string($this->userID)."'
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
			
	function load($orderID)
	{
	  $query = 
			"
				SELECT * 
				FROM  `orders` a
				JOIN
					`orderdetails` b ON a.`orderID`=b.`orderID` 
				JOIN
					`product_variations` c ON b.`variationID`=c.`variationID`
				JOIN
					`products` d ON c.`productID`=d.`productID`
				JOIN
					`users` e ON a.`userID`=e.`userID`
				WHERE 						
					a.`orderID` = '".mysql_real_escape_string($orderID)."'
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
	
	static function countRowsByUser($filterParam = "",$statusParam = "",$userID)
	{
		$statusFilter = "";
		
		if(!empty($statusParam))
		{
			$statusFilter = "`orderStatus` IN ('".implode("','", $statusParam)."') AND";
		}
		
		$filter = "";
		
		if(!empty($filterParam))
		{
			
			foreach($filterParam as $fieldExpr => $value)
			{
				$filter .= ($filter == "") ? "( " : " OR ";
				$filter .= $fieldExpr ." LIKE '%".mysql_real_escape_string($value)."%' ";				
			}
			
			$filter .= " ) AND ";
			
		}
		
		$query = "
			SELECT 
				count(*) as 'count'
			FROM
				`orders`  
			WHERE 
				`userID` = ".mysql_real_escape_string($userID)."  AND
		".$filter . $statusFilter . "	1 = 1
		";
		
		#echo "<code>".$query."</code>";
			
		$result_set = mysql_query($query);
		
		$count = mysql_fetch_assoc($result_set);
		
		return $count['count'];
	}
	
	static function countRows($filterParam = "",$statusParam = "")
	{
		$statusFilter = "";
		
		if(!empty($statusParam))
		{
			$statusFilter = "`orderStatus` IN ('".implode("','", $statusParam)."') AND";
		}
		
		$filter = "";
		
		if(!empty($filterParam))
		{
			
			foreach($filterParam as $fieldExpr => $value)
			{
				$filter .= ($filter == "") ? "( " : " OR ";
				$filter .= $fieldExpr ." LIKE '%".mysql_real_escape_string($value)."%' ";				
			}
			
			$filter .= " ) AND ";
			
		}
		
		$query = "
			SELECT 
				count(*) as 'count'
			FROM
				`orders` 
			WHERE 
		".$filter . $statusFilter . "	1 = 1
		";
		
		#echo "<code>".$query."</code>";
			
		$result_set = mysql_query($query);
		
		$count = mysql_fetch_assoc($result_set);
		
		return $count['count'];
	}
	
	function setStatus($orderID,$status)
	{
		if(!empty($status) && $status == 's' )
		{
			$this->shippingDate = @strtotime("now"); 
			$dateParam = ',`shippingDate`='.$this->shippingDate;
		}
		if(!empty($status) && $status == 'd' )
		{
			 $this->deliveryDate = @strtotime("now"); 
			 $dateParam = ',`deliveryDate`='.$this->deliveryDate;
		}
		if(!empty($status) && $status == 'r' )
		{
			 $this->returnDate = @strtotime("now"); 
			 $dateParam = ',`returnDate`='.$this->returnDate;
		}
		
		$query = "
			UPDATE
				`orders` 
			SET
			 `orderStatus` = '".mysql_real_escape_string($status)."'
			 ".$dateParam."
			WHERE
				`orderID` = '".mysql_real_escape_string($orderID)."'
			";
			$result = mysql_query($query);
		
		if($result)
		{
			return true;
		}
		
		return false;
	}
		
}

?>
