<?php
	/*
		justinsFunction.php
		Purpose: assisting funtions for venue management pages
		Contains:
			createForm($info)
			listusers($users, $venue)
			createUserRow($user, $venue)
		By: Justin Werre
		March 18, 2013
	*/

	/**
		Purpose: Creates a form with venue information 
		@param $info is an array containig the following indexes
			VEN_ID the venues id
			VEN_Name the venues name
			VEN_Unit_Addr the venues unit number
			VEN_St_Addr the venues street address
			VEN_City the venues city
			VEN_Province the province that the venue is in 
			VEN_Pcode the venues postal code
			Region_REG_ID the region that the venue belongs to
			VEN_Phone the venues phone number
			VEN_Liason the venues primary contact
			button Text to appear in the submit button for the form
		@return a form containing text boxes for each index
	*/
	function createForm($info)
	{	
		$myCon = new Connection();
		$con = $myCon->connect();
		
		echo "<form method='post'>\n";
		echo "Venue: $info[VEN_ID]<br />\n";
		echo "<input type='hidden' name='id' value=$info[VEN_ID] />\n";
		echo "<label for='name'>Name: </label>\n";
		echo "<input type='text' name='name' value='$info[VEN_Name]' />\n<br />\n";
		echo "<label>Unit: <br /></label>\n";
		echo "<input type='text' name='unit' value='$info[VEN_Unit_Addr]' />\n<br />\n";
		echo "<label>Address: <br /></label>\n";
		echo "<input type='text' name='address' value='$info[VEN_St_Addr]' />\n<br />\n";
		echo "<label>City: <br /></label>\n";
		echo "<input type='text' name='city' value='$info[VEN_City]' />\n<br />\n";
		/*echo "<label>Province: <br /></label>\n";
		echo "<input type='test' value='$info[VEN_Province]' />\n<br />\n";*/
		echo "<label>Postal Code: <br /></label>\n";	
		echo "<input type='text' name='post' value='$info[VEN_Pcode]' />\n<br />\n";
		echo "<label>Region: <br /></label>\n";
		echo "<select name='region'>\n";
			$sql = 'SELECT * FROM Region;';
			$results = mysqli_query($con, $sql);
			foreach($results as $region)
			{
				echo "<option value='$region[REG_ID]' ";
				if($region['REG_ID'] == $info['Region_REG_ID']) echo "selected";
				echo ">$region[REG_Name]</option>\n";
			}
		echo "</select>\n<br />\n";
		echo "<label>Phone: <br /></label>\n";
		echo "<input type='text' name='phone' value='$info[VEN_Phone]' />\n<br />\n";
		echo "<label>Contact: <br /></label>\n";
		echo "<input type='text' name='liason' value='$info[VEN_Liason]' />\n<br />\n";
		echo "<input type='submit' name='submit' value='$info[button]' />\n";
		if('New' == $info['VEN_ID']) echo "<input type='submit' name='submit' value='Add users' />\n";
		echo "<input type='submit' name='submit' value='Cancel' />\n";
		echo "</form>\n";
		mysqli_close($con);
	}
	
	/**
		purpose: creates a table of users which can be modified, as well as the forms to create a 
			new user, and join an existing user to the venue
		@param users an array of users to be displayed, may be empty
		@param venue the id number of the venue that new users will be associated with
		@return a table with users that can be edited, and buttons show the create new user form
			and join existing user form
	*/
	function listUsers($users, $venue)
	{
		echo "<table id='users'>\n";
		echo "<tr>\n";
		echo "<th>User Name</th>\n";
		echo "<th>User first Name</th>\n";
		echo "<th>User last Name</th>\n";
		echo "<th>User authorization</th>\n";
		echo "<th><button id='createUser' >Create User</button></th>\n";
		echo "<th><button id='joinUser'>Join User</button></th>\n";
		echo "</tr>\n";
		foreach($users as $user)
			createUserRow($user, $venue);
		echo "</table>\n";
		
		$myCon = new Connection();
		$con = $myCon->connect();
		$sql = "select * from Auth_level_lookup;";
		$authLevels = mysqli_query($con, $sql);
		echo "<div id='create-form' title='Create new user'>\n";
		echo "<form>\n";
		echo "<input type='hidden' name='venue' id='venue' value='$venue' />\n"; 
		echo "<label for='uName'>User name:</label>\n";
		echo "<input type='text' name='uName' id='uName' />\n";
		echo "<div class='error' id='uError'></div>\n";
		echo "<label for='fName'>First name:</label>\n";
		echo "<input type='test' name='fName' id='fName' />\n";
		echo "<label for='lName'>Last name:</label>\n";
		echo "<input type='text' name='lName' id='lName' />\n";
		echo "<label for='auth'>Authorization level:</label>\n";
		echo "<select name='auth' id='auth'>\n";
			foreach($authLevels as $auth)
			{
				echo "<option value='$auth[AUT_Level]'";
				echo ">$auth[AUT_Def]</option>\n";
			}
		echo "</select>\n";
		echo "</form>\n";
		echo "</div>\n";	
		echo "<div id='join-form' title='join new user'>\n"; 
		echo "<input type='hidden' id='venueId' value=$venue>\n";
		echo "<label>User name</label>\n";
		echo "<input type='text' id='existingName' name='existingName' />\n";
		echo "</div>\n";
	}
	
	/**
		Purpose: Creates a table row with user information for the listUsers table
		@param $user contains the following indexs:
			USE_ID the users id number
			USE_Name the users user name
			USE_Fname the users first name
			USE_Lname the users last name
			Auth_Level_Lookup_AUT_Level 
		@param $venue the id number of the venue associatied with the user
		@return a single table row, with text boxs for the user name, first name, last name a selection box
			for the authorization level, and a delete and save button
	*/
	function createUserRow($user, $venue)
	{
		$myCon = new Connection();
		$con = $myCon->connect();
		$sql = "select * from Auth_level_lookup;";
		$authLevels = mysqli_query($con, $sql);
		echo "<tr id='$user[USE_ID]' >\n";
		echo "<td><input id='user$user[USE_ID]' type='text' value='$user[USE_Name]' onchange=\"enableSaveButton($user[USE_ID])\"/></td>\n";
		echo "<td><input id='first$user[USE_ID]' type='text' value='$user[USE_Fname]' onchange=\"enableSaveButton($user[USE_ID])\"/></td>\n";
		echo "<td><input id='last$user[USE_ID]' type='text' value='$user[USE_Lname]' onchange=\"enableSaveButton($user[USE_ID])\"/></td>\n";
		echo "<td><select ID='auth$user[USE_ID]' onchange=\"enableSaveButton($user[USE_ID])\">\n";
			foreach($authLevels as $auth)
			{
				echo "<option value='$auth[AUT_Level]'";
				if($auth['AUT_Level'] == $user['Auth_Level_Lookup_AUT_Level']) echo " selected";
				echo ">$auth[AUT_Def]</option>\n";
			}
		echo "</select></td>\n";
		echo "<td><button onclick=\"deleteUser($user[USE_ID], $venue)\">Delete</button></td>\n";
		echo "<td><button onclick=\"updateUser($user[USE_ID], $venue)\" id='update$user[USE_ID]' disabled>Update user</button></td>\n";
		echo "</tr>\n";
	}
?>