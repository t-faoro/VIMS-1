<?php
	include_once "php/config.php";
	session_start();
	if(!verifyUser()) header("Location: index.php");
	
	//get regions
	$myCon = new Connection();
	$con = $myCon->connect();
	$sql = "SELECT * FROM Region";
	$results = mysqli_query($con, $sql);
	
	//show the page
	createHead("regions.css");
	createHeader($_SESSION['userName']);
	createNav($_SESSION['userAuth']);
	echo "<div id='content'>\n";
	echo "<table>\n";
	echo "<tr>\n";
	echo "<th>Region Name</th>\n";
	echo "<th><a href='regions.php?id=new'><button>New Region</button></a></th>\n";
	echo "</tr>\n";
	foreach($results as $region)
	{
		echo "<tr>\n";
		echo "<td>$region[REG_Name]</td>\n";
		echo "<td><a href='regions.php?id=$region[REG_ID]'><button>Modify</button></td>\n";
		echo "<tr>\n";
	}
	echo "</table>\n";
	echo "</div>";
	
	createFoot();
?>