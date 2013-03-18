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
	if(!($result = mysqli_query($con, $sql)))
	{
		//create new association
		$sql1 = venue_user_asscCreate($venue, $user, 2);
		mysqli_query($con, $sql1);
	}
	else 
	{
		//update existing association VUA_Sys_Status
		$sql2 = venue_user_asscUpdate(array('VUA_Sys_Status'), array(1), $venue, $user);
		mysqli_query($con, $sql2);
	}
	
	$sqlfour = findUser($user, $venue);
	echo $sqlfour;
	//$result = mysqli_query($con, $sql);
	// var_dump($result);
	// echo createUserRow(mysqli_fetch_assoc($result), $venue);
	mysqli_close($con);
?>