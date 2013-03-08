<?php
	/*
		pageFuncions.php
		Purpose: A set of functions to help create a page.
		By: Justin Werre
		March 1, 2013
		Notes: Based on code writen by Tylor Faoro
	*/
	
	include_once "php/config.php";
	

	function createNav($authLevel)
	{
		//Clubwatch administrator
		if(0 == $authLevel)
		{
			$adminNav = "<div id='adminNavigation' >\n";
			$adminNav .= "<ul>\n";
			$adminNav .= "<li><a href='#'>Home</a></li>\n";
			$adminNav .= "<li><a href='#'>News</a></li>\n";
			$adminNav .= "<li><a href='#'>Manage News</a></li>\n";
			$adminNav .= "<li><a href='#'>Manage Venue</a></li>\n";
			$adminNav .= "</ul>\n";
			$adminNav .= "</div>\n";
			echo $adminNav;
		}
		//Venue owner
		else if(1 == $authLevel)
		{
			$userNav = "<div id='userNavigation' >\n";
			$userNav  .= "<ul>\n";
			$userNav  .= "<li><a href='#'>Home</a></li>\n";
			$userNav  .= "<li><a href='#'>News</a></li>\n";
			$userNav  .= "<li><a href='#'>Manage Venue</a></li>\n";
			$userNav  .= "<li><a href='#'>Manage Reports</a></li>\n";
			$userNav  .= "</ul>\n";
			$userNav  .= "</div>\n";
			echo $userNav;
		}		
		//Venue staff
		else if(3 == $authLevel)
		{
			$userNav = "<div id='userNavigation' >\n";
			$userNav  .= "<ul>\n";
			$userNav  .= "<li><a href='#'>Home</a></li>\n";
			$userNav  .= "<li><a href='#'>News</a></li>\n";
			$userNav  .= "<li><a href='#'>Manage Report</a></li>\n";
			$userNav  .= "<li><a href='#'>Create Report</a></li>\n";
			$userNav  .= "</ul>\n";
			$userNav  .= "</div>\n";
			echo $userNav;
		}
	}
	

	/*
		Purpose: To create a html <head>
		Preconditions: 
			$css is an additional css file.
			$js is an additinal javascript file.
		Postconditions: Echos the opening <html> tag, and nesisary <head> contents.
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
			echo CSS($css);
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
	
	/*
		Purpose: To create the page footer and closing <body> content
		Preconditions: createHeader() and createHead() should be called BEFORE this function.
		Postconditions: Echos the page footer, closes the <body> and <html>. Do not echo any
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
	
	/*
		Purpose: To create the Page header and opening <body> content
		Preconditions: createHead() should be called BEFORE this function.
		Postconditions:
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
		}
		echo "</div>\n"; // close topRight
		echo "</div>\n"; // close header container
	}
?>