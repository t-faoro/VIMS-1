<?php
/**
	By: Justin Werre
*/

include_once "php/config.php";
session_start();
if(!verifyUser()) header("Location: index.php");

$venName = '';
$venId = '';
$venUnit = '';
$venAddress = '';
$venProvince = '';
$venRegion = '';
$venPhone = '';
$contact = '';
	
createHead();
createHeader(($_SESSION['userFname'])." ".$_SESSION['userLname']);
createNav($_SESSION['userAuth']);
echo "<div style='clear:both;'></div>\n";
echo "<div id ='content' style='color: white'>\n";

//Venue information form
echo "<form>\n";
echo "Venue: $venId<br />\n";
echo "<lable>Name:</lable>\n";
echo "<input type='text' value='$venName' />\n<br />\n";
echo "<lable>Unit:</lable>\n";
echo "<input type='text' value='$venUnit' />\n<br />\n";
echo "<label>Address: </label>\n";
echo "input type='text' value='$venAddress' />\n<br />\n";
echo "</form>\n";
	
echo "</div>\n";
createFoot();
?>