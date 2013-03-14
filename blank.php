<?php
/**
 * 
 * @author James P. Smith March 2013
 */
include_once "php/config.php";
session_start();
if(!verifyUser()) header('location:index.php');
$myCon = new Connection();

//*****************************************************************************
// get variables
$userID  = $_SESSION['userId'];
$userName = $_SESSION['userName'];
$userFname = $_SESSION['userFname'];
$userLname = $_SESSION['userLname'];
$userAuth = $_SESSION['userAuth'];
$venueID = $_SESSION['venueId'];
$venueName = $_SESSION['venueName'];
$fullName = $userFname . " " . $userLname;
date_default_timezone_set('UTC');
$date = date('Y-m-d H:i:s', time());
					
//*****************************************************************************
createHead('incident.css');
createHeader($fullName);
createNav($userAuth);

//==========================    Begin Content    =============================
echo "<div id='content'>\n";

echo "</div>\n";
//=============================================================================
createFoot();
?>