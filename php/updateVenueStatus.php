<?php
	/*
		updateVenueStatus.php
		Purpose: updates the active status of a venue
		By: Justin Werre
		@param POST['status'] The new status for the venue
		@param POST['id'] The id number of the venue to be updated
		@return string indicating status of update.
	*/
	include_once "connection.php";
	include_once "venueFunctions.php";
	$field = array("Status");
	$status = array($_POST['status']);
	$myCon = new Connection();
	$con = $myCon->connect();
	$sql = venueUpdate($field, $status, $_POST['id'], $con);
	if(mysqli_query($con, $sql)){
		if($_POST['status'] == 1)
			echo "The venue status has been set to active";
		else
			echo "The venue status has been set to deactive";
	}	
	else{
		echo "Error: Could not update venue status";
	}
	// var_dump($result);
	mysqli_close($con);
?>