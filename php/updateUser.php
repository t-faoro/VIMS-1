<?php
	/*
		purpose: updates user name, first name, last name and authorization for a particular user and venue.
		By: Justin Werre
		@param POST contains the following indexes:
			[name] string the user name
			[first] string the users first name
			[last] string the users last name
			[id] interger the users id number
			[auth] interger the users authorization level
			[venue] interger the venue number
	*/
	include_once("connection.php");
	$myCon = new Connection();
	$con = $myCon->connect();
	
	//update user table
	$sql = "UPDATE user SET ";
	$sql .= "USE_Name = '$_POST[name]', ";
	$sql .= "USE_Fname = '$_POST[first]', ";
	$sql .= "USE_Lname = '$_POST[last]' ";
	$sql .= "WHERE USE_ID = $_POST[id]";
	mysqli_query($con, $sql);
	
	//update user authorization level
	$sql = "UPDATE venue_user_assc SET ";
	$sql .= "Auth_Level_Lookup_AUT_Level = $_POST[auth] ";
	$sql .= "WHERE User_USE_ID = $_POST[id] ";
	$sql .= "AND Venue_VEN_ID = $_POST[venue]";
	mysqli_query($con, $sql);
	
?>