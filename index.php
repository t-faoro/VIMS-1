<?php 
/**************************PROGRAM FLAG***************************************/
//::
//:: Venue Information Management Systems
//:: Developed by: Excelsior Systems
//::
//:: Contributing Programmers:
//::			Tylor Faoro
//::			Justin Werre
//::			James Smith
//::			Maxwell Clyke
//::
//:: This software was developed for Clubwatch and is NOT open source. Any
//:: reproduction is not permitted unless with prior express written consent. 
//:: If you are a third party programmer hired to work with this system, 
//:: please contact Excelsior Systems with any questions and/or concerns.
/******************************************************************************/


//:: Session Delcaration for Page
session_start();


//:: Error Reporting On for Live, Off for Development
//error_reporting(0); 

//:: Config file containing all necessary includes here.
include "config.php";


//:: Instantiation of Objects Here
//$Login = new Login();
//$Form  = new Form();

//:: Necessary Stylesheets Declared Here
CSS("style.css"); 


//:: Necessary Javascript Declared Here
// JS("Example.js"); <-- This is how you call it


//:: Divides Site into content blocks (Site is Built here)
//:: Any changes made after this section will not appear on the front end.

echo setSiteInfo(SITE_TITLE);

include "snippets/header.php"; 
	
	switch ($_GET['action']){
	    case "admin":
	   		echo buildAdminNav();
		break;
			
	    case "user":
	   		echo buildUserNav();
		break;
		
	    default:
			echo buildNoNav();
		break;
	
	}
	/*if($testValue == 1){
		echo buildAdminNav();
	}
	elseif($testValue == 2){
		echo buildUserNav();	
	}
	else{
		
		echo buildNoNav();
		
	}*/

include "snippets/content.html";

echo '<br />'; // For neatness on page

include "snippets/footer.php";

//:: End of Site



//:: FUNCTIONS

/**
* Returns a string that will make up the top-of-page HTML markup, the parameter
* $title will set the page's title at the top of the browser.
* 
* @param $title
* @return $markUp
*/
function setSiteInfo($title){		
	global $styleSheet;
	global $javaScript;
	
	$markUp  = "<html>\n";
	$markUp .= "<head>\n";
	$markUp .= '<title>'.$title.'</title>'."\n";
	$markUp .= $styleSheet;
	$markUp .= $javaScript;
	return $markUp;
}


/**
* Returns a string that will make up the body-of-page HTML markup when called
* (Will possibly put page content into a class so it can have data added to it dynamically) 
*
* @return $markUp
*/
function siteContent(){
		
	$markUp = "</head>";
	$markUp .= "<body>";
	
	return $markUp;
}


/**
* Returns a string that will pass CSS style sheet include information to the front end. This function
* will properly append to the end of the last called CSS() command and show properly justified within the 
* page source when viewed.
* 
* @param  $styleName
* @return $styleSheet
*/
function CSS($styleName){
global $styleSheet;

	$styleSheet .= '<link rel="stylesheet" type="text/css" href="'.CSS_PATH.$styleName.'">';
	$styleSheet .= "\n";
	
	return $styleSheet;
}


/**
* Returns a string that will pass JavaScript include information to the front end. This function
* will properly append to the end of the last called JS() command and show properly justified within the 
* page source when viewed.
* 
* @param  $scriptName
* @return $javaScript
*/
function JS($scriptName){
global $javaScript;	

	$javaScript .= '<script type="text/javascript" src="'.JS_PATH.$scriptName.'">';
	$javaScript .= '</script>';
	return $javaScript;
}


/**
* Returns a string that will pass Image information to the front end in the form of a <IMG /> tag. 
* this function will accept two strings as parameters, $imgName which will be the image file name (with extenstion)
* and $alt will be the ALT value of the image to be presented.
*
* Example Use: IMG("image.png", "Example Image");
* 
* @param  $imgName
* @param  $alt
* @return $image
*/
function IMG($imgName, $alt){
global $image;

	$image = '<img src="'.IMG_PATH.$imgName.'" alt="'.$alt.'" />';
	return $image;
}






?>