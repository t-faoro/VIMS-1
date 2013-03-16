<?php
	/**
		Creates a form with venue information 
		By: Justin Werre
	*/
	function createForm($info)
	{	
		$myCon = new Connection();
		$con = $myCon->connect();
		
		echo "<form method='post'>\n";
		echo "Venue: $info[VEN_ID]<br />\n";
		echo "<input type='hidden' name='id' value=$info[VEN_ID] />\n";
		echo "<label>Name: <br /></label>\n";
		echo "<input type='text' name='name' value='$info[VEN_Name]' />\n<br />\n";
		echo "<label>Unit: <br /></label>\n";
		echo "<input type='text' name='unit' value='$info[VEN_Unit_Addr]' />\n<br />\n";
		echo "<label>Address: <br /></label>\n";
		echo "<input type='text' name='address' value='$info[VEN_St_Addr]' />\n<br />\n";
		echo "<label>City: <br /></label>\n";
		echo "<input type='text' name='city' value='$info[VEN_City]' />\n<br />\n";
		/*echo "<label>Province: <br /></label>\n";
		echo "<input type='test' value='$info[province]' />\n<br />\n";*/
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
	
	function listUsers($users, $venue)
	{
		echo "<table id='users'>\n";
		echo "<tr>\n";
		echo "<th>User Name</th>\n";
		echo "<th>User first Name</th>\n";
		echo "<th>User last Name</th>\n";
		echo "<th>User authorization</th>\n";
		echo "<th><button id='createUser' >create User</button></th>\n";
		echo "<th></th>\n";
		echo "</tr>\n";
		$myCon = new Connection();
		$con = $myCon->connect();
		$sql = "select * from Auth_level_lookup;";
		$authLevels = mysqli_query($con, $sql);
		foreach($users as $user)
		{
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
		echo "</table>\n";
		echo "<div id='dialog-form' title='Create new user'>\n";
		echo "<form>\n";
		echo "<fieldset>\n";
		echo "<label for='uName'>User name:</label>\n";
		echo "<input type='text' name='uName' id='uName' />\n";
		echo "<label for='fName'>First name:</label>\n";
		echo "<input type='test' name='fName' id='fName' />\n";
		echo "<lable for='lName'>Last name:</label>\n";
		echo "<input type='text' name='lName' id='lName' />\n";
		echo "</fieldset>\n";
		echo "<form>\n";
		echo "</div>\n";	
	}

?>