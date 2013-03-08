<?php
/**
 * news.php
 * Purpose: to list one month's news for a specific region.
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
$regID = $_SESSION['regID'];
$fullName = $userFname . " " . $userLname;
date_default_timezone_set('UTC');
$date = date('Y-m-d H:i:s', time());
					
//*****************************************************************************
createHead('news.css');
createHeader($fullName);
createNav($userAuth);

//==========================    Begin Content    =============================
echo "<div id='content'>\n";


$date_ts     = strtotime($date);
$year		 = date('Y', $date_ts);
$day_ts		 = date('+1 month', $date_ts);
$day_ts		 = strtotime('-1 days', strtotime($date_ts));
$day		 = date('d', $day_ts);
$lastYear_ts = strtotime('-1 year', $date_ts);
$lastYear	 = date('Y', $lastYear_ts);

// make combo boxes
//_____________________________________________________________________________

if(isset ($_GET['NewsPeriodMonth']))
{
	$month 		   = $_GET['NewsPeriodMonth'];
	$selectedYear  = $_GET['NewsPeriodYear'];
}
else
{
	$month        = date('m', $date_ts);
	$selectedYear = $year;
}
if(isset ($_GET['NewsType'])) $selectedType = $_GET['NewsType'];
else $selectedType = null;
$html  = "<div id='NewsPeriod'>\n";
$html .= "<form action='news.php' method='GET'>";
$html .= "	<label class='comboLabel'>Displaying News for Month:</label>";
$html .= "	<select name='NewsPeriodMonth' selected='" . $month . "'>\n";
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
$html .= "	<select name='NewsPeriodYear'>\n";
$html .= "		<option value='" . $year . "'";
if($selectedYear == $year) $html .= " selected='selected'";
$html .= ">" . $year . "</option>\n";
$html .= "		<option value='" . $lastYear . "'";
if($selectedYear == $lastYear) $html .= " selected='selected'";
$html .= ">" . $lastYear . "</option>\n";
$html .= "	</select><br />\n";
$html .= "	<label class='comboLabel'>News Reel:</label>";
$html .= "	<select name='NewsType'>\n";
$html .= "		<option value='02'";
if($selectedType == '02') $html .= " selected='selected'";
$html .= ">Clubwatch</option>\n";
$html .= "		<option value='01'";
if($selectedType == '01') $html .= " selected='selected'";
$html .= ">Industry</option>\n";
$html .= "	</select><br />\n";
$html .= "<input type='submit' value='Get'>\n";
$html .= "</form>\n";
$html .= "</div>\n<br />\n";	// Close News Period

echo $html;
//_____________________________________________________________________________
// Create VAR List
//_____________________________________________________________________________

$startDate = $selectedYear . "-" . $month . "-" . $day;
$endDate = strtotime($startDate);
$endDate = strtotime('-30 days', $endDate);
$endDate = date('Y-m-d', $endDate);

$sql  = "SELECT * FROM news";
$sql .= " JOIN news_region_assc";
$sql .= " ON (news.NEW_ID = news_region_assc.News_NEW_ID)";
$sql .= " WHERE (news.NEW_Date <='" . $startDate . "'";
$sql .= " AND news.NEW_Date >='" . $endDate . "'";
$sql .= " AND news.NEW_Type=" . $selectedType;
$sql .= " AND news_region_assc.region_REG_ID=" . $regID . ")";

$html  = "<div id='clear'></div>\n";
$html .= "<div id='NewsReel'>\n";
$con = $myCon->connect();
$result = mysqli_query($con, $sql);
		    	while($row = mysqli_fetch_array($result))
		        {
			        $html .= '	<h4>' . niceDate($row[1]) . "</h4>\n";
					$html .= '	' . $row[2];
			        $html .= "<br />\n";
		        }
mysqli_close($con);
$html .= "</div>\n"; // close NewsReel

echo $html;

echo "</div>\n"; // close content
//=============================================================================
createFoot();
?>