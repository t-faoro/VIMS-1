<?php
	/*
		pageFuncions.php
		Purpose: A set of functions to help create a page.
		By: Justin Werre
		March 1, 2013
		Notes: Based on code writen by Tylor Faoro
	*/
	
	include_once "php/config.php";
	
	/**
		Purpose: to create a navagation bar based on the users authrization level
		@param $authLevel the authorization level for the user
		@return the users navagation bar
	*/
	function createNav($authLevel)
	{
		//Clubwatch administrator
		if(0 == $authLevel)
		{
			$adminNav = "<div id='adminNavigation' >\n";
			$adminNav .= "<ul>\n";
			$adminNav .= "<li><a href='dashboard.php'>Home</a></li>\n";
			$adminNav .= "<li><a href='manageNews.php?action=default'>Manage News</a></li>\n";
			$adminNav .= "<li><a href='manageRegions.php'>Manage Regions</a></li>\n";
			$adminNav .= "<li><a href='manageVenues.php'>Manage Venue</a></li>\n";
			$adminNav .= "</ul>\n";
			$adminNav .= "</div>\n";
			echo $adminNav;
		}
		//Venue owner
		else if(1 == $authLevel)
		{
			$userNav = "<div id='userNavigation' >\n";
			$userNav  .= "<ul>\n";
			$userNav  .= "<li><a href='dashboard.php'>Home</a></li>\n";
			$userNav  .= "<li><a href='news.php'>News</a></li>\n";
			$userNav  .= "<li><a href='venues.php?id=$_SESSION[venueId]'>Manage Venue</a></li>\n";
			$userNav  .= "<li><a href='manageReports.php'>Manage Reports</a></li>\n";
			$userNav  .= "</ul>\n";
			$userNav  .= "</div>\n";
			echo $userNav;
		}		
		//Venue staff
		else if(2 == $authLevel)
		{
			$userNav = "<div id='userNavigation' >\n";
			$userNav  .= "<ul>\n";
			$userNav  .= "<li><a href='dashboard.php'>Home</a></li>\n";
			$userNav  .= "<li><a href='news.php'>News</a></li>\n";
			$userNav  .= "<li><a href='manageReports.php'>Manage Report</a></li>\n";
			$userNav  .= "<li><a href='manageVars.php?action=create'>Create Report</a></li>\n";
			$userNav  .= "</ul>\n";
			$userNav  .= "</div>\n";
			echo $userNav;
		}
	}
	

	/**
		Purpose: To create a html <head>
		@param	$css is an additional css file, may be a single string or an array of strings.
		@param	$js is an additinal javascript file, may be a single string or an array of strings.
		@return Echos the opening <html> tag, and nesisary <head> contents.
	*/
	function createHead($css = null, $js = null)
	{
		echo "<!DOCTYPE html>\n";
		echo "<html>\n";
		echo "<head>\n";
		echo "<title>".SITE_TITLE."</title>\n";
		echo CSS("style.css"); 
		if($css != null)
		{
			if(is_array($css))
			{
				foreach($css as $style)
					echo CSS($style);
			}
			else 
			{
				echo CSS($css);
			}
		}
		
		echo "<script src='//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js'></script>\n";
		echo "<script src='//ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/jquery-ui.min.js'></script>\n";

		if($js != null)
		{
			if(is_array($js))
			{
				foreach($js as $script)
					echo JS($script);
			}
			else 
				echo JS($js);
		}
		echo "<meta charset='UTF-8'>\n";
		echo "</head>\n";
	}
	
	/**
		Purpose: To create the page footer and closing <body> content
		Preconditions: createHeader() and createHead() should be called BEFORE this function.
		@return Echos the page footer, closes the <body> and <html>. Do not echo any
			hmtl after calling this function.
	*/
	function createFoot()
	{
		echo "<div id='footer_container'>\n";
		echo "<div id='bottomLeft'><p>Developed By: <span class='yellow'>Excelsior Systems</span></p></div>\n";
		echo "<div id='bottomMiddle' align='center'><p>&#169; 2013 Excelsior Systems - All Rights Reserved</p></div>\n";
		echo "<div id='bottomRight'>&nbsp;</div>\n";
		echo "</div>\n";
		echo "</body>\n";
		echo "</html>";
	}
	
	/**
		Purpose: To create the Page header and opening <body> content
		Preconditions: createHead() should be called BEFORE this function.
		@param $name is the name of the user logged in to the system.
		@return Echos the opening <body> tag, as well as the page header.
	*/
	function createHeader($name = null)
	{
		echo "<body>\n";
		echo "<div id='header_container' >\n";    
		echo "<div id='topLeft'>".IMG("logo_clubwatch_v4.1.png", "Clubwatch Logo")."<p>Powered By: <span class='yellow'>Clubwatch</span></p>\n"."</div>\n"; // close topLeft
		echo "<div id='topMiddle'><h2>Venue Information Management System</h2></div>\n"; // close topMiddle
		echo "<div id='topRight'>";
		if($name == null)
		{ 
			echo "<p>Welcome to VIMS, Please Log in</p></div>\n";
		}
		else 
		{
			echo "<p>Welcome <a href='accountManagement.php'>$name</a></p>";
			echo "<p><a href='index.php'>Logout</a></p>";
		}
		echo "</div>\n"; // close topRight
		echo "</div>\n"; // close header container
	}
?>