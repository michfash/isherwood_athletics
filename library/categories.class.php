<?php

/*
	The categories class
	considering sub-categories too
*/

	
class Categories
{
	#define status constants
	const STATUS_INACTIVE = 0;
	const STATUS_ACTIVE = 1;
	const STATUS_DELETED = 9;
	
	#define class/object properties
	private $statusTitle = array();
	
	private $catID;
	private $catDescription;
	private $categoryPicture;	
	private $catStatus;
	
	private $subCatID;
	private $subCatDescription;
	private $subCatStatus;
		
	function getProperty($propertyName)
	{
		return $this->$propertyName;
	}

	function setProperty($propertyName,$newValue)
	{
		$this->$propertyName = $newValue;
	}
	
	/* constructor */
	function Categories
	(
		
	)
	{
		$this->statusTitle[self::STATUS_INACTIVE] = "Inactive";
		$this->statusTitle[self::STATUS_ACTIVE] = "Active";
		$this->statusTitle[self::STATUS_DELETED] = "Deleted";
				
	}//End of constructor
	
	function saveCategory()
	{
		#default status
		$this->catStatus = self::STATUS_ACTIVE;
		
		$query = 
		"
			INSERT INTO
				
				`categories`
				
			SET
			
				`catDescription` = '".mysql_real_escape_string($this->catDescription)."',
				`categoryPicture` = '".mysql_real_escape_string($this->categoryPicture)."',
				`catStatus` = '".mysql_real_escape_string($this->catStatus)."'
		";		
		#echo "<pre>".$query."</pre>";	
				
		$result = mysql_query($query);
		$categoryID = mysql_insert_id();
		
		return $this->catID = $categoryID;
	}
	
	function saveSubCategory()
	{
		#default status
		$this->subCatStatus = self::STATUS_ACTIVE;
		
		$query = 
		"
			INSERT INTO
				
				`sub_categories`
				
			SET
			
				`catID` = '".mysql_real_escape_string($this->catID)."',
				`subCatDescription` = '".mysql_real_escape_string($this->subCatDescription)."',
				`subCatStatus` = '".mysql_real_escape_string($this->subCatStatus)."'
		";		
		#echo "<pre>".$query."</pre>";	
				
		$result = mysql_query($query);
		$subCatID = mysql_insert_id();
		
		return $this->subCatID = $subCatID;
	}
	
	static function loadAllCat_filter($filterParam = array(),$statusParam = array(),$start = 0,$end = __ROWS_PER_PAGE__)
	{
		$statusFilter = "";
		
		if(!empty($statusParam))
		{
			$statusFilter = "`catStatus` IN ('".implode("','", $statusParam)."') AND ";
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
				`categories`
			WHERE
				
		".$filter . $statusFilter . "	
			 1 = 1 
			 				
			ORDER BY
			
				`catDescription` ASC
				
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
	
	static function loadAllSubCat_filter($filterParam = array(),$statusParam = array(),$start = 0,$end = __ROWS_PER_PAGE__)
	{
		$statusFilter = "";
		
		if(!empty($statusParam))
		{
			$statusFilter = "`subCatStatus` IN ('".implode("','", $statusParam)."') AND ";
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
				`sub_categories`
			WHERE
				
		".$filter . $statusFilter . "	
			 1 = 1 
			 				
			ORDER BY
			
				`subCatDescription` ASC
				
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
	
	static function countCatRows($filterParam = "",$statusParam = "")
	{
		$statusFilter = "";
		
		if(!empty($statusParam))
		{
			$statusFilter = "`catStatus` IN ('".implode("','", $statusParam)."') AND";
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
				`categories` 
			WHERE 
		".$filter . $statusFilter . "	1 = 1
		";
		
		#echo "<code>".$query."</code>";
			
		$result_set = mysql_query($query);
		
		$count = mysql_fetch_assoc($result_set);
		
		return $count['count'];
	}
	
	static function countSubCatRows($filterParam = "",$statusParam = "")
	{
		$statusFilter = "";
		
		if(!empty($statusParam))
		{
			$statusFilter = "`subCatStatus` IN ('".implode("','", $statusParam)."') AND";
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
				`sub_categories` 
			WHERE 
		".$filter . $statusFilter . "	1 = 1
		";
		
		#echo "<code>".$query."</code>";
			
		$result_set = mysql_query($query);
		
		$count = mysql_fetch_assoc($result_set);
		
		return $count['count'];
	}
	
	function loadCat($catID)
	{
	  $query = 
			"
				SELECT
				*				
				FROM				
					`categories`
				WHERE
					
					`catID` = '".mysql_real_escape_string($catID)."'
			";
		
		#echo "<code>".$query."</code>";
		$resultset = mysql_query($query);
		
		$result = mysql_fetch_assoc ($resultset);
		
		$this->catID = $result['catID'];	
		$this->catDescription = $result['catDescription'];
		$this->categoryPicture = $result['categoryPicture'];
		$this->catStatus = $result['catStatus'];
				
		return $result;
	}
	
	function loadSubCat($subCatID)
	{
	  $query = 
			"
				SELECT
				*				
				FROM				
					`sub_categories`
				WHERE
					
					`subCatID` = '".mysql_real_escape_string($subCatID)."'
			";
		
		#echo "<code>".$query."</code>";
		$resultset = mysql_query($query);
		
		$result = mysql_fetch_assoc ($resultset);
		
		$this->subCatID = $result['subCatID'];	
		$this->catID = $result['catID'];	
		$this->subCatDescription = $result['subCatDescription'];
		$this->subCatStatus = $result['subCatStatus'];
				
		return $result;
	}
	
	function update()
	{
		#default status
		$this->catStatus = self::STATUS_ACTIVE;
		
		$query = 
		"
			UPDATE
				
				`categories`
				
			SET
			
				`catDescription` = '".mysql_real_escape_string($this->catDescription)."',
				`categoryPicture` = '".mysql_real_escape_string($this->categoryPicture)."'
								
			WHERE
			
				`catID` = '".mysql_real_escape_string($this->catID)."'
		";
		
		#echo "<pre>".$query."</pre>";	
				
		$result = mysql_query($query);
		
		return $this->catID;
	}
	
	static function setStatus($catID,$catStatus)
	{
		$query = "
			UPDATE
				`categories` 
			SET
			 `catStatus` = '".mysql_real_escape_string($catStatus)."'
			WHERE
				`catID` = '".mysql_real_escape_string($catID)."'
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
