<?php
/**
 * dashboardFunctions.php
 * contains library of functions for use in the dashboard
 * @author James P. Smith March 2013
 * */
// ============================================================================
//							Functions
// ============================================================================
/**
 * getAttendValues() gets attendance numbers from vars associated with
 * 		specified venue
 * 
 * @param $venueID contains venue ID [int]
 * @param $date	   contains current date [datetime]
 * @param $con 	   database connection [resource]
 * 
 */
function getAttendValues($venueID, $date, $con)
{
	$date_ts = strtotime($date); 
	$date    = date('Y-m-d', $date_ts);
	for($i = 0; $i < 45; $i++){
		$queryDate = $date;
		$sql = varAttend($venueID, $queryDate, $con);
		if($sql == "error") echo "Error";
		else {
			$result = mysqli_query($con, $sql);
			
			
			
			if($row = mysqli_fetch_array($result))
	        {
		        $num[$i] = $row[0];
		    } else $num[$i] = 0;
			
			$date_ts = strtotime($date);
			$date_dt = strtotime('-1 day', $date_ts);
			$date    = date('Y-m-d', $date_dt);
		}
	}
	
	echo $num[44];
	for($i = 43; $i >= 0; $i--){
		echo "," . $num[$i];
	}
	
}
// ============================================================================
/**
 * getIncidentValues() gets gets number of incidents on vars for specified
 * 		dates for last 30 days
 * 
 * @param $venueID contains venue ID [int]
 * @param $date	   contains date value YYYY-MM-DD [date]
 * @param $con 	   database connection [resource]
 * 
 */
function getIncidentValues($venueID, $date, $con)
{
	date_default_timezone_set('UTC');
	for($i = 0; $i < 45; $i++){
		$queryDate = $date;
		$sql = varIncidents($venueID, $queryDate, $con);
		
		if($sql == "error") echo "Error";
		else {
			$result = mysqli_query($con, $sql);
			
			
			
			if($row = mysqli_fetch_array($result))
	        {
		        $num[$i] = $row[0];
		    } else $num[$i] = 0;
			
			$date_ts = strtotime($date);
			$date_dt = strtotime('-1 day', $date_ts);
			$date    = date('Y-m-d', $date_dt);
		}
	}
	
	echo $num[44];
	for($i = 43; $i >= 0; $i--){
		echo "," . $num[$i];
	}
	
}
// ============================================================================
/**
 * getRegionVars() counts number of vars for specified region on specfied date
 * 		and echos the values as a comma seperated list - for sparkline
 * 
 * @param $regID   contains region ID [int]
 * @param $date	   contains date value YYYY-MM-DD [date]
 * @param $con 	   database connection [resource]
 * 
 */
function getRegionVars($regID, $date, $con)
{
	date_default_timezone_set('UTC');
	for($i = 0; $i < 14; $i++){
		$queryDate = $date;
		$sql  = "SELECT COUNT(*) FROM venue";
		$sql .= " JOIN var ON";
		$sql .= " (venue.VEN_ID = var.venue_VEN_ID)";
		$sql .= " WHERE (venue.region_REG_ID=" . $regID;
		$sql .= " AND var.VAR_Date='" . $date;
		$sql .= "')";

		if($sql == "error") echo "Error";
		else {
			$result = mysqli_query($con, $sql);
			
			
			
			if($row = mysqli_fetch_array($result))
	        {
		        $num[$i] = $row[0];
		    } else $num[$i] = 0;
			
			$date_ts = strtotime($date);
			$date_dt = strtotime('-1 day', $date_ts);
			$date    = date('Y-m-d', $date_dt);
		}
	}
	
	echo $num[13];
	for($i = 12; $i >= 0; $i--){
		echo "," . $num[$i];
	}
	
}
// ============================================================================
/**
 * getDashNews() return sql for retrieving Industry News for Dashboard
 * @param $regID   contains region ID [int]
 * @param $date	   contains datetime value YYYY-MM-DD HH:MM:SS [datetime]
 * @param $type	   contains type of news to return [int]
 * 
 * @return $sql	   contains an sql statement
 * 
 */
function getDashNews($regID, $date, $type)
{
	// find end Date 30 days previous
 	$date_ts    = strtotime($date);
	$date_dt    = strtotime('-30 days', $date_ts);
	$endDate    = date('Y-m-d', $date_dt);
	
 	$sql  = "SELECT * FROM news";
 	$sql .= " JOIN news_region_assc";
	$sql .= " ON (news.NEW_ID = news_region_assc.News_NEW_ID)";
	$sql .= " WHERE (news.NEW_Date <='" . $date . "'";
	$sql .= " AND news.NEW_Date >='" . $endDate . "'";
	$sql .= " AND news.NEW_Type=" . $type;
	$sql .= " AND (news_region_assc.region_REG_ID=" . $regID;
	$sql .= " OR news_region_assc.region_REG_ID<100))";
	$sql .= " LIMIT 2";
	
	return $sql;
	
}
// ============================================================================
/**
 * findIncidents() returns a count of incidents for a specified var ID
 * @param $varID  contains var ID to search on [int]
 * @param $con	  database conection [resource]
 * 
 * @return $sql	   contains an sql statement
 * 
 */
function findIncidents($varID, $con)
{
 	$sql  = "SELECT COUNT(*) FROM incident_entry";
	$sql .= " WHERE (var_VAR_ID =" . $varID;
	$sql .= " AND INE_Reason_for_Del IS NULL";
	$sql .= ")";

	$results = mysqli_query($con, $sql);
	if($row = mysqli_fetch_array($results)) $num = $row[0];
	else $num = 0;
	
	
	return $num;
}
// ============================================================================
/**
 * findPoliceInv() returns the number of incidents that had police involvement
 * 		on a specified var ID
 * @param $varID  contains var ID to search on [int]
 * @param $con	  database conection [resource]
 * 
 * @return $sql	   contains an sql statement
 * 
 */
function findPoliceInv($varID, $con)
{
	$sql  = "SELECT COUNT(*) FROM incident_entry";
	$sql .= " WHERE (var_VAR_ID =" . $varID;
	$sql .= " AND INE_Reason_for_Del IS NULL";
	$sql .= " AND INE_Police=1)";

	$results = mysqli_query($con, $sql);
	if($row = mysqli_fetch_array($results)) $num = $row[0];
	else $num = 0;
	
	
	return $num;
}
// ============================================================================
/**
 * findRegID() returns the region ID of a venue
 * @param $venueID  contains venue ID to search on [int]
 * @param $con	    database conection [resource]
 * 
 * @return $sql	   contains an sql statement
 * 
 */
function getRegID($venueID, $con)
{
	$sql  ="SELECT Region_REG_ID FROM venue";
	$sql .= " WHERE VEN_ID=" . $venueID;
	
	$results = mysqli_query($con, $sql);
	if($row = mysqli_fetch_array($results)) $regID = $row[0];
	
	return $regID;
	
}
?>