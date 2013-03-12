<?php
/**
 * incident.php
 * used to create and attach and incident to a VAR
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
$date = date('Y-m-d', time());

if(isset($_GET['reportDate'])) $reportDate = $_GET['reportDate'];
if(isset($_GET['sec_chklst'])) $sec_chklst = $_GET['sec_chklst'];
if(isset($_GET['supervisor'])) $supervisor = $_GET['supervisor'];
if(isset($_GET['event'])) 	   $event	   = $_GET['event'];
if($event == 'Reg. Op.'? $eventName = 'Regular Operations' : $eventName = $event);
if(isset($_GET['reportDate']))
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
	$address = $unitAddr . " " . $stAddr;
	
	$sql  = "SELECT * FROM var";
	$sql .= " WHERE (venue_VEN_ID=";
	$sql .= $venueID . " AND VAR_Date='";
	$sql .= $reportDate . "')";
	
	$result = mysqli_query($con, $sql);
	while($row  = mysqli_fetch_array($result));
	$varID = $row['VAR_ID'];
	
	$sql  = "SELECT ILL_Def FROM incident_level_lookup";
	$IllResult = mysqli_query($con, $sql);
	
	mysqli_close($con);
	
}
else $varID = null;
if(isset($_GET['ineID'])) $ineID = $_GET['ineID'];
else $ineID = null;
//*****************************************************************************
//							      Post Back
//*****************************************************************************


//*****************************************************************************

createHead('incident.css', 'por.js');
createHeader($fullName);
createNav($userAuth);

//==========================    Begin Content    =============================
echo "<div id='content'>\n";


$html  = "<div id='IncidentForm'>\n";
$html .= "<h4>Clubwatch | Venue Information Management System | Security Incident Report Form</h4>\n";
$html .= "	<div  id='IneTitle'>\n";
$html .= "		<h3>Incident Report</h3>\n";
$html .= "	</div>\n"; // close IneTitle
$html .= "	<form action='blank.php' method='GET'>\n";
$html .= "		<div class='smallRightField'>\n";
$html .= "		<div class='label'>Venue Name: </div><br />\n";
$html .= "		<span class='tab'>" . $venueName . "</span>\n";
$html .= "		</div>\n"; // close smallRightField

$html .= "		<div class='smallRightField'>\n";
$html .= "		<div class='label'>Venue Address: </div><br />\n";
$html .= "		<span class='tab'>" . $address . "</span>\n";
$html .= "		</div>\n"; // close smallRightField

$html .= "		<div class='smallRightField'>\n";
$html .= "		<div class='label'>Venue City &amp Province: </div><br />\n";
$html .= "		<span class='tab'>" . $city . ", " . $province . "</span>\n";
$html .= "		</div>\n"; // close smallRightField

$html .= "		<div class='smallRightField'>\n";
$html .= "		<div class='label'>Report Date: </div><br />\n";
$html .= "		<span class='tab'>" . nicedate($reportDate). "</span>\n";
$html .= "		</div>\n"; // close smallRightField

$html .= "		<div id='clear'></div>\n"; // close clear

$html .= "		<div class='smallLeftField'>\n";
$html .= "		<div class='label'>Event Occuring: </div><br />\n";
$html .= "		<span class='tab'>" . $eventName . "</span>\n";
$html .= "		</div>\n"; // close smallRightField

$html .= "		<div class='smallRightField'>\n";
$html .= "		<div class='label'>Supervisor on Shift: </div><br />\n";
$html .= "		<span class='tab'>" . $supervisor . "</span>\n";
$html .= "		</div>\n"; // close smallRightField

$html .= "		<div id='clear'></div>"; // close clear

$html .= "		<div id='TimeField'>\n";
$html .= "		<div class='label'>Time Incident Occurred: </div><br />\n";
$html .= "		<input type='text' name='time'>\n";
$html .= "		<input type='radio' name='pm' value=0>am\n";
$html .= "		<input type='radio' name='pm' value=1>pm\n";
$html .= "		</div>\n"; // close TimeField

$html .= "		<div id='SeverityField'>\n";
$html .= "		<div class='label'>Level of Severity: </div><br />\n";
$html .= "		<select name='severity'>\n";
$i = 0;
while($IneLevel = mysqli_fetch_array($IllResult))
{
	$html .= "			<option value=" . $i . ">";
	$html .= $IneLevel['ILL_Def'];
	$html .= "</option>\n";
	$i++;
}
$html .= "		</select>\n";
$html .= "		</div>\n"; // close SeverityField

$html .= "		<div id='clear'></div>"; // close clear

$html .= "		<div id='PoliceField'>\n";
$html .= "		<div class='label'>Where the police involved in this incident?</div><br />\n";
$html .= "		<input type='radio' name='police' value=0>No\n";
$html .= "		<input type='radio' name='police' value=1>Yes\n";
$html .= "		</div>\n"; // close PoliceField

$html .= "		<div id='SummaryField'>\n";
$html .= "		<div class='label'>Provide a description of the incident</div><br />\n";
$html .= "		<textarea name='Summary'></textarea>\n";
$html .= "		</div>\n"; // close SummaryField

$html .= "		<div id='DamagesField'>\n";
$html .= "		<div class='label'>Specify any damages incurred</div><br />\n";
$html .= "		<textarea name='Damages'></textarea>\n";
$html .= "		</div>\n"; // close DamagesField

//_____________________________________________________________________________
$html .= "<div id='PorField'>\n";
$html .= "		<div class='label'>Specify details about persons involved in the incident: </div><br />\n";

$html .= "<div id='PorLines'>\n";
$html .= "	<div class='porRec'>\n";
$html .= "		<span id='PorRec1'>\n";
$html .= "		<div class='PorLabel'>Person 1</div><br />\n"; // close PorLabel
$html .= "			<label>Name: </label><input type='textbox' name='porName1'>";
$html .= " <label>Involvement: </label><select name='porInv1'>";
$html .= "<option value='1'>Witness</option>";
$html .= "<option value='2'>Victim</option>";
$html .= "<option value='3'>Instigator</option>";
$html .= "<option value='4'>Agressor</option>";
$html .= "</select>";
$html .= " <label>Phone: </label><input type='textbox' name='porPhone1'><br />\n";
$html .= "			<label>Notes: </label><br />\n";
$html .= "			<textarea name='porNotes1'></textarea>";
$html .= "		</span>\n";
$html .= "	</div>\n"; // close PorRec
$html .= "</div>"; // close PorLines

$html .= "<input type='button' value='Add Another Person' id='addButton'>\n";
$html .= "<input type='button' value='Remove Last Person' id='removeButton'>\n";
//$html .= "<input type='button' value='Get TextBox Value' id='getButtonValue'>\n";

$html .= "</div>\n"; // close PorField
$html .= "		<div id='clear'></div>\n"; // close clear
//_____________________________________________________________________________

$html .= "<div id='ImgField'>\n";
$html .= "		<div class='label'>Add any images you may have relating to the incident: </div><br />\n";

$html .= "<div id='ImgLines'>\n";
$html .= "	<div class='ImgRec'>\n";
$html .= "		<span id='ImgRec1'>\n";
$html .= "			<label>Image 1: </label>\n";
$html .= "			<input type='file' name='img1' id='img1'><br />";
$html .= "		</span>\n";
$html .= "	</div>\n"; // close PorRec
$html .= "</div>"; // close PorLines

$html .= "<input type='button' value='Add Another Image' id='addImg'>\n";
$html .= "<input type='button' value='Remove Last Image' id='removeImg'>\n";
//$html .= "<input type='button' value='Get TextBox Value' id='getButtonValue'>\n";

$html .= "</div>\n"; // close PorField
$html .= "		<div id='clear'></div>\n"; // close clear
//_____________________________________________________________________________

$html .= "		<div class='smallLeftField'>\n";
$html .= "		<div class='label'>Form Completed by:</div><br />\n";
$html .= "		<span class='tab'>" . $fullName . "</span>\n";
$html .= "		</div>\n"; // close CompletedField

$html .= "		<div class='smallRightField'>\n";
$html .= "		<div class='label'>Venue Contact: </div><br />\n";
$html .= "		<span class='tab'>" . $liason . "</span>\n";
$html .= "		</div>\n"; // close smallRightField

$html .= "		<div id='clear'></div>\n"; // close clear

$html .= "<input type='submit' name='submit' value='Finished'>";
$html .= "<span class='print'>[Print]</span>\n";

$html .= "	</form>\n";
$html .= "</div>\n"; // close IncidentForm

echo $html;

echo "</div>\n"; // close content
//=============================================================================
createFoot();
?>