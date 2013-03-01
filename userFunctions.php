<?php
// ============================================================================
/*
 * userFunctions.php
 * file contains library of functions for interfacing with the table 'User'
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
 *	userRead() builds and sql statement to search on username, password and 
 *		venue association for login
 *	Parameters:
 *		$user	contains login username STRING
 *		$pswd	contains login password STRING
 *		$venue	contains login venue INTEGER
 * 		$con 	connection resource
 *	Returns:
 *		$sql	string containing sql statememnt
 **/


function userRead($user, $pswd, $venue, $con)
{
    // clean inputs
	$user  = mysqli_real_escape_string($con, $user);
    $venue = mysqli_real_escape_string($con, $venue);
    
	// build statement
	$sql  = "SELECT * FROM user";
	$sql .= " JOIN venue_user_assc";
	$sql .= " ON (user.USE_ID = venue_user_assc.user_USE_ID)";
	$sql .= " WHERE user.USE_Name='" . $user ."'";
	$sql .= " AND user.USE_Passwd='" . MD5($pswd) . "'";
	$sql .= " AND venue_user_assc.venue_VEN_ID=" . $venue . "";
	
	return $sql;
}
// ============================================================================
/*
 *	userCreate() builds and sql statement to insert a new user into the system
 *	Parameters:
 *		$username	 contains login username STRING
 *		$pswd		 contains login password STRING
 *		$Fname		 contains user's First Name STRING
 *		$Lname		 contains user's Last Name STRING
 *		$currentUser contains ID of user currently logged in INTEGER
 * 		$con		 connection resource
 *	Returns:
 *		$sql	string containing sql statememnt
 **/
function userCreate($username, $pswd, $Fname, $Lname, $currentUser, $con)
{
    // clean inputs
    $username    = mysqli_real_escape_string($con, $username);
    $Fname       = mysqli_real_escape_string($con, $Fname);
    $Lname       = mysqli_real_escape_string($con, $Lname);
    
    if((strlen($Fname) > 45)
        || (strlen($Lname) > 45)
        || (strlen($username) > 25)
        ) $sql = "error";

    else {
        // build sql string
	    $sql  = "INSERT INTO user";
	    $sql .= " (USE_Name, USE_Passwd, USE_Fname, USE_Lname, USE_Creator)";
	    $sql .= " VALUES (";
	    $sql .= " '" . $username . "',";
	    $sql .= " '" . MD5($pswd) . "',";
	    $sql .= " '" . $Fname . "',";
	    $sql .= " '" . $Lname . "',";
	    $sql .= " " . $currentUser . "";
	    $sql .= ")";
    }
	
	return $sql;
}

// ============================================================================
/*
 *	userUpdate() builds and sql statement to update user details
 *	Parameters:
 *		$field	  array contains field to be changed STRING
 *		$content  array contains new value STRING
 * 		$username contains user's login name STRING
 * 		$con	  connection resource
 *
 *	Returns:
 *		$sql	string containing sql statememnt
 **/
function userUpdate($field, $content, $username, $con)
{
	//buils sql string
    $sql  = "UPDATE user SET";
	$length = count($field);
	
	// loop through arrays
	for($i = 0; $i < $length; $i++)
	{
		if($i != 0) $sql .= ",";
		if($field[$i] != "Passwd")
		{
			// clean non-password inputs
			$content[$i] = mysqli_real_escape_string($con, $content[$i]);
			if(strlen($content[$i]) > 45) return "error";
			$sql .= " USE_" . $field[$i] . "='" . $content[$i] . "'";
		}
		else 
		{
			$sql .= " USE_" . $field[$i] . "='" . MD5($content[$i]) . "'";
		}
	}
	
	$sql .= " WHERE USE_Name='" . $username ."'";
	
	return $sql;
}

// ============================================================================
?>