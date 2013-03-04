<?php
// ============================================================================
/*
 * varFunctions.php
 * file contains library of functions for interfacing with the table 'Var'
 * in the 'vims' database
 * Programed by James P. Smith March 2013
 * 
 * */
// ============================================================================
/*
 * List of functions & parameters
 * 
 * userRead($user [string], $pswd [string], $venue [int], $con [resource])
 * userCreate($username [string], $pswd [string], $Fname [string], $Lname [string], $currentUser [int], $con [resource])
 * userUpdate($field [string array], $content [string array], $username [string], $con [resource])
 * 
 * */

// ============================================================================
//								Functions
// ============================================================================
/* 
 *	varRead() builds an sql statement to search on var ID
 *	Parameters:
 *		$varID	var ID INTEGER
 *	Returns:
 *		$sql	string containing sql statement
 **/


function varRead($varID)
{
    
	// build statement
	$sql  = "SELECT * FROM var";
	$sql .= " WHERE VAR_ID=" . $varID;
	
	return $sql;
}
// ============================================================================
/* 
 *	varList() builds an sql statement to list users search on venueID between 
 * 		a specified start and end date. Orders results on decending date
 *	Parameters:
 *		$venueID   contains venue ID number INTEGER
 * 		$startDate contains date of earliest report being requested [string]
 * 		$endDate   contains date of latest report being requested [string]
 *	Returns:
 *		$sql	string containing sql statement
 **/


function varList($venueID, $startDate, $endDate)
{
	// build statement
	$sql  = "SELECT * FROM var";
	$sql .= " WHERE (VAR_Date >= '" . $startDate ."'";
	$sql .= " AND VAR_Date <= '" . $endDate . "'";
	$sql .= " ) ORDER BY VAR_Date DESC";

	
	return $sql;
}

// ============================================================================
/* 
 *	varCreate() builds an sql statement to create a new VAR record in the DB
 *	Parameters:
 *		$date		contains date of report value [datetime]
 * 		$attend		contains numeric value for attendace [int]
 * 		$sec_chklst contains true/false [int]
 * 		$supervisor	contains name of supervisor [string]
 * 		$event		contains name of event [string]
 * 		$venueID	contains venue ID [int]
 * 		$userID		contains user ID [int]
 * 		$con		contains database connection [resource]
 *	Returns:
 *		$sql	string containing sql statement
 **/


function varCreate($date, $attend, $sec_chklst, $supervisor, $event, $venueID, $userID, $con)
{
	// clean inputs
	$attend 	= mysqli_real_escape_string($con, $attend);
	$supervisor = mysqli_real_escape_string($con, $supervisor);
	$event		= mysqli_real_escape_string($con, $event);
	
	if(!is_numeric($attend)
		|| strlen($supervisor) > 45
		|| strlen($event) 	   > 45) return "error";	
	
	// build statement
	$sql  = "INSERT INTO var";
	$sql .= " (VAR_Date, VAR_Attend, VAR_Sec_Chklst, VAR_Supervisor,";
	$sql .= " VAR_Event, Venue_VEN_ID, User_USE_ID)";
	$sql .= " VALUES ('" . $date . "', " . $attend . ", ";
	$sql .= $sec_chklst . ", '" . $supervisor . "', '";
	$sql .= $event . "', " . $venueID . ", " . $userID . ")";

	
	return $sql;
}
// ============================================================================
/*
 *	varUpdate() builds an sql statement to update var details
 *	Parameters:
 *		$field	 array contains field to be changed [string]
 *		$content array contains new value [string]
 * 		$varID	 contains var ID [int]
 * 		$con	 connection resource [resource]
 *
 *	Returns:
 *		$sql	string containing sql statement
 **/
	function varUpdate($field, $content, $varID, $con)
	{
		// clean inputs
		foreach ($content as $key => $value) {
			$value = mysqli_real_escape_string($con, $value);
		}
		
		$length = count($field);
		
		// build sql
		$sql  = "UPDATE var SET ";
		for($i = 0; $i < $length; $i++)
		{
			if ($field[$i] == "Attend" && (!is_numeric($content[$i]))) return "error";
			if ($field[$i] == "Supervisor" && (strlen($content[$i] ) > 45)) return "error";
			if ($field[$i] == "Event" && (strlen($content[$i] ) > 45)) return "error";
			
			
			if($i != 0) $sql .= ",";
			
			$sql .= " VAR_" . $field[$i] . "='" . $content[$i] . "'";
		}
		
		$sql .= " WHERE VAR_ID=" . $varID;
		
		return $sql;
	}
?>