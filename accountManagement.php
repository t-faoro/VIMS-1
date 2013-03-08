<?php
/**
 * Account Management
 * Author: Tylor Faoro
*/
 
session_start();


include "php/config.php";

$con = new Connection();
$con = $con->connect();
	if (mysqli_errno($con)){
		echo "Connection to Database Failed.";
	
	}

//:: Program Variable Declarations
$userName  		    = $_SESSION['userName'];
$userFname  		= $_SESSION['userFname'];
$userLname  		= $_SESSION['userLname'];
$userAuth   		= $_SESSION['userAuth'];
$venueID  		    = $_SESSION['venueId'];
$venueName  		= $_SESSION['venueName'];

//:: Password Field Declarations
$currentPassword 	= NULL;
$oldPass    		= NULL;
$newPassOne 		= NULL;
$newPassTwo 		= NULL;

//:: Misc. Variables
$error = " ";
$css = "";
$js = "";

//TEST VALUE
$verifyUser = TRUE;

//:: Draws Head HTML with Stylesheets, Javascript, Doctype, and Title already implemented.
createHead($css, $js);

//:: Draws Header for UI 
createHeader($userName);



//:: Verify that the user is logged in
// If the user is not logged in, send back to index.php
if (!verifyUser()){
		echo "Forbidden: You do not have access to view that page";
		header('Location: index.php');	
}
// Else the user must be authenticated
else{
	
	//:: Post Back Functionality
	if(isset($_POST['submit'])){		
		
		// Retrieve user's current password from database as a HASH
		$currentPassword = getUserPass($userName, $con);
		
		$newPassOne = $_POST['New_Passwd1'];
		$newPassTwo = $_POST['New_Passwd2'];
		$oldPass    = $_POST['Old_Passwd'];
		
		//:: Password reset bounds testing
		if($newPassOne == NULL || $newPassTwo == NULL ||  $oldPass == NULL){
				$error = "Error: All fields are mandatory.";
				
		}
		elseif( MD5($oldPass) === trim($currentPassword) && trim(MD5($newPassOne)) === trim(MD5($newPassTwo)) ){			
				
			if(trim(strlen($newPassTwo)) < 7 || trim(strlen($newPassTwo)) > 32){
				$error = "Error: Your password does not meet length requirements.<br />\n
						  Passwords must be between 8 and 32 characters in length, with no whitespace.";									
			}
			else{
				//:: If all tests have been passed, update the USER table with a new password and redirect to dashboard.
				//Problem with User Update query function				
				userUpdate("USE_Passwd", $newPassTwo, $userName, $con);
				$error = "Account updated successfully.";
				//header('Location: dashboard.php');
			}
						
		}
		else{
			//Old Password ented doesn't match entry in database
			$error = "Update Failed: Password's do no match.";
		}			
		
	}
	
	
}
echo '<div id="content">\n';
 echo '<div class="headingDiv"><h2>Edit Account</h2></div>';
 echo 
//:: Draws Content Blockso '<div id="leftContent">';
 echo '<div id="error">'.$error.'</div>';
 echo manageAccountForm();
 echo '</div>';
 echo '<div id="rightContent">';
 echo IMG("spotlights.jpg", "Spotlights");
 echo '</div>';
echo "</div>";
	createFoot();


//Start Functions

function getUserPass($user, $con){		
	global $userName;
	$result = "";
//:: Draws the Footer Content	

		
		$query  = "SELECT USE_Passwd ";
		$query .= "FROM User ";
		$query .= "WHERE USE_Name = '".$userName."' ";		
				
		$oldPassword = mysqli_query($con, $query);
		
		while($row = mysqli_fetch_array($oldPassword)){
			$result = $row['USE_Passwd'];			
		}
		
		return $result;
}


function manageAccountForm(){
	global $error;
	global $userName;
	global $userFname, $userLname;
	
	$form  = '<div id="accountManage" >';
	$form .= '<form method="POST" action="accountManagement.php">'."\n";	
	
	$form .= '<label>Name: </label>';
	$form .= '<span class="readOnly">'.$userFname.'</span><br />'."\n";
	
	$form .= '<label>Last Name: </label>';
	$form .= '<span class="readOnly">'.$userLname.'</span><br />'."\n";
	
	$form .= "<br />";
	
	$form .= '<br /><label>Username: </label>';
	$form .= '<span class="readOnly"><b><u>'.$userName.'</u></b></span><br />'."\n";
	
$form .= "<br />";
	
	$form .= '<label>Old Password: </label>';
	$form .= '<input type="password" name="Old_Passwd" class="passField"/><br />'."\n";
	
	$form .= '<label>New Password: </label>';
	$form .= '<input type="password" name="New_Passwd1" class="passField" /><br />'."\n";
	
	$form .= '<label>Confirm Password: </label>';
	$form .= '<input type="password" name="New_Passwd2" class="passField" /><br />'."\n";
	
	$form .= "<br /><br />";
	$form .= '<input type="submit" value="Save Changes" name="submit" class="button" />';
	
	$form .= "<br /><br />";
	
	
	$form .= '</form>';
	
	$form .= '</div>';
	
	return $form;
}




?>