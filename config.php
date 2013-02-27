<?php 

//:: Define path constants for functions
define('CSS_PATH', './style/') ;
define('JS_PATH' , './script/');
define('IMG_PATH', './images/');


//:: Define site title (if it needs to change)
define('SITE_TITLE', "Venue Information Management System");

//:: Include any classes that the application requires.
//include "VIMS_Login_System.class.php";
//include "Form.class.php";
//include "DB.class.php";
include "snippets/navigation.php";


//:: Site Variable Declaration, for use within the application.
 $markUp = "";
 $styleSheet = "";
 $javaScript = "";
 $image = "";
 $userNav = "";
 $adminNav = "";
 $noNav = "";
 
 //:: This is the switch to test Navigation systems
 //:: 1 == Admin Navigation
 //:: 2 == User Navigation
 //:: Default Case: No Navigation
 $testValue = 99;

?>