<?php
/*
	The Image class
*/
class Image
{
	private $documentPaths = "";
	private $uploadmsg = "";
	
	/* constructor */
	function getProperty($propertyName)
	{
		return $this->$propertyName;
	}

	function setProperty($propertyName,$newValue)
	{
		$this->$propertyName = $newValue;
	}//end constructor
	
	function uploadImage
	(
		$uploadFieldName,
		$documentNames,
		$destFolder,
		$uploadmsg,
		$errStr
	)
	{

		define("MAX_SIZE", 3000 * 1024); #size in Kb
		define("DESTINATION_FOLDER", $destFolder);
		
		#echo "destination folder: ".$destFolder."<br>";
		#echo "field name: ".$uploadFieldName;
		
		#print_r($_FILES[$uploadFieldName]);
		
		$_accepted_extensions_ = "jpg,jpeg,gif,png";//accepted file types (extensions)
		
		if (!isset($_FILES[$uploadFieldName]))
		{
			if ($errStr != "") $errStr.= "<br />";
			$errStr = "Field Name does NOT exist<br />";		
			return false;
		}
		//end if field was not passed
			
		if(strlen($_accepted_extensions_) > 0)
		{
			$_accepted_extensions_ = explode(",",$_accepted_extensions_); 
		} else
		{
			$_accepted_extensions_ = array();
		}//end if	
		
		// as it is multiple uploads, we will parse the $_FILES array to reorganize it into $files		
		$files = array();
		
		foreach ($_FILES[$uploadFieldName] as $k => $l) 
		{
			foreach ($l as $i => $v) 
			{
				if (!array_key_exists($i, $files)) $files[$i] = array();
				$files[$i][$k] = $v;
			}
		}

		// now we can loop through $files, and feed each element to the class
		foreach ($files as $key => $file)
		{
			$file_has_error = false;
			$file_err = "";
				
			if(is_uploaded_file($file['tmp_name']) && $file['error'] == 0)
		{
				$_name_ = $file['name'];
				$_type_ = $file['type'];
				$_tmp_name_ = $file['tmp_name'];
				$_size_ = $file['size'];
				
				if($_size_ > MAX_SIZE && MAX_SIZE > 0)
				{
					if ($file_err != "") $file_err.= "<br />";
					$file_err .= "File size too large. ";
					$file_has_error = true;
				}//end if size fits
					
				$_ext_ = explode(".", $_name_); 
				$_ext_ = strtolower($_ext_[count($_ext_)-1]);
		
				if(!in_array($_ext_, $_accepted_extensions_) && count($_accepted_extensions_) > 0)
				{
					if ($file_err != "") $file_err.= "<br />";
					$file_err .= "File type not expected";
					$file_has_error = true;
				}//end if ext accepted
					
				if(!is_dir(DESTINATION_FOLDER) || !is_writeable(DESTINATION_FOLDER))
				{
					if ($file_err != "") $file_err.= "<br />";
					$file_err .= "Destination Folder (".DESTINATION_FOLDER.") is read-only or does not exist";
					$file_has_error = true;
				}//end if destination writable
				
				if(!$file_has_error) //i.e if NO error found
				{ 
					$filenameOk = false; $index = 0; //by default unless proven otherwise
					
					do 
					{
						if (!file_exists(DESTINATION_FOLDER  .  "/" . $_name_) ) 
							$filenameOk = true;
						else 
						{
							#break filename into components
							$temp = explode(".", $file['name']);
							$_name_ = "";
							
							#rejoin file components excluding file extension
							for ($c = 0; $c < count($temp) -1;$c++)
							{
									if ($c > 0) $_name_ .= ".";
									$_name_ .= $temp[$c];
							}

							#put counter at end of filename (just before the extension)
							$_name_ .= "_".++$index.".".$temp[count($temp)-1];
						}
					}
					while(!$filenameOk);
					
					if(@copy($_tmp_name_,DESTINATION_FOLDER  ."/". $_name_))
					{
						$uploadmsg .= $file['name']." uploaded successfully<br />";
						$documentNames[$key] =  $_name_;
					} 
					else 
					//file could not be copied for whatever reason e.g. disk is full
					{ 
						$uploadmsg .= "Copy not successful, ".$file['name']." NOT uploaded  ... Could be as a result of low disk space<br />";
					}
					//end if copy file successfully to destination
					
				} 
				
				if($file_has_error) 		//if error found
				{
					$uploadmsg .="Error! document ".$file['name']." NOT uploaded  ...<span style='uploadError'><code>$file_err</code></span><br />";
					
					## send error message
					return false;	
				}

				//end if empty($errStr)
			}
			
		}
		$this->documentPaths = $documentNames[0];
		$this->uploadmsg = $uploadmsg;
		
		return true;
	}
	
}

?>