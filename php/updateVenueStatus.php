<?php
	include_once "connection.php";
	include_once "venueFunctions.php";
	$field = array("Status");
	$status = array($_POST['status']);
	$myCon = new Connection();
	$con = $myCon->connect();
	$sql = venueUpdate($field, $status, $_POST['id'], $con);
	mysqli_query($con, $sql);
	mysqli_close($con);
?>