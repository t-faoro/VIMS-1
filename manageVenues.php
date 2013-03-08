<?php
/*
	manage venues.php
	By: Justin Werre
	
*/

	include_once "php/config.php";
	session_start();
	if(!verifyUser()) header("Location: index.php");
	
	createHead();
	createHeader(($_SESSION['userFname'])." ".$_SESSION['userLname']);
	createNav($_SESSION['userAuth']);
	echo "<div ></div>\n";
	echo "<div id ='content'>\n";
	
	$myCon = new Connection();
	$con = $myCon->connect();
	$sql = "select * from venue;";
	$result = mysqli_query($con,$sql);
	echo "<table>\n";
	echo "<tr>\n";
	echo "<th>Venue Name</th>\n";
	echo "<th>City</th>\n";
	echo "<th>Region</th>\n";
	echo "<th>Phone</th>\n";
	echo "<th>Status</th>\n";
	echo "<th></th>\n";
	
	echo "</div>\n";
	createFoot();
	
?>