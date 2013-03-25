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
		@param $auth the users authorization level
		@return a form containing text boxes for each index
	*/
	function createForm($info, $auth)
	{	
		$myCon = new Connection();
		$con = $myCon->connect();
		
		//open form
		echo "<form method='post'>\n";
		echo "Venue: $info[VEN_ID]<br />\n";
		echo "<input type='hidden' name='id' value=$info[VEN_ID] ";
		if(1 == $auth) echo "disabled ";
		echo "/>\n";
		
		//Venue name
		echo "<label for='name'>Name: </label>\n";
		echo "<input type='text' name='name' value=\"$info[VEN_Name]\" />\n<br />\n";
		
		//Venue unit address
		echo "<label>Unit: <br /></label>\n";
		echo "<input type='text' name='unit' value=\"$info[VEN_Unit_Addr]\" ";
		if(1 == $auth) echo "disabled ";
		echo "/>\n<br />\n";
		
		//venue address
		echo "<label>Address: <br /></label>\n";
		echo "<input type='text' name='address' value=\"$info[VEN_St_Addr]\" ";
		if(1 == $auth) echo "disabled "; 
		echo "/>\n<br />\n";
		
		//venue City
		echo "<label>City: <br /></label>\n";
		echo "<input type='text' name='city' value=\"$info[VEN_City]\" ";
		if(1 == $auth) echo "disabled ";
		echo "/>\n<br />\n";
		
		//Venue Province
		echo "<label>Province: <br /></label>\n";
		echo "<input type='test' name='province' value=\"$info[VEN_Province]\" ";
		if(1 == $auth) echo "disabled";
		echo "/>\n<br />\n";
		
		//Venue postal code
		echo "<label>Postal Code: <br /></label>\n";	
		echo "<input type='text' name='post' value=\"$info[VEN_Pcode]\" ";
		if(1 == $auth) echo "disabled ";
		echo "/>\n<br />\n";

		//Venue region, hidden for venue owner
		if(0 == $auth)
		{		
			echo "<label>Region: <br /></label>\n";
			echo "<select name='region'>\n";
				$sql = 'SELECT * FROM Region;';
				$results = mysqli_query($con, $sql);
				foreach($results as $region)
				{
					if(100 != $info['VEN_ID'] && 99 == $region['REG_ID'])continue;
					echo "<option value='$region[REG_ID]' ";
					if($region['REG_ID'] == $info['Region_REG_ID']) echo "selected";
					echo ">$region[REG_Name]</option>\n";
				}
			echo "</select>\n<br />\n";
		}
		else
		{
			echo "<input type='hidden' vame='region' value=\"$info[Region_REG_ID]\" />\n";
		}
		
		// Venue phone number
		echo "<label>Phone: <br /></label>\n";
		echo "<input type='text' name='phone' value=\"$info[VEN_Phone]\" />\n<br />\n";
		
		// Venue primary contact
		echo "<label>Contact: <br /></label>\n";
		echo "<input type='text' name='liason' value=\"$info[VEN_Liason]\" />\n<br />\n";
		
		//Venue create owner permissions, hidden for owner
		if(0 == $auth)
		{
			echo "<label>Create Owners:</label>\n<br />\n";
			echo "<select name='owner'>\n";
			echo "<option value='0' ";
			if(0 == $info['VEN_Can_Make_Owner']) echo "selected ";
			echo ">No</option>\n";
			echo "<option value='1'";
			if(1 == $info['VEN_Can_Make_Owner']) echo "selected ";
			echo ">Yes</option>\n";
			echo "</select>\n";
			echo "<br />\n";
		}
		else
		{
			echo "<input type='hidden' name='owner' value='$info[VEN_Can_Make_Owner]' />\n";
		}
		
		//Submit/save and cancel buttons
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
		@param $userAuth the authorization level of the user
		@param $makeOwner wether or not the venue can make additinal owners
		@return a table with users that can be edited, and buttons show the create new user form
			and join existing user form
	*/
	function listUsers($users, $venue, $userAuth, $makeOwners)
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
			createUserRow($user, $venue, $userAuth, $makeOwners);
		echo "</table>\n";
		
		//New user form
		$myCon = new Connection();
		$con = $myCon->connect();
		$sql = "select * from Auth_level_lookup;";
		$authLevels = mysqli_query($con, $sql);
		echo "<div id='create-form' title='Create new user'>\n";
		echo "<form>\n";
		echo "<input type='hidden' name='venue' id='venue' value='$venue' />\n"; 
		
		//user name
		echo "<label for='uName'>User name:</label>\n";
		echo "<input type='text' name='uName' id='uName' />\n";
		echo "<div class='error' id='uError'></div>\n";
		
		//first name
		echo "<label for='fName'>First name:</label>\n";
		echo "<input type='test' name='fName' id='fName' />\n";
		
		//Last name
		echo "<label for='lName'>Last name:</label>\n";
		echo "<input type='text' name='lName' id='lName' />\n";
		
		//Password
		echo "<label for='password'>Password:</label>\n";
		echo "<input type='password' name='password' id='password'/>\n";
		
		//authorization
		echo "<label for='auth'>Authorization level:</label>\n";
		echo "<select name='auth' id='auth'>\n";
			foreach($authLevels as $auth)
			{
				//only clubwatch can have administrators
				if(100 != $venue && 0 == $auth['AUT_Level']) continue;
				//venue owners can not create administrators
				if(1 == $userAuth && 0 == $auth['AUT_Level']) continue;
				//venue owners can only create additinal owners if permited
				if(1 == $userAuth && 1 == $auth['AUT_Level'] && 0 == $makeOwners) continue;
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
		@param $userAuth the authorization level of the user loged in
		@param $makeOwner wether or not the venue can make owners
		@return a single table row, with text boxs for the user name, first name, last name a selection box
			for the authorization level, and a delete and save button
	*/
	function createUserRow($user, $venue, $userAuth, $makeOwner)
	{
		$myCon = new Connection();
		$con = $myCon->connect();
		$sql = "select * from Auth_level_lookup;";
		$authLevels = mysqli_query($con, $sql);
		echo "<tr id='$user[USE_ID]' >\n";
		echo "<td><input id='user$user[USE_ID]' type='text' value=\"$user[USE_Name]\" onchange=\"enableSaveButton($user[USE_ID])\"/></td>\n";
		echo "<td><input id='first$user[USE_ID]' type='text' value=\"$user[USE_Fname]\" onchange=\"enableSaveButton($user[USE_ID])\"/></td>\n";
		echo "<td><input id='last$user[USE_ID]' type='text' value=\"$user[USE_Lname]\" onchange=\"enableSaveButton($user[USE_ID])\"/></td>\n";
		echo "<td><select ID='auth$user[USE_ID]' onchange=\"enableSaveButton($user[USE_ID])\">\n";
			foreach($authLevels as $auth)
			{
				//only clubwatch can have administrators
				if(100 != $venue && 0 == $auth['AUT_Level']) continue;
				//venue owners can not create administrators
				if(1 == $userAuth && 0 == $auth['AUT_Level']) continue;
				//venue owners can only create additinal owners if permited
				if(1 == $userAuth //user is a club owner
					&& 1 == $auth['AUT_Level'] //show owner privilages
					&& 0 == $makeOwner //does not have permission to create owners
					&& 1 != $user['Auth_Level_Lookup_AUT_Level']) //existing user is not a owner
						continue;
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