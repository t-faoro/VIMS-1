<?php
include_once "php/config.php";
session_start();
if(!verifyUser()) header('location:index.php');

createHead();
$fullName = $_SESSION['userFname']." ".$_SESSION['userLname'];
createHeader($fullName);
createNav($_SESSION['userAuth']);

echo "<div id='content'>\n";
echo "<a href='reports.php?id=-1'><button>Create New Report</button></a>\n";
echo "</div>\n";
createFoot();
?>