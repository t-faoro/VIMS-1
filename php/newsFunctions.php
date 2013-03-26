<?php
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
	function setNews($con, $title, $date, $type, $comments, $regID, $uID){
		global $error;		
		
		$sql  = 'INSERT INTO news ';
		$sql .= '(NEW_Title, NEW_Date, NEW_Type, NEW_Content, User_USE_ID)';
		$sql .= ' VALUES(';
		$sql .= " '".$title."', ";
		$sql .= " '".$date."', ";
		$sql .= " '".$type."', ";
		$sql .= " '".$comments."', ";
		$sql .= " '".$uID."' ";
		$sql .= ')';
		
		if(!mysqli_query($con, $sql)){
			die('Error: '.mysqli_error($con));
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
      echo '<script>';
	  echo '$(function() {';
	  echo '$( "#datepicker" ).datepicker({ dateFormat: "yy-mm-dd" }).val();';
	  echo '});';
	  echo '</script>';
	  
	  echo '<head>';
	  echo '<link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css">';
	  echo '</head>';
	  
		$form  = '<div id="manageNews" >';
        $form .= '<form method="POST" action="manageNews.php?action=create">'."\n";
		$form .= '<label>Title:</label>';
		$form .= '<input type="text" value="" name="title" />'."\n";
		$form .= '<br /><br />';
		$form .= '<label>Date:</label>';
		$form .= '<input type="text" id="datepicker" value="'.currDate().'" name="newsDate" />';
		$form .= '<br /><br />';
		$form .= '<label>News Type:</label>';		
		$form .= '<select name="newsType">';
		$form .= '<option selected="selected">-- Select One --</option>';
		$form .= '<option value="1">Industry</option>';
		$form .= '<option value="2">Clubwatch News</option>';
		$form .= '</select>';
		$form .= '<br /><br />';
		$form .= '<label>Region:</label>';
		$form .= '<select name="regID"><span>'.selectElementRegionOption($con).'</span></select>';
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
	  
	  
	  echo '<script>';
	  echo '$(function() {';
	  echo '$( "#datepicker" ).datepicker({ dateFormat: "yy-mm-dd" }).val();';
	  echo '});';
	  echo '</script>';
	  echo '<head>';
	  echo '<link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css">';
	  echo '</head>';
		
		$p = $_GET['id'];
				
		$defaults = array( getNews($con, "NEW_Title", $p), getNews($con, "NEW_Date", $p), getNews($con, "NEW_Type", $p), getNews($con, "REG_Name", $p), getNews($con, "NEW_Content", $p));
		$defaultRegion = getRegionID($con, $defaults[3]);
		$type = "";	
	
		$form  = '<div id="manageNews" >';
        $form .= '<form method="POST" action="manageNews.php?action=modify">'."\n";
		$form .= '<input type="hidden" name="newsID" value='.$p.' />';
		$form .= '<label>Title:</label>';
		$form .= '<input type="text" value="'.$defaults[0].'" name="title" />'."\n";
		$form .= '<br /><br />';
		
		$form .= '<label>Date:</label>';
		$form .= '<input type="text" id="datepicker" value="'.$defaults[1].'" name="newsDate" />';
		$form .= '<br /><br />';
		
		$form .= '<label>News Type:</label>';		
		
		// Hacked in Select to show one currently residing within the database
		$form .= '<select name="newsType">';		
			if($defaults[2] == 1){ // Industry News
				$form .= '<option value="1" selected="selected">Industry</option>';
				$form .= '<option value="2">Clubwatch News</option>';
			}
			elseif($defaults[2] == 2){ // Clubwatch News
				$form .= '<option value="1">Industry</option>';
				$form .= '<option value="2" selected="selected">Clubwatch News</option>';
			}
			else{ // No Type Chosen (value = 0)
				$form .= '<option value="0" selected="selected">-- Select One --</option>';
				$form .= '<option value="1">Industry</option>';
				$form .= '<option value="2">Clubwatch News</option>';	
			}			
		$form .= '</select>';
		$form .= '<br /><br />';
		
		$form .= '<label>Region:</label>';
		$form .= '<select name="regID" value="">';
		$form .= '<span>'.selectElementRegionOption($con, $defaultRegion).'</span>';
		$form .= '</select>';
		$form .= '<br /><br />';
		
		$form .= '<label>Comments: </label>';
		$form .= '<textarea class="textarea" name="comments">';
		$form .= $defaults[4];
		$form .= '</textarea>';
		$form .= '<br /><br />';
		
		$form .= '<input type="submit" name="modifyNews" class="button" value="Submit Changes" />';
		
		$form .= '</form>';
		$form .= '</div>';
		
		return $form;
	}

	
	
	/**
	 * Draws the Delete News Form with all appropriate settings in place. The difference between
	 * this form and modifyNewsForm() is all fields will be populated with default data but data is non-editable.
	 * @author Tylor Faoro
	 *
	 * @param Object $con The Database connection object
	 *
	 * @return mixed $form A built form with necessary elements to modify a news flash entry.
	*/	
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
	
	/**
	 * Sends an update query to the database, contains any necessary changes that need to be made
	 * to the data within the database.
	 * @author Tylor Faoro
	 * 
	 * @param Object $con The database connection object
	 * @param Integer $newsID The unique id from the database to determine what news article is being worked with
	 * @param String $title The title information
	 * @param Date 	 $date The date of the news flash
	 * @param String $content The body content of the news flash.
	 * @param Integer $regID The id of the region the news flash is associated with.
	 * 
	 * @return nothing
	*/
	function modifyNews($con, $newsID, $title, $date, $type, $content, $regID){
		global $createNews;
		global $modifyNews;
		
		
		$title = mysqli_real_escape_string($con, $title);
		$content = mysqli_real_escape_string($con, $content);
		
		$sql  = "UPDATE news ";
		$sql .= 'SET NEW_Title = "'.$title.'", NEW_Date = "'.$date.'", NEW_Type = "'.$type.'", NEW_Content = "'.$content.'" ';
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
	
	/**
	 * Doesn't actually delete news. Simply puts data into the Reason_For_Delete column in the database
	 * which will cause the tuple to fail the "Is NULL" test and not be displayed within the news table.
	 * @author Tylor Faoro
	 * 
	 * @param Object $con The Database connection object
	 * @param String $reason The reason for deleting a row. Must have data in it otherwise delete will not happen
	 * @param Integer $newsID The unique value from the database assigned to each specific News Article.
	 * 
	 * @return Nothing
	 */
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
		$sql .= "ORDER BY reg_id DESC";
						
		$query = mysqli_query($con, $sql);
		
		$results = array();
		
		if($default == NULL){
			$data .= '<option value="0">-- Select One --</option>';
		}
		
			while($row = mysqli_fetch_array($query)){
				$results[] = $row;
	
					if($default != NULL && $default == $row['REG_ID']){
						$data .= '<option selected = "selected" value="'.$default.'">'.$row['REG_Name'].'</option>';
					}
					else{						
						$data .= '<option value='.$row['REG_ID'].'>'.$row['REG_Name'].'</option>';
					}
				
				
				
			}
			return $data;		
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
	 * Draws out the default page Table if a month IS selected, which will show all desired news entries into the database.
	 * This function will dynamically assign ID variables to all necessary links within the table.
	 * @author Tylor Faoro
	 *
	 * @param Object $con The Database connection object
	 *
	 * @return none
	*/
	function drawNewsTable($con){
		global $userName;

		$month = $_POST['month'];
		
		$newsID = "";
		
		$sql = 'SELECT * ';
		$sql .= 'FROM news ';
		$sql .= "ORDER BY NEW_Date DESC ";
		
		
		$query = mysqli_query($con, $sql);
		echo '<table class="tableCenter">';
		echo '<tr>';
		echo '<th>News Title</th>';
		echo '<th>News Date</th>';
		echo '<th>News Region</th>';
		echo '<th>News Poster</th>';
		echo '</tr>';
						
		while($data = mysqli_fetch_array($query)){
			$newsID = $data['NEW_ID']; // used to get region name using Junction Table
			
			if($data['NEW_Reason_for_Del'] == NULL){
				if ( date("M", strtotime($data['NEW_Date'])) == $month){
								
					echo '<tr>';
					echo '<td>'.$data['NEW_Title'].'</td>';
					echo '<td>'.date('M-d-Y', strtotime($data['NEW_Date'])).'</td>';			
					echo '<td>';
							 echo getRegionName($con, $newsID); // Calls getRegionName() function to determine name of Region for news					 
					echo '</td>';
					echo '<td>'.getNewsPosterName($con, $newsID).'</td>';
					echo '<td class="hiddenEffects">'; 
					echo '<div class="helperTray">';
					//:: Took out View news case because action is being completed on the Dashboard.
					//echo '<a href="manageNews.php?action=view&id='.$data['NEW_ID'].'">'.IMG("view2.gif", "View News Entry").'</a>';
					echo '<a href="manageNews.php?action=modify&id='.$data['NEW_ID'].'">'.IMG("edit.gif", "Edit News Entry").'</a>';			
					echo '<a href="manageNews.php?action=delete&id='.$data['NEW_ID'].'">'.IMG("delete.gif", "Delete News Entry").'</a>';
					echo '</div>';
					echo '</td>';
					echo '</tr>';
				}
			

			}
		}		
		echo '</table>';					
	}
	
	 
	 /**
	 * Draws out the default page Table if a month is NOT selected, which will show all desired news entries into the database.
	 * This function will dynamically assign ID variables to all necessary links within the table.
	 * @author Tylor Faoro
	 *
	 * @param Object $con The Database connection object
	 *
	 * @return none
	*/	
	function drawAllNewsTable($con){
		global $userName;
		date_default_timezone_set('America/Edmonton');
		
		$newsID = "";
		
		$sql = 'SELECT * ';
		$sql .= 'FROM news ';
		$sql .= "ORDER BY NEW_Date DESC ";
		
		
		$query = mysqli_query($con, $sql);
		echo '<table class="table_center">';
		echo '<tr>';
		echo '<th>News Title</th>';
		echo '<th>News Date</th>';
		echo '<th>News Region</th>';
		echo '<th>News Poster</th>';
		echo '</tr>';
						
		while($data = mysqli_fetch_array($query)){
			$newsID = $data['NEW_ID']; // used to get region name using Junction Table
			
			if($data['NEW_Reason_for_Del'] == NULL){								
					echo '<tr>';
					echo '<td>'.$data['NEW_Title'].'</td>';
					echo '<td>'.date('M-d-Y', strtotime($data['NEW_Date'])).'</td>';			
					echo '<td>';
							 echo getRegionName($con, $newsID); // Calls getRegionName() function to determine name of Region for news					 
					echo '</td>';
					echo '<td>'.getNewsPosterName($con, $newsID).'</td>';
					echo '<td class="hiddenEffects">'; 
					echo '<div class="helperTray">';
					//:: Took out View news case because action is being completed on the Dashboard.
					//echo '<a href="manageNews.php?action=view&id='.$data['NEW_ID'].'">'.IMG("view2.gif", "View News Entry").'</a>';
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
	 * Creates a HTML Select Form Element to display months. Allows user to filter news by month.
	 * @author Tylor Faoro
	 * 
	 * @param none
	 * @return Mixed $markUp HTML Markup for he select box.
	 */
	function updateNewsTable(){
		
		$markUp  = '<form method="post" action="manageNews.php?action=default">';	
		$markUp .= '<select name="month">';
		$markUp .= '<option value=0 selected="selected">Select a Month</option>';
		$markUp .= '<option value="Jan">January</option>';
		$markUp .= '<option value="Feb">February</option>';
		$markUp .= '<option value="Mar">March</option>';
		$markUp .= '<option value="Apr">April</option>';
		$markUp .= '<option value="May">May</option>';
		$markUp .= '<option value="Jun">June</option>';
		$markUp .= '<option value="Jul">July</option>';
		$markUp .= '<option value="Aug">August</option>';
		$markUp .= '<option value="Sep">September</option>';
		$markUp .= '<option value="Oct">October</option>';
		$markUp .= '<option value="Nov">November</option>';
		$markUp .= '<option value="Dec">December</option>';
		$markUp .= '</select>';
		$markUp .= '<input type="submit" name="monthSelect", value="Go" />';
		$markUp .= '</form>';
		
		return $markUp;
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
						
		$sql = "SELECT REG_Name ";
		$sql .= "FROM region ";
		$sql .= 'JOIN News_Region_Assc ';
		$sql .= 'ON region.REG_ID = News_Region_Assc.Region_REG_ID ';
		$sql .= 'JOIN news ';
		$sql .= 'ON news.NEW_ID = News_Region_Assc.News_NEW_ID ';
		$sql .= 'WHERE news.NEW_ID = '.$newsID;
						
		$subQuery = mysqli_query($con, $sql);
		while($row = mysqli_fetch_array($subQuery)){
			echo $row['REG_Name'];
		}						
	}
	
	/**
	 * Simply returns the current date and time
	 * 
	 * @return string $date A (Y/M/D H:M:S) datestring
	*/
	function currDate(){		
		$date = date('Y-m-d');
		return $date;		
	}
	
	
	/**
	 * Retrieves the news posters name from the database using the Unique $newsID
	 * of the current news article. Calls a nested function to get the news posters 
	 * user ID.
	 * DEPENDANT: getPosterID($con, $newsID)
	 * @author Tylor Faoro
	 * 
	 * @param Object $con The database connection object
	 * @param Integer $newsID The unique news flash ID from the database.
	 * 
	 * @return String $name The news flash's name.
	 */
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
	
	/**
	 * Retrieves the news posters user ID from the database. Written to work 
	 * directly with getNewsPosterName().
	 * @author Tylor Faoro
	 * 
	 * @param Object $con The database connection object
	 * @param Integer $newsID The unique ID of the current news article from the database
	 * 
	 * @return Integer $posterID The News Poster ID of the News Article
	 */
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
	
	/**
	 * Retrieves the users ID from the database based off of their username.
	 * @author Tylor Faoro
	 * 
	 * @param Object $con The Database connection object
	 * @param String $uName The username to retieve User ID for.
	 * 
	 * @return Integer $uID The users ID based off of the username provided.
	 */
	function getUserID($con, $uName){
		$uID = "";
		
		$sql  = 'SELECT USE_ID ';
		$sql .= 'FROM user ';
		$sql .= 'WHERE USE_Name = "'.$uName.'" ';
		
		$query = mysqli_query($con, $sql);


		while($row = mysqli_fetch_array($query)){
			$uID = $row['USE_ID'];
		}
		
		return $uID;
		
	}
  ?>