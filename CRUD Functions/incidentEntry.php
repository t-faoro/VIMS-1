<?php 
	
	/*================Incident Entry=============*/
		
	function incEntCreate($var, $time, $police, $content, $damages, $incidentLevel, $con) {
	
		$time 	  = mysqli_real_escape_string($con, $time);
		$content  = mysqli_real_escape_string($con, $content);
		$damages  = mysqli_real_escape_string($con, $damages);
								
		
		if(strlen($content) <= 0){
			$error =  "Error: Content cannot be left blank.";
			return $error;
		}
		
		$sql  = "INSERT INTO Incident_Entry ";
		$sql .= " (Var_VAR_ID, INE_Time, INE_Police, INE_Content, INE_Damages, Incident_Level_Lookup_ILL_Level)";
		$sql .= " VALUES (";
		$sql .= "  ".$var.",";
		$sql .= " '".$time."',";
		$sql .= "  ".$police.",";
		$sql .= " '".$content."',";
		$sql .= " '".$damages."', ";
		$sql .= "  ".$incidentLevel." ";
		$sql .= ")";

		return $sql;		
				
	}
		
	function incEntRead($ine_id, $con) {				
    	
    	$sql  = "SELECT INE_Time, INE_Police, INE_Content, INE_Damages, Incident_Level_Lookup_ILL_Level";
		$sql .= "FROM Incident_Entry";
		$sql .= "WHERE INE_ID = '".$ine_id."'";
				
		return $sql;
    			
	}
	
	
	function incEntUpdate($fieldName, $value, $ine_id, $reason_for_delete = NULL, $con) {
		
		$sql  = "UPDATE Incident_Entry";
		$sql .= "SET '".$fieldName."' = '".$value."' ";
		$sql .= "WHERE INE_ID = '".$ine_id."' ";
				
		
		if($reason_for_delete != NULL){
			//Hide the values, we never delete
		}
		
		return $sql;
		
	}


?>