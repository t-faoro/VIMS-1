<?php 

//:: Define path constants for functions
define('CSS_PATH', './css/') ;
define('JS_PATH' , './js/');
define('IMG_PATH', './images/');


//:: Define site title (if it needs to change)
define('SITE_TITLE', "Venue Information Management System");

// Include any classes that the application requires.
include_once "php/connection.php";
include_once "php/pageFunctions.php";
include_once "php/AssistingFunctions.php";
include_once "php/userFunctions.php";
include_once "php/venue_user_asscFunctions.php";
include_once "php/VenueFunctions.php";


// Site Variable Declaration, for use within the application.
 $markUp = "";
 $styleSheet = "";
 $javaScript = "";
 $image = "";
 $userNav = "";
 $adminNav = "";
 $noNav = "";
 
 //This is the switch to test Navigation systems
 //1 == Admin Navigation
 //2 == User Navigation
 //Default Case: No Navigation
 $testValue = 99;

?>