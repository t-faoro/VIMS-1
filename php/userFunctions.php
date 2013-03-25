<?php
// ============================================================================
/**
 * userFunctions.php
 * file contains library of functions for interfacing with the table 'User'
 * in the 'vims' database
 * @author James P. Smith March 2013
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
/** 
 *	userRead() builds an sql statement to search on username, password and 
 *		venue association for login
 *	@param $user	contains login username [string]
 *	@param $pswd	contains login password [string]
 *	@param $venue	contains login venue [int]
 * 	@param $con 	database connection [resource]
 * 
 *	@return $sql	string containing sql statement
 */


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
/** 
 *	userList() builds an sql statement to list users search on venueID
 *	@param $venueID contains venue ID number [int]
 * 
 *	@return $sql	string containing sql statement
 */


function userList($venueID)
{
	// build statement
	$sql  = "SELECT * FROM user";
	$sql .= " JOIN venue_user_assc";
	$sql .= " ON (user.USE_ID = venue_user_assc.user_USE_ID)";
	$sql .= " WHERE venue_user_assc.venue_VEN_ID=" . $venueID . "";
	$sql .= " AND VUA_Sys_Status = 1";
	$sql .= " ORDER BY venue_user_assc.Auth_Level_Lookup_AUT_Level";

	return $sql;
}

/*
	ownerList() builds an sql statement to list active owners of a venue
	@param $venueID the venue ID number
	@return string containing sql select statment
*/
function ownerList($venueID)
{
	// build statement
	$sql  = "SELECT count(*) FROM user";
	$sql .= " JOIN venue_user_assc";
	$sql .= " ON (user.USE_ID = venue_user_assc.user_USE_ID)";
	$sql .= " WHERE venue_user_assc.venue_VEN_ID=" . $venueID;
	$sql .= " AND venue_user_assc.Auth_Level_Lookup_AUT_Level = 1";
	$sql .= " AND VUA_Sys_Status = 1";
	$sql .= " ORDER BY venue_user_assc.Auth_Level_Lookup_AUT_Level";

	return $sql;
}

function findUser($userID, $venueID)
{
	// build statement
	$sql  = "SELECT * FROM user";
	$sql .= " JOIN venue_user_assc";
	$sql .= " ON (user.USE_ID = venue_user_assc.user_USE_ID)";
	$sql .= " WHERE venue_user_assc.venue_VEN_ID=" . $venueID ;
	$sql .= " AND venue_user_assc.user_USE_ID = $userID";
	$sql .= " ORDER BY venue_user_assc.Auth_Level_Lookup_AUT_Level";

	return $sql;
}
// ============================================================================
/**
 *	userCreate() builds an sql statement to insert a new user into the system
 *	@param $username	 contains login username [string]
 *	@param $pswd		 contains login password [string]
 *	@param $Fname		 contains user's First Name [string]
 *	@param $Lname		 contains user's Last Name [string]
 *	@param $currentUser contains ID of user currently logged in [int]
 * 	@param $con		 database connection [resource]
 * 
 *	@return $sql	string containing sql statement
 */
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
/**
 *	userUpdate() builds an sql statement to update user details
 *	@param $field	  array contains field to be changed [string]
 * 			field with value of Passwd will cause $content of corresponding 
 * 			index to be hashed using MD5
 *	@param $content  array contains new value [string]
 * 	@param $username contains user's login name [string]
 * 	@param $con	  database connection [resource]
 *
 *	@return $sql	string containing sql statement
 */
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

?>