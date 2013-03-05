<?php 
		/*================Incident Level Lookup=============*/
	function illRead($con){
		
		$sql = "SELECT * ";
		$sql .= "FROM Incident_Level_Lookup";
		
		return $sql;
		
	}


?>