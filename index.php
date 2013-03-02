<?php 
/**************************PROGRAM FLAG***************************************/
//::
//:: Venue Information Management Systems
//:: Developed by: Excelsior Systems
//::
//:: Contributing Programmers:
//::			Tylor Faoro
//::			Justin Werre
//::			James Smith
//::			Maxwell Clyke
//::
//:: This software was developed for Clubwatch and is NOT open source. Any
//:: reproduction is not permitted unless with prior express written consent. 
//:: If you are a third party programmer hired to work with this system, 
//:: please contact Excelsior Systems with any questions and/or concerns.
/******************************************************************************/

session_start();
include_once "php/config.php";

$userName = "";
$venueNumber = "";
$password = "";
$venueName = "";
$error = "";

if(isset($_POST['submit']))
{
	//Verfiy user has entered all nesisary information
	//Verify a username was entered
	if($_POST['username'] != "")
	{
		$userName = $_POST['username'];
	}
	else
	{
		$error = "You must enter a username.";
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
	//verify a venue number was entered.
	if($_POST['venue'] != "")
	{
		$venueNumber = $_POST['venue'];
	}
	else
	{
		$error = "You must enter a venue number.";
	}
}

createHead("index.css");
createHeader();

//Display the form with previously entered information if necisary
echo "<div id='content'>\n";
echo "<div id='error'>$error</div>\n";
echo "<form method='post'>\n";
echo "User Name: <input type='text' name='username' value='$userName'>\n";
echo "<br />\n";
echo "Password: <input type='password' name='password'>\n";
echo "<br />\n";
echo "Venue Number: <input type='number' name='venue' value='$venueNumber'>\n";
echo "<br />\n";
echo "<input type='submit' value='submit' name='submit'>";
echo "</form>\n";
echo"</div>\n";


createFoot();

?>