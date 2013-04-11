<?php
	/**
		findUser.php
		Purpose: Searches database to find if a user exists
		@param POST['user'] the user name of the person to find
		@return returns the user's first and last name as a string if user is found,
			retuns no user found message if user is not found
	*/
	include_once "connection.php";
	$myCon = new Connection();
	$con = $myCon->connect();
	
	$sql = "SELECT * FROM User WHERE USE_Name = '$_POST[user]'"; 
	$result = mysqli_query($con, $sql);
	$result = mysqli_fetch_assoc($result);
	if(NULL == $result)
		echo 'false';
	else
		echo "$result[USE_Fname] $result[USE_Lname]";
	mysqli_close($con);
?>