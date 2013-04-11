<?php
// ============================================================================
/**
 * venueFunctions.php
 * file contains library of functions for interfacing with the table 'Venue'
 * in the 'vims' database
 * @author James P. Smith March 2013
 * Modified by: Justin Werre Marck 23, 2013
 *   Added better error conditions
 * */
// ============================================================================
/*
 * List of functions & parameters
 * 
 *	venueRead($venID [int])
 * 	venueList($regID [int])
 *  venueCreate($venueDetails [string array], $con [resource])
 *  venueUpdate($field [string array], $content [string array], $venueID [int],
 * 		 $con [resource])
 * 
 * */

// ============================================================================
//								Functions
// ============================================================================
/** 
 *	userRead() builds an sql statement to search on VEN_ID 
 *	@param $venID	contains venue ID number [int]
 * 
 *	@return $sql	string containing sql statement
 */


function venueRead($venID)
{
	// build statement
	$sql  = "SELECT * FROM venue";
	$sql .= " WHERE VEN_ID=" . $venID ."";

	return $sql;
}

// ============================================================================
/** 
 *	venueList() builds an sql statement to list venues search on region ID
 *	@param $regID	contains region ID number [int]
 * 
 *	@return $sql	string containing sql statement
 */


function venueList($regID)
{
	// build statement
	$sql  = "SELECT * FROM venue";
	$sql .= " WHERE VEN_ID=" . $regID ."";
	$sql .= " ORDER BY VEN_Name";

	return $sql;
}
// ============================================================================
/**
 *	venueCreate() builds an sql statement to insert a new venue into the system
 *	@param $venueDetails	array[7] contains required fields in order of:
 * 			[0]VEN_Name
 * 			[1]VEN_Unit_Addr
 *  		[2]VEN_St_Addr
 * 			[3]VEN_City
 *			[4]VEN_Province
 * 			[5]VEN_Pcode
 * 			[6]VEN_Phone
 * 			[7]VEN_Liason
 *      [8]VEN_Can_Make_Owner
 * 			[9]Region_REG_ID
 * 	@param $con	database connection [resource]
 * 
 *	@return $sql	string containing sql statement
 */
function venueCreate($venueDetails, $con)
{
    // clean inputs
    foreach ($venueDetails as &$value) {
        $value = mysqli_real_escape_string($con, $value);
    }
    
		//perform error checking
         if(strlen($venueDetails[0]) > 45) $sql = "Error: Venue name is too long. Must be less than 45 characters.";
    else if(strlen($venueDetails[1]) > 10) $sql = "Error: Venue unit address is too long. Must be less than 10 characters.";
    else if(strlen($venueDetails[2]) > 45) $sql = "Error: Venue address is too long. Must be less than 45 characters.";
		else if(strlen($venueDetails[3]) > 25) $sql = "Error: Venue city is too long. Must be less than 45 characters.";
		else if(strlen($venueDetails[5]) > 7)  $sql = "Error: Venue postal code is too long. Must be less than 7 characters.";
		else if(strlen($venueDetails[6]) > 12) $sql = "Error: Venue phone number is too long. Must be less than 12 characters.";
		else if(strlen($venueDetails[7]) > 45) $sql = "Error: Venue contact is too long. Must be less than 45 characters.";
		else if(!is_numeric($venueDetails[9])) $sql = "Error: Venue region must be a number.";
    else {
        // build sql string
	    $sql  = "INSERT INTO venue";
	    $sql .= " (VEN_Name, VEN_Unit_Addr, VEN_St_Addr, VEN_City, VEN_Province,";
	    $sql .= " VEN_Pcode, VEN_Phone, VEN_Liason, VEN_Can_Make_Owner, Region_REG_ID)";
	    $sql .= " VALUES (";
	    for($i = 0; $i <= 9; $i++)
		{
			if($i != 0) $sql .= ", ";
			$sql .= "'" . $venueDetails[$i] . "'";
		}
	    $sql .= ")";
    }
	
	return $sql;
}

// ============================================================================
/**
 *	userUpdate() builds an sql statement to update user details
 *	@param $field	 array contains field to be changed [string]
 *	@param $content  array contains new value [string]
 * 	@param $venueID  contains venue ID [int]
 * 	@param $con	  	 connection [resource]
 *
 *	@return $sql	string containing sql statement
 */
function venueUpdate($field, $content, $venueID, $con)
{	
	$length = count($field);
	// clean inputs
    for($i = 0; $i < $length; $i++)
	{
		$content[$i] = mysqli_real_escape_string($con, $content[$i]);
	}
	
	//buils sql string
    $sql  = "UPDATE venue SET";

	// loop through arrays
	for($i = 0; $i < $length; $i++)
	{
		if ($field[$i] == "Name" && (strlen($content[$i] ) > 45)) return "Error: Venue name is too long. Must be less than 45 characters.";
		if ($field[$i] == "Unit_Addr" && (strlen($content[$i] ) > 10)) return "Error: Venue unit address is too long. Must be less than 10 characters.";
		if ($field[$i] == "St_Addr" && (strlen($content[$i] ) > 45)) return "Error: Venue address is too long. Must be less than 45 characters.";
		if ($field[$i] == "City" && (strlen($content[$i] ) > 25)) return "Error: Venue city is too long. Must be less than 45 characters.";
		if ($field[$i] == "Pcode" && (strlen($content[$i] ) > 7)) return "Error: Venue postal code is too long. Must be less than 7 characters.";
		if ($field[$i] == "Phone" && (strlen($content[$i] ) > 12)) return "Error: Venue phone number is too long. Must be less than 12 characters.";
		if ($field[$i] == "Liason" && (strlen($content[$i] ) > 45)) return "Error: Venue contact is too long. Must be less than 45 characters.";
		if ($field[$i] == "Status" && (!is_numeric($content[$i]))) return "Error: Venue status must be a number.";
		if ($field[$i] == "Can_Make_Owner" && (!is_numeric($content[$i]))) return "Error: Venue create owner permision must be a number.";
		if ($field[$i] == "Region_ID" && (!is_numeric($content[$i]))) return "Error: Venue region id must be a number";
		
		if($i != 0) $sql .= ",";
		
		if($field[$i] != "Region_ID")
		{
			$sql .= " VEN_" . $field[$i] . "='" . $content[$i] . "'";
		}
		else
		{
			$sql .= " Region_REG_ID='" . $content[$i] . "'";
		}
	}
	
	$sql .= " WHERE VEN_ID='" . $venueID ."'";
	
	return $sql;
}

// ============================================================================
?>