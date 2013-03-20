<?php
/**
 * incident.php
 * used to create and attach and incident to a VAR
 * @author James P. Smith March 2013
 */
include_once "php/config.php";
include_once "CRUD Functions/IncidentEntry.php";
include_once "php/classIncident.php";
include_once "php/classPor.php";

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

$pm 		  = null;
$action 	  = null;
$varID 		  = null;
$ine_time 	  = null;
$ine_severity = null;
$ine_police   = null;
$ine_summary  = null;
$ine_damages  = null;
if(isset($_GET['pm'])) 	  	   $pm	   = $_GET['pm'];
if(isset($_GET['action'])) 	   $action     = $_GET['action'];
if(isset($_GET['varID'])) 	   $varID      = $_GET['varID'];
if(isset($_GET['time'])) 	   $ine_time	   = $_GET['time'];
if(isset($_GET['severity']))   $ine_severity = $_GET['severity'];
if(isset($_GET['police'])) 	   $ine_police   = $_GET['police'];
if(isset($_GET['Summary']))    $ine_summary	= $_GET['Summary'];
if(isset($_GET['Damages']))    $ine_damages  = $_GET['Damages'];
if(isset($_GET['ineID']))	   $ineID = $_GET['ineID'];
if(isset($_GET['varID']))	   $varID = $_GET['varID'];

// Convert time value from form into 24 hr format
if($pm == 1)
{
	$ineTime_ts = strtotime($ine_time);
	echo $ineTime_ts . "\n";
	$ineTime_ts += (60 * 60 * 12);
	echo $ineTime_ts . "\n";
	$ine_time   = date('G:i:s', $ineTime_ts);
	
}
// Cancel option - returns user to manageVars.php without saving incident inputs
if($action == "Cancel") header('location:manageVars.php?action=view&varID=' . $varID);

//_____________________________________________________________________________
// Look up Incident Level Definitions to create combo box on form
$con = $myCon->connect();
$result = mysqli_query($con, 'SELECT * FROM incident_level_lookup');
$i = 0;
while($row = mysqli_fetch_array($result))
{
	$IllLevels[$i] = $row['ILL_Def'];
	$i++;
}
mysqli_close($con);
//_____________________________________________________________________________

// Get values for making a nice, complete looking report output with venue name,
//	address, phone number, etc. 
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
	$address = $unitAddr . " " . $stAddr;
	
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
	
	
	//$sql  = "SELECT ILL_Def FROM incident_level_lookup";
	//$IllResult = mysqli_query($con, $sql);
	
	mysqli_close($con);
	
}
//_____________________________________________________________________________

// Get existing data, if that's what we're here for
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

// Delete option - sets a value in Reason_for_Del. Field is no long null and 
//	is therefore record is "deleted"
if($action == 'Delete')
{
	// send to delete page
	$page = "location:delete.php?action=deleteIne&varID=$varID&ineID=$ineID";
	header($page);
}
//_____________________________________________________________________________

// Save option - creates a new entry in incident table and sends user back to
//	manage Vars page
// Add a Person option creates a new entry and sends user to person of record
//	creation page
// If there is an existing record, it is updated
if(($action == 'Save' || $action == 'Add a Person'))
{
	// No ineID, so we make a new record
	if($ineID == null)
	{
		$con = $myCon->connect();
		
		$ine_time    = mysqli_real_escape_string($con, $ine_time);
		$ine_summary = mysqli_real_escape_string($con, $ine_summary);
		$ine_damages = mysqli_real_escape_string($con, $ine_damages);
		
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
		
		$result = mysqli_query($con, $sql);
		
		$sql  = "SELECT MAX(INE_ID) AS INE_ID FROM incident_entry";
		$sql .= " WHERE (var_VAR_ID=" . $varID . ")";
		
		$result = mysqli_query($con, $sql);
		while($row = mysqli_fetch_array($result))
		{
			$ineID = $row['INE_ID'];
		}
		
		if($action == 'Save')
		{
			$page = "location:manageVars.php?action=view&varID=$varID";
		}
		if($action == 'Add a Person')
		{
			$page = "location:managePor.php?action=new&varID=$varID&ineID=$ineID";
		}
		header($page);
	}
	else 	// update the existing record
	{
		$con = $myCon->connect();
		
		$ine_time     = mysqli_real_escape_string($con, $ine_time);
		$ine_severity = mysqli_real_escape_string($con, $ine_severity);
		$ine_police   = mysqli_real_escape_string($con, $ine_police);
		$ine_summary  = mysqli_real_escape_string($con, $ine_summary);
		$ine_damages  = mysqli_real_escape_string($con, $ine_damages);
						
					
		if($ine_time != $resultTime)
		{
			//update
			$sql = incEntUpdate('INE_Time', $ine_time, $ineID, $reason_for_delete = NULL, $con);
			$new = mysqli_query($con, $sql);
			//auditing
			$sql  = "INSERT INTO modification_ine (Incident_Entry_INE_ID, Incident_Entry_Var_VAR_ID,User_USE_ID, MOD_Action)";
			$sql .= " VALUES ($ineID, $varID, $userID, 'INE_Time')";
			$new = mysqli_query($con, $sql);
		}
		
		if($ine_severity != $ineLevel)
		{
			//update
			$sql = incEntUpdate('Incident_Level_Lookup_ILL_Level', $ine_severity, $ineID, $reason_for_delete = NULL, $con);
			$new = mysqli_query($con, $sql);
			//auditing
			$sql  = "INSERT INTO modification_ine (Incident_Entry_INE_ID, Incident_Entry_Var_VAR_ID,User_USE_ID, MOD_Action)";
			$sql .= " VALUES ($ineID, $varID, $userID, 'Incident_Level_Lookup_ILL_Level')";
			$new = mysqli_query($con, $sql);
		}
		
		if($ine_police != $inePolice)
		{
			//update
			$sql = incEntUpdate('INE_Police', $ine_police, $ineID, $reason_for_delete = NULL, $con);
			$new = mysqli_query($con, $sql);
			//auditing
			$sql  = "INSERT INTO modification_ine (Incident_Entry_INE_ID, Incident_Entry_Var_VAR_ID,User_USE_ID, MOD_Action)";
			$sql .= " VALUES ($ineID, $varID, $userID, 'INE_Police')";
			$new = mysqli_query($con, $sql);
		}
		if($ine_summary != $ineContent)
		{
			//update
			$sql = incEntUpdate('INE_Content', $ine_summary, $ineID, $reason_for_delete = NULL, $con);
			$new = mysqli_query($con, $sql);
			//auditing
			$sql  = "INSERT INTO modification_ine (Incident_Entry_INE_ID, Incident_Entry_Var_VAR_ID,User_USE_ID, MOD_Action)";
			$sql .= " VALUES ($ineID, $varID, $userID, 'INE_Content')";
			$new = mysqli_query($con, $sql);
		}
		if($ine_damages != $ineDamages)
		{
			//update
			$sql = incEntUpdate('INE_Damages', $ine_damages, $ineID, $reason_for_delete = NULL, $con);
			$new = mysqli_query($con, $sql);
			//auditing
			$sql  = "INSERT INTO modification_ine (Incident_Entry_INE_ID, Incident_Entry_Var_VAR_ID,User_USE_ID, MOD_Action)";
			$sql .= " VALUES ($ineID, $varID, $userID, 'INE_Damages')";
			$new = mysqli_query($con, $sql);
		}
		
		
		mysqli_close($con);
		
		if($action == 'Save')
		{
			$page = 'location:manageVars.php?action=view&varID=' . $varID;
		}
		if($action == 'Add a Person')
		{
			$page  = "location:managePor.php?action=new&ineID=$ineID&varID=$varID";
		}
		header($page);
	}
//_____________________________________________________________________________

// Find instances of person of record and prepare data to create objects
// Person of Record objects will be appended to incident form
}
$Por[0] = null;
$records = 0;
if($ineID != null)
{
	$sql  = "SELECT * FROM person_of_record";
	$sql .= " WHERE (";
	$sql .= " incident_entry_INE_ID=$ineID";
	$sql .= " AND POR_Reason_for_Del IS NULL";
	$sql .= ")";
	
	$con = $myCon->connect();
	$result = mysqli_query($con, $sql);
	if(mysqli_num_rows($result) != 0)
	{
		
		while($row = mysqli_fetch_array($result))
		{
			$porDetails[0] = $row['POR_ID'];
			$porDetails[1] = $row['POR_Name'];
			$porDetails[2] = $row['Involvement_Lookup_INV_Level'];;
			$porDetails[3] = $row['POR_Phone'];
			$porDetails[4] = $row['POR_License'];
			$porDetails[5] = $row['POR_Notes'];
			$porDetails[6] = $ineID;
			$porDetails[7] = $varID;
			
			$Por[$records] = new Por($records + 1, $porDetails);
			$records++;
		}
	}
	mysqli_close($con);
} 

//*****************************************************************************

createHead('incident.css', 'porNew.js');
createHeader($fullName);
createNav($userAuth);

//==========================    Begin Content    =============================
echo "<div id='content'>\n";

$details[0]  = $ineID;
$details[1]  = $venueName;
$details[2]  = $address;
$details[3]  = $city . ", " . $province;
$details[4]  = $reportDate;
$details[5]  = $event;
$details[6]  = $supervisor;
$details[7]  = $ineTime;
$details[8]  = $pm;
$details[9]  = $ineLevel;
$details[10] = $inePolice;
$details[11] = $ineContent;
$details[12] = $ineDamages;
$details[13] = $userFname . " " . $userLname;
$details[14] = $liason;
$details[15] = $action;
$details[16] = $varID;

$html  = "<div id='IncidentForm'>\n";

$html .= "	<form id='IneForm' action='incident.php' method='GET'>\n";

$Incident = new Incident($details);
$html .= $Incident->drawIncident($IllLevels);
$html .= "<input type='submit' class='bottomButton' name='action' value='Add a Person'>"; 

$html .= "	</form>\n";
$html .= "<script type='text/javascript'>\n";
// Popup window code
$html .= "function newPopup(url) {\n";
$html .= "popupWindow = window.open(\n";
$html .= "url,'popUpWindow','height=700,width=1100,left=10,top=10,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no,status=yes')\n";
$html .= "}\n";
$html .= "</script>\n";
$link  = "'printIncident.php?action=print&ineID=$ineID&varID=$varID'";
$html .= '<a href="JavaScript:newPopup(' . $link . ');">';
$html .= "<button class='bottomButton'>Print</button></a>\n";
if($action == 'view') $html .= "<a href='manageVars.php?action=view&varID=" . $varID ."'><button class='bottomButton'>Done</button></a>";

$html .= "<div id='clear'></div>";

if($ineID != null)
{	
	$counter = count($Por);
	for($i = 0; $i < $records; $i++)
	{
		$html .= $Por[$i]->drawPor('disabled');
	}
}
	
$html .= "</div>\n"; // close IncidentForm

echo $html;

echo "</div>\n"; // close content
//=============================================================================
createFoot();
?>