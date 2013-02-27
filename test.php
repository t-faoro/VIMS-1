<?php

	include "connection.php";
	include "userFunctions.php";
	
	//Leave in to verify page is operating
	echo "\n<h2>Page Working</h2>";
	
	// Establish a new connection
	$myCon = new Connection();	// Do once for each page loaded
	
// ============================================================================
	$con = $myCon->connect();	// Do once for each sql function used

	$sql = userRead("jwerre", "justinpw", 101);
	
	$result = mysqli_query($con, $sql);
	while($row = mysqli_fetch_array($result))
	{
		echo "<br>\n<h3>User Found:</h3>\n";
		echo $row['USE_Fname'] . " " . $row['USE_Lname'];
		echo "<br />";
	}
	
	// close the connection
	mysql_close();				// Do once after each sql function
// ============================================================================
	
	echo "<h3>Create user function</h3>";
	
	$con = $myCon->connect();
	
	$sql = userCreate("sgraham", "stephenpw", "Stephen", "Graham", 1001);
	
	$result = mysqli_query($con, $sql);
	if($result) echo "<br>Successfully created user in VIMS<br>\n";
	else 		echo "<br>User creation failed<br>\n";
	mysql_close();
// ============================================================================	
	echo "<h3>Update Function</h3>";
	$con = $myCon->connect();
	echo "Update First Name<br>";
	$sql = userUpdate("Fname", "Phteven", "sgraham");
	$result = mysqli_query($con, $sql);
	if($result) echo "<br>Successfully updated user in VIMS<br>\n";
	else 		echo "<br>User update failed<br>\n";
	
	mysql_close();
	
?>