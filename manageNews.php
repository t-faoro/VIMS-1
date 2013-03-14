<?php 
	/**
	 * manageNews.php
	 * @author Tylor Faoro
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
	//$userAuth   		= $_SESSION['userAuth'];
	$userName  		    =  "tfaoro";//$_SESSION['userName'];
	//$venueID  		= $_SESSION['venueId'];
	//$venueName  		= $_SESSION['venueName'];
	
	$title = NULL;
	$date  = NULL;
	$comments = NULL;

			
	//:: Misc. Variables
	$error = " ";
	$css = "";
	$js = "";
	
	createHead($css, $js);
	createHeader($userName);
	
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
			setNews($con, $title, $date, $comments, $regID, 1004);
			$error = "News Created Successfully!";
																						
		}
	}
	if(isset($_POST['modifyNews'])){
		$title 		= $_POST['title'];
		$date  		= $_POST['newsDate'];
		$comments 	= $_POST['comments'];
		$regID 		= $_POST['regID'];
		//$newsID     = $_POST['newsID'];
		
			
			
		if(strlen($title) <= 0 || strlen($date) <= 0 || strlen($comments) <=0 ){
			$error = "Error: All Fields are Mandatory.";

		
		}
		else{
			modifyNews($con, $_GET['id'], $title, $date, $comments, $regID);
			$error = "News Updated Successfully!";																						
		}
	}
	if(isset($_POST['deleteNews'])){
		$reason = $_POST['reason'];
		
			
			
		if(strlen($reason) <= 0 ){
			$error = "Error: A reason is required before a deletion can occur.";

		
		}
		else{
			deleteNews($con, $reason, $_GET['id']);
			$error = "News Deleted Successfully!";																						
		}
	}
		
	//:: Draws Content Blocks
	echo '<div id="content">';
	echo '<div class="headingDiv"><h2>Manage News</h2></div>';
		 
	echo '<div id="singleContent">';
	echo '<div id="error">'.$error.'</div>';
	$news = $_GET['action'];
	if($news != "default"){
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
			echo '<div class="newNewsButton"><a href="manageNews.php?action=create" >'.IMG("new.gif", "Create a new News Article.").'</a></div>';
			drawNewsTable($con);
		break;
		
	}
	                           
	echo '</div>';	
	echo "</div>"; //End of Content	
	
	createFoot();
	
	//:: End of Page
		
//:: Start of Functions	
	function readNews($con, $p){
		echo '<div id="readNews">';
		
		echo '<div class="title"';
		echo '<h1>'.getNews($con, "NEW_Title", $p).'</h1>';
		echo '</div>';
		
		echo '</div>';
	}	
	/**
	 * Take all data received through the form via POSTBACK and attempts to insert it into the database in a readable
	 * manor.
	 * @author Tylor Faoro
	 *
	 * @param Object $con The database connection object
	 * @param String $title The Title of the news article
	 * @param String $date A date/time string
	 * @param String $comments The main content of the news flash
	 * @param Int    $uid The id of the currently logged in user.
	 *
	 * @return none
	*/
	function setNews($con, $title, $date, $comments, $regID, $uID){
		global $error;		
		
		$title	  = mysqli_real_escape_string($con, $title);
		$date 	  = mysqli_real_escape_string($con, $date);
		$comments = mysqli_real_escape_string($con, $comments);
		
		
		$sql  = 'INSERT INTO news ';
		$sql .= '(NEW_Title, NEW_Date, NEW_Content, User_USE_ID)';
		$sql .= ' VALUES(';
		$sql .= " '".$title."', ";
		$sql .= " '".$date."', ";
		$sql .= " '".$comments."', ";
		$sql .= " '".$uID."' ";
		$sql .= ')';
		

		
		if(!mysqli_query($con, $sql)){
			die('Error: setNewsSQL '.mysqli_error($con));
		}

		$newsID = getCurrentNewsID($con, $title);
		setNewsRegionJunction($con, $newsID, $regID);
	  
	}
	  
	/**
	 * Sets up the Junction between the News and Region Tables in order to properly
	 * insert News Flash data into the database.
	 * @author Tylor Faoro
	 *
	 * @param Object $con The database connection object
	 * @param int    $newsID The ID of the news flash being submitted.
	 * @param int    $regID The ID of the Region that the news is being sent to.
	 *
	 * @return none
	*/  
	function setNewsRegionJunction($con, $newsID, $regID){
		$sql2  = 'INSERT INTO News_Region_Assc ';
		$sql2 .= '(News_NEW_ID, Region_REG_ID) ';
		$sql2 .= 'VALUES(';
		$sql2 .= " '".$newsID."', ";
		$sql2 .= " '".$regID."' ";
		$sql2 .= ') ';

		if(!mysqli_query($con, $sql2)){
			$error = "Junction Not Successfully Joined.";
			return $error;
			
			die('Error: '.mysqli_error($con));
		}
	}
		
	/**
	 * When a user submits new news this function will fire to determine what the NEW_ID is of 
	 * the news being submitted. The ID that is acquired is then used to create the Junction
	 * between the News and Region Tables. This Function is written to work directly with
	 * the setNewsRegionJunction() Function.
	 * @author Tylor Faoro
	 *
	 * @param Object $con The database connection object
	 * @param String $title Acquired from parent function. Used to find the news just written.
	 *
	 * @return int $newsID Used by parent function to establish Junction between News and Region.
	*/
	function getCurrentNewsID($con, $title){
		
		$subQuery  = "SELECT NEW_ID ";
		$subQuery .= "FROM news ";
		$subQuery .= "WHERE NEW_Title = '".$title."' ";		
		$resultSubQuery = mysqli_query($con, $subQuery);
		while($row = mysqli_fetch_array($resultSubQuery)){
			$newsID = $row['NEW_ID'];			
		}		
			return $newsID;
	}
		
	/**
	 * Draws the Create News Form with all appropriate settings in place.
	 * @author Tylor Faoro
	 *
	 * @param Object $con The Database connection object
	 *
	 * @return mixed $form A built form with necessary elements to create a news flash entry.
	*/
	function newNewsForm($con){
        
		$form  = '<div id="manageNews" >';
        $form .= '<form method="POST" action="manageNews.php?action=create">'."\n";
		$form .= '<label>Title:</label>';
		$form .= '<input type="text" value="" name="title" />'."\n";
		$form .= '<br /><br />';
		$form .= '<label>Date:</label>';
		$form .= '<input type="date" value="'.currTimeDate().'" name="newsDate" />';
		$form .= '<br /><br />';
		$form .= '<label>Region:</label>';
		$form .= '<select name="regID"><span>'.selectElementRegionOption($con, NULL).'</span></select>';
		$form .= '<br /><br />';
		$form .= '<label>Comments: </label>';
		$form .= '<textarea class="textarea" name="comments">';
		$form .= '</textarea>';
		$form .= '<br /><br />';
		$form .= '<input type="submit" name="createNews" class="button" value="Submit" />';
		
		$form .= '</form>';
		$form .= '</div>';
		
		return $form;
	}
	
	/**
	 * Draws the Modify News Form with all appropriate settings in place. The difference between
	 * this form and newNewsForm() is all fields will be populated with default data via the Database.
	 * @author Tylor Faoro
	 *
	 * @param Object $con The Database connection object
	 *
	 * @return mixed $form A built form with necessary elements to modify a news flash entry.
	*/
	function modifyNewsForm($con){
        $p = $_GET['id'];
				
		$defaults = array( getNews($con, "NEW_Title", $p), getNews($con, "NEW_Date", $p), getNews($con, "REG_Name", $p), getNews($con, "NEW_Content", $p) );
		$defaultRegion = getRegionID($con, $defaults[2]);
				
		
		$form  = '<div id="manageNews" >';
        $form .= '<form method="POST" action="manageNews.php?action=modify&id='.$p.'">'."\n";

		$form .= '<input type="hidden" name="newsID" value='.$p.' />';

		$form .= '<label>Title:</label>';
		$form .= '<input type="text" value="'.$defaults[0].'" name="title" />'."\n";
		$form .= '<br /><br />';
		
		$form .= '<label>Date:</label>';
		$form .= '<input type="date" value="'.$defaults[1].'" name="newsDate" />';
		$form .= '<br /><br />';
		
		$form .= '<label>Region:</label>';
		$form .= '<select name="regID"><span>'.selectElementRegionOption($con, $defaultRegion).'</span></select>';
		$form .= '<br /><br />';
		
		$form .= '<label>Comments: </label>';
		$form .= '<textarea class="textarea" name="comments">';
		$form .= $defaults[3];
		$form .= '</textarea>';
		$form .= '<br /><br />';
		
		$form .= '<input type="submit" name="modifyNews" class="button" value="Submit Changes" />';
		
		$form .= '</form>';
		$form .= '</div>';
		
		
		return $form;
	}
	
	function deleteNewsFlashForm($con){
		$p = $_GET['id'];
				
		$defaults = array( getNews($con, "NEW_Title", $p), getNews($con, "NEW_Date", $p), getNews($con, "REG_Name", $p), getNews($con, "NEW_Content", $p) );
				
		
		$form  = '<div id="manageNews" >';
        $form .= '<form method="POST" action="manageNews.php?action=delete&id='.$p.'">'."\n";

		$form .= '<input type="hidden" name="newsID" value='.$p.' />';
		

		$form .= '<label>Title:</label>';
		$form .= '<span>'.$defaults[0].'</span>';
		$form .= '<br /><br />';
		
		$form .= '<label>Date:</label>';
		$form .= '<span>'.$defaults[1].'</span>';
		$form .= '<br /><br />';
		
		$form .= '<label>Region:</label>';
		$form .= '<span>'.$defaults[2].'</span>';
		$form .= '<br /><br />';
		
		$form .= '<label>Reason: </label>';
		$form .= '<textarea class="textarea" name="reason">';
		$form .= '</textarea>';
		$form .= '<br /><br />';
		
		$form .= '<input type="submit" name="deleteNews" class="button" value="Delete News Flash" />';
		
		$form .= '</form>';
		$form .= '</div>';
		
		
		return $form;  
	}
	
	function getRegionID($con, $regName){
		$regID = "";
		
		$sql = "SELECT REG_ID ";
		$sql .= "FROM region ";
		$sql .= 'WHERE REG_Name = "'.$regName.'"';
		
		
		$query = mysqli_query($con, $sql);
		while($row = mysqli_fetch_array($query)){
			$regID = $row['REG_ID'];
		}
		
		return $regID;
	}
	
	/**
	 * FUNCTION YET TO BE STARTED
	*/
	function modifyNews($con, $newsID, $title, $date, $content, $regID){
		global $createNews;
		global $modifyNews;
		
		$title = mysqli_real_escape_string($con, $title);
		$content = mysqli_real_escape_string($con, $content);
		
		$sql  = "UPDATE news ";
		$sql .= 'SET NEW_Title = "'.$title.'", NEW_Date = "'.$date.'", NEW_Content = "'.$content.'" ';
		$sql .= 'WHERE NEW_ID = "'.$newsID.'" ';
		
		$sql2  = "UPDATE news_region_assc ";
		$sql2 .= "SET Region_REG_ID = ".$regID." ";
		$sql2 .= "WHERE News_NEW_ID = ".$newsID." ";
		
		
			if(!mysqli_query($con, $sql)){
				die('Error: $SQL '.mysqli_error($con));
				
			}
			if(!mysqli_query($con, $sql2)){
				die('Error: $SQL2'.mysqli_error($con));
				
			}
		
		
	}
	
	function deleteNews($con, $reason, $newsID){
		$sql  = "UPDATE news ";	
		$sql .= 'SET NEW_Reason_for_Del = "'.$reason.'" ';
		$sql .= "WHERE NEW_ID = '".$newsID."' ";
		
		if(!mysqli_query($con, $sql)){
			die('Error: '.mysqli_error($con));
		}
	}
			
	/**
	* Retrieves news data from the databse and prepares it for display within the
	* Manage News page.
	* @author Tylor Faoro
	*
	* @param object $con The database object
	* @param string $column The desired column name from the DB (same column labels from DB)
	* @param int    $p The integer ID number to ensure proper news article opened for modification
	*
	* @return mixed $results Results from the query to be used to modify current information within the DB
	*/
	function getNews($con, $column, $p){
	static $results;

		$sql  = "SELECT ".$column." ";
		$sql .= "FROM news";
		$sql .= " "; // Space for query to help code readibility
		$sql .= "JOIN News_Region_Assc ";
		$sql .= "ON News_Region_Assc.News_NEW_ID = news.NEW_ID ";
		$sql .= "JOIN region ";
		$sql .= "ON News_Region_Assc.Region_REG_ID = region.REG_ID ";
		$sql .= 'WHERE NEW_ID = '.$p;
				
		$column = mysqli_real_escape_string($con, $column);
		$query = mysqli_query($con, $sql);
				
		while($data = mysqli_fetch_array($query)){
			$results = $data[$column];
		}		
		return $results;		
	}
		
	/**
	 * Retrieves the Region name and Region ID from the database, places the Name value as an option within a Form Select
	 * element. Assigns the Region ID as the return value of the form select option element.
	 * @author Tylor Faoro
	 *
	 * @param Object $con The database connection object
	 * @return static mixed $data The options which can be selected from the Region select form element.
	*/
	function selectElementRegionOption($con, $default = NULL){
		static $data;
		 
		$sql  = "SELECT * ";
		$sql .= "FROM region ";
						
		$query = mysqli_query($con, $sql);
		
		$results = array();
			while($row = mysqli_fetch_array($query)){
				$results[] = $row;
				if($row['REG_ID'] != 99){
					if($default != NULL && $default == $row['REG_ID']){
						$data .= '<option selected = "selected" value="'.$default.'">'.$row['REG_Name'].'</option>';
					}
					else{
						$data .= '<option value='.$row['REG_ID'].'>'.$row['REG_Name'].'</option>';
					}
				}
				
			}
			return $data;	
	}
	
	/**
	 * Draws out the default page Table, which will show all desired news entries into the database.
	 * This function will dynamically assign ID variables to all necessary links within the table.
	 * @author Tylor Faoro
	 *
	 * @param Object $con The Database connection object
	 *
	 * @return none
	*/
	function drawNewsTable($con){
		global $userName;
		$newsID = "";
		
		$sql = 'SELECT * ';
		$sql .= 'FROM news';
		
		$query = mysqli_query($con, $sql);
		
		echo '<table class="tableCenter">';
		echo '<tr>';
		echo '<th>News Title</th>';
		echo '<th>News Date</th>';
		echo '<th>News Region</th>';
		echo '<th>News Poster</th>';
						
		while($data = mysqli_fetch_array($query)){
			$newsID = $data['NEW_ID']; // used to get region name using Junction Table
			
			if($data['NEW_Reason_for_Del'] == NULL){
				echo '<tr>';
				echo '<td>'.$data['NEW_Title'].'</td>';
				echo '<td>'.$data['NEW_Date'].'</td>';			
				echo '<td>';
						 echo getRegionName($con, $newsID); // Calls getRegionName() function to determine name of Region for news					 
				echo '</td>';
				echo '<td>'.getNewsPosterName($con, $newsID).'</td>';
				echo '<td class="hiddenEffects">'; 
				echo '<div class="helperTray">';
				echo '<a href="manageNews.php?action=view&id='.$data['NEW_ID'].'">'.IMG("view2.gif", "View News Entry").'</a>';
				echo '<a href="manageNews.php?action=modify&id='.$data['NEW_ID'].'">'.IMG("edit.gif", "Edit News Entry").'</a>';			
				echo '<a href="manageNews.php?action=delete&id='.$data['NEW_ID'].'">'.IMG("delete.gif", "Delete News Entry").'</a>';
				echo '</div>';
				echo '</td>';
				echo '</tr>';
			}
			
		}		
		echo '</table>';					
	}
	
	/**
	 * Queries the databse for the region name associated with the region ID given through 
	 * the creation of a news flash. The query narrows down the search by searching for the newsID
	 * of the news flash just posted.
	 * @author Tylor Faoro
	 *
	 * @param Object $con The Database connection object
	 * @param Int    $newsID The newsflash ID to filter the search by.
	*/
	function getRegionName($con, $newsID){
		$results = "";
						
		$sql = "SELECT REG_Name ";
		$sql .= "FROM region ";
		$sql .= 'JOIN News_Region_Assc ';
		$sql .= 'ON region.REG_ID = News_Region_Assc.Region_REG_ID ';
		$sql .= 'JOIN news ';
		$sql .= 'ON news.NEW_ID = News_Region_Assc.News_NEW_ID ';
		$sql .= 'WHERE news.NEW_ID = '.$newsID;
						
		$subQuery = mysqli_query($con, $sql);
		while($row = mysqli_fetch_array($subQuery)){
			$results = $row['REG_Name'];
			
		}
		return $results;
								
	}
	
	/**
	 * Simply returns the current date and time
	 * 
	 * @return string $date A (Y/M/D H:M:S) datestring
	*/
	function currTimeDate(){		
		$date = date('Y-m-d H:i:s');
		return $date;		
	}
	
	function getNewsPosterName($con, $newsID){
		$name = "";
		
		$pID = getPosterID($con, $newsID);
		
		$sql  = "SELECT USE_Fname, USE_Lname ";
		$sql .= 'FROM User ';
		$sql .= 'WHERE Use_ID = "'.$pID.'"';
		
		$query = mysqli_query($con, $sql);
		while ($row = mysqli_fetch_array($query)){
			$name = $row['USE_Fname']." ".$row['USE_Lname'];	
		}
		
		return $name;
		
		
	}
	
	function getPosterID($con, $newsID){
		$posterID = "";
		
		$sql = "SELECT User_USE_ID ";
		$sql .= "FROM news ";
		$sql .= "WHERE NEW_ID = ".$newsID." ";
		
		$query = mysqli_query($con, $sql);
		while($row = mysqli_fetch_array($query)){
			$posterID = $row['User_USE_ID'];
		}
		

		return $posterID;
			
	}	

?>