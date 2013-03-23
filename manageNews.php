<?php 
	/**
	 * manageNews.php
	 * @author Tylor Faoro
	  Edited by: Justin Werre March 23, 2013
	*/	
	
	session_start();
	
	//error_reporting(0);
	include_once "php/config.php";
    
	//:: Instantiate Database Connection Object
	$con = new Connection();
    $con = $con->connect();
    if (mysqli_errno($con)){        
		echo "Connection to Database Failed.";       
    }
	
	//:: Program Variable Declarations
	$userAuth   = $_SESSION['userAuth'];
	$userName  	=  $_SESSION['userName'];
	$venueID  	= $_SESSION['venueId'];
	$venueName  = $_SESSION['venueName'];
    $userFname  = $_SESSION['userFname'];
    $userLname  = $_SESSION['userLname'];
	$fullName	= $userFname." ".$userLname;
	
	$title = NULL;
	$date  = NULL;
	$comments = NULL;
	static $month = 0;

			
	//:: Misc. Variables
	$error = " ";
	$css = "";
	$js = "";
    
	//:: Verify that the user is logged in
    // If the user is not logged in, send back to index.php

	createHead($css, $js);
	createHeader($fullName);
	createNav($userAuth);
	
	if (!verifyUser()){
        echo "Forbidden: You do not have access to view that page";
        header('Location: index.php');	
    }
	else{
		$uID = getUserID($con, $userName);
	
		if(isset($_POST['createNews'])){
			$title 		= $_POST['title'];
			$date  		= $_POST['newsDate'];
			$comments 	= $_POST['comments'];
			$regID 		= $_POST['regID'];
			//$newsID     = $_POST['newsID'];


			if(strlen($title) <= 0 || strlen($date) <= 0 || strlen($comments) <=0 ){
				$error = "Error: All Fields are Mandatory.";
	
			
			}
			else{
				setNews($con, $title, $date, $comments, $regID, $uID);
				header('Location: manageNews.php?action=default');
																							
			}
		}
		if(isset($_POST['modifyNews'])){
			$title 		= $_POST['title'];
			$date  		= $_POST['newsDate'];
			$comments 	= $_POST['comments'];
			$regID 		= $_POST['regID'];
			$newsID     = $_POST['newsID'];
			

				modifyNews($con, $newsID, $title, $date, $comments, $regID);
				header('Location: manageNews.php?action=default');																						
			
		}
		
		if(isset($_POST['deleteNews'])){
			$reason = $_POST['reason'];
		
			if(strlen($reason) <= 0 ){
				$error = "Error: A reason is required before a deletion can occur.";
		
			}
			else{
				deleteNews($con, $reason, $_GET['id']);
				header('Location: manageNews.php?action=default');																						
			}
		}
	
			
		//:: Draws Content Blocks
		echo '<div id="content">';
		echo '<div class="headingDiv"><h2>Manage News</h2></div>';	 
		echo '<div id="error">'.$error.'</div>';
		$news = $_GET['action'];
		if($news != "default" && $news != "create"){
			$p = $_GET['id'];
		}
		switch($news){
								
			case "create":			
				echo '<a href="manageNews.php?action=default">'.IMG("back_arrow.gif", "Go back one page").'</a>';	
				echo '<div id="subhead">';			
				echo "<h3><span class='yellow'>Create News Flash</span></h3>";
				echo '</div>';			
				echo newNewsForm($con);				
			break;
			
			case "modify":
				echo '<a href="manageNews.php?action=default">'.IMG("back_arrow.gif", "Go back one page").'</a>';
				echo '<div id="subhead">';
				echo "<h3 class='center'><span class='yellow'>Modify News Flash</span></h3>";
				echo '</div>';
				echo modifyNewsForm($con);			
			break;
			
			case "delete":
				echo '<a href="manageNews.php?action=default">'.IMG("back_arrow.gif", "Go back one page").'</a>';
				echo '<div id="subhead">';
				echo "<h3 class='center'><span class='yellow'>Delete News Flash</span></h3>";
				echo '</div>';
				echo deleteNewsFlashForm($con);					
			break;
			
			case "view":
				$title = getNews($con, "NEW_Title", $p);
				
				echo '<a href="manageNews.php?action=default">'.IMG("back_arrow.gif", "Go back one page").'</a>';
				echo '<div id="subhead">';
				echo "<h3 class='center'><span class='yellow'>Read News Flash</span></h3>";
				echo '</div>';
				echo "<br /><br />";
				echo '<div id="newsBlock">';
				echo '<h1>'.$title.'</h1>';
				echo '<h3>Submitted By: '.getNewsPosterName($con, $p).'</h3>';
				echo '<h4>Desired Region: <span>'.getRegionName($con, $p).'</span></h4>';
				echo '<div id="newsContent">';
				echo '<p class="center">'.getNews($con, "NEW_Content", $p).'</p>';
				echo '</div>';
				echo "<br /><br /><br />";
				echo '<div align="center"><a href="manageNews.php?action=default">DONE</a></div>';
				echo "<br /><br /><br />";
				echo "</div>";					
			break;
			
			default:
			
				if(isset($_POST['monthSelect'])){
					$month = $_POST['month'];	
					
					echo '<div class="center">';
					echo updateNewsTable();
					echo '</div>';
					echo '<div class="newNewsButton"><a href="manageNews.php?action=create" >'.IMG("new.gif", "Create a new News Article.").'</a></div>';				
					drawNewsTable($con);

				}
				else{
					$month = 0;
					echo '<div class="center">';
					echo updateNewsTable();
					echo '</div>';
					echo '<div class="newNewsButton"><a href="manageNews.php?action=create" >'.IMG("new.gif", "Create a new News Article.").'</a></div>';					
					drawAllNewsTable($con);
				
				}
			break;
			
		}
                           
		echo '</div>';	
		echo "</div>"; //End of Content	
	}
	createFoot();
	
	//:: End of Page

?>