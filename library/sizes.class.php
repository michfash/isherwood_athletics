<?php

/*
	The size class

*/

	
class Sizes
{
	#define status constants
	const STATUS_INACTIVE = 0;
	const STATUS_ACTIVE = 1;
	const STATUS_DELETED = 9;
	
	#define class/object properties
	private $statusTitle = array();
	
	private $sizeID;
	private $sizeDescription;
	private $sizeStatus;
		
	function getProperty($propertyName)
	{
		return $this->$propertyName;
	}

	function setProperty($propertyName,$newValue)
	{
		$this->$propertyName = $newValue;
	}
	
	/* constructor */
	function Sizes
	(
		
	)
	{
		$this->statusTitle[self::STATUS_INACTIVE] = "Inactive";
		$this->statusTitle[self::STATUS_ACTIVE] = "Active";
		$this->statusTitle[self::STATUS_DELETED] = "Deleted";
				
	}//End of constructor
	
	function saveSize()
	{
		#default status
		$this->sizeStatus = self::STATUS_ACTIVE;
		
		$query = 
		"
			INSERT INTO
				
				`sizes`
				
			SET
			
				`sizeDescription` = '".mysql_real_escape_string($this->sizeDescription)."',
				`sizeStatus` = '".mysql_real_escape_string($this->sizeStatus)."'
		";		
		#echo "<pre>".$query."</pre>";	
				
		$result = mysql_query($query);
		$sizeID = mysql_insert_id();
			
		return $this->sizeID = $sizeID;
	}
	
	static function loadAll_filter($filterParam = array(),$statusParam = array(),$start = 0,$end = __ROWS_PER_PAGE__)
	{
		$statusFilter = "";
		
		if(!empty($statusParam))
		{
			$statusFilter = "`sizeStatus` IN ('".implode("','", $statusParam)."') AND ";
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
				*				
			FROM				
				`sizes` 
			WHERE
				
		".$filter . $statusFilter . "	
			 1 = 1 
			 				
			ORDER BY
			
				`sizeDescription` ASC
				
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
			$statusFilter = "`sizeStatus` IN ('".implode("','", $statusParam)."') AND";
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
				`sizes` 
			WHERE 
		".$filter . $statusFilter . "	1 = 1
		";
		
		#echo "<code>".$query."</code>";
			
		$result_set = mysql_query($query);
		
		$count = mysql_fetch_assoc($result_set);
		
		return $count['count'];
	}
	
	function load($sizeID)
	{
	  $query = 
			"
				SELECT
				*				
				FROM				
					`sizes` 
				WHERE
					
					`sizeID` = '".mysql_real_escape_string($sizeID)."'
			";
		
		#echo "<code>".$query."</code>";
		$resultset = mysql_query($query);
		
		$result = mysql_fetch_assoc ($resultset);
		
		$this->sizeID = $result['sizeID'];	
		$this->sizeDescription = $result['sizeDescription'];
		$this->sizeStatus = $result['sizeStatus'];
				
		return $result;
	}
	
	function update()
	{
		#default status
		$this->sizeStatus = self::STATUS_ACTIVE;
		
		$query = 
		"
			UPDATE
				
				`sizes`
				
			SET
			
				`sizeDescription` = '".mysql_real_escape_string($this->sizeDescription)."'
								
			WHERE
			
				`sizeID` = '".mysql_real_escape_string($this->sizeID)."'
		";
		
		#echo "<pre>".$query."</pre>";	
				
		$result = mysql_query($query);
		
		return $this->sizeID;
	}
	
	static function setStatus($sizeID,$sizeStatus)
	{
		$query = "
			UPDATE
				`sizes` 
			SET
			 `sizeStatus` = '".mysql_real_escape_string($sizeStatus)."'
			WHERE
				`sizeID` = '".mysql_real_escape_string($sizeID)."'
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
