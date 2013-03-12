<?php
/**
 * manageReports.php
 * Purpose: to list one month's VARs with most recent at top
 * 			users will navigate here to create a new report
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
					
//*****************************************************************************
createHead('manageReports.css');
createHeader($fullName);
createNav($userAuth);

//==========================    Begin Content    =============================
echo "<div id='content'>\n";
echo "<a href='manageVars.php?action=create'><button id='Button_NewReport'>";
echo "Create New Report</button></a><br />\n";

$date_ts     = strtotime($date);
$year		 = date('Y', $date_ts);
$day_ts		 = date('+1 month', $date_ts);
$day_ts		 = strtotime('-1 days', strtotime($date_ts));
$day		 = date('d', $day_ts);
$lastYear_ts = strtotime('-1 year', $date_ts);
$lastYear	 = date('Y', $lastYear_ts);

// make combo boxes
//_____________________________________________________________________________

if(isset ($_GET['ReportPeriodMonth']))
{
	$month 		   = $_GET['ReportPeriodMonth'];
	$selectedYear  = $_GET['ReportPeriodYear'];
}
else
{
	$month        = date('m', $date_ts);
	$selectedYear = $year;
}

$html  = "<div id='ReportPeriod'>\n";
$html .= "<form action='manageReports.php' method='GET'>";
$html .= "	<select name='ReportPeriodMonth' selected='" . $month . "'>\n";
$html .= "		<option value='01'";
if($month == '01') $html .= " selected='selected'";
$html .= ">January</option>\n";
$html .= "		<option value='02'";
if($month == '02') $html .= " selected='selected'";
$html .= ">February</option>\n";
$html .= "		<option value='03'";
if($month == '03') $html .= " selected='selected'";
$html .= ">March</option>\n";
$html .= "		<option value='04'";
if($month == '04') $html .= " selected='selected'";
$html .= ">April</option>\n";
$html .= "		<option value='05'";
if($month == '05') $html .= " selected='selected'";
$html .= ">May</option>\n";
$html .= "		<option value='06'";
if($month == '06') $html .= " selected='selected'";
$html .= ">June</option>\n";
$html .= "		<option value='07'";
if($month == '07') $html .= " selected='selected'";
$html .= ">July</option>\n";
$html .= "		<option value='08'";
if($month == '08') $html .= " selected='selected'";
$html .= ">August</option>\n";
$html .= "		<option value='09'";
if($month == '09') $html .= " selected='selected'";
$html .= ">September</option>\n";
$html .= "		<option value='10'";
if($month == '10') $html .= " selected='selected'";
$html .= ">October</option>\n";
$html .= "		<option value='11'";
if($month == '11') $html .= " selected='selected'";
$html .= ">November</option>\n";
$html .= "		<option value='12'";
if($month == '12') $html .= " selected='selected'";
$html .= ">December</option>\n";
$html .= "	</select>\n";
$html .= "	<select name='ReportPeriodYear'>\n";
$html .= "		<option value='" . $year . "'";
if($selectedYear == $year) $html .= " selected='selected'";
$html .= ">" . $year . "</option>\n";
$html .= "		<option value='" . $lastYear . "'";
if($selectedYear == $lastYear) $html .= " selected='selected'";
$html .= ">" . $lastYear . "</option>\n";
$html .= "	</select>\n";
$html .= "<input type='submit' value='Go'>\n";
$html .= "</form>\n";
$html .= "</div>\n<br />\n";

echo $html;
//_____________________________________________________________________________
// Create VAR List
//_____________________________________________________________________________

$startDate = $selectedYear . "-" . $month . "-" . $day;
$endDate = $selectedYear . "-" . $month . "-" . "-01";

$sql = varList($venueID, $startDate, $endDate);

$con = $myCon->connect();
$result = mysqli_query($con, $sql); 

$html  = '	<table id="ListReports">' . "\n";
$html .= '		<tr>' . "\n";
$html .= '			<th>Date</th><th>Event</th><th>Attendance</th>';
$html .= '			<th>Incidents.</th><th>Police Inv.</th>' . "\n";
$html .= '		</tr>' . "\n";

	        while($row = mysqli_fetch_array($result))
	        {
	        	$html .= '		<tr>' . "\n";
		        $html .= '			<td><a href="manageVars.php?action=view&varID=';
				$html .= $row['VAR_ID'] . '">';
		        $html .= $row['VAR_Date'] . '</a></td><td>';
				$html .= $row['VAR_Event'] . '</td><td>';
				$html .= $row['VAR_Attend'] . "</td><td>";
				$html .= findIncidents($row['VAR_ID'], $con) . '</td><td>';
				if(findPoliceInv($row['VAR_ID'], $con)) $html .= "Yes</td>\n";
				else $html .=  "No</td>\n";
		        $html .= "		</tr>";
	        }

$html .= "\n" . '	</table>' . "\n";

echo $html;
mysqli_close($con);
echo "</div>\n";
//=============================================================================
createFoot();
?>