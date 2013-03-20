<?php
/**
 * delete.php
 * provides delete functionality for person of record, incident, and var tables
 * 	prompts user for reason they are deleting record and updates
 * 	record Reason_for_Del field with text entered 
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

if(isset($_GET['action']))	$action = $_GET['action'];

createHead('incident.css');
echo "<body>\n";
//*****************************************************************************
//							Action == deleteVar
//*****************************************************************************
if($action == 'deleteVar')
{
	if(isset($_GET['varID']))	$varID = $_GET['varID'];
	
	if(isset($_GET['reason']))	
	{
		$reason = "user ID: " . $userID . " Reason given: " .$_GET['reason'];
		
		$con = $myCon->connect();
		$reason = mysqli_real_escape_string($con, $reason);
		$sql  = "UPDATE var SET VAR_Reason_for_Del='$reason' WHERE VAR_ID=$varID";
		
		mysqli_query($con, $sql);
		
		// done
		header('location: manageReports.php');
	}
	else 
	{
	
	?>
	<script type="text/javascript">
		window.onload = getReason;
		function getReason(){
			var reason = prompt("Enter reason for deleting this record", "");
			window.location.href = "delete.php?action=deleteVar&reason=" + reason + "&varID=<?php echo $varID; ?>";
		}
		
	</script>
	
	<?php
	}
}

//*****************************************************************************
//							Action == deleteIne
//*****************************************************************************
if($action == 'deleteIne')
{
	if(isset($_GET['varID']))	$varID = $_GET['varID'];
	if(isset($_GET['ineID']))	$ineID = $_GET['ineID'];
	
	if(isset($_GET['reason']))	
	{
		$reason = "user ID: " . $userID . " Reason given: " .$_GET['reason'];
		
		$con = $myCon->connect();
		$reason = mysqli_real_escape_string($con, $reason);
		$sql  = "UPDATE incident_entry SET INE_Reason_for_Del='$reason' WHERE INE_ID=$ineID";
		
		mysqli_query($con, $sql);
		
		// done
		header('location:manageVars.php?action=view&varID=' . $varID);
	}
	else 
	{
	
	?>
	<script type="text/javascript">
		window.onload = getReason;
		function getReason(){
			var reason = prompt("Enter reason for deleting this record", "");
			window.location.href = "delete.php?action=deleteIne&reason=" + reason
				 + "&varID=<?php echo $varID; ?>"
				 + "&ineID=<?php echo $ineID; ?>";
		}
		
	</script>
	
	<?php
	}
	
}

//*****************************************************************************
//							Action == deletePor
//*****************************************************************************
if($action == 'deletePor')
{
	if(isset($_GET['varID']))	$varID = $_GET['varID'];
	if(isset($_GET['ineID']))	$ineID = $_GET['ineID'];
	if(isset($_GET['porID']))	$porID = $_GET['porID'];
	
	if(isset($_GET['reason']))	
	{
		$reason = "user ID: " . $userID . " Reason given: " .$_GET['reason'];
		
		$con = $myCon->connect();
		$reason = mysqli_real_escape_string($con, $reason);
		$sql  = "UPDATE person_of_record SET POR_Reason_for_Del='$reason' WHERE POR_ID=$porID";
		
		mysqli_query($con, $sql);
		
		// done
		$page = "location:incident.php?action=view&ineID=$ineID&varID=$varID";
		header($page);
	}
	else 
	{
	
	?>
	<script type="text/javascript">
		window.onload = getReason;
		function getReason(){
			var reason = prompt("Enter reason for deleting this record", "");
			window.location.href = "delete.php?action=deletePor&reason=" + reason
				 + "&varID=<?php echo $varID; ?>"
				 + "&ineID=<?php echo $ineID; ?>"
				 + "&porID=<?php echo $porID; ?>";
		}
		
	</script>
	
	<?php
	}
		
}

//*****************************************************************************

echo "</body>\n";
echo "</hmtl>\n";
?>