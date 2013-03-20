<?php
	/**
		regions.php
		Purpose: Allows administrators to create and modify regions
		@param $_GET['id'] the id number of the region to be used, 'new' if creating 
			a new region
		By: Justin Werre
		March 19, 2013
	*/
	
	//verify user has been authenticated, and open a database connection
	include_once "php/config.php";
	session_start();
	if(!verifyUser()) header("Location: index.php");
	$myCon = new Connection();
	$con = $myCon->connect();
	$errorId = "";
	$errorName = "";
	
	//if its a post back, make sure all fields are filed out,
	//then update the database and go back to manage regions
	if(isset($_POST['submit']))
	{
		if("" == $_POST['regionId'])
			$errorId = "You must enter a region Id number.";
		if("" == $_POST['region'])
			$errorName = "You must enter a region name.";
		
		//If everything is filed out, update database
		if("" == $errorId && "" == $errorName)
		{
			if('new' == $_POST['id'])
			{
				$sql = "INSERT INTO Region(REG_ID, REG_Name) VALUES ($_POST[regionId]	, '$_POST[region]')";
			}
			else
			{
				$sql = "UPDATE Region SET REG_Name = '$_POST[region]'";
				$sql .= "WHERE REG_ID = $_POST[id]";
			}
			$result = mysqli_query($con, $sql);
			if($result)
				header('Location: manageRegions.php');
			else
				$errorId = "That region number is already taken";
		}
	}
	
	//get the region's information
	$results = array('REG_ID' => 'new', 'REG_Name' => '');
	if('new' != $_GET['id'])
	{
		$sql = "SELECT * FROM Region WHERE REG_ID = $_GET[id]";
		$results = mysqli_fetch_assoc(mysqli_query($con, $sql));
	}
	else if(isset($_POST['submit']))
	{
		$results['REG_ID'] = $_POST['regionId'];
		$results['REG_Name'] = $_POST['region'];
	}
	
	//show the page
	createHead("regions.css");
	createHeader($_SESSION['userName']);
	createNav($_SESSION['userAuth']);
	echo "<div id='content'>\n";
	echo "<form method='post'>\n";
	echo "<input type='hidden' name='id' value='$results[REG_ID]'>\n";
	echo "<label for='regionId'>Region Number</label>\n";
	if('new' == $_GET['id']){ echo "<input type='number' name='regionId' >";}
	else echo "<input type='number' name='regionId' value='$results[REG_ID]' disabled>\n";
	echo "<div class='error'>$errorId</div>\n";
	echo "<label for='region'>Region: </label>\n";
	echo "<input type='text' name='region' value='$results[REG_Name]'>\n";
	echo "<div class='error'>$errorName</div>\n";
	echo "<input type='submit' name='submit' value='submit'>\n";
	echo "</div>";
	createFoot();
	mysqli_close($con);
?>