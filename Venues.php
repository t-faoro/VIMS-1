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
if(isset($_GET) && 'new' != $_GET['id'])
{
	$sql = venueRead($_GET['id']);
	$result = mysqli_query($con, $sql);
	$venInfo = mysqli_fetch_assoc($result);
	$venInfo['button'] = 'Save Changes';
}

//if post back create or update venue
if(isset($_POST['name']))
{
	$venue = array(
		'name'=>mysqli_real_escape_string($con, $_POST['name']),
		'unit'=>mysqli_real_escape_string($con, $_POST['unit']),
		'address'=>mysqli_real_escape_string($con, $_POST['address']),
		'city'=>mysqli_real_escape_string($con, $_POST['city']),
		'post'=>mysqli_real_escape_string($con, $_POST['post']),
		'phone'=>mysqli_real_escape_string($con, $_POST['phone']),
		'liasion'=>mysqli_real_escape_string($con, $_POST['liason']),
		'region'=>mysqli_real_escape_string($con, $_POST['region']),
	);
	if('' == $_POST['name'])
	{
		echo "error";
	}
	else if('New' == $_POST['id'])
	{
		$sql = venueCreate($venue, $con);
		mysqli_query($con, $sql);
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