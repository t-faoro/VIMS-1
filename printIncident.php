<?php
/**
 * printIncident.php
 * provides the user with a print-frienly page for printing Incident Reports
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
// set variables
$ineID 		= null;
$varID		= null;
$reportDate = null;
$supervisor = null;
$sec_chklst = null;
$event 		= null;
$police 	= null;
$venueAddr 	= null;
$venueLoc 	= null;
$hourMin 	= null;
$pm 		= null;
$IneLevels 	= null;
$level 		= null;
$content	= null;
$damages 	= null;
$liason 	= null;
if(isset($_GET['ineID']))		$ineID = $_GET['ineID'];
if(isset($_GET['varID']))		$varID = $_GET['varID'];

// Fill out form with 
if($varID != null)
{
	$con = $myCon->connect();
	
	$sql  = "SELECT * FROM venue";
	$sql .= " WHERE VEN_ID=";
	$sql .= $venueID;
	$result = mysqli_query($con, $sql);
	while($row  = mysqli_fetch_array($result))
	{
		$unitAddr = $row['VEN_Unit_Addr'];
		$stAddr	  = $row['VEN_St_Addr'];
		$city	  = $row['VEN_City'];
		$province = $row['VEN_Province'];
		$liason   = $row['VEN_Liason'];
	}
	$venueAddr = $unitAddr . " " . $stAddr;
	$venueLoc  = $city . ", " . $province;
	
	$sql  = "SELECT * FROM var";
	$sql .= " WHERE (venue_VEN_ID=";
	$sql .= $venueID . " AND VAR_ID=$varID)";
	
	$result = mysqli_query($con, $sql);
	while($row  = mysqli_fetch_array($result))
	{
		$reportDate = $row['VAR_Date']; 
		$sec_chklst = $row['VAR_Sec_Chklst'];
		$supervisor = $row['VAR_Supervisor'];
		$event		= $row['VAR_Event'];
		if($event == 'Reg. Op.') $event = 'Regular Operations';
	}
	
	
	$sql  = "SELECT ILL_Def FROM incident_level_lookup";
	$IllResult = mysqli_query($con, $sql);
	
	mysqli_close($con);
	
}

if(isset($_GET['ineID']))
{ 
	$ineID = $_GET['ineID'];
	
	$sql  = "SELECT * FROM incident_entry";
	$sql .= " WHERE (INE_ID=" . $ineID;
	$sql .= " AND INE_Reason_for_Del IS NULL)";
	
	$con = $myCon->connect();
	$result = mysqli_query($con, $sql);
	
	while($row = mysqli_fetch_array($result))
	{
		$resultTime = $row['INE_Time'];
		$police	= $row['INE_Police'];
		$content = $row['INE_Content'];
		$damages = $row['INE_Damages'];
		$level	= $row['Incident_Level_Lookup_ILL_Level'];
	}
	
	mysqli_close($con);

	$time_ts = strtotime($resultTime);
	if($time_ts > strtotime('12:00')) 
	{
		$time_ts = $time_ts - (60*60*12);
		$pm	= 1;
	}
	else $pm = 0;
	$hourMin = date('g:i',$time_ts); 
	
}	
else 
{
	$ineID 		= null;
	$hourMin    = null;
	$inePolice  = null;
	$ineContent = null;
	$ineDamages = null;
	$ineLevel	= null;
}

$con = $myCon->connect();
$result = mysqli_query($con, 'SELECT * FROM incident_level_lookup');
$i = 0;
while($row = mysqli_fetch_array($result))
{
	$IllLevels[$i] = $row['ILL_Def'];
	$i++;
}
mysqli_close($con);
//*****************************************************************************
$html  = "<!DOCTYPE html>\n";
$html .= "<html>\n";
$html .= "<head>\n";
$html .= "<title>Venue Information Management System</title>\n";
$html .= "<link rel='stylesheet' type='text/css' href='./css/printincident.css'>\n";
$html .= "<script src='//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js'></script>\n";
$html .= "<script src='//ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/jquery-ui.min.js'></script>\n";
$html .= "<script type='text/javascript' src='./js/print.js'></script>\n";
$html .= "<script>";
$html .=            "$(document).ready(function() {";
$html .= "$('span.print').printPage();";
$html .= "});";
$html .= "</script>";
$html .= "<meta charset='UTF-8'>\n";
$html .= "</head>\n";
//==========================    Begin Content    =============================
$html .= "<body>\n";
$html .= "<div id='content'>\n";
$html .= "<span class='print'>\n";
$html .= "<a href='#'>[Print]</a>";
$html .= "<div id='IncidentForm'>\n";
$html .= "	<form id='IneForm' action='incident.php' method='GET'>\n";

$html .= "<h4>Clubwatch | Venue Information Management System | Security Incident Report Form</h4>\n";
$html .= "	<div  id='IneTitle'>\n";
$html .= "		<h3>Incident Report</h3>\n";
$html .= "	</div>\n"; // close IneTitle

$html .= "		<div class='smallRightField'>\n";
$html .= "		<div class='label'>Venue Name: </div><br />\n";
$html .= "		<span class='tab'>" . $venueName . "</span>\n";
$html .= "		</div>\n"; // close smallRightField

$html .= "		<div class='smallRightField'>\n";
$html .= "		<div class='label'>Venue Address: </div><br />\n";
$html .= "		<span class='tab'>" . $venueAddr . "</span>\n";
$html .= "		</div>\n"; // close smallRightField

$html .= "		<div class='smallRightField'>\n";
$html .= "		<div class='label'>Venue City &amp Province: </div><br />\n";
$html .= "		<span class='tab'>" . $venueLoc . "</span>\n";
$html .= "		</div>\n"; // close smallRightField

$html .= "		<div class='smallRightField'>\n";
$html .= "		<div class='label'>Report Date: </div><br />\n";
$html .= "		<span class='tab'>" . nicedate($reportDate). "</span>\n";
$html .= "		</div>\n"; // close smallRightField

$html .= "		<div id='clear'></div>\n"; // close clear

$html .= "		<div class='smallLeftField'>\n";
$html .= "		<div class='label'>Event Occuring: </div><br />\n";
$html .= "		<span class='tab'>" . $event . "</span>\n";
$html .= "		</div>\n"; // close smallRightField

$html .= "		<div class='smallRightField'>\n";
$html .= "		<div class='label'>Supervisor on Shift: </div><br />\n";
$html .= "		<span class='tab'>" . $supervisor . "</span>\n";
$html .= "		</div>\n"; // close smallRightField

$html .= "		<div id='clear'></div>"; // close clear

$html .= "		<div id='TimeField'>\n";
$html .= "		<div class='label'>Time Incident Occurred: </div><br />\n";
$html .= "		<input type='text' name='time' value='" . $hourMin . "'>\n";
$html .= "		<input type='radio' name='pm' value=0";
if($pm == 0)$html .= " checked='checked'";
$html .= ">am\n";
$html .= "		<input type='radio' name='pm' value=1";
if($pm == 1)$html .= " checked='checked'";
$html .= ">pm\n";
$html .= "		</div>\n"; // close TimeField

$html .= "		<div id='SeverityField'>\n";
$html .= "		<div class='label'>Level of Severity: </div><br />\n";
$html .= "		<select name='severity'>\n";
$i = 1;
foreach ($IllLevels as $key => $value)
{
	$html .= "			<option value=" . $i;
	if($i == $level) $html .= " selected='selected'";
	$html .= ">" . $value;
	$html .= "</option>\n";
	$i++;
}
$html .= "		</select>\n";
$html .= "		</div>\n"; // close SeverityField

$html .= "		<div id='clear'></div>"; // close clear

$html .= "		<div id='PoliceField'>\n";
$html .= "		<div class='label'>Where the police involved in this incident?</div><br />\n";
$html .= "		<input type='radio' name='police' value=0";
if($police == 0)$html .= " checked='checked'";
$html .= ">No\n";
$html .= "		<input type='radio' name='police' value=1";
if($police == 1)$html .= " checked='checked'";
$html .= ">Yes\n";
$html .= "		</div>\n"; // close PoliceField

$html .= "		<div id='SummaryField'>\n";
$html .= "		<div class='label'>Provide a description of the incident</div><br />\n";
$html .= "		<textarea name='Summary'>" . $content . "</textarea>\n";
$html .= "		</div>\n"; // close SummaryField

$html .= "		<div id='DamagesField'>\n";
$html .= "		<div class='label'>Specify any damages incurred</div><br />\n";
$html .= "		<textarea name='Damages'>" . $damages . "</textarea>\n";
$html .= "		</div>\n"; // close DamagesField

$html .= "		<div class='smallLeftField'>\n";
$html .= "		<div class='label'>Form Completed by:</div><br />\n";
$html .= "		<span class='tab'>" . $fullName. "</span>\n";
$html .= "		</div>\n"; // close CompletedField

$html .= "		<div class='smallRightField'>\n";
$html .= "		<div class='label'>Venue Contact: </div><br />\n";
$html .= "</form>";
$html .= "		<span class='tab'>" . $liason . "</span>\n";
$html .= "		</div>\n"; // close smallRightField

$html .= "		<div id='clear'></div>\n"; // close clear




$html .= "</div>\n";
$html .= "</span>\n";
$html .= "</div>\n";
$html .= "</body>\n";
$html .= "</html>\n";
//=============================================================================
echo $html;
?>