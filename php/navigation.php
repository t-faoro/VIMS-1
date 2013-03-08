<?php

/**
 * This function will simply draw the Administrator's Navigation to the screen if
 * Admin authentication is successful. Returns a string $adminNav which will contain the
 * pre-built navigation.
 *
 * @return $adminNav
*/

function buildAdminNav(){
global $adminNav;

	$adminNav = '<div id="adminNavigation" >';
<<<<<<< HEAD
	
	$adminNav .= '<ul>';
	
=======
	$adminNav .= '<ul>';
>>>>>>> master
	$adminNav .= '<li><a href="#">Home</a></li>';
	$adminNav .= '<li><a href="#">News</a></li>';
	$adminNav .= '<li><a href="#">Manage News</a></li>';
	$adminNav .= '<li><a href="#">Manage Venue</a></li>';
<<<<<<< HEAD
	
	$adminNav .= '</ul>';
	
	$adminNav .= '</div>';
	
=======
	$adminNav .= '</ul>';
	$adminNav .= '</div>';
>>>>>>> master
	return $adminNav;
}

/**
 * This function will simply draw the User's Navigation to the screen if User
 * authentication is successful. Returns a string $adminNav which will contain the
 * pre-built navigation.
 *
 * @return $adminNav
*/
function buildUserNav(){
<<<<<<< HEAD
global $userNav;

	$userNav = '<div id="userNavigation" >';
	
	$userNav  .= '<ul>';
	
	$userNav  .= '<li><a href="#">Home</a></li>';
	$userNav  .= '<li><a href="#">News</a></li>';
	$userNav  .= '<li><a href="#">Post Report</a></li>';
	
	
	$userNav  .= '</ul>';
	
	$userNav  .= '</div>';
	
=======
	$userNav = '<div id="userNavigation" >';
	$userNav  .= '<ul>';
	$userNav  .= '<li><a href="#">Home</a></li>';
	$userNav  .= '<li><a href="#">News</a></li>';
	$userNav  .= '<li><a href="#">Post Report</a></li>';
	$userNav  .= '</ul>';
	$userNav  .= '</div>';
>>>>>>> master
	return $userNav;
}

/**
 * This function will fire on default if the user is not logged in.
 * Because VIMS can only be accessed by authenticated user's there is
 * no reason to show a navigation if authentication has not taken place.
 *
 * @return $noNav
*/
function buildNoNav(){
global $noNav;
	
	$noNav  = '<div id="noNav">';
	$noNav .= '</div>';
	
	return $noNav;
	
}

?>