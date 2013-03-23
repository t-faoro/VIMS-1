<?php
/*
	manage venues.php
	Purpose: a page from which administrators can select venues to modify, or access the create new venue page
	By: Justin Werre
	Date: March 19, 2013
*/

	//verify user has been authenticated, and create the neccessary page header.
	include_once "php/config.php";
	session_start();
	if(!verifyUser()) header("Location: index.php");
	createHead(null, "manageVenue.js");
	createHeader(($_SESSION['userFname'])." ".$_SESSION['userLname']);
	createNav($_SESSION['userAuth']);
	echo "<div style='clear:both; color: red;' id='error'></div>\n";
	echo "<div id ='content' style='color: white;'>\n";
	
	//Link to create a new venue
	//echo "<a href='Venues.php?id=new'><button>New venue</button></a>\n";
	
	//create table header
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
	echo "<th><a href='Venues.php?id=new'><button>New venue</button></a></th>\n";
	echo "<th></th>\n";
	echo "</tr>\n";

	//Display all venues
	foreach($result as $venue)
	{	
		$sql = "SELECT REG_Name from Region where REG_ID = $venue[Region_REG_ID];";
		$region = mysqli_query($con, $sql);
		$regName = mysqli_fetch_array($region);
		echo "<tr>\n";
		echo "<td>$venue[VEN_Name]</td>\n";
		echo "<td>$venue[VEN_City]</td>\n";
		echo "<td>$regName[REG_Name]</td>\n";
		echo "<td>$venue[VEN_Phone]</td>\n";
		echo "<td>\n";
			echo "<select id='$venue[VEN_ID]' onchange='updateStatus($venue[VEN_ID])'>\n";
				echo "<option value='1'>Active</option>\n";
				echo "<option value='0' ";
				if(0 == $venue['VEN_Status']) echo "selected";
				echo">Deactive</option>\n";
			echo "</select>\n";
		echo "</td>\n";
		echo "<td><a href='venues.php?id=$venue[VEN_ID]'><button>Modify</button></a></td>\n";
		echo "<td><div id='$venue[VEN_ID]UpdateStatus'></div></td>\n";
		echo "</tr>\n";
	}
	echo "</table>\n";
	mysqli_close($con);
	echo "</div>\n";
	createFoot();
?>