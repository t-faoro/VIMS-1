<?php 
/**
	index.php
	Purpose: Login screen for the VIMS system. Validates user and stores user
		information in the SESSION variable.
	@author Justin Werre
	March 4, 2013
*/

session_start();
session_unset();
include_once "php/config.php";

$userName = "";
$venueNumber = "";
$password = "";
$venueName = "";
$error = "";
$myCon = new Connection();
$sql;
$result;


//if user has submitted information, validate user
if(isset($_POST['submit']))
{
	//Verfiy user has entered all nesisary information
	//verify a venue number was entered.
	if($_POST['venue'] != "")
	{
		$venueNumber = $_POST['venue'];
	}
	else
	{
		$error = "You must enter a venue number.";
	}
	
	//Verify a password was entered
	if($_POST['password'] != "")
	{
		$password = $_POST['password'];
	}
	else
	{
		$error = "You must enter a password.";
	}
	
	//Verify a username was entered
	if( $_POST['username'] != "")
	{
		$userName = $_POST['username'];
	}
	else
	{
		$error = "You must enter a username.";
	}
	
	//If all information was provided, Find user in the database
	$con = $myCon->connect();
	if($error == "")
	{
		$sql = userRead($userName, $password, $venueNumber, $con);
		$result = mysqli_query($con, $sql);
		$row = mysqli_fetch_assoc($result);
		//if user doesn't exist, display a error message
		if($row == null)
		{
			$error = "Invalid username or password.";
		}
		else
		{
			//Make sure user has permistion to access the venue
			if($row['VUA_Sys_Status'])
			{
				//get the venue information for the venue that the user works at
				$venue = mysqli_fetch_assoc(
									mysqli_query($con, 
										venueRead($row['Venue_VEN_ID'])
									)
								);
				
				//Set the session variables
				$_SESSION['userId'] = $row['USE_ID'];
				$_SESSION['userName'] = $row['USE_Name'];
				$_SESSION['userFName'] = $row['USE_Fname'];
				$_SESSION['userLName'] = $row['USE_Lname'];
				$_SESSION['userAuth'] = $row['Auth_Level_Lookup_AUT_Level'];
				$_SESSION['venueId'] = $row['Venue_VEN_ID'];
				$_SESSION['venueName'] = $venue['VEN_Name'];
				header('Location: dashboard.php');
			}
			else
			{
				$error = "You do not have permision to access this venue.";
			}
		}
	}
	mysqli_close($con);
}


//if their was no postback, or user information was invalid
//show the login page.
createHead("index.css");
createHeader();

//Display the form with previously entered information if necessary
echo "<div id='content'>\n";
echo "<div id='error'>$error</div>\n";
echo "<form method='post'>\n";
echo "User Name: <input type='text' name='username' value='$userName'>\n";
echo "<br />\n";
echo "Password: <input type='password' name='password'>\n";
echo "<br />\n";
echo "Venue Number: <input type='text' name='venue' value='$venueNumber'>\n";
echo "<br />\n";
echo "<input type='submit' value='submit' name='submit'>";
echo "</form>\n";
echo"</div>\n";


createFoot();

?>