<?php
// ============================================================================
/*
 * venue_user_asscFunctions.php
 * file contains library of functions for interfacing with the table
 * 'Venue_user_assc' in the 'vims' database
 * Programmed by James P. Smith March 2013
 * 
 * */
// ============================================================================
/*
 * List of functions & parameters
 * 
 * 	venue_user_asscCreate($venue [int], $user [int], $auth [int])
 *  venue_user_asscUpdate($field [string array], $content [string array], $venueID [int], $userID [int])
 * 
 * */

// ============================================================================
//								Functions
// ============================================================================
/** 
  *	venue_user_asscCreate()) builds an sql statement to create an entry in the
  * 			venue_user_assc table
  *	@param
  *		$venue	contains venue ID [int]
  * 		$user	contains user ID [int]
  * 		$auth	containt auth level [int]
  * 		$con 	connection [resource]
  *	@return
  *		$sql	string containing sql statement
  **/


function venue_user_asscCreate($venue, $user, $auth)
{
	// build statement
	$sql  = "INSERT INTO venue_user_assc";
	$sql .= " (Venue_VEN_ID, User_USE_ID, Auth_Level_Lookup_AUT_Level)";
	$sql .= " VALUES";
	$sql .= " (" . $venue . ", " . $user . ", "  . $auth . ")";
	
	return $sql;
}

// ============================================================================
/**
  *	venue_user_asscUpdate() builds an sql statement to update this association
  * 		table in the 'vims' database
  *	@param
  *		$field		 array contains field names for updating [string]
  *		$content	 array contains new values [string]
  *		$venueID	 contains venue ID [int]
  *		$userID	 	 contains user ID [int]
  * 	$con		 database connection [resource]]
  *	@return
  *		$sql	string containing sql statement
  **/
function venue_user_asscUpdate($field, $content, $venueID, $userID)
{

	//buils sql string
    $sql  = "UPDATE venue_user_assc SET";
	
	$length = count($field);
	
	// loop through arrays
	for($i = 0; $i < $length; $i++)
	{
		if($i != 0) $sql .= ",";
		
		if($field[$i] != "AUT_Level")
		{
			$sql .= " VUA_" . $field[$i] . "='" . $content[$i] . "'";
		}
		else
		{
			$sql .= " Auth_Level_Lookup_AUT_Level='" . $content[$i] . "'";
		}
	}
	
	$sql .= " WHERE (Venue_VEN_ID='" . $venueID . "'";
	$sql .= " AND User_USE_ID='" . $userID . "')";
	
	echo $sql;
	return $sql;
}

?>