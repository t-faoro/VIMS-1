<?php 

session_start(); // Because authentication will be necessary.
/*
 If this method is turned off, then errors will show up at the top of the page. 
 Commented out     = live site
 Not commented out = in production site
 
 ALWAYS turn error_reporting off if the site is live
*/

//error_reporting(0); 

//Config file which contains all necessary includes.
require "config.php";


// Instantiation of Objects
$Login = new Login();
$Form  = new Form();

$Form->textInput("text", "something", "textField");





?>