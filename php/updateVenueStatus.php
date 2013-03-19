<?php
	/*
		updateVenueStatus.php
		Purpose: updates the active status of a venue
		@param POST['status'] The new status for the venue
		@param POST['id'] The id number of the venue to be updated
		@return true on successful update, false if there was an error
	*/
	include_once "connection.php";
	include_once "venueFunctions.php";
	$field = array("Status");
	$status = array($_POST['status']);
	$myCon = new Connection();
	$con = $myCon->connect();
	$sql = venueUpdate($field, $status, $_POST['id'], $con);
	if(mysqli_query($con, $sql)){
		echo "Venue status has been updated";
	}	
	else{
		echo "Error: Could not update venue status";
	}
	// var_dump($result);
	mysqli_close($con);
?>