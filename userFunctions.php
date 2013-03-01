<?php

// ============================================================================
/* 
	userRead() builds and sql statement to search on username, password and 
		venue association for login
	Parameters:
		$user	contains login username STRING
		$pswd	contains login password STRING
		$venue	contains login venue INTEGER
	Returns:
		$sql	string containing sql statememnt
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
/*
	userCreate() builds and sql statement to insert a new user into the system
	Parameters:
		$username	 contains login username STRING
		$pswd		 contains login password STRING
		$Fname		 contains user's First Name STRING
		$Lname		 contains user's Last Name STRING
		$currentUser contains ID of user currently logged in INTEGER
	Returns:
		$sql	string containing sql statememnt
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
/*
	userUpdate() builds and sql statement to update user details
	Parameters:
		$field	contains filed to be changed STRING
		$user	contains user's login name STRING
		$value	contains new value STRING

	Returns:
		$sql	string containing sql statememnt
*/
function userUpdate($field, $value, $username, $con)
{
    //clean known input
    $username = mysqli_real_escape_string($con, $username);
	$sql  = "UPDATE user";
	switch($field)
	{
		case "Fname": $value = mysqli_real_escape_string($con, $value);
                      $sql .= " SET USE_Fname='" . $value . "'"; break;
		case "Lname": $value = mysqli_real_escape_string($con, $value);
                      $sql .= " SET USE_Lname='" . $value . "'"; break;
		default:      $sql .= " SET USE_Passwd='" . MD5($value) . "'";
	}
	
	$sql .= " WHERE USE_Name='" . $username ."'";
	
	return $sql;
}

// ============================================================================
/*
	userDelete()
*/
function userDelete($user, $venue)
{
	// Stub
}
?>