<?php
	include_once "connection.php";
	$myCon = new Connection();
	$con = $myCon->connect();
	
	$sql = "SELECT * FROM User WHERE USE_Name = '$_POST[user]'"; 
	$result = mysqli_query($con, $sql);
	$result = mysqli_fetch_assoc($result);
	echo "$result[USE_Fname] $result[USE_Lname]";
	mysqli_close($con);
?>