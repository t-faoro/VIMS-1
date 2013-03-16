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
		mysqli_close($con);
		return 0;
	}
	else
	{
		$id = mysqli_insert_id($con);
		$sql = venue_user_asscCreate($_POST['venue'], $id, $_POST['auth']);
		mysqli_query($con, $sql);
		$user = mysqli_query($con, findUser($id, $_POST['venue']));
		mysqli_close($con);
		echo createUserRow(mysqli_fetch_assoc($user), $_POST['venue']);
	}
?>