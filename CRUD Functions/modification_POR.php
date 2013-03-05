<?php 
	function createModPor($user_id, $por_id, $content, $con){
		
		$content = mysqli_real_escape_string($con, $content);
		
		$sql  = "INSERT INTO Modification_POR";
		$sql .= "(User_USE_ID, Person_of_Record_POR_ID, MOD_Action)";
		$sql .= "VALUES (";
		$sql .= " ".$user_id.",";
		$sql .= " ".$por_id.",";
		$sql .= " ".$content.",";
		$sql .= ")";
		
		return $sql;
		
	} 


?>