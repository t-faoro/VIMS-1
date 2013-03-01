<?php 

class Login{
	
	//Login Variables
	private $username;
	private $password;
	
	
	// Create the front end items
	
	/**
	* Purpose: To simply draw the markup for the login form that will be used to log into the VIMS system. The String $login will contain all of the markup necessary
	*		   concatenated into the single string and sent to the front end.
	*
	* @return String $login
	*/
	public function set_login_display(){
		$login .= '<div id="login_system">';
		$login .= '<form action="?d=0&action=dashboard" method="POST">';
		$login .= '<div class="item"><label for="username">Login:</label><input class="text" id="username" type="text" name="username" /></div>';
		$login .= '<div class="item"><label for="password">Password:</label><input class="text password" id="password" type="password" name="password" /></div>';
		$login .= '<input type="hidden" name="staff_login" value="1" />';
		$login .= '<div class="item"><label>&nbsp;</label><input class="submit" type="submit" name="submit" value="Log in" /></div>';
		$login .= '</form>';
		$login .= '</div>';
		
		return $login;		
	
	}
	
	/**
	* Purpose: This function will create a logout link that will point to the session terminator
	*
	* @return String called $logout, which contains the link to the logout script.	
	*/
	public function set_logout_display(){
		$logout .= "<a href=\"?d=0&action=logout\""; //Currently this will not function
		
		return $logout;	
	}
	
	// Login Processing Methods
	/**
	* Purpose: This function will take the username and password, send it to a login authentication method to check with the database
	*		   to determine whether the credentials are valid. $username and $password will be Strings. $safePass is a bool that will 
	*		   determine if the password is hashed using MD5
	*
	* @param String $username
	* @param String $password
	* @param Bool   $safePass
	*/
	public function proc_login($username, $password, $safePass = true){
		
	}	




}







?>