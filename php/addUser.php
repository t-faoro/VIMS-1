<?php
	/**
		addUser.php
		Purpose: Creates or updates an association between an existing user, and a venue, granting them
			access to the system.
		@param $_POST['user'] the user name of the user to be added
		@param $_POST['venue'] the venue id number which the user is to be joined with
		@return a html table row with input boxes for the user name, first name, last name, authorization level, 
			delete button, and save changes button
	*/
	
	include_once "connection.php";
	include_once "justinsFunctions.php";
	include_once "venue_user_asscFunctions.php";
	include_once "userFunctions.php";
	$myCon = new Connection();
	$con = $myCon->connect();
	session_start();

	//get user information
	$sql = "SELECT * FROM User WHERE USE_Name = '$_POST[user]'"; 
	$result = mysqli_query($con, $sql);
	$result = mysqli_fetch_assoc($result);
	$user = $result['USE_ID'];
	$venue = $_POST['venue'];
	$sql = findUser($user, $venue);

	//Associate user with venue
	if(NULL == mysqli_fetch_assoc(mysqli_query($con, $sql)))
	{
		//create new association
		$sql = venue_user_asscCreate($venue, $user, 2);
		mysqli_query($con, $sql);
	}
	else 
	{
		//update existing association VUA_Sys_Status
		$sql = venue_user_asscUpdate(array('Sys_Status'), array(1), $venue, $user);
		mysqli_query($con, $sql);
	}
	
	//Create and return table row
	$sql = findUser($user, $venue);
	$result = mysqli_query($con, $sql);
	echo createUserRow(mysqli_fetch_assoc($result), $venue, $_SESSION['userAuth']);
	mysqli_close($con);
?>