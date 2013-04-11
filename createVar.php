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
createHead('createVar.css', 'datepicker.js');
createHeader($fullName);
createNav($userAuth);
//*****************************************************************************
// check variables
if(isset($_GET['reportDate'])) $reportDate = $_GET['reportDate'];
else $reportDate = date('Y-m-d', strtotime($date));
if(isset($_GET['event'])) 	   $event 	   = $_GET['event'];
else $event		= "Reg. Op.";
if(isset($_GET['attendance'])) $attendance = $_GET['attendance'];
else $attendance = null;
if(isset($_GET['supervisor'])) $supervisor = $_GET['supervisor'];
else $supervisor = $fullName;
if(isset($_GET['sec_chklst'])) $sec_chklst = $_GET['sec_chklst'];
else $sec_chklst = 1;

if(isset($_GET['action']))     $action 	   = $_GET['action'];
else $action = null;
if($action == 'Finished'){
	$con = $myCon->connect();
	$sql = varCreate($reportDate, $attendance, $sec_chklst, $supervisor, $event, $venueID, $userID, $con);
	$result = mysqli_query($con, $sql);
	header('location: manageReports.php');
}

//*****************************************************************************
//==========================    Begin Content    =============================
echo "<div id='content'>\n";
echo "	<h3>Venue Activity Report</h3>\n";

$html  = "	<div id='VarInputForm'>\n";
$html .= "		<form action='createVar.php' method='GET'>\n";
$html .= "			<table id='VarInputFormTable'>";
$html .= "				<tr>\n";
$html .= "					<td>Submitting for the night of:</td>\n";
$html .= "					<td><input type='text' id='datepicker' name='reportDate'";
$html .= " value='" . $reportDate . "'></td>\n";
$html .= "				</tr>\n";
$html .= "				<tr>\n";
$html .= "					<td>Event:</td>\n";
$html .= "					<td><input type='text' id='event' name='event'";
$html .= " value='" . $event . "'></td>\n";
$html .= "				</tr>\n";
$html .= "				<tr>\n";
$html .= "					<td>Total Nightly Attendance:</td>\n";
$html .= "					<td><input type='text' id='attendance' name='attendance'";
$html .= " value='" . $attendance . "'></td>\n";
$html .= "				</tr>\n";
$html .= "				<tr>\n";
$html .= "					<td>Name of Shift Supervisor:</td>\n";
$html .= "					<td><input type='text' id='event' name='supervisor'";
$html .= " value='" . $supervisor . "'></td>\n";
$html .= "				</tr>\n";
$html .= "				<tr>\n";
$html .= "					<td>Security Checklist Completed:</td>\n";
$html .= "					<td><select name='sec_chklst'><option value='1'";
if($sec_chklst == 1) $html .= "selected='selected'";
$html .= ">Yes</option><option value='0'";
if($sec_chklst == 2) $html .= "selected='selected'";
$html .= ">No</option></select></td>\n";
$html .= "				</tr>\n";
$html .= "			</table>\n";
$html .= "			<input type='submit' name='action' value='Add an Incident'>";
$html .= "			<input type='submit' name='action' value='Finished'>";
$html .= "		</form>\n";
$html .= "	</div>";

echo $html;

echo "</div>\n";
//=============================================================================
createFoot();
?>