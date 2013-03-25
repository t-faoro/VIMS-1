<?php
	/**
		By: Justin Werre
		Purpose: Creates a page for added and modifying venues, as well as creating users,
			and adding existing users to a venue.
		@param id a venue id in get, new if creating a new venue
	*/

	include_once "php/config.php";
	include_once "php/justinsFunctions.php";

	//Verify user is authenticated
	session_start();
	if(!verifyUser()) header("Location: index.php");

	$venInfo = array('VEN_Name'=>'',
									 'VEN_ID'=>'New',
									 'VEN_Unit_Addr'=>'',
									 'VEN_St_Addr'=>'',
									 'VEN_City'=>'',
									 'VEN_Province'=>'',
									 'VEN_Pcode'=>'',
									 'VEN_Phone'=>'',
									 'VEN_Liason'=>'',
									 'VEN_Can_Make_Owner'=>'',
									 'Region_REG_ID'=>'',
									 'button'=>'Create'
		);
	$error = '';
	$users = array();
	$myCon = new Connection();
	$con = $myCon->connect();

	//Get existing data from the data base
	if(isset($_GET) && 'new' != $_GET['id'])
	{
		$sql = venueRead($_GET['id']);
		$result = mysqli_query($con, $sql);
		$venInfo = mysqli_fetch_assoc($result);
		$venInfo['button'] = 'Save Changes';
		$sql = userList($_GET['id']);
		$users = mysqli_query($con, $sql);
	}

	//if post back create or update venue
	if(isset($_POST['name']))
	{	
		$venue = array(
			$_POST['name'],
			$_POST['unit'],
			$_POST['address'],
			$_POST['city'],
			$_POST['province'],
			$_POST['post'],
			$_POST['phone'],
			$_POST['liason'],
			$_POST['owner'],
			$_POST['region'],
		);
		
		//if user wanted to cancel changes
		if("Cancel" == $_POST['submit'])
		{
			header('Location: ManageVenues.php');
		}
		//if no venue name, show a error
		else if('' == $_POST['name'])
		{
			$error = "You must have a venue name.";
		}
		//if making a new venue, create that venue
		else if('New' == $_POST['id'])
		{	
			$sql = venueCreate($venue, $con);
			
			//Display an error message, or create the venue
			if(stristr($sql, 'Error'))
			{
				$error = $sql;
			}
			else
			{
				$result = mysqli_query($con, $sql);
				
				//not adding users, send back to manageVenues.php
				if('Create' == $_POST['submit'])
				{
					header('Location: manageVenues.php');
				}
				//adding users, post back with venue id
				else
				{
					$id = mysqli_insert_id($con);
					header('Location: Venues.php?id='.$id);
				}
			}
		}
		//modifying a existing venue
		else
		{
			$fields = array(
				'Name',
				'Unit_Addr',
				'St_Addr',
				'City',
				'Province',
				'Pcode',
				'phone',
				'Liason',
				'Can_Make_Owner',
				'Region_ID'
			);
			$sql = venueUpdate($fields, $venue, $_POST['id'], $con);
			mysqli_query($con, $sql);
			header('Location: manageVenues.php');
		}
	}

	//Show the page	
	createHead(array('venues.css', 'darkness.css'), array('manageVenue.js', 'createUser.js', 'joinUser.js'));
	createHeader(($_SESSION['userFname'])." ".$_SESSION['userLname']);
	createNav($_SESSION['userAuth']);
	echo "<div class='clear' ></div>\n";
	echo "<div id ='content'>\n";
	echo '<div id="venue">'."\n";
	echo "<div class='error'>$error</div>\n";
	createForm($venInfo, $_SESSION['userAuth']);
	//only show add users for existing venues
	if('New' != $venInfo['VEN_ID']) listUsers($users, $venInfo['VEN_ID'], $_SESSION['userAuth'], $venInfo['VEN_Can_Make_Owner']);
	echo "</div>\n";
	createFoot();
	mysqli_close($con);
?>