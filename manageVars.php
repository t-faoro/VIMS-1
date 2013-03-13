<?php
/**
 * manageVars.php
 * displays page for creating or updating/viewing a var
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
$notice = null;
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
if($action == 'Cancel') header('location: manageReports.php');
if(isset($_GET['error']))     $error 	   = $_GET['error'];
else $error = null;
if(isset($_GET['varID']))
{
	$varID	   = $_GET['varID'];
	if($action == 'Add an Incident')
	{
		$page = "location:";
		$ine  = "incident.php?reportDate=" . $reportDate . "&attendance=" . $attendance;
		$ine .= "&sec_chklst=" . $sec_chklst . "&supervisor=" . $supervisor;
		$ine .= "&event=" . $event;
		header($page . $ine);
	}
	
//*****************************************************************************
//							Action = Update
//*****************************************************************************
if($action == 'Update')
{
	$con = $myCon->connect();
	
	$sql  = "SELECT * FROM var";
	$sql .= " WHERE(VAR_Date='" . $reportDate ."'";
	$sql .= " AND VAR_Reason_for_Del IS NULL)";
	
	$result = mysqli_query($con, $sql);
	$counter = 0;
	$field[0] = null;
	while($row = mysqli_fetch_array($result))
	{
		if($attendance != $row['VAR_Attend'])
		{
			$field[$counter]   = "Attend";
			$content[$counter] = $attendance;
			$counter++;
		}
		if($sec_chklst != $row['VAR_Sec_Chklst'])
		{
			$field[$counter]   = "Sec_Chklst";
			$content[$counter] = $sec_chklst;
			$counter++;
		}
		if($supervisor != $row['VAR_Supervisor'])
		{
			$field[$counter]   = "Supervisor";
			$content[$counter] = $supervisor;
			$counter++;
		}
		if($event		!= $row['VAR_Event'])
		{
			$field[$counter]   = "Event";
			$content[$counter] = $event;
			$counter++;
		}
	}
	if($field[0] != null)
	{
		$sql = varUpdate($field, $content, $varID, $con);
		mysqli_query($con, $sql);
		$notice = "<script type='text/javascript'>window.alert('Update Complete!')</script>";
	}
	
	
	mysqli_close($con);
}
	$con = $myCon->connect();
	
	$sql = varRead($varID);
	
	$result = mysqli_query($con, $sql);
	while($row = mysqli_fetch_array($result))
	{
		$reportDate = $row['VAR_Date'];
		$attendance = $row['VAR_Attend'];
		$sec_chklst = $row['VAR_Sec_Chklst'];
		$supervisor = $row['VAR_Supervisor'];
		$event		= $row['VAR_Event'];
	}
	
	mysqli_close($con);
}
else $varID = null;
//*****************************************************************************
//						Action = Finished or Add new Incident
//*****************************************************************************

if($action == 'Finished'
	|| $action == 'Add new Incident')
{
	$con = $myCon->connect();
	$sql  = "SELECT COUNT(*) FROM var";
	$sql .= " WHERE(VAR_Date='" . $reportDate ."'";
	$sql .= " AND VAR_Reason_for_Del IS NULL)";
	$result = mysqli_query($con, $sql);
	$check = mysqli_fetch_array($result);
	
	// check for duplicate var
	if($check[0] == 0)
	{
		$sql = varCreate($reportDate, $attendance, $sec_chklst, $supervisor, $event, $venueID, $userID, $con);
		mysqli_query($con, $sql);
		$page = "location:";
		$ine  = "incident.php?reportDate=" . $reportDate . "&attendance=" . $attendance;
		$ine .= "&sec_chklst=" . $sec_chklst . "&supervisor=" . $supervisor;
		$ine .= "&event=" . $event;
		if($action == 'Finished' ? $page .= 'manageReports.php' : $page .= $ine)
		mysqli_close($con);
		header($page);
	}

	else 
	{
		header('location: manageVars.php?action=create&error=1');
	} 
}

//*****************************************************************************
//							Action = Delete
//*****************************************************************************
if($action == 'Delete')
{
	$con = $myCon->connect();
	$field[0] = "Reason_for_Del";
	$content[0] = "test";
	$sql = varUpdate($field, $content, $varID, $con);
	mysqli_query($con, $sql);
	mysqli_close($con);
	header('location: manageReports.php');
}


//*****************************************************************************
//							Action = done
//*****************************************************************************
if($action == 'Done') header('location: manageReports.php');

//*****************************************************************************
createHead('manageVars.css', 'datepicker.js');
createHeader($fullName);
createNav($userAuth);
//*****************************************************************************

//==========================    Begin Content    =============================
echo "<div id='content'>\n";

echo "	<h3>Venue Activity Report</h3>\n";
if($error == 1) echo "A report for that night already exists!";
$html  = "	<div id='VarInputForm'>\n";
$html .= "		<form action='manageVars.php' method='GET'>\n";
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
if($sec_chklst == 1) $html .= " selected='selected'";
$html .= ">Yes</option><option value='0'";
if($sec_chklst == 2) $html .= " selected='selected'";
$html .= ">No</option></select></td>\n";
$html .= "				</tr>\n";
$html .= "			</table>\n";
if($varID != null) $html .= "			<input type='hidden' name='varID' value='" . $varID . "'>";
if($action == 'create')
{
	$html .= "			<input type='submit' name='action' value='Cancel'>";
	$html .= "			<input type='submit' name='action' value='Add new Incident'>";
	$html .= "			<input type='submit' name='action' value='Finished'>";
}
if($action == 'view' || $action == 'Update')
{
	$html .= "			<input type='submit' name='action' value='Add an Incident'>";
	$html .= "			<input type='submit' name='action' value='Update'>";
	$html .= "			<input type='submit' name='action' value='Delete'>";
	$html .= "			<input type='submit' name='action' value='Done'>";
}
$html .= "		</form>\n";
$html .= "	</div>\n";
$html .= "	<div id='IneTableArea'>\n";
if($action == 'view' || $action == 'Update')
{
	$con = $myCon->connect();
	
	$sql  = "SELECT * FROM incident_entry";
	$sql .= " WHERE (var_VAR_ID='" . $varID . "'";
	$sql .= " AND INE_Reason_for_Del IS NULL)";
	
	$result = mysqli_query($con, $sql);
	if(mysqli_num_rows($result) != 0)
	{
		$html .= "<table id='IneList'>\n";
		$html .= "<tr>\n";
		$html .= "	<th>Incident Time</th>\n";
		$html .= "	<th>Incident Level</th>\n";
		$html .= "	<th>Police Inv.</th>\n";
		$html .= "	<th>People</th>\n";
		$html .= "	<th>Images</th>\n";
		$html .= "</tr>\n";
		
		while($row = mysqli_fetch_array($result))
		{
			$link  = "incident.php?action=view&ineID=" . $row['INE_ID'];
			$link .= "&reportDate=" . $reportDate;
			$link .= "&sec_chklst=" . $sec_chklst;
			$link .= "&attendance=" . $attendance;
			$link .= "&supervisor=" . $supervisor;
			$link .= "&event=" . $event;
			
			$html .= "<tr>\n";
			$html .= "<td><a href='" . $link . "'>" .$row['INE_Time'] . "</a></td>\n";
			$html .= "<td>" . $row['Incident_Level_Lookup_ILL_Level'] . "</td>\n";
			if($row['INE_Police'] == 1 ? $bool = "Yes" : $bool = "No");
			$html .= "<td>" . $bool . "</td>\n";
			
			$sql  = "SELECT COUNT(*) FROM person_of_record";
			$sql .= " WHERE (incident_entry_INE_ID =" . $row['INE_ID'];
			$sql .= " AND POR_Reason_for_Del IS NULL)";
			
			$newResult = mysqli_query($con, $sql);
			if($row1 = mysqli_fetch_array($newResult)) $num = $row1[0];
			else $num = 0;
			$html .= "<td>" . $num . "</td>";
			
			$sql  = "SELECT COUNT(*) FROM Images";
			$sql .= " WHERE (incident_entry_INE_ID =" . $row['INE_ID'];
			$sql .= " AND IMG_Archived IS NULL)";
			
			$newResult = mysqli_query($con, $sql);
			if($row1 = mysqli_fetch_array($newResult)) $num = $row1[0];
			else $num = 0;
			$html .= "<td>" . $num . "</td>";
			
		}
		
		$html .= "</table>\n";
	}
	mysqli_close($con);
}
$html .= "	</div>"; // close IneTableArea
if($notice != null) $html .= $notice;
echo $html;

echo "</div>\n";
//=============================================================================
createFoot();
?>