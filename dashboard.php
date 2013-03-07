<?php 
/**
	dashboard.php
	Purpose: Home page for all system users
	@author James P. Smith March 2013
*/

session_start();
include_once "php/config.php";

if(!verifyUser()) header('location: index.php');

$css = "dashboard.css";
$js  = "sparkline.js";

echo "<!DOCTYPE html>\n";
echo "<html>\n";
echo "<head>\n";
echo "<title>".SITE_TITLE."</title>\n";
echo CSS("style.css"); 
if($css != null)
{
	echo CSS($css);
}

echo '<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js">
		</script>' . "\n";
echo '<script type="text/javascript">' . "\n";
echo '$(function() {
		 $(".inlinesparkline").sparkline("html", { enableTagOptions: true });
		
	  });';
echo '</script>' . "\n";
if($js != null)
{
	echo JS($js);
}
echo "<meta charset='UTF-8'>\n";
echo "</head>\n";




//*****************************************************************************
// get variables
$userID  = $_SESSION['userId'];
$userName = $_SESSION['userName'];
$userFname = $_SESSION['userFname'];
$userLname = $_SESSION['userLname'];
$userAuth = $_SESSION['userAuth'];
$venueID = $_SESSION['venueId'];
$venueName = $_SESSION['venueName'];

date_default_timezone_set('UTC');
$date = date('Y-m-d H:i:s', time());
			
					
//*****************************************************************************
createHeader($userName);
//if($userAuth > 0 && $userAuth < 99) echo buildUserNav();
//else buildAdminNav();
//==========================Begin Dashboard Content============================


echo "<div id='content'>\n";
$myCon = new Connection();
$con = $myCon->connect();
$regID = getRegID($venueID, $con);
mysqli_close($con);

// create dashboard graphs
if($userAuth > 0 && $userAuth < 99)			// User Dashboard
{
	$graph  = "class='inlinesparkline' sparkWidth='250px'"; 
	$graph .= " sparkHeight='125px' sparkLineColor='orange' sparkFillColor='yellow'";
	$con = $myCon->connect();							// call db connection
	echo '<div id="DashboardGraphs">' . "\n";
	echo '	<p>' . "\n";
	echo '		<span ' . $graph . '>';
	getAttendValues($venueID, $date, $con); // call function to insert values
	echo '		</span><br>Attendance<br><br>' . "\n";
	echo '		<span ' . $graph . '>';
	getIncidentValues($venueID, $date, $con); // call function to insert values
	echo '		</span><br>Incidents' . "\n";
	echo '	</p>' . "\n";
	echo '</div>' . "\n";
	
	//*****************************************************************************
	// create news feeds
	
	
	echo '<div id="DashboardFeeds">'  . "\n";;
	//_____________________________________________________________________________
	//							Industry News Feed
	//_____________________________________________________________________________
	echo '	<a href=""><h3 class="left">Industry News</h3></a>'  . "\n";;
	
	$type = 1;								// set type to retrive Industry news
	$sql = getDashNews($regID, $date, $type);
	
	$result = mysqli_query($con, $sql);
		    	while($row = mysqli_fetch_array($result))
		        {
			        echo '	' . niceDate($row[1]) . "<br>\n";
					echo '	' . $row[2];
			        echo "<br />";
		        }
			
	
	//_____________________________________________________________________________
	//							Clubwatch Announcements Feed
	//_____________________________________________________________________________
	echo '	<a href=""><h3 class="left">Clubwatch Announcements</h3></a>' . "\n";;
	
	$type = 2;								// set type to retrive Clubwatch news
	$sql = getDashNews($regID, $date, $type);
	
	$result = mysqli_query($con, $sql);
		    	while($row = mysqli_fetch_array($result))
		        {
			        echo '	' . niceDate($row[1]) . "<br>\n";
					echo '	' . $row[2];
			        echo "<br />";
		        }
			
	
	//_____________________________________________________________________________
	//							Latest Activity Report Feed
	//_____________________________________________________________________________
	echo '	<a href=""><h3 class="left">Latest Activity Reports</h3></a>' . "\n";;
	
	$startDate = $date;
	
	$date_ts    = strtotime($date);
	$date_dt    = strtotime('-30 days', $date_ts);
	$endDate    = date('Y-m-d H:i:s', $date_dt);
	
	$sql = varList($venueID, $startDate, $endDate) . " LIMIT 3";
	
	$result = mysqli_query($con, $sql); 
	
	echo '	<table id="DashReports">' . "\n";
	echo '		<tr>' . "\n";
	echo '			<td>Date</td><td>Event</td><td>Attendance</td>';
	echo '			<td>Incidents.</td><td>Police Inv.</td>' . "\n";
	echo '		</tr>' . "\n";
	
		        while($row = mysqli_fetch_array($result))
		        {
		        	echo '		<tr>' . "\n";
			        echo '			<td>' . niceDate($row['VAR_Date']) . '</td><td>';
					echo $row['VAR_Event'] . '</td><td>';
					echo $row['VAR_Attend'] . "</td><td>";
					echo findIncidents($row['VAR_ID'], $con) . '</td><td>';
					if(findPoliceInv($row['VAR_ID'], $con)) echo "Yes</td>\n";
					else echo  "No</td>\n";
			        echo "		</tr>";
		        }
	
	echo "\n" . '	</table>' . "\n";
	echo '</div>' . "\n";;
	echo '<div id="clear"></div>';
	
//_____________________________________________________________________________
	mysqli_close($con);									// close db connection
	}
//*****************************************************************************
//_____________________________________________________________________________
else {
	
												// Create Admin home view
	$graph  = "class='inlinesparkline' sparkWidth='250px'"; 
	$graph .= " sparkHeight='125px' sparkLineColor='orange' sparkFillColor='yellow'";
	$con = $myCon->connect();							// call db connection
	echo '<div id="AdminGraphs">' . "\n";
	
	echo '<h3>Two week History by Region</h3>';
	$sql = "SELECT * FROM region WHERE REG_ID > 99 ORDER BY REG_Name";
	
	$regions = mysqli_query($con, $sql);
	while($regionID = mysqli_fetch_array($regions))
	{
		$regID = $regionID[0];
		
		echo '	<p>' . "\n";
		echo '		<span ' . $graph . '>';
		getRegionVars($regID, $date, $con);
		echo '		</span><br>' . $regionID[1] . '<br><br>' . "\n";
		echo '	</p>' . "\n";
	}

	echo '<div id="clear"></div>';
	//echo '</div>' . "\n";
	//_____________________________________________________________________________
	//							Latest Activity Report Feed
	echo '</div>' . "\n";
}
echo"</div>\n";


createFoot();

?>