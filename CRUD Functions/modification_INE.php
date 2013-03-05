<?php 
		/*================Modification_INE=============*/
	
	function createModIne($ine_id, $ine_var_id, $user_id, $content, $con){
		
		$content = mysqli_real_escape_string($con, $content);
		
		$sql  = "INSERT INTO Modification_INE";
		$sql .= "(Incident_Entry_INE_ID, Incident_Entry_Var_VAR_ID, User_USE_ID, MOD_Action)";
		$sql .= "VALUES (";
		$sql .= " ".$ine_id.",";
		$sql .= " ".$ine_var_id.",";
		$sql .= " ".$user_id.",";
		$sql .= " ".$content." ";
		$sql .= ")";
		
		return $sql;
		
	}


?>