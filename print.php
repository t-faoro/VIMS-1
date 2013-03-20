<?php
/**
 * 
 * @author James P. Smith March 2013
 */
include_once "php/config.php";
include_once "php/classIncident.php";
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
$html  = "<!DOCTYPE html>\n";
$html .= "<html>\n";
$html .= "<head>\n";
$html .= "<title>Venue Information Management System</title>\n";
//$html .= "<link rel='stylesheet' type='text/css' href='./css/style.css'>\n";
$html .= "<link rel='stylesheet' type='text/css' href='./css/incident.css'>\n";
$html .= "<script src='//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js'></script>\n";
$html .= "<script src='//ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/jquery-ui.min.js'></script>\n";
$html .= "<script type='text/javascript' src='./js/print.js'></script>\n";
$html .= "<meta charset='UTF-8'>\n";
$html .= "</head>\n";
//==========================    Begin Content    =============================
$html .= "<body>\n";
$html .= "<div id='content'>\n";
$html  = "<div id='IncidentForm'>\n";
$html .= "<span class='print'>\n";
$html .= "Print page\n";
$html .= "</span>\n";
$html .= "</div>\n";
$html .= "</div>\n";
$html .= "</body>\n";
$html .= "</html>\n";
//=============================================================================
echo $html;
?>