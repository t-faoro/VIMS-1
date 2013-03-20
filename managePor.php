<?php
/**
 * managePor.php
 * allows the user to save and update data to a person of record
 * @author James P. Smith March 2013
 */
include_once "php/config.php";
include_once "php/classPor.php";
include_once "CRUD Functions/personOfRecord.php";
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

$porName 	= null;
$porPhone 	= null;
$porInv 	= null;
$porLicense = null;
$porNotes 	= null;
$porID 		= null;
$ineID 		= null;
$action 	= null;
$varID 		= null;
$reportDate = null;
$sec_chklst = null;
$supervisor = null;
$event 		= null;		
if(isset($_GET['porID'])) 	   $porID      = $_GET['porID'];
if(isset($_GET['ineID'])) 	   $ineID      = $_GET['ineID'];
if(isset($_GET['action'])) 	   $action     = $_GET['action'];
if(isset($_GET['varID'])) 	   $varID      = $_GET['varID'];
if(isset($_GET['reportDate'])) $reportDate = $_GET['reportDate'];
if(isset($_GET['sec_chklst'])) $sec_chklst = $_GET['sec_chklst'];
if(isset($_GET['supervisor'])) $supervisor = $_GET['supervisor'];
if(isset($_GET['event'])) 	   $event	   = $_GET['event'];
if(isset($_GET['porName']))    $porName    = $_GET['porName'];
if(isset($_GET['porInv'])) 	   $porInv     = $_GET['porInv'];
if(isset($_GET['porPhone']))   $porPhone   = $_GET['porPhone'];
if(isset($_GET['porLicense'])) $porLicense = $_GET['porLicense'];
if(isset($_GET['porNotes']))   $porNotes   = $_GET['porNotes'];

//*****************************************************************************
// 								Post Back
//*****************************************************************************

// Cancel option to return user to incident report without saving changes
//	to a person of interest
if($action == 'Cancel')
{
	$page = "location:incident.php?action=view&ineID=$ineID&varID=$varID";
	header($page);
}
//______________________________________________________________________________

// Delete option - sets Reason_for_Del field - value for that attribute in no
//	long null, so record is "deleted" 
if($action == 'Delete')
{
	// send to delete page
	$page = "location:delete.php?action=deletePor&varID=$varID&ineID=$ineID&porID=$porID";
	header($page);
}
//______________________________________________________________________________

// Save option - updates or creates a new record in the database 
if($action == 'Save')
{
	
	if($porID != null)	// we have an ID, so we must be updating!
	{
		$sql  = "SELECT * FROM person_of_record";
		$sql .= " WHERE POR_ID=$porID";
		
		$con = $myCon->connect();
		$result = mysqli_query($con, $sql);
		
		
		while($row = mysqli_fetch_array($result))
		{
			if($porName != $row['POR_Name'])
			{
				// update 
				$porName = substr(mysqli_real_escape_string($con, $porName), 0, 45);
				$sql  = porUpdate('POR_Name', $porName, $porID, $reason_for_delete = NULL, $con );
				$new  = mysqli_query($con, $sql);
				// auditing entry
				$sql  = "INSERT INTO modification_por (User_USE_ID, Person_of_Record_POR_ID, MOD_Action)";
				$sql .= " VALUES($userID, $porID, 'POR_Name')";
				$new  = mysqli_query($con, $sql);
			}
			if($porInv != $row['Involvement_Lookup_INV_Level'])
			{
				// update 
				$porInv = mysqli_real_escape_string($con, $porInv);
				$sql = porUpdate('Involvement_Lookup_INV_Level', $porInv, $porID, $reason_for_delete = NULL, $con );
				$new = mysqli_query($con, $sql);
				// auditing entry
				$sql  = "INSERT INTO modification_por (User_USE_ID, Person_of_Record_POR_ID, MOD_Action)";
				$sql .= " VALUES($userID, $porID, 'Involvement_Lookup_INV_Level')";
				$new  = mysqli_query($con, $sql);
			}
			if($porPhone != $row['POR_Phone'])
			{
				// update 
				$porPhone = substr(mysqli_real_escape_string($con, $porPhone), 0, 12);
				$sql = porUpdate('POR_Phone', $porPhone, $porID, $reason_for_delete = NULL, $con );
				$new = mysqli_query($con, $sql);
				// auditing entry
				$sql  = "INSERT INTO modification_por (User_USE_ID, Person_of_Record_POR_ID, MOD_Action)";
				$sql .= " VALUES($userID, $porID, 'POR_Phone')";
				$new  = mysqli_query($con, $sql);
			}
			if($porLicense != $row['POR_License'])
			{
				// update 
				$porLicense = substr(mysqli_real_escape_string($con, $porLicense), 0, 25);
				$sql = porUpdate('POR_License', $porLicense, $porID, $reason_for_delete = NULL, $con );
				$new = mysqli_query($con, $sql);
				// auditing entry
				$sql  = "INSERT INTO modification_por (User_USE_ID, Person_of_Record_POR_ID, MOD_Action)";
				$sql .= " VALUES($userID, $porID, 'POR_License')";
				$new  = mysqli_query($con, $sql);
			}
			if($porNotes != $row['POR_Notes'])
			{
				// update 
				$sql = porUpdate('POR_Notes', $porNotes, $porID, $reason_for_delete = NULL, $con );
				$new = mysqli_query($con, $sql);
				// auditing entry
				$sql  = "INSERT INTO modification_por (User_USE_ID, Person_of_Record_POR_ID, MOD_Action)";
				$sql .= " VALUES($userID, $porID, 'POR_Notes')";
				$new  = mysqli_query($con, $sql);
			}
		}
		
		mysqli_close($con);
		$page = "location:incident.php?action=view&ineID=$ineID&varID=$varID";
		header($page);	//adios, muchachos
	}
	
	if($porID == null)		// no ID, let's make one by creating a new record
	{
		$con = $myCon->connect();
		
		// clean it up
		$porName    = substr(mysqli_real_escape_string($con, $porName), 0, 45);
		$porPhone   = substr(mysqli_real_escape_string($con, $porPhone), 0, 12);
		$porLicense = substr(mysqli_real_escape_string($con, $porLicense), 0, 25);
		$porNotes   = mysqli_real_escape_string($con, $porNotes);
		
		$sql  = "INSERT INTO person_of_record";
		$sql .= " (";
		$sql .= "incident_entry_INE_ID";
		$sql .= ", incident_entry_var_VAR_ID";
		$sql .= ", POR_Name";
		$sql .= ", POR_Phone";
		$sql .= ", POR_License";
		$sql .= ", POR_Notes";
		$sql .= ", involvement_lookup_INV_Level";
		$sql .= ") VALUES (";
		$sql .= "$ineID";
		$sql .= ", $varID";
		$sql .= ", '$porName'";
		$sql .= ", '$porPhone'";
		$sql .= ", '$porLicense'";
		$sql .= ", '$porNotes'";
		$sql .= ", '$porInv'";
		$sql .= ")";
				
		echo $sql;
		mysqli_query($con, $sql);
		mysqli_close($con);
		$page  = "location:incident.php?action=view";
		$page .= "&ineID=$ineID";
		$page .= "&varID=$varID";
		
		header($page);
	}
}
//_____________________________________________________________________________

// We're not Saving, Cancelling, or Deleting...
//	so we must just wanna look at the thing
// 	Get a record and prepare to send it to the object
if($porID != null)
{
	$sql  = "SELECT * FROM person_of_record";
	$sql .= " WHERE (";
	$sql .= " POR_ID=$porID";
	$sql .= ")";
	
	$con = $myCon->connect();
	$result = mysqli_query($con, $sql);
	if(mysqli_num_rows($result) != 0)
	{
		
		
		while($row = mysqli_fetch_array($result))
		{
			$porDetails[0] = $row['POR_ID'];
			$porDetails[1] = $row['POR_Name'];
			$porDetails[2] = $row['Involvement_Lookup_INV_Level'];
			$porDetails[3] = $row['POR_Phone'];
			$porDetails[4] = $row['POR_License'];
			$porDetails[5] = $row['POR_Notes'];
			$porDetails[6] = $ineID;
			$porDetails[7] = $varID;
			
			$Por[0] = new Por(1, $porDetails);
		}
	}
	mysqli_close($con);
} 
else 	// no ID provided, so we will get ready make a blank object
{
	$porDetails[0] = null;
	$porDetails[1] = null;
	$porDetails[2] = null;
	$porDetails[3] = null;
	$porDetails[4] = null;
	$porDetails[5] = null;
	$porDetails[6] = $ineID;
	$porDetails[7] = $varID;
	
	$Por[0] = new Por(1, $porDetails);
}

//*****************************************************************************
createHead('incident.css');
createHeader($fullName);
createNav($userAuth);

//==========================    Begin Content    =============================
echo "<div id='content'>\n";
$html  = "<div id='IncidentForm'>\n";
$html .= "	<form action='managePor.php' method='GET'>\n";
$html .= "<div id='PorField'>\n";
$html .= $Por[0]->drawPor('enabled');
$html .= "</div>\n"; //close PorField
$html .= "<input type='hidden' name='ineID' value='$ineID'>";
$html .= "<input type='hidden' name='varID' value='$varID'>";
$html .= "</form>\n";	
$html .= "<div id='clear'></div>";
$html .= "</div>"; // close IncidentForm
echo $html;
echo "</div>\n"; // close Content
//=============================================================================
createFoot();
?>