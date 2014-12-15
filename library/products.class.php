<?php

/*
	The product class
	collection of product objects, and methods to manage them
*/

	
class Products
{
	#define status constants
	const STATUS_INACTIVE = 0;
	const STATUS_ACTIVE = 1;
	const STATUS_DELETED = 9;
	
	#define class/object properties
	private $statusTitle = array();
	
	private $productID;	
	private $catID;
	private $subCatID;
	private $productName = "";
	private $productDescription = "";
	private $productStatus;
	private $productPicture;
	
	private $variationID;
	private $sizeID;
	private $colourID;
	private $stock;
	private $price;	
	private $dateCreated;
	private $updatedBy;	
	private $dateUpdated;	
	private $variationStatus;
	
	function getProperty($propertyName)
	{
		return $this->$propertyName;
	}

	function setProperty($propertyName,$newValue)
	{
		$this->$propertyName = $newValue;
	}
	
	/* constructor */
	function Products
	(
		
	)
	{
		$this->statusTitle[self::STATUS_INACTIVE] = "Inactive";
		$this->statusTitle[self::STATUS_ACTIVE] = "Active";
		$this->statusTitle[self::STATUS_DELETED] = "Deleted";
				
	}//End of constructor
	
	function saveProduct()
	{
		#default status
		$this->productStatus = self::STATUS_ACTIVE;
		
		$query = 
		"
			INSERT INTO
				
				`products`
				
			SET
			
				`catID` = '".mysql_real_escape_string($this->catID)."',
				`subCatID` = '".mysql_real_escape_string($this->subCatID)."',
				`productName` = '".mysql_real_escape_string($this->productName)."',
				`productDescription` = '".mysql_real_escape_string($this->productDescription)."',
				`productPicture` = '".mysql_real_escape_string($this->productPicture)."',
				`productStatus` = '".mysql_real_escape_string($this->productStatus)."'
		";		
		#echo "<pre>".$query."</pre>";	
				
		$result = mysql_query($query);
		$productID = mysql_insert_id();
		
		//save product variation
		//********call saveVariation method*******
		$success = $this->saveVariation($productID);
		if(!$success) echo "Error saving the product variation";
			
		return $this->productID = $productID;
	}
	
	function saveVariation($productID)
	{
		$this->dateCreated = @strtotime("now");
		
		#default status
		$this->variationStatus = self::STATUS_ACTIVE;
		
		$query = 
		"
			INSERT INTO
				
				`product_variations`
				
			SET
			
				`productID` = '".mysql_real_escape_string($productID)."',
				`sizeID` = '".mysql_real_escape_string($this->sizeID)."',
				`colourID` = '".mysql_real_escape_string($this->colourID)."',
				`stock` = '".mysql_real_escape_string($this->stock)."',
				`price` = '".mysql_real_escape_string($this->price)."',
				`dateCreated` = '".mysql_real_escape_string($this->dateCreated)."',
				`variationStatus` = '".mysql_real_escape_string($this->variationStatus)."'
		";		
		#echo "<pre>".$query."</pre>";	
				
		$result = mysql_query($query);
		$variationID = mysql_insert_id();
		
		return $this->variationID = $variationID;
	}
	
	static function loadAll_filter($filterParam = array(),$statusParam = array(),$start = 0,$end = __ROWS_PER_PAGE__)
	{
		$statusFilter = "";
		
		if(!empty($statusParam))
		{
			$statusFilter = "a.`productStatus` IN ('".implode("','", $statusParam)."') AND ";
			$statusFilter .= "b.`variationStatus` IN ('".implode("','", $statusParam)."') AND";
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
				COUNT(b.`productID`) AS 'NumberOfItemPerProduct'				
			FROM				
				`products` a
			JOIN
				`product_variations` b ON a.`productID`=b.`productID` 
			JOIN
				`categories` c ON a.`catID`=c.`catID`
			WHERE
				
		".$filter . $statusFilter . "	
			 1 = 1 
			 
			GROUP BY b.`productID` 
				
			ORDER BY
			
				b.`dateCreated` DESC
				
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
	
	static function loadAllProductPerCat($catID = 1,$filterParam = array(),$statusParam = array(),$start = 0,$end = __ROWS_PER_PAGE__)
	{
		$statusFilter = "";
		
		if(!empty($statusParam))
		{
			$statusFilter = "a.`productStatus` IN ('".implode("','", $statusParam)."') AND ";
			$statusFilter .= "b.`variationStatus` IN ('".implode("','", $statusParam)."') AND ";
			$statusFilter .= "c.`catStatus` IN ('".implode("','", $statusParam)."') AND";
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
				COUNT(b.`productID`) AS 'NumberOfItemPerProduct'				
			FROM				
				`products` a
			JOIN
				`product_variations` b ON a.`productID`=b.`productID` AND b.`stock` !=0
			JOIN
				`categories` c ON a.`catID`=c.`catID` AND c.`catID`='$catID'
			WHERE
				
		".$filter . $statusFilter . "	
			 1 = 1 
			 
			GROUP BY b.`productID` 
				
			ORDER BY
			
				b.`dateCreated` DESC
				
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
	
	static function countRows($filterParam = "",$statusParam = "")
	{
		$statusFilter = "";
		
		if(!empty($statusParam))
		{
			$statusFilter = "`productStatus` IN ('".implode("','", $statusParam)."') AND";
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
				`products` a
			JOIN
				`categories` b ON a.`catID`=b.`catID`
			WHERE 
		".$filter . $statusFilter . "	1 = 1
		";
		
		#echo "<code>".$query."</code>";
			
		$result_set = mysql_query($query);
		
		$count = mysql_fetch_assoc($result_set);
		
		return $count['count'];
	}
	
	function load($productID)
	{
	  $query = 
			"
				SELECT
					*				
				FROM				
					`products` a
				JOIN
					`product_variations` b ON a.`productID`=b.`productID` AND b.`variationStatus`='1'
				JOIN
					`categories` c ON a.`catID`=c.`catID` AND c.`catStatus`='1'
				JOIN
					`sub_categories` d ON a.`subCatID`=d.`subCatID` AND d.`subCatStatus`='1'
				WHERE
					
					a.`productID` = '".mysql_real_escape_string($productID)."'
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
	
	function loadByVariation($variationID)
	{
	  $query = 
			"
				SELECT
					*				
				FROM				
					`products` a
				JOIN
					`product_variations` b ON a.`productID`=b.`productID` AND b.`variationStatus`='1'
				JOIN
					`categories` c ON a.`catID`=c.`catID` AND c.`catStatus`='1'
				JOIN
					`sub_categories` d ON a.`subCatID`=d.`subCatID` AND d.`subCatStatus`='1'
				WHERE
					
					b.`variationID` = '".mysql_real_escape_string($variationID)."'
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
		
	function loadItem($variationID)
	{
	  $query = 
			"
				SELECT
					*				
				FROM				
					`product_variations`
				WHERE
					
					`variationID` = '".mysql_real_escape_string($variationID)."'
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
	
	function getItemProperty($productID,$sizeID,$colourID)
	{
		$query = 
			"
				SELECT
					*				
				FROM	
					`product_variations` 
				WHERE
					
					`productID` = '".mysql_real_escape_string($productID)."'
					AND `sizeID` = '".mysql_real_escape_string($sizeID)."'
					AND `colourID` = '".mysql_real_escape_string($colourID)."'
					AND `variationStatus` = '1'
			";
		
		#echo "<code>".$query."</code>";
		$resultset = mysql_query($query);
		$result = mysql_fetch_assoc ($resultset);
	
		$this->variationID = $result['variationID'];
		$this->sizeID = $result['sizeID'];
		$this->colourID = $result['colourID'];
		$this->stock = $result['stock'];
		$this->price = $result['price'];
		
		return $result;
	}
	
	function updateStockLevel($variationID)
	{				
		$query = 
		"
			UPDATE
				
				`product_variations`
				
			SET
			
				`stock` = '".mysql_real_escape_string($this->stock)."'
								
			WHERE
			
				`variationID` = '".mysql_real_escape_string($variationID)."'
		";
		
		#echo "<pre>".$query."</pre>";	
				
		$result = mysql_query($query);
		
		return true;
	}

	function update()
	{
		#default status
		$this->productStatus = self::STATUS_ACTIVE;
		
		$query = 
		"
			UPDATE
				
				`products`
				
			SET
			
				`catID` = '".mysql_real_escape_string($this->catID)."',
				`subCatID` = '".mysql_real_escape_string($this->subCatID)."',
				`productName` = '".mysql_real_escape_string($this->productName)."',
				`productDescription` = '".mysql_real_escape_string($this->productDescription)."',
				`productPicture` = '".mysql_real_escape_string($this->productPicture)."'
								
			WHERE
			
				`productID` = '".mysql_real_escape_string($this->productID)."'
		";
		
		#echo "<pre>".$query."</pre>";	
				
		$result = mysql_query($query);
		
		return $this->productID;
	}
	

	function updateItem()
	{
		$this->dateUpdated = strtotime('now');
		#default status
		$this->variationStatus = self::STATUS_ACTIVE;
		
		$query = 
		"
			UPDATE
				
				`product_variations`
				
			SET
			
				`sizeID` = '".mysql_real_escape_string($this->sizeID)."',
				`colourID` = '".mysql_real_escape_string($this->colourID)."',
				`stock` = '".mysql_real_escape_string($this->stock)."',
				`price` = '".mysql_real_escape_string($this->price)."'
								
			WHERE
			
				`variationID` = '".mysql_real_escape_string($this->variationID)."'
		";
		
		#echo "<pre>".$query."</pre>";	
				
		$result = mysql_query($query);
		
		return $this->variationID;
	}
	
	static function setStatus($productID,$productStatus)
	{
		$query = "
			UPDATE
				`products` 
			SET
			 `productStatus` = '".mysql_real_escape_string($productStatus)."'
			WHERE
				`productID` = '".mysql_real_escape_string($productID)."'
			";
			$result = mysql_query($query);
		
		if($result)
		{
			return true;
		}
		
		return false;
	}	
	
	static function setVarStatus($variationID,$variationStatus)
	{
		$query = "
			UPDATE
				`product_variations` 
			SET
			 `variationStatus` = '".mysql_real_escape_string($variationStatus)."'
			WHERE
				`variationID` = '".mysql_real_escape_string($variationID)."'
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
