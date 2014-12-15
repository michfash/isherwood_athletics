<?php

/*
	The colour class

*/

	
class Colours
{
	#define status constants
	const STATUS_INACTIVE = 0;
	const STATUS_ACTIVE = 1;
	const STATUS_DELETED = 9;
	
	#define class/object properties
	private $statusTitle = array();
	
	private $colourID;
	private $colourDescription;
	private $colourStatus;
		
	function getProperty($propertyName)
	{
		return $this->$propertyName;
	}

	function setProperty($propertyName,$newValue)
	{
		$this->$propertyName = $newValue;
	}
	
	/* constructor */
	function Colours
	(
		
	)
	{
		$this->statusTitle[self::STATUS_INACTIVE] = "Inactive";
		$this->statusTitle[self::STATUS_ACTIVE] = "Active";
		$this->statusTitle[self::STATUS_DELETED] = "Deleted";
				
	}//End of constructor
	
	function saveColour()
	{
		#default status
		$this->colourStatus = self::STATUS_ACTIVE;
		
		$query = 
		"
			INSERT INTO
				
				`colours`
				
			SET
			
				`colourDescription` = '".mysql_real_escape_string($this->colourDescription)."',
				`colourStatus` = '".mysql_real_escape_string($this->colourStatus)."'
		";		
		#echo "<pre>".$query."</pre>";	
				
		$result = mysql_query($query);
		$colourID = mysql_insert_id();
		
		if(!$result)
		{
			return array("errNo"=>mysql_errno(),"errMsg"=>mysql_error());
		}//end if
			
		return $this->colourID = $colourID;
	}
	
	static function loadAll_filter($filterParam = array(),$statusParam = array(),$start = 0,$end = __ROWS_PER_PAGE__)
	{
		$statusFilter = "";
		
		if(!empty($statusParam))
		{
			$statusFilter = "`colourStatus` IN ('".implode("','", $statusParam)."') AND ";
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
				`colours`
			WHERE
				
		".$filter . $statusFilter . "	
			 1 = 1 
			 				
			ORDER BY
			
				`colourDescription` ASC
				
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
			$statusFilter = "`colourStatus` IN ('".implode("','", $statusParam)."') AND";
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
				`colours` 
			WHERE 
		".$filter . $statusFilter . "	1 = 1
		";
		
		#echo "<code>".$query."</code>";
			
		$result_set = mysql_query($query);
		
		$count = mysql_fetch_assoc($result_set);
		
		return $count['count'];
	}
	
	function load($colourID)
	{
	  $query = 
			"
				SELECT
				*				
				FROM				
					`colours`
				WHERE
					
					`colourID` = '".mysql_real_escape_string($colourID)."'
			";
		
		#echo "<code>".$query."</code>";
		$resultset = mysql_query($query);
		
		$result = mysql_fetch_assoc ($resultset);
		
		$this->colourID = $result['colourID'];	
		$this->colourDescription = $result['colourDescription'];
		$this->colourStatus = $result['colourStatus'];
				
		return $result;
	}
	
	function update()
	{		
		#default status
		$this->colourStatus = self::STATUS_ACTIVE;
		
		$query = 
		"
			UPDATE
				
				`colours`
				
			SET
			
				`colourDescription` = '".mysql_real_escape_string($this->colourDescription)."'
								
			WHERE
			
				`colourID` = '".mysql_real_escape_string($this->colourID)."'
		";
		
		#echo "<pre>".$query."</pre>";	
				
		$result = mysql_query($query);
		
		return $this->colourID;
	}	
	
	static function setStatus($colourID,$colourStatus)
	{
		$query = "
			UPDATE
				`colours` 
			SET
			 `colourStatus` = '".mysql_real_escape_string($colourStatus)."'
			WHERE
				`colourID` = '".mysql_real_escape_string($colourID)."'
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
