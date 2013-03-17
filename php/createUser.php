<?php
	include_once "connection.php";
	include_once "userFunctions.php";
	include_once "venue_user_asscFunctions.php";
	include_once "justinsFunctions.php";
	session_start();
	$myCon = new Connection();
	$con = $myCon->connect();
	
	$sql = userCreate($_POST['user'], 'password', $_POST['first'], $_POST['last'], $_SESSION['userId'], $con);
	if(!mysqli_query($con, $sql))
	{
		$error = mysqli_error($con);
		mysqli_close($con);
		return $error;
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