<?php
/**
	By: Justin Werre
*/

include_once "php/config.php";
include_once "php/justinsFunctions.php";
session_start();
if(!verifyUser()) header("Location: index.php");

$venInfo = array('VEN_Name'=>'',
								 'VEN_ID'=>'New',
								 'VEN_Unit_Addr'=>'',
								 'VEN_St_Addr'=>'',
								 'VEN_City'=>'',
								 'prvince'=>'',
								 'VEN_Pcode'=>'',
								 'Region_REG_ID'=>'',
								 'VEN_Phone'=>'',
								 'VEN_Liason'=>'',
								 'button'=>'Create'
	);
$myCon = new Connection();

$con = $myCon->connect();

//Get existing data from the data base

if('new' != $_GET['id'])
{
	$sql = venueRead($_GET['id']);
	$result = mysqli_query($con, $sql);
	$venInfo = mysqli_fetch_assoc($result);
	$venInfo['button'] = 'Save Changes';
}

	
createHead();
createHeader(($_SESSION['userFname'])." ".$_SESSION['userLname']);
createNav($_SESSION['userAuth']);
echo "<div style='clear:both;'></div>\n";
echo "<div id ='content' style='color: white'>\n";

//Venue information form
createForm($venInfo);

	
echo "</div>\n";
createFoot();
mysqli_close($con);
?>