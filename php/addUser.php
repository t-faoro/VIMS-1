<?php
	include_once "connection.php";
	include_once "justinsFunctions.php";
	include_once "venue_user_asscFunctions.php";
	include_once "userFunctions.php";
	
	$myCon = new Connection();
	$con = $myCon->connect();

	//get user information
	$sql = "SELECT * FROM User WHERE USE_Name = '$_POST[user]'"; 
	$result = mysqli_query($con, $sql);
	$result = mysqli_fetch_assoc($result);
	$user = $result['USE_ID'];
	$venue = $_POST['venue'];
	$sql = findUser($user, $venue);
	// var_dump(mysqli_fetch_assoc(mysqli_query($con, $sql)));
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
	
	// var_dump($sql);
	$sql = findUser($user, $venue);
	$result = mysqli_query($con, $sql);
	// var_dump($result);
	echo createUserRow(mysqli_fetch_assoc($result), $venue);
	mysqli_close($con);
?>