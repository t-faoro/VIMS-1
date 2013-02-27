<?php

// ============================================================================
/* 
	userRead() builds and sql statement to search on username, password and 
		venue association for login
	Parameters:
		$user	contains login username STRING
		$pswd	contains login password STRING
		$venue	contains login venue STRING
	Returns:
		$sql	string containing sql statememnt
*/


function userRead($user, $pswd, $venue)
{
	// build statement
	$sql .= "SELECT * FROM user";
	$sql .= " JOIN venue_user_assc";
	$sql .= " ON (user.USE_ID = venue_user_assc.user_USE_ID)";
	$sql .= " WHERE user.USE_Name='" . $user ."'";
	$sql .= " AND user.USE_Passwd='" . MD5($pswd) . "'";
	$sql .= " AND venue_user_assc.venue_VEN_ID='" . $venue . "'";
	
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
function userCreate($username, $pswd, $Fname, $Lname, $currentUser)
{
	$sql .= "INSERT INTO user";
	$sql .= " (USE_Name, USE_Passwd, USE_Fname, USE_Lname, USE_Creator)";
	$sql .= " VALUES (";
	$sql .= " '" . $username . "',";
	$sql .= " '" . MD5($pswd) . "',";
	$sql .= " '" . $Fname . "',";
	$sql .= " '" . $Lname . "',";
	$sql .= " " . $currentUser . "";
	$sql .= ")";
	
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
function userUpdate($field, $value, $user)
{
	$sql .= "UPDATE user";
	switch($field)
	{
		case "Fname": $sql .= " SET USE_Fname='" . $value . "'"; break;
		case "Lname": $sql .= " SET USE_Lname='" . $value . "'"; break;
		default: $sql .= " SET USE_Passwd='" . MD5($value) . "'";
	}
	
	$sql .= " WHERE USE_Name='" . $user ."'";
	
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