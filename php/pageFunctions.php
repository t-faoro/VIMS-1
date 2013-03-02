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
		Preconditions:
		Postconditions:
	*/
	function createHead()
	{
		echo "<!DOCTYPE html>\n";
		echo "<html>\n";
		echo "<head>\n";
		echo "<title>".SITE_TITLE."</title>\n";
		echo CSS("style.css"); 
		echo "<meta charst='UTF-8'>\n";
		echo "</head>\n";
	}
?>