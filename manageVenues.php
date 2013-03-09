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
	echo "<div style='clear:both;'></div>\n";
	echo "<div id ='content' style='color: white;'>\n";
	
	//Link to create a new venue
	echo "<a href='Venues.php?id=new'><button>New venue</button></a>\n";
	
	//create table of existing venues
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
	echo "</tr>\n";
	foreach($result as $venue)
	{
		//var_dump($venue);
		$sql = "SELECT REG_Name from Region where REG_ID = $venue[Region_REG_ID];";
		$region = mysqli_query($con, $sql);
		$regName = mysqli_fetch_array($region);
		echo "<tr>\n";
		echo "<td>$venue[VEN_Name]</td>\n";
		echo "<td>$venue[VEN_City]</td>\n";
		echo "<td>$regName[REG_Name]</td>\n";
		echo "<td>$venue[VEN_Phone]</td>\n";
		echo "<td>\n";
			echo "<select>\n";
				echo "<option value='1'>Active</option>\n";
				echo "<option value='0' ";
				if(0 == $venue['VEN_Status']) echo "selected";
				echo">Deactive</option>\n";
			echo "</select>\n";
		echo "</td>\n";
		echo "<td><a href='venues.php?id=$venue[VEN_ID]'><button>Modify</button></a></td>\n";
		echo "</tr>\n";
	}
	echo "</table>\n";
	mysqli_close($con);
	
	echo "</div>\n";
	createFoot();
	
?>