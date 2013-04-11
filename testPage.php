<?php 

	include_once "php/config.php";
    $con = new Connection();
    $con = $con->connect();
    if (mysqli_errno($con)){
        
		echo "Connection to Database Failed.";
        
    }
	$results = array();
	$results = getNews($con);
	
	$value = "";
	echo "hi";
	echo "<br /><br /><br />";
	 //getNews($con);
	 foreach($results as $someting){
		echo $results;	 
	}

	

	
	
		function getNews($con){
	 	 
		
		//$sql = "SELECT * ";
		$sql  = "SELECT news.NEW_Title, news.NEW_Content, region.REG_ID ";
		$sql .= "FROM news";
		//$sql .= " WHERE NEW_ID = ".$p;
		$sql .= " "; // Space for query to help code readibility
		$sql .= "JOIN News_Region_Assc ";
		$sql .= "ON News_Region_Assc.News_NEW_ID = news.NEW_ID ";
		$sql .= "JOIN region ";
		$sql .= "ON News_Region_Assc.Region_REG_ID = region.REG_ID";
		
		
		//$column = mysqli_real_escape_string($con, $column);
		
		$query = mysqli_query($con, $sql);
		
		/*foreach($results as $value){
			echo $value[$results];	
		}*/
		while($data = mysqli_fetch_array($query)){
		   $results  = $data['NEW_Title'].', ';
		   $results .= $data['NEW_Content'].', ';
		   $results .=$data['REG_ID'];// = $data[$column];	
		   echo "<br />";
		}
		
		return $results;
	}


?>