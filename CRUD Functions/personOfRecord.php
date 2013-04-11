<?php 
		/*================Person of Record=============*/
	function porCreate($ine_id, $ine_var, $name, $phone, $licence, $notes, $involveLevel, $con){
		$name 		  =		realEscapeString($name);
		$phone 		  = 	realEscapeString($phone);
		$notes 		  = 	realEscapeString($notes);
		$involveLevel = 	realEscapeString($involveLevel);
		
		$sql  = "INSERT INTO Person_of_Record";
		$sql .= "(Incident_Entry_INE_ID, Incident_Entry_Var_VAR_ID, POR_Name, POR_Phone, POR_Licence, POR_Notes, Involvement_Lookup_INV_Level)";
		$sql .= "VALUES(";
		$sql .= " ".$ine_id.",";
		$sql .= " ".$ine_var.","; 
		$sql .= " ".$name.",";
		$sql .= " ".$phone.",";
		$sql .= " ".$licence.",";
		$sql .= " ".$notes.",";
		$sql .= " ".$involveLevel." ";
		$sql .= ")";
		
		return $sql;
	}
	
	function porRead($por_id, $con){
				
		$sql =  "SELECT * FROM Person_of_Record POR";
		$sql .= "WHERE POR_ID = ".$por_id." ";	
		
	}
	
	function porUpdate($fieldName, $value, $por_id, $reason_for_delete = NULL, $con ){
		$fieldName = mysqli_real_escape_string($con, $fieldName);
		$value = mysqli_real_escape_string($con, $value);
		$reason_for_delete = mysqli_real_escape_string($con, $reason_for_delete);
		
		$sql  = "UPDATE Person_of_Record";
		$sql .= " SET ".$fieldName."='".$value."'";
		$sql .= " WHERE POR_ID = ".$por_id." ";
		
		if($reason_for_delete != NULL){
			
			//Some code to hide the field when it is "Deleted"	
		}
		
		return $sql;
	}


?>