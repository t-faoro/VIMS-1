<?php

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
	date_default_timezone_set('UTC');
	for($i = 0; $i < 30; $i++){
		$queryDate = $date . " 00:00:00";
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
	
	echo $num[29];
	for($i = 28; $i >= 0; $i--){
		echo "," . $num[$i];
	}
	
}

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
	for($i = 0; $i < 30; $i++){
		$queryDate = $date . " 00:00:00";
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
	
	echo $num[29];
	for($i = 28; $i >= 0; $i--){
		echo "," . $num[$i];
	}
	
}
?>