<?php
	/**
		deleteUser.php
		Purpose: Updates a user to set their system status to 0, disabling their access to a system.
		@param $_POST['id'] the id number of the user to be disabled
		@param $_POST['venue'] the id number of the venue for which the user is to be denied access
	*/
	include_once "connection.php";
	include_once "userFunctions.php";
	$myCon = new Connection();
	$con = $myCon->connect();
	
	//don't delete the owner if their is only one owner
	$sql = findUser($_POST['id'], $_POST['venue']);
	$responce = mysqli_fetch_array(mysqli_query($con, $sql));
	if(1 == $responce['Auth_Level_Lookup_AUT_Level'])
	{
		$sql = ownerList($_POST['venue']);
		$response = mysqli_fetch_array(mysqli_query($con, $sql));
		if($response[0] == 1)
			return false;
	}
	
	date_default_timezone_set('America/Edmonton');
	$date = date('Y-m-d H:i:s');
	
	$sql = "UPDATE Venue_User_Assc SET VUA_STATUS_CHG='$date', VUA_Sys_Status=0 ";
	$sql .= "WHERE User_USE_ID=$_POST[id] and Venue_VEN_ID=$_POST[venue];";
	
	echo mysqli_query($con, $sql);
	
	mysqli_close($con);
?>