<?php
	/*
		pageFuncions.php
		Purpose: A set of functions to help create a page.
		By: Justin Werre
		March 1, 2013
		Notes: Based on code writen by Tylor Faoro
	*/
	
	include_once "php/config.php";
	
	/*
		Purpose: To create a html <head>
		Preconditions: $css is an additional css file.
		Postconditions: Echos the opening <html> tag, and nesisary <head> contents.
	*/
	function createHead($css = null)
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
		echo "<meta charst='UTF-8'>\n";
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
		echo "<div id='bottomLeft'><p>Developed By: <span class='yellow'>Excelsior Systems</span></p><p>&#169; 2013 Excelsior Systems <br /> All Rights Reserved</p></div>\n";
		echo "<div id='bottomMiddle'></div>\n";
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
	function createHeader()
	{
		echo "<body>\n";
		echo "<div id='header_container' >\n";    
		echo "<div id='topLeft'>".IMG("logo_clubwatch_v4.1.png", "Clubwatch Logo")."<p>Powered By: <span class='yellow'>Clubwatch</span></p>\n"."</div>\n";
		echo "<div id='topMiddle'><h2>Venue Information Management System</h2></div>\n";    
		echo "<div id='topRight'><p>Welcome to VIMS, Please Log In</p></div>\n";
		echo "</div>\n";
	}
?>