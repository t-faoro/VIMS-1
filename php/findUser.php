<?php
	include_once "connection.php";
	$myCon = new Connection();
	$con = $myCon->connect();
	
	$sql = "SELECT * FROM User WHERE USE_Name = '$_POST[user]'"; 
	echo $sql;
	//$result = mysqli_query($con, $sql);
	//echo mysqli_fetch_assoc($result);
	mysqli_close($con);
	//var_dump($_POST);
?>