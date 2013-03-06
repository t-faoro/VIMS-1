<?php 

/**
 *	Reads data from the INE_User_Association Table
 *
 *	@param  $con The object with connection information to the database
 *	@return $sql The constructed SQL statement
*/
	
	function ineUserAsscRead($con){
		
		$sql  = "SELECT * ";
		$sql .= "FROM ine_user_assc";
		$sql .= "JOIN user ON ( user_use_id = use_id )";
		$sql .= "JOIN incident_entry ON ( incident_entry_ine_id = ine_id )";	
	}
	
	function ineUserAsscCreate($user_id, $ine_id, $var_id, $con){
		
		$sql  = "INSERT INTO Ine_User_Assc";
		$sql .= "(User_USE_ID, Incident_Entry_INE_ID, Incident_Entry_Var_VAR_ID)";
		$sql .= "VALUES (";
		$sql .= " ".$user_id.",";
		$sql .= " ".$ine_id.",";
		$sql .= " ".$var_id." ";
		$sql .= ")";
		
		return $sql;
		
	}
	
	function ineUserAsscUpdate($fieldName, $value, $user_id, $ine_id, $var_id ,$con){
		
		$sql  = "UPDATE Ine_User_Assc";
		$sql .= "SET '".$fieldName."' = '".$value."' ";
		$sql .= "WHERE User_USE_ID = ".$user_id." ";
		$sql .= "AND Incident_Entry_INE_ID = ".$ine_id." ";
		$sql .= "AND Incident_Entry_VAR_VAR_ID = ".$var_id." ";
		
		return $sql;
	}
	


?>