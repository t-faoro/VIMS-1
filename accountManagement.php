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

$userName  		    = "tfaoro";//$_SESSION['userName'];
$userFname  		= "Tylor";//$_SESSION['fName'];
$userLname  		= "Faoro";//$_SESSION['lName'];
$currentPassword 	= NULL;
$oldPass    		= NULL;
$newPassOne 		= NULL;
$newPassTwo 		= NULL;
$userAuth   		= "";//$_SESSION['userAuth'];
$venue_id  		    = "";//$_SESSION['venueID'];
$venueName  		= "";//$_SESSION['venueName'];

$error = "";

//TEST VALUE
$verifyUser = TRUE;




if ($verifyUser == FALSE){
		echo "Forbidden: You do not have access to view that page";
		header('Location: index.php');	
}
else{
	
	if(isset($_POST['submit'])){		
		
		$currentPassword = getUserPass($userName, $con);
		
		$newPassOne = $_POST['New_Passwd1'];
		$newPassTwo = $_POST['New_Passwd2'];
		$oldPass    = $_POST['Old_Passwd'];
		
		
		if($newPassOne == NULL || $newPassTwo == NULL ||  $oldPass == NULL){
				$error = "Error: All fields are mandatory.";
				
		}
		elseif( MD5($oldPass) === trim($currentPassword) && trim(MD5($newPassOne)) === trim(MD5($newPassTwo)) ){			
				
			if(trim(strlen($newPassTwo)) < 7 || trim(strlen($newPassTwo)) > 32){
				$error = "Error: Your password does not meet length requirements.<br />\n
						  Passwords must be between 8 and 32 characters in length, with no whitespace.";									
			}
			else{
				//Problem with User Update query function				
				userUpdate("USE_Passwd", MD5($newPassTwo), $userName, $con);
				$error = "Account updated successfully.";
				//header('Location: dashboard.php');
			}
						
		}
		else{
			$error = "Update Failed: Password's do no match.";
		}			
		//createHead();
		//createHeader($userName);
		//createNav($authLevel);			
		//createFoot();			
	}
	
	echo manageAccountForm($con);
}	


//Start Functions

function getUserPass($user, $con){		
	global $userName;
	$result = "";	
		
		$query  = "SELECT USE_Passwd ";
		$query .= "FROM User ";
		$query .= "WHERE USE_Name = '".$userName."' ";		
				
		$oldPassword = mysqli_query($con, $query);
		
		while($row = mysqli_fetch_array($oldPassword)){
			$result = $row['USE_Passwd'];			
		}
		
		return $result;
}

function manageAccountForm($con){
	global $error;
	global $userName;
	global $userFname, $userLname;
	
	
	$form  = '<form method="POST" action="accountManagement.php">'."\n";	
	
	$form .= '<label>Username: </label>';
	$form .= '<span>'.$userName.'</span><br />'."\n";
	
	$form .= '<label>First Name: </label>';
	$form .= '<span>'.$userFname.'</span><br />'."\n";
	
	$form .= '<label>Last Name: </label>';
	$form .= '<span>'.$userLname.'</span><br />'."\n";
	
	$form .= "<br /><br />";
	
	$form .= '<label>Old Password: </label>';
	$form .= '<input type="password" name="Old_Passwd" /><br />'."\n";
	
	$form .= '<label>New Password: </label>';
	$form .= '<input type="password" name="New_Passwd1" /><br />'."\n";
	
	$form .= '<label>Confirm Password: </label>';
	$form .= '<input type="password" name="New_Passwd2" /><br />'."\n";
	
	$form .= "<br /><br />";
	$form .= '<input type="submit" value="Save Changes" name="submit" />';
	
	$form .= "<br /><br />";
	$form .= '<div id="error">'.$error.'</div>'."\n";
	
	$form .= '</form>';
	
	return $form;
}




?>