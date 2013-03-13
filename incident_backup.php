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
$pm = null;

if(isset($_GET['action'])) 	   $action     = $_GET['action'];
else $action = null;
if(isset($_GET['varID'])) 	   $varID      = $_GET['varID'];
else $varID = null;
if(isset($_GET['reportDate'])) $reportDate = $_GET['reportDate'];
else $reportDate = null;
if(isset($_GET['sec_chklst'])) $sec_chklst = $_GET['sec_chklst'];
else $sec_chklst = null;
if(isset($_GET['supervisor'])) $supervisor = $_GET['supervisor'];
else $supervisor = null;
if(isset($_GET['event'])) 	   $event	   = $_GET['event'];
else $event = null;
if(isset($_GET['time'])) 	   $ine_time	   = $_GET['time'];
else $ine_time = null;
if(isset($_GET['severity']))   $ine_severity = $_GET['severity'];
else $ine_severity = null;
if(isset($_GET['police'])) 	   $ine_police   = $_GET['police'];
else $ine_police = null;
if(isset($_GET['Summary']))    $ine_summary	= $_GET['Summary'];
else $ine_summary = null;
if(isset($_GET['Damages']))    $ine_damages  = $_GET['Damages'];
else $ine_damages = null;
$index = 1;
while(isset($_GET['porNotes' . $index])
   || isset($_GET['imgDesc'  . $index]))
{
	if(strlen($_GET['porNotes']) != 0)
	{
		if(isset($_GET['porName'  . $index]))   $por_name[$index]    = $_GET['porName' . $index];
		else $por_name[$index] = null;
		if(isset($_GET['porInv'   . $index]))   $por_inv[$index]     = $_GET['porInv' . $index];
		else $por_inv[$index] = null;
		if(isset($_GET['porPhone' . $index]))   $por_phone[$index]   = $_GET['porPhone' . $index];
		else $por_phone[$index] = null;
		if(isset($_GET['porNotes' . $index]))   $por_notes[$index]   = $_GET['porNotes' . $index];
		else $por_notes[$index] = null;
		if(isset($_GET['porLicense' . $index])) $por_license[$index] = $_GET['porLicense' . $index];
		else $por_license[$index] = null;
	}
	if(strlen($_GET['img']) != 0)
		if(isset($_GET['img'	  . $index]))   $img[$index] 		   = $_GET['img' . $index];
		else $img[$index] = null;
		if(isset($_GET['imgDesc'  . $index]))   $imgDesc[$index]     = $_GET['imgDesc' . $index];
		else $imgDesc[$index] = null;
	$index++;
}

//_____________________________________________________________________________
if($event == 'Reg. Op.'? $eventName = 'Regular Operations' : $eventName = $event);
if($reportDate != null)
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
	while($row  = mysqli_fetch_array($result)) $varID = $row['VAR_ID'];
	
	
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
		$inePolice	= $row['INE_Police'];
		$ineContent = $row['INE_Content'];
		$ineDamages = $row['INE_Damages'];
		$ineLevel	= $row['Incident_Level_Lookup_ILL_Level'];
	}
	
	mysqli_close($con);

	$time_ts = strtotime($resultTime);
	if($time_ts > strtotime('12:00')) 
	{
		$time_ts = $time_ts - (60*60*12);
		$pm	= 1;
	}
	else $pm = 0;
	$ineTime = date('g:i',$time_ts); 
	
}	
else 
{
	$ineID 		= null;
	$ineTime    = null;
	$inePolice  = null;
	$ineContent = null;
	$ineDamages = null;
	$ineLevel	= null;
}

//*****************************************************************************
//							      Post Back
//*****************************************************************************
if($action == 'Finished' && $ine_time != null)
{
	$sql  = "INSERT INTO incident_entry (";
	$sql .= "var_VAR_ID";
	$sql .= ", INE_Time";
	$sql .= ", incident_level_lookup_ILL_Level";
	$sql .= ", INE_Police";
	$sql .= ", INE_Content";
	$sql .= ", INE_Damages";
	$sql .= ") VALUES (";
	$sql .= $varID;
	$sql .= ", '" . $ine_time . "'";
	$sql .= ", " . $ine_severity;
	$sql .= ", " . $ine_police;
	$sql .= ", '" . $ine_summary . "'";
	$sql .= ", '" . $ine_damages . "'";
	$sql .= ")";
	
	$con = $myCon->connect();
	$result = mysqli_query($con, $sql);
	
	$sql  = "SELECT MAX(INE_ID) AS INE_ID FROM incident_entry";
	$sql .= " WHERE (var_VAR_ID=" . $varID . ")";
	
	$result = mysqli_query($con, $sql);
	while($row = mysqli_fetch_array($result))
	{
		$ineID = $row['INE_ID'];
	}
	$length = count($por_notes);
	
	while($length > 0){
		
		$sql  = "INSERT INTO person_of_record (";
		$sql .= "incident_entry_INE_ID";
		$sql .= ", incident_entry_var_VAR_ID";
		$sql .= ", POR_Name";
		$sql .= ", POR_Phone";
		$sql .= ", POR_License";
		$sql .= ", POR_Notes";
		$sql .= ", involvement_lookup_INV_Level";
		$sql .= ") VALUES (";
		$sql .= $ineID;
		$sql .= ", " . $varID;
		$sql .= ", '" . $por_name[$length] . "'";
		$sql .= ", '" . $por_phone[$length] . "'";
		$sql .= ", '" . $por_license[$length] . "'";
		$sql .= ", '" . $por_notes[$length] . "'";
		$sql .= ", '" . $por_inv[$length] . "'";
		$sql .= ")";
		$length--;
		
		
		$result = mysqli_query($con, $sql);
	}
	
	mysqli_close($con);
	
	//header('location:manageVars.php?action=view&varID=' . $varID);
}

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
$html .= "	<form action='incident.php' method='GET'>\n";
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
$html .= "		<input type='text' name='time' value='" . $ineTime . "'>\n";
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
while($IneLevel = mysqli_fetch_array($IllResult))
{
	$html .= "			<option value=" . $i;
	if($i == $ineLevel) $html .= " selected='selected'";
	$html .= ">" . $IneLevel['ILL_Def'];
	$html .= "</option>\n";
	$i++;
}
$html .= "		</select>\n";
$html .= "		</div>\n"; // close SeverityField

$html .= "		<div id='clear'></div>"; // close clear

$html .= "		<div id='PoliceField'>\n";
$html .= "		<div class='label'>Where the police involved in this incident?</div><br />\n";
$html .= "		<input type='radio' name='police' value=0";
if($inePolice == 0)$html .= " checked='checked'";
$html .= ">No\n";
$html .= "		<input type='radio' name='police' value=1";
if($inePolice == 1)$html .= " checked='checked'";
$html .= ">Yes\n";
$html .= "		</div>\n"; // close PoliceField

$html .= "		<div id='SummaryField'>\n";
$html .= "		<div class='label'>Provide a description of the incident</div><br />\n";
$html .= "		<textarea name='Summary'>" . $ineContent . "</textarea>\n";
$html .= "		</div>\n"; // close SummaryField

$html .= "		<div id='DamagesField'>\n";
$html .= "		<div class='label'>Specify any damages incurred</div><br />\n";
$html .= "		<textarea name='Damages'>" . $ineDamages . "</textarea>\n";
$html .= "		</div>\n"; // close DamagesField

//_____________________________________________________________________________
//
//								Persons of Record
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
$html .= " <label>Phone: </label><input type='textbox' name='porPhone1'>\n";
$html .= " <label>License: </label><input type='textbox' name='porLicense1'><br />\n";
$html .= "			<label>Notes: </label><br />\n";
$html .= "			<textarea name='porNotes1'></textarea>";
$html .= "		</span>\n";
$html .= "	</div>\n"; // close PorRec
$html .= "</div>"; // close PorLines

$html .= "<input type='button' value='Add Another Person' id='addButton'>\n";
$html .= "<input type='button' value='Remove Last Person' id='removeButton'>\n";

$html .= "</div>\n"; // close PorField
$html .= "		<div id='clear'></div>\n"; // close clear
//_____________________________________________________________________________
//
//								Image Uploads
//_____________________________________________________________________________
/*
$html .= "<div id='ImgField'>\n";
$html .= "		<div class='label'>Add any images you may have relating to the incident: </div><br />\n";

$html .= "<div id='ImgLines'>\n";
$html .= "	<div class='imgRec'>\n";
$html .= "		<span id='imgRec1'>\n";
$html .= "			<div class='ImgLabel'>Image 1: </div><br />\n";
$html .= "			<input type='file' name='img1' id='img1'><br />";
$html .= "			<label>Image Description: </label><br />\n";
$html .= "			<textarea name='imgDesc1'></textarea>";
$html .= "		</span>\n";
$html .= "	</div>\n"; // close imgRec
$html .= "</div>"; // close imgLines

$html .= "<input type='button' value='Add Another Image' id='addImg'>\n";
$html .= "<input type='button' value='Remove Last Image' id='removeImg'>\n";

$html .= "</div>\n"; // close imgField
$html .= "		<div id='clear'></div>\n"; // close clear
 */
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

$html .= "<input type='hidden' name='varID' value='" . $varID . "'>\n";
$html .= "<input type='submit' name='action' value='Finished'>\n";
$html .= "<span class='print'><button>Print</button></span>\n";

$html .= "	</form>\n";
if($action == 'view') $html .= "<a href='manageVars.php?action=view&varID=" . $varID ."'><button>Done</button></a>";
$html .= "</div>\n"; // close IncidentForm

echo $html;

echo "</div>\n"; // close content
//=============================================================================
createFoot();
?>