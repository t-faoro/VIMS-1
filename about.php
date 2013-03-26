<?php
	/*
		By: Justin Werre
		Purpose: A simple about page for the web site.
	*/
	include_once "php/config.php";
	session_start();
	createHead();
	if(verifyUser())
	{
		createHeader(($_SESSION['userFname'])." ".$_SESSION['userLname']);
		createNav($_SESSION['userAuth']);
	}
	else
		createHeader();
	
	echo "<div id='content'>\n";
	echo "<p>";
	echo "Created by: Excesior systems.<br />";
	echo "For Computer Information Technology.<br />";
	echo "Lethbridge College 2012-2013.<br />";
	echo "</p>";
	echo"</div>\n";
	createFoot();
?>