<?php
// ============================================================================
/**
 * venueFunctions.php
 * file contains library of functions for interfacing with the table 'Venue'
 * in the 'vims' database
 * @author James P. Smith March 2013
 * 
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
 * 			VEN_Name
 * 			VEN_Unit_Addr
 *  		VEN_St_Addr
 * 			VEN_City
 * 			VEN_Pcode
 * 			VEN_Phone
 * 			VEN_Liason
 * 			Region_REG_ID
 * 	@param $con	database connection [resource]
 * 
 *	@return $sql	string containing sql statement
 */
function venueCreate($venueDetails, $con)
{
    // clean inputs
    foreach ($venueDetails as $key => $value) {
        $value = mysqli_real_escape_string($con, $value);
    }
    
    if((strlen($venueDetails[0]) > 45)
        || (strlen($venueDetails[1]) > 10)
        || (strlen($venueDetails[2]) > 45)
		|| (strlen($venueDetails[3]) > 25)
		|| (strlen($venueDetails[4]) > 7)
		|| (strlen($venueDetails[5]) > 12)
		|| (strlen($venueDetails[6]) > 45)
		|| (!is_numeric($venueDetails[7]))
        ) $sql = "error";

    else {
        // build sql string
	    $sql  = "INSERT INTO venue";
	    $sql .= " (VEN_Name, VEN_Unit_Addr, VEN_St_Addr, VEN_City,";
	    $sql .= " VEN_Pcode, VEN_Phone, VEN_Liason, Region_REG_ID)";
	    $sql .= " VALUES (";
	    for($i = 0; $i <= 7; $i++)
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
		if ($field[$i] == "Name" && (strlen($content[$i] ) > 45)) return "error";
		if ($field[$i] == "Unit_Addr" && (strlen($content[$i] ) > 10)) return "error";
		if ($field[$i] == "St_Addr" && (strlen($content[$i] ) > 45)) return "error";
		if ($field[$i] == "City" && (strlen($content[$i] ) > 25)) return "error";
		if ($field[$i] == "Pcode" && (strlen($content[$i] ) > 7)) return "error";
		if ($field[$i] == "Phone" && (strlen($content[$i] ) > 12)) return "error";
		if ($field[$i] == "Liason" && (strlen($content[$i] ) > 45)) return "error";
		if ($field[$i] == "Status" && (!is_numeric($content[$i]))) return "error";
		if ($field[$i] == "Can_Make_Owner" && (!is_numeric($content[$i]))) return "error";
		if ($field[$i] == "Region_ID" && (!is_numeric($content[$i]))) return "error";
		
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