<?php
	/*
		CreateUser.php
		Purpose: creates a new user and joins them to a existing venue
		@param POST['user'] user name for the new user
		@param POST['first'] user's first name
		@param POST['last'] users' last name
		@param POST['venue'] the venue id which the user is to be joined with
		@param POST['auth'] the users authorization level for the venue
		@return returns the user name, user's first name, last name, and authorization level as a table row
			returns a error if user can not be created
	*/
	include_once "connection.php";
	include_once "userFunctions.php";
	include_once "venue_user_asscFunctions.php";
	include_once "justinsFunctions.php";
	session_start();
	$myCon = new Connection();
	$con = $myCon->connect();
	
	//Create the user and join them to a venue, returning a table row on success, and a error message on failure.
	$sql = userCreate($_POST['user'], 'password', $_POST['first'], $_POST['last'], $_SESSION['userId'], $con);
	if(!mysqli_query($con, $sql))
	{
		$error = mysqli_error($con);
		mysqli_close($con);
		echo "false";
	}
	else
	{
		$id = mysqli_insert_id($con);
		$sql = venue_user_asscCreate($_POST['venue'], $id, $_POST['auth']);
		mysqli_query($con, $sql);
		$user = mysqli_query($con, findUser($id, $_POST['venue']));
		echo createUserRow(mysqli_fetch_assoc($user), $_POST['venue']);
		mysqli_close($con);
	}		
?>