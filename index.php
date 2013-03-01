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



session_start();
include_once "php/config.php";
CSS("style.css"); 
echo setSiteInfo(SITE_TITLE);
include_once "php/header.php"; 
echo "<div id='content'></div>";
echo '<br />';
include_once "php/footer.php";

// End of Site
?>