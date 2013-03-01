<?php

	include "connection.php";
	include "userFunctions.php";
	
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
    if(true){
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
    if(false){
        // Define variables to use
        $field    = "Fname";
        $value    = "Phteven";
        $username = "sgraham";
	    //_________________________________________________________________________

	    echo "<h3>Update Function</h3>";
	    $con = $myCon->connect();
	    echo "Update First Name<br>";
    
	    $sql = userUpdate($field, $value, $username, $con);

        if($sql=="error") echo "Error";
        else {
	        $result = mysqli_query($con, $sql);
	        if($result) echo "<br>Successfully updated user in VIMS<br>\n";
	        else 		echo "<br>User update failed<br>\n";
        }
	
	    mysqli_close($con);
	}
?>