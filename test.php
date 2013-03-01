<?php

	include "connection.php";
	include "userFunctions.php";
	include "venueFunctions.php";
	
	//Leave in to verify page is operating
	echo "\n<h2>Page Working</h2>";
    // Establish a new connection
	$myCon = new Connection();	// Do once for each page loaded
	
// ============================================================================
    if(false){
        // Define variables to use
        $username = "jwerre";
        $pswd     = "justinpw";
        $user     = 101;
        //_________________________________________________________________________

	    $con = $myCon->connect();	// Do once for each sql function used
        
        $sql = userRead( "jwerre", $pswd, 101, $con);

        if($sql=="error") echo "Error";
        else{
	        $result = mysqli_query($con, $sql);
	        while($row = mysqli_fetch_array($result))
	        {
		        echo "<br>\n<h3>User Found:</h3>\n";
		        echo $row['USE_Fname'] . " " . $row['USE_Lname'];
		        echo "<br />";
	        }
        }
	
	    // close the connection
	    mysqli_close($con);				// Do once after each sql function
    }
// ============================================================================
    if(FALSE){
        // Define variables to use
        $username    = "sgraham";
        $pswd        = "stephenpw";
        $Fname       = "Stephen";
        $Lname       = "Graham";
        $currentuser = 1001;
	    //_________________________________________________________________________

        echo "<h3>Create user function</h3>";
	    $con = $myCon->connect();
	
	    $sql = userCreate($username, $pswd, $Fname, $Lname, $currentuser, $con);
	
        if($sql=="error") echo "Error";
        else {
	        $result = mysqli_query($con, $sql);
	        if($result) echo "<br>Successfully created user in VIMS<br>\n";
	        else 		echo "<br>User creation failed<br>\n";
        }

	    mysqli_close($con);
    }
// ============================================================================	
    if(FALSE){
        // Define variables to use
        $field[0] 	= "Fname";
		$field[1] 	= "Lname";
		$field[2]	= "Passwd";
        $content[0] = "Jams";
		$content[1] = "Smit";
		$content[2] = "jamespw";
        $username 	= "jpsmith";
	    //_________________________________________________________________________

	    echo "<h3>Update Function</h3>";
	    $con = $myCon->connect();
	    
    
	    $sql = userUpdate($field, $content, $username, $con);

        if($sql=="error") echo "Error";
        else {
	        $result = mysqli_query($con, $sql);
	        if($result) echo "<br>Successfully updated user in VIMS<br>\n";
	        else 		echo "<br>User update failed<br>\n";
        }
	
	    mysqli_close($con);
	}
	
// ============================================================================
	if(TRUE){
		// Define variables to use
		
		//_________________________________________________________________________
		
		$con = $myCon->connect();	// Do once for each sql function used
        
        $sql = userList(101);

        if($sql=="error") echo "Error";
        else{
	        $result = mysqli_query($con, $sql);
	        while($row = mysqli_fetch_array($result))
	        {
		        echo "<br>\n<h3>Venue Found:</h3>\n";
		        echo $row['USE_Name'];
		        echo "<br />";
	        }
        }
	
	    // close the connection
	    mysqli_close($con);	
	}
// ============================================================================
    if(FALSE){
        // Define variables to use
        $venueDetails[0] = "Club Awesome";	// VEN_Name
        $venueDetails[1] = "1A";			// VEN_Unit_Addr
        $venueDetails[2] = "300";			// VEN_St_Addr
        $venueDetails[3] = "Lethbridge";	// VEN_City
        $venueDetails[4] = "T1H1T1";		// VEN_Pcode
        $venueDetails[5] = "4033203333";	// VEN_Phone
        $venueDetails[6] = "Bob";			// VEN_Liason
        $venueDetails[7] = 101;				// Region_REG_ID
        
	    //_________________________________________________________________________

        echo "<h3>Create user function</h3>";
	    $con = $myCon->connect();
	
	    $sql = venueCreate($venueDetails, $con);
	
        if($sql=="error") echo "Error";
        else {
	        $result = mysqli_query($con, $sql);
	        if($result) echo "<br>Successfully created user in VIMS<br>\n";
	        else 		echo "<br>User creation failed<br>\n";
        }

	    mysqli_close($con);
    }
	
// ============================================================================	
    if(FALSE){
        // Define variables to use
        $field[0] 	= "Name";
		$field[1] 	= "Unit_Addr";
		$field[2]	= "Region_ID";
        $content[0] = "Mega Party";
		$content[1] = "12b";
		$content[2] = "104";
        $venueID 	= 102;
	    //_________________________________________________________________________

	    echo "<h3>Update Function</h3>";
	    $con = $myCon->connect();
	    
    
	    $sql = venueUpdate($field, $content, $venueID, $con);

        if($sql=="error") echo "Error";
        else {
	        $result = mysqli_query($con, $sql);
	        if($result) echo "<br>Successfully updated venue in VIMS<br>\n";
	        else 		echo "<br>Venue update failed<br>\n";
        }
	
	    mysqli_close($con);
	}
?>