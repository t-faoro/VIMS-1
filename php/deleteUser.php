<?php
	include_once "connection.php";
	$myCon = new Connection();
	$con = $myCon->connect();
	session_start();
	var_dump($_SESSION);
	mysqli_close($con);
?>