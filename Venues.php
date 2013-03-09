<?php
/**
	By: Justin Werre
*/

include_once "php/config.php";
session_start();
if(!verifyUser()) header("Location: index.php");

$venName = '';
$venId = 'New';
$venUnit = '';
$venAddress = '';
$venCity = '';
$venProvince = '';
$venPost = '';
$venRegion = '';
$venPhone = '';
$contact = '';
$buttonText = 'Create';
$myCon = new Connection();

$con = $myCon->connect();
	
createHead();
createHeader(($_SESSION['userFname'])." ".$_SESSION['userLname']);
createNav($_SESSION['userAuth']);
echo "<div style='clear:both;'></div>\n";
echo "<div id ='content' style='color: white'>\n";

//Venue information form
echo "<form>\n";
echo "Venue: $venId<br />\n";
echo "<label>Name: </label>\n";
echo "<input type='text' value='$venName' />\n<br />\n";
echo "<label>Unit: </label>\n";
echo "<input type='text' value='$venUnit' />\n<br />\n";
echo "<label>Address: </label>\n";
echo "<input type='text' value='$venAddress' />\n<br />\n";
echo "<label>City: </label>\n";
echo "<input type='text' value='$venCity' />\n<br />\n";
echo "<label>Province: </label>\n";
echo "<input type='test' value='$venProvince' />\n<br />\n";
echo "<label>Postal Code: </label>\n";	
echo "<input type='text' value='$venPost' />\n<br />\n";
echo "<label>Region :</label>\n";
echo "<select>\n";
	$sql = 'SELECT * FROM Region;';
	$results = mysqli_query($con, $sql);
	foreach($results as $region)
	{
		//var_dump($region);
		echo "<option value='$region[REG_ID]'>$region[REG_Name]</option>\n";
	}
echo "</select>\n<br />\n";
echo "<label>Phone</label>\n";
echo "<input type='text' value='$venPhone' />\n<br />\n";
echo "<label>Contact: </label>\n";
echo "<input type='text' value='$contact' />\n<br />\n";
echo "<input type='submit' value='$buttonText' />\n";
echo "</form>\n";
	
echo "</div>\n";
createFoot();
?>