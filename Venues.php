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
$error = '';
$users = array();
$myCon = new Connection();
$con = $myCon->connect();

//Get existing data from the data base
if(isset($_GET) && 'new' != $_GET['id'])
{
	$sql = venueRead($_GET['id']);
	$result = mysqli_query($con, $sql);
	$venInfo = mysqli_fetch_assoc($result);
	$venInfo['button'] = 'Save Changes';
	$sql = userList($_GET['id']);
	$users = mysqli_query($con, $sql);
}

//if post back create or update venue
if(isset($_POST['name']))
{
	$venue = array(
		$_POST['name'],
		$_POST['unit'],
		$_POST['address'],
		$_POST['city'],
		$_POST['post'],
		$_POST['phone'],
		$_POST['liason'],
		$_POST['region'],
	);
	if('' == $_POST['name'])
	{
		$error = "You must have a venue name.";
	}
	else if('New' == $_POST['id'])
	{	
		$sql = venueCreate($venue, $con);
		mysqli_query($con, $sql);
		echo mysqli_error($con);
		header('Location: manageVenues.php');
	}
	else
	{
		$fields = array(
			'Name',
			'Unit_Addr',
			'St_Addr',
			'City',
			'Pcode',
			'phone',
			'Liason',
			'Region_ID'
		);
		$sql = venueUpdate($fields, $venue, $_POST['id'], $con);
		mysqli_query($con, $sql);
		echo mysqli_error($con);
		header('Location: manageVenues.php');
	}
}
	
createHead('venues.css');
createHeader(($_SESSION['userFname'])." ".$_SESSION['userLname']);
createNav($_SESSION['userAuth']);
echo "<div class='clear' ></div>\n";
echo "<div id ='content'>\n";
echo "<div class='error'>$error</div>\n";
//Venue information form
createForm($venInfo);
if('New' != $venInfo['VEN_ID']) listUsers($users);

	
echo "</div>\n";
createFoot();
mysqli_close($con);
?>