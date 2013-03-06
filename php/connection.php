<?php
// ============================================================================
/**
 * Class containing database connection parameters
 * Instantiation initializes the variables
 * @author James P. Smith March 2013
 */
	

class Connection{
	private $host;
	private $user;
	private $pswd;
	private $db;
	
// ============================================================================	
	/*
		Constructor method initializes variables
	*/
	public function Connection()
	{
		$this->host = "localhost";			// host name
		$this->user = "vimsfrontend";		// user to connect as
		$this->pswd = "poweroverwhelming";	// user password
		$this->db	= "vims";				// database name
	}
// ============================================================================	
	/*
		Class method for connecting to the database
	*/
	function connect()
	{
		
		$con=mysqli_connect("$this->host","$this->user","$this->pswd","$this->db");
		// Check connection
		if (mysqli_connect_errno())
		{
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		return $con;
	}
	
}
?>